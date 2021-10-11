<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Miscelleneous;
use Illuminate\Http\Request;

class MiscelleneousController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'farm' => 'required|exists:farms,id'
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $currentUser->farms()->where('farms.id', $request->farm)->firstOrFail();

        $miscelleneous = Miscelleneous::create([
            'reason' => $request->reason,
            'amount' => $request->amount,
            'farm_id' => $request->farm
        ]);

        return response()->success($miscelleneous);
    }
}
