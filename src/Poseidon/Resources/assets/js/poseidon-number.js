/*!
 * poseidon-number.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Displays a number input with its -/+ buttons
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
    var Number = function ($el, options) {
        // vars
        const _this = this;

        _this.$el     = $el;
        _this.options = options;

        _this.$input = _this.$el.find(_this.options.input);
        _this.$minus = _this.$el.find(_this.options.minus);
        _this.$plus  = _this.$el.find(_this.options.plus);

        if (!_this.$input.length || !_this.$minus.length || !_this.$plus.length) {
            return;
        }

        const _min = _this.$input.prop('min'),
            _max   = _this.$input.prop('max'),
            _value = _this.$input.prop('value');

        if ('' === _value || _min === _value) {
            _this.$minus.addClass(_this.options.disable);
        } else if (_max === _value) {
            _this.$plus.addClass(_this.options.disable);
        }

        // bind events
        _this.$input.on('input', $.proxy(_this.inputEvent, _this));
        _this.$minus.on('click', $.proxy(_this.valueDown, _this));
        _this.$plus.on('click', $.proxy(_this.valueUp, _this));
    };

    /**
     * @type {nodeElement}
     */
    Number.prototype.$el    = null;
    Number.prototype.$input = null;
    Number.prototype.$minus = null;
    Number.prototype.$plus  = null;

    /**
     * @type {array}
     */
    Number.prototype.options = null;

    /**
     * Input event on number
     * @param {event} e
     */
    Number.prototype.inputEvent = function (e) {
        e.preventDefault();
        e.stopPropagation();

        const _this = this,
            _min    = _this.$input.prop('min'),
            _max    = _this.$input.prop('max'),
            _value  = _this.$input.prop('value');

        _this.$input.attr('value', _value);

        _this.$minus.removeClass(_this.options.disable);
        _this.$plus.removeClass(_this.options.disable);

        if (_min === _value) {
            _this.$minus.addClass(_this.options.disable);
        } else if (_max === _value) {
            _this.$plus.addClass(_this.options.disable);
        }
    };

    /**
     * Click event on minus button
     * @param {event} e
     */
    Number.prototype.valueDown = function (e) {
        e.preventDefault();
        e.stopPropagation();

        const _this = this;
        _this.$input[0].stepDown();
        _this.$input.trigger('input');
    };

    /**
     * Click event on plus button
     * @param {event} e
     */
    Number.prototype.valueUp = function (e) {
        e.preventDefault();
        e.stopPropagation();

        const _this = this;
        _this.$input[0].stepUp();
        _this.$input.trigger('input');
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
                disable: 'disable',
                input: 'input[type="number"]',
                minus: 'button.minus',
                plus: 'button.plus',
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Number($(this), settings);
            });
        }
    };

    $.fn.poseidonNumber = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on poseidonNumber');
            return false;
        }
    };
})(window.jQuery);
