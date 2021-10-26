<?php

namespace App\Http\Requests\Users;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'role_id' => 'required|integer|in:' . Role::EDITOR . ',' . Role::ASSISTANT,
            'password' => ['required', 'confirmed', Password::min(8), 'regex:/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/']
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
            'password.regex' => 'The :attribute must contain at least one lower case letter, an upper case letter and digit',
        ];
    }
}
