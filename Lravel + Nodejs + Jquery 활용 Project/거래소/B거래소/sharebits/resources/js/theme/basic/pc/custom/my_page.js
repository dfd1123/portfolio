var min;
var sec;
var timer;
var timer2;

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
        } else if(text.length < 8 || text.length > 12) {
            swal({
                text: __.myp.restrict_password,
                icon: "warning",
                button: __.message.ok,
            });
            return false;
        }

        return true;
    };

    if(!check(currentPassword.val())) {
        currentPassword.val('').focus();
        return false;
    }
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