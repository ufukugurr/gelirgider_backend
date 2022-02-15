<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
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
            'category_id' => 'required|numeric',
            'currency' => 'required|size:3',
            'amount' => 'required|numeric',
            'description' => 'max:255'
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
            'category_id.required' => 'Lütfen kategori seçiniz.',
            'category_id.numeric' => 'Hatalı kategori.',

            'currency.required' => 'Lütfen para birimini seçiniz.',
            'currency.size' => 'Hatalı para birimi.',

            'amount.required' => 'Lütfen tutar giriniz.',
            'amount.numeric' => 'Hatalı tutar girdiniz.',

            'description.max' => 'Açıklama çok uzun.',
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
