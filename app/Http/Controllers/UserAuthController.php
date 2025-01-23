<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    //
    function login(Request $request){
        return"login function";
    }
    function signup(Request $request){
        return"signup function";
    }
}
