<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>J1 Beatz 관리페이지</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
    html,
    body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links>a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
    </style>

    <script src="{{ asset('vendor/js/jquery.min.js') }} "></script>
    <script type="text/javascript">
    $(function() {

        $('#btn-login').click(function() {
            var param = { 'pwd': $('#pwd').val()
            };
            $.ajax({
                method: "POST",
                data: param,
                dataType: 'json',
                url: 'api/login',
                success: function(data) {
                    if(data.state ==1){
                        location.href='/dashboard';
                    }
                    else{
                        alert('암호 오류');
                    }

                },
                error: function(e) {}
            });
        });
    });
    </script>
</head>

<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                J1 Beatz 관리페이지
            </div>

            <div class="links">
                <form>
                    <div>
                        <div>
                            <p>비밀번호</p>
                            <input id="pwd" type="password" />
                        </div>
                    </div>
                    <input id="btn-login" type="button"  value="로그인"></button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>