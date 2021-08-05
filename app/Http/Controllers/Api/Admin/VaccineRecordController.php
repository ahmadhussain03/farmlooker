<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Models\VaccineRecord;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VaccineRecordController extends Controller
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
            $vaccineRecordQuery = $currentUser->vaccineRecords();

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $vaccineRecords = $vaccineRecordQuery->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $vaccineRecords
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
                'animal_id' => 'required|numeric',
                'name' => 'required|string|max:255',
                'reason' => 'required|string',
                'date' => 'required|date'
            ]);

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $animal = $currentUser->animals()->where('animals.id', $request->animal_id)->firstOrFail();
            $vaccineRecord = VaccineRecord::create(array_merge($data, [
                'user_id' => auth()->id()
            ]));

            return response()->json([
                'code' => 200,
                'message' => 'Vaccine Record Created Successfully',
                'data' => $vaccineRecord
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
    public function show($vaccine_record)
    {
        try {

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $vaccineRecord = $currentUser->vaccineRecords()->where('vaccine_records.id', $vaccine_record)->firstOrFail();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $vaccineRecord
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Vaccine Record Not Found.',
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
    public function update(Request $request, $vaccine_record)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $vaccineRecord = $currentUser->vaccineRecords()->where('vaccine_records.id', $vaccine_record)->firstOrFail();

            $data = $this->validate($request, [
                'animal_id' => 'nullable|numeric',
                'name' => 'nullable|string|max:255',
                'reason' => 'nullable|string',
                'date' => 'nullable|date'
            ]);

            if($request->has('animal_id') && $request->animal_id != $vaccineRecord->animal_id){
                $currentUser->animals()->where('animals.id', $request->animal_id)->firstOrFail();
            }

            $vaccineRecord->update($data);

            return response()->json([
                'code' => 200,
                'message' => 'Vaccine Record Updated Successfully',
                'data' => $vaccineRecord
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Vaccine Record Not Found.',
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
    public function destroy($vaccine_record)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $vaccineRecord = $currentUser->vaccineRecords()->where('vaccine_records.id', $vaccine_record)->firstOrFail();

            $vaccineRecord->delete();

            return response()->json([
                "code" => 200,
                "message" => "Vaccine Record Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Vaccine Record Not Found.',
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