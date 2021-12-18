<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        abort(404);
    }

    public function create()
    {
        return view('admin.type.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string|max:255'
        ]);

        Type::create(['type' => $request->type]);

        session()->flash('success', 'Animal Type Created Successfully!');

        return redirect()->route('admin.type.create');
    }
}
