<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;


class Common extends Facade{
    protected static function getFacadeAccessor() { return 'common'; }
}