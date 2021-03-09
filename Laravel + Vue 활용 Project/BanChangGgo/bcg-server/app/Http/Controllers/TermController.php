<?php

namespace App\Http\Controllers;

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
        //
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
