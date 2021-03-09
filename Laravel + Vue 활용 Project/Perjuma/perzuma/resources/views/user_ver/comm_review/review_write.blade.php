<div class="re_comment_wrap">
    <input type="hidden" name="rv_no" value="" />
    <div class="comment_box">
        <div class="comment_hd">
            <h3 id="review_hd">리뷰 작성</h3>
            <span><i class="fal fa-times"></i></span>
        </div>
        <div>
            <div class="comment_inp_box">
                <h5>별점</h5>
                <div id="rating_zip" class="rating_box">
                    <input type="hidden" name="rating" value="3.0" />
                    <div class="starRev">
                        <span class="starR on" data-rating="1.0"></span>
                        <span class="starR on" data-rating="2.0"></span>
                        <span class="starR on" data-rating="3.0"></span>
                        <span class="starR" data-rating="4.0"></span>
                        <span class="starR" data-rating="5.0"></span>
                    </div>
                    <div class="rating_score_box">
                        <em>3.0</em>/5
                    </div>
                </div>
            </div>
            <div class="comment_inp_box">
                <h5>제목</h5>
                <textarea id="title"></textarea>
            </div>
            <div class="comment_inp_box">
                <h5>내용</h5>
                <textarea id="contents"></textarea>
            </div>
            <div class="comment_img_box" style="margin-bottom:1.3em;">
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
        </div>
        <div class="comment_ft">
            <button type="button" id="review_ft">작성</button>
        </div>
    </div>
</div>


<script>
    var trd_no = $('input[name="trd_no"]').val();
    var agent_no = $('input[name="agent_no"]').val();

    function readURL(input, index) {
        var formData = new FormData();

        $.each(input.files, function(i, file) {
            //dataImg.append('file', file);
        });

        formData.append('images', $('#commnet_img'+index)[0].files[0]);
        formData.append('index', index);
        formData.append('rv_no', $('input[name="rv_no"]').val());
        formData.append('trd_no', $('input[name="trd_no"]').val());
        formData.append('agent_no', $('input[name="agent_no"]').val());
        formData.append('req', 'upldimg');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {

                //Ajax로 이미지 업로드!
                $.ajax({
                    type : "POST",
                    url : "/api/user_ver/review",
                    data : formData,
                    dataType: 'json',     
                    mimeType: 'multipart/form-data',
                    contentType: 'multipart/form-data',
                    success : function(data) {
                        if(data.status == 1){
                            $('#preview_comment'+index).attr('src', '/storage/fdata/trade/review'+data.file_name);
                            
                            $('input[name="rv_no"]').val(data.rv_no);
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

    $(".img_wrap>img").enableLongClick(function(id, x, y){
        var target = $('#'+id);
        var inp_index = target.siblings('input').data('index');
        var rv_no = $('input[name="rv_no"]').val();

        if(!target.hasClass('default_img')){
            var img_name = target.attr('src');
            img_name = img_name.replace('/storage/fdata/trade/review','');
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
                        url : "/api/user_ver/review", 
                        dataType: 'json',     
                        data : { trd_no : trd_no, agent_no : agent_no, img_name : img_name, rv_no: rv_no, req : 'deleteimg'},
                        success : function(data) {
                            if(data.status == 1){
                                $('input[name="rv_no"]').val(data.rv_no);

                                $('.img_wrap>img').attr('src', '/images/default_add_img.png');
                                $('.img_wrap>img').addClass('default_img');
                                $('#first_img_addbtn>label').attr('for','commnet_img'+inp_index);
                                data.trd_img.forEach(function(key, index, item) {
                                    var real_index = parseInt(index)+1;
                                    
                                    $('#preview_comment'+real_index).removeClass('default_img');
                                    $('#preview_comment'+real_index).attr('src', '/storage/fdata/trade/review'+item);
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

    $('.comment_ft button').on('click', function(){
        var title = $('#title').val();
        var content = $('#contents').val();
        var rv_no = $('input[name="rv_no"]').val();
        var rating = $('input[name="rating"]').val();
        
        $.ajax({
            type : "POST",
            url : "/api/user_ver/review", 
            dataType: 'json',     
            data : { trd_no : trd_no, agent_no : agent_no, rv_no : rv_no, title : title, content : content, rating : rating, req : 'write'},
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

    $('.starRev span').click(function(){
        $(this).parent().children('span').removeClass('on');
        $(this).addClass('on').prevAll('span').addClass('on');
        
        $('input[name="rating"]').val($(this).data('rating'));
        $('.rating_score_box em').text($(this).data('rating'));
        return false;
    });
</script>