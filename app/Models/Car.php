<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id', 'plate', 'available', 'km'];

    public function rules () {
        return [
            'modelo_id' => 'exists:modelos,id',
            'plate' => 'required',
            'available' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }

    public function modelo () {
        return $this->belongsTo('App\Models\Modelo');
    }
}
