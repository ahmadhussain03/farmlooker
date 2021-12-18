<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use App\Models\Animal;
use App\Models\Salary;
use Illuminate\Http\Request;
use App\Models\Miscelleneous;
use App\Models\OrderFeedExpense;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {

         /** @var App\Models\User */
         $currentUser = auth()->user();
         $expenseQuery = $currentUser->expenses()->with(['farm'])->orderBy('dated', 'desc');
         $expenseQuery->select(["expenses.dated", "expenses.expenseable_type", "expenses.farm_id", "expenses.amount", "expenses.id as expenseId", "farms.*"]);

        if($request->has('client') && $request->client === 'datatable'){

            return DataTables::eloquent($expenseQuery)
               ->addColumn('expense_type', function($expense){
                   switch($expense->expenseable_type){
                       case Animal::class:
                           return 'Animal Purchased';
                           break;
                       case Salary::class:
                           return 'Salary';
                           break;
                        case OrderFeedExpense::class:
                            return 'Order Feed';
                            break;
                        case Miscelleneous::class:
                            return 'Miscelleneous';
                            break;
                       default:
                           return $expense->expenseable_type;
                   }
               })
               ->setRowId('expenseId')
               ->addIndexColumn()
               ->toJson();
       }

       if($request->has('search') && $request->search != ""){
            $search = $request->search;
            $expenseQuery
                ->where('amount', 'like', '%' . $search . '%')
                ->orWhere('expenseable_type', 'like', '%' . $search . '%')
                ->orWhere('dated', 'like', '%' . $search . '%')
                ->orWhereHas('farm', function($query) use ($search){
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        $expenses = $expenseQuery->paginate($perPage);

        return response()->success($expenses);
    }

    public function show()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $purchaseTotal = $currentUser->expenses()->sum('expenses.amount');

        return response()->success($purchaseTotal);
    }

    public function summary()
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();

        $currentMonthExpense = $currentUser->expenses()->select([DB::raw("SUM(expenses.amount) AS price")])->groupBy(DB::raw("MONTH(expenses.dated)"), DB::raw("YEAR(expenses.dated)"), 'farm_user.user_id')->whereRaw("MONTH(expenses.dated) = ?", [now()->month])->whereRaw("YEAR(expenses.dated) = ?", [now()->year])->first();
        $totalExpense = $currentUser->expenses()->sum('expenses.amount');
        $animalExpense = $currentUser->expenses()->where('expenses.expenseable_type', Animal::class)->sum('expenses.amount');
        $salaries = $currentUser->expenses()->where('expenses.expenseable_type', Salary::class)->sum('expenses.amount');
        $orderFeedExpense = $currentUser->expenses()->where('expenses.expenseable_type', OrderFeedExpense::class)->sum('expenses.amount');
        $miscelleneous = $currentUser->expenses()->where('expenses.expenseable_type', Miscelleneous::class)->sum('expenses.amount');

        return response()->success(['current_month_expense' => $currentMonthExpense->price ?? 0, 'total_expense' => $totalExpense, 'animal_expense' => $animalExpense, 'salaries' => $salaries, 'order_feed_expense' => $orderFeedExpense, 'miscelleneous' => $miscelleneous]);
    }
}
