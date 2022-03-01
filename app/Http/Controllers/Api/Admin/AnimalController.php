<?php

namespace App\Http\Controllers\Api\Admin;

use DB;
use DataTables;
use App\Models\Type;
use App\Models\Breed;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class AnimalController
 * @package App\Http\Controllers\Api
 */
class AnimalController extends Controller
{
    /**
     * Paginated Data of Animals
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $animalQuery = $currentUser->animals()->with(['maleParent', 'femaleParent', 'farm', 'type', 'breed']);

        // if($request->has('client') && $request->client === 'datatable'){
        //     $animalQuery->select(["*", "animals.id as animalId"]);
        //     return DataTables::eloquent($animalQuery)
        //         ->editColumn('dob', function($animal){
        //             return $animal->dob->toFormattedDateString();
        //         })
        //         ->setRowId('animalId')
        //         ->addIndexColumn()
        //         ->toJson();
        // }

        if($request->has('sex')){
            $animalQuery->where('sex', $request->sex);
        }

        if($request->has('type')){
            $animalQuery->where('type_id', $request->type);
        }

        if($request->has('search') && $request->search != ""){
            $search = $request->search;
            $animalQuery
                ->where('animals.auid', 'like', '%' . $search . '%')
                ->orWhere('animals.animal_id', 'like', '%' . $search . '%')
                ->orWhere('animals.disease', 'like', '%' . $search . '%')
                ->orWhere('animals.dob', 'like', '%' . $search . '%')
                ->orWhere('animals.add_as', 'like', '%' . $search . '%')
                ->orWhere('animals.sex', 'like', '%' . $search . '%')
                ->orWhereHas('farm', function($query) use ($search){
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('type', function($query) use ($search){
                    $query->where('type', 'like', '%' . $search . '%');
                })
                ->orWhereHas('breed', function($query) use ($search){
                    $query->where('breed', 'like', '%' . $search . '%');
                });
        }

        if($request->has('sort_field') && $request->has('sort_order')){
            $relationArray = explode(".", $request->sort_field);
            if(count($relationArray) > 1){
                $relation = $relationArray[0];
                $field = $relationArray[1];
                $sortOrder = $request->sort_order;

                $animalQuery->with([$relation => function($query) use ($field, $sortOrder) {
                    $query->orderBy($field, $sortOrder);
                }]);
            } else {
                $animalQuery->orderBy($request->sort_field, $request->sort_order);
            }
        }

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $animals = $animalQuery->paginate($perPage);

        return response()->success($animals);
    }

    /**
     * Get specific Animal from ID
     *
     * @param $animal
     * @return JsonResponse
     */
    public function show($animal): JsonResponse
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $animal = $currentUser->animals()->with(['farm', 'type', 'breed'])->where('animals.id', $animal)->firstOrFail();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $animal
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'code' => 404,
                'message' => 'Animal Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Tree Animal from ID
     *
     * @param $animal
     * @return JsonResponse
     */
    public function tree($id): JsonResponse
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $animal = $currentUser->animals()->with(['femaleParentTree', 'maleParentTree', 'type', 'breed'])->where('animals.id', $id)->firstOrFail();
            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $animal
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'code' => 404,
                'message' => 'Animal Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a New Animal
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->validate($request, [
                'animal_id' => 'required',
                'type_id' => 'required|integer',
                'breed_id' => 'required|integer',
                'add_as' => 'required|in:purchased,calved',
                'male_breeder_id' => 'nullable|integer|required_if:add_as,calved',
                'female_breeder_id' => 'nullable|integer|required_if:add_as,calved',
                'sex' => 'required|in:male,female',
                'dob' => 'required',
                'purchase_date' => 'nullable|date',
                'disease' => 'required|in:healthy,sick',
                'farm_id' => 'required|integer|min:1',
                'price' => 'nullable|required_if:add_as,purchased|numeric',
                'previous_owner' => 'required_if:add_as,purchased'
            ]);

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();

            Type::findOrFail($request->type_id);
            Breed::findOrFail($request->breed_id);

            $animal = Animal::create($data);
            $animal->load(['maleParent', 'femaleParent', 'type', 'breed']);
            return response()->json([
                'code' => 200,
                'message' => 'Animal Created Successfully',
                'data' => $animal
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Existing Animal with ID
     *
     * @param Request $request
     * @param $animal
     * @return JsonResponse
     */
    public function update(Request $request, $animal): JsonResponse
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $animal = $currentUser->animals()->where('animals.id', $animal)->firstOrFail();

            $this->validate($request, [
                'animal_id' => 'nullable',
                'type_id' => 'nullable|integer',
                'breed_id' => 'nullable|integer',
                'add_as' => 'in:purchased,calved',
                'male_breeder_id' => 'nullable|integer',
                'female_breeder_id' => 'nullable|integer',
                'sex' => 'in:male,female',
                'dob' => 'string',
                'purchase_date' => 'nullable|date',
                'disease' => 'in:healthy,sick',
                'price' => 'numeric',
                'previous_owner' => 'required_if:add_as,purchased'
            ]);

            if($request->farm_id && $request->farm_id != $animal->farm_id){
                $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();
            }

            $animal->update($request->all());
            $animal->load(['maleParent', 'femaleParent', 'type', 'breed']);
            return response()->json([
                'code' => 200,
                'message' => 'Animal Updated Successfully',
                'data' => $animal
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'code' => 404,
                'message' => 'Animal Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $exception) {
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete Animal using ID
     *
     * @param $animal
     * @return JsonResponse
     */
    public function destroy($animal): JsonResponse
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $animal = $currentUser->animals()->where('animals.id', $animal)->firstOrFail();
            $animal->delete();

            return response()->json([
                "code" => 200,
                "message" => "Animal Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'code' => 404,
                'message' => 'Animal Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete Animal using Bulk IDs
     *
     * @param $animal
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'animals' => 'required|array|min:1',
            'animals.*' => 'integer'
        ]);

         /** @var App\Models\User */
         $currentUser = auth()->user();
         $currentUser->animals()->whereIn('animals.id', $request->animals)->delete();

        return response()->success(null, "Animals Deleted Successfully!");
    }
}
