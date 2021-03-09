@extends('company_ver.layouts.app') 
@section('content')
<div class="main_div">
    <div class="top_div">
        <div class="title">
            <div class="left_title">
                <p>상세견적 및 도면 업로드<em>(2개)</em></p>
                <p> xls, xlsx, pdf, jpg 가능</p>
            </div>
            <div class="right_title">
                <button class="btn">파일 첨부</button>
            </div>
        </div>
        <div class="info">
            <div class="info_left_div">
                <img src="{{asset('/images/perzuman_round_02.svg')}}"/>
            </div>
            <div class="info_right_div">
                <p>잠깐! 상세견적 및 도면파일을 업로드 해주셔야 <em>최종입찰</em>이 <em>완료</em>됩니다.</p>
            </div>
        </div>
    </div>
    <div class="middle_div">
        <div class="input_div">
            <div id="upload_div1">
                <span id="txt1">파일을 첨부해 주세요</span><img src="{{asset('/images/icon_file_upload.svg')}}"/>
                <input type="file" name="img1" id="img1" style="display:none;" onchange="ChangeText(this,'txt1');"/>
            </div>
            <div id="upload_div2">
                <span id="txt2">파일을 첨부해 주세요</span><img src="{{asset('/images/icon_file_upload.svg')}}"/>
                <input type="file" name="img2" id="img2" style="display:none;" onchange="ChangeText(this,'txt2');"/>
            </div>
        </div>
    </div>
    <div class="bottom_div">
        <button class="btn">최종입찰 완료</button>
    </div>
</div>
@include('company_ver.layouts.footer')
<style>
#content{
    padding:3.3em 0;
}
</style>
<script>
    $('#content').css('height',$(window).height());
    $('#upload_div1').on('click',function(){
        document.getElementById("img1").click();
    });
    $('#upload_div2').on('click',function(){
        document.getElementById("img2").click();
    });
    function ChangeText(oFileInput, sTargetID) {
        var fullPath = $(oFileInput).val()
        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
        var filename = fullPath.substring(startIndex);
        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
            filename = filename.substring(1);
        }
        if(filename!=''){
            $('#'+sTargetID).html(filename);
        }
        else{
            $('#'+sTargetID).html('파일을 첨부해 주세요');
        }
        

        if($('#img1').val() != '' && $('#img2').val() != ''){
            $('.bottom_div .btn').addClass('active');
        }
        else{
            $('.bottom_div .btn').removeClass('active');
        }
    }
    $('.bottom_div .btn').on('click', function(){
        if($(this).hasClass('active')){
            swal({
                title: "재확인",
                text: "입찰을 완료하시겠습니까?",
                buttons: {
                    yes: {
                        text: "예",
                        value: true,
                    },
                    no: {
                        text: "아니오",
                        value: false,
                    },
                },
            })
            .then((value) => {
                if(value){
                    var trd_no = findGetParameter('trd_no');
                    var param={
                        'trd_no' : trd_no,
                        'user_no' : {{auth()->user()->user_no}}
                    }
                    $.ajax({
                        type : "PUT",
                        dataType: "json",
                        data : param,
                        url : "/api/trade/trdbidding",
                        success : function(data) {
                            console.log(data);
                            if(data.state==1 && data.query!=null){
                                swal({
                                    title: "완료",
                                    text: "최종입찰을 완료하여 거래가 시작됩니다",
                                    buttons: {
                                        yes: {
                                            text: "확인",
                                            value: true,
                                        },
                                    },
                                })
                                .then((value) => {
                                    if(value){
                                        // Ajax 성공하면..
                                        location.href="/company_ver";
                                    }
                                });
                            }
                            else{
                                alert(data.msg);
                            }
                        },
                        error : function(data){
                        }
                    });
                }
            });
        }else{
            swal({
                title: "알림",
                text: "파일을 첨부해 주세요",
                button: "확인",
            });
        }
    });
</script>
@endsection