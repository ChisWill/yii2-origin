(function () {
    var $ = {
        now: function () {
            return Date.parse(new Date()) / 1000;
        },
        get: function (key, defaultValue) {
            var value = window.sessionStorage.getItem(key);
            if (value) {
                return JSON.parse(value);
            } else if (defaultValue) {
                return defaultValue;
            } else {
                return {};
            }
        },
        set: function (key, value) {
            window.sessionStorage.setItem(key, JSON.stringify(value));
        },
        ajax: function (params) {
            params = params || {};
            var data = '',
                d = '';
            for (var k in params) {
                data += d + k + '=' + params[k];
                d = '&';
            }
            data = data ? '?' + data : '';
            var xhr = new XMLHttpRequest(),
                isAsync = !/firefox/.test(navigator.userAgent.toLowerCase());
            xhr.open('get', '/site/trace' + data, isAsync);
            xhr.send();
        },
        submit: function (duration) {
            this.ajax({
                pathname: C.pathname,
                referrer: C.referrer,
                title: C.title,
                duration: duration
            });
        }
    };
    var C = {
        startTime: $.now(),
        domain: document.domain,
        referrer: document.referrer,
        title: document.title,
        pathname: window.location.pathname
    };
    var V = {};
    window.onbeforeunload = function () {
        V.page = C.domain + C.pathname;
        V.lastPage = $.get('LAST_PAGE', '');
        if (V.lastPage != V.page) {
            $.submit($.now() - C.startTime);
            $.set('LAST_PAGE', V.page);
        }
    };
})();