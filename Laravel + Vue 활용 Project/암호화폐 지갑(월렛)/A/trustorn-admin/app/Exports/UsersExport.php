<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class UsersExport implements FromCollection, ShouldAutoSize
{
    public function __construct($from, $to) {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','2048M');
        
        $columns = ['id', 'username', 'fullname', 'email', 'country', 'mobile_number', 'ip', 'created_at', 'updated_at'];
        $users = DB::table('users')->select($columns);

        if(!empty($this->from)) {
            $users = $users->whereDate('created_at', '>=', $this->from." 00:00:00");
        }
        if(!empty($this->to)) {
            $users = $users->whereDate('created_at', '<=', $this->to." 23:59:59");
        }

        return $users->get()->prepend($columns);
    }
}