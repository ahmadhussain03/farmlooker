<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\Forbidden;
use App\Models\Farm;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

        $farmsQuery = $authUser->farms();

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
            'location' => 'required|string|max:255',
            'area_of_hector' => 'required|numeric'
        ]);

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
            'location' => 'string|max:255',
            'area_of_hector' => 'numeric|numeric'
        ]);

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
