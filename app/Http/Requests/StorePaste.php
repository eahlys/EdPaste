<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaste extends FormRequest
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
            'pasteTitle' => 'max:70',
            'pasteContent' => 'required',
            'pastePassword' => 'required_if:privacy,password',
        ];
    }

    public function messages()
    {
        return [
            'pasteContent.required' => 'Your paste cannot be empty',
            'pastePassword.required_if' => 'Please enter a password',
            'pasteTitle.max' => 'Title must not exceed 70 characters',
            'g-recaptcha-response.required' => 'Captcha required',
            'g-recaptcha-response.captcha' => 'Captcha required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('g-recaptcha-response', 'required|captcha', function ($input) {
            return !\Auth::check();
        });
    }
}
