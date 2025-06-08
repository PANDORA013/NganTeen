<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Menu;

class CartRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'pembeli';
    }

    public function rules()
    {
        $menu = Menu::find($this->menu_id);
        $maxStok = $menu ? $menu->stok : 1;

        return [
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => "required|integer|min:1|max:{$maxStok}",
        ];
    }

    public function messages()
    {
        return [
            'menu_id.required' => 'Menu tidak valid',
            'menu_id.exists' => 'Menu tidak ditemukan',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1',
            'jumlah.max' => 'Stok tidak mencukupi',
        ];
    }
}