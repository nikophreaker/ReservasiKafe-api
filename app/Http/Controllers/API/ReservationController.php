<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Reservation;

class ReservationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservation = Reservation::all();
        return response()->json($reservation);
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
            'user_id' => 'required|integer',
            'staff_id' => 'required|integer',
            'date' => 'required',
            'time' => 'required',
            'number_of_guests' => 'required|integer'
        ]);

        if ($validate->fails()) {
            return $this->sendError('Add Reservation Error.', $validate->errors());
        }

        $reservation = Reservation::create($request->all());

        return $this->sendResponse($reservation, 'Add Reservation successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return $this->sendError('Reservation not found', 'Reservation not found');
        }

        return $this->sendResponse($reservation, 'Show Reservation successfully.');
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
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return $this->sendError('Update Error.', 'Reservation not found');
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

        $reservation->update($request->all());

        return $this->sendResponse($reservation, 'Update Reservation successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return $this->sendError('Deleted Error.', 'Reservation not found');
        }

        $reservation->delete();

        return $this->sendResponse($reservation, 'Deleted Reservation successfully.');
    }
}
