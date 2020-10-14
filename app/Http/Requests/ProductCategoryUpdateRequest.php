<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryUpdateRequest extends FormRequest
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
            'parent_id' => 'nullable|numeric',
            'name' => 'required|unique:product_categories,name,'.$this->route('category')->id.'|max:255',
            'slug' => 'required|unique:product_categories,slug,'.$this->route('category')->id.'|max:255|alpha_dash'
        ];
    }
}
