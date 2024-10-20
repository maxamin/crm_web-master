(function (namespace, $){
    'use strict';

    var Profile = function () {

        var o = this;

        this.errorMessages = [];

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Profile.prototype;

    p.initialize = function () {

        this._cacheDOMElements();
        this._enableEvents();
        this._initFileReader();

        console.log('Profile initialized');
    };

    p._cacheDOMElements = function () {

    };

    p._enableEvents = function () {

        var o = this;

        $('input.avatar-file').on('change', function (e) {
            o._handleAvatarFileChange(e);
        });
    };

    p._handleAvatarFileChange = function (e) {

        var o = this;

        var file = e.currentTarget.files[0];

        if (typeof file == 'undefined' || file.length <= 0) {
            return false;
        }

        if (!this._validateAvatar(file)) {

            this.errorMessages.forEach(function (item) {
                toastr.error(item);
            });

            this.errorMessages.length = 0;

            return false;
        }

        this._$preview = $(e.currentTarget).closest('.avatar-widget').find('.jcrop-preview');

        this._$preview.on('load', function (e) {

            if ($(e.currentTarget).data('Jcrop')) {
                $(e.currentTarget).data('Jcrop').destroy();
            }

            $(e.currentTarget).off('load');

            $(e.currentTarget).Jcrop({
                aspectRatio: 1,
                minSize: [100, 100],
                setSelect: [50, 50, 200, 200],
                boxWidth: 800,
                boxHeight: 600,
                onChange: function (c) {
                    o._setCords(c);
                },
                onSelect: function (c) {
                    o._setCords(c);
                }
            });
        });

        this._fileReader.readAsDataURL(file);
    };

    p._validateAvatar = function (file) {

        var filter = /^(image\/jpeg|image\/png)$/i;

        if (!filter.test(file.type)) {
            this.errorMessages.push('Разрешена загрузка файлов только со следующими расширениями: png, jpg.');
        }

        return !(typeof this.errorMessages !== 'undefined' && this.errorMessages.length > 0);
    };

    p._initFileReader = function () {

        var o = this;

        this._fileReader = new FileReader();

        this._fileReader.onload = function (e) {

            o._$preview.attr('src', e.target.result);
        };
    };

    p._setCords = function (c) {

        var $container = this._$preview.closest('.avatar-widget');

        $container.find('#profile-x1').val(c.x);
        $container.find('#profile-x2').val(c.x2);
        $container.find('#profile-y1').val(c.y);
        $container.find('#profile-y2').val(c.y2);
    };

    namespace.Profile = new Profile;

}(window.App.Model, jQuery));
