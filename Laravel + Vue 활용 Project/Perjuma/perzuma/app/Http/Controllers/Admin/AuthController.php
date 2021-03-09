<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use DB;
use Hash;
use Auth;

class AuthController extends Controller
{

    use AuthenticatesUsers;


    public function login() {
        $views = view('admin.auth.login');

        return $views;
    }

    public function login_attemp(Request $request) {

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/admin/main');

        } else {
            return redirect() -> back() -> with('jsAlert', '아이디나 비밀번호가 맞지 않습니다.');
        }

    }

    public function admin_create(){
        $views = view('admin.auth.create');
        
        return $views;
    }
    
    public function admin_store(Request $request){
        DB::table('admins')->insert([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        //return Redirect::route('admin.auth.list');
    }

    public function logout(Request $request) {
        Auth::logout();
        Session::flush();

        return Redirect::route('admin.login');
    }
}
