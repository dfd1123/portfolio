<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;


class Nexmo_sms extends Facade{
    protected static function getFacadeAccessor() { return 'nexmo_sms'; }
}