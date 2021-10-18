<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IncomeChartController extends Controller
{
    public function index()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $data = $currentUser->incomes()->select([DB::raw("SUM(incomes.amount) AS price"), DB::raw("MONTH(incomes.created_at) AS month"), DB::raw("YEAR(incomes.created_at) AS year")])->groupBy(DB::raw("MONTH(incomes.created_at)"), DB::raw("YEAR(incomes.created_at)"), 'farm_user.user_id')->whereRaw("YEAR(incomes.created_at) = ?", [now()->year])->get();

        return response()->success($data);
    }
}
