<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Utils\Nexmo;
use Intervention\Image\Facades\Image as InterventionImage;

use File;
//use Illuminate\Support\Facades\Input;

class PlnrController extends Controller
{
    /**
     * Show the profile for the given user.

     * @param  int  $id
     * @return View
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        if ($this->decode_res['uid'] ===null) {
            $this->res['query'] =null;
            $this->res['state'] = config('res_code.NO_AUTH');
            $this->res['msg'] = $this->decode_res['msg'].' no-token available';
            die(json_encode($this->res));
        }
    }

    public function __invoke($id)
    {
        return 'User controller';
    }

    public function index()
    {
        return 'API FOR Planners';
    }

    // $res['query'] =  DB::SELECT(DB::RAW(("UPDATE users
    // SET user_aes ='qw2e'
    // WHERE user_no =1
    // RETURNING user_no;")));
    // update나 delete의경우 해당방법으로 returning 우회

    // 요청경로  GET - URL  : api/User/{req}
    public function show(Request $request, $req)
    {
        $p = $request->all();


        switch ($req) {
            
            case 'plnrstate':

            //토큰있는경우 자기자신만 조회
            if(isset($this->decode_res['uid']) 
            && $this->checkRange($this->decode_res['uid'] ,0 , 2100000000) ){

                $sql = "SELECT pln_id
                ,state
                ,pln_state_info
                ,created_at at time zone 'KST' as created_at
                FROM planner
                WHERE pln_id = :pln_id";

                $params = array('pln_id'=>$this->decode_res['uid']);

                $this->res = $this->execute_query($sql, $params);
            }
            
            break;

            //모든유저가 접근가능
            case 'plnrdetail':

            if( ($request->filled('pln_id')    
                  && $this->checkRange($p['pln_id'], 1,2100000000))){

                //users테이블 조인해서 필요한정보 가져오기.
                $sql = "SELECT pln_name
                FROM planner
                WHERE pln_id = :pln_id";

                $params = array('pln_id'=>$p['pln_id']);

                $this->res = $this->execute_query($sql, $params);
            }


            break;

            //권한체크 - 최고관리자만 이하코드 실행가능
            //플래너 목록 조회
            case 'plnrlist':

                $params = array();
                if ($request->filled('offset') && $request->input('offset') >= 0) {
                    $params['offset'] = $p['offset'];
                } else {
                    $params['offset'] =0;
                }

                
                $params['state'] =1;
                if ($request->filled('state') && $request->input('state') >= 0) {
                    $params['offset'] =$p['state'];
                } 

                //WHERE절 시작
                $whereClause = " ";
                if ($request->filled('pln_name') && strlen($request->input('pln_name')) >= 2) {
                    $params['pln_name'] = '%'.$p['pln_name'].'%';
                    $whereClause.= ' TP.state = :state  AND pln_name LIKE :pln_name ';
                }
                if ($request->filled('user_email') && strlen($request->input('user_email')) >= 2) {
                    $params['user_email'] = '%'.$p['user_email'].'%';
                    $whereClause.= ' TP.state = :state  AND TP.user_email LIKE :user_email ';
                }

                if ($request->filled('pln_name_exists') && strlen($request->input('pln_name_exists')) >= 2) {
                    $params['pln_name'] = $p['pln_name_exists'];
                    $whereClause.= "(TP.state = :state OR TP.state  >= 0 ) AND pln_name LIKE :pln_name ";
                }

                $sql =  "SELECT TP.pln_id
,TP.pln_type
,TP.pln_name
,TP.pln_thumb
,TP.pln_bg_photo
,TP.pln_desc
,TP.pln_info
,TP.pln_trip_style
,TP.pln_mobile
,TP.state AS pstate
,TP.pln_state_info 
,TP.created_at at time zone 'KST' AS p_created_at
,TP.pln_score
,TU.email
,TU.created_at at time zone 'KST' AS u_created_at
FROM  planner TP JOIN users TU
ON TP.pln_id = TU.id
WHERE ";

$sql.= $whereClause;

$sql.="
ORDER BY pln_id DESC
OFFSET :offset LIMIT 10;";

                $this->res = $this->execute_query($sql, $params);
            break;
			case 'msg_verify_check':
				$sql = "SELECT
					id
				FROM
					users
				WHERE
					id = :id
					AND user_contact_verify_number LIKE :verify_code";
				$param = array('id'=>$this->decode_res['uid'], 'verify_code'=>$p['verify_code']);
				$this->execute_query($sql, $param, 'select');
			break;
           
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }


    public function store(Request $request)
    {
        $p = $request->all();
        //플래너가입
        if ($request->filled('req') && $p['req'] ==='plnr') {
            $p = $request->all();
            //Filed 확인
            if ($request->filled('pln_name', 'pln_desc', 'pln_info', 'pln_style', 'pln_mobile','pln_juri')
            &&  ($p['pln_type'] == 0 || $p['pln_type']== 1)   //개인 or 회사
            && (mb_strlen($p['pln_name'])>2  && mb_strlen($p['pln_name']) < 13)
            && (mb_strlen($p['pln_desc'])>2  && mb_strlen($p['pln_desc']) < 128)
            && (mb_strlen($p['pln_mobile'])>9  && mb_strlen($p['pln_mobile']) < 12)
            ) {
                if(isset($this->decode_res['uid'])){
                    $p['pln_id'] = $this->decode_res['uid'];
                }else{
                    $this->res['state'] = config('res_code.NO_AUTH');
                    $this->res['msg'] = '입력오류 :인증정보없음';
                    return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
               
                }
                //플래너 정보, 스타일 확인
                foreach( $p['pln_info'] as  $desc ){
                    if( $this->checkLength($desc, 2 , 128) ){
                    }else{
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '입력오류 : 플래너정보 2 ~128자';
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                } 
                foreach( $p['pln_style'] as  $style ){
                    if(  $this->checkLength($style ,2, 128)){
                    }else{
                        
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '입력오류 : 플래너스타일 2 ~128자';
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }
                foreach( $p['pln_juri'] as  $style ){
                    if(  $this->checkLength($style ,6 , 128)){
                    }else{
                        
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '입력오류 : 플래너관할지역 2 ~128자';
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }


                //필수 File 확인  (신분증, 서류파일)   ->복수개 검사수정
                if (  $this->checkFiles('docs', $request, array('jpg','jpeg','png','hwp','docs','word','txt'))
                    && $this->checkFiles('idcard', $request, array('jpg','jpeg','png'))
                ) {
                    $idcardPath = 'no-img';
                    $docsPath = 'no-img';
                    try {
                    	/*
						 * if ($ext = $this->checkExtension('plnrthumb', $request, array('jpeg','png','jpg'))) {
	                        $thumbPath = $this->saveFileNameUnder(
	                            'plnrthumb',
	                            $request,
	                            config('filesystems.planner_thumb')   //파일저장경로
	                        .'plnrthumb_'.$this->decode_res['uid']                     //파일명
	                        .'.'.$ext                                //확인된 확장자
	                        );
	                    
	                        $sql = "UPDATE planner 
	                                SET pln_thumb = :pln_thumb, updated_at = NOW()
	                                WHERE pln_id = :pln_id;";
	                        $param = array('pln_id'=>$this->decode_res['uid'] , 'pln_thumb'=>'plnrthumb_'.$this->decode_res['uid'].'.'.$ext);
	                        $this->execute_query($sql, $param, 'update');
	                    }else{
	                        $this->res['query'] =null;
	                        $this->res['state'] = config('res_code.EXT_ERR');
	                        $this->res['msg'] = '확장자없음 -pln_thumb  CODE : 351';
	                        break;
	                    }*/
                        $docsSaved = array();
						foreach ($request->file('docs') as $file) {
                        
                    		$mime = $file->getMimeType();
                			$extension = $this->getImgExtension($mime);	
							
							if($extension != 'not_image')
		                    {
		                        // 이미지 회전 후 저장
		                        $img = InterventionImage::make($file)->orientate();
		
		                        if($img->width() >= 1000){
		                            $img->resize(700, null, function ($constraint) {
		                                $constraint->aspectRatio(); //비율유지
		                            });
		                        }else{
		                            //$img->rotate($rotate)->encode('jpg');
		                        }
		
		                        $filename = Str::uuid()->toString().$extension;
		                        $path = "/home/tripick/storage/".config('filesystems.planner_docs').$filename;
								$stored = $img->save($path);
		                        array_push($docsSaved, $filename);
		                    }else{
		                        $filename = Str::uuid()->toString().$extension;
	                            $path = config('filesystems.planner_docs').$filename;
	                            $stored = $file->storeAs('',$path);
	                            array_push($docsSaved, $filename);
		                    }
							
                        	/*$filename = Str::uuid()->toString();
                            $path = config('filesystems.planner_docs').$filename;
                            $stored = $file->storeAs('',$path);
                            array_push($docsSaved, $filename);*/
                        }
                        
                        $idcardSaved = array();
                        foreach ($request->file('idcard') as $file) {
                        	
							$mime = $file->getMimeType();
                			$extension = $this->getImgExtension($mime);	
							
							if($extension != 'not_image')
		                    {
		                        // 이미지 회전 후 저장
		                        $img = InterventionImage::make($file)->orientate();
		
		                        if($img->width() >= 1000){
		                            $img->resize(700, null, function ($constraint) {
		                                $constraint->aspectRatio(); //비율유지
		                            });
		                        }else{
		                            //$img->rotate($rotate)->encode('jpg');
		                        }
		
		                        $filename = Str::uuid()->toString().$extension;
		                        $path = "/home/tripick/storage/".config('filesystems.planner_idcard').$filename;
								$stored = $img->save($path);
		                        array_push($idcardSaved, $filename);
		                    }else{
		                        $filename = Str::uuid()->toString().$extension;
	                            $path = config('filesystems.planner_idcard').$filename ;
	                            $stored = $file->storeAs('', $path);
	                            array_push($idcardSaved, $filename);
		                    }
                        	/*$filename = Str::uuid()->toString();
                            $path = config('filesystems.planner_idcard').$filename ;
                            $stored = $file->storeAs('', $path);
                            array_push($idcardSaved, $filename);*/
                        }
						
                        
                        //배경사진, 썸네일은 회원가입 후 수정
                        $params = array('pln_id' => $p['pln_id'],
                        'pln_type' =>  $p['pln_type'],
                        'pln_name' => $p['pln_name'],
                        'pln_desc' => $p['pln_desc'],
                        'pln_info' => json_encode($p['pln_info']),
                        'pln_trip_style' => json_encode($p['pln_style']),
                        'jurisdiction_area' => json_encode($p['pln_juri']),
                        'pln_mobile' => $p['pln_mobile']);
                        
                        $params['pln_id_card'] = json_encode($idcardSaved);
                        $params['pln_docs'] =json_encode($docsSaved);
                    

                        $sql = 'INSERT INTO
planner (pln_id
, pln_type
, pln_name
, pln_desc
, pln_info
, pln_trip_style
, pln_mobile
, pln_id_card
, pln_docs
, jurisdiction_area ) SELECT
    :pln_id
    ,:pln_type
    ,:pln_name
    ,:pln_desc
    ,:pln_info
    ,:pln_trip_style
    ,:pln_mobile
    ,:pln_id_card
    ,:pln_docs
    ,:jurisdiction_area
WHERE
    NOT EXISTS (
    SELECT
        TP.pln_id
        , TP.state
    FROM
        planner TP
    WHERE
        TP.pln_id = :pln_id)
RETURNING pln_id, state;';

                        $this->execute_query($sql, $params);
                    } catch (exception $e) {
                        $this->res['query']=null;
                        $this->res['msg']= 'QUERY ERROR';
                        $this->res['state'] = config('res_code.QUERY_ERR');
                    }
    
                    //정상적으로 실행된 경우
                    if (count($this->res['query']) >0 &&  $this->res['query'][0]->pln_id > 0) {
                        $this->res['state'] = config('res_code.OK');
                        $this->res['msg'] = '심사가 진행됩니다.';
                    } else {
                        //$this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '등록 심사중입니다.';
                    }
                } else {
                    $this->res['query'] = null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '신분증, 서류증빙 파일 없음 - CODE : TYPE  269';
                }
            } else {
                $this->res['query'] = null;
                $this->res['state'] = config('res_code.PARAM_ERR');
                $this->res['msg'] = '변수 없음 or 데이터 길이 안맞음 - CODE : TYPE  307';
            }
        }
		
        //예외상황
        else {
            $this->res['query'] = null;
            $this->res['state'] = config('res_code.PARAM_ERR');
            $this->res['msg'] = '분기 없음 - CODE : TYPE 320';
        }
        //정상등록된 경우 state 1  query : user_id
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
    
    public function update(Request $request, $req)
    {
        $p = $request->all();
        //var_dump($request->all());
        switch ($req) {
            //배경이미지 편집
            case 'pln_bg_photo':
                //배경사진 있을경우 변경저장
                if ($this->checkFile('plnrbg', $request)) {
                    if ($ext = $this->checkExtension('plnrbg', $request, array('jpeg','png','jpg'))) {
                        $thumbPath = $this->saveFileNameUnder(
                            'plnrbg',
                            $request,
                            config('filesystems.planner_bg')   //파일저장경로
                        .'plnrbg_'.$this->decode_res['uid']                     //파일명
                        .'.'.$ext                                //확인된 확장자
                        );
                    
                        $sql = "UPDATE planner 
                                SET pln_bg_photo = :pln_bg_photo, updated_at = NOW()
                                WHERE pln_id = :pln_id;";
                        $param = array('pln_id'=>$this->decode_res['uid'] , 'pln_bg_photo'=>'plnrbg_'.$this->decode_res['uid'].'.'.$ext);
                        $this->execute_query($sql, $param, 'update');
                    }else{
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.EXT_ERR');
                        $this->res['msg'] = '확장자없음 -pln_bg_photo  CODE : 351';
                        break;
                    }
                }else{
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '파일없음 -pln_bg_photo  CODE : 357';
                    break;
                }
            break;

            //썸네일 편집
            case 'pln_thumb':
                if ($this->checkFile('plnrthumb', $request)) {
                    if ($ext = $this->checkExtension('plnrthumb', $request, array('jpeg','png','jpg'))) {
                        $thumbPath = $this->saveFileNameUnder(
                            'plnrthumb',
                            $request,
                            config('filesystems.planner_thumb')   //파일저장경로
                        .'plnrthumb_'.$this->decode_res['uid']                     //파일명
                        .'.'.$ext                                //확인된 확장자
                        );
                    
                        $sql = "UPDATE planner 
                                SET pln_thumb = :pln_thumb, updated_at = NOW()
                                WHERE pln_id = :pln_id;";
                        $param = array('pln_id'=>$this->decode_res['uid'] , 'pln_thumb'=>'plnrthumb_'.$this->decode_res['uid'].'.'.$ext);
                        $this->execute_query($sql, $param, 'update');
                    }else{
                        $this->res['query'] =null;
                        $this->res['state'] = config('res_code.EXT_ERR');
                        $this->res['msg'] = '확장자없음 -pln_thumb  CODE : 351';
                        break;
                    }
                }else{
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '파일없음 -pln_thumb  CODE : 357';
                    break;
                }
            break;
            
            //소개글 편집
            case 'pln_desc':
                if (!$request->filled('pln_desc')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 -plnrinfo  CODE : 399';
                    break;
                }

                if ( !$this->checkLength($p['pln_desc'] ,2 ,255) ){
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '소개글 글자길이안맞음(10자 이상)  CODE : 406';
                    break;
                }

                $sql = "UPDATE planner 
                        SET updated_at = NOW()
                        , pln_desc = :pln_desc
                        WHERE pln_id = :pln_id";

                $params = array(
                    'pln_desc'=>$p['pln_desc'],
                    'pln_id'=>$this->decode_res['uid']
                );

                $this->execute_query($sql, $params, 'update');
            break;

            case 'pln_home':
                if (!$request->filled('pln_info','pln_style','pln_juri')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 -plnrinfo  CODE : 427';
                    break;
                }

                //플래너 정보, 스타일 확인
                foreach( $p['pln_info'] as  $desc ){
                    if( $this->checkLength($desc, 3 , 128) ){
                    }else{
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '입력오류 : 플래너정보 3 ~128자';
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                } 
                foreach( $p['pln_style'] as  $style ){
                    if(  $this->checkLength($style ,3 , 128)){
                    }else{
                        
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '입력오류 : 플래너스타일 3 ~128자';
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }
                foreach( $p['pln_juri'] as  $style ){
                    if(  $this->checkLength($style ,1 , 128)){
                    }else{
                        
                        $this->res['query'] = null;
                        $this->res['state'] = config('res_code.PARAM_ERR');
                        $this->res['msg'] = '입력오류 : 플래너관할지역 1 ~128자';
                        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
                    }
                }
                $sql = "UPDATE planner 
                        SET updated_at = NOW()
                        , pln_info = :pln_info
                        , pln_trip_style = :pln_trip_style 
                        , jurisdiction_area = :jurisdiction_area
                        WHERE pln_id = :pln_id";

                $params = array('pln_id' => $this->decode_res['uid'],
                        'pln_info' => json_encode($p['pln_info']),
                        'pln_trip_style' => json_encode($p['pln_style']),
                        'jurisdiction_area' => json_encode($p['pln_juri']));

                $this->execute_query($sql, $params, 'update');
            break;

            
            



            case 'plnrinfo':
	            if (!$request->filled('pln_desc','pln_info','pln_trip_style')) {
	                $this->res['query'] =null;
	                $this->res['state'] = config('res_code.PARAM_ERR');
	                $this->res['msg'] = '변수없음 -plnrinfo  CODE : 1';
	                break;
	            }
	
	            if ( !$this->checkLength($p['pln_desc'] ,2 ,255)  
	            || !$this->checkLength($p['pln_info']   ,2 ,256)  
	            || !$this->checkLength($p['pln_trip_style'] ,2 , 256) ){
	                $this->res['query'] =null;
	                $this->res['state'] = config('res_code.PARAM_ERR');
	                $this->res['msg'] = '변수오류 -plnrinfo  CODE : 2';
	                break;
	            }
	
	            $params = array('pln_desc'=>$p['pln_desc'],
	            'pln_info'=>$p['pln_info'],
	            'pln_trip_style'=>$p['pln_trip_style']
	            );
	            
	            $sql = "UPDATE planner 
	            SET updated_at = NOW()
	            , pln_desc = :pln_desc
	            , pln_info = :pln_info
	            , pln_trip_style = :pln_trip_style ";
	
	            //썸네일 있을경우 변경저장
	            if ($this->checkFile('plnrthumb', $request)) {
	                if ($ext = $this->checkExtension('plnrthumb', $request, array('jpeg','png','jpg'))) {
	
	                    $thumbPath = $this->saveFileNameUnder(
	                        'plnrthumb',
	                        $request,
	                        config('filesystems.planner_thumb')   //파일저장경로
	                    .'plnrthumb_'.$this->decode_res['uid']                     //파일명
	                    .'.'.$ext                                //확인된 확장자
	                    );
	                    $sql .=" , pln_thumb =  :pln_thumb";
	
	                    $params['pln_thumb'] = 'plnrthumb_'.$this->decode_res['uid'].'.'.$ext;
	                }
	            }
	            //배경사진 있을경우 변경저장
	            if ($this->checkFile('plnrbg', $request)) {
	                if ($ext = $this->checkExtension('plnrbg', $request, array('jpeg','png','jpg'))) {
	
	                    $thumbPath = $this->saveFileNameUnder(
	                        'plnrbg',
	                        $request,
	                        config('filesystems.planner_bg')   //파일저장경로
	                    .'plnrbg_'.$this->decode_res['uid']                      //파일명
	                    .'.'.$ext                                //확인된 확장자
	                    );
	                
	                    $sql .=" , pln_bg_photo =  :pln_bg_photo";
	                    $params['pln_bg_photo'] = 'plnrbg_'.$this->decode_res['uid'].'.'.$ext ;
	                }
	            }
	            
	            $sql.=" WHERE pln_id = :pln_id ;";
	            
	            $params['pln_id']  = $this->decode_res['uid'];   //차후 AUTH에서 가져오기
	
	            //정상실행일경우 state 1 query 1
	            $this->execute_query($sql, $params, 'update');
            break;
            //관리자만 접근가능
            case 'plnrstate':
                if (!$request->filled('id') && !$request->filled('state')) {
                    $this->res['query'] =null;
                    $this->res['state'] = config('res_code.PARAM_ERR');
                    $this->res['msg'] = '변수없음 - state CODE : 1';
                    break;
                }
				$sql = "UPDATE planner 
                SET state = :state
                WHERE pln_id = :id;";
                $param = array('pln_id'=>$this->decode_res['uid'] , 'state'=>$p['state']);
				
                
                $this->execute_query($sql, $param, 'update');
            break;
			
			//가입시 휴대폰 인증
			case 'msgsend':
				$p = $request->all();
				if(isset($this->decode_res['uid'])){
	                $p['user_id'] = $this->decode_res['uid'];
	            }else{
	                $this->res['state'] = config('res_code.NO_AUTH');
	                $this->res['msg'] = '입력오류 :인증정보없음';
	                break;
	            }
				if($request->filled('user_contact') && (strlen($p['user_contact'])>9  && strlen($p['user_contact']) < 16)){
                    $global_number = "82".substr($p['user_contact'], 1);
					$verify_code = Nexmo::send_code($global_number);
					$sql = 'UPDATE
						users
					SET
						user_contact_verify_number = :verify_code
					WHERE
						id = :id';
					$param = array('id'=>$this->decode_res['uid'], 'verify_code'=>$verify_code['msg']);
					$this->execute_query($sql, $param, 'update');
	            }else{
	                $this->res['state'] = config('res_code.NO_AUTH');
	                $this->res['msg'] = '입력오류 :인증정보없음';
					break;
	            }
			break;
        }
        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }

    //사용고민중.
    public function destroy(Request $request)
    {
    }
	
	private function getImgExtension($mime){
        switch($mime){
            case 'image/png':
                return '.png';
                break;
            case 'image/jpeg':
                return '.jpg';
                break;
            case 'image/gif':
                return '.gif';
                break;
            case 'image/bmp':
                return '.bmp';
                break;
            case 'image/svg':
                return '.svg';
                break;
            default :
                return 'not_image';
                break;
        }
    }

    private function getFileExtension($mime){
        switch($mime){
            case 'application/msword':
                return '.doc';
                break;
            case 'application/vnd.ms-powerpoint':
                return '.ppt';
                break;
            case 'application/pdf':
                return '.pdf';
                break;
            case 'application/vnd.ms-excel':
                return '.xls';
                break;
            case 'application/x-hwp':
                return '.hwp';
                break;
            case 'application/haansofthwp':
                return '.hwp';
                break;
            case 'application/vnd.hancom.hwp':
                return '.hwp';
                break;
            case 'application/zip':
                return '.zip';
                break;
            case 'application/x-7z-compressed':
                return '.7z';
                break;
            default :
                return 'not_file';
                break;
        }
    }
}