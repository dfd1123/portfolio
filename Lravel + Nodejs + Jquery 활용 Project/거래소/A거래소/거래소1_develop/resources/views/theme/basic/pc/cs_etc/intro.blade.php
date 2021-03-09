@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div id="company_intro_wrapper">

    <div class="hd_container">
        <span class="bg_span"></span>
        <h2 class="_tit">
            <b>{{ __('support.title_company_intro') }}</b>
        </h2>
        <span class="_tit_bt_line"></span>
    </div>

    <div class="com_intro_wrapper">

        <div class="intro_section section_con" data-index="0">

            <h3 class="_com_intro_main_tit">{{ __('support.company_intro_sec01_tit') }}</h3>
            <p class="_text">{!! __('support.company_intro_sec01_txt') !!}</p>
            
            <div class="company_character_wrapper">
                <ul class="company_character_group">
                    <li class="company_character_list">
                        <img src="/images/icon/com_character_icon_04.svg" alt="com_character_icon" class="_icon">
                        <h4>{{ __('support.innovation') }}</h4>
                        <p>{!! __('support.innovation_desc') !!}</p>
                    </li>
                    <li class="company_character_list">
                        <img src="/images/icon/com_character_icon_03.svg" alt="com_character_icon" class="_icon">
                        <h4>{{ __('support.security') }}</h4>
                        <p>{!! __('support.security_desc') !!}</p>
                    </li>
                    <li class="company_character_list">
                        <img src="/images/icon/com_character_icon_01.svg" alt="com_character_icon" class="_icon">
                        <h4>{{ __('support.fun') }}</h4>
                        <p>{!! __('support.fun_desc') !!}</p>
                    </li>
                    <li class="company_character_list">
                        <img src="/images/icon/com_character_icon_02.svg" alt="com_character_icon" class="_icon">
                        <h4>{{ __('support.communication') }}</h4>
                        <p>{!! __('support.communication_desc') !!}</p>
                    </li>
                    <li></li>
                </ul>
            </div>

        </div>

        <div class="intro_desc_section section_con" data-index="1">

            <h3 class="_com_intro_main_tit">{{ __('support.company_intro_sec02_tit') }}</h3>
            <p class="_text">{!! __('support.company_intro_sec02_txt') !!}</p>
            
            <div class="guide_line_logos">
                <img src="/images/logos/guideline_img_logo_01.svg" alt="guideline_img_logo" class="guideline_logo">
                <img src="/images/logos/guideline_img_logo_02.svg" alt="guideline_img_logo" class="guideline_logo">
                <img src="/images/logos/header_logo_blue-2.svg" alt="guideline_img_logo" class="guideline_logo">
            </div>

            <div class="guide_line_text_wrapper">
                <div class="guide_line_text_con">
                    <h5>{{ __('support.guide_line_01_tit') }}</h5>
                    <p>{{ __('support.guide_line_01_txt') }}</p>
                </div>
                <div class="guide_line_text_con">
                    <h5>{{ __('support.guide_line_02_tit') }}</h5>
                    <p>{{ __('support.guide_line_02_txt01') }}</p>
                    <span class="mini_ex">ex)</span>
                    <br>{!! __('support.guide_line_02_txt02') !!}</p>
                </div>
                <div class="guide_line_text_con">
                    <h5>{{ __('support.guide_line_03_tit') }}</h5>
                    <p>{{ __('support.guide_line_03_txt01') }}<br>
                        <span class="mini_ex">ex)</span><br>
                        <b class="list_dot">·</b>{{ __('support.guide_line_03_txt02') }}<br>
                        <b class="list_dot">·</b>{{ __('support.guide_line_03_txt03') }}<br>
                        <b class="list_dot">·</b>{{ __('support.guide_line_03_txt04') }}<br>
                        <b class="list_dot">·</b>{{ __('support.guide_line_03_txt05') }}<br>
                        <b class="list_dot">·</b>{{ __('support.guide_line_03_txt06') }}<br>
                    </p>
                </div>
                <div class="guide_line_text_con">
                    <h5>{{ __('support.guide_line_04_tit') }}</h5>
                    <p>{{ __('support.guide_line_04_txt') }}</p>
                </div>
                <div class="guide_line_text_con">
                    <h5>{{ __('support.guide_line_05_tit') }}</h5>
                    <p>{!! __('support.guide_line_05_txt') !!}</p>
                </div>
                <div class="guide_line_text_con">
                    <h5>{{ __('support.guide_line_06_tit') }}</h5>
                    <p>{!! __('support.guide_line_06_txt') !!}</p>
                </div>
                <div class="guide_line_text_con">
                    <h5>{{ __('support.guide_line_07_tit') }}</h5>
                    <p>{!! __('support.guide_line_07_txt') !!}</p>
                </div>
            </div>

        </div>


        <div class="map_wrap section_con" data-index="2">

            <div class="map_inner">
                <div class="map_frame">
                <h4 class="map_topic">{{ __('support.roadmap') }}</h4>
                <div class="years">
                    <span class="map_year active map_year_space this_year">2019</span>
                    <span class="map_year next_year">2020</span>  
                </div>
                </div>
            </div>
                
            <div class="ex-roadmap-wrapper">
                <ul class="ex-roadmap-navi">
                <li class="ex-roadmap-list active"><span class="ex-roadmap-dot"></span></li>
                <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
                <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
                <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
                <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
                <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
                <li class="ex-roadmap-list"><span class="ex-roadmap-dot"></span></li>
                </ul>
            </div>

            <div class="ex-roadmap-desc-wrapper">
                <ul class="ex-roadmap-desc-group">
                    <li class="ex-roadmap-desc-list">
                        <span class="_month">{{ __('support.month_04') }}</span>
                        <h5 class="_tit">{{ __('support.ex-roadmap_01_tit') }}</h5>
                        <p class="_desc">{!! __('support.ex-roadmap_01_txt') !!}</p>
                    </li>
                    <li class="ex-roadmap-desc-list">
                        <span class="_month">{{ __('support.month_09') }}</span>
                        <h5 class="_tit">웹 기반 그래픽 거래 플랫폼</h5>
                        <p class="_desc">온라인상에서 암호화폐를 거래할 수 있는<br>최첨단 그래픽 거래 플랫폼 구축</p>
                    </li>
                    <li class="ex-roadmap-desc-list">
                        <span class="_month">{{ __('support.month_09') }}</span>
                        <h5 class="_tit">비트코인 이더리움 리플 등 상장</h5>
                        <p class="_desc">시장 선도 코인 상장으로 통합 거래소 플랫폼 구축</p>
                    </li>
                    <li class="ex-roadmap-desc-list">
                        <span class="_month">{{ __('support.month_11') }}</span>
                        <h5 class="_tit">경기티켓, 팀 악세서리 구매</h5>
                        <p class="_desc">티켓구매 블록체인 시스템으로 입장권 위조와 복제를 방지하며<br>팀 유니폼 및 악세서리 등 50%할인 구매 페이지…</p>
                    </li>
                    <li class="ex-roadmap-desc-list">
                        <span class="_month">{{ __('support.month_01') }}</span>
                        <h5 class="_tit">실시간 스트리밍</h5>
                        <p class="_desc">방송사와의 제휴를 통해 경기 영상을 보며<br>거래할 수 있도록 시스템 구축</p>
                    </li>
                    <li class="ex-roadmap-desc-list">
                        <span class="_month">{{ __('support.month_03') }}</span>
                        <h5 class="_tit">Major League Baseball Fan club coin 상장</h5>
                        <p class="_desc">야구 팬클럽코인 상장으로 <br>스포츠 팬클럽 암호화폐 거래 플랫폼 저변 확대</p>
                    </li>
                    <li class="ex-roadmap-desc-list">
                        <span class="_month">{{ __('support.month_03') }}</span>
                        <h5 class="_tit">소셜 트레이딩 시작</h5>
                        <p class="_desc">파트너 따라 하기 자동 매매 시스템으로<br>Master Partner 기능을 사용하여 혁신적인 매매 플랫폼 구축</p>
                    </li>
                </ul>
            </div>

        </div>

        <div class="last_section">
            <img src="/images/logos/header_logo_blue-2.svg" alt="header_logo_blue">
            <span class="_big_tit">{{ __('support.customer_service_center') }}</span>
            <b class="_number">1588-5808</b>
            <span class="_ment">{{ __('support.inquiry_for_listed') }} : cs@spowide.com</span>
        </div>

        <div id="com_intro_floating_nav" class="com_intro_floating">
            <ul>
                <li class="active">{{ __('support.vision') }}</li>
                <li>{{ __('support.guideline') }}</li>
                <li>{{ __('support.roadmap') }}</li>
            </ul>
        </div>

    </div>

</div>

<script>

    $('#com_intro_floating_nav ul li').each(function(index){

        $(this).attr('data-index', index);

    }).click(function(){

        var i = $(this).attr('data-index');
        var this_contents = $('.section_con[data-index='+i+']').offset().top;

        $('html').animate({
            scrollTop: this_contents
        })

    })

    $(window).scroll(function(){

        var this_contents = [ $('.section_con[data-index=0]').offset().top, $('.section_con[data-index=1]').offset().top, $('.section_con[data-index=2]').offset().top]
        var nav_offset_top = $('.intro_section').offset().top;
        var scrlTop = $(this).scrollTop();

        if( scrlTop > nav_offset_top-80 ){

            $('#com_intro_floating_nav').addClass('fixed');

        }else if ( scrlTop < nav_offset_top-80 ){

            $('#com_intro_floating_nav').removeClass('fixed');

        }

        if( scrlTop < this_contents[1] ){

            $('#com_intro_floating_nav ul li').removeClass('active')
            $('#com_intro_floating_nav ul li[data-index='+0+']').addClass('active');

        }else if ( scrlTop > this_contents[1] && scrlTop < this_contents[2] ){

            $('#com_intro_floating_nav ul li').removeClass('active')
            $('#com_intro_floating_nav ul li[data-index='+1+']').addClass('active');

        }else if ( scrlTop > this_contents[2] ){

            $('#com_intro_floating_nav ul li').removeClass('active')
            $('#com_intro_floating_nav ul li[data-index='+2+']').addClass('active');

        }

    })

    
    $('.ex-roadmap-list').each(function(index){

        $(this).attr('data-index',index);

    }).click(function(){

        $(this).addClass('active');
        $('.ex-roadmap-list').not(this).removeClass('active');

        var i = $(this).attr('data-index');

        $('.ex-roadmap-desc-list[data-index='+i+']').fadeIn(700);
        $('.ex-roadmap-desc-list[data-index!='+i+']').hide();

    if ( i < 4 ){

        $('.map_year:first-child').addClass('active');
        $('.map_year:last-child').removeClass('active');

    }else{
        
        $('.map_year:first-child').removeClass('active');
        $('.map_year:last-child').addClass('active');

    }

    });

    $('.ex-roadmap-desc-list').each(function(index){

        $(this).attr('data-index',index);

    });

    $('.this_year').click(function(){
        $('.ex-roadmap-list').eq(0).click(); 
    });

    $('.next_year').click(function(){
        $('.ex-roadmap-list').eq(4).click(); 
    });

</script>

@endsection