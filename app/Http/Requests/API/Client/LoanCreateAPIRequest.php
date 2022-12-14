<?php

namespace App\Http\Requests\API\Client;

use App\Http\Requests\API\BaseAPIRequest;

class LoanCreateAPIRequest extends BaseAPIRequest
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
            'user_id' => 'sometimes|integer',
            'amount' => 'required|integer',
            'currency' => 'required|string',
            'duration' => 'required|integer',
            'first_paydate' => 'required',
        ];
    }

}
