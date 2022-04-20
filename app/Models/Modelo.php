<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = ['brand_id', 'name', 'image', 'door_qt', 'seaters', 'air_bag', 'abs'];

    public function rules () {
        return [
            'brand_id' => 'exists:brands,id',
            'name' => "required|unique:brands,name,$this->id|min:3",
            'image' => 'required|file|mimes:png,svg',
            'door_qt' => 'required|integer|digits_between:1,5',
            'seaters' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    public function feedback () {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'image.mimes' => 'O arquivo deve ser uma imagem PNG ou SVG',
            'name.unique' => 'O nome informado já existe',
            'name.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

    public function brand () {
        return $this->belongsTo('App\Models\Brand');
    }
}
