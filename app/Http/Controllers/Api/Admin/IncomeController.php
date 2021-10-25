<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnimalSold;
use App\Models\OtherIncome;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function summary()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();

        $totalIncome = $currentUser->incomes()->sum('incomes.amount');
        $animalSold = $currentUser->incomes()->where('incomes.incomeable_type', AnimalSold::class)->sum('incomes.amount');
        $otherIncome = $currentUser->incomes()->where('incomes.incomeable_type', OtherIncome::class)->sum('incomes.amount');

        return response()->success(['total_income' => $totalIncome, 'animal_sold' => $animalSold, 'other_income' => $otherIncome]);
    }
}
