<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseAPIRequest;

class LoginAPIRequest extends BaseAPIRequest
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
            'email' => 'required',
            'password'  => 'required',
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
            'email.required' => 'Email is required!',
            'password.required' => 'Password is required!',
        ];
    }
}
