(function (namespace, $){
    'use strict';

    var Form = function () {

        var o = this;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Form.prototype;

    p.initialize = function () {

        this._enableEvents();
        this._initInputMask();
        this._initSelect2();
        this._initProductStatusSelect();
        this._initLeadStatusSelectric();
        this._initDatePicker();
        this._initStaticInputMask();

        console.log('Form initialized');
    };

    p._enableEvents = function () {

        var o = this;

        $(document.body).on('click', '.btn-group.radio-uncheckable .btn.active', function(e) {
            o._handleRadioUncheckable(e);
        });
    };

    p._initInputMask = function () {

        $(":input").inputmask();

    };

    p._initSelect2 = function () {

        $('select.select2-list').select2({
            formatNoMatches: function () {
                return "<b>Подходящих записей не найдено.</b>";
            },
            placeholder: function () {

                return $(this).data('placeholder');
            },
            allowClear: function () {

                return $(this).data('allow-clear');
            }
        });
    };

    p._initProductStatusSelect = function () {

        $('select.product-status').each(function () {

            var $selectric;
            var $selectricWrapper;
            var $selectricItems;

            $(this).selectric({
                onInit: function (select) {
                    $selectricWrapper = $(this).closest('.selectric-wrapper');
                    $selectric = $selectricWrapper.find('.selectric');
                    $selectricItems = $selectricWrapper.find('.selectric-items');

                    $selectricItems.find('li').each(function () {
                        var $option = $(select).find('option[data-index="' + $(this).data('index') + '"]');

                        $(this).attr('style', $option.data('style'));
                    });

                    $selectric.attr('style', $selectricItems.find('li.selected').attr('style'));
                },

                onChange: function (element) {
                    $(element).change();
                    $selectric.attr('style', $selectricItems.find('li.selected').attr('style'));
                },

                onRefresh: function (select) {
                    $selectricItems.find('li').each(function () {
                        var $option = $(select).find('option[data-index="' + $(this).data('index') + '"]');
                        $(this).attr('style', $option.data('style'));
                    });

                    $selectric.attr('style', $selectricItems.find('li.selected').attr('style'));
                }
            });
        });
    };

    p._initLeadStatusSelectric = function () {

        var $selectric;
        var $selectricWrapper;
        var $selectricItems;

        $('select.lead-status').selectric({
            onInit: function (select) {
                $selectricWrapper = $(this).closest('.selectric-wrapper');
                $selectric = $selectricWrapper.find('.selectric');
                $selectricItems = $selectricWrapper.find('.selectric-items');

                $selectricItems.find('li').each(function () {
                    var $option = $(select).find('option[data-index="' + $(this).data('index') + '"]');
                    $(this).attr('style', $option.data('style'));
                });

                $selectric.attr('style', $selectricItems.find('li.selected').attr('style'));
            },

            onChange: function (element) {
                $(element).change();
                $selectric.attr('style', $selectricItems.find('li.selected').attr('style'));
            },

            onRefresh: function (select) {
                $selectricItems.find('li').each(function () {
                    var $option = $(select).find('option[data-index="' + $(this).data('index') + '"]');
                    $(this).attr('style', $option.data('style'));
                });

                $selectric.attr('style', $selectricItems.find('li.selected').attr('style'));
            }
        });
    };

    p._handleRadioUncheckable = function (e) {

        e.stopImmediatePropagation();
        e.preventDefault();

        var $current = $(e.currentTarget);

        $current.removeClass('active');
        $current.find('input:radio').prop('checked', false).change();
    };

    p._initDatePicker = function (e) {

        $('input.datepicker').datepicker({
            format: 'dd.mm.yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'ru'
        });
    };

    p._initStaticInputMask = function () {

        $(".mask").inputmask();
    };

    namespace.Form = new Form;

}(window.App, jQuery));
