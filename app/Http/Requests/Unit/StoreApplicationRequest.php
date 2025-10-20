<?php

namespace App\Http\Requests\Unit;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('config')) {
                $config = json_decode($this->input('config'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $validator->errors()->add('config', 'Invalid JSON format in config field.');
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Application name is required.',
            'name.max' => 'Application name cannot exceed 255 characters.',
            'type.required' => 'Application type is required.',
            'type.max' => 'Application type cannot exceed 255 characters.',
            'config.required' => 'Configuration is required.',
        ];
    }
}
