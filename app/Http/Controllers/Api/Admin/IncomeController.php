<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use App\Models\AnimalSold;
use App\Models\OtherIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IncomeController extends Controller
{

    public function index(Request $request)
    {
         /** @var App\Models\User */
         $currentUser = auth()->user();
         $incomeQuery = $currentUser->incomes()->with(['farm'])->orderBy('dated', 'desc');

         if($request->has('client') && $request->client === 'datatable'){
             $incomeQuery->select(["incomes.dated", "incomes.incomeable_type", "incomes.farm_id", "incomes.amount", "incomes.id as incomeId", "farms.*"]);
             return DataTables::eloquent($incomeQuery)
                ->addColumn('income_type', function($income){
                    switch($income->incomeable_type){
                        case AnimalSold::class:
                            return 'Animal Sold';
                            break;
                        case OtherIncome::class:
                            return 'Other Income';
                            break;
                        default:
                            return $income->incomeable_type;
                    }
                })
                ->setRowId('incomeId')
                ->addIndexColumn()
                ->toJson();
        }

        if($request->has('search') && $request->search != ""){
            $search = $request->search;
            $incomeQuery
                ->where('amount', 'like', '%' . $search . '%')
                ->orWhere('incomeable_type', 'like', '%' . $search . '%')
                ->orWhere('dated', 'like', '%' . $search . '%')
                ->orWhereHas('farm', function($query) use ($search){
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $incomes = $incomeQuery->paginate($perPage);

        return response()->success($incomes);
    }

    public function summary()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();

        $currentMonthIncome = $currentUser->incomes()->select([DB::raw("SUM(incomes.amount) AS price")])->groupBy(DB::raw("MONTH(incomes.dated)"), DB::raw("YEAR(incomes.dated)"), 'farm_user.user_id')->whereRaw("MONTH(incomes.dated) = ?", [now()->month])->whereRaw("YEAR(incomes.dated) = ?", [now()->year])->first();
        $totalIncome = $currentUser->incomes()->sum('incomes.amount');
        $animalSold = $currentUser->incomes()->where('incomes.incomeable_type', AnimalSold::class)->sum('incomes.amount');
        $otherIncome = $currentUser->incomes()->where('incomes.incomeable_type', OtherIncome::class)->sum('incomes.amount');

        return response()->success(['current_month_income' => $currentMonthIncome->price ?? 0, 'total_income' => $totalIncome, 'animal_sold' => $animalSold, 'other_income' => $otherIncome]);
    }
}
