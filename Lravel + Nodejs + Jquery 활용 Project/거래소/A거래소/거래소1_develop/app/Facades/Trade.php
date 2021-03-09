<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;


class Trade extends Facade{
    protected static function getFacadeAccessor() { return 'trade'; }
}