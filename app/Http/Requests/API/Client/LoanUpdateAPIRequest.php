<?php

namespace App\Http\Requests\API\Client;

use App\Http\Requests\API\BaseAPIRequest;

class LoanUpdateAPIRequest extends BaseAPIRequest
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
            'id'=>'required|integer',
            'status' => 'required|in:approved,pending',
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
            'status.in' => 'Status must approved or pending!',
        ];
    }

}
