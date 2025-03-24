<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function profile(Request $request)
     {
        return $request->user();
    }

    public function getUserDetails(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'msg' => 'User details retrieved successfully'
        ]);
    }

    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->get();
        return response()->json(['orders' => $orders]);
    }

}
