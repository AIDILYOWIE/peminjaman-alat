<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'peminjam';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'items' => 'required|array|min:1',
            'items.*.alat_id' => 'required|exists:alat,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ];
    }

    /**
     * Custom messages for validation.
     */
    // public function messages(): array
    // {
    //     return [
    //         'borrow_date.required' => 'Tanggal peminjaman harus diisi.',
    //         'borrow_date.after_or_equal' => 'Tanggal peminjaman tidak boleh hari yang sudah lewat.',
    //         'return_date.required' => 'Tanggal pengembalian harus diisi.',
    //         'return_date.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal pinjam.',
    //         'items.required' => 'Daftar alat tidak boleh kosong.',
    //     ];
    // }
}
