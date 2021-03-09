<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;

class QnaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qnas = DB::connection('mysql_sub')
            ->table("btc_qna")
            ->select(
                'id',
                'title',
                'view',
                'answered',
                'createdby',
                'created'
            )
            ->where('createdby', Auth::user()->username)
            ->where('country', Auth::user()->country)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($qnas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->title;
        $description = $request->description;

        $qnas_not_answered = DB::connection('mysql_sub')
            ->table("btc_qna")
            ->where('createdby', Auth::user()->username)
            ->where('answered', 0)
            ->count();

        if ($qnas_not_answered > 2) {
            return response()->json([
                'code' => '-1',
                'message' => 'not answered qnas exist'
            ], 422);
        }

        if (empty($title) || empty($description)) {
            return response()->json([
                'code' => '-2',
                'message' => 'title or description is empty'
            ], 422);
        }

        $qna = DB::connection('mysql_sub')
            ->table("btc_qna")
            ->insertGetId([
                'title' => $title,
                'description' => $description,
                'trademarket_type' => Auth::user()->market_type,
                'country' => Auth::user()->country,
                'answered' => 0,
                'createdby' => Auth::user()->username,
                'created' => time(),
                'updated' => time()
            ]);

        return response()->json($qna, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $qna = DB::connection('mysql_sub')
            ->table("btc_qna")
            ->where('id', $id)
            ->where('createdby', Auth::user()->username)
            ->first();

        if (empty($qna)) {
            return response()->json(null);
        }

        $qna_comment = DB::connection('mysql_sub')
            ->table("btc_qna_comment")
            ->where('qna_id', $id)
            ->get();

        $qna->comments = $qna_comment;

        return response()->json($qna);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $title = $request->title;
        $description = $request->description;

        if (empty($title) || empty($description)) {
            return response()->json([
                'code' => '-1',
                'message' => 'title or description is empty'
            ], 422);
        }

        DB::connection('mysql_sub')
            ->table("btc_qna")
            ->where('id', $id)
            ->where('createdby', Auth::user()->username)
            ->update([
                'title' => $request->title,
                'description' => $request->description,
                'updated' => time()
            ]);

        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
