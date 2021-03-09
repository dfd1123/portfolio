var intViewportHeight = window.innerHeight;
var href = $(location).attr('href');
var protocol = $(location).attr('protocol') + "//";
var host = $(location).attr('host');
href = href.replace(protocol, '').replace(host, '').split('?');
href = href[0].split('/');
$(document).ready(function () {
  /* JS button ripple effect */

  /*
      $(".ripple_btn").on("click", function (e) {
          console.log('awd');
          // Remove any old one
          $(".ripple").remove();
      
          // Setup
          var posX = $(this).offset().left,
              posY = $(this).offset().top,
              buttonWidth = $(this).width(),
              buttonHeight =  $(this).height();
          
          // Add the element
          $(this).prepend("<span class='ripple'></span>");
      
          
      // Make it round!
          if(buttonWidth >= buttonHeight) {
          buttonHeight = buttonWidth;
          } else {
          buttonWidth = buttonHeight; 
          }
          
          // Get the center of the element
          var x = e.pageX - posX - buttonWidth / 2;
          var y = e.pageY - posY - buttonHeight / 2;
          
      
          // Add the ripples CSS and start the animation
          $(".ripple").css({
          width: buttonWidth,
          height: buttonHeight,
          top: y + 'px',
          left: x + 'px'
          }).addClass("rippleEffect");
      });
  */
  $('#app').css('min-height', intViewportHeight + 'px');
  $('#loading_wrap').hide();
  $('.back_history').on("click", function () {
    history.back();
  });
  $('.es_req').on("click", function () {
    var id = $(this).data('id');
    var step = $(this).data('step');
    var pre_step = parseInt(step) - 1;

    if (pre_step != 0) {
      location.href = '/user_ver/estimate_request/' + pre_step + '?id=' + id + '';
    } else if (pre_step == 0) {
      location.href = '/user_ver';
    }
  });
  $('.close_hd_btn').on("click", function () {
    var trd_no = $('input[name="trd_no"]').val();

    if (trd_no != '') {
      swal({
        title: "재확인",
        text: "견적요청을 취소하고\n처음으로 돌아가시겠습니까?\n입력하신 내용은 저장되지 않습니다.",
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
            type: "DELETE",
            dataType: "json",
            url: "/api/estimate_request/" + trd_no + "",
            success: function success(data) {
              if (data.status) {
                location.href = '/user_ver';
              } else {
                swal({
                  title: "네트워크 오류",
                  text: "잠시 후 다시 시도해주세요.",
                  button: "확인"
                });
                location.reload();
              }
            },
            error: function error(data) {
              swal({
                title: "삭제 불가",
                text: "이미 시공이 진행되었거나 견적신청이\n종료됬을 경우 삭제할 수 없습니다.",
                button: "확인"
              });
            }
          });
        }
      });
    } else {
      location.href = '/user_ver';
    }
  });
  $('.go_home').on("click", function () {
    location.href = "/user_ver/";
  });
  $('.alarm_hd_btn').on("click", function () {
    $('.alarm_new_icon').hide();
    $('.alarm_box').addClass('animated bounceIn delay-03s fast');
    $('.alarm_wrap').show();
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

  if (href[1] != 'login' && href[1] != 'register') {
    if (href[2] == 'corporation_status' || href[2] == undefined) {
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

  $('.menu-trigger').on('click', function (e) {
    e.preventDefault();

    if ($(this).hasClass('active-1')) {
      $('#menu_wrap .menu_content ul').removeClass('active');
      $('.request_complete .request_content').css('z-index', 17);
      $('#sub_hd2 .center_hd').css('padding-bottom', '6.5em');
      setTimeout(function () {
        $('#menu_wrap').removeClass('active');
        $('header .left_hd').show();
        $('.menu_tit').siblings().show();
        $('.menu_tit').hide();
        $('header').css('z-index', 17);
      }, 300);
    } else {
      $('.menu_tit').siblings().hide();
      $('.menu_tit').show();
      $('#menu_wrap').addClass('active');
      $('header .left_hd').hide();
      $('header').css('z-index', 19);
      $('.request_complete .request_content').css('z-index', 10);
      $('#sub_hd2 .center_hd').css('padding-bottom', '0.7em');
      setTimeout(function () {
        $('#menu_wrap .menu_content ul').addClass('active');
      }, 300);
    }

    $(this).toggleClass('active-1');
  });
  new WOW({
    boxClass: 'wow',
    animateClass: 'animated',
    offset: 0,
    live: true
  }).init();
  [].map.call(document.querySelectorAll('[anim="ripple"]'), function (el) {
    el.addEventListener('click', function (e) {
      e = e.touches ? e.touches[0] : e;
      var r = el.getBoundingClientRect(),
          d = Math.sqrt(Math.pow(r.width, 2) + Math.pow(r.height, 2)) * 2;
      el.style.cssText = "--s: 0; --o: 1;";
      el.offsetTop;
      el.style.cssText = "--t: 1; --o: 0; --d: " + d + "; --x:" + (e.clientX - r.left) + "; --y:" + (e.clientY - r.top) + ";";
    });
  });
});
$('#alarm_body').scroll(function () {
  if ($('#alarm_body').scrollTop() == $('.alarm_wrap .alarm_content .alarm_box>div').height() - $('#alarm_body').height()) {}
});

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
