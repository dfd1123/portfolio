@extends('company_ver.layouts.app') 
@section('content')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<div class="estimate_request_wrap">
    @include('company_ver.company_regist.include.step_bar')
    <input type="hidden" name="average" id="average" readonly>
    <div style="width:100%;height:100%;padding:20px;">
      <div class="range_box first">
          <h3>이동가능거리</h3>
          <span id="average_bind">0~5km</span>
          <div id="slider_average_bind_range"></div>
          <ul>
              @for($i=0;$i<6;$i++)
              <li></li>
              @endfor
          </ul>
      </div>
    </div>
</div>

@include('company_ver.ft_button.ft_button')

<script>
$(function() {
  $('.ft_button button').addClass('active');
  $( "#slider_average_bind_range" ).slider({
      range: "min",
      value: 5,
      min: 5,
      max: 100,
      step: 15,
      slide: function( event, ui ) {
          if(ui.value >= 95){
              $( "#average_bind" ).text( "전국" );
          }else{
              $( "#average_bind" ).text( "0~"+ui.value + "km" );
          }

          $( "#average" ).val( ui.value );
      }
  });

  $( "#average" ).val( $( "#slider_average_bind_range" ).slider( "value" ) );
  $('.ft_button button').on('click', function(){
      if($(this).hasClass('active')){
        var user_no = findGetParameter('user_no');
        var urltype = findGetParameter('type');
        var agent_distance;
        if($("#slider_average_bind_range").slider("value") >=95){
          agent_distance = '전국';
        }
        else{
          agent_distance = String($("#slider_average_bind_range").slider("value"));
        }
        var param = {
          'step' : 3
          ,'agent_no' : user_no
          ,'agent_distance' : agent_distance
        }
          $.ajax({
            type : "POST",
            data : param,
            dataType: "json",
            url : "/api/agentinfo",
            success : function(data) {
              if(data.query!=null && data.state==1){
                if(urltype==1){
                  location.href="/company_ver/company_myagent";
                }else{
                  location.href="/company_ver/company_regist/4?user_no="+data.query[0].agent_no+"";
                }
                  
              }
              else{
                  swal({
                      title: "오류",
                      text: "죄송합니다.<br/>시스템 오류로 인해 업종이 저장되지 않았습니다.<br/>다시 시도해주세요.",
                      button: "확인",
                  });
              }
            },
            error : function(data){
              swal({
                  title: "오류",
                  text: "죄송합니다.<br/>시스템 오류로 인해 업종이 저장되지 않았습니다.<br/>다시 시도해주세요.",
                  button: "확인",
              });
            }
        });
      }else{
          swal({
              title: "알림",
              text: "시공 예정 장소를 입력하여 주세요.",
              button: "확인",
          });
      }
  });
});

</script>
<style>
#content{
  background-color:#fff;
}
.seekbar {
  margin: 12px 0;
  padding: 45px 13px 10px;
  position: relative;
}

.seekbar-progress {
  height: 5px;
  background-color: #bbbbbb;
  background-image: linear-gradient(to bottom, #ebebeb 0%, #f5f5f5 100%);
  background-repeat: repeat-x;
  border-radius: 6px;
}

.seekbar-progress [role="progressbar"] {
  height: 100%;
  position: relative;
  background: #17334a;
  border-radius: 6px;
  box-shadow: inset 0 -1px 0 rgba(0,0,0,.15);
}

.seekbar-progress [role="progressbar"]:after {
  content: " ";
  display: block;
  width: 40px;
  height: 40px;
  position: absolute;
  top: -15px;
  right: -15px;
  background: url('{{asset('/images/seekbar_thumb.svg')}}');
  background-size:cover;
}

.seekbar input[type="range"] {
  -webkit-appearance: none;
  width: 100%;
  height: 100%;
  margin: 0;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 2;
  background: transparent;
  outline: 0;
  border: 0;
}

.seekbar input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  display: block;
  width: 48px;
  height: 48px;
  background-color: transparent;
}

/*
 Mozilla:
 https://developer.mozilla.org/en-US/docs/User:Jonathan_Watt/range
*/
.seekbar input[type="range"]::-moz-range-thumb {
  display: block;
  width: 48px;
  height: 48px;
  background: transparent;
  border: 0;
}

.seekbar input[type="range"]::-moz-range-track {
  background: transparent;
  border: 0;
}

.seekbar input[type="range"]::-moz-focus-outer {
  border: 0;
}

</style>
@endsection