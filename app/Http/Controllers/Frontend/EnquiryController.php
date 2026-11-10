<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Product;

class EnquiryController extends Controller
{
    public function submitForm(?Product $product = null)
    {
        $settings = CompanySetting::allValues();

        return view('frontend.enquiry', compact('product', 'settings'));
    }
}
