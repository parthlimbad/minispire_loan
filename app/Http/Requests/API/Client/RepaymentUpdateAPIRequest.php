<?php

namespace App\Http\Requests\API\Client;

use App\Http\Requests\API\BaseAPIRequest;

class RepaymentUpdateAPIRequest extends BaseAPIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Parth L.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Parth L.
     */
    public function rules()
    {
        return [
            'loan_id'=> 'required|integer',
            'pay_date' => 'required|date|date_format:Y-m-d',
            'paid_amount' => 'required|integer',
        ];
    }

    /**
     * Message for validation
     *
     * @return array
     * @author Parth L.
     */
    public function messages()
    {
        return [
            'pay_date.date' => 'Pay Date must be a date',
            'pay_date.date_format' => 'Pay Date must be in format: YYYY-MM-DD',
        ];
    }

}
