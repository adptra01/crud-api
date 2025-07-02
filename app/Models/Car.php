<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public $table = 'cars';

    public $fillable = [
        'car_name',
        'day_rate',
        'month_rate',
        'image',
    ];

    protected $casts = [
        'car_name' => 'string',
        'day_rate' => 'double',
        'month_rate' => 'double',
        'image' => 'string',
    ];

    public static array $rules = [
        'car_name' => 'required, max:50, string',
        'day_rate' => 'required, numeric, between:0,99999999.99999999',
        'month_rate' => 'required, numeric, between:0,99999999.99999999 ',
        'image' => 'required, image, max:2048',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
