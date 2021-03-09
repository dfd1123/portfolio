/* ==============================================

    ----------------------------------------------------------------
	* Date		:	2019.09.16
	* Modify	:
	* Name		:	front_ui.js
	----------------------------------------------------------------

	- Description -
	01. front.init()				:	프론트 스크립트 초기실행
	02. front.ready()				:	html 문서 로드 완료시
	03. front.resize()				:	window 크기 변경시
	04. front.scroll()				:	window 스크롤시

============================================== */

/* ===================================
@ front
=================================== */
var front = {

  winW: null,										// 윈도우 넓이
  winH: null,										// 윈도우 높이
  browser: null,									// 브라우저 종류
  sc: null,											// 스크롤 상단값
  mobile: null,									// 모바일 여부 체크

  init: function () {
    front.winW = $(window).width()
    front.winH = $(window).height()
    front.browser = navigator.userAgent
    front.mobile = front.common.agentCheck()

    front.scroll()
    front.common.init()
    front.form.init()
    front.contents.init()
    front.pop.init()
  },

  ready: function () {

  },

  load: function () {

  },

  resize: function () {
    front.winW = $(window).width()
    front.winH = $(window).height()
  },

  scroll: function () {
    front.winW = $(window).width()
    front.winH = $(window).height()
    front.sc = $(window).scrollTop()
  },

  common: {

    init: function () {
      var common = front.common

      common.gnb.init()
    },

    gnb: {

      target: null,

      init: function () {
        var _this = front.common.gnb
        _this.target = $('#lnb')

        _this.target.find('> ul > li').each(function () {
          if ($(this).find('.snb').length > 0) {
            $(this).find('> a').attr('href', 'javascript:;')
            $(this).addClass('two-depth')
          } else {
            $(this).addClass('none')
          }

          if ($(this).hasClass('actived')) {
            $(this).addClass('open')
          }
        })

        _this.target.find('> ul > li > a').click(function () {
          var _parent = $(this).parent()
          if (_parent.hasClass('open')) {
            _parent.removeClass('open')
          } else {
            _parent.addClass('open')
          }

          var _sideHeight = $('#lnb').find('> ul').innerHeight()
          var _contentHeight = $('#dBody').innerHeight()

          if (_sideHeight > _contentHeight) {
            $('#dBody').css({ 'min-height': _sideHeight + 'px' })
          } else {
            $('#dBody').attr('style', '')
          }
        })
      }

    },

    moveScroll: function (tgY, speed) {
      if (speed == null || speed == 'undefind') speed = 1000
      $('html, body').stop().animate({ 'scrollTop': tgY }, { queue: false, duration: speed, easing: 'easeOutCubic' })
    },

    getParamater: function (key) {
      var url = location.href
      var spoint = url.indexOf('?')
      var query = url.substring(spoint, url.length)
      var keys = new Array()
      var values = new Array()
      var nextStartPoint = 0
      while (query.indexOf('&', (nextStartPoint + 1)) > -1) {
        var item = query.substring(nextStartPoint, query.indexOf('&', (nextStartPoint + 1)))
        var p = item.indexOf('=')
        keys[keys.length] = item.substring(1, p)
        values[values.length] = item.substring(p + 1, item.length)
        nextStartPoint = query.indexOf('&', (nextStartPoint + 1))
      }
      item = query.substring(nextStartPoint, query.length)
      p = item.indexOf('=')
      keys[keys.length] = item.substring(1, p)
      values[values.length] = item.substring(p + 1, item.length)
      var value = ''
      for (var i = 0; i < keys.length; i++) {
        if (keys[i] == key) {
          value = values[i]
        }
      }
      return value
    },

    agentCheck: function () {
      var UserAgent = navigator.userAgent
      var UserFlag = true
      if (UserAgent.match(/iPhone|iPad|iPod|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i) != null || UserAgent.match(/LG|SAMSUNG|Samsung/) != null) UserFlag = false

      return UserFlag
    },

    getPer: function (_Amin, _Amax, _Bmin, _Bmax, _index) {
      var value = (_index / (_Bmax - _Bmin)) * (_Amax - _Amin) + _Amin

      return value
    }

  },

  contents: {

    init: function () {

    }

  },

  form: {
    fileNum: 1,
    init: function () {
      $('.login-wrap').find('.input-cell').each(function (idx) {
        var _this = $(this)

        _this.find('input').focusout(function () {
          if (String($(this)[0].value).length > 0) {
            $(this).addClass('is-value')
          } else {
            $(this).removeClass('is-value')
          }
        })
      })
    },

    // 파일 업로드의 경로값 설정
    inputFile: function (_target) {
      var _t = $(_target)
      var _p = _t.parent()
      var _n = _t.val()

      if (_n != '') {
        _t.next().val(_n)
      } else {
        _t.next().val('')
      }
    },

    // 파일 업로드 셀 추가
    addInputFile: function (t) {
      var _t = $(t)
      var _p = _t.parents('.file-parent')
      var _line = ''
      _line += '<li class="add-input">'
      _line += '	<div class="add-file">'
      _line += '		<div class="file-input">'
      _line += '			<input type="file" name="file-upload" class="input-file" onchange="front.form.inputFile(this)" title="파일 업로드 찾기">'
      _line += '			<input type="text" name="file-address" readonly="" class="read-file" title="업로드된 파일 경로">'
      _line += '			<a href="javascript:;" class="btn-close">파일삭제</a>'
      _line += '			<a href="javascript:;" class="btn-01 type-03 round">찾아보기</a>'
      _line += '		</div>'
      _line += '		<p class="btn-ui">'
      _line += '			<a href="javascript:;" onclick="front.form.addInputFile(this);" class="btn-add"></a>'
      _line += '			<a href="javascript:;" onclick="front.form.deleteInputFile(this);" class="btn-del"></a>'
      _line += '		</p>'
      _line += '	</div>'
      _line += '</li>'

      _p.append(_line)

      this.fileNum++
    },

    // 파일 업로드 셀 삭제
    deleteInputFile: function (t) {
      var _parents = $(t).parents('.file-parent')
      if (_parents.find('> li').length > 1) {
        $(t).parents('.add-input').remove()
      }
    },

    // 텍스트 입력 셀 추가
    addInputText: function (t) {
      var _t = $(t)
      var _p = _t.parents('.file-parent')
      var _line = ''
      _line += '<li class="add-input">'
      _line += '	<div class="add-text">'
      _line += '		<div class="row-input-wrap">'
      _line += '			<div><input type="text" name="" id="" style="width:150px" class="al-r"><span class="char">/</span></div>'
      _line += '			<div><input type="text" name="" id="" style="width:150px" class="al-r"><span class="char">원</span></div>'
      _line += '		</div>'
      _line += '		<p class="btn-ui">'
      _line += '			<a href="javascript:;" onclick="front.form.addInputText(this);" class="btn-add"></a>'
      _line += '			<a href="javascript:;" onclick="front.form.deleteInputText(this);" class="btn-del"></a>'
      _line += '		</p>'
      _line += '	</div>'
      _line += '</li>'

      _p.append(_line)

      this.fileNum++
    },

    // 텍스트 입력 셀 삭제
    deleteInputText: function (t) {
      var _parents = $(t).parents('.file-parent')
      if (_parents.find('> li').length > 1) {
        $(t).parents('.add-input').remove()
      }
    }

  },

  pop: {

    init: function () {
      $('.layer-pop-wrap').on('click', function (e) {
        if ($(e.target).parents('.pop-data').length == 0) {
          $(this).stop(true).fadeOut(400)
          $('html').removeClass('fix')
        }
      })
    },

    /* ==========================================
		/	@ 팝업 오픈
		/	front.pop.open(아이디값) 으로 팝업 오픈
		========================================== */
    open: function (_id) {
      var target = $('#' + _id)
      $('html').addClass('fix')

      target.stop(true).fadeIn(400)
    },

    close: function (_t) {
      var _target = $(_t).parents('.layer-pop-wrap')
      _target.stop(true).fadeOut(400)
      $('html').removeClass('fix')
    },

    stepChange: function (_t, _index) {
      var _target = $(_t).parents('.layer-pop-wrap')
      _target.find('.pop-data').eq(_index).show().siblings().hide()
    },

    // open window Pop
    windowPopOpen: function (_url, _name, _width, _height) {
      window.open(_url, _name, 'width=' + _width + 'px, height=' + _height + 'px, top=0, left=0, resizable=yes, scrollbars=yes, location=no,  toolbar=no, status=no, menubar=no')
    }

  }

}
/* ===================================
@ init
=================================== */
$(function () {
  front.init()
})

/* ===================================
@ document ready
=================================== */
$(document).ready(function () {
  front.ready()
})

/* ===================================
@ window load
=================================== */
$(window).load(function () {
  front.load()
})

/* ===================================
@ window resize
=================================== */
$(window).resize(function () {
  front.resize()
})

/* ===================================
@ window scroll
=================================== */
$(window).scroll(function () {
  front.scroll()
})
