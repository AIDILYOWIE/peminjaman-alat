<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
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
        $item = $this->route('item');
        $id = ($item instanceof \App\Models\Alat) ? $item->id : $item;

        $rules = [
            'code' => ['required', 'string', Rule::unique('alat', 'code')->ignore($id)],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'nama' => ['required', 'string', 'max:255', Rule::unique('alat', 'nama')->ignore($id)],
            'deskripsi' => ['required', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'denda' => ['required', 'numeric', 'min:0'],
        ];

        if ($this->isMethod('POST')) {
            $rules['gambar'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'];
        } else {
            $rules['gambar'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'];
        }

        return $rules;
    }
}
