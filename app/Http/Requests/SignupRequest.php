<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            'name' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/'], // Nom sans caractères spéciaux
            'email' => 'required|email|unique:users,email', // Email valide et unique
            'password' => 'required|min:6', // Mot de passe min 6 caractères
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.regex' => 'Le nom ne doit pas contenir de caractères spéciaux.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ];
    }
}
