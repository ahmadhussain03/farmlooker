<?php

namespace App\Http\Controllers\Api;

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
            $workerQuery = Worker::query()->where('user_id', auth()->id());

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $workers = $workerQuery->paginate($perPage);

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

            $this->validate($request, [
                'name' => 'required|string|max:255',
                'phone_no' => 'required|string|phone:AUTO,SA|max:20',
                'address' => 'required|string',
                'pay' => 'required|numeric',
                'location' => 'required|string',
                'joining_date' => 'required|date',
                'duty' => 'required|string|max:255'
            ]);

            $worker = Worker::create(array_merge($request->all(), [
                'user_id' => auth()->id()
            ]));

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
            $worker = Worker::findOrFail($worker);

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

            $worker = Worker::findOrFail($worker);

            $this->validate($request, [
                'name' => 'nullable|string|max:255',
                'phone_no' => 'nullable|string|phone:AUTO,SA|max:20',
                'address' => 'nullable|string',
                'pay' => 'nullable|numeric',
                'location' => 'nullable|string',
                'joining_date' => 'nullable|date'
            ]);

            $worker->update($request->all());

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
            $worker = Worker::findOrFail($worker);

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
