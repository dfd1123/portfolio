<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;


class Secure extends Facade{
    protected static function getFacadeAccessor() { return 'secure'; }
}