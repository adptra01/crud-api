<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCarAPIRequest;
use App\Http\Requests\API\UpdateCarAPIRequest;
use App\Models\Car;
use App\Repositories\CarRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CarAPIController
 */
class CarAPIController extends AppBaseController
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepo)
    {
        $this->carRepository = $carRepo;
    }

    /**
     * Display a listing of the Cars.
     * GET|HEAD /cars
     */
    public function index(Request $request): JsonResponse
    {
        $cars = $this->carRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($cars->toArray(), 'Cars retrieved successfully');
    }

    /**
     * Store a newly created Car in storage.
     * POST /cars
     */
    public function store(CreateCarAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $car = $this->carRepository->create($input);

        return $this->sendResponse($car->toArray(), 'Car saved successfully');
    }

    /**
     * Display the specified Car.
     * GET|HEAD /cars/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Car $car */
        $car = $this->carRepository->find($id);

        if (empty($car)) {
            return $this->sendError('Car not found');
        }

        return $this->sendResponse($car->toArray(), 'Car retrieved successfully');
    }

    /**
     * Update the specified Car in storage.
     * PUT/PATCH /cars/{id}
     */
    public function update($id, UpdateCarAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Car $car */
        $car = $this->carRepository->find($id);

        if (empty($car)) {
            return $this->sendError('Car not found');
        }

        $car = $this->carRepository->update($input, $id);

        return $this->sendResponse($car->toArray(), 'Car updated successfully');
    }

    /**
     * Remove the specified Car from storage.
     * DELETE /cars/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Car $car */
        $car = $this->carRepository->find($id);

        if (empty($car)) {
            return $this->sendError('Car not found');
        }

        $car->delete();

        return $this->sendSuccess('Car deleted successfully');
    }
}
