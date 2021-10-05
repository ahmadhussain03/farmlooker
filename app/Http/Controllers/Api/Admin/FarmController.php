<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use App\Models\Farm;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\Forbidden;
use App\Http\Controllers\Controller;
use App\Models\City;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $authUser = User::findOrFail(auth()->id());

        $farmsQuery = $authUser->farms()->with(['city']);

        if($request->has('client') && $request->client === 'datatable'){
            $farmsQuery->select(["*", "farms.id as farmId"]);
            return DataTables::eloquent($farmsQuery)
                ->setRowId('farmId')
                ->addIndexColumn()
                ->toJson();
        }

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $farms = $farmsQuery->search()->paginate($perPage);

        return response()->success($farms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:255',
            'area_of_hector' => 'required|numeric',
            'city_id' => 'required|integer|min:1'
        ]);

        City::findOrFail($request->city_id);

        $user = User::findOrFail(auth()->id());

        $farm = Farm::create($data);

        $user->farms()->attach($farm);

        return response()->success($farm);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $farm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $farm)
    {
        $farm = Farm::findOrFail($farm);

        if(!$farm->admin()->where('users.id', auth()->id())->exists()){
            throw new Forbidden();
        }

        $data = $this->validate($request, [
            'name' => 'string|max:255',
            'area_of_hector' => 'numeric|numeric',
            'city_id' => 'integer|min:1'
        ]);

        if($request->city_id){
            City::findOrFail($request->city_id);
        }

        $farm->update($data);

        return response()->success($farm);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $farm
     * @return \Illuminate\Http\Response
     */
    public function destroy($farm)
    {
        $farm = Farm::findOrFail($farm);

        $farm->delete();

        return response()->success(null, "Farm Deleted Successfully!");
    }
}
