(function (namespace, $){
    'use strict';

    var URL = function () {

        var o = this;

        $(document).ready(function () {
            o.initialize();
        });
    };

    URL.TEMPLATES = {
        'leads-change-status': '/lead/change-status'
    };

    var p = URL.prototype;

    p.initialize = function () {
        console.log('Url initialized');
    };

    p.to = function (obj) {

        var temp = URL.TEMPLATES[Object.keys(obj)[0]];

        if (temp !== undefined) {

            console.log(temp);

        } else {
            throw new Error('url not found');
        }
    };

    namespace.URL = new URL;

}(window.App, jQuery));
