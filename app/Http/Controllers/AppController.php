<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AppController extends Controller
{
    public function index()
    {
        return view('app', [
            'user' => User::loggedIn() ? User::fromSession() : null
        ]);
    }
}
