<div class="sidebar">
    <div class="sidebar-overlay"></div>
    <div class="sidebar-content">
        
        <div class="top-head">
            <h1><a href="{{route('home')}}"><img src="{{asset('/storage/image/mobile/ic_home.png')}}" alt="home"/></a></h1>
            @auth
                <div class="name">{{Auth::user()->name}}님 <em>환영합니다</em><a href="{{route('mypage.password_edit')}}" class="kr">비밀번호변경</a></div>
                <div class="coin en">{{number_format($coin_balance, 2)}}<em>c</em><a href="{{route('mypage.account_edit')}}" class="kr">내계좌정보</a></div>
            @else
                <div class="name guest">로그인을 해주세요</em><a href="{{route('login')}}" class="kr">로그인</a></div>
            @endauth
        </div>
        @auth
            <div class="my-icons">
                <ul>
                    <li><a href="{{route('mypage.mobile_mypage')}}?index=3"><img src="{{asset('/storage/image/mobile/ic_left_icon01.png')}}" alt=""/><em class="en" id="sidebar_total_cart_cnt">{{$share_cart_cnt}}</em><span>장바구니</span></a></li>
                    <li><a href="{{route('mypage.mobile_mypage')}}?index=2"><img src="{{asset('/storage/image/mobile/ic_left_icon02.png')}}" alt=""/><em class="en" id="sidebar_total_batting_cnt">{{$my_batting_cnt}}</em><span>베팅수</span></a></li>
                    <li><a href="{{route('mypage.mobile_mypage')}}?index=4"><img src="{{asset('/storage/image/mobile/ic_left_icon03.png')}}" alt=""/><em class="en" id="sidebar_total_order_cnt">{{$my_order_cnt}}</em><span>구매수</span></a></li>
                </ul>
            </div>
        @endauth
        <div class="navmenu">
            <a href="{{route('product_list.sel_product',0)}}">갤러리<img src="{{asset('/storage/image/mobile/ic_menugo.png')}}" alt=""/></a>
            <a href="{{route('product.batting_list',["ca_id"=>0, "status"=>1])}}">베팅<img src="{{asset('/storage/image/mobile/ic_menugo.png')}}" alt=""/></a>
            <a href="{{route('products.create')}}">작품등록<img src="{{asset('/storage/image/mobile/ic_menugo.png')}}" alt=""/></a>
            <a href="{{route('policy.policy', 1)}}">서비스 정책<img src="{{asset('/storage/image/mobile/ic_menugo.png')}}" alt=""/></a>
            <a href="{{route('howtouse.howtouse')}}">이용방법<img src="{{asset('/storage/image/mobile/ic_menugo.png')}}" alt=""/></a>
        </div>
        <div class="mymic">
            <ul>
                <li><a href="{{route('notice.list')}}"><img src="{{asset('/storage/image/mobile/img_notice.png')}}" alt=""/>공지사항</a></li>
                <li><a href="{{route('events.index')}}"><img src="{{asset('/storage/image/mobile/img_eventgo.png')}}" alt=""/>이벤트</a></li>
                <li><a href="{{route('faq.list')}}"><img src="{{asset('/storage/image/mobile/img_faq.png')}}" alt=""/>FAQ</a></li>
            </ul>
        </div>
        <div style="padding: 0 15px;margin-top: 5px;text-align: left;">
            <a href="#" onclick="javascript:onPopKBAuthMark();return false;">
                <img src="http://img1.kbstar.com/img/escrow/escrowcmark.gif" border="0" style="width: 52px;padding: 6px;background: #fff;border: 1px solid #ddd;">
            </a>
        </div>
        <div class="latest hidden">
            <h3>공지사항</h3>
            <span><a href="{{route('notice.list')}}"><img src="{{asset('/storage/image/mobile/ic_latest_go.png')}}" alt=""/></a></span>
            <!--<ul>
                <li><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/>[회화] <strong>연인, 바다, 이별</strong></a></li>
                <li><a href=""><img src="{{asset('/storage/image/mobile/img_pic_view.png')}}" alt=""/>[회화] <strong>연인, 바다, 이별</strong></a></li>
            </ul> -->
        </div>
    </div>
</div>

<form name="KB_AUTHMARK_FORM" method="get">
    <input type="hidden" name="page" value="C021590"/>
    <input type="hidden" name="cc" value="b034066:b035526"/>
    <input type="hidden" name="mHValue" value='46df1f044e7c844c918e4808064a8d88201712271501362'/>
</form>

<script>
function onPopKBAuthMark()
{
    window.open('','KB_AUTHMARK','height=604, width=648, status=yes, toolbar=no, menubar=no,location=no');
    document.KB_AUTHMARK_FORM.action='https://okbfex.kbstar.com/quics';
    document.KB_AUTHMARK_FORM.target='KB_AUTHMARK';
    document.KB_AUTHMARK_FORM.submit();
}
</script>