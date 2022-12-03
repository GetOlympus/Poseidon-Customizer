/*!
 * poseidon-dimensions.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Makes the fields interact when lock is enabled
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
    var Dimensions = function ($el, options) {
        // vars
        const _this = this;

        _this.$el     = $el;
        _this.options = options;

        if (!_this.$el.length) {
            return;
        }

        _this.$lock = _this.$el.find(_this.options.lock);

        if (!_this.$lock.length) {
            return;
        }

        _this.$fields = _this.$el.find(_this.options.fields);
        _this.$icon   = _this.$lock.find('> .dashicons');

        // bind events
        _this.$lock.on('click', $.proxy(_this.lockEvent, _this));
    };

    /**
     * @type {nodeElement}
     */
    Dimensions.prototype.$el     = null;
    Dimensions.prototype.$fields = null;
    Dimensions.prototype.$icon   = null;
    Dimensions.prototype.$lock   = null;

    /**
     * @type {array}
     */
    Dimensions.prototype.options = null;

    /**
     * Input event on number
     * @param {event} e
     */
    Dimensions.prototype.inputEvent = function (e) {
        const _this = this;
        _this.updateValue(e.currentTarget.value);
    };

    /**
     * Click event on lock
     * @param {event} e
     */
    Dimensions.prototype.lockEvent = function (e) {
        e.preventDefault();
        e.stopPropagation();

        const _this = this;

        if (!_this.$lock.hasClass(_this.options.css)) {
            // lock fields
            _this.$lock.addClass(_this.options.css);
            _this.$icon.addClass(_this.options.icon.lock).removeClass(_this.options.icon.unlock);

            _this.updateValue(_this.$fields[0].value);

            // bind input event
            _this.$fields.on('input', $.proxy(_this.inputEvent, _this));
        } else {
            // unlock fields
            _this.$lock.removeClass(_this.options.css);
            _this.$icon.removeClass(_this.options.icon.lock).addClass(_this.options.icon.unlock);

            // unbind input event
            _this.$fields.off('input', _this.inputEvent);
        }
    };

    /**
     * Update properly and completly field value
     * @param {string} value
     */
    Dimensions.prototype.updateValue = function (value) {
        const _this = this;

        $.each(_this.$fields, function (idx, elt) {
            $(elt).val(value).attr('value', value);
        });
    };

    /**
     * Method declaration
     */
    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            let settings = {
                css: 'locked',
                fields: 'input[type="number"]',
                lock: 'button',
                icon: {
                    lock: 'dashicons-lock',
                    unlock: 'dashicons-unlock',
                },
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Dimensions($(this), settings);
            });
        }
    };

    $.fn.poseidonDimensions = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on poseidonDimensions');
            return false;
        }
    };
})(window.jQuery);
