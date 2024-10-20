(function (namespace, $){
    'use strict';

    var Task = function () {

        var o = this;

        this.searchDebounce = 500;
        this.minSearchLength = 3;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Task.prototype;

    p.initialize = function () {

        this._cacheDOMElements();
        this._enableEvents();

        console.log('Task initialized');
    };

    p._cacheDOMElements = function () {

        var $sRelatedTmpl = $('#searchRelatedTmpl');

        this.related = {
            searchTemplate: $sRelatedTmpl.length ? Handlebars.compile($sRelatedTmpl.html()) : undefined,
            $searchContainer: $('#searchRelatedContainer'),
            $searchResult: $('#searchRelatedResult'),
            $view: $('#viewRelated'),
            $search: $('#searchRelated'),
            $searchType: $('#searchRelatedType'),
            $searchInput: $('#searchRelatedInput'),
            $id: $('#tasks-relation_id'),
            $type: $('#tasks-relation_type')
        }

    };

    p._enableEvents = function () {

        var o = this;

        $(document).on('click', 'a.display-type', function (e) {
            $('#day').val($(e.currentTarget).data('type')).change();
        });

        $(document).on('submit', '.close-task-form', function (e) {
            o._handleClose(e);
        });

        this.related.$searchType.on('change', function (e) {
            o.related.$searchInput.trigger('input');
        });

        this.related.$searchInput.on('input', function (e) {
            o._handleSearchRelated(e);
        });

        $(document).on('click', 'a.link-task-related', function (e) {
            o._handleLinkRelated(e);
        });

        $(document).on('click', 'a.remove-task-related', function (e) {
            o._handleUnlinkRelated(e);
        });
    };

    p._handleUnlinkRelated = function (e) {

        this.related.$id.val('');
        this.related.$type.val('');
        this.related.$search.show();
        this.related.$searchInput.focus();
    };

    p._handleLinkRelated = function (e) {

        this.related.$id.val($(e.currentTarget).data('id'));
        this.related.$type.val(this.related.$searchType.find('option:checked').val());

        this.related.$searchResult.html('');

        this.related.$searchContainer.hide();
        this.related.$search.hide();

        this.related.$searchInput.val('');
        this.related.$searchInput.change();

        this.related.$view.find('#relatedLinkView').attr('href', $(e.currentTarget).data('url'));
        this.related.$view.find('#relatedName').html($(e.currentTarget).data('name'));
        this.related.$view.find('#relatedType').html(this.related.$searchType.find('option:checked').html());
        this.related.$view.show();
    };

    p._handleSearchRelated = function (e) {

        var o = this;

        window.Helpers.debounce(function () {
            var sVal = $(e.currentTarget).val();
            if (sVal.length >= o.minSearchLength) {

                var queryData = {
                    'q': sVal
                };

                $.ajax({
                    method: 'GET',
                    url: o.related.$searchType.find('option:checked').data('url'),
                    data: queryData,
                    success: function (data) {
                        if (typeof data !== 'undefined' && data.length > 0) {

                            data.forEach(function (item, i , arr) {
                                item.url = o.related.$searchType.find('option:checked').data('url-view') + '/' + item.id;
                            });

                            var related = {
                                related: data
                            };

                            o.related.$searchResult.html(o.related.searchTemplate(related));
                            o.related.$searchContainer.show();
                            $('.nano').nanoScroller();

                        } else {
                            o.related.$searchResult.html('');
                            o.related.$searchContainer.hide();
                        }
                    }
                });

            } else {
                o.related.$searchResult.html('');
                o.related.$searchContainer.hide();
            }
        }, o.searchDebounce);
    };

    p._handleClose = function (e) {

        e.preventDefault();

        var form = e.currentTarget;

        $.ajax({
            method: $(form).attr('method'),
            data: $(form).serialize(),
            url: $(form).attr('action'),
            success: function (data) {
                switch (data.status) {
                    case 'success':

                        $(form).closest('.popover').popoverX('hide');

                        var $taskContainer = $($(form).data('task-container-selector'));

                        $taskContainer.removeClass('expired');
                        $taskContainer.find('span.task-status').removeClass('opened').addClass('closed').html('Закрыта');

                        $taskContainer.find('.close-user').html(data.closeUser);
                        toastr.success(data.message);
                        break;
                    case 'error':
                        toastr.error(data.message);
                        break;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(jqXHR.responseText);
            }
        });
    };

    namespace.Task = new Task;

}(window.App.Model, jQuery));

