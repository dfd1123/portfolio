@foreach($popups as $popup)
        @if(!isset($_COOKIE['nopopup'.$popup->id]) || (isset($_COOKIE['nopopup'.$popup->id]) && $_COOKIE['nopopup'.$popup->id] != 1))
            <div id="popup_{{$popup->id}}" class="popupWrap" style="z-index:10{{$popup->id}}px">
                <div class="popup_overlay"></div>
                <div class="popupCon">
                        <div class="popup_li">
                            <div class="close-container">
                                <div class="leftright"></div>
                                <div class="rightleft"></div>
                                <label class="close">close</label>
                            </div>
                            <div class="no_ore_chck_box">
                                <label><input type="checkbox" class="grayCheckbox no_more_today" data-id="{{$popup->id}}" id="no_more_today_{{$popup->id}}">{{__('popup.today')}}</label>
                            </div>
                            @if($popup->pc_img == NULL)
                                <h2 class="tit">{{$popup->title}}</h2>
                                <div class="body">
                                    {!! $popup->body !!}
                                    <!--img src="{{asset('/storage/image/homepage/sharebits-logo-dark.svg')}}" class="logo_img" alt="sharebits-logo"-->
                                </div>
                            @else
                                <img src="{{ asset('/storage/image/popup'.$popup->pc_img) }}" class="only_img" alt="popup_{{$popup->id}}">
                            @endif
                        </div>
                </div>
            </div>
        @endif
@endforeach

<style>

    .popup_overlay{
        position: fixed;
        top: 0;
        left: 0;
        z-index: 15;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
    }

    .popupWrap{
        width: 100%;
        position: relative;
        text-align: center;
    }

    .popupWrap .popupCon{
        width: 550px;
        margin: 0 auto;
        position: relative;
        z-index: 16;
    }

    .popup_li{
        position: absolute;
        top: 14rem;
        left: 0;
        width: 100%;
        background: #fff;
        border-radius: 7px;
    }

    .popup_li .only_img{
        width:auto;
        margin:0 auto;
    }

    .popup_li h2.tit{
        background: rgb(68,74,88);
        font-size: 23px;
        color: #fff;
        font-weight: bold;
        padding: 13px 0;
        border-top-right-radius: 7px;
        border-top-left-radius: 7px;
    }

    .popup_li>div.body{
        padding: 20px;
        padding-bottom: 13px;
        text-align: center;
    }

    .popup_li>div.body .logo_img{
        width: 101px;
        padding-top: 27px;
    }

    .no_ore_chck_box{
        position: absolute;
        bottom:-30px;
        left:0;
        cursor:pointer;
    }

    .no_ore_chck_box label{
        color: #fff;
        font-size:13px;
    }

    .no_ore_chck_box input{
        margin-right: 10px;
    }

    .close-container{
        position: absolute;
        top:-57px;
        right:0;
        margin: auto;
        width: 50px;
        height: 50px;
        margin-top: 0;
        cursor: pointer;
    }

    .leftright{
        height: 2px;
        width: 40px;
        position: absolute;
        margin-top: 24px;
        background-color: #fff;
        border-radius: 2px;
        transform: rotate(45deg);
        transition: all .3s ease-in;
    }

    .rightleft{
        height: 2px;
        width: 40px;
        position: absolute;
        margin-top: 24px;
        background-color: #fff;
        border-radius: 2px;
        transform: rotate(-45deg);
        transition: all .3s ease-in;
    }

    .close-container label{
        display:none;
        color: white;
        font-family: Helvetica, Arial, sans-serif; 
        font-size: .6em;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all .3s ease-in;
        opacity: 0;
    }
    
</style>

<script>
    $(document).ready(function(){
        $('.popupWrap .popup_overlay').click(function(){
            $(this).parent().remove();
        });

        $('.popupWrap .close-container').click(function(){
            $(this).parent().parent().parent().remove();
        });

        $('.no_more_today').click(function(){
            var id = $(this).data('id');

            if ($('#no_more_today_'+id).is(":checked")){
                $('#popup_'+id).remove();
                $.ajax({
                    url : "/nomore/popup",
                    type : "POST",
                    data : {_token : CSRF_TOKEN, id : id},
                    dataType : "JSON"
                }).done(function(data) {
                    
                }).fail(function(){
                    console.log("error");
                });
            }
        })
    })


</script>