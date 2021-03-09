<header id="sub_hd2">
    <div class="center_hd">
        <h1>{{$title}}</h1>
        <div class="search-div">
                <input id="main_search" placeholder="시공내역 검색"/>
            
            <div style="display:inline-block;float:right;text-align:right">
                <img src="{{asset('/images/btn_menu_wh.svg')}}" alt="searchbtn" style="width:20px;height:20px;"/>
            </div>
            
        </div>
        
    </div>
    <div class="right_hd alarm_hd_btn">
        <img src="{{asset('/images/btn_alarm_wh.svg')}}" class="alarm_icon" alt="alarm">
        <img src="{{asset('/images/icon_alarm_new.svg')}}" class="alarm_new_icon" alt="alarm_new">
    </div>
    
</header>
<style>
    #content {
        padding: 3.3em 0;
        padding-top: 9.8em;
        background: url(/images/bg_main_tile.png);
        background-size: 100% auto;
        background-repeat-x: repeat;
        background-repeat-y: repeat;
    }
    #sub_hd2{
        background: rgba(11, 115, 210);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 5;
    }

    #sub_hd2 .left_hd{
        position: absolute;
        top: 1.4em;
        left: 1.3em;
        z-index: 3;
    }

    #sub_hd2 .left_hd img{
        width: 1.3em;
    }

    #sub_hd2 .center_hd{
        text-align: center;
        height: 2.6em;
        color: #fff;
        padding-top: 0.7em;
        padding-bottom: 6.5em;
        box-sizing: content-box;
    }

    #sub_hd2 .center_hd h1{
        padding: 0.75em 0;
        font-size: 1.05em;
        font-weight: bold;
    }

    #sub_hd2 .right_hd{
        position: absolute;
        top: 1.55em;
        right: 1.3em;
        z-index: 3;
    }

    #sub_hd2 .right_hd img{
        width: 1.2em;
    }
    #sub_hd2 .right_hd{
        position: absolute;
        top: 1.55em;
        right: 1.3em;
        z-index: 3;
    }

    #sub_hd2 .right_hd img.alarm_icon{
        width: 1.2em;
    }

    #sub_hd2 .right_hd img.alarm_new_icon{
        position: absolute;
        top: -4px;
        right: -1px;
        z-index: 5;
        width: 0.7em;
        display:none;
    }


    .search-div{
        border-radius: 49.5px;
        box-shadow: 0px 13px 16px 0 rgba(36, 36, 36, 0.07);
        background-color: #ffffff;
        padding:14px 20px;
        margin:20px;
        text-align:left;
    }
    .search-div input{
        color:#adb3bc;
        text-align:left;
        font-size:15px;
        width:80%;
        background: transparent;
        border: transparent;
    }
    .search-div input:focus{
    outline: none;
    border: none !important;
    box-shadow: none;
}
</style>