<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image'];

    public function rules () {
        return [
            'name' => "required|unique:brands,name,$this->id|min:3",
            'image' => 'required|file|mimes:png,svg',
        ];
    }

    public function feedback () {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'image.mimes' => 'O arquivo deve ser uma imagem PNG ou SVG',
            'name.unique' => 'O nome da marca informado da marca já existe',
            'name.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

    public function modelos () {
        return $this->hasMany('App\Models\Modelo');
    }
}
