@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">
	
	<div class="coebox">
		<h3 class="tit">코인관리하기</h3>
		<ul class="tabs">
			<li><a class="active">코인입출금</a></li>
			<li><a href="{{route('coin.coin_deposit')}}">입출금내역</a></li>
		</ul>
		<div id="tab-content" class="tab-content mobile">
			<div class="ipout kr">
				<p>회원님에게 할당된 아래 주소로 텔루스 골드를 입금할 수 있습니다.</p>
				<div>
					<h4>내 텔루스 골드 입금주소(입금전용)</h4>
					<span>코인을 입금하시려면 아래 QR코드 또는 주소를 사용하세요.</span>
					<ul>
						<li>
							<em><img src="https://chart.googleapis.com/chart?chs=110x110&cht=qr&chl={{ $coin_address }}&choe=UTF-8"></em>
						</li>
						<li><input type="text" id="address_copy" tabindex="2" title="" value="{{ $coin_address }}" disabled="" class="required kr" style="width:100%;">	<button onclick="address_copy();" class="joinbt"  style="width:100%;">주소복사</button></li>
					</ul>
				</div>
				<dl>
					<dt>출금주소</dt>
					<dd><input type="text" id="address_send" name="" tabindex="2" title="" class="required kr" style="width:60%;">	<button onclick="address_valid();" id="address_valid_btn" class="ylbt">주소체크</button></dd>
					<dt>출금수량(TLG)</dt>
					<dd><input type="number" id="address_send_amount" onkeyup="address_calcul_total();" tabindex="2" title="" class="required kr"style="width:60%;">	<button onclick="address_amount_maximum();" class="ylbt">최대</button></dd>
				</dl>
				<dl>
					<dt>출금수수료(부가세 포함)</dt>
					<dd id="address_send_fee" class="gray en">0</dd>
					<dt>총 출금(수수료 포함)</dt>
					<dd id="address_send_total"  class="gray en">0</dd>
				</dl>
				<button type="submit" class="joinbt w95" onclick="address_send();">출금하기</button>
			</div>
		</div>
	</div>
</div>

@endsection