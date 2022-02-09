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
            $search = strtolower($request->search);
            $countriesQuery->whereRaw('lower(name) like (?)',["%{$search}%"]);
        }

        if($request->has('all')){
            $countries = $countriesQuery->get();
        } else {
            $countries = $countriesQuery->paginate($perPage);
        }

        return response()->success($countries);
    }

    public function states(Request $request, $id)
    {
        $statesQuery = State::query()->where('country_id', $id);

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        if($request->has('search')){
            $search = strtolower($request->search);
            $statesQuery->whereRaw('lower(name) like (?)',["%{$search}%"]);
        }

        if($request->has('all')){
            $states = $statesQuery->get();
        } else {
            $states = $statesQuery->paginate($perPage);
        }


        return response()->success($states);
    }

    public function cities(Request $request, $id)
    {
        $citiesQuery = City::query()->where('state_id', $id);

        $perPage = $request->has('limit') ? intval($request->limit) : 10;

        if($request->has('search')){
            $search = strtolower($request->search);
            $citiesQuery->whereRaw('lower(name) like (?)',["%{$search}%"]);
        }

        if($request->has('all')){
            $cities = $citiesQuery->get();
        } else {
            $cities = $citiesQuery->paginate($perPage);
        }


        return response()->success($cities);
    }
}
