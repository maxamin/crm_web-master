(function (namespace, $){
    'use strict';

    var Widget = function () {

        var o = this;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Widget.prototype;

    p.initialize = function () {

        this._enableEvents();

        console.log('Widget initialized');
    };

    p._enableEvents = function () {

        var o = this;

        $('.widget-pjax-create').on('pjax:end', function(e){
            o._handlePjaxCreateEnd(e);
        });
    };

    p._handlePjaxCreateEnd = function (e) {

        window.materialadmin.AppForm.initialize();
        window.App.Form.initialize();

        $.pjax.reload($(e.currentTarget).data('reload-container'));
    };

    namespace.Widget = new Widget;

}(window.App, jQuery));

