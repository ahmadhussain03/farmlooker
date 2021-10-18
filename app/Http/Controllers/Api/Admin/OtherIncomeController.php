<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherIncome;
use Illuminate\Http\Request;

class OtherIncomeController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'farm' => 'required|integer'
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $currentUser->farms()->where('farms.id', $request->farm)->firstOrFail();

        $otherIncome = OtherIncome::create([
            'reason' => $request->reason,
            'amount' => $request->amount,
            'farm_id' => $request->farm
        ]);

        return response()->success($otherIncome);
    }
}
