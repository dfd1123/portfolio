<?php

namespace App\Http\Controllers\Company_ver;

use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Comment;
use DB;
use File;

class AgentCommentController extends Controller
{
    public function show($comment){
        $user_trd_comment = DB::table('user_trd_comment')->where('ucc_no',$comment)->first();

        if($user_trd_comment != NULL){
            if($user_trd_comment->confirm==0){
                DB::table('user_trd_comment')->where('ucc_no',$comment)->update([
                    "confirm" => 1,
                ]);
            }
    
            $response = array(
                "status" => 1,
                "user_trd_comment" => $user_trd_comment,
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
                case 'uplddraws':
                    return $this->uploadFile($request);
                case 'deleteimg':
                    return $this->deleteImg($request);
                case 'deletefile':
                    return $this->deleteFile($request);
            }
        }else{

        }
    }

    public function update($comment){

    }

    public function destroy(Request $request, $comment){
        $status = 0;
        $user_trd_comment = DB::table('user_trd_comment')->where('ucc_no', $comment)->where('agent_no',auth()->user()->user_no)->first();

        foreach(json_decode($user_trd_comment->ucc_imgs) as $ucc_img){

            $img_path = "../storage/app/".config('filesystems.comment_photo').$ucc_img;

            if(File::exists($img_path)) {
                if(File::delete($img_path)){
                    $status = 1;
                }
            }
        }
        
        if($status){
            $status = DB::table('user_trd_comment')->where('ucc_no', $comment)->where('agent_no',auth()->user()->user_no)->delete();
        }

        $response = array(
            "status" => $status,
        );

        return response()->json($response);
    }

    private function delete($comment){

    }

    private function write(Request $request){
        $ucc_no = $request->ucc_no;
        $trd_no = $request->trd_no;
        $title = $request->title;
        $conetent = $request->content;

        if($ucc_no != ''){
            $status = DB::table('user_trd_comment')->where('ucc_no', $ucc_no)->where('agent_no', auth()->user()->user_no)->update([
                "ucc_title" => $title,
                "ucc_comment" => $conetent,
            ]);
        }else{
            $user_trd_comment = Comment::create([
                "ucc_title" => $title,
                "ucc_comment" => $conetent,
                "trd_no" => $trd_no,
                "agent_no" => auth()->user()->user_no,
            ]);

            if($user_trd_comment != NULL){
                $ucc_no = $user_trd_comment->ucc_no;
                $status = 1;
            }
        }

        if($status){
            DB::table('trades')->where('trd_no', $trd_no)->where('agent_no',auth()->user()->user_no)->update([
                "updated_at" => now(),
            ]);
        }

        $response = array(
            "status" => $status,
            "ucc_no" => $ucc_no,
        );

        return response()->json($response);

    }

    private function uploadImg(Request $request){
        $ucc_no = $request->ucc_no;
        $trd_no = $request->trd_no;
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
            
            $path = "../storage/app/".config('filesystems.comment_photo').$hash.".jpg";
            
            $img->save($path);

            if($index == 1){
                if($ucc_no != ''){
                        $tempArr = array(
                            $file_name
                        );
        
                        DB::table('user_trd_comment')->where('ucc_no', $user_trd_comment->ucc_no)->where('agent_no',auth()->user()->user_no)->update([
                            "ucc_imgs" => json_encode($tempArr),
                        ]);
                }else{
                    $tempArr = array(
                        $file_name
                    );

                    $user_trd_comment = Comment::create([
                        "ucc_imgs" => json_encode($tempArr),
                        "trd_no" => $trd_no,
                        "agent_no" => auth()->user()->user_no,
                    ]);

                    $ucc_no = $user_trd_comment->ucc_no;
                }
            }else{
                $user_trd_comment = DB::table('user_trd_comment')
                                    ->where('ucc_no', $ucc_no)
                                    ->where('agent_no',auth()->user()->user_no)
                                    ->first();

                $ucc_imgs = json_decode($user_trd_comment->ucc_imgs);
                array_push($ucc_imgs, $file_name);
                DB::table('user_trd_comment')->where('ucc_no', $user_trd_comment->ucc_no)->update([
                    "ucc_imgs" => json_encode($ucc_imgs),
                ]);
            }
            

            $response = array(
                "status" => 1,
                "file_name" => $file_name,
                "ucc_no" => $ucc_no,
            );
        }else{
            $response = array(
                "status" => 2,
            );
        }

        return response()->json($response);
    }

    private function uploadFile($request)
    {
        $ucc_no = $request->ucc_no;
        $trd_no = $request->trd_no;
        $index = $request->index;
        $path = '';
        $file_name='';
        $storage_path = "../storage/app/";
        $save_path = "public/file/comment/estimate/";
        
		if($index <= 2){
            if($file = $request->file('files')){
                if ($file->isValid()) {
                    $file_name = $file->getClientOriginalName();
                    $path = $file->storeAs($save_path, $file_name);
                    $path = str_replace($save_path,"",$path);
                }	
            }
            
            if($ucc_no != ''){
                $user_trd_comment = DB::table('user_trd_comment')->where('ucc_no', $ucc_no)->where('agent_no',auth()->user()->user_no)->first();

                if($user_trd_comment->ucc_files == null || $user_trd_comment->ucc_files == '[]'){
                    $tempArr = array(
                        $path,
                    );
                    DB::table('user_trd_comment')->where('ucc_no', $ucc_no)->where('agent_no', auth()->user()->user_no)->update([
                        "ucc_files" => json_encode($tempArr),
                    ]);
                }else{
    
                    $ucc_files = json_decode($user_trd_comment->ucc_files);
                    array_push($ucc_files, $path);
                    DB::table('user_trd_comment')->where('trd_no', $trd_no)->where('agent_no', auth()->user()->user_no)->update([
                        "ucc_files" => json_encode($ucc_files),
                    ]);
                }
            }else{
                $user_trd_comment = Comment::create([
                    "ucc_files" => json_encode($tempArr),
                    "trd_no" => $trd_no,
                    "agent_no" => auth()->user()->user_no,
                ]);

                $ucc_no = $user_trd_comment->ucc_no;
            }

            $response = array(
                "status" => 1,
                "file_name" => $file_name,
                "index" => $index,
                "ucc_no" => $ucc_no,
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
        $ucc_no = $request->ucc_no;
        $trd_no = $request->trd_no;
        $img_name = $request->img_name;
        $img_path = "../storage/app/".config('filesystems.comment_photo').$img_name;

        $user_trd_comment = DB::table('user_trd_comment')->where('ucc_no',$ucc_no)->first();
        $ucc_imgs = json_decode($user_trd_comment->ucc_imgs);
        //dd(count((array)$trd_img));
        $key = array_search($img_name, (array)$ucc_imgs);
        $ucc_imgs = (array)$ucc_imgs;
        if (false !== $key) {
            unset($ucc_imgs[$key]);
        }
        
        if(File::exists($img_path)) {
            if(File::delete($img_path)){
                if(count($ucc_imgs) == 0 && $user_trd_comment->ucc_files == NULL){
                    $status = DB::table('user_trd_comment')->where('ucc_no',$ucc_no)->where('agent_no',auth()->user()->user_no)->delete();
                }else{
                    $status = DB::table('user_trd_comment')->where('ucc_no',$ucc_no)->where('agent_no',auth()->user()->user_no)->update([
                        "ucc_imgs" => json_encode($ucc_imgs),
                    ]);
                }
            }
        }

        $response = array(
            "status" => $status,
            "trd_img" => $ucc_imgs,
        );

        return response()->json($response);
    }
}
