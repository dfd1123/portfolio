<?php

namespace App\Http\Middleware;

use Closure;

class CheckOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$google_verified = DB::table('btc_security_lv')->where('uid',Auth::user()->id)->first()->google_verified;
    	if($google_verified == 1){
    		return redirect('otp_input');
    	}
		
        return $next($request);
    }
}
