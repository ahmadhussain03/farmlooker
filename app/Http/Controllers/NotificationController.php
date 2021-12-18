<?php

namespace App\Http\Controllers;

use App\Jobs\SendBulkFcm;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return view('admin.notification.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'users' => 'required|in:all,admin,super_admin,moderator',
            'message' => 'required|string'
        ]);

        SendBulkFcm::dispatch($request->users, $request->message)->onQueue('fcm');

        session()->flash('success', 'FCM Sent Successfully!');
        return redirect()->route('admin.notification.create');
    }
}
