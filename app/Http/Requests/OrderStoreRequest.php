<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'order_status_id' => 'required|exists:order_statuses,id',
            'marketplace_fee_id' => 'nullable|exists:marketplace_fees,id',
            'discount' => 'nullable|numeric'
        ];
    }
}
