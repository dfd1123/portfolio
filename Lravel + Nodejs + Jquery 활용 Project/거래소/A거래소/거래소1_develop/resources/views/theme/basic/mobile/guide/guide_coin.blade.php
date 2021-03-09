@extends(session('theme').'.mobile.layouts.app') 
@section('content')

<div id="guide_page_wrapper">

    <div class="m_hd_title">
        <div class="inner">
            이용안내
        </div>
    </div>

    <div class="m_tab_list">
        <ul>
            <li>
                <a href="/guide/guide_cash"> 
                    원화 입출금
                </a>
            </li>
            <li class="active">
                <a href="/guide/guide_coin"> 
                    코인 입출금
                </a>
            </li>
            <li>
                <a href="/guide/guide_trade"> 
                    매수&매도
                </a>
            </li>
        </ul>
    </div>

    <div class="scrl_wrap">

    </div>  

</div>

<style>

#guide_page_wrapper{
    height: 100%;
}

#guide_page_wrapper > .scrl_wrap{
    height: calc(100% - 40px);
    background-color: #f8f8f8;
}

</style>


@endsection