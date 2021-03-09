<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;

class UsersExport implements FromCollection, ShouldAutoSize
{
    public function collection()
    {
        $columns = ['id', 'username', 'fullname', 'email', 'country', 'mobile_number', 'ip', 'created_at', 'updated_at'];
        return DB::table('users')->select($columns)->get()->prepend($columns);
    }
}