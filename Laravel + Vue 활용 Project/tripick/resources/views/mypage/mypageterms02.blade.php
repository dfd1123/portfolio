@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--mypage">
    
    <div class="hd-title hd-title--01">
        <button type="button" onClick="history.back()" class="hd-title__left-btn hd-title__left-btn--prev"><span class="none">이전버튼</span></button>
        <h2 class="hd-title__center">개인정보 처리방침</h2>
    </div>

    <div class="wrapper--mypage__scroll-area">
        <div class="terms_style">
            @include('mypage.include.terms02')
        </div>
    </div>

</div>

@include('nav.nav_user')

@endsection

@section('script')
<script>
</script>
@endsection