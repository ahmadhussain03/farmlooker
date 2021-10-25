<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\Worker;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'worker' => 'required|integer|exists:workers,id',
            'pay' => 'required|numeric',
            'dated' => 'required|date'
        ]);

        $worker = Worker::findOrFail($request->worker);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $currentUser->farms()->where('farms.id', $worker->farm_id)->firstOrFail();

        $salary = Salary::create([
            'worker_id' => $request->worker,
            'pay' => $request->pay,
            'farm_id' => $worker->farm_id,
            'dated' => $request->dated
        ]);

        return response()->success($salary);
    }
}
