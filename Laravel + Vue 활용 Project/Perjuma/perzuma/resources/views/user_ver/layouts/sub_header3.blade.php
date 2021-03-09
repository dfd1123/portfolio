<header id="sub_hd3">
    <div class="hd_wrap">
        <div class="left_hd back_history">
            <i class="fal fa-long-arrow-left"></i>
        </div>
        <div class="center_hd">
            <h1>{{$title}}</h1>
        </div>
    </div>
    @if($pagename[2] == 'company_page')
    <div class="company_page_subhd">
        <div id="estimate_view" class="ripple_btn {{($kind == 'estimate_view')?'active':''}}" onclick="location.href='/user_ver/company_page/estimate_view?agent_no={{$agent_no}}&trd_no={{$trd_no}}'">시공견적</div>
        <div id="review_view" class="ripple_btn {{($kind == 'review_view')?'active':''}}" onclick="location.href='/user_ver/company_page/review_view?agent_no={{$agent_no}}&trd_no={{$trd_no}}'">리뷰(<em>{{count($reviews)}}</em>)</div>
    </div>
    @endif
</header>
