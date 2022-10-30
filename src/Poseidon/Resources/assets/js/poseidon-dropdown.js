/*!
 * dropdown-control.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Displays a dropdown
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    /**
     * Constructor
     * @param {nodeElement} $el
     * @param {array}       options
     */
    var Dropdown = function ($el, options) {
        // vars
        var _this = this;

        _this.$el     = $el;
        _this.options = options;

        // update target
        _this.$target = $('#' + _this.$el.attr('data-dropdown'));

        if (!_this.$target.length) {
            return;
        }

        // bind click event
        _this.$el.on('click', $.proxy(_this.clickEvent, _this));
    };

    /**
     * @type {nodeElement}
     */
    Dropdown.prototype.$el     = null;
    Dropdown.prototype.$fog    = null;
    Dropdown.prototype.$target = null;

    /**
     * @type {array}
     */
    Dropdown.prototype.options = null;

    /**
     * Click event on opening dropdown
     * @param {event} e
     */
    Dropdown.prototype.clickEvent = function (e) {
        e.preventDefault();
        var _this = this;

        if (!_this.$target.hasClass(_this.options.opened)) {
            // open sequence
            _this.createFog();
            _this.$el.addClass(_this.options.opened);
            _this.$target.addClass(_this.options.opened);
            _this.scrollTo();
        } else {
            // close sequence
            _this.$target.removeClass(_this.options.opened);
            _this.$el.removeClass(_this.options.opened);
            _this.deleteFog();
        }
    };

    /**
     * Create fog on parent Dom
     */
    Dropdown.prototype.createFog = function () {
        var _this      = this,
            $container = _this.$el.parent();

        if (!_this.options.fog) {
            return;
        }

        _this.$fog = $(document.createElement('div'))

        _this.$fog.addClass('pos-c-dropdown-fog');
        $container.append(_this.$fog);
        _this.$fog.addClass(_this.options.opened);

        _this.$fog.on('click', function (e) {
            e.preventDefault();

            _this.$target.removeClass(_this.options.opened);
            _this.$el.removeClass(_this.options.opened);
            _this.deleteFog();
        });
    };

    /**
     * Delete fog from parent Dom
     */
    Dropdown.prototype.deleteFog = function () {
        var _this = this;

        if (!_this.options.fog) {
            return;
        }

        _this.$fog.removeClass(_this.options.opened);

        setTimeout(function () {
            _this.$fog.remove();
            _this.$fog = null;
        }, _this.options.delay);
    };

    /**
     * Scroll to dropdown
     */
    Dropdown.prototype.scrollTo = function () {
        var _this      = this,
            $container = _this.$el.closest(_this.options.container),
            $scrollto  = _this.$el.parent();

        if (!$container.length) {
            return;
        }

        // does not work! To check
        setTimeout(function () {
            $container.animate({
                scrollTop: -1 * ($scrollto.offset().top - $container.offset().top + $container.scrollTop())
            });
        }, _this.options.delay);
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                container: 'ul.open',
                delay: 200,
                fog: true,
                opened: 'opened'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Dropdown($(this), settings);
            });
        }
    };

    $.fn.poseidonDropdown = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on poseidonDropdown');
            return false;
        }
    };
})(window.jQuery);
