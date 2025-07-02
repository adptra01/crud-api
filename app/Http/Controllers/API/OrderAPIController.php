<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateOrderAPIRequest;
use App\Http\Requests\API\UpdateOrderAPIRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *   schema="Order",
 *   type="object",
 *   title="Order",
 *   description="An Order model",
 *
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="car_id", type="integer", example=42),
 *   @OA\Property(property="order_date", type="string", format="date", example="2025-07-01"),
 *   @OA\Property(property="pickup_date", type="string", format="date", example="2025-07-05"),
 *   @OA\Property(property="dropoff_date", type="string", format="date", example="2025-07-10"),
 *   @OA\Property(property="pickup_location", type="string", example="Jakarta Airport"),
 *   @OA\Property(property="dropoff_location", type="string", example="Bandung Station")
 * )
 */
class OrderAPIController extends AppBaseController
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    /**
     * @OA\Get(
     *   path="/orders",
     *   summary="List all orders",
     *   tags={"Order"},
     *   description="Retrieve a list of all orders with optional pagination parameters 'skip' and 'limit'.",
     *
     *   @OA\Parameter(
     *       name="skip",
     *       in="query",
     *       description="Number of records to skip for pagination",
     *       required=false,
     *       @OA\Schema(type="integer", format="int32")
     *   ),
     *   @OA\Parameter(
     *       name="limit",
     *       in="query",
     *       description="Maximum number of records to return",
     *       required=false,
     *       @OA\Schema(type="integer", format="int32")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *
     *         @OA\Items(ref="#/components/schemas/Order")
     *       ),
     *
     *       @OA\Property(property="message", type="string", example="Orders retrieved successfully")
     *     )
     *   )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    }

    /**
     * @OA\Post(
     *   path="/orders",
     *   summary="Create a new order",
     *   tags={"Order"},
     *   description="Create a new order record with the provided data.",
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Order object that needs to be added",
     *     @OA\JsonContent(ref="#/components/schemas/Order")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Order"),
     *       @OA\Property(property="message", type="string", example="Order saved successfully")
     *     )
     *   )
     * )
     */
    public function store(CreateOrderAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $order = $this->orderRepository->create($input);

        return $this->sendResponse($order->toArray(), 'Order saved successfully');
    }

    /**
     * @OA\Get(
     *   path="/orders/{id}",
     *   summary="Get an order by ID",
     *   tags={"Order"},
     *   description="Retrieve a single order by its ID.",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the order to retrieve",
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Order"),
     *       @OA\Property(property="message", type="string", example="Order retrieved successfully")
     *     )
     *   )
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var Order $order */
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        return $this->sendResponse($order->toArray(), 'Order retrieved successfully');
    }

    /**
     * @OA\Put(
     *   path="/orders/{id}",
     *   summary="Update an order by ID",
     *   tags={"Order"},
     *   description="Update the details of an existing order by its ID.",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the order to update",
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Order object with updated data",
     *     @OA\JsonContent(ref="#/components/schemas/Order")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Order"),
     *       @OA\Property(property="message", type="string", example="Order updated successfully")
     *     )
     *   )
     * )
     */
    public function update($id, UpdateOrderAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Order $order */
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        $order = $this->orderRepository->update($input, $id);

        return $this->sendResponse($order->toArray(), 'Order updated successfully');
    }

    /**
     * @OA\Delete(
     *   path="/orders/{id}",
     *   summary="Delete an order by ID",
     *   tags={"Order"},
     *   description="Delete an existing order by its ID.",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the order to delete",
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="string", example="Order deleted successfully"),
     *       @OA\Property(property="message", type="string", example="Order deleted successfully")
     *     )
     *   )
     * )
     */
    public function destroy($id): JsonResponse
    {
        /** @var Order $order */
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        $order->delete();

        return $this->sendSuccess('Order deleted successfully');
    }
}
