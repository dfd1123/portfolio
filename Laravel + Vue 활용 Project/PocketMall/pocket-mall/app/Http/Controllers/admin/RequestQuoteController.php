<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class RequestQuoteController extends Controller
{
    public function index(){
        $invoices = DB::table('request_quote')->orderBy('rq_id', 'DESC')->paginate(30);

        $invoices->withPath('/admin/invoices');

        $views = view('admin.invoices.list');

        $views->invoices = $invoices;
        
        return $views;
    }
}
