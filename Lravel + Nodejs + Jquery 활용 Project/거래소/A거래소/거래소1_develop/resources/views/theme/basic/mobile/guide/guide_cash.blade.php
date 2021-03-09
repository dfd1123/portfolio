@extends(session('theme').'.mobile.layouts.app') 
@section('content')

<div id="guide_page_wrapper">

    <div class="m_hd_title">
        <div class="inner">
            이용안내
        </div>
    </div>

    <div class="m_tab_list">
        <ul>
            <li class="active">
                <a href="/guide/guide_cash"> 
                    원화 입출금
                </a>
            </li>
            <li>
                <a href="#" onclick="alert('준비 중입니다.');">
                <!-- <a href="/guide/guide_coin" >  -->
                    코인 입출금
                </a>
            </li>
            <li>
                <a href="#" onclick="alert('준비 중입니다.');">
                <!-- <a href="/guide/guide_trade">  -->
                    매수&매도
                </a>
            </li>
        </ul>
    </div>

    <div class="scrl_wrap">
        <ul class="guide_page_board">
            <li class="guide_list" onclick="open_img(0);">원화 입금 방법</li>
            <li class="guide_list" onclick="alert('준비중입니다');">원화 출금 방법</li>
        </ul>
    </div>

    <div id="guide_img_wrap" class="guide_img_wrap">
        <img src="" alt="image" id="img_guide">
        <input type="button" class="guide_img_wrap_close" onclick="close_img();"> 
    </div>

</div>

<script>

// 누르면 팝업 열리는 함수
function open_img(index){
    var img_list = ['/images/guide_cash_in_img.png','test'];
    $('#img_guide').attr('src', img_list[index]);
    $('#guide_img_wrap').fadeIn(300);
}
// end

// 열린 팝업 닫기버튼
function close_img(){
    $('#guide_img_wrap').hide();
}
// end

</script>


@endsection