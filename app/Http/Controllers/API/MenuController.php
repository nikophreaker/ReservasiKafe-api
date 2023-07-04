<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;

class MenuController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = Menu::all();
        return response()->json($menu);
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
            'item_name' => 'required',
            'description' => 'required',
            'price' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Add Menu Error.', $validate->errors());
        }

        $menu = Menu::create($request->all());

        return $this->sendResponse($menu, 'Add Menu successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return $this->sendError('Menu not found', 'Menu not found');
        }

        return $this->sendResponse($menu, 'Show Menu successfully.');
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
        $menu = Menu::find($id);

        if (!$menu) {
            return $this->sendError('Update Error.', 'Menu not found');
        }

        $validate = Validator::make($request->all(), [
            'item_name' => 'required',
            'description' => 'required',
            'price' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Update Error.', $validate->errors());
        }

        $menu->update($request->all());

        return $this->sendResponse($menu, 'Update Menu successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return $this->sendError('Deleted Error.', 'Menu not found');
        }

        $menu->delete();

        return $this->sendResponse($menu, 'Deleted Menu successfully.');
    }
}
