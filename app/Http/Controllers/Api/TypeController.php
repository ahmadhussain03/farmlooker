<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function types(Request $request)
    {
        $typeQuery = Type::query();
        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $types = $typeQuery->paginate($perPage);

        return response()->success($types);
    }

    public function breeds(Request $request, $id)
    {
        $breedQuery = Breed::query()->where('type_id', $id);

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $breeds = $breedQuery->paginate($perPage);

        return response()->success($breeds);
    }
}
