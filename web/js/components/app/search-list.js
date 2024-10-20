(function (namespace, $){
    'use strict';

    var SearchList = function () {

        var o = this;

        this.searchDebounce = 500;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = SearchList.prototype;

    p.initialize = function () {

        this._enableEvents();

        console.log('SearchList initialized');
    };

    p._enableEvents = function () {

        var o = this;

        $(document).on('input', '.pjax-search input[type="text"]', function (e) {
            o._handleTextInput(e);
        });

        $(document).on('change', '.pjax-search input:not([type="text"])', function (e) {
            o._handleInputChange(e);
        });

        $(document).on('change', '.pjax-search select', function (e) {
            o._handleInputChange(e);
        });

        $('.pjax-search').on('pjax:end', function(e) {
            o._handlePjaxSearchEnd(e);
        });

        $('.pjax-list').on('pjax:end', function (e) {
            o._handlePjaxListEnd(e);
        });
    };

    p._handleTextInput = function (e) {

        var o = this,
            eventInput = e.currentTarget;

        window.Helpers.debounce(function () {

            o.eventInput = eventInput;

            $(eventInput).closest('form').submit();

        }, o.searchDebounce);
    };

    p._handleInputChange = function (e) {

        this.eventInput = e.currentTarget;

        $(this.eventInput).closest('form').submit();
    };

    p._handlePjaxSearchEnd = function (e) {

        if (this.eventInput && $(this.eventInput).is('input[type="text"]')) {

            $(e.currentTarget).find('#' + this.eventInput.id).focus()
                .selectRange(this.eventInput.selectionStart, this.eventInput.selectionEnd);
        }

        this.eventInput = null;

        window.App.Form.initialize();

        $.pjax.reload($(e.currentTarget).data('reload-container'));
    };

    p._handlePjaxListEnd = function (e) {

        $(e.currentTarget).find('[data-toggle="popover-x"]').each(function () {
            if ($(this).is('[data-toggle="popover-x"]')) {
                $(this).popoverButton();
            }
        });

        window.App.Form.initialize();
    };

    namespace.SearchList = new SearchList;

}(window.App, jQuery));


