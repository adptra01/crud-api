<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $table = 'orders';

    public $fillable = [
        'car_id',
        'order_date',
        'pickup_date',
        'dropoff_date',
        'pickup_location',
        'dropoff_location',
    ];

    protected $casts = [
        'car_id' => 'integer',
        'order_date' => 'date:Y-m-d',
        'pickup_date' => 'date:Y-m-d',
        'dropoff_date' => 'date:Y-m-d',
        'pickup_location' => 'string',
        'dropoff_location' => 'string',
    ];

    public static array $rules = [
        'car_id' => 'required|exists:cars,id',
        'order_date' => 'required|date',
        'pickup_date' => 'required|date',
        'dropoff_date' => 'required|date',
        'pickup_location' => 'required|max:50|string',
        'dropoff_location' => 'required|max:50|string',
    ];
}
