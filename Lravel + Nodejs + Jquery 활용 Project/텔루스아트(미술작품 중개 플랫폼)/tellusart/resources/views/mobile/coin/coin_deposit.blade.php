@extends('mobile.layouts.app')

@section('content')

<div class="sub-container">
	
	<div id="scroll-content" class="coebox">
		<h3 class="tit">코인관리하기</h3>
		<ul class="tabs">
			<li><a href="{{route('coin.coin_edit')}}">코인입출금</a></li>
			<li><a class="active">입출금내역</a></li>
		</ul>
		<div id="transactions_list" class="tab-content mobile"></div>
	</div>
</div>

<!-- template -->
<template id="coin-deposit-item">
	<div class="cashin">
		<em class="date en"><!--2019.05.31--></em>
		<dl>
			<dt class="type"><!--출금--></dt>
			<dd class="en">
				<strong><span class="amount"><!--1.00000000--></span><em>TLG</em></strong>
				<span class="address"><!--0x9A0cCD5f57f6383D76F9eA266b7D471053620DC8--></span><br/>
				<span class="txid" style="opacity: 0.8"><!--0xda1587765911a152f9f847133ad012ffdec608df1cb83027c45da7f38f8da1c9--></span>
			</dd>
		</dl>
		<span class="status"><!--출금완료--></span>
	</div>
</template>

<script>
	$(function(){
		refresh_transactions();
	});
</script>

@endsection