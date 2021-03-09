<nav role="navigation" class="header">
	<span class="ion-ios-navicon pull-left"><i></i></span>
	<h1 class="logo en">tellus art</h1>
	<div class="tsearch">
		<a href="" class="search"><i class="fal fa-search"></i></a>
	</div>
</nav>
<div class="main_search">
	<div>
		<a href="" class="ct"><img src="{{asset('/storage/image/mobile/ic_sclose.png')}}"/></a>
		<form id="search_item" method="get" action="{{route('products.search_list',-1)}}">
			@csrf
			<input type="text" name="keyword" placeholder="작가명, 회화명, 분야 등으로 검색">
			<span><button type="submit"><img src="{{asset('/storage/image/mobile/ic_search_w.png')}}" alt=""/></span>
		</form>
	</div>
</div>
<script>
		$("a.search").click(function(){
			$(".main_search").animate({height:40},300,"linear"); 
			return false;
		});
		$("a.ct").click(function(){
			$(".main_search").animate({height:0},100,"linear"); 
			return false;
		});
</script>