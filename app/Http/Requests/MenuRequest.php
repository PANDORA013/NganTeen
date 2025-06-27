<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'penjual';
    }

    public function rules()
    {
        return [
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'area_kampus' => 'required|in:Kampus A,Kampus B,Kampus C',
            'nama_warung' => 'required|string|max:255',
            'gambar' => 'nullable|image|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'nama_menu.required' => 'Nama menu harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.min' => 'Harga tidak boleh negatif',
            'stok.required' => 'Stok harus diisi',
            'stok.min' => 'Stok tidak boleh negatif',
            'area_kampus.required' => 'Area kampus harus dipilih',
            'area_kampus.in' => 'Area kampus tidak valid',
            'nama_warung.required' => 'Nama warung harus diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 2MB'
        ];
    }
}
