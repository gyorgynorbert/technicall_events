<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // We can rely on the 'admin' middleware, but it's good practice
        // to also authorize here.
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
        ];
    }
}
