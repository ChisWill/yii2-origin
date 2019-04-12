/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2019-01-05 11:05:17
 * @version $Id$
 */
(function($) {
    $.fn.extend({
        slider: function(options) {
            var settings = $.extend({
                speed: 500

            },
            options);
            return this.each(function() {
                var slidercontents = $(this).addClass('image-slider-contents');
                var slider = $('<div/>').addClass('image-slider').attr('id', slidercontents.attr('id'));
                var backbutton = $('<div/>').addClass('image-slider-back');
                var forwardbutton = $('<div/>').addClass('image-slider-forward');
                slidercontents.removeAttr('id');
                slidercontents.before(slider);
                slider.append(backbutton);
                slider.append(slidercontents);
                slider.append(forwardbutton);
                var total = $('> div', slidercontents).length;
                var left = 0;
                var w;
                var width;
                var maxScroll;
                slider.append($('<div/>').css('display', 'none').addClass('preview').append($('<div/>').addClass('img-large')
                .append($('<div/>').html('&nbsp').click(function(e) {
                    //弹出的萝卜图zuo点击事件
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    //display previous image
                    var img = $(this).parent().find('img:first');
                    var index = parseInt(img.attr('class'));
                    img.removeAttr('class');
                    if (index == 1) {
                        index = total + 1;
                    }
                        index--;
                        var src = $('.' + index + ' div>div img:first').attr('src');
                        var txt1 = $('.' + index + ' div a').eq(0).html();
                        var txt2 = $('.' + index + ' div a').eq(1).html();
                        var txt3 = $('.' + index + ' div a').eq(2).html();
                        $('.preview').find('.label').eq(0).html(txt1);
                        $('.preview').find('.label').eq(1).html(txt2);
                        $('.preview').find('.label').eq(2).html(txt3);
                        $('.preview').find('img:first').addClass('' + (index) + '').css('opacity', '0').attr('src', src).stop(false, true).animate({
                            opacity: 1
                        },
                        1000);
                    // else
                    // $('.preview').find('img').addClass('' + (index) + '')

                }).addClass('left').css('opacity', '0.5').hover(function() {
                    $(this).css('opacity', '1')
                },
                function() {
                    $(this).css('opacity', '0.5')
                }))  //弹出的萝卜图右点击事件
                .append($('<div/>').html('&nbsp').click(function(e) {
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    //display next image
                    var img = $(this).parent().find('img:first');
                    var index = parseInt(img.attr('class'));
                    img.removeAttr('class');
                    if (index == total) {
                        index = 0;
                    }
                    index++;
                    var src = $('.' + index + ' div>div img:first').attr('src');
                    var txt1 = $('.' + index + ' div a').eq(0).html();
                    var txt2 = $('.' + index + ' div a').eq(1).html();
                    var txt3 = $('.' + index + ' div a').eq(2).html();
                    $('.preview').find('.label').eq(0).html(txt1);
                    $('.preview').find('.label').eq(1).html(txt2);
                    $('.preview').find('.label').eq(2).html(txt3);
                    $('.preview').find('img:first').addClass('' + (index) + '').css('opacity', '0').attr('src', src).stop(false, true).animate({
                        opacity: 1
                    },
                    1000);
                    // else
                    // $('.preview').find('img').addClass('' + (index) + '')

                }).addClass('right').css('opacity', '0.5').hover(function() {
                    $(this).css('opacity', '1')
                },
                function() {
                    $(this).css('opacity', '0.5')
                }))
                .append($('<img/>'))).append($('<div/>').addClass('label'))
                .append($('<div/>').addClass('label'))
                .append($('<div/>').addClass('label'))
                // .append($('<div/>').addClass('close').click(function(e) {
                //     $(this).parent().fadeOut("slow");

                // }
                // ))
                );
                //轮播消失 
                $(document).click(function(e) {
                    if ($('.preview').css('display') == 'block')
                    $('.preview').fadeOut("slow");

                });
                function initialize() {
                    var tempElements = $('> div', slidercontents);
                    var allElements = new Array();
                    tempElements.each(function(index, el) {
                        allElements.push($('<div/>').addClass('' + (index + 1) + '').addClass('outer').append(el));

                    });

                    allElements = $(allElements);
                    $('> div', slidercontents).remove();
                    var wrapper = $('<div/>').addClass('contents-wrapper');
                    allElements.each(function(index, el) {

                        wrapper.append($(el));

                    });
                    slidercontents.append(wrapper);
                    var w = $('.outer:eq(0)', slidercontents).outerWidth() + parseFloat($('.outer:eq(0)', slidercontents).css('margin-left')) + parseFloat($('.outer:eq(0)', slidercontents).css('margin-right'));
                    var screenX = window.screen.width;
                    var width ;
                    if (screenX >= 1200 || screenX <=320) {
                        width = (total) * w;
                    } else  {
                        width = (total + 1) * w;
                    }  
                    var maxScroll = width - $('.image-slider-contents').outerWidth();
                    wrapper.css({
                        width: width
                    });
                    $('> div > div', slidercontents).css('display', '');
                    // $('.outer', slidercontents).each(function(index) {
                    //     $(this).prepend($('<img/>').attr('src', './images/zoom.png').addClass('zoom')
                    //     .css({
                    //         cursor: 'pointer',
                    //         'position': 'absolute',
                    //         'float': 'right',
                    //         right:-10,
                    //         top: -12,
                    //         width: 34,
                    //         height: 32
                    //     }));

                    // });

                    // 点击文字回到顶部
                    // $('.outer a').click(function(e) {
                    //     e.stopPropagation();
                    //     e.stopImmediatePropagation();

                    // });
                    // 点击出现第二个轮播
                    $('.outer').hover(function() {
                        $(this).addClass('active');
                    },
                    function() {
                        $(this).removeClass('active');
                    }).click(
                    function(e) {
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        var cls = $(this).attr('class').split(' ')[0];
                        var p = $(this).find('div');
                        var img = p.find('.spic-back img:first').attr('src');
                        var preview = $('.preview');
                        var l = $('.image-slider').width() / 2 - preview.outerWidth() / 2;
                        var t = (p.offset().top - preview.height());
                        t -= t / 2;
                        // preview.css({
                        //     left: l
                        // });
                        var text1 = p.find('a').eq(0).html();
                        var text2 = p.find('a').eq(1).html();
                        var text3 = p.find('a').eq(2).html();
                        preview.find('img:first').attr('src', img).addClass(cls);
                        preview.find('.label').eq(0).html(text1);
                        preview.find('.label').eq(1).html(text2);
                        preview.find('.label').eq(2).html(text3);
                        preview.fadeIn("slow");


                    });

                    forwardbutton.click(function() {
                        left -= w;
                        if (left + maxScroll >= 0) {
                            $('.contents-wrapper').stop(false, true).animate({
                                left: '-=' + w
                            },
                            settings.speed);

                        }
                        else
                        left += w;

                    });
                    backbutton.click(function() {
                        if (left < 0) {
                            left += w;
                            $('.contents-wrapper').stop(false, true).animate({
                                left: '+=' + w
                            },
                            settings.speed);

                        }

                    });

                }
                initialize();

                function getDimensions(value) {
                    return value + 'px';

                }


            });

        }

    });

})(jQuery);
