<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ExpenseChartController extends Controller
{
    public function index()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $data = $currentUser->expenses()->select([DB::raw("SUM(expenses.amount) AS price"), DB::raw("MONTH(expenses.dated) AS month"), DB::raw("YEAR(expenses.dated) AS year")])->groupBy(DB::raw("MONTH(expenses.dated)"), DB::raw("YEAR(expenses.dated)"), 'farm_user.user_id')->whereRaw("YEAR(expenses.dated) = ?", [now()->year])->get();

        return response()->success($data);
    }
}
