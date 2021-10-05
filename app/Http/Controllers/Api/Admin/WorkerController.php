<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WorkerController extends Controller
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
        $workerQuery = $currentUser->workers()->with(['farm']);

        if($request->has('client') && $request->client === 'datatable'){
            $workerQuery->select(["*", "workers.id as workerId"]);
            return DataTables::eloquent($workerQuery)->editColumn('joining_date', function($worker){
                return $worker->joining_date->toFormattedDateString();
            })
            ->setRowId('workerId')
            ->addIndexColumn()
            ->toJson();
        }

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $workers = $workerQuery->search()->paginate($perPage);

        return response()->success($workers);
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
            'phone_no' => 'required|string|max:20',
            'address' => 'required|string',
            'pay' => 'required|numeric',
            'joining_date' => 'required|date',
            'duty' => 'required|string|max:255',
            'farm_id' => 'required|integer|min:1',
            'id_or_passport' => 'required|max:255'
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();

        $worker = Worker::create($data);

        return response()->success($worker, 'Worker Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($worker)
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $worker = $currentUser->workers()->where('workers.id', $worker)->firstOrFail();

        return response()->success($worker);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $worker)
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $worker = $currentUser->workers()->where('workers.id', $worker)->firstOrFail();

        $data = $this->validate($request, [
            'name' => 'string|max:255',
            'phone_no' => 'string|max:20',
            'address' => 'string',
            'pay' => 'numeric',
            'joining_date' => 'date',
            'duty' => 'string|max:255',
            'farm_id' => 'integer|min:1',
            'id_or_passport' => 'string|max:255'
        ]);

        if($request->farm_id && $request->farm_id != $worker->farm_id){
            $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();
        }

        $worker->update($data);

        return response()->success($worker, 'Worker Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($worker)
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $worker = $currentUser->workers()->where('workers.id', $worker)->firstOrFail();

        $worker->delete();

        return response()->success(null, "Worker Deleted Successfully!");
    }
}
