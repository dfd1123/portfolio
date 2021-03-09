//View 페이지

//초기설정 및 로드
var board_name = $('input[name="board_name"]').val();
var board_id = $('input[name="board_id"]').val();
var comment_orderby = $('#comment_orderby').val();

comment_load();
//초기설정 및 로드 

//댓글 순서(작성순, 추천순)
$('#comment_orderby').on('change', function () {
    comment_orderby = $(this).val();
    comment_load();
});
//댓글 순서(작성순, 추천순)

//댓글 더보기 클릭시 로드
$('.more_comment_btn').on('click', function () {
    var offset = $('#reply_con_wrap').data('offset');
    $.ajax({
        type: "GET",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), board_name: board_name, id: board_id, offset: offset, order_by: comment_orderby, kind: "comment_more" },
        url: "/api/comunity",
        success: function (data) {
            data.comments.forEach(function (comment) {
                var templete = $($('#reply_con_contents').html());
                templete.attr('id', 'reply' + comment.id);
                templete.find('._nickname').text(comment.writer_nickname);
                templete.find('._date').text(moment(comment.updated_at).format('YYYY-MM-DD HH:mm:ss'));
                templete.find('.contents').data('id', comment.id);
                templete.find('.contents p').text(comment.comment);
                templete.find('.contents p').attr('id', 'contents' + comment.id);
                templete.find('.like_type_btn').data('id', comment.id);
                templete.find('.recomend_cnt').text(comment.recomend);
                templete.find('.unrecomend_cnt').text(comment.unrecomend);
                templete.find('.edit').data('id', comment.id);
                templete.find('.del').data('id', comment.id);
                templete.find('.del').attr('onclick', 'comment_delete("#modal_popup_del_comment", ' + comment.id + ')');
                templete.find('.reple').data('id', comment.id);

                if (data.auth) {
                    if (comment.recomend_uid != null) {
                        if (comment.recomend_uid.indexOf(data.uid) !== -1) {
                            templete.find('.like_btn').addClass('active');
                        }
                    }

                    if (comment.unrecomend_uid != null) {
                        if (comment.unrecomend_uid.indexOf(data.uid) !== -1) {
                            templete.find('.hate_btn').addClass('active');
                        }
                    }

                    if (data.uid != comment.writer_id){
                        templete.find('.edit').remove();
                        if(!data.admin) {
                            templete.find('.del').remove();
                        }
                    }
                }else{
                    templete.find('.edit').remove();
                    templete.find('.del').remove();
                }


                comment.re_comments.forEach(function (re_comment) {
                    var templete2 = $($('#in_reply_con_contents').html());
                    templete2.attr('id', 're_reply' + re_comment.id);
                    templete2.find('._nickname').text(re_comment.writer_nickname);
                    templete2.find('._date').text(moment(re_comment.updated_at).format('YYYY-MM-DD HH:mm:ss'));
                    templete2.find('.contents').data('id', re_comment.id);
                    templete2.find('.contents p').text(re_comment.comment);
                    templete2.find('.contents p').attr('id', 'contents' + re_comment.id);
                    templete2.find('.like_type_btn').data('id', re_comment.id);
                    templete2.find('.recomend_cnt').text(re_comment.recomend);
                    templete2.find('.unrecomend_cnt').text(re_comment.unrecomend);
                    templete2.find('.edit').data('id', re_comment.id);
                    templete2.find('.del').data('id', re_comment.id);
                    templete2.find('.del').attr('onclick', 'comment_delete("#modal_popup_del_re_comment", ' + re_comment.id + ')');
                    templete.find('.in_comment_wrap').show();

                    if (data.auth) {
                        if(re_comment.recomend_uid != null) {
                            if (re_comment.recomend_uid.indexOf(data.uid) !== -1) {
                                templete2.find('.like_btn').addClass('active');
                            }
                        }

                        if(re_comment.unrecomend_uid != null) {
                            if (re_comment.unrecomend_uid.indexOf(data.uid) !== -1) {
                                templete2.find('.hate_btn').addClass('active');
                            }
                        }
                        
                        if (data.uid != comment.writer_id){
                            templete2.find('.edit').remove();
                            if(!data.admin) {
                                templete2.find('.del').remove();
                            }
                        }
                    }else{
                        templete2.find('.edit').remove();
                        templete2.find('.del').remove();
                    }

                    templete.find('.in_comment_wrap').append(templete2);
                });

                $('#reply_con_wrap').append(templete);
            });

            $('#reply_con_wrap').data("comment_cnt", data.comment_cnt);
            $('#reply_con_wrap').data("offset", data.offset);
            $('._total_amt em').text(data.comment_cnt);

            if (data.comment_cnt > data.offset) {
                $('.more_comment_btn').show();
            } else {
                $('.more_comment_btn').remove();
            }

        },
        error: function (data) {
            swal({
                title: "네트워크 오류",
                text: "잠시 후 다시 시도해주세요.",
                button: "확인",
            });
        }
    });
});
//댓글 더보기 클릭시 로드

//댓글 등록
$(document).on('click', '#comment_submit', function () {
    var comment = $(this).siblings('textarea[name="comment"]').val();
    var comment_id = $(this).siblings('input[name="comment_id"]').val();

    if (comment != '') {
        if (comment_id == '') {
            $.ajax({
                type: "POST",
                dataType: "json",
                data: { _token: $('meta[name="csrf-token"]').attr('content'), board_name: board_name, board_id: board_id, comment: comment, comment_id: comment_id, kind: "comment" },
                url: "/api/comunity",
                success: function (data) {
                    if(data.status != 0){
                        var templete = $($('#reply_con_contents').html());
                        templete.find('._nickname').text(data.writer_nickname);
                        templete.find('._date').text(moment().format());
                        templete.find('.contents p').text(data.comment);
                        templete.find('.hd_btns').hide();

                        $('#reply_con_wrap').append(templete);

                        var total_cnt = parseInt($('._total_amt em').text()) + 1;
                        $('._total_amt em').text(total_cnt);

                        $('textarea[name="comment"]').val('');
                        swal({
                            title: "댓글 등록",
                            text: data.message,
                            button: "확인",
                        });
                    }else{
                        swal({
                            title: "댓글 등록",
                            text: data.message,
                            button: "확인",
                        });
                    }
                },
                error: function (data) {
                    swal({
                        title: "댓글 등록 실패",
                        text: "일시적 네트워크 오류이거나 커뮤니티 사용이 금지되었습니다.\n다시 시도 후 운영자에게 문의하세요",
                        button: "확인",
                    });
                }
            });
        } else {
            $.ajax({
                type: "POST",
                dataType: "json",
                data: { _token: $('meta[name="csrf-token"]').attr('content'), board_name: board_name, board_id: board_id, comment: comment, comment_id: comment_id, kind: "comment" },
                url: "/api/comunity",
                success: function (data) {
                    if(data.status != 0){
                        var templete = $($('#in_reply_con_contents').html());
                        templete.find('._nickname').text(data.writer_nickname);
                        templete.find('._date').text(moment().format());
                        templete.find('.contents p').text(data.comment);
                        templete.find('.hd_btns').hide();
                        
                        $('#reply' + comment_id + ' .in_comment_wrap')
                            .append(templete)
                            .find('.in_comment_wrap')
                            .show();

                        $('textarea[name="comment"]').val('');
                        swal({
                            title: "댓글 등록",
                            text: data.message,
                            button: "확인",
                        });
                    }else{
                        swal({
                            title: "댓글 등록",
                            text: data.message,
                            button: "확인",
                        });
                    }
                },
                error: function (data) {
                    swal({
                        title: "댓글 등록 실패",
                        text: "일시적 네트워크 오류이거나 커뮤니티 사용이 금지되었습니다.\n다시 시도 후 운영자에게 문의하세요",
                        button: "확인",
                    });
                }
            });
        }
    } else {
        swal({
            text: "댓글 내용을 입력하세요.",
            button: "확인",
        });
    }
});
//댓글 등록

//게시판글 추천
$('.this_posting_recommned_btn').on('click', function () {
    $.ajax({
        type: "PUT",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), board_name: board_name, kind: "board_recomend" },
        url: "/api/comunity/" + board_id + "",
        success: function (data) {
            if (data.status) {
                if (data.in_de) {
                    var recomend_cnt = parseInt($('.this_posting_recommned_btn ._num').text().replace(',', '')) + 1;
                    $('.this_posting_recommned_btn').addClass('active');
                } else {
                    var recomend_cnt = parseInt($('.this_posting_recommned_btn ._num').text().replace(',', '')) - 1;
                    $('.this_posting_recommned_btn').removeClass('active');
                }

                $('.this_posting_recommned_btn ._num').text(recomend_cnt);
            }else{
                swal({
                    title: "로그인 필요",
                    text: "로그인 후 다시 시도해주세요.",
                    button: "확인",
                });
            }

        },
        error: function (data) {
            swal({
                title: "요청 실패",
                text: "일시적 네트워크 오류이거나 커뮤니티 사용이 금지되었습니다.\n다시 시도 후 운영자에게 문의하세요",
                button: "확인",
            });
        }
    });
});
//게시판글 추천

//댓글 좋아요 버튼 클릭시
$(document).on('click', '.like_btn', function () {
    var like_btn = $(this);
    var like_cnt_obj = like_btn.children('.recomend_cnt');
    var comment_id = like_btn.data('id');
    
    if(!like_btn.hasClass('active')){
        swal({
            title: "로그인 필요",
            text: "로그인 후 시도해주세요.",
            button: "확인",
        });

        return false;
    }

    $.ajax({
        type: "PUT",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), comment_id: comment_id, kind: "comment_recomend" },
        url: "/api/comunity/" + board_id + "",
        success: function (data) {
            if (data.status) {
                if (data.in_de) {
                    var recomend_cnt = parseInt(like_cnt_obj.text().replace(',', '')) + 1;
                    like_btn.addClass('active');
                } else {
                    var recomend_cnt = parseInt(like_cnt_obj.text().replace(',', '')) - 1;
                    like_btn.removeClass('active');
                }

                like_cnt_obj.text(recomend_cnt);
            }

        },
        error: function (data) {
            swal({
                title: "요청 실패",
                text: "일시적 네트워크 오류이거나 커뮤니티 사용이 금지되었습니다.\n다시 시도 후 운영자에게 문의하세요",
                button: "확인",
            });
        }
    });
});
//댓글 좋아요 버튼 클릭시

//댓글 싫어요 버튼 클릭시
$(document).on('click', '.hate_btn', function () {
    var hate_btn = $(this);
    var hate_btn_obj = hate_btn.children('.unrecomend_cnt');
    var comment_id = hate_btn.data('id');

    if(!hate_btn.hasClass('active')){
        swal({
            title: "로그인 필요",
            text: "로그인 후 시도해주세요.",
            button: "확인",
        });

        return false;
    }

    $.ajax({
        type: "PUT",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), comment_id: comment_id, kind: "comment_unrecomend" },
        url: "/api/comunity/" + board_id + "",
        success: function (data) {
            if (data.status) {
                if (data.in_de) {
                    var unrecomend_cnt = parseInt(hate_btn_obj.text().replace(',', '')) + 1;
                    hate_btn.addClass('active');
                } else {
                    var unrecomend_cnt = parseInt(hate_btn_obj.text().replace(',', '')) - 1;
                    hate_btn.removeClass('active');
                }

                hate_btn_obj.text(unrecomend_cnt);
            }

        },
        error: function (data) {
            swal({
                title: "응답 실패",
                text: "일시적 네트워크 오류이거나 커뮤니티 사용이 금지되었습니다.\n다시 시도 후 운영자에게 문의하세요",
                button: "확인",
            });
        }
    });
});
//댓글 싫어요 버튼 클릭시

//댓글 수정 버튼 클릭시
$(document).on('click', '.comunity_table_view .reply_list .hd .hd_btns .edit', function () {
    var content = $(this).parent().parent().siblings('.contents').children('p').text();
    $('#modal_popup_edit_reple textarea').val(content);
    $('.comment_edit_btn').data('id', $(this).data('id'));
    custom_alert_popup_open('#modal_popup_edit_reple');
});
//댓글 수정하고 submit 할때
$('.comment_edit_btn').on('click', function () {
    var content = $('#modal_popup_edit_reple textarea').val();
    var recomment = $('input[name="recomment"]').val();

    var id = $(this).data('id');
    $.ajax({
        type: "PUT",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), content: content, recomment: recomment, kind: "comment" },
        url: "/api/comunity/" + id + "",
        success: function (data) {
            if (data.status) {
                $('#contents' + id + '').text(content);
                $('#modal_popup_edit_reple').fadeOut(200).removeClass('active');
                $('#modal_popup_edit_reple textarea').val('');
            }

        },
        error: function (data) {
            swal({
                title: "댓글 수정 실패",
                text: "일시적 네트워크 오류이거나 커뮤니티 사용이 금지되었습니다.\n다시 시도 후 운영자에게 문의하세요",
                button: "확인",
            });
        }
    });
});
//댓글 수정하고 submit 할때
//댓글 수정 버튼 클릭시

//댓글 답글 버튼 클릭시
$(document).on('click', '.comunity_table_view .reply_list .hd .hd_btns .reple', function () {
    var comment_id = $(this).data('id');
    var templete = $($('#comment_inp_con').html());
    $('.comment_write_wrap').hide();
    $('.comment_write_wrap').html('');
    $('#comment_inp_wrap').html('');
    templete.find('input[name="comment_id"]').val(comment_id);
    $(this).parent().parent().parent().siblings('.comment_write_wrap').append(templete);
    $(this).parent().parent().parent().siblings('.comment_write_wrap').show();
    var offset = $(this).parent().parent().parent().siblings('.comment_write_wrap').offset();
    $('html, body').animate({ scrollTop: offset.top - 300 }, 400);
});
//댓글 답글 버튼 클릭시

//초기에 댓글 불러올때 사용하는 함수
function comment_load() {
    $.ajax({
        type: "GET",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), board_name: board_name, board_id: board_id, order_by: comment_orderby, kind: "comment" },
        url: "/api/comunity",
        success: function (data) {
            $('#reply_con_wrap').empty();
            data.comments.forEach(function (comment) {
                var templete = $($('#reply_con_contents').html());
                templete.attr('id', 'reply' + comment.id);
                templete.find('._nickname').text(comment.writer_nickname);
                templete.find('._date').text(moment(comment.updated_at).format('YYYY-MM-DD HH:mm:ss'));
                templete.find('.contents').data('id', comment.id);
                templete.find('.contents p').text(comment.comment);
                templete.find('.contents p').attr('id', 'contents' + comment.id);
                templete.find('.like_type_btn').data('id', comment.id);
                templete.find('.recomend_cnt').text(comment.recomend);
                templete.find('.unrecomend_cnt').text(comment.unrecomend);
                templete.find('.edit').data('id', comment.id);
                templete.find('.del').data('id', comment.id);
                templete.find('.del').attr('onclick', 'comment_delete("#modal_popup_del_comment", ' + comment.id + ')');
                templete.find('.reple').data('id', comment.id);

                if (data.auth) {
                    if (comment.recomend_uid != null) {
                        if (comment.recomend_uid.indexOf(data.uid) !== -1) {
                            templete.find('.like_btn').addClass('active');
                        }
                    }

                    if (comment.unrecomend_uid != null) {
                        if (comment.unrecomend_uid.indexOf(data.uid) !== -1) {
                            templete.find('.hate_btn').addClass('active');
                        }
                    }

                    if (data.uid != comment.writer_id){
                        templete.find('.edit').remove();
                        if(!data.admin) {
                            templete.find('.del').remove();
                        }
                    }
                }else{
                    templete.find('.edit').remove();
                    templete.find('.del').remove();
                }


                comment.re_comments.forEach(function (re_comment) {
                    var templete2 = $($('#in_reply_con_contents').html());
                    templete2.attr('id', 're_reply' + re_comment.id);
                    templete2.find('._nickname').text(re_comment.writer_nickname);
                    templete2.find('._date').text(moment(re_comment.updated_at).format('YYYY-MM-DD HH:mm:ss'));
                    templete2.find('.contents').data('id', re_comment.id);
                    templete2.find('.contents p').text(re_comment.comment);
                    templete2.find('.contents p').attr('id', 'contents' + re_comment.id);
                    templete2.find('.like_type_btn').data('id', re_comment.id);
                    templete2.find('.recomend_cnt').text(re_comment.recomend);
                    templete2.find('.unrecomend_cnt').text(re_comment.unrecomend);
                    templete2.find('.edit').data('id', re_comment.id);
                    templete2.find('.del').data('id', re_comment.id);
                    templete2.find('.del').attr('onclick', 'comment_delete("#modal_popup_del_re_comment", ' + re_comment.id + ')');
                    templete.find('.in_comment_wrap').show();

                    if (data.auth) {
                        if(re_comment.recomend_uid != null) {
                            if (re_comment.recomend_uid.indexOf(data.uid) !== -1) {
                                templete2.find('.like_btn').addClass('active');
                            }
                        }
                        
                        if(re_comment.unrecomend_uid != null) {
                            if (re_comment.unrecomend_uid.indexOf(data.uid) !== -1) {
                                templete2.find('.hate_btn').addClass('active');
                            }
                        }

                        if (data.uid != comment.writer_id){
                            templete2.find('.edit').remove();
                            if(!data.admin) {
                                templete2.find('.del').remove();
                            }
                        }
                    }else{
                        templete2.find('.edit').remove();
                        templete2.find('.del').remove();
                    }

                    templete.find('.in_comment_wrap').append(templete2);
                });

                $('#reply_con_wrap').append(templete);
            });

            $('#reply_con_wrap').data("offset", data.offset);
            $('._total_amt em').text(data.comment_cnt);

            if (data.comment_cnt > data.offset) {
                $('.more_comment_btn').show();
            } else {
                $('.more_comment_btn').remove();
            }

        },
        error: function (data) {
            swal({
                title: "네트워크 오류",
                text: "잠시 후 다시 시도해주세요.",
                button: "확인",
            });
        }
    });
}
//초기에 댓글 불러올때 사용하는 함수

//게시글 삭제 버튼 클릭시
$('#board_delete_btn').on('click', function () {
    $('#delete_board_form').submit();
});
//게시글 삭제 버튼 클릭시

//댓글 삭제 버튼 클릭시
$('#comment_delete_btn').on('click', function () {
    var id = $(this).data('id');
    $.ajax({
        type: "DELETE",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), board_name: board_name, board_id: board_id, kind: "comment" },
        url: "/api/comunity/" + id + "",
        success: function (data) {
            if (data.status) {
                $('#reply' + id).remove();
                $('#modal_popup_del_comment').fadeOut(200).removeClass('active');
                $('._total_amt em').text(parseInt($('._total_amt em').text()) - 1);
            }

        },
        error: function (data) {
            swal({
                title: "네트워크 오류",
                text: "잠시 후 다시 시도해주세요.",
                button: "확인",
            });
        }
    });
});
//댓글 삭제 버튼 클릭시

//대댓글 삭제 버튼 클릭시
$('#re_comment_delete_btn').on('click', function () {
    var id = $(this).data('id');
    $.ajax({
        type: "DELETE",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), board_name: board_name, board_id: board_id, kind: "re_comment" },
        url: "/api/comunity/" + id + "",
        success: function (data) {
            if (data.status) {
                $('#re_reply' + id).remove();
                $('#modal_popup_del_re_comment').fadeOut(200).removeClass('active');
            }

        },
        error: function (data) {
            swal({
                title: "네트워크 오류",
                text: "잠시 후 다시 시도해주세요.",
                button: "확인",
            });
        }
    });
});
//대댓글 삭제 버튼 클릭시

// 임시 팝업소스 
function comment_delete(name, id) {
    $(name).show().addClass('active');

    if (name == '#modal_popup_del_comment') {
        $('#comment_delete_btn').data('id', id);
    } else if (name == '#modal_popup_del_re_comment') {
        $('#re_comment_delete_btn').data('id', id);
    }
}

function custom_alert_popup_open(name) {
    $(name).show().addClass('active');
}
function custom_alert_popup_close(name) {
    $('#modal_popup_edit_reple textarea').val('');
    $(name).parents('.custom_alert_popup').fadeOut(200).removeClass('active');
}

//View 페이지
