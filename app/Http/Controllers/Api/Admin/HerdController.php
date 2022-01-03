<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Herd;
use Illuminate\Http\Request;

class HerdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         /** @var App\Models\User */
         $currentUser = auth()->user();
         $herdQuery = $currentUser->herds()->with(['farm']);

         if($request->has('farm') && $request->farm !== null){
             $herdQuery->where('herds.farm_id', $request->farm);
         }

         if($request->has('search') && $request->search != ""){
            $search = $request->search;
            $herdQuery
                ->where('herds.name', 'like', '%' . $search . '%')
                ->orWhereHas('farm', function($query) use ($search){
                    $query->where('farms.name', 'like', '%' . $search . '%');
                });
        }

        if($request->has('sort_field') && $request->has('sort_order')){
            $relationArray = explode(".", $request->sort_field);
            if(count($relationArray) > 1){
                $relation = $relationArray[0];
                $field = $relationArray[1];
                $sortOrder = $request->sort_order;

                $herdQuery->with([$relation => function($query) use ($field, $sortOrder) {
                    $query->orderBy($field, $sortOrder);
                }]);
            } else {
                $herdQuery->orderBy($request->sort_field, $request->sort_order);
            }
        }

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $herds = $herdQuery->paginate($perPage);

        return response()->success($herds);
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
            'farm_id' => 'required|integer|min:1'
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();

        $herd = Herd::create($data);

        return response()->success($herd, 'Herd Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($herd)
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $herd = $currentUser->herds()->with(['farm'])->where('herds.id', $herd)->firstOrFail();

        return response()->success($herd);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $herd)
    {
        $data = $this->validate($request, [
            'name' => 'nullable|string|max:255',
            'farm_id' => 'nullable|integer|min:1'
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $herd = $currentUser->herds()->with(['farm'])->where('herds.id', $herd)->firstOrFail();

        if($request->has('farm_id') && $request->farm_id !== null)
            $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();

        $herd->update($data);

        return response()->success($herd, 'Herd Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $herd
     * @return \Illuminate\Http\Response
     */
    public function destroy($herd)
    {
         /** @var App\Models\User */
         $currentUser = auth()->user();
         $herd = $currentUser->herds()->where('herds.id', $herd)->firstOrFail();

         $herd->delete();

         return response()->success(null, 'Herd Deleted Successfully!');
    }


    /**
     * Delete Assets using Bulk IDs
     *
     * @param $animal
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'herds' => 'required|array|min:1',
            'herds.*' => 'integer'
        ]);

         /** @var App\Models\User */
         $currentUser = auth()->user();
         $currentUser->herds()->whereIn('herds.id', $request->herds)->delete();

        return response()->success(null, "Herds Deleted Successfully!");
    }
}
