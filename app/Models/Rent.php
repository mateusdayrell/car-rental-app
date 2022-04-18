<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'car_id', 'begin_date', 'end_date', 'daily_value', 'km_start', 'km_end'];

    public function rules () {
        return [
            'client_id' => 'exists:clients,id',
            'car_id' => 'exists:cars,id',
            'begin_date' => 'required',
            'end_date' => '',
            'daily_value' => 'required',
            'km_start' => 'required',
            'km_end' => '',
        ];
    }

}
