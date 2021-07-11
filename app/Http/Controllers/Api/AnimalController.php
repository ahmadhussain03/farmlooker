<?php

namespace App\Http\Controllers\Api;

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
        try {
            $animalQuery = Animal::query()->with(['maleParent', 'femaleParent']);

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $animals = $animalQuery->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $animals
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
            $animal = Animal::with(['maleParent', 'femaleParent'])->findOrFail($animal);

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
    public function tree($animal): JsonResponse
    {
        try {
            $animal = Animal::with(['femaleParentTree', 'maleParentTree'])->findOrFail($animal);

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
            $this->validate($request, [
                'animal_id' => 'required',
                'type' => 'required|string|max:255',
                'breed' => 'required|string|max:255',
                'add_as' => 'required|in:purchased,calved',
                'male_breeder_id' => 'nullable|integer',
                'female_breeder_id' => 'nullable|integer',
                'sex' => 'required|in:male,female',
                'dob' => 'required',
                'purchase_date' => 'nullable|date',
                'location' => 'required',
                'disease' => 'required|in:healthy,sick',
                'price' => 'nullable|numeric',
            ]);

            $animal = Animal::create(array_merge($request->all(), ["user_id" => auth()->id()]));
            $animal->load(['maleParent', 'femaleParent']);
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
            $animal = Animal::findOrFail($animal);

            $this->validate($request, [
                'animal_id' => 'nullable',
                'type' => 'nullable|string|max:255',
                'breed' => 'nullable|string|max:255',
                'add_as' => 'nullable|in:purchased,calved',
                'male_breeder_id' => 'nullable|integer',
                'female_breeder_id' => 'nullable|integer',
                'sex' => 'nullable|in:male,female',
                'dob' => 'nullable',
                'purchase_date' => 'nullable|date',
                'location' => 'nullable',
                'disease' => 'nullable|in:healthy,sick',
                'price' => 'nullable|numeric',
            ]);

            $animal->update($request->all());
            $animal->load(['maleParent', 'femaleParent']);
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
            $animal = Animal::findOrFail($animal);
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
}
