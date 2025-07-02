<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'car_id',
        'order_date',
        'pickup_date',
        'dropoff_date',
        'pickup_location',
        'dropoff_location',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Order::class;
    }
}
