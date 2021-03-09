@extends('pc.layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/pc/diffrent_hd.css')}}">
	<div id="container">
        <div class="orderbox">
            <div class="cartbox">
                <h3 class="mytit">주문상세보기</h3>
            </div> 
            <style>
                .otxt.txtb dl dt{margin-top:8px}
                .otxt.txtb dl dd{line-height:35px;font-size:15px;}
                .otxt ul.payment li.choice{font-size:15px;}
            </style>
            <!-- 장바구니 리스트 -->
            <form name="" method="post" action="">
                <div class="orderall">
                    <div class="leftform">
                        <h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 주문자 정보</h3>
                        
                        <div class="boxline">
                            <div class="otxt txtb">
                                <h4>주문자 정보</h4>
                                <dl>
                                    <dt>이름</dt>
                                    <dd>{{$order->user->name}}</dd>
                                </dl>
                                <dl>
                                    <dt>연락처</dt>
                                    <dd>{{$order->user->mobile_number}}</dd>
                                </dl>
                                <dl>
                                    <dt>이메일</dt>
                                    <dd>{{$order->user->email}}</dd>
                                </dl>
                            </div>
                            <div class="otxt txtb pt20">
                                <h4>배송지 정보</h4>
                                <dl>
                                    <dt>이름</dt>
                                    <dd>{{$order->delivery->getter_name}}</dd>
                                </dl>
                                <dl>
                                    <dt>연락처</dt>
                                    <dd>{{$order->delivery->getter_mobile}}</dd>
                                </dl>
                                <dl>
                                    <dt>이메일</dt>
                                    <dd>{{$order->delivery->getter_email}}</dd>
                                </dl>
                                <dl>
                                    <dt>주소</dt>
                                    <dd>{{$order->delivery->user_addr1}}{{$order->delivery->user_extra_addr}} {{$order->delivery->user_addr2}}</dd>
                                </dl>
                                <dl>
                                    <dt>배송메세지</dt>
                                    <dd>{{$order->delivery->delivery_ask}}</dd>
                                </dl>
                            </div>
                            <div class="otxt txtb pt20">
                                <h4>주문 처리상태</h4>
                                <dl>
                                    <dt>배송회사</dt>
                                    <dd>{{$delivery_company}}</dd>
                                </dl>
                                <dl>
                                    <dt>운송장번호</dt>
                                    <dd>
                                        @if($order->delivery->send_post_num != NULL)
                                            {{$order->delivery->send_post_num}}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>배송상태</dt>
                                    <dd>
                                        @if($order->order_state < 2)
                                            배송대기
                                        @elseif($order->order_state == 2)
                                            배송중
                                        @elseif($order->order_state >= 3)
                                            배송완료
                                        @endif                                        

                                    </dd>
                                </dl>
                                <dl>
                                    <dt>주문상태</dt>
                                    <dd>
                                        @if($order->order_state == 0)
                                            입금대기
                                        @elseif($order->order_state == 1)
                                            배송대기
                                        @elseif($order->order_state == 2)
                                            배송중
                                        @elseif($order->order_state == 3)
                                            배송완료
                                        @elseif($order->order_state == 4)
                                            @if($order->order_cancel == 1)
                                                주문취소
                                            @elseif($order->order_cancel == 2)
                                                환불
                                            @else
                                                취소/환불 신청
                                            @endif
                                        @elseif($order->order_state == 5)
                                            주문확정
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                            <div class="otxt txtb linenone pt20">
                                <h4>결제방식</h4>
                                <ul class="payment">
                                    <li class="choice">
                                        @if($order->how_pay == 0)
                                            무통장입금
                                        @elseif($order->how_pay == 10)
                                            코인결제
                                        @endif
                                    </li>
                                </ul>
                                @if($order->how_pay == 0)
                                    <dl>
                                        <dt>은행명</dt>
                                        <dd>{{$company->account_bank}}</dd>
                                    </dl>
                                    <dl>
                                        <dt>계좌번호</dt>
                                        <dd>{{$company->account_number}}</dd>
                                    </dl>
                                    <dl>
                                        <dt>예금주</dt>
                                        <dd>{{$company->account_user}}</dd>
                                    </dl>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="rightform">
                        <h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 결제정보</h3>
                        <div class="boxline graybox">
                            <div class="pro_order">
                                <ul>
                                    <li><img src="{{asset('/storage/image/product/'.$order->product->image1)}}" alt="상품 이미지"></li>
                                    <li>{{$order->product->title}}<span class="option">작가명 : {{$order->product->artist_name}} <br/>사이즈 : {{$order->product->art_width_size}} X {{$order->product->art_height_size}}cm</span></li>
                                </ul>
                                <dl>
                                    <dt>총 상품가격   :    </dt>
                                    <dd>
                                        <p class="number price en">
                                            <em class="coinic">c</em> {{number_format($order->product->coin_price, 8)}}
                                            <em class="kric">￦</em> {{number_format($order->product->cash_price)}}
                                        </p>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>배송비   :    </dt>
                                    <dd>
                                        <p class="number price en">
                                            @if($order->how_pay == 0)
                                                <em class="kric">￦</em> {{number_format($order->product->delivery_price)}}
                                            @elseif($order->how_pay == 10)
                                                <em class="coinic">c</em> 0
                                            @endif
                                        </p>
                                    </dd>
                                </dl>
                                <dl class="total">
                                    <dt>총 결제금액   :    </dt>
                                    <dd>
                                        <p class="number price en">
                                            @if($order->how_pay == 0)
                                                <em class="kric">￦</em> {{number_format($order->total_price)}}
                                            @elseif($order->how_pay == 10)
                                                <em class="coinic">c</em> {{number_format($order->total_price)}}
                                            @endif
                                        </p>
                                    </dd>
                                </dl>
                            </div>
                            
                            <a href="{{route('mypage.my_order_list')}}"class="joinbt">주문목록 돌아가기</a>
                        </div>
                    </div>
                    
                </div>
            </form>
            <!-- //장바구니 리스트 -->
        </div>
    </div>
    
@endsection
