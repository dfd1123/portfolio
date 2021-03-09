<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Auth;

class Controller extends BaseController
{
    public function __construct()
    {
        Auth::setDefaultDriver('user');
    }
}
