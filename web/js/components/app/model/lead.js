(function (namespace, $){
    'use strict';

    var Lead = function () {

        var o = this;

        this.searchDebounce = 500;
        this.minSearchLength = 3;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Lead.prototype;

    p.initialize = function () {

        this._cacheDOMElements();
        this._enableEvents();

        console.log('Lead initialized');
    };

    p._cacheDOMElements = function () {

        var $sContactsTmpl = $('#searchContactsTmpl');
        var $contactTmpl = $('#contactTmpl');

        this.contacts = {
            searchTemplate: $sContactsTmpl.length ? Handlebars.compile($sContactsTmpl.html()) : undefined,
            template: $contactTmpl.length ? Handlebars.compile($contactTmpl.html()) : undefined,
            $searchType: $('#searchContactType'),
            $searchInput: $('#searchContactInput'),
            $searchContainer: $('#searchContactsContainer'),
            $container: $('#contactsContainer'),
            $result: $('#searchContactsResult'),
            $view: $('#viewContact'),
            $id: $('#leads-contacts_id'),
            $search: $('#searchContact')
        };

    };

    p._enableEvents = function () {

        var o = this;

        $('.change-lead-status').on('change', function (e) {
            var $selected = $(e.currentTarget).find(':selected');

            o.changeStatus($selected.data('id'), $selected.data('status'));
        });

        $('.change-product-status').on('change', function (e) {
            var $selected = $(e.currentTarget).find(':selected');

            o.changeProductStatus($selected.data('id'), $selected.data('status'));
        });

        this.contacts.$searchType.on('change', function (e) {
            o.contacts.$searchInput.trigger('input');
        });

        this.contacts.$searchInput.on('input', function (e) {
            o._handleSearchContacts(e);
        });

        $(document).on('click', 'a.link-lead-contact', function (e) {
            o._handleLinkLeadContact(e);
        });

        $(document).on('click', 'a.remove-lead-contact', function (e) {
            o._handleRemoveLeadContact(e);
        });

    };

    p._handleSearchContacts = function (e) {

        var o = this;

        window.Helpers.debounce(function () {
            var sVal = $(e.currentTarget).val();
            if (sVal.length >= o.minSearchLength) {

                var queryData = {
                    'q': sVal
                };

                $.ajax({
                    method: 'GET',
                    url: o.contacts.$searchType.find('option:checked').data('url'),
                    data: queryData,
                    success: function (data) {
                        if (typeof data !== 'undefined' && data.length > 0) {

                            data.forEach(function (item, i , arr) {
                                item.url = o.contacts.$searchType.find('option:checked').data('url-view') + '/' + item.id;
                            });

                            var contacts = {
                                contacts: data
                            };

                            o.contacts.$result.html(o.contacts.searchTemplate(contacts));
                            o.contacts.$searchContainer.show();
                            $('.nano').nanoScroller();

                        } else {
                            o.contacts.$result.html('');
                            o.contacts.$searchContainer.hide();
                        }
                    }
                });

            } else {
                o.contacts.$result.html('');
                o.contacts.$searchContainer.hide();
            }
        }, o.searchDebounce);
    };

    p._handleLinkLeadContact = function (e) {

        var contact = {
            id: $(e.currentTarget).data('id'),
            okpo: $(e.currentTarget).data('okpo'),
            name: $(e.currentTarget).data('name')
        };

        this.contacts.$id.val(contact.id);

        this.contacts.$result.html('');

        this.contacts.$searchContainer.hide();
        this.contacts.$search.hide();

        this.contacts.$searchInput.val('');
        this.contacts.$searchInput.change();

        this.contacts.$container.html(this.contacts.template(contact));
        this.contacts.$view.show();
    };

    p._handleRemoveLeadContact = function (e) {

        this.contacts.$id.val('');
        this.contacts.$search.show();
        this.contacts.$searchInput.focus();
    };

    p.changeStatus = function (id, status) {

        /*
        var url = window.App.URL.to({'leads-change-status' : {
            id: id,
            status: status
        }});
        */

        var url =  '/lead/change-status?id=_id&status=_status'.replace(/_id/g, id).replace(/_status/, status);

        $.ajax({
            url: url,
            method: 'POST',
            success: function (data) {
                toastr[data.status](data.message);
            }
        });
    };

    p.changeProductStatus = function (id, status) {

        /*
         var url = window.App.URL.to({'products-change-status' : {
         id: id,
         status: status
         }});
         */

        var url =  '/lead/change-product-status?id=_id&status=_status'.replace(/_id/g, id).replace(/_status/, status);

        $.ajax({
            url: url,
            method: 'POST',
            success: function (data) {
                toastr[data.status](data.message);
            }
        });
    };

    p.setProductStyle = function ($select) {
        var style = $select.find('option:selected').data('style');

        $select.attr('style', style);
    };

    namespace.Lead = new Lead;

}(window.App.Model, jQuery));
