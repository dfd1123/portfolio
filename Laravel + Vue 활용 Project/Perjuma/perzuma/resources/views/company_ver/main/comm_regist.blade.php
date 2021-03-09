<div class="re_comment_wrap">
    <input type="hidden" name="trd_no" id="trd_no" value="{{$trd_no}}" />
    <div class="comment_box">
        <div class="comment_hd">
            <h3>코멘트 등록(업체)</h3>
            <span><i class="fal fa-times"></i></span>
        </div>
        <div>
            <div class="comment_inp_box">
                <h5>제목</h5>
                <textarea id="title"></textarea>
            </div>
            <div class="comment_inp_box">
                <h5>내용</h5>
                <textarea id="contents"></textarea>
            </div>
            <div class="comment_img_box">
                <div class="top_estimate_box">
                    <div class="add_img_li">
                        <div class="box">
                            <div class="content">
                                <div id="first_img_addbtn" class="img_wrap">
                                    <label for="commnet_img1" data-index="1">
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
                                        <input type="file" name="commnet_img{{$i}}" id="commnet_img{{$i}}" class="hide"  data-index="{{$i}}" accept="/image/*" capture="camera" />
                                        <img src="/images/default_add_img.png" id="preview_comment{{$i}}" class="default_img" alt="default_img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="bottom_estimate_box">
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
        <div class="comment_ft">
            <button type="button">작성</button>
        </div>
    </div>
</div>

<template id="none_estimate_file_li">
    <div class="no_file">파일을 업로드 하여주세요.</div>
</template>

<template id="estimate_file_li">
    <div class="estimate_file_li">
        <span id="estimate_file_name"></span>
        <span id="estimate_file_download"><img src="" alt="download_btn"></span>
    </div>
</template>



<script>
    var trd_no = $('input[name="trd_no"]').val();

    function readURL(input, index) {
        var formData = new FormData();
        var ucc_no = $('input[name="ucc_no"]').val();
        $.each(input.files, function(i, file) {
            //dataImg.append('file', file);
        });

        formData.append('images', $('#commnet_img'+index)[0].files[0]);
        formData.append('index', index);
        formData.append('ucc_no', $('input[name="ucc_no"]').val());
        formData.append('trd_no', $('input[name="trd_no"]').val());
        formData.append('req', 'upldimg');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {

                //Ajax로 이미지 업로드!
                $.ajax({
                    type : "POST",
                    url : "/api/company_ver/comment",
                    data : formData,
                    dataType: 'json',     
                    mimeType: 'multipart/form-data',
                    contentType: 'multipart/form-data',
                    success : function(data) {
                        if(data.status == 1){
                            $('#preview_comment'+index).attr('src', '/storage/fdata/trade/comment'+data.file_name);
                            $('input[name="ucc_no"]').val(data.ucc_no);
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
                $('#preview_comment'+index).removeClass('default_img');
                $('#preview_comment'+index).attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".img_wrap input[type='file']").change(function() {
        var index = $(this).data('index');
        var next_idx = index + 1;
        $('#first_img_addbtn label').attr("for", "commnet_img" + next_idx + "");

        readURL(this, index);
    });

    $('.estimate_file_inp').on('change', function(){
        var dataFile = new FormData();
        var index = $('#estimate_insert_btn').data('index');
        dataFile.append('index', index);
        dataFile.append('files', $('#file'+index)[0].files[0]);
        dataFile.append('trd_no', $('input[name="trd_no"]').val());
        dataFile.append('ucc_no', $('input[name="ucc_no"]').val());
        dataFile.append('req','uplddraws');

        $.ajax({
            type : "POST",
            url : "/api/company_ver/comment", //"/api/estimate_file_store",
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

                    $('input[name="ucc_no"]').val(data.ucc_no);

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

    $(".img_wrap>img").enableLongClick(function(id, x, y){
        var target = $('#'+id);
        var inp_index = target.siblings('input').data('index');
        var ucc_no = $('input[name="ucc_no"]').val();
        if(!target.hasClass('default_img')){
            var img_name = target.attr('src');
            img_name = img_name.replace('/storage/fdata/trade/comment','');
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
                        url : "/api/company_ver/comment", 
                        dataType: 'json',     
                        data : { trd_no : trd_no, ucc_no: ucc_no, img_name : img_name, req : 'deleteimg'},
                        success : function(data) {
                            if(data.status == 1){
                                console.log(data.trd_img);
                                $('.img_wrap>img').attr('src', '/images/default_add_img.png');
                                $('.img_wrap>img').addClass('default_img');
                                $('#first_img_addbtn>label').attr('for','commnet_img'+inp_index);
                                data.trd_img.forEach(function(key, index, item) {
                                    var real_index = parseInt(index)+1;
                                    
                                    $('#preview_comment'+real_index).removeClass('default_img');
                                    $('#preview_comment'+real_index).attr('src', '/storage/fdata/trade/comment'+item);
                                    img_index = real_index;
                                });

                                $('input[name="ucc_no"]').val(data.ucc_no);

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

    $('.comment_ft button').on('click', function(){
        var title = $('#title').val();
        var content = $('#contents').val();
        var ucc_no = $('input[name="ucc_no"]').val();

        $.ajax({
            type : "POST",
            url : "/api/company_ver/comment", 
            dataType: 'json',     
            data : { trd_no : trd_no, ucc_no : ucc_no, title : title, content : content, req : 'write'},
            success : function(data) {
                if(data.status == 1){
                    swal({
                        title: "작성 완료",
                        text: "코멘트가 작성되었습니다.",
                        button: "확인",
                    }).then((value) => {
                        history.go(0);
                    });

                }else if(data.status == 0){
                    swal({
                        title: "네트워크 오류",
                        text: "잠시 후 다시 시도해주세요.",
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
    });
</script>