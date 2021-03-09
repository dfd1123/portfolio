( function($){
	// touch constant
	var touchStart = 'touchstart';
	var touchEnd = 'touchend';
	var touchMove = 'touchmove';
 
	var callback, duration;
	var timerID;
 
	var pX, pY;
	var BOUNDS = 10; // tap area bound
	
	$.fn.enableLongClick = function(_callback, _duration){
		callback = _callback;
		duration = _duration;
 
		$(this).bind('touchstart touchend touchmove', eventHandler);
	}
 
	var eventHandler = function(e){
		var type = e.type;
		
		if(type == touchStart){
			pX = e.pageX;
			pY = e.pageY;
 
			timerID = setTimeout(function(){
				// 현재 포커스가 들어간 객체의 ID 값을 알려준다. 
				// Get id value of current focused.
				var targetID = e.target.id;
 
				//콜백으로 던져준다.
				// Send results to callback
				// @param targetID Object id
				// @param e.pageX x-axis touch point 
				// @param e.pageY y-axis touch point
				return callback(targetID, e.pageX, e.pageY);
			}, duration);
 
			//Duration에 못미쳐 터치가 끝나면 fire
			//It will be fired while moving or detaching user's finger from screen before timeout
		}else if(type == touchEnd){
			clearTimeout(timerID);
		}else if(type == touchMove){
			//드래깅 할 때 이벤트 fire
 
			var cX = e.pageX;
			var cY = e.pageY;
			
			var bleft = pX - (BOUNDS / 2);
			var btop = pY - (BOUNDS / 2);
			var bright = pX + (BOUNDS / 2);
			var bbottom = pY + (BOUNDS / 2);
			
			// 탭을 2번할 때, 움직임으로 자동적으로 timeout 이벤트가 해제되는 것을 방지
			// To prevent releasing timeout event when the user tap an object twice
			if(!(cX >= bleft && cX <= bright && cY >= btop && cY < bbottom)){
				clearTimeout(timerID);
			}
		}
	}
})(jQuery);
