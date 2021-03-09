<head>
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/moment/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
    <style>
        .chat {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .chat li {
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #B3A9A9;
        }

        .chat li.left .chat-body {
            margin-left: 60px;
        }

        .chat li.right .chat-body {
            margin-right: 60px;
        }


        .chat li .chat-body p {
            margin: 0;
            color: #777777;
        }

        .panel .slidedown .glyphicon,
        .chat .glyphicon {
            margin-right: 5px;
        }

        .panel-body {
            overflow-y: scroll;
            height: 250px;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: #555;
        }
        .system{
            text-align: center;
            background-color: gray;
            margin: 5px 5px;
            padding: 5px;
            border-radius: 10px;
            font-size: 11px;
            color: white;
        }

        .mine{
            text-align: right;
            margin: 20px 0;
        }
        .mine .name{
            margin: 5px 20px;
            font-size: 13px;
        }
        .mine .message {
            position: relative;
            background: #ffeB33;
            color:black;
            margin-right: 20px;
            padding: 8px 9px;
            border-radius: 5px;
            word-break: break-all;
            max-width:200px;
            display:inline-block;
            text-align:left;
            font-size:14px;
        }
        .mine .message:after {
            left: 100%;
            top: 50%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-color: rgba(255, 255, 255, 0);
            border-left-color: #ffeB33;
            border-width: 5px 10px;
            margin-top: -14px;
        }
        .mine .date{
            font-size:9px;
            margin-right:6px;
        }

        .you{
            text-align: left;
            margin: 20px 0;
        }
        .you .name{
            margin: 5px 13px;
            font-size: 13px;
        }
        .you .message{
            position: relative;
            background: #FFFFFF;
            color: black;
            margin-left: 14px;
            padding: 8px 9px;
            border-radius: 5px;
            word-break: break-all;
            max-width:200px;
            display:inline-block;
            font-size:14px;
        }
        .you .message:after {
            right: 100%;
            top: 50%;
            border: solid transparent;
            content: " ";
            height: 0px;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-color: rgba(255, 255, 255, 0);
            border-right-color: #FFFFFF;
            border-width: 5px 10px;
            margin-top: -14px;
        }

        .you .date{
            font-size:9px;
            margin-left:6px;
        }
        .phpdebugbar{
            display:none;
        }
    </style>
    
</head>
<body id="chat_body" style="overflow:hidden;">
<div style="position:relative;border: 1px solid #797979;border-radius: 0px;box-shadow:0px 0px 2px #797979;">
    <div style="background-color:#FFFFFF;border-bottom:1px solid gray;text-align:right;">
        <button onclick="parent.$('#chat_div_frame').hide().find('#chat-frame').attr('src','about:blank');" style="background: none;border: none;font-size: 17px;font-weight: 300;">x</button>
    </div>
    
    <div id="chat-log" style="height: 500px; background-color:#B2C7D9; overflow-y: scroll;"></div>
    <div style="position:relative;">
        <textarea id="chat-text" style="resize:none;width:100%;padding:6px 70px 6px 8px;height:77px;line-height:18px;font-size:14px;" ></textarea>
        <button id="chat-submit" type="button" style="position:absolute;top:8px;right:10px;padding:5px 11px;font-size:12px;background-color:#ffeB33;border-radius:3px;border:1px solid #DADADA; cursor:pointer;">전송</button>
    </div>
</div>

<script>
    $(function(){
        $('#chat_body').css('margin-bottom','0');
    });
    
    var roomId = '{{$room_id}}';
    var roomToken = '{{$room_token}}';

    var userId = '{{$user_id}}';
    var userName = '{{$user_name}}';

    if(roomId && roomToken && userId && userName) {
        $(function() {
            if(window.location.href.indexOf("https://cointouse.com") == 0) {
                var socket = io.connect("wss://chat.cointouse.com/", {transports:['websocket']});
            } else {
                var socket = io.connect("wss://devchat.cointouse.com/", {transports:['websocket']});
            }

            // 채팅 메세지 보내기
            var chatSend = function() {
                var msg = $("#chat-text").val();
                if (!msg) return;

                socket.emit("send:message", {
                    message: msg
                });

                $("#chat-text").val('');
            };

            $("#chat-submit").on("click", function() {
                chatSend();
            });

            $("#chat-text").on('keypress',function(e) {
                if(e.which == 13) {
                    chatSend();
                }
            });

            // 하단 스크롤
            var chatScrollDown = function() {
                setTimeout(function() {
                    var div = $('#chat-log');
                    div.scrollTop(div.prop('scrollHeight'));
                }, 200);
            };

            // 언어팩
            var systemLang = function(data, str) {
                if(data.message === '__connect') {
                    return {system: true, message: data.name + "님이 입장하셨습니다."};
                } else if(data.message === '__disconnect') {
                    return {system: true, message: data.name + "님이 퇴장하셨습니다."};
                }

                return {system: false, message: str};
            };

            // 인젝션 방지
            var escape = function(str) {
                return $('<div>').html(str).text();
            }

            // 접속
            socket.on("connect", function(data) {
                socket.emit("load:room", { 
                    userId: userId,
                    userName: userName, 
                    roomId: roomId, 
                    roomToken: roomToken 
                });
            });

            // 채팅내역 불러오기
            socket.on("load:room", function(data) {
                $("#chat-log").empty();

                var text = "";
                data.data.forEach(function(data){
                    var date = moment(data.timestamp).format('HH:mm');
                    var name = data.name;
                    var str = systemLang(data, data.message);

                    if(data.message === '__connect' || data.message === '__disconnect'){
                        text += "<p class='system'>" + escape(str.message) + "</p>";
                    }else if(data.id == {{$user_id}}){
                        text += "<div class='mine'><p class='name'>" + escape(name) + "</p><span class='date'>" + escape(date) + "</span><div class='message'>" + escape(str.message) + "</div></div>";
                    }else{
                        text += "<div class='you'><p class='name'>" + escape(name) + "</p><div class='message'>" + escape(str.message) + "</div><span class='date'>" + escape(date) + "</span></div>";
                    }
                    
                });

                $("#chat-log").append(text);

                chatScrollDown();

                socket.emit("join:room", {});
            });

            // 채팅내역 채팅창에 추가
            socket.on("send:message", function(data) {
                var date = moment(data.timestamp).format('HH:mm');
                var name = data.name;
                var str = systemLang(data, data.message);
                text = '';

                if(data.message === '__connect' || data.message === '__disconnect'){
                    text += "<p class='system'>" + escape(str.message) + "</p>";
                }else if(data.id == {{$user_id}}){
                    text += "<div class='mine'><p class='name'>" + escape(name) + "</p><span class='date'>" + escape(date) + "</span><div class='message'>" + escape(str.message) + "</div></div>";
                }else{
                    text += "<div class='you'><p class='name'>" + escape(name) + "</p><div class='message'>" + escape(str.message) + "</div><span class='date'>" + escape(date) + "</span></div>";
                }

                $("#chat-log").append(text);

                chatScrollDown();
            });

            socket.on("disconnect", function(data) {
                var str = '[' + moment(data.timestamp).format('HH:mm') + '@서버 재접속 중...]';

                $("#chat-log").append("<p>" + escape(str) + "</p>");

                chatScrollDown();
            });
        });
    }
</script>
</body>
