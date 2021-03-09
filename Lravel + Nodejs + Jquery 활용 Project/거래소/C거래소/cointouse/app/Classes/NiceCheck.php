<?php

namespace App\Classes;

use Session;

class NiceCheck {
    public function NiceCheck_main()
    {
    	/* NICECHECK 휴대폰인증 모듈 시작   */
    	$sitecode = "BH480";				// NICE로부터 부여받은 사이트 코드
    	$sitepasswd = "ut20lRWWRbee";			// NICE로부터 부여받은 사이트 패스워드
    	
    	if(strpos(env('APP_URL'),"local") !== false){
    		$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/vendor/nicecheck/CPClient.exe"; //모듈위치
		}else{
			$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/vendor/nicecheck/CPClient_64bit"; //모듈위치
		}
    	$authtype = "";      		// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
    	
		$popgubun 	= "Y";			//Y : 취소버튼 있음 / N : 취소버튼 없음
		$customize 	= "";			//없으면 기본 웹페이지 / Mobile : 모바일페이지
	    
		$gender = "";      			// 없으면 기본 선택화면, 0: 여자, 1: 남자
		
	    $reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
	    
	    // 실행방법은 싱글쿼터(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
    	$reqseq = shell_exec("$cb_encode_path SEQ $sitecode");

		// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
		// 리턴url은 인증 전 인증페이지를 호출하기 전 url과 동일해야 합니다. ex) 인증 전 url : http://www.~ 리턴 url : http://www.~
    	$returnurl = env('APP_URL')."/nicecheck/checkplus_success";	// 성공시 이동될 URL
    	$errorurl = env('APP_URL')."/nicecheck/checkplus_fail";		// 실패시 이동될 URL
    	
    	// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.
    
    	Session::put('REQ_SEQ',$reqseq);
		
		// 입력될 plain 데이타를 만든다.
	    $plaindata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
					 "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
					 "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
					 "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
					 "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
					 "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
					 "9:CUSTOMIZE" . strlen($customize) . ":" . $customize .
					 "6:GENDER" . strlen($gender) . ":" . $gender ;
	    
	    $enc_data = shell_exec("$cb_encode_path ENC $sitecode $sitepasswd $plaindata");
		
	    $returnMsg = "";
	
	    if( $enc_data == -1 )
	    {
	        $returnMsg = "암/복호화 시스템 오류입니다.";
	        $enc_data = "";
	    }
	    else if( $enc_data== -2 )
	    {
	        $returnMsg = "암호화 처리 오류입니다.";
	        $enc_data = "";
	    }
	    else if( $enc_data== -3 )
	    {
	        $returnMsg = "암호화 데이터 오류 입니다.";
	        $enc_data = "";
	    }
	    else if( $enc_data== -9 )
	    {
	        $returnMsg = "입력값 오류 입니다.";
	        $enc_data = "";
	    }
    	/* NICECHECK 휴대폰인증 모듈 끝   */
    	
    	return ["returnMsg" => $returnMsg, "enc_data" => $enc_data];
    }

	public function NiceCheck_success()
    {
	   	$sitecode = "BH480";				// NICE로부터 부여받은 사이트 코드
    	$sitepasswd = "ut20lRWWRbee";			// NICE로부터 부여받은 사이트 패스워드
    	
    	if(strpos(env('APP_URL'),"local") !== false){
    		$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/vendor/nicecheck/CPClient.exe"; //모듈위치
		}else{
			$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/vendor/nicecheck/CPClient_64bit"; //모듈위치
		}
			
	    $enc_data = $_POST["EncodeData"];		// 암호화된 결과 데이타
	
			//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
	    if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {info("입력 값 확인"); return "입력 값 확인이 필요합니다 : ".$match[0];} // 문자열 점검 추가. 
	    if(base64_encode(base64_decode($enc_data))!=$enc_data) {info("입력 값 확인"); return "입력 값 확인이 필요합니다";}
	
			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			
	    if ($enc_data != "") {
	
	        $plaindata = shell_exec("$cb_encode_path DEC $sitecode $sitepasswd $enc_data");		// 암호화된 결과 데이터의 복호화
	
	        if ($plaindata == -1){
	            return "암/복호화 시스템 오류";
	        }else if ($plaindata == -4){
	            return "복호화 처리 오류";
	        }else if ($plaindata == -5){
	            return "HASH값 불일치 - 복호화 데이터는 리턴됨";
	        }else if ($plaindata == -6){
	            return "복호화 데이터 오류";
	        }else if ($plaindata == -9){
	            return "입력값 오류";
	        }else if ($plaindata == -12){
	            return "사이트 비밀번호 오류";
	        }else{
	            // 복호화가 정상적일 경우 데이터를 파싱합니다.
	            $ciphertime = shell_exec("$cb_encode_path CTS $sitecode $sitepasswd $enc_data");	// 암호화된 결과 데이터 검증 (복호화한 시간획득)
	        

	            //$name = $this->GetValue($plaindata , "NAME");
	            //$name = $this->GetValue($plaindata , "UTF8_NAME"); //charset utf8 사용시 주석 해제 후 사용
	           	$requestnumber = $this->GetValue($plaindata , "REQ_SEQ");
				$mobileno = $this->GetValue($plaindata , "MOBILE_NO");
				
	            if(strcmp(session('REQ_SEQ'), $requestnumber) != 0)
	            {
	            	return "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.";
	                $requestnumber = "";
	                $responsenumber = "";
	                $authtype = "";
	                $name = "";
	            	$birthdate = "";
	            	$gender = "";
	            	$nationalinfo = "";
	            	$dupinfo = "";
					$mobileno = "";
					$mobileco = "";
	            }
				$returnMsg = $mobileno;
				return ["mobileno" => $mobileno, "returnMsg" => "성공"];
	        }
	    }
	}
	
	public function NiceCheck_fail()
    {
	   	$sitecode = "BH480";				// NICE로부터 부여받은 사이트 코드
    	$sitepasswd = "ut20lRWWRbee";			// NICE로부터 부여받은 사이트 패스워드
    	
    	if(strpos(env('APP_URL'),"local") !== false){
    		$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/vendor/nicecheck/CPClient.exe"; //모듈위치
		}else{
			$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/vendor/nicecheck/CPClient_64bit"; //모듈위치
		}
			
	    $enc_data = $_POST["EncodeData"];		// 암호화된 결과 데이타
	
			//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
	    if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {info("입력 값 확인"); return "입력 값 확인이 필요합니다 : ".$match[0];} // 문자열 점검 추가. 
	    if(base64_encode(base64_decode($enc_data))!=$enc_data) {info("입력 값 확인"); return "입력 값 확인이 필요합니다";}
	
			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			
	    if ($enc_data != "") {
	
	        $plaindata = shell_exec("$cb_encode_path DEC $sitecode $sitepasswd $enc_data");		// 암호화된 결과 데이터의 복호화
	
	        if ($plaindata == -1){
	            return "암/복호화 시스템 오류";
	        }else if ($plaindata == -4){
	            return "복호화 처리 오류";
	        }else if ($plaindata == -5){
	            return "HASH값 불일치 - 복호화 데이터는 리턴됨";
	        }else if ($plaindata == -6){
	            return "복호화 데이터 오류";
	        }else if ($plaindata == -9){
	            return "입력값 오류";
	        }else if ($plaindata == -12){
	            return "사이트 비밀번호 오류";
	        }else{
	            // 복호화가 정상적일 경우 데이터를 파싱합니다.
	            $requestnumber = $this->GetValue($plaindata , "REQ_SEQ");
	            $errcode = $this->GetValue($plaindata , "ERR_CODE");
				
				$err_array = array(
					'0001' => '인증 불일치(통신사선택오류, 생년월일/성명/휴대폰번호 불일치, 휴대폰일시정지, 선불폰가입자, SMS발송실패, 인증문자불일치 등)',
					'0003' => '기타인증오류',
					'0010' => '인증번호 불일치(소켓)',
					'0012' => '요청정보오류(입력값오류)',
					'0013' => '암호화 시스템 오류',
					'0014' => '암호화 처리 오류',
					'0015' => '암호화 데이터 오류',
					'0016' => '복호화 처리 오류',
					'0017' => '복호화 데이터 오류',
					'0018' => '통신오류',
					'0019' => '데이터베이스 오류',
					'0020' => '유효하지않은 CP코드',
					'0021' => '중단된 CP코드',
					'0022' => '휴대전화본인확인 사용불가 CP코드',
					'0023' => '미등록 CP코드',
					'0031' => '유효한 인증이력 없음',
					'0035' => '기인증완료건(소켓)',
					'0040' => '본인확인차단고객(통신사)',
					'0041' => '인증문자발송차단고객(통신사)',
					'0050' => 'NICE 명의보호서비스 이용고객 차단',
					'0052' => '부정사용차단',
					'0070' => '간편인증앱 미설치',
					'0071' => '앱인증 미완료',
					'0072' => '간편인증 처리중 오류',
					'0073' => '간편인증앱 미설치(LG U+ Only)',
					'0074' => '간편인증앱 재설치필요',
					'0075' => '간편인증사용불가-스마트폰아님',
					'0076' => '간편인증앱 미설치',
					'0078' => '14세 미만 인증 오류',
					'0079' => '간편인증 시스템 오류',
					'9097' => '인증번호 3회 불일치'
				);
				
	            if(strcmp(session('REQ_SEQ'), $requestnumber) != 0)
	            {
	            	return "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.";
	                $requestnumber = "";
	                $responsenumber = "";
	                $authtype = "";
	                $name = "";
	            	$birthdate = "";
	            	$gender = "";
	            	$nationalinfo = "";
	            	$dupinfo = "";
					$mobileno = "";
					$mobileco = "";
	            }
				
				return $err_array[$errcode];
	        }
	    }
	}
	
	function GetValue($str , $name) 
    {
        $pos1 = 0;  //length의 시작 위치
        $pos2 = 0;  //:의 위치

        while( $pos1 <= strlen($str) )
        {
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $key = substr($str , $pos2 + 1 , $len);
            $pos1 = $pos2 + $len + 1;
            if( $key == $name )
            {
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $value = substr($str , $pos2 + 1 , $len);
                return $value;
            }
            else
            {
                // 다르면 스킵한다.
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $pos1 = $pos2 + $len + 1;
            }            
        }
    }
}
