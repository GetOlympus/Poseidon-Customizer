/*!
 * poseidon-slider.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Displays a slider with its input text and selectbox components
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
    var Slider = function ($el, options) {
        // vars
        const _this = this;

        _this.$el     = $el;
        _this.options = options;

        if (!_this.$el.length) {
            return;
        }

        _this.$number = _this.$el.find(_this.options.number);
        _this.$range  = _this.$el.find(_this.options.range);
        _this.$select = _this.$el.find(_this.options.select);

        if (!_this.$number.length || !_this.$range.length || !_this.$select.length) {
            return;
        }

        // bind events
        _this.$number.on('input', $.proxy(_this.numberEvent, _this));
        _this.$range.on('input', $.proxy(_this.rangeEvent, _this));
        _this.$select.on('change', $.proxy(_this.selectEvent, _this));
    };

    /**
     * @type {nodeElement}
     */
    Slider.prototype.$el     = null;
    Slider.prototype.$number = null;
    Slider.prototype.$range  = null;
    Slider.prototype.$select = null;

    /**
     * @type {array}
     */
    Slider.prototype.options = null;

    /**
     * Input event on number
     */
    Slider.prototype.numberEvent = function () {
        const _this = this,
            _value  = _this.$number.val();

        _this.$number.attr('value', _value);
        _this.updateValue('range', _value);
    };

    /**
     * Input event on range
     */
    Slider.prototype.rangeEvent = function () {
        const _this = this,
            _value  = _this.$range.val();

        _this.$range.attr('value', _value);
        _this.updateValue('number', _value);
    };

    /**
     * Change event on select
     */
    Slider.prototype.selectEvent = function () {
        const _this = this,
            $option = _this.$select.find('option:selected');

        const _min = Number.parseInt($option.attr('data-min')),
            _max   = Number.parseInt($option.attr('data-max')),
            _step  = Number.parseInt($option.attr('data-step')),
            _value = Number.parseInt(_this.$number.val());

        _this.$number.prop({
            min: _min,
            max: _max,
            step: _step
        });

        _this.$range.prop({
            min: _min,
            max: _max,
            step: _step
        });

        if (_value < _min) {
            _this.updateValue('number', _min);
            _this.updateValue('range', _min);
        } else if (_value > _max) {
            _this.updateValue('number', _max);
            _this.updateValue('range', _max);
        }
    };

    /**
     * Update properly and completly field value
     * @param {string} field
     * @param {string} value
     */
    Slider.prototype.updateValue = function (field, value) {
        const _this = this,
            $fields = {
                number: _this.$number,
                range: _this.$range,
            };

        $fields[field].val(value).attr('value', value);
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
                number: 'input[type="number"]',
                range: 'input[type="range"]',
                select: 'select',
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Slider($(this), settings);
            });
        }
    };

    $.fn.poseidonSlider = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on poseidonSlider');
            return false;
        }
    };
})(window.jQuery);
