<header id="sub_hd">
    <div class="left_hd back_history">
        <i class="fal fa-long-arrow-left"></i>
    </div>
    <div class="center_hd">
        <h1>{{$title}}</h1>
    </div>
</header>
<script>
    $('.right_hd').on('click',function(){
        swal({
                title: "알림",
                text: "업체 등록을 중단하고 \n홈으로 이동하시겠습니끼?",
                button: "확인",
            });
    });
</script>
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
        padding-top: 0.7em;
        box-sizing: content-box;
    }

    #sub_hd .center_hd h1{
        padding: 0.75em 0;
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