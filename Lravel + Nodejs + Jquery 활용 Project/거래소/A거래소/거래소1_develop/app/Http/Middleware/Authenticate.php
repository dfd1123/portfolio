<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }else{
        	$google_verified = DB::table('btc_security_lv')->where('uid',Auth::user()->id)->first()->google_verified;
	    	if($google_verified == 1){
	    		return redirect('otp_input');
	    	}
        }
    }
}
