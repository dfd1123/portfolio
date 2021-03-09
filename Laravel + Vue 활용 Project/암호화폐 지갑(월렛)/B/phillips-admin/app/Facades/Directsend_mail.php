<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;


class Directsend_mail extends Facade{
    protected static function getFacadeAccessor() { return 'directsend_mail'; }
}
