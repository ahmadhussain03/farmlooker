<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Miscelleneous;
use App\Models\OrderFeedExpense;
use App\Models\Salary;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $purchaseAnimals = $currentUser->animals()->where('animals.add_as', 'purchased')->paginate($perPage);

        return response()->success($purchaseAnimals);
    }

    public function show()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $purchaseTotal = $currentUser->expenses()->sum('expenses.amount');

        return response()->success($purchaseTotal);
    }

    public function summary()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();

        $totalExpense = $currentUser->expenses()->sum('expenses.amount');
        $animalExpense = $currentUser->expenses()->where('expenses.expenseable_type', Animal::class)->sum('expenses.amount');
        $salaries = $currentUser->expenses()->where('expenses.expenseable_type', Salary::class)->sum('expenses.amount');
        $orderFeedExpense = $currentUser->expenses()->where('expenses.expenseable_type', OrderFeedExpense::class)->sum('expenses.amount');
        $miscelleneous = $currentUser->expenses()->where('expenses.expenseable_type', Miscelleneous::class)->sum('expenses.amount');

        return response()->success(['total_expense' => $totalExpense, 'animal_expense' => $animalExpense, 'salaries' => $salaries, 'order_feed_expense' => $orderFeedExpense, 'miscelleneous' => $miscelleneous]);
    }
}
