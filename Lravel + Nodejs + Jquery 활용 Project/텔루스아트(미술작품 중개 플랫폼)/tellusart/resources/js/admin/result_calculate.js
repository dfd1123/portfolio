$('.result_confirm').click(function(){
    var id = $(this).data('id');
    var button = $(this);
    $.ajax({
        url: '/result_calculate/confirm',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, id:id},
        dataType: 'JSON',
        /* remind that 'data' is the response of the AjaxController */
        success: function (data) { 
            $('.tsa-table-wrap .table tbody td[data-id="'+id+'"] input').attr("disabled",true);
            button.parent().html('<span class="result_success">처리성공</span>')
            alert('정산처리 완료!');    	
        }
    });
});

$('#all_result_confirm').submit(function(){
    $('input[name="is_confirm_id"]').val(selectRow());
    return true;
});

var check = false;

function CheckAll(){

    var chk = document.getElementsByName("confirm[]");
    
    if(check == false){
    
        check = true;
        
        for(var i=0; i<chk.length;i++){                                                                 
            if(!chk[i].disabled){
                chk[i].checked = true;     //모두 체크
            }
        
        }
    
    }else{
    
        check = false;
        
        for(var i=0; i<chk.length;i++){                                                                    
            if(!chk[i].disabled){
                chk[i].checked = false;     //모두 해제
            }
        }
    
    }

}


function selectRow() {

    var chk = document.getElementsByName("confirm[]"); // 체크박스객체를 담는다
    var len = chk.length;    //체크박스의 전체 개수
    var checkRow = '';      //체크된 체크박스의 value를 담기위한 변수
    var checkCnt = 0;        //체크된 체크박스의 개수
    var checkLast = '';      //체크된 체크박스 중 마지막 체크박스의 인덱스를 담기위한 변수
    var rowid = '';             //체크된 체크박스의 모든 value 값을 담는다
    var cnt = 0;                 

    for(var i=0; i<len; i++){
        if(chk[i].checked == true){
            checkCnt++;        //체크된 체크박스의 개수	
            checkLast = i;     //체크된 체크박스의 인덱스
        }
    } 



    for(var i=0; i<len; i++){
        if(chk[i].checked == true){  //체크가 되어있는 값 구분
            checkRow = chk[i].value;

            if(checkCnt == 1){                            //체크된 체크박스의 개수가 한 개 일때,
                rowid += checkRow;        //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
            }else{                                            //체크된 체크박스의 개수가 여러 개 일때,
                if(i == checkLast){                     //체크된 체크박스 중 마지막 체크박스일 때,
                    rowid += checkRow;  //'value'의 형태 (뒤에 ,(콤마)가 붙지않게)
                }else{
                    rowid += checkRow+"|";	 //'value',의 형태 (뒤에 ,(콤마)가 붙게)         			
                }
            }
        
            cnt++;
            checkRow = '';    //checkRow초기화.
        }
    }

    return rowid;    //'value1', 'value2', 'value3' 의 형태로 출력된다.

}