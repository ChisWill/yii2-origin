(function (doc, win) {
    var docEl = doc.documentElement, //html
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize', //事件名称
        recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) {
                return;
            }
            if (clientWidth < 414) {
                docEl.style.fontSize = '50px';
            } else if (clientWidth >= 414 && clientWidth < 480) {
                docEl.style.fontSize = '60px';
            } else if(clientWidth >= 480 && clientWidth < 540) {
                docEl.style.fontSize = '70px';
            } else if(clientWidth >= 540 && clientWidth < 768) {
                docEl.style.fontSize = '80px';
            } else if(clientWidth >= 768 && clientWidth < 920) {
                docEl.style.fontSize = '90px';
            } else {
                docEl.style.fontSize = '100px';
            } 
        };
    if (!doc.addEventListener) {
        return;
    }
    win.addEventListener(resizeEvt, recalc, false);
    recalc();
})(document, window);

setTimeout(function () {
    $("body").css({
        'visibility': 'visible'
    });
}, 100);
$(window).scroll(function() {
    if($(window).scrollTop() > 50){
        $(".header").addClass("fixed-top");
    } else{
        $(".header").removeClass("fixed-top");
    }
});
