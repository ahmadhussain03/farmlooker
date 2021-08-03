<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $user = User::findOrFail(auth()->id());
            $notifications = $user->notifications()->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $notifications
            ]);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
