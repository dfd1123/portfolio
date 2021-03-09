$('#notice_wrap').scroll(function(){
    if($('#notice_wrap').scrollTop() == $('#notice_tbl').height()-$('#notice_wrap').height()){
        more_notice();
    }
});

function more_notice(){
    var offset = $('#notice_tbl').data('offset');
    var count = $('#notice_tbl').data('count');

    if(offset < count){
        $.ajax({
            url : "/notice/more",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset},
            dataType : "JSON"
        }).done(function(data) {
            $('#notice_tbl').data('offset',data.offset);
            $('#notice_tbl tbody').append(data.str);

            if(data.offset >= count){
                $('#notice_wrap').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}

$('#event_wrap').scroll(function(){
    if($('#event_wrap').scrollTop() == $('#event_tbl').height()-$('#event_wrap').height()){
        more_event();
    }
});

function more_event(){
    var offset = $('#event_tbl').data('offset');
    var count = $('#event_tbl').data('count');

    if(offset < count){
        $.ajax({
            url : "/event/more",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset},
            dataType : "JSON"
        }).done(function(data) {
            $('#event_tbl').data('offset',data.offset);
            $('#event_tbl tbody').append(data.str);

            if(data.offset >= count){
                $('#event_wrap').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}

$('#qna_wrap').scroll(function(){
    if($('#qna_wrap').scrollTop() == $('#qna_tbl').height()-$('#qna_wrap').height()){
        more_qna();
    }
});

function more_qna(){
    var offset = $('#qna_tbl').data('offset');
    var count = $('#qna_tbl').data('count');

    if(offset < count){
        $.ajax({
            url : "/qna/more",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset},
            dataType : "JSON"
        }).done(function(data) {
            $('#qna_tbl').data('offset',data.offset);
            $('#qna_tbl tbody').append(data.str);

            if(data.offset >= count){
                $('#qna_wrap').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}

$('#ico_list_wrap0').scroll(function(){
    if($('#ico_list_wrap0').scrollTop() == $('#ico_list_div0').height()-$('#ico_list_wrap0').height()){
        more_ico0();
    }
});

function more_ico0(){
    var offset = $('#ico_list_div0').data('offset');
    var count = $('#ico_list_div0').data('count');
    var category = $('#ico_list_div0').data('category');

    if(offset < count){
        $.ajax({
            url : "/ico/list/more1",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset, category: category},
            dataType : "JSON"
        }).done(function(data) {
            $('#ico_list_div0').data('offset',data.offset);
            $('#ico_list_div0').append(data.str);

            if(data.offset >= count){
                $('#ico_list_wrap0').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}


$('#ico_list_wrap1').scroll(function(){
    if($('#ico_list_wrap1').scrollTop() == $('#ico_list_div1').height()-$('#ico_list_wrap1').height()){
        more_ico1();
    }
});

function more_ico1(){
    var offset = $('#ico_list_div1').data('offset');
    var count = $('#ico_list_div1').data('count');
    var category = $('#ico_list_div1').data('category');

    if(offset < count){
        $.ajax({
            url : "/ico/list/more2",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset, category: category},
            dataType : "JSON"
        }).done(function(data) {
            $('#ico_list_div1').data('offset',data.offset);
            $('#ico_list_div1').append(data.str);

            if(data.offset >= count){
                $('#ico_list_wrap1').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}

$('#ico_history_wrap').scroll(function(){
    if($('#ico_history_wrap').scrollTop() == $('#ico_history_li').height()-$('#ico_history_wrap').height()){
        more_ico_history();
    }
});

function more_ico_history(){
    var offset = $('#ico_history_li').data('offset');
    var count = $('#ico_history_li').data('count');

    if(offset < count){
        $.ajax({
            url : "/ico/history/more",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset},
            dataType : "JSON"
        }).done(function(data) {
            $('#ico_history_li').data('offset',data.offset);
            $('#ico_history_li ul').append(data.str);

            if(data.offset >= count){
                $('#ico_history_wrap').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}

$('#p2p_list_wrap').scroll(function(){
    if($('#p2p_list_wrap').scrollTop()-40 == $('#p2p_list_con').height()-$('#p2p_list_wrap').height()){
        list_p2p_more();
    }
});

function list_p2p_more(){
    var offset = $('#p2p_list_con').data('offset');
    var count = $('#p2p_list_con').data('count');
    var category = $('#select_p2p_list').val();
    var srch = $('#p2p_list_srch').val();
    var type = $('#p2p_list_con').data('type');
    var auth = login_yn;

    if(offset < count){
        $.ajax({
            url : "/mobile/p2p/list/more",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset, category: category, srch: srch, type: type, auth: auth},
            dataType : "JSON"
        }).done(function(data) {
            $('#p2p_list_con').data('offset',data.offset);
            $('#p2p_list_con').append(data.str);

            if(data.offset >= count){
                $('#p2p_list_wrap').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}


$('#p2p_onpro_wrap').scroll(function(){
    if($('#p2p_onpro_wrap').scrollTop() == $('#p2p_onpro_con').height()-$('#p2p_onpro_wrap').height()){
        p2p_onpro_more();
    }
});

function p2p_onpro_more(){
    var offset = $('#p2p_onpro_con').data('offset');
    var count = $('#p2p_onpro_con').data('count');
    var type = $('#p2p_onpro_con').data('type');
    var auth = login_yn;

    if(offset < count){
        $.ajax({
            url : "/mobile/p2p/onprogress/more",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset, type: type},
            dataType : "JSON"
        }).done(function(data) {
            $('#p2p_onpro_con').data('offset',data.offset);
            $('#p2p_onpro_con').append(data.str);

            if(data.offset >= count){
                $('#p2p_onpro_wrap').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}

$('#p2p_history_wrap').scroll(function(){
    if($('#p2p_history_wrap').scrollTop()-78 == $('#p2p_history_con').height()-$('#p2p_history_wrap').height()){
        p2p_history_more();
    }
});

function p2p_history_more(){
    var offset = $('#p2p_history_con').data('offset');
    var count = $('#p2p_history_con').data('count');
    var from_date = $('input[name="from_date"]').val();
    var to_date = $('input[name="to_date"]').val();

    $('#txtFilter').val('');
    filter();
    $('#selectFilter').val('');
    selFilter();

    if(offset < count){
        $.ajax({
            url : "/mobile/p2p/history/more",
            type : "POST",
            data : {_token : CSRF_TOKEN, offset: offset, from_date: from_date, to_date: to_date},
            dataType : "JSON"
        }).done(function(data) {
            $('#p2p_history_con').data('offset',data.offset);
            $('#p2p_history_con ul').append(data.str);

            if(data.offset >= count){
                $('#p2p_history_wrap').attr('id','');
            }
        }).fail(function(){
            console.log("error");
        });
    }
}

