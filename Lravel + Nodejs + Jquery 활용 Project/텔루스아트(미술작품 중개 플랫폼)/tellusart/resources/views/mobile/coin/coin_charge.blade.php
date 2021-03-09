@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">
	
	<div class="coebox">
		<h3 class="tit">코인충전하기</h3>
		<ul class="tabs">
			<li><a href="coin_charge.html" class="active">코인충전</a></li>
			<li><a href="coin_list.html">충전내역</a></li>
		</ul>
		<div id="tab-content" class="tab-content">
			<div class="cashin cbox">
				<dl class="line">
					<dt><i class="fal fa-chevron-circle-right"></i> 코인충전에 사용할 금액</dt>
					<dd><input type="number" title="" id="charge_cash" onkeyup="charge_calcul();" class="required kr" style="width:85%"></dd>
				</dl>
				<dl class="last">
					<dt><i class="fal fa-chevron-circle-right"></i> 예상되는 최대 텔루스골드 충전개수 <em>* 최대 개수보다 적을수 있습니다.</em></dt>
					<dd class="fir"><input type="text" title="" id="charge_coin" class="required kr" disabled="disabled"></dd>
				</dl>
				<button id="charge_submit" class="joinbt">충전하기</button>
			</div>
		</div>
	</div>
</div>

@endsection