<?php

namespace App\Repositories;

use App\Models\Car;

class CarRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'car_name',
        'day_rate',
        'month_rate',
        'image',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Car::class;
    }
}
