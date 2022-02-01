<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index()
    {
        /** @var App\Models\User */
        $user = auth()->user();
        $plans = Plan::all();

        return response()->success(['intent' => $user->createSetupIntent(), 'plans' => $plans]);
    }
}
