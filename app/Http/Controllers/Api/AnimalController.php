<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateAnimalRequest;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAnimalRequest;

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
        $animalQuery = Animal::query();

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $animals = $animalQuery->paginate($perPage);

        return response()->json($animals);
    }

    /**
     * Get specific Animal from ID
     *
     * @param Animal $animal
     * @return JsonResponse
     */
    public function show(Animal $animal): JsonResponse
    {
        return response()->json($animal);
    }

    /**
     * Create a New Animal
     *
     * @param CreateAnimalRequest $request
     * @return JsonResponse
     */
    public function store(CreateAnimalRequest $request): JsonResponse
    {
        $animal = Animal::create(array_merge($request->all(), ["user_id" => auth()->id()]));
        return response()->json($animal);
    }

    /**
     * Update Existing Animal with ID
     *
     * @param UpdateAnimalRequest $request
     * @param Animal $animal
     * @return JsonResponse
     */
    public function update(UpdateAnimalRequest $request, Animal $animal): JsonResponse
    {
        $animal->update($request->all());
        return response()->json($animal);
    }

    /**
     * Delete Animal using ID
     *
     * @param Animal $animal
     * @return JsonResponse
     */
    public function destroy(Animal $animal): JsonResponse
    {
        $animal->delete();

        return response()->json(["message" => "Animal Deleted Successfully!"]);
    }
}
