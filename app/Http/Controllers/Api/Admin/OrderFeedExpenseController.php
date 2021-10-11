<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderFeedExpense;
use Illuminate\Http\Request;

class OrderFeedExpenseController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
            'farm' => 'required|exists:farms,id'
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $currentUser->farms()->where('farms.id', $request->farm)->firstOrFail();

        $orderFeedExpense = OrderFeedExpense::create([
            'name' => $request->name,
            'date' => $request->date,
            'price' => $request->price,
            'weight' => $request->weight,
            'farm_id' => $request->farm
        ]);

        return response()->success($orderFeedExpense);
    }
}
