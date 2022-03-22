<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    /**
     * Get All Devices from external API
     *
     * @return Response
     */
    public function index()
    {
        $result = Http::withBasicAuth(config('services.sigfox.username'), config('services.sigfox.password'))->get( config('services.sigfox.url') . 'devices')->throw();

        return response()->json($result->json());
    }
}
