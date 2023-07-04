<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Staff;

class StaffController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = Staff::all();
        return response()->json($staff);
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
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'email' => 'required|email:rfc,dns|unique:staff,email',
            'phone_number' => 'required|string|regex:/(8)[0-9]{8}/|max:15|unique:staff,phone_number',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Add Staff Error.', $validate->errors());
        }

        $staff = Staff::create($request->all());

        return $this->sendResponse($staff, 'Add Staff successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return $this->sendError('Staff not found', 'Staff not found');
        }

        return $this->sendResponse($staff, 'Show Staff successfully.');
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
        $staff = Staff::find($id);

        if (!$staff) {
            return $this->sendError('Update Error.', 'Staff not found');
        }

        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'email' => 'required|email:rfc,dns|unique:staff,email,' . $staff->id,
            'phone_number' => 'required|string|regex:/(8)[0-9]{8}/|max:15|unique:staff,phone_number,' . $staff->id,
        ]);

        if ($validate->fails()) {
            return $this->sendError('Update Error.', $validate->errors());
        }

        $staff->update($request->all());

        return $this->sendResponse($staff, 'Update Staff successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return $this->sendError('Deleted Error.', 'Staff not found');
        }

        $staff->delete();

        return $this->sendResponse($staff, 'Deleted Staff successfully.');
    }
}
