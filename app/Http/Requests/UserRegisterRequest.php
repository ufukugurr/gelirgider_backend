<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'Lütfen adınızı giriniz.',

            'last_name.required' => 'Lütfen soyadınızı giriniz.',

            'email.required' => 'Lütfen e-posta adresinizi giriniz.',
            'email.email' => 'Lüfen geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'E-posta adresi daha önceden kayıt edilmiş.',

            'password.required' => 'Lütfen geçerli bir şifre giriniz.',
            'password.min' => 'Şifreniz en az 6 karakterden oluşmalıdır.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(sendError($validator->errors()->first(), 422));
    }
}
