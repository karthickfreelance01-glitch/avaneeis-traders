<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::latest()->get();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function qr(Request $request)
    {
        $hasSource = Schema::hasColumn('enquiries', 'source');

        $query = Enquiry::with('category', 'product')->latest();
        $hasSource ? $query->fromQr() : $query->whereRaw('1 = 0');

        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(fn ($q) => $q->where('customer_name', 'like', $term)
                ->orWhere('phone', 'like', $term)
                ->orWhere('city', 'like', $term)
                ->orWhere('interested_product', 'like', $term)
                ->orWhere('enquiry_reference', 'like', $term));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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

        return view('admin.enquiries.qr', compact('enquiries', 'qrUrl', 'stats', 'hasSource', 'statuses', 'statusCounts', 'trend'));
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
