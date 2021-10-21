<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;

class CountryController extends Controller
{
    public function countries(Request $request)
    {
        $countriesQuery = Country::query();

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        if($request->has('search')){
            $countriesQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $countries = $countriesQuery->paginate($perPage);

        return response()->success($countries);
    }

    public function states(Request $request, $id)
    {
        $statesQuery = State::query()->where('country_id', $id);

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        if($request->has('search')){
            $statesQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $states = $statesQuery->paginate($perPage);

        return response()->success($states);
    }

    public function cities(Request $request, $id)
    {
        $citiesQuery = City::query()->where('state_id', $id);

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        if($request->has('search')){
            $citiesQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $cities = $citiesQuery->paginate($perPage);

        return response()->success($cities);
    }
}
