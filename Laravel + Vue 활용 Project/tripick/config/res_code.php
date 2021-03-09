<?php

return [
	
	// HTTP 200일 경우, 서버응답코드
	'NO_DATA' =>0,
	'OK' =>1,
	'PARAM_ERR' =>2,
	'QUERY_ERR' =>3,
	'EXT_ERR' =>4,
	'NO_AUTH' =>100,
	
	//JWT 키
	'JWT_SECRET_KEY' =>'JWTPASS여기에',
	
	//JWT Private Payload
	'LT_ADM' =>'admin',
	'LT_USR' =>'user',
	'LT_AGT' =>'agt'
];

