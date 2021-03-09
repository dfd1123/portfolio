<?php

namespace TLCfund\Http\Controllers;

use Jenssegers\Agent\Agent;

use TLCfund\User;
use TLCfund\Review;
use Illuminate\Http\Request;
use Auth;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
		$agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = User::where('id',$request->id)->first();
		
		if($user->level == 1){
			$review_kind = 1;
		}else if($user->level == 2){
			$review_kind = 2;
		}
		
        $input = $request->all();
		
		return Review::create([
        	'art_id' => $id,
        	'review_kind' => $review_kind,
        	'writer_id' =>$user->user_id,
        	'writer_name' => $user->name,
        	'profile_img' => $user->profile_img,
        	'unickname' => $user->nickname,
        	'review_body' => $request->review_body,
        	'rating' => $request->rating,
        ]);
    }
	
	public function storet(Request $request, $id)
    {
        $user = Auth::user();
		
        $input = $request->all();
		
		if($user->profile_img == NULL){
			$profile_img='';
		}
		
		return Review::create([
        	'art_id' => $id,
        	'writer_id' =>$user->user_id,
        	'writer_name' => $user->name,
        	'profile_img' => $profile_img,
        	'unickname' => $user->nickname,
        	'review_body' => $request->input('review_body'),
        	'rating' => $request->input('rating'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
