@extends(session('theme').'.mobile.layouts.app') 
@section('content')

@include(session('theme').'.mobile.notice.include.sub_menu')

<div class="m_cs_wrap m_cs_wrap-view">

    <div class="cs_table_view ios-scroll mobile_guide_wrap">
    
        <div class="et_cetera">
          <ul>
            <li><b>회사명</b>{{$setting->company}}</li>
            <li><b>대표이사</b>{{$setting->ceo}}</li>
            <li><b>사업자등록번호</b>{{$setting->business_num}}</li>
            <li><b>주소지</b>{{$setting->address}}</li>
            <li><b>상장 및 제휴문의</b>{{$setting->infoemail}}</li>
            <li><b>고객센터 연락처</b>{{$setting->phone_num}}</li>
          </ul>
        </div>

    </div>

</div>

<style>
  .m_cs_wrap-view .cs_table_view{
    background: #fafafa;
  }

  .et_cetera{
    padding: 30px 0;
  }

  .et_cetera ul{
    padding: 22px 20px;
    background: #fff;
    border-radius: 10px;
  }

  .et_cetera ul li{
    padding-bottom: 10px;
    padding-top: 10px;
    letter-spacing: -1px;
    border-bottom: 1px solid #ddd;
  }

  .et_cetera ul li b{
    display: block;
    margin-bottom: 5px;
  }
</style>

@endsection