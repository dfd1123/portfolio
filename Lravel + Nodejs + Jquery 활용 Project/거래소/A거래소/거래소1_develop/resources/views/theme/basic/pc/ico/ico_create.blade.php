@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap p2p_ico_wrap ico_create_wrap">

    <div class="board_st_inner">

        <div class="board_st_con ico_write_con">

            <div class="left_con">
                
    @include(session('theme').'.pc.ico.include.sub_menu')

            </div>
            <div class="right_con">
                <form method="post" action="{{route('ico_insert')}}" enctype="multipart/form-data" name="allform" id="create_form">

                    @csrf {{-- 페이지 제목 --}}
                    <section class="hd_subject">
                        <h1>{{ __('icoo.ico_submit')}}</h1>
                        <span>{{ __('icoo.manager_submit')}}</span>
                    </section>
                    {{-- //페이지 제목 --}} 
                    
                    {{-- 게시글 정보 --}}
                    <section class="info_form_group _notice">

                        <h5>{{ __('icoo.post_info')}}</h5>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.title')}}</label>
                            <input type="text" id="tp_tit" name="title" class="form-control" placeholder="{{ __('icoo.m_input_title')}}" >
                            <span class="type_num_checker"><b id="pre_char">0</b>&nbsp;/&nbsp;<b id="max_char">0</b> Byte</span>
                        </p>

                        <div class="form_align">
                            <label class="tit under_txt">{{ __('icoo.s_image')}}</label>
                            <div class="w_100">
                                <input type="file" name="thumnail" placeholder="{{ __('icoo.filename')}}" id="thum_file" class="hide img_up" >
                                <input type="text" name="file_name" value="" class="form-control w_half mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}" readonly>
                                <label class="form-control attach_btn float-left" for="thum_file">{{ __('icoo.aff_file')}}</label>
                            </div>
                        </div>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.ico_int')}}</label>
                            <input type="text" id="ico_intro" name="intro" class="form-control" placeholder="{{ __('icoo.m_simple')}}">
                            <span class="type_num_checker" ><b id="ico_pre">0</b>&nbsp;/&nbsp;<b id="ico_max">0</b> Byte</span>
                        </p>

                    </section>
                    {{-- //게시글 정보 --}} 
                    
                    {{-- 기본 정보 --}}
                    <section class="info_form_group">
                        <h5>{{ __('icoo.basic_info')}}</h5>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.icocoinname')}}</label>
                            <input type="text" name="coin_name" class="form-control" placeholder="{{ __('icoo.input_coinname')}}" >
                        </p>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.ico_symbol')}}</label>
                            <div class="w_100">
                                <input type="text" id="coin_symbol" name="symbol" class="form-control bd-blue w_half" placeholder="{{ __('icoo.input_sym')}} (ex: BTC, ETH)" >
                            </div>
                        </div>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.available_coin')}}</label>
                            <div class="w_100">
                                <select id="collect_coin"name="collect" class="form-control bd-red w_half" >
                                    <option value="" selected>{{ __('icoo.select_coin')}}</option>
                                    @forelse($icos as $ico)
                                    <option value="{{$ico->symbol}}" >{{$ico->symbol}}</option>
                                    @empty
                                    
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form_align _buyprice">
                            <label class="tit">{{ __('icoo.buy_price')}}</label>
                            <div class="w_100">

                                <div class="grouper">
                                    <input type="number" class="form-control w_50px mr-2 text-center" readonly value="1">
                                    <input type="text" class="form-control symbol_form bd-blue blue text-center this_symbol" readonly value="SYMBOL">
                                </div>

                                <span class="pl-2 pr-2 float-left">≈</span>

                                <div class="grouper">
                                    <input type="number" id="ico_coin_p" name="ico_coin_p" class="form-control w_180px mr-2" placeholder="{{ __('icoo.inputprice')}}"  step="any">
                                    <input type="text" class="form-control symbol_form bd-red red text-center collect_symbol" readonly value="SYMBOL">
                                </div>

                                <div class="grouper point_clr_1 ml-2">
                                    1 <span class="currency this_symbol">SYMBOL</span> &nbsp; ≈ &nbsp;<span id="c_price"> 0.00 </span><span class="currency collect_symbol">SYMBOL</span>
                                </div>

                            </div>
                        </div>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.minimum_buy_price')}}</label>
                            <div class="w_100">
                                <input name="ico_min" type="number" class="form-control w_180px mr-2 float-left" placeholder="{{ __('icoo.inputmoney')}}"   step="any"/>
                                <input type="text" class="form-control symbol_form bd-red red text-center float-left collect_symbol" readonly value="SYMBOL">
                            </div>
                        </div>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.goal')}}</label>
                            <div class="w_100">
                                <input name="ico_goal" type="number" class="form-control w_180px mr-2 float-left" placeholder="{{ __('icoo.inputmoney')}}"  step="any"/>
                                <input type="text" class="form-control symbol_form bd-red red text-center float-left collect_symbol" readonly value="SYMBOL">
                            </div>
                        </div>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.starttime')}}</label>
                            <div class="w_100">
                                <input type="text" name="ico_from" id="start_sch_ico" class="form-control w_180px float-left mr-2" placeholder="{{ __('icoo.select_date')}}" >
                                <input type="text" name="ico_from_h" class="form-control w_180px start_timepicker" >
                            </div>
                        </div>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.endtime')}}</label>
                            <div class="w_100">
                                <input type="text" name="ico_to" id="end_sch_ico" class="form-control w_180px float-left mr-2" placeholder="{{ __('icoo.select_date')}}" >
                                <input type="text" name="ico_to_h" class="form-control w_180px end_timepicker" >
                            </div>
                        </div>

                    </section>
                    {{-- //기본 정보 --}} 
                    
                    {{-- 혜택/관련기사 및 인터뷰 --}}
                    <section class="info_form_group _interview">
                        <h5>{{ __('icoo.benefit_interview')}}</h5>

                        <p class="form_align benefit_p">
                            <label class="tit">{{ __('icoo.benefits')}}</label>
                            <input type="text" name="benefit[]" id="benefit_1" class="form-control" placeholder="{{ __('icoo.input_bene_01')}}">
                        </p>

                        <p class="form_align benefit_p">
                            <label class="tit"></label>
                            <input type="text" name="benefit[]" id="benefit_2" class="form-control" placeholder="{{ __('icoo.input_bene_02')}}">
                        </p>

                        <p class="form_align benefit_p">
                            <label class="tit"></label>
                            <input type="text" name="benefit[]" id="benefit_3" class="form-control" placeholder="{{ __('icoo.input_bene_03')}}">
                        </p>
                        
                        <div class="form_align">
                            <label class="tit"></label>
                            <div class="w_100 plus_tr_btn" id="bnf_plus_btn">
                            {{ __('icoo.addbene')}}
                            </div>
                        </div>


                        <div class="form_align_group _news">
                            <label class="tit under_txt pdf">{!! __('icoo.article_interview') !!}</label>

                            <table>
                                <tbody>
                                    <tr class="subject_form">
                                        <th>
                                        {{ __('icoo.inter_01')}}
                                        </th>
                                        <td>
                                            <input type="text" name="news_name[]" id="subject_1" class="form-control" placeholder="{{ __('icoo.inter_title')}}">
                                        </td>
                                    </tr>
                                    <tr class="url_form" name="newsline">
                                        <th>

                                        </th>
                                        <td>
                                            <input type="text" name="news_url[]" id="url_1" class="form-control" placeholder="URL {{ __('icoo.aff')}}">
                                        </td>
                                    </tr>
                                    <tr class="subject_form">
                                        <th>
                                        {{ __('icoo.inter_02')}}
                                        </th>
                                        <td>
                                            <input type="text" name="news_name[]" id="subject_2" class="form-control" placeholder="{{ __('icoo.inter_title')}}">
                                        </td>
                                    </tr>
                                    <tr class="url_form" name="newsline">
                                        <th>

                                        </th>
                                        <td>
                                            <input type="text" name="news_url[]" id="url_2" class="form-control" placeholder="URL {{ __('icoo.aff')}}">
                                        </td>
                                    </tr>
                                    <tr class="subject_form">
                                        <th>
                                        {{ __('icoo.inter_03')}}
                                        </th>
                                        <td>
                                            <input type="text" name="news_name[]" id="subject_3" class="form-control" placeholder="{{ __('icoo.inter_title')}}">
                                        </td>
                                    </tr>
                                    <tr class="url_form" name="newsline">
                                        <th>

                                        </th>
                                        <td>
                                            <input type="text" name="news_url[]" id="url_3" class="form-control" placeholder="URL {{ __('icoo.aff')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="plus_tr_btn" id="news_plus_btn">
                                        {{ __('icoo.add_inter')}}
                                        </th>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        
                    </section>
                    {{-- //혜택/관련기사 및 인터뷰 --}} 
                    
                    {{-- 세부정보 --}}
                    <section class="info_form_group _detail">
                        <h5>{{ __('icoo.details')}}</h5>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.field_of_use')}}</label>
                            <input type="text" name="ico_use" class="form-control" placeholder="{{ __('icoo.m_input_use')}}" />
                        </p>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.technology_base')}}</label>
                            <input type="text" name="ico_tech" class="form-control" placeholder="{{ __('icoo.m_input_tech')}}" />
                        </p>

                        <div class="form_align bb-f0f0">
                            <label class="tit">{{ __('icoo.homepage')}}</label>
                            <input type="text" name="ico_url" class="form-control mb-3" placeholder="{{ __('icoo.add_url')}}" />
                        </div>

                        <div class="form_align_group mt-3 bb-f0f0">
                            <label class="tit under_txt pdf">{{ __('icoo.white_paper')}}</label>

                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                        {{ __('icoo.jp')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_jp" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="white_pp_jp">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.en')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_eng" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left" for="white_pp_eng">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        {{ __('icoo.ch')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_cn" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left mb-2 filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="white_pp_cn">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.kr')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_kr" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left" for="white_pp_kr">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <div class="form_align_group bb-f0f0 mt-3">
                            <label class="tit under_txt pdf">{{ __('icoo.analtsis_report')}}</label>

                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                        {{ __('icoo.jp')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_jp" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="essay_rpt_jp">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.en')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_eng" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left" for="essay_rpt_eng">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        {{ __('icoo.ch')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_cn" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left mb-2 filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="essay_rpt_cn">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.kr')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_kr" class="hide pdf_up only_pdf">
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left" for="essay_rpt_kr">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <div class="form_align_group mt-3">
                            <label class="tit">{{ __('icoo.formula_sns')}}</label>


                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                            Facebook
                                        </th>
                                        <td>
                                            <input type="text" name="sns_url[]" value="" class="form-control mr-2 float-left" placeholder="Facebook {{ __('icoo.adrs')}}">
                                        </td>
                                        <th>
                                            Twitter
                                        </th>
                                        <td>
                                            <input type="text" name="sns_url[]" value="" class="form-control mr-2 float-left" placeholder="Twitter {{ __('icoo.adrs')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Blog
                                        </th>
                                        <td>
                                            <input type="text" name="sns_url[]" value="" class="form-control mr-2 float-left" placeholder="Blog {{ __('icoo.adrs')}}">
                                        </td>
                                        <th>
                                            Youtube
                                        </th>
                                        <td>
                                            <input type="text" name="sns_url[]" value="" class="form-control mr-2 float-left" placeholder="Youtube {{ __('icoo.adrs')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            etc
                                        </th>
                                        <td>
                                            <input type="text" name="sns_url[]" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.etc')}} SNS {{ __('icoo.adrs')}}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </section>
                    {{-- //세부정보 --}} 
                    
                    {{-- 상세페이지 --}}
                    <section class="info_form_group _detail_page">
                        <h5>{{ __('icoo.detail_ico')}}</h5>

                        <div class="form_align_group">
                            <label class="tit under_txt">{{ __('icoo.detail_page')}}</label>

                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                        {{ __('icoo.image_01')}}
                                        </th>
                                        <td>
                                            <input type="file" name="cont[]" placeholder="{{ __('icoo.filename')}}" id="detail_page1" class="hide img_up" >
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="detail_page1">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.image_02')}}
                                        </th>
                                        <td>
                                            <input type="file" name="cont[]" placeholder="{{ __('icoo.filename')}}" id="detail_page2" class="hide img_up" >
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left" for="detail_page2">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        {{ __('icoo.image_03')}}
                                        </th>
                                        <td>
                                            <input type="file" name="cont[]" placeholder="{{ __('icoo.filename')}}" id="detail_page3" class="hide img_up" >
                                            <input type="text" value="" class="form-control mr-2 float-left filename_input" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="detail_page3">{{ __('icoo.aff_file')}}</label>
                                        </td>
                                        <th>

                                        </th>
                                        <td>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </section>
                    {{-- //상세페이지 --}}
                    
                    {{-- 등록하기 --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn_style">{{ __('icoo.submit')}}</button>
                    </div>
                    {{-- //등록하기 --}}

                </form>
            </div>
        </div>

    </div>

</div>

<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.icoo = {
        @foreach(__('icoo') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }
</script>
    
@endsection