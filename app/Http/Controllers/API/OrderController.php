<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response00
     * 
     */
    public function index()
    {
        $order = Order::all();
        return response()->json($order);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'reservation_id' => 'required|integer',
            'item_id' => 'required|integer',
            'quantity' => 'required|integer',
            'special_requests' => 'required|string',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Add Order Error.', $validate->errors());
        }

        $order = Order::create($request->all());

        return $this->sendResponse($order, 'Add Order successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->sendError('Order not found', 'Order not found');
        }

        return $this->sendResponse($order, 'Show Order successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->sendError('Update Error.', 'Order not found');
        }

        $validate = Validator::make($request->all(), [
            'reservation_id' => 'required|integer',
            'item_id' => 'required|integer',
            'quantity' => 'required|integer',
            'special_requests' => 'required|string',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Update Error.', $validate->errors());
        }

        $order->update($request->all());

        return $this->sendResponse($order, 'Update Order successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->sendError('Deleted Error.', 'Order not found');
        }

        $order->delete();

        return $this->sendResponse($order, 'Deleted Order successfully.');
    }
}
