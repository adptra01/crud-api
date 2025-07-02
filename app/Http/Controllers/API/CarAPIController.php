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
     *      summary="List all cars",
     *      tags={"Car"},
     *      description="Retrieve a list of all cars with optional pagination parameters 'skip' and 'limit'.",
     *
     *      @OA\Parameter(
     *          name="skip",
     *          in="query",
     *          description="Number of records to skip for pagination",
     *          required=false,
     *          @OA\Schema(type="integer", format="int32")
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Maximum number of records to return",
     *          required=false,
     *          @OA\Schema(type="integer", format="int32")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Car")
     *              ),
     *
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Cars retrieved successfully"
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
     *      summary="Create a new car",
     *      tags={"Car"},
     *      description="Create a new car record with the provided data.",
     *
     *      @OA\RequestBody(
     *        required=true,
     *        description="Car object that needs to be added",
     *        @OA\JsonContent(ref="#/components/schemas/Car")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Car"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Car saved successfully"
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
     *      summary="Get a car by ID",
     *      tags={"Car"},
     *      description="Retrieve a single car by its ID.",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the car to retrieve",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Car"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Car retrieved successfully"
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
     *      summary="Update a car by ID",
     *      tags={"Car"},
     *      description="Update the details of an existing car by its ID.",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the car to update",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64")
     *      ),
     *
     *      @OA\RequestBody(
     *        required=true,
     *        description="Car object with updated data",
     *        @OA\JsonContent(ref="#/components/schemas/Car")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Car"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Car updated successfully"
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
     *      summary="Delete a car by ID",
     *      tags={"Car"},
     *      description="Delete an existing car by its ID.",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the car to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="string",
     *                  example="Car deleted successfully"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Car deleted successfully"
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
