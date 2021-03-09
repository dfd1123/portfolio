<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;


class My_info extends Facade{
    protected static function getFacadeAccessor() { return 'my_info'; }
}