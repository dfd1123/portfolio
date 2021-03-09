var elementUI = {
  addClass: function ($element, targetClass) {
    if (this.hasClass($element, targetClass) === false) {
      $element.className += ' ' + targetClass
    }
  },
  hasClass: function ($element, targetClass) {
    return (' ' + $element.className + ' ').indexOf(' ' + targetClass + ' ') > -1
  },
  hasId: function ($element, targetId) {
    return (' ' + $element.id + ' ').indexOf(' ' + targetId + ' ') > -1
  },
  removeClass: function ($element, targetClass) {
    var rgx = new RegExp('(?:^|\\s)' + targetClass + '(?!\\S)', 'g')
    $element.className = $element.className.replace(rgx, '')
  },

  parentsClass: function ($element, targetClass) {
    var path = []
    var node = $element

    if (this.hasClass(node, targetClass)) return node

    while (node != document.body) {
      path.push(node)
      node = node.parentNode
      if (this.hasClass(node, targetClass)) return node
    }
  },

  parentsId: function ($element, targetId) {
    var path = []
    var node = $element

    if (this.hasId(node, targetId)) return node

    while (node != document.body) {
      path.push(node)
      node = node.parentNode
      if (this.hasId(node, targetId)) return node
    }
  }
}

var browserUI = {
  windowFixEnable: function () {
    elementUI.addClass(document.documentElement, 'fix')
  },
  windowFixDisable: function () {
    elementUI.removeClass(document.documentElement, 'fix')
  },
  getScrollBarWidth: function () {
    var inner = document.createElement('p')
    inner.style.width = '100%'
    inner.style.height = '200px'

    var outer = document.createElement('div')
    outer.style.position = 'absolute'
    outer.style.top = '0px'
    outer.style.left = '0px'
    outer.style.visibility = 'hidden'
    outer.style.width = '200px'
    outer.style.height = '150px'
    outer.style.overflow = 'hidden'
    outer.appendChild(inner)

    document.body.appendChild(outer)
    var w1 = inner.offsetWidth
    outer.style.overflow = 'scroll'
    var w2 = inner.offsetWidth
    if (w1 == w2) w2 = outer.clientWidth

    document.body.removeChild(outer)

    return (w1 - w2)
  }
}

function inputFile (target) {
  var readValue = (target.value == '') ? '' : target.value
  var readInputElement = target.parentElement.getElementsByClassName('read-file')[0]
  readInputElement.value = readValue
}

function layerPopOpen (id) {
  var $element = document.getElementById(id)
  elementUI.addClass($element, 'open')
  $element.style.display = 'block'
}

function layerPopClose (id) {
  var $element = document.getElementById(id)
  elementUI.removeClass($element, 'open')
  $element.style.display = 'none'
}

function layerPopToggle (id) {
  var $element = document.getElementById(id)
  if (elementUI.hasClass($element, 'open')) {
    layerPopClose(id)
  } else {
    layerPopOpen(id)
  }
}

/*
function pageInit () {
  // console.log('pageInit');
  if (!document.getElementById('dHead')) {
    return
  }

  var gnbHeight = document.getElementById('dHead').offsetHeight
  var dbodyHeight = document.getElementById('dBody').offsetHeight

  if (dbodyHeight < gnbHeight) document.getElementById('dBody').setAttribute('style', 'min-height:' + gnbHeight + 'px;')
  elementUI.addClass(document.getElementById('snb'), 'open')
  elementUI.addClass(document.getElementById('contents'), 'open')
  elementUI.addClass(document.getElementById('dFoot'), 'open')
  // console.log(gnbHeight + ' : ' + dbodyHeight);
}

window.addEventListener('DOMContentLoaded', pageInit)
*/
