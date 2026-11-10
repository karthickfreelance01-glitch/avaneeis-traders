<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;

class CompanySettingController extends Controller
{
    public function index()
    {
        $settings = CompanySetting::allValues();

        return view('admin.settings', compact('settings'));
    }

    public function profile()
    {
        return view('admin.profile', ['admin' => auth('admin')->user()]);
    }

    public function update(Request $request)
    {
        $fields = $request->except('_token', '_method');

        foreach ($fields as $key => $value) {
            CompanySetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }

        return back()->with('success', 'Company settings updated successfully.');
    }
}
