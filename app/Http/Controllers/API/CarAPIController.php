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
 * @OA\Schema(
 *   schema="Car",
 *   type="object",
 *   title="Car",
 *   description="A Car model",
 *
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="car_name", type="string", example="Avanza"),
 *   @OA\Property(property="day_rate", type="number", format="double", example=150.0),
 *   @OA\Property(property="month_rate", type="number", format="double", example=3000.0),
 *   @OA\Property(property="image", type="string", description="URL or filename of the image", example="avanza.jpg")
 * )
 */
class CarAPIController extends AppBaseController
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepo)
    {
        $this->carRepository = $carRepo;
    }

    /**
     * @OA\Get(
     *      path="/cars",
     *      summary="getCarList",
     *      tags={"Car"},
     *      description="Get all cars",
     *
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *
     *                  @OA\Items(ref="#/components/schemas/Car")
     *              ),
     *
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
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
     * @OA\Post(
     *      path="/cars",
     *      summary="createCar",
     *      tags={"Car"},
     *      description="Create Car",
     *
     *      @OA\RequestBody(
     *        required=true,
     *
     *        @OA\JsonContent(ref="#/components/schemas/Car")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Car"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateCarAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $car = $this->carRepository->create($input);

        return $this->sendResponse($car->toArray(), 'Car saved successfully');
    }

    /**
     * @OA\Get(
     *      path="/cars/{id}",
     *      summary="getCarItem",
     *      tags={"Car"},
     *      description="Get Car",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Car",
     *
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Car"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
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
     * @OA\Put(
     *      path="/cars/{id}",
     *      summary="updateCar",
     *      tags={"Car"},
     *      description="Update Car",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Car",
     *
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *
     *      @OA\RequestBody(
     *        required=true,
     *
     *        @OA\JsonContent(ref="#/components/schemas/Car")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Car"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
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
     * @OA\Delete(
     *      path="/cars/{id}",
     *      summary="deleteCar",
     *      tags={"Car"},
     *      description="Delete Car",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Car",
     *
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
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
