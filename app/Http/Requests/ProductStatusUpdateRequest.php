<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStatusUpdateRequest extends FormRequest
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
            'status' => 'required|unique:product_statuses,status,'.$this->route('status')->id.'|max:255|string',
            'slug' => 'required|unique:product_statuses,slug,'.$this->route('status')->id.'|max:255|string'
        ];
    }
}
