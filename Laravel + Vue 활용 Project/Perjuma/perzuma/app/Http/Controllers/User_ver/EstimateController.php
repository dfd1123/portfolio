<?php

namespace App\Http\Controllers\User_ver;

use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use DB;
use File;

class EstimateController extends Controller
{
    public function __construct()
    {
        $agent = new Agent();
        $this->device = ($agent->isDesktop()) ? 'pc' : 'mobile';
    }

    public function index(Request $request)
    {
        return $this->estimate_request($request, 1);
    }
    
    public function store(Request $request)
    {
        $p = $request->all();

        if(auth()->user()->state == 0){
            $verify = false;
        }else{
            $verify = true;
        }

        if ($request->filled('step')) {
            $step = $request->input('step');
            switch ($step) {
                default:
                case 1:
                //가게명, 주소
                return $this->step1_store($request, $verify);
                case 2:
                //업종 선택
                return $this->step2_store($request, $verify);
                case 3:
                //평수 및 예산
                return $this->step3_store($request, $verify);
                case 4:
                //날짜선택
                return $this->step4_store($request, $verify);
                case 5:
                //매장사진, 도면 업로드
                return $this->step5_store($request, $verify);
                case 6:
                //프리미엄, 베이직 선택
                return $this->step6_store($request, $verify);
                case 7:
                //최종확인
                return $this->step7_store($request, $verify);
                break;
            }
        }
    }

    //급하게진행함, R.F진행해야함
    public function show(Request $request, $step = 1)
    {
        switch ($step) {
            case 'list':
            return $this->business_list($request);
            case 'load':
            return $this->estimate_request_load($request);
            case 'chkhistory':
            return $this->check_wait_request($request);
            case 'step7load':
            return $this->step7load($request);
            break;
            case 'confirm':
            return $this->step7load($request);
            default:
            return $this->estimate_request($request, $step);
        }
    }
    
    private function step7load(Request $request)
    {
        $trd_no = $request->trd_no;

        $trade = DB::table('trades')->where('trd_no', $trd_no)->join('business_list', 'trades.bl_no', '=', 'business_list.bl_no')->join('users', 'trades.client_no', '=', 'users.user_no')->first();

        $response = array(
            "trade" => $trade,
            "trd_img" => json_decode($trade->trd_img),
            "user_name" => $trade->name,
        );

        return response()->json($response);
    }

    private function estimate_request(Request $request, $step)
    {
        $views = view('user_ver.estimate_request.step_'.$step);

        switch ($step) {
            case 1:
                $title = '가게명 및 주소 입력';
                break;
            case 2:
                $title = '업종 선택';
                break;
            case 3:
                $title = '평수 및 예산 선택';
                break;
            case 4:
                $title = '시공 예정일 선택';
                break;
            case 5:
                $title = '시공 관련 이미지 추가(선택)';
                break;
            case 6:
                $title = '매니저 옵션 추가';
                break;
            case 7:
            //result_confirm 경로를 step7로 수정해야함, css가 링크되어있어서 수정요청.
                $title ='최종확인';
                $views = view('user_ver.result_confirm.result_confirm');

                $views->title = '최종 확인';
                $views->ft_btn_name = '최종 확인';
                $views->trd_no = $request->id;
                break;
            case 8:
            //완료화면을 STEP8로 수정해야함
                break;
        }

        if (isset($request->id)) {
            $id = $request->id;
        } else {
            $id = '';
        }

        $views->index = $step;
        $views->title = $title;
        $views->trd_no = $id;
        $views->ft_btn_name = '선택 완료';

        return $views;
    }

    private function step1_store(Request $request, $verify)
    {
        $trd_no = $request->trd_no;
        $store_name = $request->store_name;
        $post_num = $request->post_num;
        $address = $request->address;
        $detail_address = $request->detail_address;

        if($verify){
            if ($trd_no == '') {
                $id = DB::table('trades')->insertGetId([
                    "trd_name" => $store_name,
                    "client_no" => auth()->user()->user_no,
                    "post_num" => $post_num,
                    "address" => $address,
                    "detail_address" => $detail_address,
                ], 'trd_no');
            } else {
                $id = $trd_no;
                DB::table('trades')->where('trd_no', $id)->update([
                    "trd_name" => $store_name,
                    "client_no" => auth()->user()->user_no,
                    "post_num" => $post_num,
                    "address" => $address,
                    "detail_address" => $detail_address,
                ], 'trd_no');
            }

            $response = array(
                "id" => $id,
                "verify" => $verify,
            );
        }else{
            $response = array(
                "verify" => $verify,
            );
        }

        return response()->json($response);
    }

    private function step2_store(Request $request, $verify)
    {
        $upstream_id = $request->upstream_id;
        $trd_no = $request->trd_no;

        if($verify){
            DB::table('trades')->where('trd_no', $trd_no)->update([
                "bl_no" => $upstream_id,
            ]);
        }

        $response = array(
            "id" => $trd_no,
            "verify" => $verify,
        );

        return response()->json($response);
    }

    private function step3_store(Request $request, $verify)
    {
        $average = $request->average;
        $budget = $request->budget;
        $trd_no = $request->trd_no;

        if($verify){
            DB::table('trades')->where('trd_no', $trd_no)->update([
                "trd_budget" => $budget,
                "trd_area" => $average,
            ]);
        }

        $response = array(
            "id" => $trd_no,
            "verify" => $verify,
        );

        return response()->json($response);
    }

    private function step4_store(Request $request, $verify)
    {
        $selectDate = $request->selectDate;
        $trd_no = $request->trd_no;

        if($verify){
            DB::table('trades')->where('trd_no', $trd_no)->update([
                "construct_dt" => $selectDate,
            ]);
        }

        $response = array(
            "id" => $trd_no,
            "verify" => $verify,
        );

        return response()->json($response);
    }

    private function step5_store(Request $request, $verify)
    {
        if($verify){
            if ($request->filled('req')) {
                $req = $request->input('req');
                switch ($req) {
                        case 'upldimg':
                        return  $this->stp5_upload_store_img($request);
                        case 'uplddraws':
                        return $this->stp5_upload_draws($request);
                        case 'deleteimg':
                        return $this->estimate_img_delete($request);
                        case 'deletefile':
                        return $this->estimate_file_delete($request);
                    }
            }
        }
    }

    //매장이미지 저장
    private function stp5_upload_store_img($request)
    {
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
            
            $path = "../storage/app/".config('filesystems.est_req_photo').$hash.".jpg";
            
            $img->save($path);

            $trade = DB::table('trades')->where('trd_no', $trd_no)->first();
            if($trade->trd_img == null || $trade->trd_img == '[]'){
                $tempArr = array(
                    $file_name
                );

                DB::table('trades')->where('trd_no', $trd_no)->update([
                    "trd_img" => json_encode($tempArr),
                ]);
            }else{
                $trd_img = json_decode($trade->trd_img);
                array_push($trd_img, $file_name);
                DB::table('trades')->where('trd_no', $trd_no)->update([
                    "trd_img" => json_encode($trd_img),
                ]);
            }

            $response = array(
                "status" => 1,
                "file_name" => $file_name,
            );
        }else{
            $response = array(
                "status" => 2,
            );
        }

        return response()->json($response);
    }

    //매장 도면 업로드
    private function stp5_upload_draws($request)
    {
        $trd_no = $request->trd_no;
        $index = $request->index;
        $path = '';
        $file_name='';
        $storage_path = "../storage/app/";
        $save_path = "public/file/estimate/";
        
		if($index <= 2){
            if($file = $request->file('files')){
                if ($file->isValid()) {
                    $file_name = $file->getClientOriginalName();
                    $path = $file->storeAs($save_path, $file_name);
                    $path = str_replace($save_path,"",$path);
                }	
            }
            $response = array(
                "status" => 1,
                "file_name" => $file_name,
                "index" => $index,
            );
            $trade = DB::table('trades')->where('trd_no', $trd_no)->first();
            if($trade->trd_file == null || $trade->trd_file == '[]'){
                $tempArr = array(
                    $path,
                );
                DB::table('trades')->where('trd_no', $trd_no)->update([
                    "trd_file" => json_encode($tempArr),
                ]);
            }else{

                $trd_file = json_decode($trade->trd_file);
                
                array_push($trd_file, $path);
                DB::table('trades')->where('trd_no', $trd_no)->update([
                    "trd_file" => json_encode($trd_file),
                ]);
            }
        }else{
            $response = array(
                "status" => 2,
            );
        }
        
        return response()->json($response);
    }

    private function estimate_img_delete(Request $request){
        $status = 0;
        $trd_no = $request->trd_no;
        $img_name = $request->img_name;
        $img_path = "../storage/app/".config('filesystems.est_req_photo').$img_name;

        $trade = DB::table('trades')->where('trd_no',$trd_no)->select('trd_img')->first();
        $trd_img = json_decode($trade->trd_img);
        //dd(count((array)$trd_img));
        $key = array_search($img_name, (array)$trd_img);
        $trd_img = (array)$trd_img;
        if (false !== $key) {
            unset($trd_img[$key]);
        }
        
        if(File::exists($img_path)) {
            if(File::delete($img_path)){
                $status = DB::table('trades')->where('trd_no',$trd_no)->update([
                    "trd_img" => json_encode($trd_img),
                ]);
            }
        }

        $response = array(
            "status" => $status,
            "trd_img" => $trd_img,
        );

        return response()->json($response);
    }

    private function estimate_file_delete(Request $request){
        $status = 0;
        $trd_no = $request->trd_no;
        $file_name = '/'.$request->file_name;
        $file_path = "../storage/app/public/file/estimate".$file_name;

        $trade = DB::table('trades')->where('trd_no',$trd_no)->select('trd_file')->first();
        $trd_file = json_decode($trade->trd_file);
        
        $key = array_search($file_name, (array)$trd_file);
        $trd_file = (array)$trd_file;
        if (false !== $key) {
            unset($trd_file[$key]);
        }

        
        if(File::exists($file_path)) {
            if(File::delete($file_path)){
                $status = DB::table('trades')->where('trd_no',$trd_no)->update([
                    "trd_file" => json_encode($trd_file),
                ]);
            }
        }

        $response = array(
            "status" => $status,
            "trd_file" => $trd_file,
            "index" => count($trd_file),
        );

        return response()->json($response);
    }

    private function step6_store(Request $request, $verify)
    {
        $supervision = $request->supervision;
        $trd_no = $request->trd_no;

        if($verify){
            DB::table('trades')->where('trd_no', $trd_no)->update([
                "is_premium" => $supervision,
            ]);
        }

        $response = array(
            "id" => $trd_no,
            "verify" => $verify,
        );

        return response()->json($response);
    }


    private function business_list(Request $request)
    {
        $business_lists = DB::table('business_list')->orderBy('sequence', 'asc')->get();
        $bl_no = '';
        if ($request->trd_no != '') {
            $trade = DB::table('trades')->where('trd_no', $request->trd_no)->first();
            $bl_no = $trade->bl_no;
        }
        $response = array(
            "business_lists" => $business_lists,
            "bl_no" => $bl_no,
        );

        return response()->json($response);
    }

    private function check_wait_request(Request $request)
    {
        $trade = DB::table('trades')
        ->where('client_no', auth()->user()->user_no)
        ->where('agent_no', NULL)
        ->where('construct_dt', NULL)
        ->orderBy('trd_no', 'desc')->first();

        if ($trade != null) {
            if ($trade->trd_name == null) {
                $response = array(
                    "redirect" => 1,
                    "trd_no" => $trade->trd_no,
                );
            } elseif ($trade->bl_no == null) {
                $response = array(
                    "redirect" => 2,
                    "trd_no" => $trade->trd_no,
                );
            } elseif ($trade->trd_area == null) {
                $response = array(
                    "redirect" => 3,
                    "trd_no" => $trade->trd_no,
                );
            } elseif ($trade->construct_dt == null) {
                $response = array(
                    "redirect" => 4,
                    "trd_no" => $trade->trd_no,
                );
            }else{
                $response = array(
                    "redirect" => 0,
                    "trd_no" => null,
                );
            }
        } else {
            $response = array(
                "redirect" => 0,
                "trd_no" => null,
            );
        }

        return response()->json($response);
    }


    private function step7_store(Request $request)
    {
        $trd_no = $request->trd_no;
        $other_remark_txt = $request->other_remark_txt;
    
        Db::table('trades')->where('trd_no', $trd_no)->update([
                "other_remark_txt" => $other_remark_txt,
            ]);
    
        $response = array(
                "id" => $trd_no,
            );
    
        return response()->json($response);
    }

    private function estimate_request_load(Request $request)
    {
        $trd_no = $request->trd_no;
        $step = $request->step;

        $trade = DB::table('trades')->where('trd_no', $trd_no)->first();

        if ($step == 1) {
            $response = array(
                "trd_name" => $trade->trd_name,
                "address" => $trade->address,
                "detail_address" => $trade->detail_address,
                "post_num" => $trade->post_num,
            );
        } elseif ($step == 2) {
            $response = array(
                "bl_no" => $trade->bl_no,
            );
        } elseif ($step == 3) {
            $response = array(
                "trd_budget" => $trade->trd_budget,
                "trd_area" => $trade->trd_area,
            );
        } elseif ($step == 4) {
            $response = array(
                "contruct_dt" => $trade->construct_dt,
            );
        } elseif ($step == 5) {
            $response = array(
                "trd_img" => json_decode($trade->trd_img),
                "trd_file" => json_decode($trade->trd_file),
            );
        } elseif ($step == 6) {
            $response = array(
                "is_premium" => $trade->is_premium,
            );
        }

        return response()->json($response);
    }

    public function destroy($trd_no){
        DB::table('agent_trades_bidding')->where('trd_no', $trd_no)->delete();
        $status = DB::table('trades')->where('trd_no', $trd_no)->delete();

        $response = array(
            "status" => $status,
        );

        return response()->json($response);
    }
}
