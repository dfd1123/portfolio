var controller = new ScrollMagic.Controller();
var tl = new TimelineLite;

var tween = tl.to('.ex_ball', 0.5, {top: 122, left: 45+'%', opacity: 1, scale: 1}).to('.ex_ball', 0.7, {rotation: 360, ease: Linear.easeNone});
var store_wrap = $('.store_wrap').offset().top;

new ScrollMagic.Scene({
    duration:800,    // the scene should last for a scroll distance of 100px
    offset:2000,
    triggerHook: 'onCenter'    // start this scene after scrolling for 50px
})
.setTween(tween) // pins the element for the the scene's duration
.addTo(controller); // assign the scene to the controller
