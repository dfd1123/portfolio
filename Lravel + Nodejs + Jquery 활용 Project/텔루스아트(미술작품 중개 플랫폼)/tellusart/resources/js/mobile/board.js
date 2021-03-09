function mobile_notice_more() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    var notice_list = $('#notice_list');
    var offset = notice_list.data('offset');
    var count = notice_list.data('count');
    var str = '';

    if(offset != count){
        $('#board_load').show();
        $.ajax({
            url: "/mobile/notice/more",
            type: "POST",
            data: { _token: CSRF_TOKEN, offset : offset },
            dataType: "JSON",
            success: function(data) {

                $.each(data.notices, function (index, notice) { 
                        
                    str += '<div class="date en">' + notice.created_at.replace('-','.') + '</div>';
                    str += '<div class="blist_box"><h3><a href="/notices/' + notice.id + '" style="color:#000">' + notice.title + '</a></h3>';
                    str += '<div class="conbox">' +notice.body+ '</div>';
                    str += '<ul>';
                    str += '<li class="en"><img src="/storage/image/mobile/ic_viewcount.png" alt="" />'+ notice.hit +'</li>';
                    str += '<li class="en"><img src="/storage/image/mobile/ic_file.png" alt="" />';
                    
                    if(notice.file1 != null){
                        str += '1</li>';
                    }else{
                        str += '0</li>';
                    }
                    
                    str += '</ul></div>';
                
                });

                $('#board_load').hide();

                notice_list.data('offset', data.offset);
                
                notice_list.append(str);

                if(data.offset == count){
                    $('a.more').hide();
                }

            }
        });
    }
}

function mobile_event_more() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    var event_list = $('#event_list');
    var offset = $('#event_list').data('offset');
    var count = $('#event_list').data('count');
    var str = '';

    if(offset != count){
        $('#board_load').show();
        $.ajax({
            url: "/mobile/event/more",
            type: "POST",
            data: { _token: CSRF_TOKEN, offset : offset },
            dataType: "JSON",
            success: function(data) {
                var today = todays();
                var todayArr =  today.split('-');
                var todayCompare = new Date(todayArr[0], parseInt(todayArr[1])-1, todayArr[2]);


                $.each(data.events, function (index, event) { 

                    var startDate = event.start_time; 
                    var startDateArr = startDate.split('-');
                    
                    var endDate = event.end_time; 
                    var endDateArr = endDate.split('-');
                            
                    var startDateCompare = new Date(startDateArr[0], parseInt(startDateArr[1])-1, startDateArr[2]);
                    var endDateCompare = new Date(endDateArr[0], parseInt(endDateArr[1])-1, endDateArr[2]);
                            
                    str += '<div class="date en">' + event.created_at.split(' ')[0] + '</div>';
                    str += '<div class="elist_box"><h3><a href="/events/'+event.id+'">'+event.title+'</a></h3>';
                    str += '<div class="conbox"><p><a href="/events/'+event.id+'"><img src="/storage/image/event/'+event.pc_banner+'" alt=""/></a></p></div>';
                    str += '<div class="estate">';
                    
                    if(startDateCompare.getTime() < todayCompare.getTime() && todayCompare.getTime() < endDateCompare.getTime() ) {
                        str += '<span class="ing">진행중</span>';
                    }else if(todayCompare.getTime() > endDateCompare.getTime()){
                        str += '<span class="noting">종료</span>';
                    }else if(startDateCompare.getTime() > todayCompare.getTime()){
                        str += '<span class="noting">예정</span>';
                    }

                    str += '<ul><li>'+endDate.replace('-','.')+'</li></ul>';
                    str += '</div></div>';
                });

                $('#board_load').hide();

                event_list.data('offset', data.offset);
                
                event_list.append(str);

                if(data.offset == count){
                    $('a.more').hide();
                }
                
            }
        });
    }
}

function todays(){
    var date = new Date();
 
    var year = date.getFullYear(); 
    var month = date.getMonth()+1; 
    var day = date.getDate(); 
 
    if ((day+"").length < 2) {       // 일이 한자리 수인 경우 앞에 0을 붙여주기 위해
        day = "0" + day;
    }
 
    return (year+"-"+month+"-"+day); 
}