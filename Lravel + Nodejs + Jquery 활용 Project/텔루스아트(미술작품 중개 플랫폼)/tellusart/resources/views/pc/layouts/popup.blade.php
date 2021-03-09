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
                                <label><input type="checkbox" class="grayCheckbox no_more_today" data-id="{{$popup->id}}" id="no_more_today_{{$popup->id}}">오늘하루동안 보지 않기</label>
                            </div>
                            @if($popup->link != NULL)
                            <a href="http://{{$popup->link}}">
                            @endif
                            @if($popup->pc_img == NULL)
                                <h2 class="tit">{{$popup->title}}</h2>
                                <div class="body">
                                    {!! $popup->body !!}
                                </div>
                            @else
                                <img src="{{ asset('/storage/image/popup'.$popup->pc_img) }}" class="only_img" alt="popup_{{$popup->id}}">
                            @endif
                            @if($popup->link != NULL)
                            </a>
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
        z-index: 100;
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
        z-index: 101;
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
        width: inherit;
        max-width:100%;
        margin:0 auto;
    }

    .popup_li h2.tit{
        background: #fea803;
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
        margin-right: 15px;
    }
    
    input.no_more_today {
        vertical-align: baseline;
        box-sizing: border-box;
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

    .grayCheckbox {
        width: 12px;
        height: 12px;
        position: relative;
        transition: all .3s;
    }

    .grayCheckbox:checked::after {
        content: '';
        position: absolute;
        background-image: url(/storage/image/homepage/icon/graycheckbox-02.svg);
        background-size: 110%;
        background-color: #464a58;
        background-position: center;
        transition: all .3s;
        border: 2px solid #464a58;
        width: 20px;
        height: 20px;
        top: -3px;
        left: -3px;
    }

    .grayCheckbox::after {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        width: 20px;
        height: 20px;
        background-color: #fff;
        border: 2px solid #464a58;
        border-radius: 50%;
        transition: all .3s;
        background-size: 115%;
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

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
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