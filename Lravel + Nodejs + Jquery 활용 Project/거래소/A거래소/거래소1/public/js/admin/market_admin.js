(function ($) {
  "use strict"; // Start of use strict
  // Toggle the side navigation

  $("#sidebarToggle").on('click', function (e) {
    e.preventDefault();
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
  }); // Prevent the content wrapper from scrolling when the fixed side navigation hovered over

  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
          delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  }); // Scroll to top button appear

  $(document).on('scroll', function () {
    var scrollDistance = $(this).scrollTop();

    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  }); // Smooth scrolling using jQuery easing

  $(document).on('click', 'a.scroll-to-top', function (event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: $($anchor.attr('href')).offset().top
    }, 1000, 'easeInOutExpo');
    event.preventDefault();
  });
})(jQuery); // End of use strict
// 파일 첨부됐을 때 파일명 보이게하기


$('input[type="file"]').change(function () {
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
} //end 파일 첨부됐을 때 파일명 보이게하기


$('#register_agree').submit(function () {
  var register_agree1 = $('input[name="register_agree1"]').is(":checked");
  var register_agree2 = $('input[name="register_agree2"]').is(":checked");
  var register_agree3 = $('input[name="register_agree3"]').is(":checked");

  if (register_agree1 && register_agree2) {
    $('input[name="register_agree1"]').val(1);
    $('input[name="register_agree2"]').val(1);

    if (register_agree3) {
      $('input[name="register_agree3"]').val(1);
    }

    return true;
  } else {
    $.alert({
      title: '회원가입',
      content: '필수 동의 사항에 동의하지 않으셨습니다.'
    });
    console.log('false');
    return false;
  }
});
$(document).ready(function () {
  var check_ajax = true;
  $("select[name='status']").change(function () {
    var status = $(this).val();
    var id = $(this).data('id');

    if (confirm('정말 해당 회원의 상태를 수정하시겠습니까?')) {
      $.ajax({
        url: '/admin/user_status_change',
        type: 'POST',

        /* send the csrf-token and the input to the controller */
        data: {
          _token: CSRF_TOKEN,
          status: status,
          id: id
        },
        dataType: 'JSON',

        /* remind that 'data' is the response of the AjaxController */
        success: function success(data) {
          if (data.status == 1) {
            alert('해당 회원의 계정 사용을 허용하셨습니다.');
            $(this).val($.data(this, 'current'));
          } else if (data.status == 2) {
            alert('해당 회원의 계정 사용을 정지하셨습니다.');
            $(this).val($.data(this, 'current'));
          }
        }
      });
    }

    $(this).val(status);
  });
  $('input[name="email_verified"]').change(function () {
    var email_verified = $(this).val();
    var id = $('input[name="ver_temp_user_id"]').val();

    if (confirm('이메일 인증상태를 변경하시겠습니까?')) {
      $.ajax({
        url: '/admin/email_security_change',
        type: 'POST',
        data: {
          _token: CSRF_TOKEN,
          email_verified: email_verified,
          id: id
        },
        dataType: 'JSON',
        success: function success(data) {
          $.alert('변경 완료');
        }
      });
    } else {
      if (email_verified == 0) {
        $('#email_verified_y').prop("checked", true);
        $('#email_verified_n').prop("checked", false);
      } else {
        $('#email_verified_n').prop("checked", true);
        $('#email_verified_y').prop("checked", false);
      }
    }
  });
  $('input[name="mobile_verified"]').change(function () {
    var mobile_verified = $(this).val();
    var id = $('input[name="ver_temp_user_id"]').val();

    if (confirm('휴대폰 인증 상태를 변경하시겠습니까?')) {
      $.ajax({
        url: '/admin/mobile_security_change',
        type: 'POST',
        data: {
          _token: CSRF_TOKEN,
          mobile_verified: mobile_verified,
          id: id
        },
        dataType: 'JSON',
        success: function success(data) {
          $.alert('변경 완료');
        }
      });
    } else {
      if (mobile_verified == 0) {
        $('#mobile_verified_y').prop("checked", true);
        $('#mobile_verified_n').prop("checked", false);
      } else {
        $('#mobile_verified_n').prop("checked", true);
        $('#mobile_verified_y').prop("checked", false);
      }
    }
  });
  $('input[name="google_verified"]').change(function () {
    var google_verified = $(this).val();
    var id = $('input[name="ver_temp_user_id"]').val();

    if (confirm('OTP 인증상태를 변경하시겠습니까?')) {
      $.ajax({
        url: '/admin/google_security_change',
        type: 'POST',
        data: {
          _token: CSRF_TOKEN,
          google_verified: google_verified,
          id: id
        },
        dataType: 'JSON',
        success: function success(data) {
          $.alert('변경 완료');
        }
      });
    } else {
      if (google_verified == 0) {
        $('#google_verified_y').prop("checked", true);
        $('#google_verified_n').prop("checked", false);
      } else {
        $('#google_verified_n').prop("checked", true);
        $('#google_verified_y').prop("checked", false);
      }
    }
  });
  $('button.document_agree_btn').click(function () {
    var id = $(this).data('id');

    if (confirm('정말 승인하시겠습니까?')) {
      $.ajax({
        url: '/admin/document_agree',
        type: 'POST',
        data: {
          _token: CSRF_TOKEN,
          id: id
        },
        dataType: 'JSON',
        success: function success(data) {
          if (data.status == 1) {
            alert('정상적으로 승인 처리 되었습니다.');
            location.reload();
          } else {
            alert('신분증 자료가 모두 제출되지 않았습니다.\n신분증 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
          }
        }
      });
    }
  });
  $('button.document_reject_load_btn').click(function () {
    var id = $(this).data('id');
    $('input[name="temp_user_id"]').val(id);
    $.ajax({
      url: '/admin/document_reject_load',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id
      },
      dataType: 'JSON',
      success: function success(data) {
        if (data.reject_reason == '') {
          $('input[name="document_reject"]').val(data.reject_reason);
          $('.jw_modal_ft button').text('사유 등록');
        } else {
          $('input[name="document_reject"]').val(data.reject_reason);
          $('.jw_modal_ft button').text('사유 변경');
        }
        /* 거절버튼 */


        loadPopup('.document_reject_load_btn', '#reject_wrap', function (e) {
          var id = $(e.currentTarget).data('id');
          $('#temp_user_id').val(id);
        });
      }
    });
    $('#reject_wrap').removeClass('hidden');
    setTimeout(function () {
      $('#reject_wrap').addClass('active');
    }, 300);
  });
  $('#reject_wrap .jw_overlay, #reject_wrap .jw_modal_hd>div').click(function () {
    $('#reject_wrap').removeClass('active');
    setTimeout(function () {
      $('#reject_wrap').addClass('hidden');
    }, 300);
  });
  $('button.disagree_document_btn').click(function () {
    var id = $('input[name="temp_user_id"]').val();
    var document_reject = $('input[name="document_reject"]').val();
    $.ajax({
      url: '/admin/document_disagree',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id,
        document_reject: document_reject
      },
      dataType: 'JSON',
      success: function success(data) {
        if (data.status == 1) {
          alert('정상적으로 거절 처리 되었습니다.');
          location.reload();
        } else {
          alert('신분증 자료가 모두 제출되지 않았습니다.\n신분증 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
        }
      }
    });
    $('#reject_wrap').removeClass('hidden');
    setTimeout(function () {
      $('#reject_wrap').addClass('active');
    }, 300);
  });
  $('button.account_agree_btn').click(function () {
    var id = $(this).data('id');

    if (confirm('정말 승인하시겠습니까?')) {
      $.ajax({
        url: '/admin/account_agree',
        type: 'POST',
        data: {
          _token: CSRF_TOKEN,
          id: id
        },
        dataType: 'JSON',
        success: function success(data) {
          if (data.status == 1) {
            alert('정상적으로 승인 처리 되었습니다.');
            location.reload();
          } else {
            alert('계좌 자료가 모두 제출되지 않았습니다.\n계좌 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
          }
        }
      });
    }
  });
  $('button.account_reject_load_btn').click(function () {
    var id = $(this).data('id');
    $('input[name="temp_user_id"]').val(id);
    $.ajax({
      url: '/admin/account_reject_load',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id
      },
      dataType: 'JSON',
      success: function success(data) {
        if (data.reject_reason == '') {
          $('input[name="account_reject"]').val(data.reject_reason);
          $('.jw_modal_ft button').text('사유 등록');
        } else {
          $('input[name="account_reject"]').val(data.reject_reason);
          $('.jw_modal_ft button').text('사유 변경');
        }
      }
    });
    $('#reject_wrap').removeClass('hidden');
    setTimeout(function () {
      $('#reject_wrap').addClass('active');
    }, 300);
  });
  $('#reject_wrap .jw_overlay, #reject_wrap .jw_modal_hd>div').click(function () {
    $('#reject_wrap').removeClass('active');
    setTimeout(function () {
      $('#reject_wrap').addClass('hidden');
    }, 300);
  });
  $('button.disagree_account_btn').click(function () {
    var id = $('input[name="temp_user_id"]').val();
    var account_reject = $('input[name="account_reject"]').val();
    $.ajax({
      url: '/admin/account_disagree',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id,
        account_reject: account_reject
      },
      dataType: 'JSON',
      success: function success(data) {
        if (data.status == 1) {
          alert('정상적으로 거절 처리 되었습니다.');
          location.reload();
        } else {
          alert('계좌 자료가 모두 제출되지 않았습니다.\n계좌 자료가 모두 제출되어야만 승인/거절을 할 수 있습니다.');
        }
      }
    });
    $('#reject_wrap').removeClass('hidden');
    setTimeout(function () {
      $('#reject_wrap').addClass('active');
    }, 300);
  });
  $('.user_secession').click(function (e) {
    var button = $(e.currentTarget);
    var id = button.data('id');
    button.attr('disabled', true);
    $.confirm({
      title: '확인',
      content: '정말 해당 회원을 탈퇴처리 하시겠습니까?',
      buttons: {
        예: function _() {
          $.ajax({
            url: '/admin/user_secession',
            type: 'POST',
            data: {
              _token: CSRF_TOKEN,
              status: status,
              id: id
            },
            dataType: 'JSON',
            success: function success(data) {
              if (data.status == 1) {
                $.alert('해당 회원의 계정을 탈퇴처리 했습니다.');
              }

              button.attr('disabled', false);
            }
          });
        },
        아니오: function _() {
          $.alert('회원 탈퇴 처리를 취소 하셨습니다.');
          button.attr('disabled', false);
        }
      }
    });
  });
  $('button.cash_confirm').click(function () {
    var id = $(this).data('id');
    $.ajax({
      url: '/admin/cash_confirm',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id
      },
      dataType: 'JSON',
      success: function success(data) {
        alert(data.status);
        location.reload();
      }
    });
  });
  $('button.cash_reject').click(function () {
    var id = $(this).data('id');
    $.ajax({
      url: '/admin/cash_reject',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id
      },
      dataType: 'JSON',
      success: function success(data) {
        alert(data.status);
        location.reload();
      }
    });
  });
  $('button.company_confirm').click(function () {
    var id = $(this).data('id');
    $.ajax({
      url: '/admin/company_confirm',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id
      },
      dataType: 'JSON',
      success: function success(data) {
        alert(data.status);
        location.reload();
      }
    });
  });
  $('button.company_reject').click(function () {
    var id = $(this).data('id');
    $.ajax({
      url: '/admin/company_reject_load',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id
      },
      dataType: 'JSON',
      success: function success(data) {
        if (data.company_reject == '') {
          $('input[name="company_reject"]').val(data.company_reject);
          $('.jw_modal_ft button').text('사유 등록');
        } else {
          $('input[name="company_reject"]').val(data.company_reject);
          $('.jw_modal_ft button').text('사유 변경');
        }
        /* 거절버튼 */


        loadPopup('.company_reject', '#reject_wrap', function (e) {
          var id = $(e.currentTarget).data('id');
          $('#temp_user_id').val(id);
        });
      }
    });
  });
  $('button.disagree_company_btn').click(function () {
    var id = $('#temp_user_id').val();
    var company_reject = $('input[name="company_reject"]').val();
    $.ajax({
      url: '/admin/company_reject',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        id: id,
        company_reject: company_reject
      },
      dataType: 'JSON',
      success: function success(data) {
        alert(data.status);
        location.reload();
      }
    });
  });
  $('.payment_calculate').on('click', function () {
    if (check_ajax) {
      check_ajax = false;
      var check = confirm('정산하시기 전에 해당 계좌에 꼭 입금을 하신후 정산하셔야 됩니다. 정말 정산하시겠습니까?');

      if (check) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var label = $(this).data('label');
        $.ajax({
          url: "/admin/payment_calculate",
          type: "POST",
          data: {
            _token: CSRF_TOKEN,
            label: label
          },
          dataType: "JSON",
          success: function success(data) {
            alert(data.status);
            location.reload();
          }
        });
      }
    }
  });
});
$('#notauto_confirm_wrap .jw_overlay, #notauto_confirm_wrap .jw_modal_hd>div').click(function () {
  $('#notauto_confirm_wrap').removeClass('active');
  setTimeout(function () {
    $('#notauto_confirm_wrap').addClass('hidden');
  }, 300);
});
$('button.notauto_withdraw_confirm').click(function () {
  var id = $(this).data('id');
  $('input[name="id"]').val(id);
  $('#notauto_confirm_wrap').removeClass('hidden');
  setTimeout(function () {
    $('#notauto_confirm_wrap').addClass('active');
  }, 300);
});
$('button.notauto_confirm_submit').click(function () {
  var tx_id = $('input[name="tx_id"]').val();
  $.ajax({
    url: '/admin/coin/manual_confirm',
    type: 'POST',
    data: {
      _token: CSRF_TOKEN,
      tx_id: tx_id,
      request_id: id
    },
    dataType: 'JSON',
    success: function success(data) {
      $('input[name="id"]').val('');
      $('input[name="tx_id"]').val('');
      $('#notauto_confirm_wrap').removeClass('active');
      setTimeout(function () {
        $('#notauto_confirm_wrap').addClass('hidden');
      }, 300);
      alert(data.message);
      $('#co_button_wrap_' + request_id).html('<span class="withdraw_confirm">' + data.status + '</span>');
    }
  });
});

function auto_withdraw_confirm(request_id) {
  $.ajax({
    url: '/admin/coin/external_withdraw_confirm',
    type: 'POST',
    data: {
      _token: CSRF_TOKEN,
      request_id: request_id
    },
    dataType: 'JSON',
    success: function success(data) {
      alert(data.message);
      $('#co_button_wrap_' + request_id).html('<span class="withdraw_wait">' + data.status + '</span>');
    }
  });
}

function cancel_co_io(request_id, status) {
  if (confirm("정말 출금을 거부 하시겠습니까?")) {
    $.ajax({
      url: '/admin/coin/cancel_coin_io',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        request_id: request_id,
        status: status
      },
      dataType: 'JSON',
      success: function success(data) {
        alert(data.message);
        $('#co_button_wrap_' + request_id).html('<span class="withdraw_reject">' + data.status + '</span>');
      }
    });
  }

  function internal_withdraw_confirm(request_id) {
    $.ajax({
      url: '/admin/coin/internal_withdraw_confirm',
      type: 'POST',
      data: {
        _token: CSRF_TOKEN,
        request_id: request_id
      },
      dataType: 'JSON',
      success: function success(data) {
        alert(data.message);
        $('#co_button_wrap_' + request_id).html('<span class="withdraw_confirm">' + data.status + '</span>');
      }
    });
  }
}

$('form.ico_cancel_form button').click(function () {
  var obj = $(this);
  var id = obj.data('id');
  var reject = prompt('승인취소 및 거부 사유를 작성하여 주세요.');
  obj.siblings('input[name="reject"]').val(reject);
  $('#cancel_form_' + id).submit();
});
$('.ico_reject').click(function () {
  swal($(this).data('reject'));
});
$('button#deposit').click(function () {
  var coin_api = $(this).data('api');
  $('input[name="coin_api"]').val(coin_api); //AJAX 코인 입금 정보 로드

  $('#deposite_modal').removeClass('hidden');
  setTimeout(function () {
    $('#deposite_modal').addClass('active');
  }, 300);
});
$('#deposite_modal .jw_overlay, #deposite_modal .jw_modal_hd>div').click(function () {
  $('#deposite_modal').removeClass('active');
  setTimeout(function () {
    $('#deposite_modal').addClass('hidden');
  }, 300);
});
$('button#withraw').click(function () {
  var coin_api = $(this).data('api');
  $('input[name="coin_api"]').val(coin_api); //AJAX 코인 입금 정보 로드

  $('#withdraw_modal').removeClass('hidden');
  setTimeout(function () {
    $('#withdraw_modal').addClass('active');
  }, 300);
});
$('#withdraw_modal .jw_overlay, #withdraw_modal .jw_modal_hd>div').click(function () {
  $('#withdraw_modal').removeClass('active');
  setTimeout(function () {
    $('#withdraw_modal').addClass('hidden');
  }, 300);
});
