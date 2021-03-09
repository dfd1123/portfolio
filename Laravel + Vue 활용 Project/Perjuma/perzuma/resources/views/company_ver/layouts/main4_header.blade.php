<header id="sub_hd4">
    <div class="left_hd back_history">
        <i class="fal fa-long-arrow-left" aria-hidden="true"></i>
    </div>
    <div class="center_hd">
        <h1>{{$title}}</h1>
    </div>
    <div class="right_hd alarm_hd_btn">
        <img src="{{asset('/images/btn_alarm_wh.svg')}}" class="alarm_icon" alt="alarm">
        <img src="{{asset('/images/icon_alarm_new.svg')}}" class="alarm_new_icon" alt="alarm_new">
    </div>
</header>

<style>
    #sub_hd4{
        background: #007bd2;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 5;
    }

    #sub_hd4 .left_hd{
        position: absolute;
        top: 1.4em;
        left: 1.3em;
        z-index: 3;
    }

    #sub_hd4 .left_hd i{
        color: #fff;
        font-size: 1.4em;
    }

    #sub_hd4 .center_hd{
        text-align: center;
        height: 2.6em;
        color: #fff;
        padding-top: 0.7em;
        box-sizing: content-box;
    }

    #sub_hd4 .center_hd h1{
        padding: 0.75em 0;
        font-size: 1.05em;
        font-weight: bold;
    }

    #sub_hd4 .right_hd{
        position: absolute;
        top: 1.55em;
        right: 1.3em;
        z-index: 3;
    }

    #sub_hd4 .right_hd img.alarm_icon{
        width: 1.2em;
    }

    #sub_hd4 .right_hd img.alarm_new_icon{
        position: absolute;
        top: -4px;
        right: -1px;
        z-index: 5;
        width: 0.7em;
        display:none;
    }
</style>