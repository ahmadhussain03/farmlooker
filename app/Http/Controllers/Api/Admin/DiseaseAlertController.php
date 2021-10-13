<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use App\Models\DiseaseAlert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DiseaseAlertController extends Controller
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
            $diseaseAlertQuery = $currentUser->diseaseAlerts()->with('animal');

            if($request->has('client') && $request->client === 'datatable'){
                $diseaseAlertQuery->select(["*", "disease_alerts.id as diseaseId"]);

                return DataTables::eloquent($diseaseAlertQuery)->editColumn('symptoms', function($alert){
                    return implode(", ", $alert->symptoms);
                })
                ->setRowId('diseaseId')
                ->addIndexColumn()
                ->toJson();
            }

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $diseaseAlerts = $diseaseAlertQuery->search()->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $diseaseAlerts
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
                'animal_id' => 'required|integer|min:1',
                'description' => "required|string",
                'symptoms' => 'required|array',
                'symptoms.*' => 'string'
            ]);

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $animal = $currentUser->animals()->where('animals.id', $request->animal_id)->first();

            if(!$animal){
                return response()->json([
                    'code' => 404,
                    'message' => 'Animal Not Found.',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }

            $animal->disease = 'sick';
            $animal->save();

            $alert = DiseaseAlert::create([
                'animal_id' => $animal->id,
                'description' => $request->description,
                'symptoms' => json_encode($request->symptoms),
                'user_id' => auth()->id()
            ]);

            $alert->load('animal');

            return response()->json([
                'code' => 200,
                'message' => 'Disease Alert Created Successfully',
                'data' => $alert
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
    public function show($disease_alert)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $diseaseAlert = $currentUser->diseaseAlerts()->with('animal')->findOrFail($disease_alert);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $diseaseAlert
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Disease Alert Not Found.',
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($disease_alert)
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $diseaseAlert = $currentUser->diseaseAlerts()->with('animal')->findOrFail($disease_alert);
            $diseaseAlert->delete();

            return response()->json([
                "code" => 200,
                "message" => "Disease Alert Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Disease Alert Not Found.',
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
