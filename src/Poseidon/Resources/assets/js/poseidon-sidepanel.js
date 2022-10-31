/*!
 * poseidon-sidepanel.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Displays a sidepanel with dynamic contents
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
    var Sidepanel = function ($el, options) {
        // vars
        var _this = this;

        _this.$el     = $el;
        _this.options = options;

        var _attr = _this.$el.attr('data-sidepanel');

        // check `data-sidepanel` attribute
        if ('undefined' === typeof _attr || false === _attr) {
            return;
        }

        // update target
        _this.$target = $('#' + _attr);

        if (!_this.$target.length) {
            return;
        }

        _this.options['body_opened'] = 'poseidon-sidepanel-opened';
        _this.options['container']   = '.wp-full-overlay';
        _this.options['fog_class']   = 'pos-p-sidepanel-fog';
        _this.options['panel_class'] = 'pos-p-sidepanel';

        _this.$body      = $('body');
        _this.$container = _this.$body.find(_this.options.container);

        if (!_this.$container.length) {
            return;
        }

        _this.$parent = _this.$el.closest(_this.options.parent);

        if (!_this.$parent.length) {
            return;
        }

        // bind click event
        _this.$el.on('click', $.proxy(_this.clickEvent, _this));
    };

    /**
     * @type {nodeElement}
     */
    Sidepanel.prototype.$el        = null;
    Sidepanel.prototype.$body      = null;
    Sidepanel.prototype.$container = null;
    Sidepanel.prototype.$fog       = null;
    Sidepanel.prototype.$panel     = null;
    Sidepanel.prototype.$parent    = null;
    Sidepanel.prototype.$target    = null;

    /**
     * @type {array}
     */
    Sidepanel.prototype.options = null;

    /**
     * Click event on opening sidepanel
     * @param {event} e
     */
    Sidepanel.prototype.clickEvent = function (e) {
        e.preventDefault();
        var _this = this;

        if (!_this.$el.hasClass(_this.options.opened)) {
            // open sequence
            _this.createSidepanel();
            _this.$el.addClass(_this.options.opened);
        } else {
            // close sequence
            _this.$el.removeClass(_this.options.opened);
            _this.deleteSidepanel();
        }
    };

    /**
     * Create sidepanel on container Dom
     */
    Sidepanel.prototype.createSidepanel = function () {
        var _this = this;

        // build panel
        _this.$panel = $(document.createElement('div'))
        _this.$panel.addClass(_this.options.panel_class);

        // detach contents
        _this.detach(_this.$panel);

        // build fog
        _this.$fog = $(document.createElement('div'))
        _this.$fog.addClass(_this.options.fog_class);

        // append them into container
        _this.$container.append(_this.$panel);
        _this.$container.append(_this.$fog);

        // add open class
        _this.$body.addClass(_this.options.body_opened);

        _this.$fog.on('click', function (e) {
            e.preventDefault();

            _this.$el.removeClass(_this.options.opened);
            _this.deleteSidepanel();
        });
    };

    /**
     * Delete fog from parent Dom
     */
    Sidepanel.prototype.deleteSidepanel = function () {
        var _this = this;

        // remove open class
        _this.$body.removeClass(_this.options.body_opened);

        setTimeout(function () {
            // detach contents
            _this.detach(_this.$parent);

            // remove fog
            _this.$fog.remove();
            _this.$fog = null;

            // remove panel
            _this.$panel.remove();
            _this.$panel = null;
        }, _this.options.delay);
    };

    /**
     * Move contents from source to target
     * @param {nodeElement} $where
     */
    Sidepanel.prototype.detach = function ($where) {
        var _this = this;
        $where.append(_this.$target);
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                delay: 200,
                opened: 'opened',
                parent: '#parent'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Sidepanel($(this), settings);
            });
        }
    };

    $.fn.poseidonSidepanel = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on poseidonSidepanel');
            return false;
        }
    };
})(window.jQuery);
