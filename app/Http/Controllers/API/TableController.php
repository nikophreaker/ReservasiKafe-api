<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Table;

class TableController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $table = Table::all();
        return response()->json($table);
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
            'table_number' => 'required|integer',
            'capacity' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Add Table Error.', $validate->errors());
        }

        $menu = Table::create($request->all());

        return $this->sendResponse($menu, 'Add Table successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return $this->sendError('Table not found', 'Table not found');
        }

        return $this->sendResponse($table, 'Show Table successfully.');
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
        $table = Table::find($id);

        if (!$table) {
            return $this->sendError('Update Error.', 'Table not found');
        }

        $validate = Validator::make($request->all(), [
            'table_number' => 'required',
            'capacity' => 'required',
            'available' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Update Error.', $validate->errors());
        }

        $table->update($request->all());

        return $this->sendResponse($table, 'Update Table successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return $this->sendError('Deleted Error.', 'Table not found');
        }

        $table->delete();

        return $this->sendResponse($table, 'Deleted Table successfully.');
    }
}
