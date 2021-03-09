@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">
	
	<div class="coebox">
		<h3 class="tit">코인충전하기</h3>
		<ul class="tabs">
			<li><a href="coin_charge.html" >코인충전</a></li>
			<li><a href="coin_list.html"class="active">충전내역</a></li>
		</ul>
		<div id="tab-content" class="tab-content">
			<div class="cashin">
				<em class="en">2019.05.31</em>
				<dl>
					<dt>사용내역 </dt>
					<dd class="en gray"><strong>1.00000000<em>TLC</em></strong></dd>
					<dt>충전코인 </dt>
					<dd class="en gray"><strong>1.00000000<em>TLC</em></strong></dd>
				</dl>
			</div>
			<div class="cashin">
				<em class="en">2019.05.31</em>
				<dl>
					<dt>사용내역 </dt>
					<dd class="en gray"><strong>1.00000000<em>TLC</em></strong></dd>
					<dt>충전코인 </dt>
					<dd class="en gray"><strong>1.00000000<em>TLC</em></strong></dd>
				</dl>
			</div>
			<div class="cashin">
				충전내역이 없습니다.
			</div>
		</div>
	</div>
</div>


@endsection