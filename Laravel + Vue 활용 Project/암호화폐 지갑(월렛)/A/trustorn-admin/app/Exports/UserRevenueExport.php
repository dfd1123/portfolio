<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class UserRevenueExport implements FromCollection, ShouldAutoSize
{
    public function __construct($from, $to) {
        $this->from = $from.'-01';
        $this->to = $to.'-30';
    }

    public function collection()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','2048M');
        
        $columns = ['uid', 'fullname', 'email', 'date', 'revenue', 'coin_retention', 'return_invest'];
        $users = DB::table('monthly_personal_revenue')
                ->leftJoin('users','monthly_personal_revenue.uid', '=' , 'users.id')
                ->orderBy('monthly_personal_revenue.created_at', 'desc')
                ->select($columns);

        if(!empty($this->from)) {
            $users = $users->whereDate('monthly_personal_revenue.created_at', '>=', $this->from." 00:00:00");
        }
        if(!empty($this->to)) {
            $users = $users->whereDate('monthly_personal_revenue.created_at', '<=', $this->to." 23:59:59");
        }

        //dd($users);

        return $users->get()->prepend($columns);
    }
}