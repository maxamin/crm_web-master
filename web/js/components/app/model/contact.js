(function (namespace, $){
    'use strict';

    var Contact = function () {

        var o = this;

        this.searchDebounce = 500;
        this.minSearchLength = 3;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Contact.prototype;

    p.initialize = function () {

        this._cacheDOMElements();
        this._enableEvents();

        console.log('Contact initialized');
    };

    p._cacheDOMElements = function () {

        this.infos = {
            key: $('#contactsInfoKey').data('key'),
            template: $('#contactsInfosTemplate').html(),
            $container: $('#contactsInfosContainer')
        };

        var $sContactsTmpl = $('#searchContactsTmpl');

        this.links = {
            key: $('#contactsLinkKey').data('key'),
            keep: $('#contactsLinkKeep').data('keep'),
            template: $('#contactsLinkTemplate').html(),
            searchTemplate: $sContactsTmpl.length ? Handlebars.compile($sContactsTmpl.html()) : undefined,
            $searchContainer: $('#searchContactsContainer'),
            $searchResult: $('#searchContactsResult'),
            $container: $('#contactsLinkContainer')
        };

        var $sContactsNameOkpoTmpl = $('#searchContactsNameOkpoTmpl');

        this.searchByName = {
            template: $sContactsNameOkpoTmpl.length ? Handlebars.compile($sContactsNameOkpoTmpl.html()) : undefined,
            $container: $('#searchContactsNameContainer'),
            $result: $('#searchContactsNameResult')
        };

        this.searchByOkpo = {
            template: $sContactsNameOkpoTmpl.length ? Handlebars.compile($sContactsNameOkpoTmpl.html()) : undefined,
            $container: $('#searchContactsOkpoContainer'),
            $result: $('#searchContactsOkpoResult')
        };
    };

    p._enableEvents = function () {

        var o = this;

        $('#newContactsInfos').on('click', function(e) {
            o.infos.key ++;
            o.infos.$container.append(o.infos.template.replace(/temp_key/g, 'new' + o.infos.key));
        });

        $('#searchContacts').on('input', function (e) {
            o._handleSearchLegal(e);
        });

        $(document).on('click', 'a.link-contact', function (e) {
            o._handleLinkContact(e);
        });

        $(document).on('click', 'a.remove-keep-link', function (e) {
            o.links.keep.splice(o.links.keep.indexOf($(e.currentTarget).data('id')), 1); //remove link from keep
        });

        $('.name-search').on('input', function (e) {
            o._handleSearchByName(e);
        });

        $('.name-search').on('blur', function (e) {

            if (!($(window.Helpers.clicky).closest('#searchContactsNameContainer').length > 0)) {
                o.searchByName.$result.html('');
                o.searchByName.$container.hide();
            }
        });

        $('.okpo-search').on('input', function (e) {
            o._handleSearchByOkpo(e);
        });
    };
    
    p._handleSearchByOkpo = function (e) {

        var o = this;

        window.Helpers.debounce(function () {
            var sVal = $(e.currentTarget).val();
            if (sVal.length >= o.minSearchLength && sVal != $(e.currentTarget).data('okpo')) {

                var queryData = {
                    'okpo': sVal
                };

                $.ajax({
                    method: 'GET',
                    url: $(e.currentTarget).data('url'),
                    data: queryData,
                    success: function (data) {
                        if (typeof data !== 'undefined' && data.length > 0) {
                            var contacts = {
                                contacts: data
                            };

                            o.searchByOkpo.$result.html(o.searchByOkpo.template(contacts));
                            o.searchByOkpo.$container.show();
                        } else {
                            o.searchByOkpo.$result.html('');
                            o.searchByOkpo.$container.hide();
                        }
                    }
                });

            } else {
                o.searchByOkpo.$result.html('');
                o.searchByOkpo.$container.hide();
            }
        }, o.searchDebounce);
    };

    p._handleSearchByName = function (e) {

        var o = this;

        window.Helpers.debounce(function () {
            var sVal = $(e.currentTarget).val();
            if (sVal.length >= o.minSearchLength && sVal !== $(e.currentTarget).data('name')) {

                var queryData = {
                    'name': sVal
                };

                $.ajax({
                    method: 'GET',
                    url: $(e.currentTarget).data('url'),
                    data: queryData,
                    success: function (data) {

                        if (typeof data !== 'undefined' && data.length > 0) {
                            var contacts = {
                                contacts: data
                            };

                            o.searchByName.$result.html(o.searchByName.template(contacts));
                            o.searchByName.$container.show();
                            $('.nano').nanoScroller();
                        } else {
                            o.searchByName.$result.html('');
                            o.searchByName.$container.hide();
                        }
                    }
                });

            } else {
                o.searchByName.$result.html('');
                o.searchByName.$container.hide();
            }
        }, o.searchDebounce);
    };

    p._handleSearchLegal = function (e) {

        var o = this;

        window.Helpers.debounce(function () {
            var sVal = $(e.currentTarget).val();
            if (sVal.length >= o.minSearchLength) {

                var queryData = {
                    'q': sVal
                };

                $.ajax({
                    method: 'GET',
                    url: $(e.currentTarget).data('url'),
                    data: queryData,
                    success: function (data) {

                        if (typeof data !== 'undefined' && data.length > 0) {
                            var contacts = {
                                contacts: data
                            };

                            o.links.$searchResult.html(o.links.searchTemplate(contacts));
                            o.links.$searchContainer.show();
                            $('.nano').nanoScroller();
                        } else {
                            o.links.$searchResult.html('');
                            o.links.$searchContainer.hide();
                        }
                    }
                });

            } else {
                o.links.$searchResult.html('');
                o.links.$searchContainer.hide();
            }
        }, o.searchDebounce);
    };

    p._handleLinkContact = function (e) {

        var id = $(e.currentTarget).data('id');
        var okpo = $(e.currentTarget).data('okpo');
        var name = $(e.currentTarget).data('name');

        if (this.links.keep.indexOf(id) === -1) { //if link is not in keep
            this.links.key ++;
            this.links.$container.append(
                this.links.template
                    .replace(/temp_key/g, 'new' + this.links.key)
                    .replace(/temp_id/g, id)
                    .replace(/temp_okpo/g, okpo)
                    .replace(/temp_name/g, name)
            );
            this.links.keep.push(id); //add link to keep
        }

        this.links.$searchResult.html('');
        this.links.$searchContainer.hide();
    };

    namespace.Contact = new Contact;

}(window.App.Model, jQuery));
