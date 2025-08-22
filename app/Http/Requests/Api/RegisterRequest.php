<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // regola locale e produzione
        $prodPasswordRule = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()];
        $localPasswordRule = 'required|string|min:8|confirmed';

        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => App::environment('local') ? $localPasswordRule : $prodPasswordRule,
        ];
    }
}
