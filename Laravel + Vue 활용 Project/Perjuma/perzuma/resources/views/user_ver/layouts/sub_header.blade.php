<header id="sub_hd">
    <div class="left_hd {{($pagename[2] != 'estimate_request')?'back_history':'es_req'}}" {{($pagename[2] == 'estimate_request')?'data-step='.$pagename[3].' data-id='.$trd_no:''}}>
        <i class="fal fa-long-arrow-left"></i>
    </div>
    <div class="center_hd">
        <h1>{{$title}}</h1>
    </div>
    @if($pagename[2] == 'estimate_request' || $pagename[2] == 'result_confirm')
        @if($pagename[2] == 'result_confirm')
            @if($kind != 're_confirm')
                <div class="right_hd close_hd_btn"></div>
            @endif
        @else
            <div class="right_hd close_hd_btn"></div>
        @endif
    @endif
</header>