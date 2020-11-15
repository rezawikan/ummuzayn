<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_category_id' => 'required|numeric|exists:product_categories,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'product_status_id' => 'required|numeric|exists:product_statuses,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
            'has_variations' => 'required',
            'variations.*.product_variation_type_id' => 'required_if:has_variations,==,true|numeric|exists:product_variation_types,id',
            'variations.*.variation_name' => 'required_if:has_variations,==,true|string',
            'variations.*.price' => 'required|numeric',
            'variations.*.base_price' => 'required|lt:variations.*.price|numeric',
            'variations.*.weight' => 'required|numeric',
            'variations.*.stock' => 'required|numeric'
        ];
    }
}
