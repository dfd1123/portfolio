<?php

return [
	
	// HTTP 200일 경우, 서버응답코드

	'OK' =>1, // 성공

	//백엔드 에러 및 성공
	'NO_DATA' =>0, //데이터없음
	'PARAM_ERR' =>2, //parameter 에러
	'QUERY_ERR' =>3, //쿼리에러
	'NETWORK_ERR' =>4, //쿼리에러

	//파일 업로드 에러
	'SIZE_ERR' => 11, //크기 에러
	'EXT_ERR' =>12, //확장자 에러
	'IMG_ERR' =>13, //이미지가 아닐때 에러

	//기타에러
    'PWD_ERR' =>21, //비밀번호 에러
    'DUPLI_ERR' =>22, //값 중복
	'INFO_ERR' =>23, //정보값 틀림
	'API_ERR' =>24, //API 에러

	'NO_AUTH' =>100, //로그인 인증값 없음

];