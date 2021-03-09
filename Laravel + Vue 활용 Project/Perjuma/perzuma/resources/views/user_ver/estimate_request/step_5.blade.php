@extends('user_ver.layouts.app') 
@section('content')

    <div class="estimate_request_wrap">

        @include('user_ver.estimate_request.include.step_bar')
        <input type="hidden" name="trd_no" id="trd_no" value="{{$trd_no}}" />
        
        <div class="add_img_wrap">
            <div class="top_estimate_box">
                <p class="add_img_p">시공 예정 장소에 대해 참고될만한 이미지 첨부!</p>
                <div class="add_img_li">
                    <div class="box">
                        <div class="content">
                            <div id="first_img_addbtn" class="img_wrap">
                                <label for="construction_img1" data-index="1">
                                    <i class="fal fa-plus"></i><br />
                                    이미지 등록
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @for($i=1;$i<=5;$i++)
                    <div class="add_img_li">
                        <div class="box">
                            <div class="content">
                                <div class="img_wrap">
                                    <input type="file" name="construction_img{{$i}}" id="construction_img{{$i}}" class="hide"  data-index="{{$i}}" accept="/image/*" capture="camera" />
                                    <img src="/images/default_add_img.png" id="preview_contruct{{$i}}" class="default_img" alt="default_img">
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <div class="bottom_estimate_box">
            <h3>견적 업로드</h3>
            <p>퍼주마 이용 전 타 업체에서 문의하여 받았던 견적을 업로드하여<br>보다 빠르게 견적을 받아보실 수 있습니다.</p>
            <div class="estimate_file_wrap">
                <div class="estimate_file_top">
                    <div class="estimate_file_top_left">
                        <b>견적파일 등록<small>(2개)</small></b>
                        <span>xls, xlsx, pdf, jpg 가능</span>
                    </div>
                    <div class="estimate_file_top_right">
                        <label for="file1" id="estimate_insert_btn" data-index="1">파일첨부</label>
                    </div>
                </div>
                <div class="estimate_file_bottom">
                    <input type="file" name="file1" id="file1" multiple="" maxlength="2" class="estimate_file_inp" style="display:none;" />
                    <input type="file" name="file2" id="file2" class="estimate_file_inp" style="display:none;" />
                    <div class="file_wrap">
                        <div class="no_file">파일을 업로드 하여주세요.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <template id="none_estimate_file_li">
        <div class="no_file">파일을 업로드 하여주세요.</div>
    </template>

    <template id="estimate_file_li">
        <div class="estimate_file_li">
            <span id="estimate_file_name"></span>
            <span id="estimate_file_download"><i class="far fa-trash-alt"></i><!--<img src="{{asset('/images/download_btn.svg')}}" alt="download_btn">--></span>
        </div>
    </template>

    @include('user_ver.ft_button.ft_button')

    <style>
    #content{
        background:#f2f2f2;
    }

    .estimate_request_wrap{
        background: #fff;
    }
    </style>

    <script>
        $('.ft_button button').addClass('active');

        var trd_no = $('input[name="trd_no"]').val();

        if(trd_no != ''){
            data_load($('#step_index').data('index'));
        }

        function data_load(step){
            $.ajax({
                type : "GET",
                dataType: "json",
                data : {trd_no : trd_no, step : step},
                url : "/api/estimate_request/load",
                success : function(data) {
                    var img_index = 0;
                    if(data.trd_img != null){
                        Object.keys(data.trd_img).forEach(function(key, index, array) {
                            var real_index = parseInt(index)+1;
                            $('#preview_contruct'+real_index).removeClass('default_img');
                            $('#preview_contruct'+real_index).attr('src', '/storage/fdata/trade/estimate'+data.trd_img[key]);
                            img_index = real_index;
                        });

                        img_index += 1;
                        $('#first_img_addbtn label').data('index',img_index);
                        $('#first_img_addbtn label').attr('for', 'construction_img'+img_index);
                    }
                    var file_wrap = $('.file_wrap');
                    
                    if(data.trd_file != null){
                        Object.keys(data.trd_file).forEach(function(key, index, array) {
                            var real_index = parseInt(index)+1;

                            
                            if(real_index == 1){
                                file_wrap.html("");
                            }

                            var template = $($('#estimate_file_li').html());
                            template.find('#estimate_file_name').text(data.trd_file[key].replace("/",""));
                            file_wrap.append(template);

                            img_index = real_index;
                        });
                        
                        img_index += 1;
                        $('#estimate_insert_btn').data('index',img_index);
                        $('#estimate_insert_btn').attr('for','file'+img_index);
                    }


                },
                error : function(data){
                    swal({
                        title: "네트워크 오류",
                        text: "잠시 후 다시 시도해주세요.",
                        button: "확인",
                    });
                }
            });
        }

        function readURL(input, index) {
            var formData = new FormData();

            $.each(input.files, function(i, file) {
                //dataImg.append('file', file);
            });

            formData.append('images', $('#construction_img'+index)[0].files[0]);
            formData.append('index', index);
            formData.append('trd_no', $('input[name="trd_no"]').val());
            formData.append('step', '5');
            formData.append('req', 'upldimg');
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {

                    //Ajax로 이미지 업로드!
                    $.ajax({
                        type : "POST",
                        url : "/api/estimate_request",
                        data : formData,
                        dataType: 'json',     
                        mimeType: 'multipart/form-data',
                        contentType: 'multipart/form-data',
                        success : function(data) {
                            if(data.status == 1){
                                $('#preview_contruct'+index).attr('src', '/storage/fdata/trade/estimate'+data.file_name);
                            }else if(data.status == 2){
                                swal({
                                    title: "알림",
                                    text: "최대 이미지 업로드 갯수는 5개입니다.",
                                    button: "확인",
                                });
                            }
                        },
                        error : function(data){
                            swal({
                                title: "네트워크 오류",
                                text: "잠시 후 다시 시도해주세요.",
                                button: "확인",
                            });
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                    // 성공하면 실행
                    $('#preview_contruct'+index).removeClass('default_img');
                    $('#preview_contruct'+index).attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".img_wrap input[type='file']").change(function() {
            var index = $(this).data('index');
            var next_idx = index + 1;
            $('#first_img_addbtn label').attr("for", "construction_img" + next_idx + "");

            if(next_idx >= 2){
                $('.ft_button button').addClass('active');
            }else{
                $('.ft_button button').removeClass('active');
            }
            readURL(this, index);
        });

        $('.estimate_file_inp').on('change', function(){
            var dataFile = new FormData();
            var index = $('#estimate_insert_btn').data('index');
            dataFile.append('index', index);
            dataFile.append('files', $('#file'+index)[0].files[0]);
            dataFile.append('trd_no', $('input[name="trd_no"]').val());
            dataFile.append('req','uplddraws');
            dataFile.append('step',5);

            $.ajax({
                type : "POST",
                url : "/api/estimate_request", //"/api/estimate_file_store",
                data : dataFile,
                dataType: 'json',     
                mimeType: 'multipart/form-data',
                contentType: 'multipart/form-data',
                success : function(data) {
                    if(data.status == 1){
                        //성공적으로 업로드
                        var file_wrap = $('.file_wrap');
                        if(data.index == 1){
                            file_wrap.html("");
                        }

                        var template = $($('#estimate_file_li').html());
                        template.find('#estimate_file_name').text(data.file_name);
                        file_wrap.append(template);

                        $('#estimate_insert_btn').data('index',parseInt(index)+1);
                        $('#estimate_insert_btn').attr('for','file'+ (parseInt(index)+1));

                    }else if(data.status == 2){
                        swal({
                            title: "알림",
                            text: "최대 파일 업로드 갯수는 2개입니다.",
                            button: "확인",
                        });
                    }
                },
                error : function(data){
                    swal({
                        title: "네트워크 오류",
                        text: "잠시 후 다시 시도해주세요.",
                        button: "확인",
                    });
                },
                cache: false,
                contentType: false,
                processData: false
            });

        });

        $('.ft_button button').on('click', function(){

            if($(this).hasClass('active')){
                // 업종 id인 upstream_id를 Ajax의 data 값으로 전달하여 API 전달

                // Ajax 성공하면..
                location.href="/user_ver/estimate_request/6?id="+trd_no+"";

                // Ajax 실패하면..
                /*
                swal({
                    title: "오류",
                    text: "죄송합니다.<br>시스템 오류로 인해 업종이 저장되지 않았습니다.<br>다시 시도해주세요.",
                    button: "확인",
                });
                */
            }else{
                swal({
                    title: "알림",
                    text: "최소 1장 이상의 시공 관련 이미지를\n추가하셔야 합니다.",
                    button: "확인",
                });
            }

        });

        $(".img_wrap>img").enableLongClick(function(id, x, y){
            var target = $('#'+id);
            var inp_index = target.siblings('input').data('index');
            if(!target.hasClass('default_img')){
                var img_name = target.attr('src');
                img_name = img_name.replace('/storage/fdata/trade/estimate','');
                swal({
                    title: "이미지 삭제",
                    text: "선택하신 이미지를 삭제하시겠습니까?",
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
                        $.ajax({
                            type : "POST",
                            url : "/api/estimate_request", 
                            dataType: 'json',     
                            data : { trd_no : trd_no, img_name : img_name, req : 'deleteimg', step : 5 },
                            success : function(data) {
                                if(data.status == 1){
                                    console.log(data.trd_img);
                                    $('.img_wrap>img').attr('src', '/images/default_add_img.png');
                                    $('.img_wrap>img').addClass('default_img');
                                    $('#first_img_addbtn>label').attr('for','construction_img'+inp_index);
                                    data.trd_img.forEach(function(key, index, item) {
                                        var real_index = parseInt(index)+1;
                                        
                                        $('#preview_contruct'+real_index).removeClass('default_img');
                                        $('#preview_contruct'+real_index).attr('src', '/storage/fdata/trade/estimate'+item);
                                        img_index = real_index;
                                    });

                                }else if(data.status == 0){
                                    swal({
                                        title: "알림",
                                        text: "이미지가 삭제되지 않았습니다.\n관리자에게 문의하세요.",
                                        button: "확인",
                                    });
                                }
                            },
                            error : function(data){
                                swal({
                                    title: "네트워크 오류",
                                    text: "잠시 후 다시 시도해주세요.",
                                    button: "확인",
                                });
                            }
                        });
                    }
                });
            }
        },1000);

        $(document).on('click', '#estimate_file_download',  function(){
            var this_li = $(this);
            var file_name = this_li.siblings('span').text();

            swal({
                title: "견적파일 삭제",
                text: "선택하신 파일을 삭제하시겠습니까?",
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
                    $.ajax({
                        type : "POST",
                        url : "/api/estimate_request", 
                        dataType: 'json',     
                        data : { trd_no : trd_no, file_name : file_name, req : 'deletefile', step : 5 },
                        success : function(data) {
                            if(data.status == 1){
                                //성공적으로 업로드
                                var file_wrap = $('.file_wrap');

                                file_wrap.html("");

                                var template = $($('#estimate_file_li').html());

                                data.trd_file.forEach(function(key, index, item) {
                                    var real_index = parseInt(index)+1;
                                
                                    template.find('#estimate_file_name').text(item.replace('/',''));

                                    img_index = real_index;
                                });

                                file_wrap.append(template);

                                $('#estimate_insert_btn').data('index',data.index);
                                $('#estimate_insert_btn').attr('for','file'+ data.index);

                            }else if(data.status == 2){
                                swal({
                                    title: "알림",
                                    text: "최대 파일 업로드 갯수는 2개입니다.",
                                    button: "확인",
                                });
                            }
                        },
                        error : function(data){
                            swal({
                                title: "네트워크 오류",
                                text: "잠시 후 다시 시도해주세요.",
                                button: "확인",
                            });
                        }
                    });
                }
            });
        });
    </script>


@endsection