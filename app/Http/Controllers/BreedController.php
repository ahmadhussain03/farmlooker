<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\Type;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    public function index()
    {
        abort(404);
    }

    public function create()
    {
        $types = Type::all();
        return view('admin.breed.create', ['types' => $types]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'breed' => 'required|string|max:255',
            'type' => 'required|integer|min:1'
        ]);

        $type = Type::findOrFail($request->type);

        Breed::create([
            'breed' => $request->breed,
            'type_id' => $type->id
        ]);

        session()->flash('success', 'Animal Breed Created Successfully!');

        return redirect()->route('admin.breed.create');
    }
}
