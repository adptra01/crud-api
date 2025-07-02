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
 *   @OA\Property(property="card_id", type="integer", example=42),
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
     *   summary="getOrderList",
     *   tags={"Order"},
     *   description="Get all orders",
     *
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *
     *         @OA\Items(ref="#/components/schemas/Order")
     *       ),
     *
     *       @OA\Property(property="message", type="string")
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
     *   summary="createOrder",
     *   tags={"Order"},
     *   description="Create Order",
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(ref="#/components/schemas/Order")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="data", ref="#/components/schemas/Order"),
     *       @OA\Property(property="message", type="string")
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
     *   summary="getOrderItem",
     *   tags={"Order"},
     *   description="Get Order by ID",
     *
     *   @OA\Parameter(
     *     name="id", in="path", required=true, @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="data", ref="#/components/schemas/Order"),
     *       @OA\Property(property="message", type="string")
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
     *   summary="updateOrder",
     *   tags={"Order"},
     *   description="Update Order",
     *
     *   @OA\Parameter(
     *     name="id", in="path", required=true, @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(ref="#/components/schemas/Order")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="data", ref="#/components/schemas/Order"),
     *       @OA\Property(property="message", type="string")
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
     *   summary="deleteOrder",
     *   tags={"Order"},
     *   description="Delete Order",
     *
     *   @OA\Parameter(
     *     name="id", in="path", required=true, @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="data", type="string"),
     *       @OA\Property(property="message", type="string")
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
