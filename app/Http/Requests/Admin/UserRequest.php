<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $id = ($user instanceof \App\Models\User) ? $user->id : $user;

        $rules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($id)],
            'no_induk' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(['admin', 'petugas', 'peminjam'])],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'string', Password::min(8)];
        } else {
            $rules['password'] = ['nullable', 'string', Password::min(8)];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal berjumlah 8 karakter.',
        ];
    }
}
