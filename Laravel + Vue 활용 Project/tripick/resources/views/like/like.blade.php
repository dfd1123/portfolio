@extends('layouts.app')

@section('content')
<div class="wrapper wrapper--like">

    <div class="hd-title hd-title--05">
        <h2 class="hd-title__center">찜 목록</h2>
        <div class="hd-title__sch">
            <input type="text" id="search_fa_pln" placeholder="플래너 검색">
        </div>
    </div>

    <div class="wrapper--like__scroll-area">
        <ul class="user-like__group" id="search_fa_pln_ul">
            @forelse($likes as $like)
            <li class="user-list user-like__list" onClick="location.href='/planner/view/{{ $like->pln_id }}/0';">
                <div class="user-like__card">
                    <figure class="user-list__thum" style="background-image: url(/storage/fdata/planner/thumb/{{ $like->pln_thumb }});"></figure>
                    <h5 class="user-list__name">
                        <b>{{ $like->pln_name }}</b>
                        <span class="user-like__type">{{ $like->pln_type == 0 ? '개인' : '업체'}}</span>
                    </h5>
                    <p class="user-list__msg">{{ $like->pln_desc }}</p>
                </div>
            </li>
            @empty
            <li class="user-list user-like__list user-like__list--nothing">
                <div class="user-like__card">
                    <img src="/img/icon/icon-nothing-plnr.svg" class="icon">
                    <span class="caution">찜한 플래너가 없습니다.</span>
                </div>
            </li>
            @endforelse
        </ul>
    </div>

</div>

@include('nav.nav_user')
@endsection

@section('script')
<script>
    $('#search_fa_pln').keyup(function(){
        
        var keyword = $(this).val();
        
        $('#search_fa_pln_ul li').hide();

        var temp = $('#search_fa_pln_ul li div h5 b:contains("'+keyword+'")').closest('li').show();
    });
</script>
@endsection