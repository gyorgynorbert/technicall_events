<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // This is a public form, so anyone is authorized
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone_number' => 'required|string|regex:/^0\d{9}$/',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Kérjük, adja meg a nevét.',
            'name.string' => 'A név szövegből kell álljon.',
            'name.min' => 'A név legalább 2 karakterből kell álljon.',
            'name.max' => 'A név legfeljebb 255 karakterből állhat.',
            'email.required' => 'Kérjük, adja meg az email címét.',
            'email.email' => 'Kérjük, érvényes email címet adjon meg.',
            'email.max' => 'Az email cím legfeljebb 255 karakterből állhat.',
            'phone_number.required' => 'Kérjük, adja meg a telefonszámát.',
            'phone_number.string' => 'A telefonszám szövegből kell álljon.',
            'phone_number.regex' => 'A telefonszám formátuma nem megfelelő. Példa: 0712345678',
        ];
    }
}
