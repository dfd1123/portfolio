<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Classes\UserCpTestResult;
use Facades\App\Classes\UserCpTest;
use Facades\App\Classes\Batch;
use Auth;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CpTestResultRawTotalExport;

class UserCpTestResultController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request, $id)
    {
        $user_id = 3;

        switch ($id) {
            case 1:
                $latest_batch_id = Batch::latest();
                return response()->json(UserCpTestResult::result_1($user_id, $latest_batch_id));
            case 2:
                return response()->json(UserCpTestResult::result_2($user_id));
            break;
        }

        return abort(404);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        //
    }

    public function raw_total(Request $request)
    {
        $batch_id = $request->batch_id;
        if (!$batch_id) {
            abort(500);
        }

        return Excel::download(new CpTestResultRawTotalExport($batch_id), 'raw_total.xlsx');
    }
    
    public function delete_record(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            abort(500);
        }

        $batch_id = $request->batch_id;
        if (!$batch_id) {
            abort(500);
        }

        return response()->json(UserCpTest::delete_record($user_id, $batch_id));
    }
}
