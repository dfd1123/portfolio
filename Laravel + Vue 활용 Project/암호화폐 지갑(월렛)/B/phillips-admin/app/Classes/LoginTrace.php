<?php

namespace App\Classes;

use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

use Carbon\Carbon;

use Auth;
use DB;
use Log;
use Request;

class LoginTrace {
	public function trace($request) {
        $agent = new Agent();

        $time = Carbon::now()->toDateTimeString();
        $ip = $request->ip();

        $device = $agent->device();
        $device_kind = 'Unknown';
        if($agent->isMobile()) {
            $device_kind = 'Mobile';
        } else if ($agent->isDesktop()) {
            $device_kind = 'Desktop';
        } else if ($agent->isPhone()) {
            $device_kind = 'Phone';
        } else if($agent->isTablet()) {
            $device_kind = 'Tablet';
        }

        $browser = $agent->browser() . ' (' . $agent->version($agent->browser()) . ') ';
        $os = $agent->platform();
        $lang = implode('|', $agent->languages());

        try {
            $position = Location::get($ip);
            $location = $position->countryName . ' ' . $position->regionName . ' ' . $position->cityName;
        } catch(Exception $e) {}
        
        DB::connection('mysql_sub')->table('btc_login_trace')->insert([
            'time' => $time,
            'uid' => Auth::user()->id,
            'email' => Auth::user()->email,
            'mobile_number' => Auth::user()->mobile_number,
            'fullname' => Auth::user()->fullname,
            'ip' => $ip,
            'device' => $device,
            'device_kind' => $device_kind,
            'browser' => $browser,
            'os' => $os,
            'lang' => $lang,
            'location' => $location
        ]);
	}

	public function Activity($activity_log){ //관리자 활동내역
		$admin = Auth::guard('admin')->user();

		$agent = new Agent();

        $time = Carbon::now()->toDateTimeString();
       	$ip = Request::ip();
		
        $device = $agent->device();
        $device_kind = 'Unknown';
        if($agent->isMobile()) {
            $device_kind = 'Mobile';
        } else if ($agent->isDesktop()) {
            $device_kind = 'Desktop';
        } else if ($agent->isPhone()) {
            $device_kind = 'Phone';
        } else if($agent->isTablet()) {
            $device_kind = 'Tablet';
        }

        $browser = $agent->browser() . ' (' . $agent->version($agent->browser()) . ') ';
        $os = $agent->platform();
        $lang = implode('|', $agent->languages());
		if($ip == '127.0.0.1'){
			$location = 'local';
		}else{
	        try {
	            $position = Location::get($ip);
	            $location = $position->countryName . ' ' . $position->regionName . ' ' . $position->cityName;
	        } catch(Exception $e) {}
		}
		
        DB::connection('mysql_sub')->table('btc_admin_activity')->insert([
            'admin_id' => $admin->id,
            'admin_fullname' => $admin->email,
            'admin_email' => $admin->mobile_number,
            'admin_mobile' => $admin->fullname,
            'admin_ip' => $ip,
            'admin_device' => $device,
            'admin_device_kind' => $device_kind,
            'admin_browser' => $browser,
            'admin_os' => $os,
            'admin_lang' => $lang,
            'admin_location' => $location,
            'activity_log' => $activity_log,
            'created_at' => DB::raw('now()')
        ]);
	}
}
