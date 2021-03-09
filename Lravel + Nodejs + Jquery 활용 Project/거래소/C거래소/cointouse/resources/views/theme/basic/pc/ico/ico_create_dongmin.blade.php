@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap p2p_ico_wrap ico_create_wrap">

    <div class="board_st_inner">

        <div class="board_st_con ico_write_con">

            <div class="left_con">
    @include(session('theme').'.pc.ico.include.sub_menu')

            </div>
            <div class="right_con">
                <form method="post" action="{{route('ico_insert')}}" enctype="multipart/form-data" name="allform">

                    @csrf 
                    {{-- 페이지 제목 --}}
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
                            <input type="text" name="title" class="form-control" placeholder="{{ __('icoo.input_title')}}">
                            <span class="type_num_checker"><b>0</b>&nbsp;/&nbsp;<b>n</b>{{ __('icoo.ja')}}</span>
                        </p>

                        <div class="form_align">
                            <label class="tit under_txt">{{ __('icoo.s_image')}}</label>
                            <div class="w_100">
                                <input type="file" name="thumnail" placeholder="{{ __('icoo.filename')}}" id="thum_file" class="hide">
                                <input type="text" value="" class="form-control w_180px mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                <label class="form-control attach_btn float-left" for="thum_file">{{ __('icoo.filename')}}</label>
                            </div>
                        </div>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.filename')}}</label>
                            <input type="text" name="intro" class="form-control" placeholder="{{ __('icoo.simple')}}">
                            <span class="type_num_checker"><b>0</b>&nbsp;/&nbsp;<b>n</b>{{ __('icoo.ja')}}</span>
                        </p>

                    </section>
                    {{-- //게시글 정보 --}}

                    {{-- 기본 정보 --}}
                    <section class="info_form_group">
                        <h5>{{ __('icoo.ico_int')}}</h5>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.icocoinname')}}</label>
                            <input type="text" name="coin_name" class="form-control" placeholder="{{ __('icoo.input_coinname')}}">
                        </p>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.ico_symbol')}}</label>
                            <div class="w_100">
                                <input type="text" name="symbol" class="form-control bd-blue w_half" placeholder="{{ __('icoo.input_sym')}} (ex: BTC, ETH)">
                            </div>
                        </div>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.available_coin')}}</label>
                            <div class="w_100">
                                <select name="collect" class="form-control bd-red w_half">
                                    <option value="collect" selected>{{ __('icoo.select_coin')}}</option>
                                    <option value="collect" ></option>
                                    <option value="collect" ></option>
                                </select>
                            </div>
                        </div>

                        <div class="form_align _buyprice">
                            <label class="tit">{{ __('icoo.buy_price')}}</label>
                            <div class="w_100">
                                
                                <div class="grouper">
                                    <input type="number" class="form-control w_50px mr-2" readonly>
                                    <input type="text" class="form-control symbol_form bd-blue blue text-center" readonly value="SYMBOL">
                                </div>
                                
                                <span class="pl-2 pr-2 float-left">≈</span>
                                
                                <div class="grouper">
                                    <input type="number" name="ico_coin_p" class="form-control w_180px mr-2" placeholder="{{ __('icoo.inputprice')}}"/>
                                    <input type="text" class="form-control symbol_form bd-red red text-center" readonly value="SYMBOL">
                                </div>
                                
                                <div class="grouper point_clr_1 ml-2">
                                    1 <span class="currency">DIVI</span> &nbsp; ≈ &nbsp; 0.05 <span class="currency">BTC</span>
                                </div>
                                    
                            </div>
                        </div>

                        <div class="form_align">
                            <label class="tit">{{ __('icoo.minimum_buy_price')}}</label>
                            <div class="w_100">
                                <input name="ico_min" type="number" class="form-control w_180px mr-2 float-left" placeholder="{{ __('icoo.inputmoney')}}"/>
                                <input type="text" class="form-control symbol_form bd-red red text-center float-left" readonly value="SYMBOL">
                            </div>
                        </div>
                        
                        <div class="form_align">
                            <label class="tit">{{ __('icoo.starttime')}}</label>
                            <div class="w_100">
                                <input type="text" name="ico_from" class="form-control w_180px float-left mr-2" placeholder="{{ __('icoo.select_date')}}">
                                <select class="form-control w_180px" >
                                    <option>{{ __('icoo.am')}}</option>
                                    <option>{{ __('icoo.pm')}}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form_align">
                                <label class="tit">{{ __('icoo.endtime')}}</label>
                                <div class="w_100">
                                    <input type="text" name="ico_to" class="form-control w_180px float-left mr-2" placeholder="{{ __('icoo.select_date')}}">
                                    <select class="form-control w_180px" >
                                        <option>{{ __('icoo.am')}}</option>
                                        <option>{{ __('icoo.pm')}}</option>
                                    </select>
                                </div>
                            </div>

                    </section>
                    {{-- //기본 정보 --}}

                    {{-- 혜택/관련기사 및 인터뷰 --}}
                    <section class="info_form_group _interview">
                        <h5>{{ __('icoo.benefit_interview')}}</h5>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.benefits')}}</label>
                            <input type="text" name="benefit[]" class="form-control" placeholder="{{ __('icoo.input_bene_01')}}">
                        </p>
                        
                        <p class="form_align">
                            <label class="tit"></label>
                            <input type="text" name="benefit[]" class="form-control" placeholder="{{ __('icoo.input_bene_02')}}">
                        </p>
                            
                        <p class="form_align">
                            <label class="tit"></label>
                            <input type="text" name="benefit[]" class="form-control" placeholder="{{ __('icoo.input_bene_03')}}">
                        </p>
                            
                            
                        <p class="form_align">
                            <label class="tit under_txt">{{ __('icoo.article_interview')}}</label>
                            <input type="text" name="news[]" class="form-control" placeholder="URL {{ __('icoo.aff')}}">
                        </p>
                        
                        <p class="form_align">
                            <label class="tit"></label>
                            <input type="text" name="news[]" class="form-control" placeholder="URL {{ __('icoo.aff')}}">
                        </p>
                        <p class="form_align">
                            <label class="tit"></label>
                            <input type="text" name="news[]" class="form-control" placeholder="URL {{ __('icoo.aff')}}">
                        </p>

                    </section>
                    {{-- //혜택/관련기사 및 인터뷰 --}}

                    {{-- 세부정보 --}}
                    <section class="info_form_group _detail">
                        <h5>{{ __('icoo.details')}}</h5>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.field_of_use')}}</label>
                            <input type="text" name="ico_use" class="form-control" placeholder="{{ __('icoo.m_input_use)}}" />
                        </p>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.technology_base')}}</label>
                            <input type="text" name="ico_tech" class="form-control" placeholder="{{ __('icoo.m_input_tech)}}" />
                        </p>

                        <p class="form_align">
                            <label class="tit">{{ __('icoo.homepage')}}</label>
                            <input type="text" name="ico_url" class="form-control" placeholder="{{ __('icoo.add_url)}}" />
                        </p>

                        <div class="form_align_group">
                            <label class="tit under_txt pdf">{{ __('icoo.white_paper')}}</label>

                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                        {{ __('icoo.jp')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_jp" class="hide">
                                            <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="white_pp_jp">{{ __('icoo.filename')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.en')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_eng" class="hide">
                                            <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left" for="white_pp_eng">{{ __('icoo.filename')}}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        {{ __('icoo.ch')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_cn" class="hide">
                                            <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="white_pp_cn">{{ __('icoo.filename')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.kr)}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_whitepater[]" placeholder="{{ __('icoo.filename')}}" id="white_pp_kr" class="hide">
                                            <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left" for="white_pp_kr">{{ __('icoo.filename')}}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <div class="form_align_group">
                            <label class="tit under_txt pdf">{{ __('icoo.analtsis_report')}}</label>

                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                        {{ __('icoo.jp')}}
                                        </th>
                                        <td>
                                            <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_jp" class="hide">
                                            <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="essay_rpt_jp">{{ __('icoo.filename')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.en')}}
                                        </th>
                                        <td>
                                                <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_eng" class="hide">
                                                <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                                <label class="form-control attach_btn float-left" for="essay_rpt_eng">{{ __('icoo.filename')}}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        {{ __('icoo.ch')}}
                                        </th>
                                        <td>
                                                <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_cn" class="hide">
                                                <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                                <label class="form-control attach_btn float-left mr-3" for="essay_rpt_cn">{{ __('icoo.filename')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.kr)}}
                                        </th>
                                        <td>
                                                <input type="file" name="ico_report[]" placeholder="{{ __('icoo.filename')}}" id="essay_rpt_kr" class="hide">
                                                <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                                <label class="form-control attach_btn float-left" for="essay_rpt_kr">{{ __('icoo.filename')}}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        
                        <div class="form_align _officalsns">
                                <label class="tit">{{ __('icoo.formula_sns')}}</label>
                                    <select class="form-control w_180px mr-2">
                                        <option>SNS{{ __('icoo.select')}}</option>
                                        <option value="instagram">{{ __('icoo.insta')}}</option>
                                        <option value="facebook">{{ __('icoo.fb')}}</option>
                                        <option value="blog">{{ __('icoo.blog')}}</option>
                                    </select>
                                    <input type="text" name="sns_url[]" class="form-control" placeholder="{{ __('icoo.addurl')}}">
                            </div>
                            <div class="form_align _officalsns">
                                    <label class="tit"></label>
                                        <select class="form-control w_180px mr-2">
                                        <option>SNS{{ __('icoo.select')}}</option>
                                        <option value="instagram">{{ __('icoo.insta')}}</option>
                                        <option value="facebook">{{ __('icoo.fb')}}</option>
                                        <option value="blog">{{ __('icoo.blog')}}</option>
                                        </select>
                                        <input type="text" name="sns_url[]" class="form-control" placeholder="{{ __('icoo.addurl')}}">
                                </div>

                                <div class="form_align _officalsns">
                                        <label class="tit"></label>
                                            <select class="form-control w_180px mr-2">
                                            <option>SNS{{ __('icoo.select')}}</option>
                                        <option value="instagram">{{ __('icoo.insta')}}</option>
                                        <option value="facebook">{{ __('icoo.fb')}}</option>
                                        <option value="blog">{{ __('icoo.blog')}}</option>
                                            </select>
                                            <input type="text" name="sns_url[]" class="form-control" placeholder="{{ __('icoo.addurl')}}">
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
                                            <input type="file" name="cont[]" placeholder="{{ __('icoo.filename')}}" id="detail_page1" class="hide img_up" data-index="1">
                                            <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                            <label class="form-control attach_btn float-left mr-3" for="detail_page1">{{ __('icoo.filename')}}</label>
                                        </td>
                                        <th>
                                        {{ __('icoo.image_02')}}
                                        </th>
                                        <td>
                                                <input type="file" name="cont[]" placeholder="{{ __('icoo.filename')}}" id="detail_page2" class="hide img_up" data-index="2">
                                                <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                                <label class="form-control attach_btn float-left" for="detail_page2">{{ __('icoo.filename')}}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                        {{ __('icoo.image_03')}}
                                        </th>
                                        <td>
                                                <input type="file" name="cont[]" placeholder="{{ __('icoo.filename')}}" id="detail_page3" class="hide img_up" data-index="3">
                                                <input type="text" value="" class="form-control mr-2 float-left" placeholder="{{ __('icoo.filename')}}">
                                                <label class="form-control attach_btn float-left mr-3" for="detail_page3">{{ __('icoo.filename')}}</label>
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
                    <input class="sbm_btn"   type="button" value="{{ __('icoo.jechul')}}" onclick=func()>
                </form>
            </div>
        </div>
        
    </div>

</div>
<script language="Javascript">
/* 이미지는 png와 jpg만 첨부 가능하도록*/
$('.img_up').change(function(){
    var $check = $(this);
    //alert($(this).value);
    var thumbext = this.value.slice(this.value.indexOf(".")+1).toLowerCase();
    if(thumbext != "jpg" && thumbext != "png" ){ //확장자를 확인합니다.
        alert('{{ __('icoo.only_image')}}');
        $check.val('');
        return false;
    }alert($check.val());
    
})
/* 이미지는 png와 jpg만 첨부 가능하도록*/



function func(){
    var obj = document.allform;
    
    //for($i = 1 ; $i<4 ; $i++){
    if(obj.ico_tech.val == null){
        alert('{!! __('icoo.nece')!!}');
        $('#detail_page1').focus();
        return false;
    }
    //}
    if(obj.ico_url.val == null){
        alert('{{ __('icoo.hp_url')}}');
        obj.ico_url.focus();
        return false;
    }
    $("input[name='allform']").submit();
}
</script>
@endsection