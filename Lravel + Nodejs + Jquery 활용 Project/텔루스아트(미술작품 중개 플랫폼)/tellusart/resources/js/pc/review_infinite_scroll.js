var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');



var normal_review_wrap = $('#review_more_modal .jw_modal_bd');
var normal_review_con = $('#review_more_modal .jw_modal_bd .content_box');

$(normal_review_wrap).scroll(function() {

    if (normal_review_con.height() >= normal_review_wrap.height()){
        if (normal_review_wrap.scrollTop() == normal_review_con.height() - normal_review_wrap.height()) {
            more_normal_review(normal_review_con.data('offset'), normal_review_con.data('count'), normal_review_con.data('proid'));
        }
    }
});





var expert_review_wrap = $('#expert_review_more_modal .jw_modal_bd');
var expert_review_con = $('#expert_review_more_modal .jw_modal_bd .content_box');

$(expert_review_wrap).scroll(function() {

    if (expert_review_con.height() >= expert_review_wrap.height()){
        if (expert_review_wrap.scrollTop() == expert_review_con.height() - expert_review_wrap.height()) {
            more_expert_review(expert_review_con.data('offset'), expert_review_con.data('count'), expert_review_con.data('proid'));
        }
    }
});








function more_normal_review(offset, count, proid){
    var str = '';
    if(offset != count){
        $.ajax({
            url: '/normal_review/more',
            type: 'POST',
            data: {_token: CSRF_TOKEN, offset:offset, count:count, proid:proid},
            dataType: 'JSON',
            success: function (data) { 
                $.each(data.reviews, function (index, review) { 
                
                    str += normal_review_struct(data.login_yn, data.uid, review.writer_id, review.id, review.review_body, review.profile_img, review.unickname, review.updated_at, review.recomend, review.unrecomend); 
                
                })

                normal_review_con.data('offset', data.offset);
                normal_review_con.append(str);
            }
        });
    }
}


function more_expert_review(offset, count, proid){
    var str = '';
    if(offset != count){
        $.ajax({
            url: '/expert_review/more',
            type: 'POST',
            data: {_token: CSRF_TOKEN, offset:offset, count:count, proid:proid},
            dataType: 'JSON',
            success: function (data) { 
                $.each(data.reviews, function (index, review) { 
                
                    str += expert_review_struct(data.login_yn, data.uid, review.uid, review.id, review.review_body, review.profile_img, review.uname, review.updated_at, review.recomend, review.unrecomend, review.rating); 
                
                })

                expert_review_con.data('offset', data.offset);
                expert_review_con.append(str);
            }
        });
    }
}