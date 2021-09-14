<?php

namespace App\Http\Controllers\Api\Admin;

use RakibDevs\Weather\Weather;
use App\Http\Controllers\Controller;
use Stevebauman\Location\Facades\Location;

class WeatherController extends Controller
{

    private $weather;

    public function __construct(Weather $weather)
    {
        $this->weather = $weather;
    }

    public function index()
    {
        $location = Location::get(request()->ip());

        $weather = $this->weather->getCurrentByCord($location->latitude, $location->longitude);

        return response()->json($weather);
    }
}
