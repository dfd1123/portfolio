<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent; 

use Auth;
use DB;

class QnaController extends Controller
{
    public function __construct()
    {
		$this->middleware('auth');
        $agent = new Agent();
		$this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';

    }
	
	public function index(){
		$views = view(session('theme').'.'.$this->device.'.'.'qna.qna_list');	

		if($this->device == 'pc'){
			$qnas = DB::connection('mysql_sub')->table('btc_qna')->where('createdby',Auth::user()->username)->orderBy('created','desc')->paginate(15);

			$qnas->withPath('qna');
		}else{
			$count = DB::connection('mysql_sub')->table('btc_qna')->where('createdby',Auth::user()->username)->orderBy('created','desc')->count();
			$qnas = DB::connection('mysql_sub')->table('btc_qna')->where('createdby',Auth::user()->username)->orderBy('created','desc')->limit(15)->get();
		
			$views->count = $count;
		}

		$views->qnas = $qnas;

		return $views;
	}
	
	public function show($id){
		$views = view(session('theme').'.'.$this->device.'.'.'qna.qna_show');
		
		$qna = DB::connection('mysql_sub')->table('btc_qna')->where('id',$id)->first();

		$qna_answer = DB::connection('mysql_sub')->table('btc_qna_comment')->where('qna_id',$id)->first();
		
		$views->qna = $qna;
		$views->qna_answer = $qna_answer;
		$views->id = $id;
		
		return $views;
	}

	public function insert(Request $request){
		if($request->qna_submit == 'create'){
			DB::connection('mysql_sub')->table('btc_qna')->insert([
				'trademarket_type' => session('market_type'),
				'country' => Auth::user()->country,
				'title' => $request->input('title'),
				'description' => str_replace("\n","<br />",$request->input('description')),
				'createdby' => Auth::user()->username,
				'created' => time(),
				'updated' => time(),
			]);
		}else if($request->qna_submit == 'edit'){
			DB::connection('mysql_sub')->table('btc_qna')->where('id',$request->id)->update([
				'trademarket_type' => session('market_type'),
				'title' => $request->input('title'),
				'description' => str_replace("\n","<br />",$request->input('description')),
				'createdby' => Auth::user()->username,
				'updated' => time(),
			]);
		}

		return redirect()->back();
	}
}
