/**
 * ajax
 */
// Laravel backend setting

window.moment = require('moment');

window.imagesloaded = require('imagesloaded');

const WOW = require('wowjs'); 
window.wow = new WOW.WOW({ live: false });


var href = $(location).attr('href');
var protocol = $(location).attr('protocol')+"//";
var host = $(location).attr('host');

href = href.replace(protocol,'').replace(host, '').split('?');
href = href[0].split('/');

var csrf_token = document.head.querySelector('meta[name="csrf-token"]');

$.ajaxSetup({
    timeout: 5000,
    beforeSend: function (xhr)
    {
        xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");
        if (csrf_token) {
            xhr.setRequestHeader("X-CSRF-TOKEN",csrf_token.content);
        }else{
            console.error(
                "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
            );
        }
        /**
         * Auth
         */
        // 이전에 발급받은 토큰이 있으면 ajax 헤더에 추가
        if (localStorage.passportToken) {
            xhr.setRequestHeader("Authorization", `Bearer ${localStorage.passportToken}`);
        }
    },
    // ajax에서 만료된 토큰으로 데이터 요청 시 세션 만료 표시
    error: function(jqXHR, exception) {
        if (jqXHR.status === 401) {
            console.log(this.url);
            if(localStorage.passportToken || exception.url !== "/api/login"){
                swal({
                    text: "세션이 만료 되었습니다.\n로그인 페이지로 이동합니다.",
                    button: "확인",
                    icon: "warning",
                }).then(function(){
                    localStorage.removeItem("passportToken");
                    $.ajaxSetup({
                        beforeSend: function (xhr)
                        {
                            xhr.setRequestHeader("Authorization", undefined);
                        }
                    });
                    location.href="/login?expire=1";
                });
            }
        }
    }
});

function refresh_token(){
    if(href[1] !== "login" && href[1] !== "register" && href[1] !== "forgotpwd"){
        $.ajax({
            type : "POST",
            dataType: "json",
            url : "/api/refresh",
            success : function(data) {
                return data.token;
            },
            error : function(data){
                return localStorage.passportToken;
            }
        });
    }
}

// 주기적으로 액세스 토큰 갱신
const refreshInterval = 15 * 60 * 1000;
const refresh = () => {
    if(href[1] !== "login" && href[1] !== "register" && href[1] !== "forgotpwd" && href[1] != 'password'){
        try {
            if (localStorage.passportToken) {

                $.ajax({
                    type : "POST",
                    dataType: "json",
                    url : "/api/refresh",
                    success : function(data) {
                        const token = data.token;

                        localStorage.passportToken = token;
                        $.ajaxSetup({
                            beforeSend: function (xhr)
                            {
                                xhr.setRequestHeader("Authorization", `Bearer ${token}`);
                            }
                        });
                    },
                    error : function(data){
                        const token =  localStorage.passportToken;

                        localStorage.passportToken = token;
                        $.ajaxSetup({
                            beforeSend: function (xhr)
                            {
                                xhr.setRequestHeader("Authorization", `Bearer ${token}`);
                            }
                        });
                    }
                });
            }else{
                swal({
                    text: "로그인을 하셔야 이용 가능합니다.\n로그인 페이지로 이동합니다",
                    button: "확인",
                    icon: "warning",
                }).then(function(){
                    location.href="/login?expire=1";
                });
            }
        }finally {
            setTimeout(() => refresh(), refreshInterval);
        }
    }
};
setTimeout(() => refresh(), 3 * 1000);


