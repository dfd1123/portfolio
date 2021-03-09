<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Facades\App\Classes\LoginInfo;

class CheckPlanner
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
        $user_id = LoginInfo::get();
        
        $sql = 'SELECT
                    state
                FROM
                    planner
                WHERE
                    pln_id = :user_id';
        $pln_state = DB::select($sql, array('user_id'=>$user_id));
        if (isset($pln_state[0]->state)) {
            if ($pln_state[0]->state != 1) {
                return redirect('/planner/intro')->with('jsAlert', '플래너 등록이 필요한 서비스입니다.');
            }
        }else{
            return redirect('/planner/intro')->with('jsAlert', '플래너 등록이 필요한 서비스입니다.');
        }

        return $next($request);
    }
}
