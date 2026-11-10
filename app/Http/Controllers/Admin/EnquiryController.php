<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Enquiry;
use App\Models\EnquiryNote;
use App\Support\SimpleXlsx;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::latest()->get();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    /**
     * Base query for the QR enquiries page, with every filter applied.
     * Shared by the list, the Excel export and the PDF report.
     */
    protected function qrQuery(Request $request)
    {
        $hasSource = Schema::hasColumn('enquiries', 'source');
        $hasHowFound = Schema::hasColumn('enquiries', 'how_found');

        $query = Enquiry::with('category', 'product')->latest();
        $hasSource ? $query->fromQr() : $query->whereRaw('1 = 0');

        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(function ($q) use ($term, $hasHowFound) {
                $q->where('customer_name', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('interested_product', 'like', $term)
                    ->orWhere('enquiry_reference', 'like', $term);
                if ($hasHowFound) {
                    $q->orWhere('how_found', 'like', $term);
                }
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($hasHowFound && $request->filled('how_found')) {
            $query->where('how_found', $request->how_found);
        }
        if ($request->filled('product')) {
            $query->where('interested_product', 'like', '%'.$request->product.'%');
        }
        if ($request->filled('location')) {
            $query->where('city', 'like', '%'.$request->location.'%');
        }
        if ($from = $this->filterDate($request->from)) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $this->filterDate($request->to)) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    protected function filterDate($value): ?string
    {
        if (! $value) {
            return null;
        }
        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Human readable list of the active filters (shown on reports). */
    protected function qrFilterSummary(Request $request): array
    {
        $summary = [];
        if ($from = $this->filterDate($request->from)) {
            $summary['From'] = Carbon::parse($from)->format('d M Y');
        }
        if ($to = $this->filterDate($request->to)) {
            $summary['To'] = Carbon::parse($to)->format('d M Y');
        }
        foreach (['status' => 'Status', 'how_found' => 'How found us', 'product' => 'Product', 'location' => 'Location', 'search' => 'Search'] as $key => $label) {
            if ($request->filled($key)) {
                $summary[$label] = $request->input($key);
            }
        }

        return $summary;
    }

    public function qr(Request $request)
    {
        $hasSource = Schema::hasColumn('enquiries', 'source');
        $hasHowFound = Schema::hasColumn('enquiries', 'how_found');
        $howFoundOptions = Enquiry::HOW_FOUND_OPTIONS;
        $query = $this->qrQuery($request);

        $enquiries = $query->paginate(20)->withQueryString();
        $qrUrl = route('enquiry.qr');
        $statuses = ['New', 'Contacted', 'Follow-up', 'Quotation Sent', 'Converted', 'Closed'];
        $statusCounts = array_fill_keys($statuses, 0);
        $trend = [];

        if ($hasSource) {
            foreach (Enquiry::fromQr()->selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status') as $st => $c) {
                $statusCounts[$st] = (int) $c;
            }
            $daily = Enquiry::fromQr()
                ->where('created_at', '>=', today()->subDays(6))
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->groupBy('d')
                ->pluck('c', 'd');
        }

        for ($i = 6; $i >= 0; $i--) {
            $day = today()->subDays($i);
            $trend[] = ['label' => $day->format('D'), 'count' => (int) (($daily ?? collect())[$day->toDateString()] ?? 0)];
        }

        $total = array_sum($statusCounts);
        $stats = [
            'total' => $total,
            'new' => $statusCounts['New'],
            'today' => end($trend)['count'],
            'converted' => $statusCounts['Converted'],
            'rate' => $total ? round($statusCounts['Converted'] / $total * 100) : 0,
            'week' => array_sum(array_column($trend, 'count')),
        ];

        $filterCount = count($this->qrFilterSummary($request)) - ($request->filled('status') ? 1 : 0);

        return view('admin.enquiries.qr', compact('enquiries', 'qrUrl', 'stats', 'hasSource', 'statuses', 'statusCounts', 'trend', 'hasHowFound', 'howFoundOptions', 'filterCount'));
    }

    /**
     * Download the filtered QR enquiries as an Excel (.xlsx) file.
     */
    public function qrExportExcel(Request $request)
    {
        $enquiries = $this->qrQuery($request)->get();
        $filename = 'qr-enquiries-'.now()->format('Y-m-d_His');

        $rows = [['S.No', 'Reference', 'Date', 'Time', 'Customer Name', 'Phone', 'Location', 'How Found Us', 'Product', 'Category', 'Status']];
        foreach ($enquiries as $i => $e) {
            $rows[] = [
                $i + 1,
                $e->enquiry_reference,
                $e->created_at?->format('d-m-Y') ?? '',
                $e->created_at?->format('h:i A') ?? '',
                $e->customer_name,
                $e->phone,
                $e->city ?? '',
                $e->how_found ?? '',
                $e->interested_product ?? $e->product?->name ?? '',
                $e->product_category ?? $e->category?->name ?? '',
                $e->status,
            ];
        }

        $filters = collect($this->qrFilterSummary($request))->map(fn ($v, $k) => $k.': '.$v)->implode('   |   ');
        $titleRows = [
            CompanySetting::valueFor('company_name', 'AVANEEIS TRADERS').' - QR Enquiry Report',
            'Generated: '.now()->format('d M Y, h:i A').'   |   Total: '.$enquiries->count().($filters ? '   |   '.$filters : ''),
            '',
        ];

        if (SimpleXlsx::available()) {
            $path = SimpleXlsx::build($rows, 'QR Enquiries', [6, 14, 12, 10, 26, 16, 26, 24, 30, 20, 15], $titleRows);

            return response()->download($path, $filename.'.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        }

        // Fallback when PHP's zip extension is disabled: CSV (opens in Excel)
        return response()->streamDownload(function () use ($rows, $titleRows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows Tamil/Unicode correctly
            foreach ($titleRows as $t) {
                fputcsv($out, [$t]);
            }
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * PDF report of the filtered QR enquiries.
     * Uses barryvdh/laravel-dompdf when installed, otherwise opens a
     * print-ready page (browser "Save as PDF").
     */
    public function qrExportPdf(Request $request)
    {
        $enquiries = $this->qrQuery($request)->get();
        $filters = $this->qrFilterSummary($request);
        $company = CompanySetting::valueFor('company_name', 'AVANEEIS TRADERS');
        $phone = CompanySetting::valueFor('contact_phone_1', '98423 24074');
        $summary = [
            'total' => $enquiries->count(),
            'byStatus' => $enquiries->countBy('status')->sortDesc(),
            'byHowFound' => $enquiries->countBy(fn ($e) => $e->how_found ?: 'Not specified')->sortDesc(),
        ];
        $filename = 'qr-enquiries-'.now()->format('Y-m-d_His').'.pdf';

        $pdfClass = 'Barryvdh\\DomPDF\\Facade\\Pdf';
        if (class_exists($pdfClass)) {
            $forPdf = true;

            return $pdfClass::loadView('admin.enquiries.qr-report', compact('enquiries', 'filters', 'company', 'phone', 'summary', 'forPdf'))
                ->setPaper('a4', 'landscape')
                ->download($filename);
        }

        $forPdf = false;

        return view('admin.enquiries.qr-report', compact('enquiries', 'filters', 'company', 'phone', 'summary', 'forPdf'));
    }

    public function show(Enquiry $enquiry)
    {
        $enquiry->load('product', 'category', 'subcategory', 'notesList');

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'status' => ['required', 'string', 'max:255'],
        ]);

        $enquiry->status = $request->status;
        $enquiry->save();

        if ($request->filled('note')) {
            EnquiryNote::create([
                'enquiry_id' => $enquiry->id,
                'note' => $request->note,
            ]);
        }

        return back()->with('success', 'Enquiry status updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return back()->with('success', 'Enquiry deleted.');
    }
}
