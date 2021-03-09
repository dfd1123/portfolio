<?php

namespace App\Http\Controllers\User_ver;

use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Review;

use DB;
use File;

class ReviewController extends Controller
{
    public function index(Request $request){
        $req = $request->req;

        switch($req){
            case 'default_list':
                return $this->default_list($request);
            case 'more_list':
                return $this->more_list($request);
        }


    }

    public function show($review){
        $review_item = DB::table('review')->where('rv_no',$review)->first();

        if($review_item != NULL){
            $response = array(
                "status" => 1,
                "review" => $review_item,
                "rv_imgs" => json_decode($review_item->rv_imgs),
            );
        }else{
            $response = array(
                "status" => 0,
            );
        }

        return response()->json($response);
    }

    public function store(Request $request){
        if ($request->filled('req')) {
            $req = $request->input('req');
            switch($req){
                case 'write':
                    return $this->write($request);
                case 'upldimg':
                    return $this->uploadImg($request);
                case 'deleteimg':
                    return $this->deleteImg($request);
            }
        }else{

        }
    }

    public function update($review){

    }

    public function destroy(Request $request, $review){
        $status = 0;
        $review_item = DB::table('review')->where('rv_no', $review)->first();

        foreach(json_decode($review_item->rv_imgs) as $rv_img){

            $img_path = "../storage/app/".config('filesystems.review_photo').$rv_img;

            if(File::exists($img_path)) {
                if(File::delete($img_path)){
                    $status = 1;
                }
            }
        }
        
        if($status){
            DB::table('agent_info')->where('agent_no', $agent_no)->update([
                "agent_rating" => DB::raw('(agent_rating - '.$review_item->rv_point.')/2'),
                "agent_review_cnt" => DB::raw('agent_rating - 1'),
            ]);

            $status = DB::table('review')->where('rv_no', $review)->delete();
        }

        $response = array(
            "status" => $status,
        );

        return response()->json($response);
    }

    private function default_list(Request $request){
        $limit = 10;

        $agent_no = $request->agent_no;

        $reviews = DB::table('review')
                    ->join('users', 'users.user_no', '=', 'review.client_no')
                    ->where('agent_no',$agent_no)
                    ->orderBy('rv_no','desc')
                    ->get();

        $response = array(
            "reviews" => $reviews,
            "reviews_cnt" => count($reviews),
            "offset" => $limit,
        );

        return response()->json($response);
    }

    private function more_list(Request $request){
        $offset = $request->offset;
        $agent_no = $request->agent_no;
        $limit = 10;

        $reviews = DB::table('review')
                    ->join('users', 'users.user_no', '=', 'review.client_no')
                    ->where('agent_no',$agent_no)
                    ->orderBy('rv_no','desc')
                    ->offset($offset)->limit($limit)->get();

        $offset += count($reviews);

        $response = array(
            "reviews" => $reviews,
            "offset" => $limit,
        );

        return response()->json($response);
    }

    private function write(Request $request){
        $rv_no = $request->rv_no;
        $trd_no = $request->trd_no;
        $agent_no = $request->agent_no;
        $title = $request->title;
        $conetent = $request->content;
        $rating = $request->rating;

        if($rv_no == ''){
            $review = Review::create([
                "rv_title" => $title,
                "rv_content" => $conetent,
                "client_no" => auth()->user()->user_no,
                "agent_no" => $agent_no,
                "ctrt_no" => $trd_no,
                "rv_point" => $rating,
            ]);

            if($review != NULL){
                $rv_no = $review->rv_no;
                $status = 1;
                DB::table('agent_info')->where('agent_no', $agent_no)->update([
                    "agent_rating" => DB::raw('(agent_rating+'.$review->rv_point.')/2'),
                    "agent_review_cnt" => DB::raw('agent_rating+1'),
                ]);
            }
        }else{
            $status = DB::table('review')->where('rv_no', $rv_no)->update([
                "rv_title" => $title,
                "rv_content" => $conetent,
                "rv_point" => $rating,
            ]);
        }

        $response = array(
            "status" => $status,
            "rv_no" => $rv_no,
        );

        return response()->json($response);

    }

    private function uploadImg(Request $request){
        $rv_no = $request->rv_no;
        $trd_no = $request->trd_no;
        $agent_no = $request->agent_no;
        $index = $request->index;

        
        
        //확장자 제한에서 체크해야함
        if ($request->hasFile('images') && $request->file('images')->isValid()) {
            $file = $request->file('images');
            $img = InterventionImage::make($file)->orientate();

            if ($img->width() >= 1000) {
                $img->resize(700, null, function ($constraint) {
                    $constraint->aspectRatio(); //비율유지
                })->encode('jpg');
            } else {
                $img->encode('jpg');
            }

            $hash = '/'.md5($img->__toString(). time());

            $file_name = $hash.'.jpg';
            
            $path = "../storage/app/".config('filesystems.review_photo').$hash.".jpg";
            
            $img->save($path);

            if($index == 1){

                $tempArr = array(
                    $file_name
                );

                $review = Review::create([
                                "rv_imgs" => json_encode($tempArr),
                                "client_no" => auth()->user()->user_no,
                                "agent_no" => $agent_no,
                                "ctrt_no" => $trd_no,
                            ]);

                if($review != NULL){
                    $rv_no = $review->rv_no;
                    DB::table('agent_info')->where('agent_no', $agent_no)->update([
                        "agent_review_cnt" => DB::raw('agent_rating+1'),
                    ]);
                    $status = 1;
                }

            }else{

                $review = DB::table('review')->where('rv_no', $rv_no)->first();

                $rv_imgs = json_decode($review->rv_imgs);
                array_push($rv_imgs, $file_name);
                DB::table('review')->where('rv_no', $review->rv_no)->update([
                    "rv_imgs" => json_encode($rv_imgs),
                ]);
            }
            

            $response = array(
                "status" => 1,
                "file_name" => $file_name,
                "rv_no" => $rv_no,
            );
        }else{
            $response = array(
                "status" => 2,
            );
        }

        return response()->json($response);
    }

    private function deleteImg(Request $request){
        $status = 0;
        $rv_no = $request->rv_no;
        $agent_no = $request->agent_no;
        $img_name = $request->img_name;
        $img_path = "../storage/app/".config('filesystems.review_photo').$img_name;

        $review = DB::table('review')->where('rv_no',$rv_no)->first();
        $rv_imgs = json_decode($review->rv_imgs);
        //dd(count((array)$trd_img));
        $key = array_search($img_name, (array)$rv_imgs);
        $rv_imgs = (array)$rv_imgs;
        if (false !== $key) {
            unset($rv_imgs[$key]);
        }

        if(File::exists($img_path)) {
            if(File::delete($img_path)){

                $status = DB::table('review')->where('rv_no',$review->rv_no)->update([
                    "rv_imgs" => json_encode($rv_imgs),
                ]);
                
            }
        }

        $response = array(
            "status" => $status,
            "rv_imgs" => $rv_imgs,
            "rv_no" => $rv_no,
        );

        return response()->json($response);
    }
}
