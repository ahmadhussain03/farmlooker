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
        $data = $currentUser->animals()->select([DB::raw("SUM(animals.price) AS price"), DB::raw("MONTH(animals.purchase_date) AS month"), DB::raw("YEAR(animals.purchase_date) AS year")])->groupBy(DB::raw("MONTH(animals.purchase_date)"), DB::raw("YEAR(animals.purchase_date)"), 'farm_user.user_id')->where('animals.add_as', 'purchased')->whereRaw("YEAR(animals.purchase_date) = ?", [now()->year])->get();

        return response()->success($data);
    }
}
