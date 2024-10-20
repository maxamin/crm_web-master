(function (namespace, $){
    'use strict';

    var Helpers = function () {

        var o = this;

        this.clicky = null;

        $(document).ready(function () {
            o.initialize();
        });
    };

    var p = Helpers.prototype;

    p.initialize = function () {

        this._initJQueryFunctions();
        this._initJSFunctions();
        this._initEvents();

        console.log('Helpers initialized');
    };

    p._initEvents = function () {

        var o = this;

        $(document).mousedown(function(e) {
            // The latest element clicked
            o.clicky = e.target;
        });

        // when 'clicky == null' on blur, we know it was not caused by a click
        // but maybe by pressing the tab key
        $(document).mouseup(function(e) {
            o.clicky = null;
        });

    };

    p.debounce = (function (){
        var timeout = 0;
        return function (func, wait, immediate) {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    })();

    p._initJQueryFunctions = function () {

        $.fn.selectRange = function(start, end) {
            if(end === undefined) {
                end = start;
            }
            return this.each(function() {
                if('selectionStart' in this) {
                    this.selectionStart = start;
                    this.selectionEnd = end;
                } else if(this.setSelectionRange) {
                    this.setSelectionRange(start, end);
                } else if(this.createTextRange) {
                    var range = this.createTextRange();
                    range.collapse(true);
                    range.moveEnd('character', end);
                    range.moveStart('character', start);
                    range.select();
                }
            });
        };
    };

    p._initJSFunctions = function () {

        // Шаги алгоритма ECMA-262, 5-е издание, 15.4.4.14
        // Ссылка (en): http://es5.github.io/#x15.4.4.14
        // Ссылка (ru): http://es5.javascript.ru/x15.4.html#x15.4.4.14
        if (!Array.prototype.indexOf) {
            Array.prototype.indexOf = function(searchElement, fromIndex) {
                var k;

                // 1. Положим O равным результату вызова ToObject с передачей ему
                //    значения this в качестве аргумента.
                if (this == null) {
                    throw new TypeError('"this" is null or not defined');
                }

                var O = Object(this);

                // 2. Положим lenValue равным результату вызова внутреннего метода Get
                //    объекта O с аргументом "length".
                // 3. Положим len равным ToUint32(lenValue).
                var len = O.length >>> 0;

                // 4. Если len равен 0, вернём -1.
                if (len === 0) {
                    return -1;
                }

                // 5. Если был передан аргумент fromIndex, положим n равным
                //    ToInteger(fromIndex); иначе положим n равным 0.
                var n = +fromIndex || 0;

                if (Math.abs(n) === Infinity) {
                    n = 0;
                }

                // 6. Если n >= len, вернём -1.
                if (n >= len) {
                    return -1;
                }

                // 7. Если n >= 0, положим k равным n.
                // 8. Иначе, n<0, положим k равным len - abs(n).
                //    Если k меньше нуля 0, положим k равным 0.
                k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

                // 9. Пока k < len, будем повторять
                while (k < len) {
                    // a. Положим Pk равным ToString(k).
                    //   Это неявное преобразование для левостороннего операнда в операторе in
                    // b. Положим kPresent равным результату вызова внутреннего метода
                    //    HasProperty объекта O с аргументом Pk.
                    //   Этот шаг может быть объединён с шагом c
                    // c. Если kPresent равен true, выполним
                    //    i.  Положим elementK равным результату вызова внутреннего метода Get
                    //        объекта O с аргументом ToString(k).
                    //   ii.  Положим same равным результату применения
                    //        Алгоритма строгого сравнения на равенство между
                    //        searchElement и elementK.
                    //  iii.  Если same равен true, вернём k.
                    if (k in O && O[k] === searchElement) {
                        return k;
                    }
                    k++;
                }
                return -1;
            };
        }

        // js capitalize
        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        };
    };

    namespace.Helpers = new Helpers;

}(window, jQuery));
