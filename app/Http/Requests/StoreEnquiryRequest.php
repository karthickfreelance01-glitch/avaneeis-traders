<?php

namespace App\Http\Requests;

use App\Models\Enquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $common = [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'location' => ['required', 'string', 'max:255'],
            'source' => ['nullable', 'in:website,qr'],
        ];

        // QR form: "How found us" dropdown + free-text product
        if ($this->input('source') === 'qr') {
            return $common + [
                'how_found' => ['required', Rule::in(Enquiry::HOW_FOUND_OPTIONS)],
                'product_name' => ['required', 'string', 'max:255'],
            ];
        }

        // Normal website form: category + product dropdowns (unchanged)
        return $common + [
            'category_id' => ['required', 'exists:categories,id'],
            'product_id' => ['required', 'exists:products,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid phone number.',
            'category_id.required' => 'Please select a category.',
            'product_id.required' => 'Please select a product.',
            'how_found.required' => 'Please tell us how you found us.',
            'how_found.in' => 'Please choose an option from the "How did you find us?" list.',
            'product_name.required' => 'Please enter the product you are interested in.',
        ];
    }
}
