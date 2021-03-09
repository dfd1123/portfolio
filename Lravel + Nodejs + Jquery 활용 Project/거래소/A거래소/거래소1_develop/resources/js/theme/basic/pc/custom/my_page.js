var min;
var sec;
var timer;
var timer2;
window.name = "Parent_window";

/*락리워드 락내역-배당내역 탭메뉴*/
$(".lock_history_table").each(function(index) {
    //순서값 배부
    $(this).attr("data-index", index);
});

$(".lock_reward_group .lock_history_tab ul li")
    .each(function(index) {
        //순서값 배부
        $(this).attr("data-index", index);
    })
    .click(function() {
        //누른 메뉴 꾸밈효과 주고, 아닌 탭은 꾸밈효과 지운다
        $(this).addClass("active");
        $(".lock_reward_group .lock_history_tab ul li")
            .not(this)
            .removeClass("active");

        var i = $(this).data("index");
        //클릭한 순서값에 따라 매치되는 박스 보여주고 매치되지 않으면 숨긴다
        $(".lock_history_table[data-index=" + i + "]").show();
        $(".lock_history_table[data-index!=" + i + "]").hide();
    });

$("#select_coin").change(function(e) {
    //코인 선택시 화면 이동
    window.location.href = "/mypage/lock_reward/" + $(e.target).val();
});

$("#btn_lock").click(function(e) {
    //락 버튼 클릭 시 값 체크, 실행여부 묻기
    return (
        after_lock_coin_validate("lock") &&
        confirm(__.myp.ask_lock)
    );
});

$("#btn_unlock").click(function(e) {
    //락 버튼 클릭 시 값 체크, 실행여부 묻기
    return (
        after_lock_coin_validate("unlock") &&
        confirm(__.myp.ask_unlock)
    );
});

$("#lock_history_view_more").click(function(e) {
    // 중복실행 방지
    var button = $(e.currentTarget);
    if(button.data("isRunning")) {
        return;
    }
    button.data("isRunning", true);

    // 락 내역 더보기 버튼 클릭 시 불러오기
    var page = button.data("next-page");
    var limit = button.data("limit");
    var coin = $('#lock_coin').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/history_view_more",
        type: "POST",
        data: { _token: CSRF_TOKEN, page: page, limit: limit, coin: coin },
        dataType: "JSON"
    })
    .done(function(data) {
        var lockHistory = $('#lock_history tbody');

        data.lock_items.forEach(function(item) {
            var status = item.operation == 1 ? 'lock_status' : 'unlock_status';
            var operation = item.operation == 1 ? __.myp.lock : __.myp.unlock;
            $('<tr>')
                .addClass(status)
                .append($('<td>').text(operation))
                .append($('<td>').text(item.amount))
                .append($('<td>').text(item.created_dt))
                .appendTo(lockHistory);
        });

        if(data.lock_items_next_page > 0) {
            button.data({
                'next-page': data.lock_items_next_page,
                'isRunning': false
            });
        } else {
            $('#lock_history_view_more').hide();
        }
    })
    .fail(function() {
        console.log("error");
    });
});

$("#lock_dividend_view_more").click(function(e) {
    // 중복실행 방지
    var button = $(e.currentTarget);
    if(button.data("isRunning")) {
        return;
    }
    button.data("isRunning", true);
    
    // 배당 내역 더보기 버튼 클릭 시 불러오기
    var page = button.data("next-page");
    var limit = button.data("limit");
    var coin = $('#lock_coin').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/dividend_view_more",
        type: "POST",
        data: { _token: CSRF_TOKEN, page: page, limit: limit, coin: coin },
        dataType: "JSON"
    })
    .done(function(data) {
        var lockDividend = $('#lock_dividend tbody');

        data.dividend_items.forEach(function(item) {
            $('<tr>')
                .append($('<td>').text(item.amount))
                .append($('<td>').text(item.created_dt))
                .appendTo(lockDividend);
        });

        if(data.dividend_items_next_page > 0) {
            button.data({
                'next-page': data.dividend_items_next_page,
                'isRunning': false
            });
        } else {
            $('#lock_dividend_view_more').hide();
        }
    })
    .fail(function() {
        console.log("error");
    });
});

if($("#amount_coin").length > 0) {
    setInputFilter($("#amount_coin")[0], function(value) {
        //입력된 값 체크 후 잘못된 값이면 입력방지
        return /^\d*[.]?\d*$/.test(value);
    });
}

function after_lock_coin_validate(type) {
    if (lock_coin_validate(type)) {
        return true;
    }

    $("#amount_coin").val("");
    return false;
}

function lock_coin_validate(type) {
    var avaliable_amount = $("#available_amount").val();
    var lock_amount = $("#lock_amount").val();
    var amount_input = $("#amount_coin").val();

    if (amount_input == "") {
        swal({
            text: __.myp.require_value,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }

    if ((amount_input + ".").split(".")[1].length > 8) {
        swal({
            text: __.myp.too_many_points,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }

    var amount = parseFloat(amount_input);
    if (amount == NaN || amount_input.charAt(amount_input.length - 1) === ".") {
        swal({
            text: __.myp.wrong_value,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }

    if (amount <= 0) {
        swal({
            text: __.myp.not_zero_or_less,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }

    if (type === "lock") {
        if (amount > parseFloat(avaliable_amount)) {
            swal({
                text: __.myp.not_exceed_avail,
                icon: "warning",
                button: __.message.ok,
            });
            return false;
        }
    } else if (type === "unlock") {
        if (amount > parseFloat(lock_amount)) {
            swal({
                text: __.myp.not_exceed_lock,
                icon: "warning",
                button: __.message.ok,
            });
            return false;
        }
    }

    return true;
}

function setInputFilter(textbox, inputFilter) {
    [
        "input",
        "keydown",
        "keyup",
        "mousedown",
        "mouseup",
        "select",
        "contextmenu",
        "drop"
    ].forEach(function(event) {
        textbox.addEventListener(event, function() {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(
                    this.oldSelectionStart,
                    this.oldSelectionEnd
                );
            }
        });
    });
}
/*//락리워드 락내역-배당내역 탭메뉴*/

/*비밀번호 변경 탭메뉴*/
var currentPassword = $('#current_password');
var newPassword = $('#new_password');
var confirmPassword = $('#confirm_password');
var pwNo = $('.register_pw_alert .pw_no');
var pwYes = $('.register_pw_alert .pw_yes');
var buttonPasswordChange = $('#btn_password_change');

newPassword.keyup(function(e) {
    checkPasswordConfirm();
});

confirmPassword.keyup(function(e) {
    checkPasswordConfirm();
});

function checkPasswordConfirm() {
    if(newPassword.val() !== confirmPassword.val()) {
        pwYes.addClass('hide');
        pwNo.removeClass('hide');
    } else {
        pwYes.removeClass('hide');
        pwNo.addClass('hide');
    }
}

buttonPasswordChange.click(function() {
    return password_change_validate();
});

function password_change_validate() {
    var check = function(text) {
        if(text === '') {
            swal({
                text: __.myp.require_password,
                icon: "warning",
                button: __.message.ok,
            });
            return false;
        } else if(text.length < 8 || text.length > 20) {
            swal({
                text: __.myp.restrict_password,
                icon: "warning",
                button: __.message.ok,
            });
            return false;
        }

        return true;
    };

    if(!check(newPassword.val())) {
        newPassword.val('').focus();
        return false;
    }
    if(!check(confirmPassword.val())) {
        confirmPassword.val('').focus();
        return false;
    }

    if(newPassword.val() !== confirmPassword.val()) {
        swal({
            text: __.myp.not_match_password,
            icon: "warning",
            button: __.message.ok,
        });
        newPassword.val('').focus();
        confirmPassword.val('');
        return false;
    }

    return true;
}
/*//비밀번호 변경 탭메뉴*/

// 보안인증 JS  //
$('#security_country').on('change',function(){
	var country = this.value;
	if(country == 'kr'){
		$("#mobile_number").attr("onclick","fnPopup();");
		$("#mobile_number").attr("readonly",true);
		$("#mobile_number").attr("placeholder","클릭해주세요.");
	}else{
		$("#mobile_number").attr("onclick","");
		$("#mobile_number").attr("readonly",false);
		$("#mobile_number").attr("placeholder","Phone Number");
	}
});



function fnPopup(){
	window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
	document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
	document.form_chk.target = "popupChk";
	document.form_chk.submit();
}

function nicecheck_alert(status,messages){
	if(status == 0){
		swal({
	        text: messages,
	        icon: "warning",
	        button: __.message.ok,
	    }).then((confirm)=>{
            if(confirm){
                location.reload();
            }
        });
	}else{
		swal({
	        text: __.message.complete_sms_certify,
	        icon: "success",
	        button: __.message.ok,
        }).then((confirm)=>{
            if(confirm){
                location.reload();
            }
        });
	}
	
    
}

$('#sms_certify').click(function(){
	var country = $('select[name="country"]').val();
	var mobile_number = $('input[name="mobile_number"]').val();

	if($(this).hasClass('active') && $('input[name="mobile_cv"]').val() == 1){
		swal({
			text: __.message.already_confirm_mobile,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}
	if(country == '' || (country == null)){
		swal({
			text: __.message.please_select_country,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}

	if(mobile_number == ''){
		swal({
			text: __.message.please_input_mobile,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}

	$.ajax({
		url : "/mobile/verify",
		type : "POST",
		data: {_token: CSRF_TOKEN, mobile_number: mobile_number, country: country},
		dataType: 'JSON',
		success : function(data) {
			if(data.yn){
                swal({
                    text: __.message.send_sms_certify_code,
                    icon: "success",
                    button: __.message.ok,
                });
				clearInterval(timer);
				$('#sms_certify').text(__.message.resend);
				min = 3;
				sec = (min*60)%60;
				$('#mobile_number_code').attr('readonly',false);
				timer = setInterval("start_timer()",1000);
			}else{
				swal({
					text: __.message.already_exist_mobile,
					icon: "warning",
					button: __.message.ok,
				});
			}
		}
	});
});

// jw_modal START
function jw_modal_open(kind){
    $('#'+kind).show();
    setTimeout(function() {
       $('#'+kind).addClass('active');
    }, 100);
    
    $('.modal-window #cancel_btn').bind('click', function(){
        jw_modal_close()
    });
}

function jw_modal_close(){
    setTimeout(function() {
        $('.modal-window').removeClass('active');
     }, 400);
    $('.modal-window').hide();

    var templete = $($('#account_in_popup_content').html());
    $('#account').html(templete);
}

$(document).on('click', '.modal-window #cancel_btn', function(){
    jw_modal_close()
});
// js_modal END

// 회원가입 폰인증번호 입력하면 색깔 변해서 입력한 티 나게 하기
$('#mobile_number_code').keyup(function(){
	
	if( $(this).val()=='' ){
		$('#sms_certify_confirm').removeClass('active');
	}else{
		$('#sms_certify_confirm').addClass('active');
	}
});
// end

$('#sms_certify_confirm').click(function(){
    var certify_code = $('input[name="mobile_certify_code"]').val();
    var country = $('select[name="country"]').val();
	var mobile_number = $('input[name="mobile_number"]').val();

	if($(this).hasClass('active') && $('input[name="mobile_cv"]').val() == 1){
		swal({
			text: __.message.already_confirm_mobile,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}

	if(certify_code == ''){
		swal({
			text: __.message.please_input_mobile_certify,
			icon: "warning",
			button: __.message.ok,
		});
		return false;
	}

	$.ajax({
		url : "/mobile/verify/confirm",
		type : "POST",
		data: {_token: CSRF_TOKEN, certify_code: certify_code, mobile_number: mobile_number, country: country},
		dataType: 'JSON',
		success : function(data) {
			if(data.yn){
				clearInterval(timer);
				$('#sms_certify_confirm').html('<i class="fal fa-check"></i>'+__.message.complete_certify);
				$('#sms_certify_confirm').addClass('active');
				$('#mobile_number_code').attr('readonly','readonly');
                $('input[name="mobile_cv"]').val(1);
                
                swal({
					text: __.message.complete_sms_certify,
					icon: "success",
					button: [__.message.ok, true],
				}).then((value) => {
                    location.href='/home';
                });
			}else{
				swal({
					text: __.message.wrong_sms_certify,
					icon: "warning",
					button: __.message.ok,
				});
			}
		}
	});

	///mobile/verify/confirm
});


$('#security_setting_document').on('submit',function(){
	if($('#thum_file').val() == ''){
		swal({
            text: __.boan.require_document1_value,
            icon: "warning",
            button: __.message.ok,
        });
		return false;
	}else if($('#thum_file2').val() == ''){
		swal({
            text: __.boan.require_document2_value,
            icon: "warning",
            button: __.message.ok,
        });
		return false;
	}
	return true;
});

$(document).on('click', '.bank_view li', function(){
    var check_bank = $(this).text();
    $('#check_pop_bank').prop('checked', false);
    $('#check_bank em').text(check_bank);
    $('#check_bank em').css('color', '#000');
    $('#check_bank_name').val(check_bank);
    $('#check_bank_name').data('code', $(this).data('code'));
});

var isAccountCertifing = false;
$(document).on('click', '#account_certify_btn', function(){
    var account_num  = $('#account_num').val();
    var account_bank = $('#check_bank_name').val();
    var account_code = $('#check_bank_name').data('code');

    if(account_num == ''){
        swal({
            text: __.boan.require_account1_value,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }else if(account_bank == ''){
        swal({
            text: __.boan.require_account2_value,
            icon: "warning",
            button: __.message.ok,
        });
        return false;
    }else if($(this).hasClass('btn_certi_before')){
        return false;
    }

    if(isAccountCertifing) {
        return;
    }
    isAccountCertifing = true;
    $.ajax({
        type : "POST",
        dataType: "json",
        data : {_token: CSRF_TOKEN, account_num : account_num, account_bank : account_bank, account_code : account_code},
        url : "/account/certify",
        success : function(data) {
            if(data.status){
                $('#account_confirm_form').removeClass("hide");
                $('#account_certify_btn').addClass('btn_certi_before');
            }else{
                swal({
                    text: data.msg,
                    icon: "warning",
                    button: __.message.ok,
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
        complete : function(data) {
            isAccountCertifing = false;
        }
    });
});

$(document).on('click', '#account_certify_confirm_btn', function(){
    var account_num  = $('#account_num').val();
    var account_bank = $('#check_bank_name').val();
    var act_number_code = $('#act_number_code').val();

    if(account_num == ''){
        return false;
    }else if(account_bank == ''){
        return false;
    }else if(act_number_code == ''){
        return false;
    }

    $.ajax({
        type : "POST",
        dataType: "json",
        data : {_token: CSRF_TOKEN, account_num : account_num, account_bank : account_bank, account_certify_code : act_number_code},
        url : "/account/certify/confirm",
        success : function(data) {
            swal({
                text: data.msg,
                icon: data.icon,
                button: __.message.ok,
            });

            if(data.status){
                $('#account_infor').text(data.account_bank + ' ' + data.account_num);
                $('#account_status_btn').text('변경하기');
                jw_modal_close();
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

/*
$('#security_setting_account').on('submit',function(){
	if($('#account_num').val() == ''){
		swal({
            text: __.boan.require_account1_value,
            icon: "warning",
            button: __.message.ok,
        });
		return false;
	}else if($('#account_bank').val() == ''){
		swal({
            text: __.boan.require_account2_value,
            icon: "warning",
            button: __.message.ok,
        });
		return false;
	}else if($('#thum_file').val() == ''){
		swal({
            text: __.boan.require_account3_value,
            icon: "warning",
            button: __.message.ok,
        });
		return false;
	}else if($('#thum_file2').val() == ''){
		swal({
            text: __.boan.require_account4_value,
            icon: "warning",
            button: __.message.ok,
        });
		return false;
	}
	return true;
});
*/
function start_timer(){

	var temp_chr1;
	var temp_chr2;

	if(sec == 0){
		sec = 59;
		min -= 1;
	}else{
		sec -= 1;
	}
	
	temp_chr1 = min.toString();
	temp_chr2 = sec.toString();
	
	if(temp_chr1.length == 1){
		temp_chr1 = '0' + temp_chr1;
	}
	
	if(temp_chr2.length == 1){
		temp_chr2 = '0' + temp_chr2;
	}
	
	$('#ViewTimer').text(temp_chr1+':'+temp_chr2);
	
	if(min==0 && sec==0){
		clearInterval(timer);
		$('#sms_certify_confirm').removeClass('active');
		$('input[name="mobile_certify_code"]').attr('readonly','readonly');
	}
}