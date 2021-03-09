@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--home">

	<div class="wrapper--home__scroll-area">

		<div class="wrapper--home__recommend-intro">

			<div class="recommend-intro-card">

				@if(isset($check_estm[0]))
				<button type="button" class="button button--01" onClick="location.href='/estimate/step1'">
					추가 추천받기
				</button>
				<button type="button" class="button button--01" onClick="location.href='/estimate/match/{{ $check_estm[0]->estm_id }}'">
					받은 추천관리
				</button>
				@else
				<button type="button" class="button button--01" onClick="location.href='/estimate/step1'">
					추천받기
				</button>
				@endif
				<span class="inline inline--center"> <a href="/pr_home" class="wrapper--home__product__alink wrapper--home__product__alink--view-prdt">추천 상품 보기</a> <a href="/find" class="wrapper--home__product__alink wrapper--home__product__alink--find-frnd">동행객 찾기</a> </span>
			</div>

		</div>

		<div class="wrapper--home__estimate-con">

			<div class="inline inline--left">
				<h2 class="wrapper--home__estimate-con__title">견적입찰현황</h2>
			</div>

			<ul id="eb_list_ul">
				@forelse($ebs as $eb)
				<li class="matching-product__list" onclick="location.href='/planner/view/{{ $eb->pln_id }}/{{ $eb->eb_id }}';">
					<div class="matching-product__card">
						<figure class="matching-product__card__logo" style="background-image: url({{'/storage/'.config('filesystems.planner_thumb').$eb->pln_thumb}});"></figure>
						<dl class="matching-product__card__info">
							<dt class="matching-product__card__title">
								{{$eb->pln_name}}<span
								class="matching-product__card__status is-ing">매칭 진행중</span>
							</dt>
							<dd class="matching-product__card__desc01">
								{{$eb->eb_title}}
							</dd>
							
							<dd class="matching-product__card__desc02">
								<em class="matching-product__card__info--color">기간</em>{{$eb->estm_period}}
								<br>
								<em
								class="matching-product__card__info--color">장소</em>{{$eb->estm_area}}
							</dd>
							<dd class="matching-product__card__desc02">
								<em class="matching-product__card__info--color">제안내용</em>{{$eb->eb_desc}}
								<br>
								<em class="matching-product__card__info--color">제안가</em>{{ number_format($eb->estm_asking_price)}}원
							</dd>
						</dl>
					</div>
				</li>
				@empty
				<li class="matching-product__list matching-product__list--nothing">
					<div class="matching-product__card">
						<img src="/img/icon/icon-nothing-plnr.svg" class="icon">
						<span class="caution"> @if(!isset($check_estm[0]))
							진행중인 견적이 없습니다.
							<br>
							추천받기를 통해 견적을 등록해주세요!
							@else
							입찰중인 플래너가 없습니다.
							@endif </span>
					</div>
				</li>
				@endforelse

			</ul>
		</div>
	</div>

</div>
<template id="eb_list_tmpl">
    <li class="matching-product__list"  name="tplt_eb_li" onclick="">
        <div class="matching-product__card">
            <figure class="matching-product__card__logo" name="tplt_pln_thumb" style="background-image: url(/storage/fdata/planner/thumb/);"></figure>
            <dl class="matching-product__card__info">
                <dt class="matching-product__card__title" name="tplt_pln_name"><span
                        class="matching-product__card__status is-ing">매칭 진행중</span></dt>
                <dd class="matching-product__card__desc01"  name="tplt_pln_desc"></dd>
                <dd class="matching-product__card__desc02">
                    <em class="matching-product__card__info--color">기간</em><span name="tplt_estm_period"></span><br>
                    <em class="matching-product__card__info--color">장소</em><span name="tplt_estm_area"></span>
                </dd>
            </dl>
        </div>
    </li>
</template>
@include('nav.nav_user')
@endsection

@section('script')
<script>
    $(function(){
        var ebStart = 20;
        $('.wrapper--home__scroll-area').bind('scroll', function(){
            if($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight){
                $.ajax({
                    url: "/api/estimate_bid/estimate_bid_user",
                    data: {
                        offset: ebStart
                    },
                    method: "GET",
                    dataType: "json",
                    async: false
                })
                .done(function(data) {
                    if (data.state == 1) {
                        $.each(data.query, function(index, item){
                            var templete = $($('#eb_list_tmpl').html());
                            
                            templete.attr("onclick","location.href='/planner/view/"+item.pln_id+"/"+item.eb_id+"';");
                            templete.find('[name=tplt_pln_thumb]').css("background-image","url(/storage/"+item.pln_thumb+")");
                            templete.find('[name=tplt_pln_name]').text(item.pln_name);
                            templete.find('[name=tplt_pln_desc]').text(item.pln_desc);
                            templete.find('[name=tplt_estm_period]').text(item.estm_period);
                            templete.find('[name=tplt_estm_area]').text(item.estm_area);
                        
                            $('#eb_list_ul').append(templete);
                        });
                        ebStart += data.query.length;

                    } else {
                        dialog.alert({
                            title:'오류',  
                            message: data.msg,
                            button: "확인"
                        });
                    }
                })
                .fail(function(xhr, status, errorThrown) {
                    console.log(xhr);
                }) // 
                .always(function(xhr, status) {});
                
            }
        });
    });
    
        
</script>
@endsection