<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function types()
    {
        return response()->success(Type::all());
    }

    public function breeds($id)
    {
        $breeds = Breed::where('type_id', $id)->get();
        return response()->success($breeds);
    }
}
