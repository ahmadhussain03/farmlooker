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
        $data = $currentUser->expenses()->select([DB::raw("SUM(expenses.amount) AS price"), DB::raw("MONTH(expenses.created_at) AS month"), DB::raw("YEAR(expenses.created_at) AS year")])->groupBy(DB::raw("MONTH(expenses.created_at)"), DB::raw("YEAR(expenses.created_at)"), 'farm_user.user_id')->whereRaw("YEAR(expenses.created_at) = ?", [now()->year])->get();

        return response()->success($data);
    }
}
