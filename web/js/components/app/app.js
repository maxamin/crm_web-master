(function (namespace, $){
    'use strict';

    var App = function () {

        var o = this;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = App.prototype;

    p.initialize = function () {

        this._initDefaultConfig();
        this._enableEvents();

        console.log('App initialized');
    };

    p._initDefaultConfig = function () {

        this._initToastr();
    };

    p._initToastr = function () {
        toastr.options = {
            "closeButton" : true,
            "progressBar" : false,
            "debug" : false,
            "positionClass" : 'toast-top-right',
            "showDuration" : 330,
            "hideDuration" : 330,
            "timeOut" :  3000,
            "extendedTimeOut" : 1000,
            "showEasing" : 'swing',
            "hideEasing" : 'swing',
            "showMethod" : 'fadeIn',
            "hideMethod" : 'fadeOut',
            "onclick" : null
        }
    };

    p._enableEvents = function () {

        var o = this;

        $(document.body).on('click', '.remove-btn', function () {
            $(document.body).find($(this).data('container-selector')).remove();
        });

        $(document.body).on('click', '.hide-btn', function () {
            $(document.body).find($(this).data('container-selector')).hide($(this).data('hide-time'));
        });

        $(document.body).on('click', '.show-btn', function () {
            $(document.body).find($(this).data('container-selector')).show($(this).data('hide-time'));
        });

        $(document.body).on('click', '.as-link', function (e) {
            o._handleClickAsLink(e);
        });

        $(document.body).on('mousedown', '.as-link', function (e) {
            o._handleMouseDownAsLink(e);
        });
    };

    p._handleClickAsLink = function (e) {
        if (!($(e.target).closest('a').length > 0) && !($(e.target).closest('button').length > 0)) {

            window.location.href = $(e.currentTarget).data('href');
        }
    };

    p._handleMouseDownAsLink = function (e) {

        if (this.asLink) {
            $(this.asLink).off('mouseup');
        }

        this.asLink = e.currentTarget;

        $(this.asLink).on('mouseup', function (e) {

            if (!($(e.target).closest('a').length > 0) && !($(e.target).closest('button').length > 0)) {
                switch (e.which) {
                    case 2:
                        window.open($(e.currentTarget).data('href'), '_blank');
                        break;
                }
            }
        });
    };

    namespace.App = new App;

}(window, jQuery));
