<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Exception;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{

    private $stripe;

    function __construct()
    {
        $this->stripe = new StripeClient(config("services.stripe.secret"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plan.list', ['plans' => $plans]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactive()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.plan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'interval' => 'required|in:week,day,year,month',
        ]);

        $currency = 'usd';

        try {

            $price = $this->stripe->prices->create([
                'unit_amount' => $request->amount * 100,
                'currency' => $currency,
                'recurring' => ['interval' => $request->interval],
                'product_data' => ['name' => $request->name],
                'nickname' => $request->description
            ]);

            Plan::create([
                'name' => $request->name,
                'description' => $request->description,
                'currency' => $currency,
                'stripe_id' => $price->id,
                'product' => $price->product,
                'interval' => $request->interval,
                'interval_count' => $price->recurring->interval_count,
                'amount' => $request->amount * 100
            ]);

            session()->flash('success', 'New Billing Plan Created!');
            return redirect()->route('admin.plan.index');

        } catch(Exception $ex){
            session()->flash('error', $ex->getMessage());
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $plan)
    {
        DB::beginTransaction();

        try {

            DB::commit();
        } catch(Exception $ex){
            DB::rollBack();
            session()->flash('error', $ex->getMessage());
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($plan)
    {
        $plan = Plan::findOrFail($plan);
        try {
            $this->stripe->prices->update($plan->stripe_id, ['active' => false]);
            $plan->delete();

            session()->flash('success', 'Plan Deleted Successfully!');

            return back();
        } catch(Exception $ex){
            session()->flash('error', $ex->getMessage());
            return back();
        }
    }
}
