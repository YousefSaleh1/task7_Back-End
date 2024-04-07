<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        $data = OrderResource::collection($orders);
        return $this->customeResponse($data, 'Done!', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $order = Order::create(['total_price' => $request->products]);

            foreach ($request->products as $product) {
                $order->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
            }

            DB::commit();
            $data = new OrderResource($order);
            return $this->customeResponse($data, 'Created Successfully', 201);
        } catch (\Throwable $th) {
            Log::debug($th);
            DB::rollBack();
            return $this->customeResponse(null, 'Failed To Create', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            $data = new OrderResource($order);
            return $this->customeResponse($data, 'Done!', 200);
        } catch (\Throwable $th) {
            Log::debug($th);
            return $this->customeResponse(null, 'Not Found', 404);
        }
    }

    /**
     * Get the orders of the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function ordersByUser()
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $orders = $user->orders;
        $data = OrderResource::collection($orders);
        return $this->customeResponse($data, 'Done!', 200);
    }

    /**
     * Get the order of the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderByUser(Order $order)
    {
        try {
            $user_id = Auth::user()->id;
            if ($order->user_id == $user_id) {
                $data = new OrderResource($order);
                return $this->customeResponse($data, 'Done!', 200);
            }
            abort(403, 'Unauthorized');
        } catch (\Throwable $th) {
            Log::debug($th);
            return $this->customeResponse(null, 'Not Found', 404);
        }
    }
}
