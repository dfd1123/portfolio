<header id="sub_hd">
    <div class="left_hd back_history">
        <i class="fal fa-long-arrow-left"></i>
    </div>
    <div class="center_hd">
        @if($pagename[1] == 'forgotpwd')
        <h1>비밀번호 찾기</h1>
        @elseif($pagename[1] == 'password')
        <h1>비밀번호 변경</h1>
        @endif
    </div>
</header>
<style>
    #sub_hd{
        background: #007bd2;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 5;
    }

    #sub_hd .left_hd{
        position: absolute;
        top: 1.3em;
        left: 1.3em;
        z-index: 3;
    }

    #sub_hd .left_hd i{
        color: #fff;
        font-size: 1.4em;
    }

    #sub_hd .center_hd{
        text-align: center;
        height: 2.6em;
        color: #fff;
        box-sizing: content-box;
    }

    #sub_hd .center_hd h1{
        font-size: 1.05em;
        font-weight: bold;
    }

    #sub_hd .right_hd{
        position: absolute;
        top: 1.55em;
        right: 1.3em;
        z-index: 3;
    }

    #sub_hd .right_hd img{
        width: 0.95em;
    }
</style>