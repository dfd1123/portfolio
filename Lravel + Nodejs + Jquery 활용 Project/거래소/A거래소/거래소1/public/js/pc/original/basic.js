var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var locale = $('meta[name="locale"]').attr('content');
var login_yn;
alertify.buy = alertify.extend("buy");
alertify.sell = alertify.extend("sell"); //document.addEventListener('touchstart', handler, {passive: true});

$(document).ready(function () {
  login_yn = IsLogin();
  $('.posi_wrap').css('display', 'none');
  alertify.set({
    delay: 3000
  });
  $('.banner_slide').show();
  $('.stop_user_id_warning').click(function () {
    swal({
      title: '계정 정지',
      text: '관리자에 의해 정지된 계정입니다.\n고객센터로 문의주시기 바랍니다.',
      icon: "warning",
      button: __.message.ok
    });
  });
});

function IsLogin() {
  var result;
  $.ajax({
    url: "/check/login",
    type: "POST",
    async: false,
    data: {
      _token: CSRF_TOKEN
    },
    dataType: 'JSON',
    success: function success(data) {
      result = data.login_yn;
    }
  });
  return result;
}

function dateSet(date) {
  var new_date = new Date(moment(date).toISOString()); //추후 Moment Timezone으로 변경 요망

  if (locale == 'kr') {
    var month = pad(new_date.getMonth() + 1, 2);
    var day = pad(new_date.getDate(), 2);
    var hour = pad(new_date.getHours() + 9, 2);
    var minute = pad(new_date.getMinutes(), 2);
    var second = pad(new_date.getSeconds(), 2);
  } else if (locale == 'jp') {
    var month = pad(new_date.getMonth() + 1, 2);
    var day = pad(new_date.getDate(), 2);
    var hour = pad(new_date.getHours() + 9, 2);
    var minute = pad(new_date.getMinutes(), 2);
    var second = pad(new_date.getSeconds(), 2);
  } else if (locale == 'ch') {
    var month = pad(new_date.getMonth() + 1, 2);
    var day = pad(new_date.getDate(), 2);
    var hour = pad(new_date.getHours() + 8, 2);
    var minute = pad(new_date.getMinutes(), 2);
    var second = pad(new_date.getSeconds(), 2);
  } else if (locale == 'th') {
    var month = pad(new_date.getMonth() + 1, 2);
    var day = pad(new_date.getDate(), 2);
    var hour = pad(new_date.getHours() + 7, 2);
    var minute = pad(new_date.getMinutes(), 2);
    var second = pad(new_date.getSeconds(), 2);
  } else {
    var month = pad(new_date.getMonth() + 1, 2);
    var day = pad(new_date.getDate(), 2);
    var hour = pad(new_date.getHours(), 2);
    var minute = pad(new_date.getMinutes(), 2);
    var second = pad(new_date.getSeconds(), 2);
  }

  return month + "-" + day + " " + hour + ":" + minute + ":" + second;
}

function pad(n, width) {
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
} //필터 (코인목록 검색)


function filter() {
  var searchstr1 = $('#txtFilter').val().toUpperCase();
  var searchstr2 = $('#txtFilter').val().toLowerCase();

  if ($('#txtFilter').val() == "") {
    $("table.target tr").css('display', '');
    $("ul.target li").css('display', '');
  } else {
    $("table.target tr").css('display', 'none');
    $("table.target tr[name*='" + searchstr1 + "']").css('display', '');
    $("table.target tr[name*='" + searchstr2 + "']").css('display', '');
    $("ul.target li").css('display', 'none');
    $("ul.target li[name*='" + searchstr1 + "']").css('display', '');
    $("ul.target li[name*='" + searchstr2 + "']").css('display', '');
  }

  return false;
}
/*코인목록 검색*/


$('#txtFilter').keyup(function () {
  filter();
  return false;
});
$('#txtFilter').keypress(function () {
  if (event.keyCode == 13) {
    filter();
    return false;
  }
});
/*//코인목록 검색*/

function selFilter() {
  var selvalue = $('select#selectFilter').val();

  if (selvalue == '') {
    $("table.target tr").css('display', '');
    $("ul.target li").css('display', '');
  } else {
    $("table.target tr").css('display', 'none');
    $("table.target tr:contains(" + selvalue + ")").css('display', '');
    $("ul.target li").css('display', 'none');
    $("ul.target li:contains(" + selvalue + ")").css('display', '');
  }
}

$('select#selectFilter').change(function () {
  selFilter();
  return false;
});
/**
 * 중복서브밋 방지
 * 
 * @returns {Boolean}
 */

var doubleSubmitFlag = false;

function doubleSubmitCheck() {
  if (doubleSubmitFlag) {
    return doubleSubmitFlag;
  } else {
    doubleSubmitFlag = true;
    return false;
  }
}
/*자동으로 넘겨지는 배너영역 슬라이드 스크립트입니당!*/


$('.autoplay').slick({
  slidesToShow: 4,
  //몇개가 보일지(현재 4개의 배너가 보이고있음)
  slidesToScroll: 1,
  //몇개씩 넘겨지는지 (현재 하나씩만 넘어가고있음)
  autoplay: true,
  //자동재생하기
  autoplaySpeed: 3000,
  //넘겨지는 속도입니당
  dots: true,
  //위에 동그라미 리스트들
  infinite: true,
  //계속 무한대로 돌아가는 걸 상징합니당
  responsive: [{
    //반응형에 따라서
    breakpoint: 1199,
    //해상도 가로길이가 1199px일때,
    settings: {
      slidesToShow: 3,
      //3개가 보이고
      slidesToScroll: 1
    }
  }, {
    breakpoint: 770,
    //해상도 가로길이가 770px일때
    settings: {
      slidesToShow: 2,
      //2개가 보임
      slidesToScroll: 1
    }
  }, {
    breakpoint: 580,
    //해상도 가로길이가 580px(모바일)일때
    settings: {
      slidesToShow: 1,
      //1개가 보임
      slidesToScroll: 1
    }
  }] //responsive

}); //slick

/*메인 공지사항 넘기기*/

$('.ntc_li .ntc_span').click(function () {
  slideNotice(this, 0);
});

function slideNotice(x, y) {
  var thisSlidegroup = $(x).next('.slide_group');
  var slideHeight = thisSlidegroup.children('.ntc_p').outerHeight();
  thisSlidegroup.stop().animate({
    top: -slideHeight
  }, function () {
    thisSlidegroup.css({
      top: y
    }).find('.ntc_p').first().appendTo(thisSlidegroup);
  });
}

noticeTimer = setInterval(function () {
  $('.ntc_li .ntc_span').trigger('click');
}, 2000);
/* 메인 USDC마켓-BTC마켓-ETH마켓 탭메뉴 */

$('.main_chart_top_tab ul li').click(function () {
  $(this).addClass('active');
  $('.main_chart_top_tab ul li').not(this).removeClass('active');
}); //end

/*----------------거래소 js start----------------*/

/*공지사항 자동넘기기*/

$('#ntc_next_btn').click(function () {
  $('.ntc_bar .ntc_ul').stop().animate({
    top: -15
  }, function () {
    $('.ntc_bar .ntc_ul').css({
      top: 0
    }).find('li').first().appendTo('.ntc_bar .ntc_ul');
  });
});
var auto = setInterval(function () {
  $('#ntc_next_btn').trigger('click');
}, 3000);
/*//공지사항 자동넘기기*/

/*공지사항 닫기버튼*/

$('#ntc_x_btn').click(function () {
  $('.ntc_bar').toggleClass('active');
});
/*//공지사항 닫기버튼*/

/*호가창 화살표*/

$('#arrow_both_btn').click(function () {
  $('.trans_wrap .left_con .wait_wrap').css({
    height: 355
  });
});
$('#arrow_up_btn').click(function () {
  $('.trans_wrap .left_con .up_wrap').css({
    height: 710
  });
  $('.trans_wrap .left_con .down_wrap').css({
    height: 0
  });
});
$('#arrow_down_btn').click(function () {
  $('.trans_wrap .left_con .up_wrap').css({
    height: 0
  });
  $('.trans_wrap .left_con .down_wrap').css({
    height: 710
  });
});
/*//호가창 화살표*/

/*거래기록 더보기 버튼*/

$('#toggle_but').click(function () {
  $('.trans_wrap .right_con_inbox-2').toggleClass('active');
  $('.trans_wrap .right_con_inbox-1').toggleClass('active');
});
/*//거래기록 더보기 버튼*/

/* 코인목록 탭버튼 */

$('.market_list_tab li').click(function () {
  $(this).addClass('active');
  $('.market_list_tab li').not(this).removeClass('active');
});
/* //코인목록 탭버튼 */

/*반응형 1580 이하일 때, 구매 - 판매 둘중 하나 고르기*/

$('#option_buy_btn').addClass('active');
$('#option_sell_btn').click(function () {
  $(this).addClass('active');
  $('.option_li').not(this).removeClass('active');
  $('.trans_wrap .center_con .deal_wrap .deal_con.coin_buy_con').hide();
  $('.trans_wrap .center_con .deal_wrap .deal_con.coin_sell_con').show();
});
$('#option_buy_btn').click(function () {
  $(this).addClass('active');
  $('.option_li').not(this).removeClass('active');
  $('.trans_wrap .center_con .deal_wrap .deal_con.coin_buy_con').show();
  $('.trans_wrap .center_con .deal_wrap .deal_con.coin_sell_con').hide();
});
/*//반응형 1580 이하일 때, 구매 - 판매 둘중 하나 고르기*/

/*----------------거래소 js end----------------*/

$('.third_history_con .third_history_hd select').change(function () {
  var sel_wd_kind = $(this).val();
  $("#transaction_list tr").css('display', 'none');

  if (sel_wd_kind == 0) {
    $("#transaction_list tr.out").css('display', '');
  } else if (sel_wd_kind == 1) {
    $("#transaction_list tr.in").css('display', '');
  } else {
    $("#transaction_list tr").css('display', '');
  }
});
$('#my_coin').click(function () {
  if ($(this).prop("checked")) {
    $("#coin_list_table > tbody > tr").hide();
    var k = "poss";
    var temp = $("#coin_list_table > tbody > tr > td:nth-child(3):contains('poss')");
    temp.parent().show();
  } else {
    $("#coin_list_table > tbody > tr").show();
  }
});
/*입금주소-출금신청-입출금내역 탭메뉴*/

$('.transac_con_wrap .toggle_con').each(function (index) {
  //순서값 배부
  $(this).attr('data-index', index);
});
$('.transac_header ul li').each(function (index) {
  //순서값 배부
  $(this).attr('data-index', index);
}).click(function () {
  //누른 메뉴 꾸밈효과 주고, 아닌 탭은 꾸밈효과 지운다
  $(this).addClass('active');
  $('.transac_header ul li').not(this).removeClass('active');
  var i = $(this).data('index'); //클릭한 순서값에 따라 매치되는 박스 보여주고 매치되지 않으면 숨긴다

  $('.transac_con_wrap .toggle_con[data-index=' + i + ']').show();
  $('.transac_con_wrap .toggle_con[data-index!=' + i + ']').hide();
});
/*//입금주소-출금신청-입출금내역 탭메뉴*/

$('.trans_inner_2 .trans_right .ta_right_tit i').click(function () {
  iconRotate();
  setTimeout(function () {
    iconRotateStop();
  }, 3000);
});

if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
  $('.transwallet_btn').on('click', function (e) {
    select_all_and_copy("deposit_address");
  });
} else {
  var clipboard = new ClipboardJS('.transwallet_btn');
  clipboard.on('success', function (e) {
    swal({
      text: __.message.success_copy_addr,
      icon: "success",
      buttons: {
        yes: {
          text: __.message.yes,
          value: true
        }
      }
    }); //alertify.success(__.message.success_copy_addr);

    e.clearSelection();
  });
  clipboard.on('error', function (e) {
    swal({
      text: __.message.fail_copy_addr,
      icon: "warning",
      buttons: {
        yes: {
          text: __.message.yes,
          value: true
        }
      }
    });
  });
}

function iconRotate() {
  var rotateIcon = $('.trans_inner_2 .trans_right .ta_right_tit i');
  rotateIcon.addClass('active');
}

function iconRotateStop() {
  var rotateIcon = $('.trans_inner_2 .trans_right .ta_right_tit i');
  rotateIcon.removeClass('active');
} // 복사 소스 IOS, ANDROID, WINDOW, INTERNET 다 가능


function select_all_and_copy(el) {
  // http://www.seabreezecomputers.com/tips/copy2clipboard.htm
  // ++ added line for table
  var el = document.getElementById(el); // Copy textarea, pre, div, etc.

  if (document.body.createTextRange) {
    // IE 
    var textRange = document.body.createTextRange();
    textRange.moveToElementText(el);
    textRange.select();
    textRange.execCommand("Copy");
    swal({
      text: __.message.success_copy_addr,
      icon: "success",
      buttons: {
        yes: {
          text: __.message.yes,
          value: true
        }
      }
    }); //alertify.success(__.message.success_copy_addr);
  } else if (window.getSelection && document.createRange) {
    // non-IE
    var editable = el.contentEditable; // Record contentEditable status of element

    var readOnly = el.readOnly; // Record readOnly status of element

    el.contentEditable = true; // iOS will only select text on non-form elements if contentEditable = true;

    el.readOnly = false; // iOS will not select in a read only form element

    var range = document.createRange();
    range.selectNodeContents(el);
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range); // Does not work for Firefox if a textarea or input

    if (el.nodeName == "TEXTAREA" || el.nodeName == "INPUT") el.select(); // Firefox will only select a form element with select()

    if (el.setSelectionRange && navigator.userAgent.match(/ipad|ipod|iphone/i)) el.setSelectionRange(0, 999999); // iOS only selects "form" elements with SelectionRange

    el.contentEditable = editable; // Restore previous contentEditable status

    el.readOnly = readOnly; // Restore previous readOnly status 

    if (document.queryCommandSupported("copy")) {
      var successful = document.execCommand('copy');

      if (successful) {
        swal({
          text: __.message.success_copy_addr,
          icon: "success",
          buttons: {
            yes: {
              text: __.message.yes,
              value: true
            }
          }
        });
      } //alertify.success(__.message.success_copy_addr);
      else {
          swal({
            text: __.message.fail_copy_addr,
            icon: "warning",
            buttons: {
              yes: {
                text: __.message.yes,
                value: true
              }
            }
          });
        }
    } else {
      if (!navigator.userAgent.match(/ipad|ipod|iphone|android|silk/i)) swal({
        text: __.message.fail_copy_addr,
        icon: "warning",
        buttons: {
          yes: {
            text: __.message.yes,
            value: true
          }
        }
      });
    }
  }
} // 코인변환 함수


function exchangeCashCoin(before_cointype, after_cointype) {
  if (before_cointype == 'USD') {
    var before_text_cointype = 'USDC';
    var after_text_cointype = after_cointype;
  } else {
    var before_text_cointype = before_cointype;
    var after_text_cointype = 'USDC';
  }

  swal({
    text: '정말 ' + before_text_cointype + '를 ' + after_text_cointype + '로 변환하시겠습니까?',
    icon: "warning",
    buttons: {
      yes: {
        text: __.message.yes,
        value: true
      },
      no: {
        text: __.message.no,
        value: false
      }
    },
    dangerMode: true
  }).then(function (value) {
    if (value) {
      var before_text_cointype;
      var after_text_cointype;
      var decimal_before;
      var decimal_after;
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url: "/exchangeCashCoin",
        type: "POST",
        data: {
          _token: CSRF_TOKEN,
          before_cointype: before_cointype,
          after_cointype: after_cointype
        },
        dataType: "JSON"
      }).done(function (data) {
        if (data.status == 'success') {
          if (before_cointype == 'USD') {
            before_text_cointype = 'USDC';
            decimal_before = 2;
          } else {
            before_text_cointype = before_cointype;
            decimal_before = 0;
          }

          if (after_cointype == 'USD') {
            after_text_cointype = 'USDC';
            decimal_after = 2;
          } else {
            after_text_cointype = after_cointype;
            decimal_after = 0;
          }

          all_refresh();
          swal({
            title: __.message.change_complete,
            text: __.message.trade_wait_money + data.before_balance.toFixed(decimal_before) + " " + before_text_cointype + __.message.change_fee + data.swap_fee + "% ( " + data.fee.toFixed(decimal_after) + " " + after_text_cointype + " )" + __.message.change_money + data.after_balance.toFixed(decimal_after) + " " + after_text_cointype,
            icon: "success",
            button: __.message.ok
          });
        } else {
          swal({
            title: __.message.change_fail,
            text: __.message.change_fail_balance,
            icon: "warning",
            button: __.message.ok
          });
        }
      }).fail(function () {
        console.log("error");
      });
    }
  });
} //코인선택했을때 코인정보 표출


function select_coin(symbol) {
  $('.posi_wrap').css('display', 'table');
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
    url: "/select_coin",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      symbol: symbol
    },
    dataType: "JSON"
  }).done(function (data) {
    //사용할 코인 정보
    $("#this_symbol_hidden").val(data.symbol);
    $("#this_symbol_text_hidden").val(data.symbol_text); // 코인 보유정보

    $("#this_symbol").html(data.coinname + " " + __.message.deposit_withdraw);
    $("#this_balance_total").html(' <b>' + numberWithCommas(data.total.toFixed(data.coin_decimal)) + '</b> <b class="currency">' + data.symbol_text + '</b> ');
    $("#this_balance_eval").html(' <b>' + numberWithCommas(data.eval.toFixed(data.cash_decimal)) + '</b> <u class="currency">KRW</u> ');
    $("#this_balance_pending").html(' <b>' + numberWithCommas(data.pending.toFixed(data.coin_decimal)) + '</b> <u class="currency">' + data.symbol_text + '</u> ');
    $("#this_balance_available").html(' <b>' + numberWithCommas(data.available.toFixed(data.coin_decimal)) + '</b> <u class="currency">' + data.symbol_text + '</u> ');

    if (symbol == 'USD') {
      $('.coin_table').addClass('hide');
    } else if (symbol == 'KRW') {
      $('.coin_table').addClass('hide');
      $('.cash_table').removeClass('hide');
      $("#cash_withdraw_limit_amt").html(' <b>' + numberWithCommas(data.withdraw_limit_amt) + '</b> <b class="currency">' + data.symbol_text + '</b> ');
      $("#cash_withdraw_amt_label").text(__.message.withdraw_amt.replace("*symbol_text*", data.symbol_text));
    } else {
      $('.coin_table').removeClass('hide');
      $('.cash_table').addClass('hide'); // 입금정보 삽입

      $("#first_deposit_info_ment").text(__.message.ments1.replace("*coinname*", data.coinname));
      $("#first_deposit_info").text(__.message.my_deposit_addr.replace("*coinname*", data.coinname));
      $("#deposit_coin_address_qrcode").html('<img id="qrcode" src="https://chart.googleapis.com/chart?chs=200x200&amp;cht=qr&amp;chl=' + data.address + '&amp;choe=UTF-8">');
      $("#deposit_coin_address").val(data.address); // 출금정보 삽입

      $("#withdraw_limit_amt").html(' <b>' + numberWithCommas(data.withdraw_limit_amt) + '</b> <b class="currency">' + data.symbol_text + '</b> ');
      $("#withdraw_amt_label").text(__.message.withdraw_amt.replace("*symbol_text*", data.symbol_text));
      $('#withdraw_amt').val('');
      $('#withdraw_check_address').val('');
    }

    refresh_trans_wallet();
    refresh_withdraw_history();
    $('.posi_wrap').css('display', 'none');
  }).fail(function () {
    console.log("error");
  });
} //새로코침 버튼 기능


function all_refresh() {
  $('.posi_wrap').css('display', 'table');
  var symbol = $("#this_symbol_hidden").val();
  refresh_trans_wallet();
  select_coin(symbol);
  refresh_withdraw_history();
} // 출금 시 코인 주소 체크


function withdraw_check_address() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var symbol = $("#this_symbol_text_hidden").val();
  var address = $("#withdraw_check_address").val();

  if (address == '' || address == null) {
    swal({
      text: __.message.please_input_addr,
      icon: "warning",
      button: __.message.ok
    });
  } else {
    $.ajax({
      url: "/check_address",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        symbol: symbol,
        address: address
      },
      dataType: "JSON"
    }).done(function (data) {
      if (data.result == 'valid') {
        swal({
          text: __.message.correct_addr,
          icon: "success",
          button: __.message.ok
        });
      } else if (data.result == 'invalid') {
        swal({
          text: __.message.uncorrect_addr,
          icon: "error",
          button: __.message.ok
        });
      } else {
        swal({
          text: __.message.error_network,
          icon: "warning",
          button: __.message.ok
        });
      }
    }).fail(function () {
      console.log("error");
    });
  }
} //출금가능한 코인 최대양


function withdraw_max_amt() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var symbol = $("#this_symbol_hidden").val();
  $.ajax({
    url: "/withdraw_max_amt",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      symbol: symbol
    },
    dataType: "JSON"
  }).done(function (data) {
    $("#withdraw_amt").val(data.max_amount);
    $("#withdraw_send_fee").text(data.send_fee);
    $("#withdraw_total_amt").text(data.total_amount);
    $("#cash_withdraw_amt").val(data.max_amount);
    $("#cash_withdraw_send_fee").text(data.send_fee);
    $("#cash_withdraw_total_amt").text(data.total_amount);
  }).fail(function () {
    console.log("error");
  });
}

function withdraw_onkey_amt() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var symbol = $("#this_symbol_hidden").val();

  if (symbol == 'KRW') {
    var amt = $("#cash_withdraw_amt").val();
  } else {
    var amt = $("#withdraw_amt").val();
  }

  if (amt == '' || amt == null) {
    amt = 0;
  }

  $.ajax({
    url: "/withdraw_onkey_amt",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      symbol: symbol,
      amt: amt
    },
    dataType: "JSON"
  }).done(function (data) {
    console.log(data);
    $("#withdraw_amt").val(data.amount);
    $("#withdraw_send_fee").text(data.send_fee);
    $("#withdraw_total_amt").text(data.total_amount);
    $("#cash_withdraw_amt").val(data.amount);
    $("#cash_withdraw_send_fee").text(data.send_fee);
    $("#cash_withdraw_total_amt").text(data.total_amount);

    if (data.check_amt) {
      swal({
        text: __.message.over_Asset,
        icon: "warning",
        button: __.message.ok
      });
    }
  }).fail(function () {
    console.log("error");
  });
} //코인 출금


function send_transaction() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var symbol = $("#this_symbol_hidden").val();
  var amt = $("#withdraw_amt").val();
  var address = $("#withdraw_check_address").val();

  if (symbol == 'USD') {
    swal({
      text: __.message.please_change_another_coin,
      icon: "warning",
      button: __.message.ok
    });
  } else if (address == '') {
    swal({
      text: __.message.please_input_addr,
      icon: "warning",
      button: __.message.ok
    });
  } else if (amt == '' || amt == 0) {
    swal({
      text: __.message.please_input_out_amt,
      icon: "warning",
      button: __.message.ok
    });
  } else {
    $.ajax({
      url: "/send_transaction",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        symbol: symbol,
        amt: amt,
        address: address
      },
      dataType: "JSON"
    }).done(function (data) {
      if (data.state == 'check_address') {
        swal({
          text: __.message.please_check_addr,
          icon: "warning",
          button: __.message.ok
        });
      } else if (data.state == 'over_balance') {
        swal({
          text: __.message.over_asset2,
          icon: "warning",
          button: __.message.ok
        });
      } else if (data.state == 'under_fee') {
        swal({
          text: __.message.over_asset3,
          icon: "warning",
          button: __.message.ok
        });
      } else if (data.state == 'success') {
        swal({
          title: __.message.withdraw_success,
          text: __.message.withdraw_wait_need_confirm,
          icon: "success",
          button: __.message.ok
        });
        $('.transac_header ul li[data-index=2]').addClass('active');
        $('.transac_header ul li[data-index!=2]').removeClass('active');
        $('.transac_con_wrap .toggle_con[data-index=2]').show();
        $('.transac_con_wrap .toggle_con[data-index!=2]').hide();
        all_refresh();
      } else if (data.state == 'success_now') {
        swal({
          text: __.message.complete_withdraw,
          icon: "success",
          button: __.message.ok
        });
        $('.transac_header ul li[data-index=2]').addClass('active');
        $('.transac_header ul li[data-index!=2]').removeClass('active');
        $('.transac_con_wrap .toggle_con[data-index=2]').show();
        $('.transac_con_wrap .toggle_con[data-index!=2]').hide();
        all_refresh();
      }
    }).fail(function () {
      console.log("error");
    });
  }
} // 자기지갑 보유내역 새로고침


function refresh_trans_wallet() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var total_holding;
  var holding_percent;
  var holding_balance;
  var holding_convert;
  var refresh_decimal;
  $.ajax({
    url: "/refresh_trans_wallet",
    type: "POST",
    data: {
      _token: CSRF_TOKEN
    },
    dataType: "JSON"
  }).done(function (data) {
    total_holding = data.total_holding;
    $("#holding_total_balance").text(numberWithCommas(total_holding.toFixed(0)));
    $.each(data.coin, function () {
      if (total_holding == 0) {
        holding_percent = 0;
      } else {
        holding_percent = this.balance * this.price / total_holding * 100;
      }

      if (this.api == 'krw') {
        refresh_decimal = 0;
        holding_balance = this.balance;
        holding_convert = this.balance * this.price_usd;
        $("#holding_convert_" + this.symbol).text(numberWithCommas(holding_convert.toFixed(this.decimal_usd)));
        $("#holding_balance_" + this.symbol).text(numberWithCommas(holding_balance.toFixed(refresh_decimal)));
        $("#holding_percent_" + this.symbol).text(holding_percent.toFixed(2) + "%");
      } else {
        refresh_decimal = 8;
        holding_balance = this.balance;
        holding_convert = this.balance * this.price;
        $("#holding_convert_" + this.symbol).text(numberWithCommas(holding_convert.toFixed(this.decimal_krw)));
        $("#holding_balance_" + this.symbol).text(numberWithCommas(holding_balance.toFixed(refresh_decimal)));
        $("#holding_percent_" + this.symbol).text(holding_percent.toFixed(2) + "%");
      }
    });
  }).fail(function () {
    console.log("error");
  });
}

function refresh_withdraw_history() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var symbol = $("#this_symbol_hidden").val();
  var str;
  var symbol_text;
  var status_text;
  var button_text;
  var date_at;
  $.ajax({
    url: "/refresh_withdraw_history",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      symbol: symbol
    },
    dataType: "JSON"
  }).done(function (data) {
    $("#transaction_list").empty();
    $("#cash_list").empty();
    $.each(data.transaction_list, function () {
      if (this.cointype == 'USD' || this.cointype == 'usd') {
        symbol_text = 'USDC';
      } else if (this.cointype == 'KRW' || this.cointype == 'krw') {
        date_at = this.updated.split(' '); //날짜 분리

        symbol_text = this.cointype;
        button_text = '';

        if (this.status == 'confirm') {
          status_text = __.message.trans_complete;
        } else if (this.status == 'deposite_request') {
          status_text = __.message.deposite_request;
          button_text = "<button onclick = 'cash_cancel(" + this.id + ",0);'>" + __.message.trans_cancel + "</button>";
        } else if (this.status == 'deposite_reject') {
          status_text = __.message.deposite_reject;
        } else if (this.status == 'deposite_cancel') {
          status_text = __.message.deposite_cancel;
        } else if (this.status == 'withdraw_request') {
          status_text = __.message.withdraw_request;
          button_text = "<button onclick = 'cash_cancel(" + this.id + ",1);'>" + __.message.trans_cancel + "</button>";
        } else if (this.status == 'withdraw_reject') {
          status_text = __.message.withdraw_reject;
        } else if (this.status == 'withdraw_cancel') {
          status_text = __.message.withdraw_cancel;
        }

        if (this.type == 'deposite') {
          str = '<tr class="in" data-kind="0">';
          str += '<td rowspan="2">' + __.message.deposit + '</td>';
        } else if (this.type == 'withdraw') {
          str = '<tr class="out" data-kind="1">';
          str += '<td rowspan="2">' + __.message.withdraw + '</td>';
        }

        str += '<td rowspan="2">';
        str += '<p>';
        str += '' + numberWithCommas(this.amount) + ' <span class="currency">' + symbol_text + '</span>';
        str += '</p></td>';
        str += '<td>' + status_text + button_text + '</td>';
        str += '</tr>';

        if (this.type == 'deposite') {
          str += '<tr>';
        } else if (this.type == 'withdraw') {
          str += '<tr>';
        }

        str += '<td class="data"> ' + date_at[0] + '';
        str += '<br>';
        str += '' + date_at[1] + ' </td>';
        str += '</tr>';
        $("#cash_list").append(str);
      } else {
        date_at = this.updated.split(' '); //날짜 분리

        symbol_text = this.cointype;

        if (this.type == 'withdraw' || this.type == 'out') {
          if (this.status == 'withdraw_request') {
            status_text = __.message.request_withdraw;
          } else if (this.status == 'withdraw_request_confirm') {
            status_text = __.message.wait_withdraw;
          } else if (this.status == 'withdraw_reject') {
            status_text = __.message.reject_withdraw;
          } else {
            status_text = __.message.complete_withdraw;
          }

          str = '<tr class="out" data-kind="0">';
          str += '<td rowspan="2">' + __.message.withdraw + '</td>';
          str += '<td>';
          str += '<p>';
          str += '' + this.total_amt + ' <span class="currency">' + symbol_text + '</span>';
          str += '</p></td>';
          str += '<td>' + status_text + '</td>';
          str += '</tr>';
          str += '<tr class="out">';
          str += '<td>';
          str += '<p>';
          str += '' + this.receiver_address + '';
          str += '<br>';

          if (this.type == 'withdraw' && this.status != 'withdraw_request') {
            str += '(<a href="#" target="_blank">' + this.tx_id + '</a>)';
          }

          str += '</p></td>';
          str += '<td class="data"> ' + date_at[0] + '';
          str += '<br>';
          str += '' + date_at[1] + ' </td>';
          str += '</tr>';
        } else if (this.type == 'receive' || this.type == 'in') {
          str = '<tr class="in" data-kind="1">';
          str += '<td rowspan="2">' + __.message.deposit + '</td>';
          str += '<td>';
          str += '<p>';
          str += '' + this.total_amt + ' <span class="currency">' + symbol_text + '</span>';
          str += '</p></td>';
          str += '<td>' + __.message.complete_deposit + '</td>';
          str += '</tr>';
          str += '<tr class="in">';
          str += '<td>';
          str += '<p>';
          str += '' + this.receiver_address + '';
          str += '<br>';

          if (this.type == 'withdraw') {
            str += '(<a href="#" target="_blank">' + this.tx_id + '</a>)';
          }

          str += '</p></td>';
          str += '<td class="data"> ' + date_at[0] + '';
          str += '<br>';
          str += '' + date_at[1] + ' </td>';
          str += '</tr>';
        }

        $("#transaction_list").append(str);
      }

      $('.posi_wrap').css('display', 'none');
    });
  }).fail(function () {
    console.log("error");
  });
}

function numberWithCommas(n) {
  var parts = n.toString().split(".");
  return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function update_erc_eth_balance() {
  $.ajax({
    url: "/refresh_erc_eth_balance",
    type: "POST",
    data: {
      _token: CSRF_TOKEN
    },
    dataType: "JSON"
  }).done(function (data) {//console.log(data);	
  }).fail(function () {
    console.log("error");
  });
}

function cash_deposite() {
  var amount = $('#cash_deposite').val();
  $.ajax({
    url: "/cash_deposite",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      amount: amount
    },
    dataType: "JSON"
  }).done(function (data) {
    if (data.status == 'error1') {
      swal({
        text: __.message.cash_deposite_error1,
        icon: "warning",
        button: __.message.ok
      });
    } else if (data.status == 'error2') {
      swal({
        text: __.message.cash_deposite_error2,
        icon: "warning",
        button: __.message.ok
      });
    } else {
      swal({
        text: __.message.cash_deposite_success,
        icon: "success",
        button: __.message.ok
      });
      all_refresh();
    }
  }).fail(function () {
    console.log("error");
  });
}

function cash_withdraw() {
  var amount = $('#cash_withdraw_amt').val();
  var symbol = $("#this_symbol_hidden").val();
  $.ajax({
    url: "/cash_withdraw",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      amount: amount,
      symbol: symbol
    },
    dataType: "JSON"
  }).done(function (data) {
    if (data.status == 'error1') {
      swal({
        text: __.message.cash_withdraw_error1,
        icon: "warning",
        button: __.message.ok
      });
    } else if (data.status == 'error2') {
      swal({
        text: __.message.cash_withdraw_error2,
        icon: "warning",
        button: __.message.ok
      });
    } else if (data.status == 'error2') {
      swal({
        text: __.message.cash_withdraw_error3,
        icon: "warning",
        button: __.message.ok
      });
    } else {
      swal({
        text: __.message.cash_withdraw_success,
        icon: "success",
        button: __.message.ok
      });
      all_refresh();
    }
  }).fail(function () {
    console.log("error");
  });
}

function cash_cancel(id, type) {
  $.ajax({
    url: "/cash_cancel",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      id: id,
      type: type
    },
    dataType: "JSON"
  }).done(function (data) {
    console.log(data);

    if (data.status == 'error') {
      swal({
        text: __.message.cash_cancel_error,
        icon: "warning",
        button: __.message.ok
      });
    } else {
      swal({
        text: __.message.cash_cancel_success,
        icon: "success",
        button: __.message.ok
      });
      all_refresh();
    }
  }).fail(function () {
    console.log("error");
  });
}

$('#my_coin').click(function () {
  if ($(this).prop("checked")) {
    $("#my_coin_table > tbody > tr").hide();
    var k = "poss";
    var temp = $("#my_coin_table > tbody > tr > td:nth-child(2):contains('poss')");
    temp.parent().show();
  } else {
    $("#my_coin_table > tbody > tr").show();
  }
}); //1주일, 2주일, 1개월 클릭시 달력 input box 에 삽입

function input_calendar_data(value) {
  var date_start = new Date();
  var date_end = new Date();
  date_start.setDate(date_start.getDate() - value); // value 값 전으로 날짜선택
  //시작날짜 yyyy-mm-dd 형식 만들기

  var start_year = date_start.getFullYear();
  var start_month = date_start.getMonth() + 1;
  var start_day = date_start.getDate();
  var date_s = start_year + '-' + start_month + '-' + start_day; //마지막날짜 yyyy-mm-dd 형식 만들기

  var end_year = date_end.getFullYear();
  var end_month = date_end.getMonth() + 1;
  var end_day = date_end.getDate();
  var date_e = end_year + '-' + end_month + '-' + end_day;
  $("#start_sch").val(date_s);
  $("#end_sch").val(date_e);
} //날짜를 통한 검색 조회


function search_date_history() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var start_date = $("#start_sch").val();
  var end_date = $("#end_sch").val();

  if (start_date == '' || end_date == '') {
    swal({
      text: __.message.start_date_end_date,
      icon: "warning",
      button: __.message.ok
    });
  } else {
    $.ajax({
      url: "/search_date_history",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        start_date: start_date,
        end_date: end_date
      },
      dataType: "JSON"
    }).done(function (data) {
      $('#history_lists').empty();
      $.each(data.historys, function () {
        str = '<tr class="trs_type-' + this.trade_type + '" name="' + this.coinname + '/' + this.symbol + '">';
        str += '<td>';
        str += '<p>';
        str += '<span>' + this.created_dt + '</span>';
        str += '</p></td>';
        str += '<td>';
        str += '<p>';
        str += '<span>' + this.currency + this.lang_market + '</span>';
        str += '</p>';
        str += '</td>';
        str += '<td class="coin_td"><img class="coin_symbol" src="/storage/image/homepage/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname + '</span><span class="coin_name_eng">' + this.api + '/' + this.currency + '</span></td>';
        str += '<td>';
        str += '<p>';
        str += '<span>' + this.trade_type_name + '</span>';
        str += '</p></td>';
        str += '<td>';
        str += '<div>';
        str += '<p>';
        str += '<span>' + this.contract_coin_amt + '</span>';
        str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
        str += '</p>';
        str += '</div>';
        str += '<div>';
        str += '<p>';
        str += '<span>' + this.coin_price + '</span>';
        str += '<span class="currency">&nbsp;' + this.currency + '</span>';
        str += '</p>';
        str += '</div>';
        str += '</td>';
        str += '<td>';
        str += '<div>';
        str += '<p>';
        str += '<span>' + this.total_price + '</span>';
        str += '<span class="currency">&nbsp;' + this.currency + '</span>';
        str += '</p>';
        str += '</div>';
        str += '<div>';
        str += '<p>';
        str += '<span>' + this.fee + '</span>';
        str += '<span class="currency">&nbsp;' + this.currency + '</span>';
        str += '</p>';
        str += '</div>';
        str += '</td>';
        str += '<td>';
        str += '<p>';
        str += '<span>' + this.calcul_price + '</span>';
        str += '<span class="currency">&nbsp;' + this.currency + '</span>';
        str += '</p></td>';
        str += '</tr>';
        $('#history_lists').append(str);
      });

      if (data.historys_count == 0) {
        str = '<tr class="none_transac"><td colspan="8"><i class="fas fa-exclamation-circle none_fas mr-1"></i>' + __.my_asset.property_sentence2 + '</td></tr>';
        $('#history_lists').append(str);
      }

      history_offset = 20;

      if (history_offset >= data.historys_count) {
        $("#show_more_btn_div").empty();
      } else {
        $("#show_more_btn_div").html('<button onclick = "show_more_history();"><i class="fal fa-plus"></i>' + __.my_asset.more + '</button>');
      }
    }).fail(function () {
      console.log("error");
    });
  }
} //거래내역 더보기 클릭


function show_more_history() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var start_date = $("#start_sch").val();
  var end_date = $("#end_sch").val();
  var str;

  if (start_date == '' || end_date == '') {
    input_calendar_data(30); //날짜 없을시 기본날짜 1달치

    start_date = $("#start_sch").val();
    end_date = $("#end_sch").val();
  }

  $.ajax({
    url: "/show_more_history",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      offset: history_offset,
      start_date: start_date,
      end_date: end_date
    },
    dataType: "JSON"
  }).done(function (data) {
    $('#txtFilter').val('');
    filter();
    $('#selectFilter').val('');
    selFilter();
    history_offset += 10;
    $.each(data.historys, function () {
      str = '<tr class="trs_type-' + this.trade_type + '" name="' + this.coinname + '/' + this.symbol + '">';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.created_dt + '</span>';
      str += '</p></td>';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.currency + this.lang_market + '</span>';
      str += '</p>';
      str += '</td>';
      str += '<td class="coin_td"><img class="coin_symbol" src="/storage/image/homepage/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname + '</span><span class="coin_name_eng">' + this.api + '/' + this.currency + '</span></td>';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.trade_type_name + '</span>';
      str += '</p></td>';
      str += '<td>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.contract_coin_amt + '</span>';
      str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_price + '</span>';
      str += '<span class="currency">&nbsp;' + this.currency + '</span>';
      str += '</p>';
      str += '</div>';
      str += '</td>';
      str += '<td>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.total_price + '</span>';
      str += '<span class="currency">&nbsp;' + this.currency + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.fee + '</span>';
      str += '<span class="currency">&nbsp;' + this.currency + '</span>';
      str += '</p>';
      str += '</div>';
      str += '</td>';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.calcul_price + '</span>';
      str += '<span class="currency">&nbsp;' + this.currency + '</span>';
      str += '</p></td>';
      str += '</tr>';
      $('#history_lists').append(str);
    });

    if (history_offset >= data.historys_count) {
      $("#show_more_btn_div").empty();
    }
  }).fail(function () {
    console.log("error");
  });
} //미체결 더보기 클릭


function show_more_not_concluded() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var str;
  $.ajax({
    url: "/show_more_not_concluded",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      offset: concluded_offset
    },
    dataType: "JSON"
  }).done(function (data) {
    concluded_offset += 10;
    $.each(data.wait_trades, function () {
      str = '<tr class="trs_type-' + this.type + '">';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.created_dt + '</span>';
      str += '</p></td>';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.currency + this.lang_market + '</span>';
      str += '</p>';
      str += '</td>';
      str += '<td class="coin_td"><img class="coin_symbol" src="/storage/image/homepage/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname + '</span><span class="coin_name_eng">' + this.api + '/' + this.currency + '</span></td>';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.type_name + '</span>';
      str += '</p></td>';
      str += '<td>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_price + '</span>';
      str += '<span class="currency">&nbsp;' + this.currency + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_amt_total + '</span>';
      str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<td>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_amt_finished + '</span>';
      str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_amt + '</span>';
      str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
      str += '</p>';
      str += '</div>';
      str += '</td>';
      str += '<td>';
      str += '<button class="status_btn" id="btc_cancel_request_' + this.id + '" data-id="' + this.id + '" onclick="myasset_trade_cancel(' + this.id + ')">';
      str += __.my_asset.cancel_trade;
      str += '</button></td>';
      str += '</tr>';
      $('#not_concluded_lists').append(str);
    });

    if (concluded_offset >= data.wait_trades_count) {
      $("#show_more_not_concluded_btn").empty();
    }
  }).fail(function () {
    console.log("error");
  });
}

function refresh_not_concluded() {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var str;
  $.ajax({
    url: "/refresh_not_concluded",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      offset: concluded_offset
    },
    dataType: "JSON"
  }).done(function (data) {
    concluded_offset = 20;
    $('#not_concluded_lists').empty();
    $.each(data.wait_trades, function () {
      str = '<tr class="trs_type-' + this.type + '">';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.created_dt + '</span>';
      str += '</p></td>';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.currency + this.lang_market + '</span>';
      str += '</p>';
      str += '</td>';
      str += '<td class="coin_td"><img class="coin_symbol" src="/storage/image/homepage/coin_img/' + this.api + '.png" alt="coin_img"><span class="coin_name">' + this.coinname + '</span><span class="coin_name_eng">' + this.api + '/' + this.currency + '</span></td>';
      str += '<td>';
      str += '<p>';
      str += '<span>' + this.type_name + '</span>';
      str += '</p></td>';
      str += '<td>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_price + '</span>';
      str += '<span class="currency">&nbsp;' + this.currency + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_amt_total + '</span>';
      str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<td>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_amt_finished + '</span>';
      str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
      str += '</p>';
      str += '</div>';
      str += '<div>';
      str += '<p>';
      str += '<span>' + this.coin_amt + '</span>';
      str += '<span class="currency">&nbsp;' + this.symbol + '</span>';
      str += '</p>';
      str += '</div>';
      str += '</td>';
      str += '<td>';
      str += '<button class="status_btn" id="btc_cancel_request_' + this.id + '" data-id="' + this.id + '" onclick="myasset_trade_cancel(' + this.id + ')">';
      str += __.my_asset.cancel_trade;
      str += '</button></td>';
      str += '</tr>';
      $('#not_concluded_lists').append(str);
    });

    if (concluded_offset >= data.wait_trades_count) {
      $("#show_more_not_concluded_btn").empty();
    }
  }).fail(function () {
    console.log("error");
  });
}

function myasset_trade_cancel(id) {
  swal({
    text: __.message.real_trade_cancel_confirm,
    icon: "warning",
    buttons: {
      yes: {
        text: __.message.yes,
        value: true
      },
      no: {
        text: __.message.no,
        value: false
      }
    }
  }).then(function (value) {
    if (value) {
      $.ajax({
        url: "/trade_cancel_new",
        type: "POST",
        data: {
          _token: CSRF_TOKEN,
          id: id
        },
        dataType: 'JSON',
        success: function success(data) {
          alertify.success(data.message);
          refresh_not_concluded();
        }
      });
    }
  });
}

var history_offset = 20; // 시작점

var concluded_offset = 20; // 시작점
//START 내 자산 > 거래내역 데이터 검색 pikaday plugin

var startDate,
    endDate,
    updateStartDate = function updateStartDate() {
  startPicker.setStartRange(startDate);
  endPicker.setStartRange(startDate);
  endPicker.setMinDate(startDate);
},
    updateEndDate = function updateEndDate() {
  startPicker.setEndRange(endDate);
  startPicker.setMaxDate(endDate);
  endPicker.setEndRange(endDate);
},
    startPicker = new Pikaday({
  field: document.getElementById('start_sch'),
  minDate: new Date(2017, 1, 1),
  maxDate: new Date(),
  onSelect: function onSelect() {
    startDate = this.getDate();
    updateStartDate();
  },
  format: 'YYYY-MM-D',
  toString: function toString(date, format) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    return "".concat(year, "-").concat(month, "-").concat(day);
  }
}),
    endPicker = new Pikaday({
  field: document.getElementById('end_sch'),
  minDate: new Date(2017, 1, 1),
  maxDate: new Date(),
  onSelect: function onSelect() {
    endDate = this.getDate();
    updateEndDate();
  },
  format: 'YYYY-MM-D',
  toString: function toString(date, format) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    return "".concat(year, "-").concat(month, "-").concat(day);
  }
}),
    _startDate = startPicker.getDate(),
    _endDate = endPicker.getDate();

if (_startDate) {
  startDate = _startDate;
  updateStartDate();
}

if (_endDate) {
  endDate = _endDate;
  updateEndDate();
} //END 내 자산 > 거래내역 데이터 검색 pikaday plugin

/*1:1 문의하기 list페이지의 문의하기 버튼누르면 모달팝업*/


$('#qnaWrite').click(function () {
  $('#qnaWrite1').css({
    display: 'block'
  }).animate({
    opacity: 1,
    top: 10 + '%'
  });
});
/*//1:1 문의하기 list페이지의 문의하기 버튼누르면 모달팝업*/

/*1:1 문의하기 view페이지의 수정하기 버튼누르면 모달팝업*/

$('#qnaModify').click(function () {
  $('#qnaModify1').css({
    display: 'block'
  }).animate({
    opacity: 1,
    top: 10 + '%'
  });
});
/*//1:1 문의하기 view페이지의 수정하기 버튼누르면 모달팝업*/

$('#pna_write').submit(function () {
  var is_title = $('input[name="title"]').val();
  var is_cont = $('textarea[name="description"]').val();

  if (is_title == '') {
    swal({
      text: __.message.is_title,
      icon: "warning",
      buttons: {
        yes: {
          text: __.message.yes,
          value: true
        }
      }
    });
    return false;
  }

  if (is_cont == '') {
    swal({
      text: __.message.is_cont,
      icon: "warning",
      buttons: {
        yes: {
          text: __.message.yes,
          value: true
        }
      }
    });
    return false;
  }

  return true;
});
/* 자주묻는문의 유형별로 보여주는 버튼 */

$('#faq_tab_list ul li').each(function (index) {
  $(this).attr('data-index', index);
});
$('#faq_tab_list ul li').click(function () {
  var i = $(this).data('index');

  if (i == 0) {
    $(".faq_tab").show();
  } else {
    var thisTit = $(this).children('a').text();
    $(".faq_tab").hide();
    $(".faq_tab:contains('" + thisTit + "')").show();
  }

  $(this).addClass('active');
  $('#faq_tab_list ul li').not(this).removeClass('active');
});
/* //자주묻는문의 유형별로 보여주는 버튼 */

/* p2p 구매신청/판매신청 버튼누르면 모달팝업*/

$(document).on('click', '.p2pApply', function () {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var id = $(this).data('id');
  console.log(id);
  $('#fullscreen_modal').fadeIn();
  $('.posi_wrap').css('display', '');
  $.ajax({
    url: "/p2p_ajax_test",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      id: id
    },
    dataType: "JSON"
  }).done(function (data) {
    console.log(data);

    if (data.type == 'buy') {
      $("#modal_title").html(__.ptop.apply_sell);
    } else {
      $("#modal_title").html(__.ptop.apply_buy);
    }

    $("input[name='p_id']").val(data.id);
    $("input[name='type']").val(data.type);
    $('#pop_name').text(data.name);
    $('#coin_name').text(data.coin_symbol);
    $('.modal_coin_type').text(data.coin_type);
    $('#modal_amount').text(data.coin_amount);
    $('#modal_price').text(data.coin_price);
    $('#country_money').text(data.country_money);

    if (data.country_money == "JPY") {
      $('.if_jp').css('display', 'block');
    } else {
      $('.if_jp').css('display', 'none');
    }

    $('#p2pApply1').css({
      display: 'block'
    }).animate({
      opacity: 1,
      top: 10 + '%'
    });
    $('.posi_wrap').css('display', 'none');
  }).fail(function () {
    console.log("error");
  });
});
/* //p2p 구매신청/판매신청 모달팝업*/

/* p2p 장외거래등록 모달팝업*/

$('.p2pWrite').click(function () {
  $('.posi_wrap').css('display', '');
  $('#p2pCreate').css({
    display: 'block'
  }).animate({
    opacity: 1,
    top: 10 + '%'
  });
  $('.posi_wrap').css('display', 'none');
});
/* // p2p 장외거래등록 모달팝업*/

/* 코인종류 선택시 수량의 코인이름 뜨기 */

$(document.body).on('change', "#selectBoxs", function (e) {
  //doStuff
  var optVal = $("#selectBoxs option:selected").val();
  $("#this_coin").val(optVal);
});
/* //코인종류 선택시 수량의 코인이름 뜨기 */

$(document.body).on('change', 'select[name="country_money"]', function (e) {
  var state = $('select[name="country_money"] option:selected').val();

  if (state == 'JPY') {
    console.log(state);
    $('.if_jp').css('display', 'block');
  } else {
    $('.if_jp').css('display', 'none');
  }
});
$('#p2p_history_more').click(function () {
  var offset = $('#p2p_history_wrap').data('offset');
  var count = $('#p2p_history_wrap').data('count');
  var from_date = $('input[name="from_date"]').val();
  var to_date = $('input[name="to_date"]').val();
  $('#txtFilter').val('');
  filter();
  $('#selectFilter').val('');
  selFilter();
  $.ajax({
    url: "/p2p/history/more",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      offset: offset,
      from_date: from_date,
      to_date: to_date
    },
    dataType: "JSON"
  }).done(function (data) {
    $('#p2p_history_tbl tbody').append(data.str);
    $('#p2p_history_wrap').attr('data-offset', data.offset);

    if (count <= data.offset) {
      $('#p2p_history_more').remove();
    }
  }).fail(function () {
    console.log("error");
  });
});

function more_p2p_list(type, auth) {
  var offset = $('#p2p_list_con').data('offset');
  var count = $('#p2p_list_con').data('count');
  var category = $('#select_p2p_list').val();
  var srch = $('#p2p_list_srch').val();

  if (count <= offset) {
    return false;
  }

  $.ajax({
    url: "/p2p/list/more",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      offset: offset,
      category: category,
      srch: srch,
      type: type,
      auth: auth
    },
    dataType: "JSON"
  }).done(function (data) {
    $('#p2p_list_con').append(data.str);
    $('#p2p_list_con').data('offset', data.offset);
  }).fail(function () {
    console.log("error");
  });
}

$("#p2p_apply").submit(function () {
  var is_addr_c = "input[name='coin_address']";
  var is_bank = "input[name='bank']";
  var is_account = "input[name='account']";

  if ($(is_addr_c).val() == '') {
    swal({
      text: __.message.is_coin_addr,
      icon: "warning",
      button: __.message.ok
    });
    $(is_addr_c).focus();
    return false;
  }

  if ($(is_bank).val() == '') {
    swal({
      text: __.message.is_bank,
      icon: "warning",
      button: __.message.ok
    });
    $(is_bank).focus();
    return false;
  }

  if ($(is_account).val() == '') {
    swal({
      text: __.message.is_account,
      icon: "warning",
      button: __.message.ok
    });
    $(is_account).focus();
    return false;
  }

  return true;
});
$("#p2p_create").submit(function () {
  var is_sel_c = "select[name='coin_type']";
  var is_amt_c = "input[name='coin_amount']";
  var is_sel_m = "select[name='country_money']";
  var is_amt_m = "input[name='coin_price']";
  var is_addr_c = "input[name='wt_coin_address']";
  var is_bank = "input[name='wt_bank']";
  var is_account = "input[name='wt_account']";
  var is_account_name = "input[name='wt_account_name']";
  var is_cont = "textarea[name='wt_cont']";

  if ($(is_sel_c).val() == '') {
    swal({
      text: __.message.is_sel_c,
      icon: "warning",
      button: __.message.ok
    });
    $(is_sel_c).focus();
    return false;
  }

  if ($(is_amt_c).val() == '') {
    swal({
      text: __.message.is_amt_c,
      icon: "warning",
      button: __.message.ok
    });
    $(is_amt_c).focus();
    return false;
  }

  if ($(is_sel_m).val() == '') {
    swal({
      text: __.message.is_sel_m,
      icon: "warning",
      button: __.message.ok
    });
    $(is_sel_m).focus();
    return false;
  }

  if ($(is_amt_m).val() == '') {
    swal({
      text: __.message.is_amt_m,
      icon: "warning",
      button: __.message.ok
    });
    $(is_amt_m).focus();
    return false;
  }

  if ($(is_addr_c).val() == '') {
    swal({
      text: __.message.is_coin_addr,
      icon: "warning",
      button: __.message.ok
    });
    $(is_addr_c).focus();
    return false;
  }

  if ($(is_bank).val() == '') {
    swal({
      text: __.message.is_bank,
      icon: "warning",
      button: __.message.ok
    });
    $(is_bank).focus();
    return false;
  }

  if ($(is_account_name).val() == '') {
    swal({
      text: __.message.is_name,
      icon: "warning",
      button: __.message.ok
    });
    $(is_account).focus();
    return false;
  }

  if ($(is_account).val() == '') {
    swal({
      text: __.message.is_account,
      icon: "warning",
      button: __.message.ok
    });
    $(is_account).focus();
    return false;
  }

  if ($(is_cont).val() == '') {
    swal({
      text: __.message.is_cont,
      icon: "warning",
      button: __.message.ok
    });
    $(is_cont).focus();
    return false;
  }

  return true;
});

function confirm_okay(pr_id) {
  swal({
    text: __.message.is_coin_in,
    icon: "warning",
    buttons: {
      yes: {
        text: __.message.yes,
        value: true
      },
      no: {
        text: __.message.no,
        value: false
      }
    }
  }).then(function (value) {
    if (value) {
      document.location.href = '/p2p_confirm/' + pr_id;
    }
  });
}

function confirm_cancel(pr_id) {
  swal({
    text: 'Are you sure you want to cancel?',
    icon: "warning",
    buttons: {
      yes: {
        text: __.message.yes,
        value: true
      },
      no: {
        text: __.message.no,
        value: false
      }
    }
  }).then(function (value) {
    if (value) {
      document.location.href = '/p2p_canceled/' + pr_id;
    }
  });
}

var min;
var sec;
var timer;
var timer2;
window.name = "Parent_window";
/*락리워드 락내역-배당내역 탭메뉴*/

$(".lock_history_table").each(function (index) {
  //순서값 배부
  $(this).attr("data-index", index);
});
$(".lock_reward_group .lock_history_tab ul li").each(function (index) {
  //순서값 배부
  $(this).attr("data-index", index);
}).click(function () {
  //누른 메뉴 꾸밈효과 주고, 아닌 탭은 꾸밈효과 지운다
  $(this).addClass("active");
  $(".lock_reward_group .lock_history_tab ul li").not(this).removeClass("active");
  var i = $(this).data("index"); //클릭한 순서값에 따라 매치되는 박스 보여주고 매치되지 않으면 숨긴다

  $(".lock_history_table[data-index=" + i + "]").show();
  $(".lock_history_table[data-index!=" + i + "]").hide();
});
$("#select_coin").change(function (e) {
  //코인 선택시 화면 이동
  window.location.href = "/mypage/lock_reward/" + $(e.target).val();
});
$("#btn_lock").click(function (e) {
  //락 버튼 클릭 시 값 체크, 실행여부 묻기
  return after_lock_coin_validate("lock") && confirm(__.myp.ask_lock);
});
$("#btn_unlock").click(function (e) {
  //락 버튼 클릭 시 값 체크, 실행여부 묻기
  return after_lock_coin_validate("unlock") && confirm(__.myp.ask_unlock);
});
$("#lock_history_view_more").click(function (e) {
  // 중복실행 방지
  var button = $(e.currentTarget);

  if (button.data("isRunning")) {
    return;
  }

  button.data("isRunning", true); // 락 내역 더보기 버튼 클릭 시 불러오기

  var page = button.data("next-page");
  var limit = button.data("limit");
  var coin = $('#lock_coin').val();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  $.ajax({
    url: "/history_view_more",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      page: page,
      limit: limit,
      coin: coin
    },
    dataType: "JSON"
  }).done(function (data) {
    var lockHistory = $('#lock_history tbody');
    data.lock_items.forEach(function (item) {
      var status = item.operation == 1 ? 'lock_status' : 'unlock_status';
      var operation = item.operation == 1 ? __.myp.lock : __.myp.unlock;
      $('<tr>').addClass(status).append($('<td>').text(operation)).append($('<td>').text(item.amount)).append($('<td>').text(item.created_dt)).appendTo(lockHistory);
    });

    if (data.lock_items_next_page > 0) {
      button.data({
        'next-page': data.lock_items_next_page,
        'isRunning': false
      });
    } else {
      $('#lock_history_view_more').hide();
    }
  }).fail(function () {
    console.log("error");
  });
});
$("#lock_dividend_view_more").click(function (e) {
  // 중복실행 방지
  var button = $(e.currentTarget);

  if (button.data("isRunning")) {
    return;
  }

  button.data("isRunning", true); // 배당 내역 더보기 버튼 클릭 시 불러오기

  var page = button.data("next-page");
  var limit = button.data("limit");
  var coin = $('#lock_coin').val();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  $.ajax({
    url: "/dividend_view_more",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      page: page,
      limit: limit,
      coin: coin
    },
    dataType: "JSON"
  }).done(function (data) {
    var lockDividend = $('#lock_dividend tbody');
    data.dividend_items.forEach(function (item) {
      $('<tr>').append($('<td>').text(item.amount)).append($('<td>').text(item.created_dt)).appendTo(lockDividend);
    });

    if (data.dividend_items_next_page > 0) {
      button.data({
        'next-page': data.dividend_items_next_page,
        'isRunning': false
      });
    } else {
      $('#lock_dividend_view_more').hide();
    }
  }).fail(function () {
    console.log("error");
  });
});

if ($("#amount_coin").length > 0) {
  setInputFilter($("#amount_coin")[0], function (value) {
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
      button: __.message.ok
    });
    return false;
  }

  if ((amount_input + ".").split(".")[1].length > 8) {
    swal({
      text: __.myp.too_many_points,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  var amount = parseFloat(amount_input);

  if (amount == NaN || amount_input.charAt(amount_input.length - 1) === ".") {
    swal({
      text: __.myp.wrong_value,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (amount <= 0) {
    swal({
      text: __.myp.not_zero_or_less,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (type === "lock") {
    if (amount > parseFloat(avaliable_amount)) {
      swal({
        text: __.myp.not_exceed_avail,
        icon: "warning",
        button: __.message.ok
      });
      return false;
    }
  } else if (type === "unlock") {
    if (amount > parseFloat(lock_amount)) {
      swal({
        text: __.myp.not_exceed_lock,
        icon: "warning",
        button: __.message.ok
      });
      return false;
    }
  }

  return true;
}

function setInputFilter(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
    textbox.addEventListener(event, function () {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
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
newPassword.keyup(function (e) {
  checkPasswordConfirm();
});
confirmPassword.keyup(function (e) {
  checkPasswordConfirm();
});

function checkPasswordConfirm() {
  if (newPassword.val() !== confirmPassword.val()) {
    pwYes.addClass('hide');
    pwNo.removeClass('hide');
  } else {
    pwYes.removeClass('hide');
    pwNo.addClass('hide');
  }
}

buttonPasswordChange.click(function () {
  return password_change_validate();
});

function password_change_validate() {
  var check = function check(text) {
    if (text === '') {
      swal({
        text: __.myp.require_password,
        icon: "warning",
        button: __.message.ok
      });
      return false;
    } else if (text.length < 8 || text.length > 12) {
      swal({
        text: __.myp.restrict_password,
        icon: "warning",
        button: __.message.ok
      });
      return false;
    }

    return true;
  };

  if (!check(currentPassword.val())) {
    currentPassword.val('').focus();
    return false;
  }

  if (!check(newPassword.val())) {
    newPassword.val('').focus();
    return false;
  }

  if (!check(confirmPassword.val())) {
    confirmPassword.val('').focus();
    return false;
  }

  if (newPassword.val() !== confirmPassword.val()) {
    swal({
      text: __.myp.not_match_password,
      icon: "warning",
      button: __.message.ok
    });
    newPassword.val('').focus();
    confirmPassword.val('');
    return false;
  }

  return true;
}
/*//비밀번호 변경 탭메뉴*/
// 보안인증 JS  //


$('#security_country').on('change', function () {
  var country = this.value;

  if (country == 'kr') {
    $("#mobile_number").attr("onclick", "fnPopup();");
    $("#mobile_number").attr("readonly", true);
    $("#mobile_number").attr("placeholder", "클릭해주세요.");
  } else {
    $("#mobile_number").attr("onclick", "");
    $("#mobile_number").attr("readonly", false);
    $("#mobile_number").attr("placeholder", "Phone Number");
  }
});

function fnPopup() {
  window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
  document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
  document.form_chk.target = "popupChk";
  document.form_chk.submit();
}

function nicecheck_alert(status, messages) {
  if (status == 0) {
    swal({
      text: messages,
      icon: "warning",
      button: __.message.ok
    });
  } else {
    swal({
      text: __.message.complete_sms_certify,
      icon: "success",
      button: __.message.ok
    });
  }

  location.reload();
}

$('#sms_certify').click(function () {
  var country = $('select[name="country"]').val();
  var mobile_number = $('input[name="mobile_number"]').val();

  if ($(this).hasClass('active') && $('input[name="mobile_cv"]').val() == 1) {
    swal({
      text: __.message.already_confirm_mobile,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (country == '' || country == null) {
    swal({
      text: __.message.please_select_country,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (mobile_number == '') {
    swal({
      text: __.message.please_input_mobile,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  $.ajax({
    url: "/mobile/verify",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      mobile_number: mobile_number,
      country: country
    },
    dataType: 'JSON',
    success: function success(data) {
      if (data.yn) {
        swal({
          text: __.message.send_sms_certify_code,
          icon: "success",
          button: __.message.ok
        });
        clearInterval(timer);
        $('#sms_certify').text(__.message.resend);
        min = 3;
        sec = min * 60 % 60;
        $('#mobile_number_code').attr('readonly', false);
        timer = setInterval("start_timer()", 1000);
      } else {
        swal({
          text: __.message.already_exist_mobile,
          icon: "warning",
          button: __.message.ok
        });
      }
    }
  });
}); // 회원가입 폰인증번호 입력하면 색깔 변해서 입력한 티 나게 하기

$('#mobile_number_code').keyup(function () {
  if ($(this).val() == '') {
    $('#sms_certify_confirm').removeClass('active');
  } else {
    $('#sms_certify_confirm').addClass('active');
  }
}); // end

$('#sms_certify_confirm').click(function () {
  var certify_code = $('input[name="mobile_certify_code"]').val();
  var country = $('select[name="country"]').val();
  var mobile_number = $('input[name="mobile_number"]').val();

  if ($(this).hasClass('active') && $('input[name="mobile_cv"]').val() == 1) {
    swal({
      text: __.message.already_confirm_mobile,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (certify_code == '') {
    swal({
      text: __.message.please_input_mobile_certify,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  $.ajax({
    url: "/mobile/verify/confirm",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      certify_code: certify_code,
      mobile_number: mobile_number,
      country: country
    },
    dataType: 'JSON',
    success: function success(data) {
      if (data.yn) {
        clearInterval(timer);
        $('#sms_certify_confirm').html('<i class="fal fa-check"></i>' + __.message.complete_certify);
        $('#sms_certify_confirm').addClass('active');
        $('#mobile_number_code').attr('readonly', 'readonly');
        $('input[name="mobile_cv"]').val(1);
        swal({
          text: __.message.complete_sms_certify,
          icon: "success",
          button: [__.message.ok, true]
        }).then(function (value) {
          location.href = '/home';
        });
      } else {
        swal({
          text: __.message.wrong_sms_certify,
          icon: "warning",
          button: __.message.ok
        });
      }
    }
  }); ///mobile/verify/confirm
});
$('#security_setting_document').on('submit', function () {
  if ($('#thum_file').val() == '') {
    swal({
      text: __.boan.require_document1_value,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  } else if ($('#thum_file2').val() == '') {
    swal({
      text: __.boan.require_document2_value,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  return true;
});
$('#security_setting_account').on('submit', function () {
  if ($('#account_num').val() == '') {
    swal({
      text: __.boan.require_account1_value,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  } else if ($('#account_bank').val() == '') {
    swal({
      text: __.boan.require_account2_value,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  } else if ($('#thum_file').val() == '') {
    swal({
      text: __.boan.require_account3_value,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  } else if ($('#thum_file2').val() == '') {
    swal({
      text: __.boan.require_account4_value,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  return true;
});

function start_timer() {
  var temp_chr1;
  var temp_chr2;

  if (sec == 0) {
    sec = 59;
    min -= 1;
  } else {
    sec -= 1;
  }

  temp_chr1 = min.toString();
  temp_chr2 = sec.toString();

  if (temp_chr1.length == 1) {
    temp_chr1 = '0' + temp_chr1;
  }

  if (temp_chr2.length == 1) {
    temp_chr2 = '0' + temp_chr2;
  }

  $('#ViewTimer').text(temp_chr1 + ':' + temp_chr2);

  if (min == 0 && sec == 0) {
    clearInterval(timer);
    $('#sms_certify_confirm').removeClass('active');
    $('input[name="mobile_certify_code"]').attr('readonly', 'readonly');
  }
}
/*쓰기버튼누르면 모달팝업*/


$('.write_btn').click(function () {
  $('#fullscreen_modal').fadeIn();
});
$('.modal_popup .btn_style.cancel_btn').click(function () {
  $('#fullscreen_modal').fadeOut();
  $('.modal_popup').animate({
    top: 100 + '%',
    opacity: 0
  });
});
/*//쓰기버튼누르면 모달팝업*/

/* p2p_ico 플로팅네비게이션 */

var floatPosition = parseInt($('#floating_nav').css('top'));
$(window).scroll(function () {
  var scrollTop = $(window).scrollTop();
  var nowscrTop = $(this).scrollTop();
  var newPosition = scrollTop + floatPosition;

  if (nowscrTop < 60) {
    $('#floating_nav').stop().animate({
      top: 0
    }, 500);
  } else {
    $('#floating_nav').stop().animate({
      top: newPosition - 70
    }, 500);
  }
}).scroll();
$('#email_certify').click(function () {
  var email = $('input[name="email"]').val();

  if (email == '') {
    swal({
      text: __.message.not_email_addr_input,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if ($(this).hasClass('active') && $('input[name="email_cv"]').val() == 1) {
    swal({
      text: __.message.already_confirm_email,
      icon: "warning",
      button: __.message.ok
    });
  } else {
    $.ajax({
      url: "/email/duplicate",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        email: email
      },
      dataType: 'JSON',
      success: function success(data) {
        if (data.yn) {
          swal({
            text: __.message.already_exist_email,
            icon: "warning",
            button: __.message.ok
          });
          $('input[name="email"]').val('');
        } else {
          swal({
            text: __.message.possible_email_addr,
            icon: "success",
            buttons: {
              yes: {
                text: __.message.yes,
                value: true
              },
              no: {
                text: __.message.no,
                value: false
              }
            },
            dangerMode: true
          }).then(function (value) {
            if (value) {
              $('input[name="email_cv"]').val(1);
              $('#email_certify').addClass('active');
              $('#email_certify').html('<i class="fal fa-check"></i>' + __.message.possible_use);
              $('input[name="email"]').attr('readonly', 'readonly');
            } else {
              $('input[name="email"]').val('');
            }
          });
        }
      }
    });
  }
});
$('#password').keyup(function () {
  var password_confirm = $('#password-confirm').val();
  var password = $(this).val();

  if (password == '' || password_confirm == '') {
    $('.register_pw_alert .pw_yes').addClass('hide');
    $('.register_pw_alert .pw_no').addClass('hide');
    return false;
  }

  if (password == password_confirm) {
    $('.register_pw_alert .pw_yes').removeClass('hide');
    $('.register_pw_alert .pw_no').addClass('hide');
  } else {
    $('.register_pw_alert .pw_no').removeClass('hide');
    $('.register_pw_alert .pw_yes').addClass('hide');
  }
});
$('#password-confirm').keyup(function () {
  var password_confirm = $(this).val();
  var password = $('#password').val();

  if (password == '' || password_confirm == '') {
    $('.register_pw_alert .pw_yes').addClass('hide');
    $('.register_pw_alert .pw_no').addClass('hide');
    return false;
  }

  if (password == password_confirm) {
    $('.register_pw_alert .pw_yes').removeClass('hide');
    $('.register_pw_alert .pw_no').addClass('hide');
  } else {
    $('.register_pw_alert .pw_no').removeClass('hide');
    $('.register_pw_alert .pw_yes').addClass('hide');
  }
});
$('.register_auth_wrap #password').change(function () {
  if (!chkPwd($.trim($('#password').val()))) {
    $('#password').val('');
    $('mpassword').focus();
    return false;
  }
});
$('#username_certify').click(function () {
  var username = $('input[name="username"]').val();

  if (username == '') {
    swal({
      text: __.message.please_input_username,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if ($(this).hasClass('active') && $('input[name="username_cv"]').val() == 1) {
    swal({
      text: __.message.already_confirm_username,
      icon: "warning",
      button: __.message.ok
    });
  } else {
    $.ajax({
      url: "/username/duplicate",
      type: "POST",
      data: {
        _token: CSRF_TOKEN,
        username: username
      },
      dataType: 'JSON',
      success: function success(data) {
        if (data.yn) {
          swal({
            text: __.message.already_exist_username,
            icon: "warning",
            button: __.message.ok
          });
          $('input[name="username"]').val('');
        } else {
          swal({
            text: __.message.possible_username,
            icon: "success",
            buttons: {
              yes: {
                text: __.message.yes,
                value: true
              },
              no: {
                text: __.message.no,
                value: false
              }
            },
            dangerMode: true
          }).then(function (value) {
            if (value) {
              $('input[name="username_cv"]').val(1);
              $('#username_certify').addClass('active');
              $('#username_certify').html('<i class="fal fa-check"></i>' + __.message.possible_use);
              $('input[name="username"]').attr('readonly', 'readonly');
            } else {
              $('input[name="username"]').val('');
            }
          });
        }
      }
    });
  }
});
$('#register_form').submit(function () {
  var email_cv = $('input[name="email_cv"]').val();
  var country = $('select[name="country"]').val();

  if (email_cv != 1) {
    swal({
      text: __.message.not_certify_email,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (country == '') {
    swal({
      text: __.message.please_select_country,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }
});

function chkPwd(str) {
  var reg_pwd = /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;

  if (!reg_pwd.test(str)) {
    swal({
      text: '영문, 숫자를 혼합하여\n8자리 ~ 20자리 이내로 입력해주세요.',
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  return true;
}
/* 혜택 추가버튼 */


var bnf_i = 3;
$(document).ready(function () {
  var coin_p = $('#get_ico_coin_p').text();
  $('#up_btn').click(function () {
    var this_var = parseFloat($(this).siblings('input').val());
    var price = parseFloat(value_up_btn(this_var));
    $(this).siblings('input').val(price);
    $(this).siblings('input').attr('value', price);
    $('#change_price').text(parseFloat(price / coin_p).toFixed(8));
  });
  $('#down_btn').click(function () {
    if ($(this).siblings('input').val() > 0) {
      var this_var = parseFloat($(this).siblings('input').val());
      var price = parseFloat(value_down_btn(this_var));
      $(this).siblings('input').val(price);
      $(this).siblings('input').attr('value', price);
      $('#change_price').text(parseFloat(price / coin_p).toFixed(8));
    } else {
      swal({
        text: __.message.is_over_zero,
        icon: "warning",
        button: __.message.ok
      });
    }
  });
  $('#ico_total_buy').on("change", function () {
    $('#change_price').val(($('#ico_total_buy').val() / coin_p).toFixed(8));
    $('#change_price').text(($('#ico_total_buy').val() / coin_p).toFixed(8));
  });
  $('.not_active_btn').prop("onclick", null).off("click");
});
$('#bnf_plus_btn').click(function () {
  bnf_i++;
  var plusP = '<p class="form_align benefit_p">' + '<label class="tit"></label>' + '<input type="text" name="benefit[]" id="benefit_' + bnf_i + '" class="form-control" placeholder="' + __.icoo.benefits_msg_n + bnf_i + __.icoo.benefits_msg_v + '"></p>';
  var pHtml = $(' .form_align.benefit_p:last ');
  pHtml.after(plusP);

  if (bnf_i == 10) {
    $(this).hide();
  }
});
/* //혜택 추가버튼 */

/* 관련기사 및 인터뷰 추가버튼 */

var news_i = 3;
$('#news_plus_btn').click(function () {
  news_i++;
  var plusTr = '<tr class="subject_form">' + '<th>' + __.icoo.Arti_inter + news_i + '</th>' + '<td><input type="text" name="news_name[]" id="subject_' + news_i + '" class="form-control" placeholder="' + __.icoo.inter_title + '"></td>' + '</tr>' + '<tr class="url_form" name="newsline">' + '<th></th>' + '<td><input type="text" name="news_url[]" id="url_' + news_i + '" class="form-control" placeholder="URL ' + __.icoo.aff + '"></td>' + '</tr>';
  var trHtml = $("tr[name=newsline]:last");
  trHtml.after(plusTr);

  if (news_i == 10) {
    $(this).hide();
  }
});
/* //관련기사 및 인터뷰 추가버튼 */

var tp_title = document.getElementById('tp_tit');
/* 제목 입력 Byte 수 제한 */

if (tp_title) {
  var count = function count(message) {
    var totalByte = 0;

    for (var index = 0, length = message.length; index < length; index++) {
      var currentByte = message.charCodeAt(index);
      currentByte > 128 ? totalByte += 2 : totalByte++;
    }

    return totalByte;
  };

  var checkByte = function checkByte(event) {
    var totalByte = count(event.target.value);

    if (totalByte <= MAX_MESSAGE_BYTE) {
      countSpan.innerText = totalByte.toString();
      message = event.target.value;
    } else {
      swal({
        text: MAX_MESSAGE_BYTE + __.message.is_available_byte,
        icon: "warning",
        button: __.message.ok
      });
      countSpan.innerText = count(message).toString();
      event.target.value = message;
    }
  };

  tp_title.addEventListener('keyup', checkByte);
  var countSpan = document.getElementById('pre_char');
  var message = '';
  var MAX_MESSAGE_BYTE = 40;
  document.getElementById('max_char').innerHTML = MAX_MESSAGE_BYTE.toString();
}
/* 제목 입력 Byte 수 제한 */

/* ICO소개 입력 Byte 수 제한 */


var ico_intro = document.getElementById('ico_intro');

if (ico_intro) {
  var _count = function _count(ico_message) {
    var ico_totalByte = 0;

    for (var index = 0, length = ico_message.length; index < length; index++) {
      var currentByte = ico_message.charCodeAt(index);
      currentByte > 128 ? ico_totalByte += 2 : ico_totalByte++;
    }

    return ico_totalByte;
  };

  var ico_checkByte = function ico_checkByte(ico_event) {
    var ico_totalByte = _count(ico_event.target.value);

    if (ico_totalByte <= ICO_MAX_MESSAGE_BYTE) {
      ico_countSpan.innerText = ico_totalByte.toString();
      ico_message = ico_event.target.value;
    } else {
      swal({
        text: ICO_MAX_MESSAGE_BYTE + __.message.is_available_byte,
        icon: "warning",
        button: __.message.ok
      });
      ico_countSpan.innerText = _count(ico_message).toString();
      ico_event.target.value = ico_message;
    }
  };

  ico_intro.addEventListener('keyup', ico_checkByte);
  var ico_countSpan = document.getElementById('ico_pre');
  var ico_message = '';
  var ICO_MAX_MESSAGE_BYTE = 100;
  document.getElementById('ico_max').innerHTML = ICO_MAX_MESSAGE_BYTE.toString();
}
/* input box 작성 시 코인가격 변경 */


$('#ico_coin_p').on("change", function () {
  $('#c_price').val($('#ico_coin_p').val());
  $('#c_price').text($('#ico_coin_p').val().toUpperCase());
});
/**/

/*  input box 작성 시 코인명 변경 */

$('#coin_symbol').on("change", function () {
  $('.this_symbol').val($('#coin_symbol').val());
  $('.this_symbol').text($('#coin_symbol').val().toUpperCase());
});
/*  input box 작성 시 코인명 변경 */

/* select 시 코인명 변경 */

$('#collect_coin').on("change", function () {
  $('.collect_symbol').val($('#collect_coin').val());
  $('.collect_symbol').text($('#collect_coin').val());
});
/* select 시 코인명 변경 */

/* 파일명 추출 */

$('input[type="file"]').change(function () {
  if ($(this).hasClass('pdf_up')) {
    var $check = $(this);
    var thumbext = this.value.slice(this.value.indexOf(".") + 1).toLowerCase();

    if (thumbext != "pdf") {
      //확장자를 확인합니다.
      swal({
        text: __.message.is_pdf,
        icon: "warning",
        button: __.message.ok
      });
      $check.val('');
      return false;
    }
  } else if ($(this).hasClass('img_up')) {
    var $check = $(this);
    var thumbext = this.value.slice(this.value.indexOf(".") + 1).toLowerCase();

    if (thumbext != "jpg" && thumbext != "png") {
      //확장자를 확인합니다.
      swal({
        text: __.message.is_img,
        icon: "warning",
        button: __.message.ok
      });
      $check.val('');
      return false;
    }
  } else {
    swal({
      text: __.message.is_wrong,
      icon: "warning",
      button: __.message.ok
    });
  }

  FileName(this, 0);
});

function FileName(x, y) {
  if (window.FileReader) {
    // modern browser 
    var thisVal = $(x)[y].files[y].name;
  } else {
    // old IE 
    var thisVal = $(x).val().split('/').pop().split('\\').pop(); // 파일명만 추출 
  }

  $(x).siblings('.filename_input').val(thisVal);
}
/* 시간설정 플러그인 */


$('.start_timepicker').timepicker({
  timeFormat: 'h:mm p',
  interval: 60,
  minTime: '0',
  maxTime: '11:00pm',
  defaultTime: '09',
  startTime: '10:00',
  dynamic: false,
  dropdown: true,
  scrollbar: true
});
$('.end_timepicker').timepicker({
  timeFormat: 'h:mm p',
  interval: 60,
  minTime: '0',
  maxTime: '11:00pm',
  defaultTime: '00',
  startTime: '10:00',
  dynamic: false,
  dropdown: true,
  scrollbar: true
});
/* //시간설정 플러그인 */

$('#create_form').submit(function () {
  //e.preventDefault();
  return func();
});

function func() {
  var obj = document.allform;
  /**/

  console.log(obj);

  if (obj.title.value == '') {
    swal({
      text: __.message.is_title,
      icon: "warning",
      button: __.message.ok
    });
    obj.title.focus();
    return false;
  }

  if (obj.thumnail.value == '') {
    swal({
      text: __.message.is_thumnail,
      icon: "warning",
      button: __.message.ok
    });
    obj.file_name.focus();
    return false;
  }

  if (obj.intro.value == '') {
    swal({
      text: __.message.is_intro,
      icon: "warning",
      button: __.message.ok
    });
    obj.intro.focus();
    return false;
  }

  if (obj.coin_name.value == '') {
    swal({
      text: __.message.is_coin_name,
      icon: "warning",
      button: __.message.ok
    });
    obj.coin_name.focus();
    return false;
  }

  if (obj.symbol.value == '') {
    swal({
      text: __.message.is_coin_symbol,
      icon: "warning",
      button: __.message.ok
    });
    obj.symbol.focus();
    return false;
  }

  if (obj.collect.value == '') {
    swal({
      text: __.message.is_coin_collect,
      icon: "warning",
      button: __.message.ok
    });
    obj.collect.focus();
    return false;
  }

  if (obj.ico_coin_p.value == '') {
    swal({
      text: __.message.is_coin_price,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_coin_p.focus();
    return false;
  }

  if (obj.ico_min.value == '') {
    swal({
      text: __.message.is_coin_min,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_min.focus();
    return false;
  }

  if (obj.ico_goal.value == '') {
    swal({
      text: __.message.is_goal_price,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_goal.focus();
    return false;
  }

  if (obj.ico_from.value == '' || obj.ico_from_h.value == '') {
    swal({
      text: __.message.is_ico_from,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_from.focus();
    return false;
  }

  if (obj.ico_to.value == '' || obj.ico_to_h.value == '') {
    swal({
      text: __.message.is_ico_to,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_to.focus();
    return false;
  }

  if (obj.ico_use.value == '') {
    swal({
      text: __.message.is_ico_use,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_use.focus();
    return false;
  }

  if (obj.ico_tech.value == '') {
    swal({
      text: __.message.is_ico_tech,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_tech.focus();
    return false;
  }

  if (obj.ico_url.value == '') {
    swal({
      text: __.message.is_ico_url,
      icon: "warning",
      button: __.message.ok
    });
    obj.ico_url.focus();
    return false;
  }

  return true;
} //START 내 자산 > 거래내역 데이터 검색 pikaday plugin


var ico_startDate,
    ico_endDate,
    updateStartDate = function updateStartDate() {
  ico_startPicker.setStartRange(ico_startDate);
  ico_endPicker.setStartRange(ico_startDate);
  ico_endPicker.setMinDate(ico_startDate);
},
    updateEndDate = function updateEndDate() {
  ico_startPicker.setEndRange(ico_endDate);
  ico_startPicker.setMaxDate(ico_endDate);
  ico_endPicker.setEndRange(ico_endDate);
},
    ico_startPicker = new Pikaday({
  field: document.getElementById('start_sch_ico'),
  minDate: new Date(),
  maxDate: new Date(2020, 1, 1),
  onSelect: function onSelect() {
    ico_startDate = this.getDate();
    updateStartDate();
  },
  format: 'YYYY-MM-D',
  toString: function toString(date, format) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    return "".concat(year, "-").concat(month, "-").concat(day);
  }
}),
    ico_endPicker = new Pikaday({
  field: document.getElementById('end_sch_ico'),
  minDate: new Date(),
  maxDate: new Date(2020, 1, 1),
  onSelect: function onSelect() {
    ico_endDate = this.getDate();
    updateEndDate();
  },
  format: 'YYYY-MM-D',
  toString: function toString(date, format) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    return "".concat(year, "-").concat(month, "-").concat(day);
  }
}),
    _ico_startDate = ico_startPicker.getDate(),
    _ico_endDate = ico_endPicker.getDate();

if (_ico_startDate) {
  ico_startDate = _ico_startDate;
  updateStartDate();
}

if (_ico_endDate) {
  ico_endDate = _ico_endDate;
  updateEndDate();
}
/*
$('#create_form').submit(function(e){
    e.preventDefault();

    return func();
});

function func(){
*/


function func_buy() {
  var obj = document.ico_buy;
  var check1 = $('.grayCheckbox:checked').length; //첫번째 변수에 동의했는지 확인

  var get_min_buy = $('#min_buy').text().split(' ');
  var get_buyer_remain = $('#my_all_asset').text().split(' ');
  var ico_seller_remain = parseFloat($('input[name="ico_remain"]').val());
  var ico_buyer_remain = parseFloat(get_buyer_remain[0]);
  var ico_total_buy = parseFloat($('#ico_total_buy').val());
  var min_buy = parseFloat(get_min_buy[0]);

  if (ico_total_buy == null) {
    swal({
      text: __.message.is_total_buy,
      icon: "warning",
      button: __.message.ok
    });
    $('#ico_total_buy').focus();
    return false;
  }

  if (check1 == 0) {
    swal({
      text: __.message.is_ico_checked,
      icon: "warning",
      button: __.message.ok
    });
    obj.info_agree.focus();
    return false;
  }

  if (ico_total_buy = '') {
    swal({
      text: __.message.is_wrong_buy0 + '1',
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (ico_total_buy > ico_buyer_remain) {
    swal({
      text: __.message.is_wrong_buy0,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (ico_seller_remain < ico_total_buy) {
    swal({
      text: __.message.is_wrong_buy1,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (ico_total_buy < min_buy) {
    swal({
      text: __.message.is_wrong_buy2,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  $("#ico_buy_form").submit();
}

function value_up_btn(value) {
  return (parseFloat(value) + 0.01).toFixed(2);
}

function value_down_btn(value) {
  return (parseFloat(value) - 0.01).toFixed(2);
}

$('.info li button').click(function () {
  var coin_p = $('#get_ico_coin_p').text();
  var per = $(this).attr('id').split('_'); //per[1];

  $('#is_percent').text(per[1]);
  var asset = $('#my_all_asset').text().split(' '); //asset[0];

  if (asset[0] != 0) {
    console.log(asset[0]);
    var my_per = parseFloat(asset[0]) * parseFloat(0.01) * parseFloat(per[1]);
    $('#ico_total_buy').val(my_per);
    $('#ico_total_buy').attr('value', my_per);
    $('#change_price').text((my_per / coin_p).toFixed(8));
  } else {
    swal({
      text: __.message.is_no_money,
      icon: "warning",
      button: __.message.ok
    });
    $('#is_percent').text(0);
  }
});
var min;
var sec;
var timer;
var timer2;
$('#existing_user_sms_certify').click(function () {
  var country = $('select[name="country"]').val();
  var mobile_number = $('input[name="mobile_number"]').val();
  var duplicate_confirm = $('input[name="duplicate_confirm"]').val();

  if (country == '' || country == null) {
    swal({
      text: __.message.please_select_country,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  if (mobile_number == '') {
    swal({
      text: __.message.please_input_mobile,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  $.ajax({
    url: "/mobile/existing_user_verify",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      mobile_number: mobile_number,
      country: country,
      duplicate_confirm: duplicate_confirm
    },
    dataType: 'JSON',
    success: function success(data) {
      if (data.yn) {
        alertify.success(__.message.send_sms_certify_code);
        clearInterval(timer);
        $('#sms_certify').text(__.message.resend);
        min = 3;
        sec = min * 60 % 60;
        $('#mobile_number_code').attr('readonly', false);
        timer = setInterval("security_lv_start_timer()", 1000);
      } else {
        swal({
          text: __.message.already_exist_mobile,
          icon: "warning",
          button: __.message.ok
        });
      }
    }
  });
});
$('#existing_user_sms_certify_confirm').click(function () {
  var certify_code = $('input[name="mobile_certify_code"]').val();

  if (certify_code == '') {
    swal({
      text: __.message.please_input_mobile_certify,
      icon: "warning",
      button: __.message.ok
    });
    return false;
  }

  $.ajax({
    url: "/mobile/existing_user_verify/confirm",
    type: "POST",
    data: {
      _token: CSRF_TOKEN,
      certify_code: certify_code
    },
    dataType: 'JSON',
    success: function success(data) {
      if (data.yn) {
        clearInterval(timer);
        $('#existing_user_sms_certify_confirm').html('<i class="fal fa-check"></i>' + __.message.complete_certify);
        $('#existing_user_sms_certify_confirm').addClass('active');
        $('#mobile_number_code').attr('readonly', 'readonly');
        swal({
          text: __.message.complete_sms_certify,
          icon: "success",
          button: __.message.ok
        }).then(function () {
          window.location.href = '/';
        });
      } else {
        swal({
          text: __.message.wrong_sms_certify,
          icon: "warning",
          button: __.message.ok
        });
      }
    }
  });
});

function security_lv_start_timer() {
  var temp_chr1;
  var temp_chr2;

  if (sec == 0) {
    sec = 59;
    min -= 1;
  } else {
    sec -= 1;
  }

  temp_chr1 = min.toString();
  temp_chr2 = sec.toString();

  if (temp_chr1.length == 1) {
    temp_chr1 = '0' + temp_chr1;
  }

  if (temp_chr2.length == 1) {
    temp_chr2 = '0' + temp_chr2;
  }

  $('#ViewTimer').text(temp_chr1 + ':' + temp_chr2);

  if (min == 0 && sec == 0) {
    clearInterval(timer);
    $('#sms_certify_confirm').removeClass('active');
    $('input[name="mobile_certify_code"]').attr('readonly', 'readonly');
  }
}
