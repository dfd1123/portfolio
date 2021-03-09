<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use DateTime;
use Auth;

class Controller extends BaseController
{
    public function __construct()
    {
        Auth::setDefaultDriver('api');
    }

    protected function validTime($time, $format='H:i:s')
    {
        $d = DateTime::createFromFormat("Y-m-d $format", "2017-12-01 $time");
        return $d && $d->format($format) == $time;
    }
}
