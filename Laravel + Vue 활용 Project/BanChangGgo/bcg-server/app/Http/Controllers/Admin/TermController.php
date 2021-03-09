<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\Term;

class TermController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        return response()->json(Term::index());
    }

    public function store(Request $request)
    {
        $params = [
            'version' => $request->input('version'),
            'terms_service' => $request->input('terms_service'),
            'privacy_policy' => $request->input('privacy_policy'),
            'pay_terms_service' => $request->input('pay_terms_service')
        ];

        return response()->json(Term::update($params));
    }

    public function show(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
