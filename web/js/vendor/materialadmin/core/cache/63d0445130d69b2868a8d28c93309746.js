(function($) {
    "use strict";
    var App = function() {
        var o = this;
        $(document).ready(function() {
            o.initialize();
        });
    };
    var p = App.prototype;
    App.SCREEN_XS = 480;
    App.SCREEN_SM = 768;
    App.SCREEN_MD = 992;
    App.SCREEN_LG = 1200;
    p._callFunctions = null;
    p._resizeTimer = null;
    p.initialize = function() {
        this._enableEvents();
        this._initBreakpoints();
        this._initInk();
        this._initAccordion();
    };
    p._enableEvents = function() {
        var o = this;
        $(window).on('resize', function(e) {
            clearTimeout(o._resizeTimer);
            o._resizeTimer = setTimeout(function() {
                o._handleFunctionCalls(e);
            }, 300);
        });
    };
    p.getKnobStyle = function(knob) {
        var holder = knob.closest('.knob');
        var options = {
            width: Math.floor(holder.outerWidth()),
            height: Math.floor(holder.outerHeight()),
            fgColor: holder.css('color'),
            bgColor: holder.css('border-top-color'),
            draw: function() {
                if (knob.data('percentage')) {
                    $(this.i).val(this.cv + '%');
                }
            }
        };
        return options;
    };
    p._initAccordion = function() {
        $('.panel-group .card .in').each(function() {
            var card = $(this).parent();
            card.addClass('expanded');
        });
        $('.panel-group').on('hide.bs.collapse', function(e) {
            var content = $(e.target);
            var card = content.parent();
            card.removeClass('expanded');
        });
        $('.panel-group').on('show.bs.collapse', function(e) {
            var content = $(e.target);
            var card = content.parent();
            var group = card.closest('.panel-group');
            group.find('.card.expanded').removeClass('expanded');
            card.addClass('expanded');
        });
    };
    p._initInk = function() {
        var o = this;
        $('.ink-reaction').on('click', function(e) {
            var bound = $(this).get(0).getBoundingClientRect();
            var x = e.clientX - bound.left;
            var y = e.clientY - bound.top;
            var color = o.getBackground($(this));
            var inverse = (o.getLuma(color) > 183) ? ' inverse' : '';
            var ink = $('<div class="ink' + inverse + '"></div>');
            var btnOffset = $(this).offset();
            var xPos = e.pageX - btnOffset.left;
            var yPos = e.pageY - btnOffset.top;
            ink.css({
                top: yPos,
                left: xPos
            }).appendTo($(this));
            window.setTimeout(function() {
                ink.remove();
            }, 1500);
        });
    };
    p.getBackground = function(item) {
        var color = item.css("background-color");
        var alpha = parseFloat(color.split(',')[3], 10);
        if ((isNaN(alpha) || alpha > 0.8) && color !== 'transparent') {
            return color;
        }
        if (item.is("body")) {
            return false;
        } else {
            return this.getBackground(item.parent());
        }
    };
    p.getLuma = function(color) {
        var rgba = color.substring(4, color.length - 1).split(',');
        var r = rgba[0];
        var g = rgba[1];
        var b = rgba[2];
        var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b;
        return luma;
    };
    p._initBreakpoints = function(alias) {
        var html = '';
        html += '<div id="device-breakpoints">';
        html += '<div class="device-xs visible-xs" data-breakpoint="xs"></div>';
        html += '<div class="device-sm visible-sm" data-breakpoint="sm"></div>';
        html += '<div class="device-md visible-md" data-breakpoint="md"></div>';
        html += '<div class="device-lg visible-lg" data-breakpoint="lg"></div>';
        html += '</div>';
        $('body').append(html);
    };
    p.isBreakpoint = function(alias) {
        return $('.device-' + alias).is(':visible');
    };
    p.minBreakpoint = function(alias) {
        var breakpoints = ['xs', 'sm', 'md', 'lg'];
        var breakpoint = $('#device-breakpoints div:visible').data('breakpoint');
        return $.inArray(alias, breakpoints) < $.inArray(breakpoint, breakpoints);
    };
    p.callOnResize = function(func) {
        if (this._callFunctions === null) {
            this._callFunctions = [];
        }
        this._callFunctions.push(func);
        func.call();
    };
    p._handleFunctionCalls = function(e) {
        if (this._callFunctions === null) {
            return;
        }
        for (var i = 0; i < this._callFunctions.length; i++) {
            this._callFunctions[i].call();
        }
    };
    window.materialadmin = window.materialadmin || {};
    window.materialadmin.App = new App;
}(jQuery));
(function(namespace, $) {
    "use strict";
    var AppNavigation = function() {
        var o = this;
        $(document).ready(function() {
            o.initialize();
        });
    };
    var p = AppNavigation.prototype;
    AppNavigation.MENU_MAXIMIZED = 1;
    AppNavigation.MENU_COLLAPSED = 2;
    AppNavigation.MENU_HIDDEN = 3;
    p._lastOpened = null;
    p.initialize = function() {
        this._enableEvents();
        this._invalidateMenu();
        this._evalMenuScrollbar();
    };
    p._enableEvents = function() {
        var o = this;
        $(window).on('resize', function(e) {
            o._handleScreenSize(e);
        });
        $('[data-toggle="menubar"]').on('click', function(e) {
            o._handleMenuToggleClick(e);
        });
        $('[data-dismiss="menubar"]').on('click', function(e) {
            o._handleMenubarLeave();
        });
        $('#main-menu').on('click', 'li', function(e) {
            o._handleMenuItemClick(e);
        });
        $('#main-menu').on('click', 'a', function(e) {
            o._handleMenuLinkClick(e);
        });
        $('body.menubar-hoverable').on('mouseenter', '#menubar', function(e) {
            setTimeout(function() {
                o._handleMenubarEnter();
            }, 1);
        });
    };
    p._handleScreenSize = function(e) {
        this._invalidateMenu();
        this._evalMenuScrollbar(e);
    };
    p._handleMenuToggleClick = function(e) {
        if (!materialadmin.App.isBreakpoint('xs')) {
            $('body').toggleClass('menubar-pin');
        }
        var state = this.getMenuState();
        if (state === AppNavigation.MENU_COLLAPSED) {
            this._handleMenubarEnter();
        } else if (state === AppNavigation.MENU_MAXIMIZED) {
            this._handleMenubarLeave();
        } else if (state === AppNavigation.MENU_HIDDEN) {
            this._handleMenubarEnter();
        }
    };
    p._handleMenuItemClick = function(e) {
        e.stopPropagation();
        var item = $(e.currentTarget);
        var submenu = item.find('> ul');
        var parentmenu = item.closest('ul');
        this._handleMenubarEnter(item);
        if (submenu.children().length !== 0) {
            this._closeSubMenu(parentmenu);
            var menuIsCollapsed = this.getMenuState() === AppNavigation.MENU_COLLAPSED;
            if (menuIsCollapsed || item.hasClass('expanded') === false) {
                this._openSubMenu(item);
            }
        }
    };
    p._handleMenubarEnter = function(menuItem) {
        var o = this;
        var offcanvasVisible = $('body').hasClass('offcanvas-left-expanded');
        var menubarExpanded = $('#menubar').data('expanded');
        var menuItemClicked = (menuItem !== undefined);
        if ((menuItemClicked === true || offcanvasVisible === false) && menubarExpanded !== true) {
            $('#content').one('mouseover', function(e) {
                o._handleMenubarLeave();
            });
            $('body').addClass('menubar-visible');
            $('#menubar').data('expanded', true);
            $('#menubar').triggerHandler('enter');
            if (menuItemClicked === false) {
                if (this._lastOpened) {
                    var o = this;
                    this._openSubMenu(this._lastOpened, 0);
                    this._lastOpened.parents('.gui-folder').each(function() {
                        o._openSubMenu($(this), 0);
                    });
                } else {
                    var item = $('#main-menu > li.active');
                    this._openSubMenu(item, 0);
                }
            }
        }
    };
    p._handleMenubarLeave = function() {
        $('body').removeClass('menubar-visible');
        if (materialadmin.App.minBreakpoint('md')) {
            if ($('body').hasClass('menubar-pin')) {
                return;
            }
        }
        $('#menubar').data('expanded', false);
        if (materialadmin.App.isBreakpoint('xs') === false) {
            this._closeSubMenu($('#main-menu'));
        }
    };
    p._handleMenuLinkClick = function(e) {
        if (this.getMenuState() !== AppNavigation.MENU_MAXIMIZED) {
            e.preventDefault();
        }
    };
    p._closeSubMenu = function(menu) {
        var o = this;
        menu.find('> li > ul').stop().slideUp(170, function() {
            $(this).closest('li').removeClass('expanded');
            o._evalMenuScrollbar();
        });
    };
    p._openSubMenu = function(item, duration) {
        var o = this;
        if (typeof(duration) === 'undefined') {
            duration = 170;
        }
        this._lastOpened = item;
        item.addClass('expanding');
        item.find('> ul').stop().slideDown(duration, function() {
            item.addClass('expanded');
            item.removeClass('expanding');
            o._evalMenuScrollbar();
            $('#main-menu ul').removeAttr('style');
        });
    };
    p._invalidateMenu = function() {
        var selectedLink = $('#main-menu a.active');
        selectedLink.parentsUntil($('#main-menu')).each(function() {
            if ($(this).is('li')) {
                $(this).addClass('active');
                $(this).addClass('expanded');
            }
        });
        if (this.getMenuState() === AppNavigation.MENU_COLLAPSED) {
            $('#main-menu').find('> li').removeClass('expanded');
        }
        if ($('body').hasClass('menubar-visible')) {
            this._handleMenubarEnter();
        }
        $('#main-menu').triggerHandler('ready');
        $('#menubar').addClass('animate');
    };
    p.getMenuState = function() {
        var matrix = $('#menubar').css("transform");
        var values = (matrix) ? matrix.match(/-?[\d\.]+/g) : null;
        var menuState = AppNavigation.MENU_MAXIMIZED;
        if (values === null) {
            if ($('#menubar').width() <= 100) {
                menuState = AppNavigation.MENU_COLLAPSED;
            } else {
                menuState = AppNavigation.MENU_MAXIMIZED;
            }
        } else {
            if (values[4] === '0') {
                menuState = AppNavigation.MENU_MAXIMIZED;
            } else {
                menuState = AppNavigation.MENU_HIDDEN;
            }
        }
        return menuState;
    };
    p._evalMenuScrollbar = function() {
        if (!$.isFunction($.fn.nanoScroller)) {
            return;
        }
        var footerHeight = $('#menubar .menubar-foot-panel').outerHeight();
        footerHeight = Math.max(footerHeight, 1);
        $('.menubar-scroll-panel').css({
            'padding-bottom': footerHeight
        });
        var menu = $('#menubar');
        if (menu.length === 0)
            return;
        var menuScroller = $('.menubar-scroll-panel');
        var parent = menuScroller.parent();
        if (parent.hasClass('nano-content') === false) {
            menuScroller.wrap('<div class="nano"><div class="nano-content"></div></div>');
        }
        var height = $(window).height() - menu.position().top - menu.find('.nano').position().top;
        var scroller = menuScroller.closest('.nano');
        scroller.css({
            height: height
        });
        scroller.nanoScroller({
            preventPageScrolling: true,
            iOSNativeScrolling: true
        });
    };
    window.materialadmin.AppNavigation = new AppNavigation;
}(this.materialadmin, jQuery));

(function(namespace, $) {
    "use strict";
    var AppOffcanvas = function() {
        var o = this;
        $(document).ready(function() {
            o.initialize();
        });
    };
    var p = AppOffcanvas.prototype;
    p._timer = null;
    p._useBackdrop = null;
    p.initialize = function() {
        this._enableEvents();
    };
    p._enableEvents = function() {
        var o = this;
        $(window).on('resize', function(e) {
            o._handleScreenSize(e);
        });
        $('.offcanvas').on('refresh', function(e) {
            o.evalScrollbar(e);
        });
        $(document.body).on('click','[data-toggle="offcanvas"]', function(e) {
            e.preventDefault();
            o._handleOffcanvasOpen($(e.currentTarget));
        });
        $(document.body).on('click','[data-dismiss="offcanvas"]', function(e) {
            o._handleOffcanvasClose();
        });
        $(document.body).on('click', '#base > .backdrop', function(e) {
            o._handleOffcanvasClose();
        });
        $('[data-toggle="offcanvas-left"].active').each(function() {
            o._handleOffcanvasOpen($(this));
        });
        $('[data-toggle="offcanvas-right"].active').each(function() {
            o._handleOffcanvasOpen($(this));
        });
    };
    p._handleScreenSize = function(e) {
        this.evalScrollbar(e);
    };
    p._handleOffcanvasOpen = function(btn) {
        if (btn.hasClass('active')) {
            this._handleOffcanvasClose();
            return;
        }
        var id = btn.attr('href');
        this._useBackdrop = (btn.data('backdrop') === undefined) ? true : btn.data('backdrop');
        this.openOffcanvas(id);
        this.invalidate();
    };
    p._handleOffcanvasClose = function(e) {
        this.closeOffcanvas();
        this.invalidate();
    };
    p.openOffcanvas = function(id) {
        this.closeOffcanvas();
        $(id).addClass('active');
        var leftOffcanvas = ($(id).closest('.offcanvas:first').length > 0);
        if (this._useBackdrop)
            $('body').addClass('offcanvas-expanded');
        var width = $(id).width();
        if (width > $(document).width()) {
            width = $(document).width() - 8;
            $(id + '.active').css({
                'width': width
            });
        }
        width = (leftOffcanvas) ? width : '-' + width;
        var translate = 'translate(' + width + 'px, 0)';
        $(id + '.active').css({
            '-webkit-transform': translate,
            '-ms-transform': translate,
            '-o-transform': translate,
            'transform': translate
        });
    };
    p.closeOffcanvas = function() {
        $('[data-toggle="offcanvas"]').removeClass('expanded');
        $('.offcanvas-pane').removeClass('active');
        $('.offcanvas-pane').css({
            '-webkit-transform': '',
            '-ms-transform': '',
            '-o-transform': '',
            'transform': ''
        });
    };
    p.toggleButtonState = function() {
        var id = $('.offcanvas-pane.active').attr('id');
        $('[data-toggle="offcanvas"]').removeClass('active');
        $('[href="#' + id + '"]').addClass('active');
    };
    p.toggleBackdropState = function() {
        if ($('.offcanvas-pane.active').length > 0 && this._useBackdrop) {
            this._addBackdrop();
        } else {
            this._removeBackdrop();
        }
    };
    p._addBackdrop = function() {
        if ($('#base > .backdrop').length === 0 && $('#base').data('backdrop') !== 'hidden') {
            $('<div class="backdrop"></div>').hide().appendTo('#base').fadeIn();
        }
    };
    p._removeBackdrop = function() {
        $('#base > .backdrop').fadeOut(function() {
            $(this).remove();
        });
    };
    p.toggleBodyScrolling = function() {
        clearTimeout(this._timer);
        if ($('.offcanvas-pane.active').length > 0 && this._useBackdrop) {
            var scrollbarWidth = this.measureScrollbar();
            var bodyPad = parseInt(($('body').css('padding-right') || 0), 10);
            if (scrollbarWidth !== bodyPad) {
                $('body').css('padding-right', bodyPad + scrollbarWidth);
                $('.headerbar').css('padding-right', bodyPad + scrollbarWidth);
            }
        } else {
            this._timer = setTimeout(function() {
                $('body').removeClass('offcanvas-expanded');
                $('body').css('padding-right', '');
                $('.headerbar').removeClass('offcanvas-expanded');
                $('.headerbar').css('padding-right', '');
            }, 330);
        }
    };
    p.invalidate = function() {
        this.toggleButtonState();
        this.toggleBackdropState();
        this.toggleBodyScrolling();
        this.evalScrollbar();
    };
    p.evalScrollbar = function() {
        if (!$.isFunction($.fn.nanoScroller)) {
            return;
        }
        var menu = $('.offcanvas-pane.active');
        if (menu.length === 0)
            return;
        var menuScroller = $('.offcanvas-pane.active .offcanvas-body');
        var parent = menuScroller.parent();
        if (parent.hasClass('nano-content') === false) {
            menuScroller.wrap('<div class="nano"><div class="nano-content"></div></div>');
        }
        var height = $(window).height() - menu.find('.nano').position().top;
        var scroller = menuScroller.closest('.nano');
        scroller.css({
            height: height
        });
        scroller.nanoScroller({
            preventPageScrolling: true
        });
    };
    p.measureScrollbar = function() {
        var scrollDiv = document.createElement('div');
        scrollDiv.className = 'modal-scrollbar-measure';
        $('body').append(scrollDiv);
        var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
        $('body')[0].removeChild(scrollDiv);
        return scrollbarWidth;
    };
    window.materialadmin.AppOffcanvas = new AppOffcanvas;
}(this.materialadmin, jQuery));
(function(namespace, $) {
    "use strict";
    var AppCard = function() {
        var o = this;
        $(document).ready(function() {
            o.initialize();
        });
    };
    var p = AppCard.prototype;
    p.initialize = function() {};
    p.addCardLoader = function(card) {
        var container = $('<div class="card-loader"></div>').appendTo(card);
        container.hide().fadeIn();
        var opts = {
            lines: 17,
            length: 0,
            width: 3,
            radius: 6,
            corners: 1,
            rotate: 13,
            direction: 1,
            color: '#000',
            speed: 2,
            trail: 76,
            shadow: false,
            hwaccel: false,
            className: 'spinner',
            zIndex: 2e9
        };
        var spinner = new Spinner(opts).spin(container.get(0));
        card.data('card-spinner', spinner);
    };
    p.removeCardLoader = function(card) {
        var spinner = card.data('card-spinner');
        var loader = card.find('.card-loader');
        loader.fadeOut(function() {
            spinner.stop();
            loader.remove();
        });
    };
    p.toggleCardCollapse = function(card, duration) {
        duration = typeof duration !== 'undefined' ? duration : 400;
        var dispatched = false;
        card.find('.nano').slideToggle(duration);
        card.find('.card-body').slideToggle(duration, function() {
            if (dispatched === false) {
                $('#COLLAPSER').triggerHandler('card.bb.collapse', [!$(this).is(":visible")]);
                dispatched = true;
            }
        });
        card.toggleClass('card-collapsed');
    };
    p.removeCard = function(card) {
        card.fadeOut(function() {
            card.remove();
        });
    };
    window.materialadmin.AppCard = new AppCard;
}(this.materialadmin, jQuery));
(function(namespace, $) {
    "use strict";
    var AppForm = function() {
        var o = this;
        $(document).ready(function() {
            o.initialize();
        });
    };
    var p = AppForm.prototype;
    p.initialize = function() {
        this._enableEvents();
        this._initRadioAndCheckbox();
        this._initFloatingLabels();
        this._initValidation();
    };
    p._enableEvents = function() {
        var o = this;
        $('[data-submit="form"]').on('click', function(e) {
            e.preventDefault();
            var formId = $(e.currentTarget).attr('href');
            $(formId).submit();
        });
        $('textarea.autosize').on('focus', function() {
            $(this).autosize({
                append: ''
            });
        });
    };
    p._initRadioAndCheckbox = function() {
        $('.checkbox-styled input, .radio-styled input').each(function() {
            if ($(this).next('span').length === 0) {
                $(this).after('<span></span>');
            }
        });
    };
    p._initFloatingLabels = function() {
        var o = this;
        $('.floating-label .form-control').on('keyup change', function(e) {
            var input = $(e.currentTarget);
            if ($.trim(input.val()) !== '') {
                input.addClass('dirty').removeClass('static');
            } else {
                input.removeClass('dirty').removeClass('static');
            }
        });
        $('.floating-label .form-control').each(function() {
            var input = $(this);
            if ($.trim(input.val()) !== '') {
                input.addClass('static').addClass('dirty');
            }
        });
        $('.form-horizontal .form-control').each(function() {
            $(this).after('<div class="form-control-line"></div>');
        });
    };
    p._initValidation = function() {
        if (!$.isFunction($.fn.validate)) {
            return;
        }
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.parent('label').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
        $('.form-validate').each(function() {
            var validator = $(this).validate();
            $(this).data('validator', validator);
        });
    };
    window.materialadmin.AppForm = new AppForm;
}(this.materialadmin, jQuery));
(function(namespace, $) {
    "use strict";
    var AppNavSearch = function() {
        var o = this;
        $(document).ready(function() {
            o.initialize();
        });
    };
    var p = AppNavSearch.prototype;
    p._clearSearchTimer = null;
    p.initialize = function() {
        this._enableEvents();
    };
    p._enableEvents = function() {
        var o = this;
        $('.navbar-search .btn').on('click', function(e) {
            o._handleButtonClick(e);
        });
        $('.navbar-search input').on('blur', function(e) {
            o._handleFieldBlur(e);
        });
    };
    p._handleButtonClick = function(e) {
        e.preventDefault();
        var form = $(e.currentTarget).closest('form');
        var input = form.find('input');
        var keyword = input.val();
        if ($.trim(keyword) === '') {
            form.addClass('expanded');
            input.focus();
        } else {
            form.addClass('expanded');
            form.submit();
            clearTimeout(this._clearSearchTimer);
        }
    };
    p._handleFieldBlur = function(e) {
        var input = $(e.currentTarget);
        var form = input.closest('form');
        form.removeClass('expanded');
        clearTimeout(this._clearSearchTimer);
        this._clearSearchTimer = setTimeout(function() {
            input.val('');
        }, 300);
    };
    window.materialadmin.AppNavSearch = new AppNavSearch;
}(this.materialadmin, jQuery));
(function(namespace, $) {
    "use strict";
    var AppVendor = function() {
        var o = this;
        $(document).ready(function() {
            o.initialize();
        });
    };
    var p = AppVendor.prototype;
    p.initialize = function() {
        this._initScroller();
        this._initTabs();
        this._initTooltips();
        this._initPopover();
        this._initSortables();
    };
    p._initScroller = function() {
        if (!$.isFunction($.fn.nanoScroller)) {
            return;
        }
        $.each($('.scroll'), function(e) {
            var holder = $(this);
            materialadmin.AppVendor.addScroller(holder);
        });
        materialadmin.App.callOnResize(function() {
            $.each($('.scroll-xs'), function(e) {
                var holder = $(this);
                if (!holder.is(":visible")) return;
                if (materialadmin.App.minBreakpoint('xs')) {
                    materialadmin.AppVendor.removeScroller(holder);
                } else {
                    materialadmin.AppVendor.addScroller(holder);
                }
            });
            $.each($('.scroll-sm'), function(e) {
                var holder = $(this);
                if (!holder.is(":visible")) return;
                if (materialadmin.App.minBreakpoint('sm')) {
                    materialadmin.AppVendor.removeScroller(holder);
                } else {
                    materialadmin.AppVendor.addScroller(holder);
                }
            });
            $.each($('.scroll-md'), function(e) {
                var holder = $(this);
                if (!holder.is(":visible")) return;
                if (materialadmin.App.minBreakpoint('md')) {
                    materialadmin.AppVendor.removeScroller(holder);
                } else {
                    materialadmin.AppVendor.addScroller(holder);
                }
            });
            $.each($('.scroll-lg'), function(e) {
                var holder = $(this);
                if (!holder.is(":visible")) return;
                if (materialadmin.App.minBreakpoint('lg')) {
                    materialadmin.AppVendor.removeScroller(holder);
                } else {
                    materialadmin.AppVendor.addScroller(holder);
                }
            });
        });
    };
    p.addScroller = function(holder) {
        holder.wrap('<div class="nano"><div class="nano-content"></div></div>');
        var scroller = holder.closest('.nano');
        scroller.css({
            height: holder.outerHeight()
        });
        scroller.nanoScroller();
        holder.css({
            height: 'auto'
        });
    };
    p.removeScroller = function(holder) {
        if (holder.parent().parent().hasClass('nano') === false) {
            return;
        }
        holder.parent().parent().nanoScroller({
            destroy: true
        });
        holder.parent('.nano-content').replaceWith(holder);
        holder.parent('.nano').replaceWith(holder);
        holder.attr('style', '');
    };
    p._initSortables = function() {
        if (!$.isFunction($.fn.sortable)) {
            return;
        }
        $('[data-sortable="true"]').sortable({
            placeholder: "ui-state-highlight",
            delay: 100,
            start: function(e, ui) {
                ui.placeholder.height(ui.item.outerHeight() - 1);
            }
        });
    };
    p._initTabs = function() {
        if (!$.isFunction($.fn.tab)) {
            return;
        }
        $('[data-toggle="tabs"] a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    };
    p._initTooltips = function() {
        if (!$.isFunction($.fn.tooltip)) {
            return;
        }
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    };
    p._initPopover = function() {
        if (!$.isFunction($.fn.popover)) {
            return;
        }
        $('[data-toggle="popover"]').popover({
            container: 'body'
        });
    };
    window.materialadmin.AppVendor = new AppVendor;
}(this.materialadmin, jQuery));