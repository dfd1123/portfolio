$('.adm_user_level').change(function(){
    var id = $(this).data('id');
    var level = $(this).val();
    if(confirm('정말 해당 관리자의 보안등급을 변경하시겠습니까?')){
        $.ajax({
            url: '/adm/admin_user_level_change',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, id:id, level:level},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) { 
                alert('성공적으로 해당 관리자의 보안등급을 변경하였습니다.');
                location.reload();
            }
        });
    }
});

function admin_user_delete(id){
    if(confirm('정말 해당 관리자를 삭제하시겠습니까?')){
        location.href='/admin/admin_user_delete?id='+id+'';
    }
}