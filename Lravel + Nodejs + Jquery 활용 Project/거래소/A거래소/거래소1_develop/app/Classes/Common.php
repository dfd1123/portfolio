<?php

namespace App\Classes;

use DB;

class Common {
	public function randomHash($lenght = 7){
        $random = substr(md5(rand()),0,$lenght);
        return $random;
    }

}
