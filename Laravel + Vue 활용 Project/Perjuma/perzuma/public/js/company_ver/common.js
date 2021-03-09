var href = $(location).attr('href');
var protocol = $(location).attr('protocol') + "//";
var host = $(location).attr('host');
href = href.replace(protocol, '').replace(host, '').split('?');
href = href[0].split('/');
$(function () {
  $('.back_history').on("click", function () {
    history.back();
  });
  $('.go_home').on("click", function () {
    location.href = "/user_ver/";
  });
  $('.alarm_hd_btn').on("click", function () {
    $('.alarm_new_icon').hide();
    $('.alarm_box').addClass('animated bounceIn delay-03s fast');
    $('.alarm_wrap').show(); //location.href="#alarm";
  });
  $('.alarm_wrap .overlay').on("click", function () {
    $('.alarm_box').removeClass('animated bounceIn delay-03s fast');
    $('.alarm_wrap').hide();
  });
  $(document).on("click", ".message_wrap .message_ft button", function () {
    $('.message_wrap').removeClass('active emergency new');
    $('#message_box .message_body .message_text_wrap .message_icon img').hide();
  });
  $(document).on("click", ".alarm_li", function (e) {
    var msg_no = $(this).data('id');
    var this_li = $(this);
    $.ajax({
      type: "GET",
      dataType: "json",
      url: "/api/message/view?msg_no=" + msg_no + "",
      success: function success(data) {
        var message_box = $('#message_box');
        var templete = $($('#message_content').html());

        if (data.message.msg_type == 1) {
          templete.find('h5').text('퍼주마 매니저(긴급 메세지)');
        } else {
          templete.find('h5').text('일반 메세지');
        }

        templete.find('#msg_title').text(data.message.msg_title);
        templete.find('#msg_send_dt').text(moment(data.message.send_dt).format('YYYY-MM-DD hh:mm'));
        templete.find('#msg_content').text(data.message.msg_content);
        console.log(data.message.msg_img);

        if (data.message.msg_img !== null && data.message.msg_img !== '[]') {
          Object.keys(JSON.parse(data.message.msg_img)).forEach(function (key, index, array) {
            var real_index = parseInt(index) + 1;
            templete.find('.swiper-wrapper').append('<div class="swiper-slide"><img src="/storage/image/review' + data.message.msg_img[key] + '" alt="" /></div>');
            var message_swiper = new Swiper('#message_slider', {
              loop: true,
              pagination: {
                el: '.swiper-paginate',
                dynamicBullets: true
              }
            });
          });
        } else {
          templete.find('.message_slider_wrap').remove();
        }

        message_box.html(templete);

        if (this_li.hasClass('emergency')) {
          if (this_li.hasClass('new')) {
            $('.message_wrap').addClass('active new emergency');
            $('#message_box .message_body .message_text_wrap .message_icon img.new_emergency').show();
            this_li.removeClass('new');
          } else {
            $('.message_wrap').addClass('active emergency');
          }
        } else {
          if (this_li.hasClass('new')) {
            $('.message_wrap').addClass('active new');
            $('#message_box .message_body .message_text_wrap .message_icon img.new_alarm').show();
          } else {
            $('.message_wrap').addClass('active');
          }
        }
      },
      error: function error(data) {
        swal({
          title: "네트워크 에러",
          text: "잠시 후 다시 시도하여 주세요.",
          button: "확인"
        });
      }
    });
  });

  if (href[1] != 'login' && href[1] != 'register' && href[1] != 'forgotpwd' && href[1] != 'password') {
    if (href[2] == undefined || href[2] == 'detail' || href[2] == 'company_request' || href[2] == 'company_bidding_detail' || href[2] == 'company_bidding' || href[2] == 'company_bidding_regist') {
      $.ajax({
        type: "GET",
        dataType: "json",
        url: "/api/message/default_list",
        success: function success(data) {
          if (data["new"]) {
            $('.alarm_new_icon').show();
          }

          var alarm_body = $('#alarm_body>div');
          alarm_body.data('offset', data.offset);
          alarm_body.data('count', data.count);

          if (data.messages !== undefined) {
            data.messages.forEach(function (message, index, array) {
              var templete = $($('#alarm_li').html());
              console.log(message.msg_state);

              if (message.msg_state == 0) {
                templete.addClass('new');
              }

              if (message.msg_type == 1) {
                templete.addClass('emergency');
                templete.find('h5').text('[긴급메시지] ' + message.msg_title);
              } else {
                templete.find('h5').text(message.msg_title);
              }

              templete.data('id', message.msg_no);
              templete.find('span').text(moment(message.send_dt).format('YYYY-MM-DD hh:mm'));
              templete.find('p').text(message.msg_content);
              templete.find('button').data('id', message.msg_no);
              alarm_body.append(templete);
            });
          }
        },
        error: function error(data) {
          swal({
            title: "네트워크 에러",
            text: "잠시 후 다시 시도하여 주세요.",
            button: "확인"
          });
        }
      });
    }
  }
});
$('#alarm_body').scroll(function () {
  console.log("ㅁㅈㅇㅁㅈㅇ");

  if ($('#alarm_body').scrollTop() == $('.alarm_wrap .alarm_content .alarm_box>div').height() - $('#alarm_body').height()) {}
});

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
} //GET파라미터 찾기


function findGetParameter(parameterName) {
  var result = null,
      tmp = [];
  location.search.substr(1).split("&").forEach(function (item) {
    tmp = item.split("=");
    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
  });
  return result;
}

function logout() {
  swal({
    title: "로그아웃",
    text: "정말 로그아웃 하시겠습니까?",
    buttons: {
      yes: {
        text: "예",
        value: true
      },
      no: {
        text: "아니오",
        value: false
      }
    }
  }).then(function (value) {
    if (value) {
      $.ajax({
        type: 'GET',
        url: '/api/logout',
        success: function success(data) {
          localStorage.removeItem("passportToken");
          $.ajaxSetup({
            beforeSend: function beforeSend(xhr) {
              xhr.setRequestHeader("Authorization", undefined);
            }
          });
          location.href = "/login";
        }
      });
    }
  });
}

function unregist() {
  swal({
    title: "회원탈퇴",
    text: "정말 회원탈퇴 신청을 하시겠습니까? \n심사후 탈퇴됩니다",
    buttons: {
      yes: {
        text: "예",
        value: true
      },
      no: {
        text: "아니오",
        value: false
      }
    }
  }).then(function (value) {
    if (value) {
      $.ajax({
        type: 'GET',
        url: '/api/unregist',
        success: function success(data) {
          localStorage.removeItem("passportToken");
          $.ajaxSetup({
            beforeSend: function beforeSend(xhr) {
              xhr.setRequestHeader("Authorization", undefined);
            }
          });
          swal({
            title: "회원탈퇴",
            text: "정상 처리 되었습니다",
            buttons: {
              yes: {
                text: "예",
                value: true
              }
            }
          }).then(function (value) {
            if (value) {
              location.href = "/login";
            }
          });
        }
      });
    }
  });
}
