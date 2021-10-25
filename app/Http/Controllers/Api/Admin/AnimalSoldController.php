<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnimalSold;
use Illuminate\Http\Request;

class AnimalSoldController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'animal' => 'required|exists:animals,id',
            'amount' => 'required|numeric',
            'dated' => 'required|date'
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $animal = $currentUser->animals()->where('animals.id', $request->animal)->firstOrFail();

        $animalSold = AnimalSold::create([
            'animal_id' => $animal->id,
            'farm_id' => null,
            'amount' => $request->amount,
            'previous_farm' => $animal->farm_id,
            'dated' => $request->dated
        ]);

        $animal->farm_id = null;
        $animal->save();

        return response()->success($animalSold);
    }
}
