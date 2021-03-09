<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
    <meta http-equiv="Content-Style-Type" content="text/css">

	<title>퍼주마 이메일</title>


        
</head>
<body>
    <div>
        안녕하십니까 {{$user_name}}님
    </div>
    <a href="{{$url}}/email/verify/{{$jwt}}" target="_blank">인증하기</a>

    <style>
        
        #app{
            position:relative;
        }
    </style>
    
    
</body>
</html>
