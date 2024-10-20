(function (namespace, $){
    'use strict';

    var Dashboard = function () {

        var o = this;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Dashboard.prototype;

    p.initialize = function () {
        this._initLeadsSortable();
        console.log('Dashboard initialized');
    };

    p._initLeadsSortable = function () {

        $('.leads-sortable').sortable({
            cursor: 'move',
            revert: true,
            containment : '.leads-sortable-container',
            placeholder: 'leads-sortable-highlight',
            forcePlaceholderSize: true,
            connectWith: '.leads-sortable',
            receive: function (event, ui) {
                window.App.Model.Lead.changeStatus($(ui.item).data('id'), $(event.target).data('status-id'));
            }
        }).disableSelection();
    };

    namespace.Dashboard = new Dashboard;

}(window.App, jQuery));