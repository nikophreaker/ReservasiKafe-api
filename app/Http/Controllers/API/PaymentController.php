<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment = Payment::all();
        return response()->json($payment);
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
            'user_id' => 'required|integer',
            'payment_method' => 'required|string',
            'total_amount' => 'required|integer',
            'transaction_date' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Add Payment Error.', $validate->errors());
        }

        $payment = Payment::create($request->all());

        return $this->sendResponse($payment, 'Add Payment successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return $this->sendError('Payment not found', 'Payment not found');
        }

        return $this->sendResponse($payment, 'Show Payment successfully.');
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
        $payment = Payment::find($id);

        if (!$payment) {
            return $this->sendError('Update Error.', 'Payment not found');
        }

        $validate = Validator::make($request->all(), [
            'reservation_id' => 'required|integer',
            'user_id' => 'required|integer',
            'payment_method' => 'required|string',
            'total_amount' => 'required|integer',
            'transaction_date' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Update Error.', $validate->errors());
        }

        $payment->update($request->all());

        return $this->sendResponse($payment, 'Update Payment successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return $this->sendError('Deleted Error.', 'Payment not found');
        }

        $payment->delete();

        return $this->sendResponse($payment, 'Deleted Payment successfully.');
    }
}
