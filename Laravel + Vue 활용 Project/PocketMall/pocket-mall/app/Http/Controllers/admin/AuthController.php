<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Hash;
use Session;
use Redirect;

class AuthController extends Controller
{
    public function login() {
        $views = view('admin.auth.login');

        return $views;
    }

    public function login_form(Request $request) {

        if (Auth::guard('admin') -> attempt(['email' => $request -> input('email'), 'password' => $request -> input('password')])) {
            return redirect() -> route('admin.invoices');

        } else {
            return redirect() -> back() -> with('jsAlert', '아이디나 비밀번호가 맞지 않습니다.');
        }

        $views = view('admin.auth.login');

        return $views;
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        Session::flush();

        return Redirect::route('admin.login');
    }
}
