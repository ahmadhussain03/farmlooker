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
        try {
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

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $workers
            ]);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $data = $this->validate($request, [
                'name' => 'required|string|max:255',
                'phone_no' => 'required|string|phone:AUTO,SA|max:20',
                'address' => 'required|string',
                'pay' => 'required|numeric',
                'location' => 'required|string',
                'joining_date' => 'required|date',
                'duty' => 'required|string|max:255',
                'farm_id' => 'required|integer|min:1',
                'id_or_passport' => 'required|max:255'
            ]);

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();

            $worker = Worker::create($data);

            return response()->json([
                'code' => 200,
                'message' => 'Worker Created Successfully',
                'data' => $worker
            ]);
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($worker)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $worker = $currentUser->workers()->where('workers.id', $worker)->firstOrFail();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $worker
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Worker Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $worker = $currentUser->workers()->where('workers.id', $worker)->firstOrFail();

            $data = $this->validate($request, [
                'name' => 'string|max:255',
                'phone_no' => 'string|phone:AUTO,SA|max:20',
                'address' => 'string',
                'pay' => 'numeric',
                'location' => 'string',
                'joining_date' => 'date',
                'duty' => 'string|max:255',
                'farm_id' => 'integer|min:1',
                'id_or_passport' => 'string|max:255'
            ]);

            if($request->farm_id && $request->farm_id != $worker->farm_id){
                $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();
            }

            $worker->update($data);

            return response()->json([
                'code' => 200,
                'message' => 'Worker Updated Successfully',
                'data' => $worker
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Worker Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($worker)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $worker = $currentUser->workers()->where('workers.id', $worker)->firstOrFail();

            $worker->delete();

            return response()->json([
                "code" => 200,
                "message" => "Worker Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Worker Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
