<header id="sub_hd5">
    <div class="left_hd back_history">
        <i class="fal fa-long-arrow-left"></i>
    </div>
    <div class="center_hd">
        <h1>{{$title}}</h1>
        <span>{{$updated_at}}</span>
    </div>
    <div class="right_hd alarm_hd_btn">
        <img src="{{asset('/images/btn_alarm_wh.svg')}}" class="alarm_icon" alt="alarm">
        <img src="{{asset('/images/icon_alarm_new.svg')}}" class="alarm_new_icon" alt="alarm_new">
    </div>
</header>

<style>
    #sub_hd5{
        background: #007bd2;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 5;
    }

     #sub_hd5 .left_hd{
        position: absolute;
        top: 1.3em;
        left: 1.3em;
        z-index: 3;
    }

    #sub_hd5 .left_hd i{
        color: #fff;
        font-size: 1.4em;
    }

    #sub_hd5 .center_hd{
        text-align: center;
        padding: 0.75em 0;
        color: #fff;
        padding-top: 1em;
        box-sizing: content-box;
    }

    #sub_hd5 .center_hd h1{
        font-size: 1.05em;
        font-weight: bold;
    }

    #sub_hd5 .center_hd span{
        display: block;
        font-size: 0.7em;
        line-height: 1.89;
        letter-spacing: -0.36px;
        color: #eaf2ff;
    }

    #sub_hd5 .right_hd{
        position: absolute;
        top: 1.55em;
        right: 1.3em;
        z-index: 3;
    }

    #sub_hd5 .right_hd img.alarm_icon{
        width: 1.2em;
    }

    #sub_hd5 .right_hd img.alarm_new_icon{
        position: absolute;
        top: -4px;
        right: -1px;
        z-index: 5;
        width: 0.7em;
        display:none;
    }
</style>