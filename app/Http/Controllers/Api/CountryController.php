<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;

class CountryController extends Controller
{
    public function countries()
    {
        $countries = Country::all();
        return response()->success($countries);
    }

    public function states($id)
    {
        $states = State::where('country_id', $id)->get();

        return response()->success($states);
    }

    public function cities($id)
    {
        $cities = City::where('state_id', $id)->get();

        return response()->success($cities);
    }
}
