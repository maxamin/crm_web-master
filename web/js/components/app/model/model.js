(function (namespace, $){
    'use strict';

    var Model = function () {

        this.contextMenu = {
            hidden: true
        };

        var o = this;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Model.prototype;

    p.initialize = function () {

        this._cacheDOMElements();
        this._enableEvents();

        console.log('Model initialized');
    };

    p._cacheDOMElements = function () {

        var $modal = $('#removeModelConfirm');

        this.deleteModal = {
            $modal: $modal,
            $confirm: $modal.find('#removeModelLink'),
            $message: $modal.find('#removeModelConfirmMessage')
        };

        var $container = $('#modelRowContextMenuContainer');
        var $menu = $container.find('#modelRowContextMenu');

        this.contextMenu = {
            $container: $container,
            $menu: $menu,
            $updateBtn: $menu.find('.update-model-btn'),
            $deleteBtn: $menu.find('.delete-model-btn')
        };
    };

    p._enableEvents = function () {

        var o = this;

        $(document).on('click', '.delete-model-btn', function (e) {

            o._handleDeleteConfirm(e);
        });

        $(document).on('click', '.btn-unlink', function (e) {

            o._handleUnlinkConfirm(e);
        });

        $(document).on('contextmenu', '.model-row', function (e) {

            o._handleContextMenu(e);
        });

        this.deleteModal.$confirm.on('click', function (e) {

            o._handleDelete(e);
        });

        this.contextMenu.$container.on({
            'click' : function (e) {
                o.contextMenu.hidden = true;
                $(e.currentTarget).removeClass('show');
            },
            'contextmenu' : function (e) {
                if (!($(e.target).closest('a').length > 0)) {
                    e.preventDefault();
                    o.contextMenu.hidden = true;
                    $(e.currentTarget).removeClass('show');
                }
            },
            'mouseleave' : function () {
                if (o.contextMenu.hidden === true) {
                    o.contextMenu.$row.removeClass('tr-hover');
                }
            }
        });

    };

    p._handleDeleteConfirm = function (e) {

        var message = 'Вы действительно хотите удалить <b>' + $(e.currentTarget).data('name') + '</b> ?';

        this.deleteModal.$message.html(message);
        this.deleteModal.$confirm
            .attr('data-ajax', 0)
            .attr('href', $(e.currentTarget).data('href'))
            .attr('data-method', 'post');

        this.deleteModal.$modal.modal();
    };

    p._handleUnlinkConfirm = function (e) {

        var message = 'Вы действительно хотите удалить связь с <b>' + $(e.currentTarget).data('name') + '</b> ?';

        this.deleteModal.$message.html(message);
        this.deleteModal.$confirm
            .attr('data-ajax', 1)
            .attr('href', $(e.currentTarget).data('href'))
            .attr('data-container-selector', $(e.currentTarget).data('container-selector'))
            .removeAttr('data-method');

        this.deleteModal.$modal.modal();
    };

    p._handleDelete = function (e) {

        if ($(e.currentTarget).data('ajax') == 1) {
            e.preventDefault();

            var $container = $($(e.currentTarget).data('container-selector'));

            $.ajax({
                url: $(e.currentTarget).attr('href'),
                method: 'POST',
                success: function (data) {

                    if ($container) {
                        $container.remove();
                    }

                    toastr[data.status](data.message);
                }
            });

            this.deleteModal.$modal.modal('hide');
        }
    };

    p._handleContextMenu = function (e) {

        if (!($(e.target).closest('a').length > 0) && !($(e.target).closest('button').length > 0)) {
            e.preventDefault();

            this.contextMenu.$row = $(e.currentTarget);
            this.contextMenu.$row.addClass('tr-hover');

            this.contextMenu.$updateBtn.attr('href', $(e.currentTarget).data('update'));
            this.contextMenu.$deleteBtn
                .data('name', $(e.currentTarget).data('name'))
                .data('href', $(e.currentTarget).data('delete'));

            this.contextMenu.$menu.css({
                top: e.pageY + 1 + "px",
                left: e.pageX + 1 + "px"
            });

            this.contextMenu.hidden = false;
            this.contextMenu.$container.addClass('show');
        }
    };

    namespace.Model = new Model;

}(window.App, jQuery));
