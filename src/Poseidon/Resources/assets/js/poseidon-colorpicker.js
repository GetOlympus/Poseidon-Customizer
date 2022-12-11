/*!
 * poseidon-colorpicker.js v0.0.2
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Displays a Color Picker depending on its options
 *
 * @roadmap
 * • fix     - color picker's position on inline mode when the container is not the `body`
 * • fix     - check on Firefox, Safari and Edge
 * • feature - orientation option works on `horizontal` and `vertical`
 * • feature - placement option works on `auto`, `top` and `bottom`
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
    var ColorPicker = function ($el, options) {
        var _this = this;

        _this.options = options;
        _this.$el     = $el;

        // set identifier
        _this.id = _this.getUuid();

        // set private options
        _this.options['init']    = 'pos-cp--init';
        _this.options['opened']  = 'pos-cp--opened';
        _this.options['hidden']  = 'pos-cp--none';
        _this.options['element'] = 'pos-cp--element';
        _this.options['sizes']   = {};

        // initialize color picker
        _this._initialize();
    };

    // parameters ---------------------------------------------------------

    /**
     * @type {nodeElement}
     */
    ColorPicker.prototype.$colorpicker = null;
    ColorPicker.prototype.$container = null;
    ColorPicker.prototype.$el = null;

    /**
     * Color Picker's components used to performance issue
     * @type {nodeElement}
     */
    ColorPicker.prototype.$output = null;
    ColorPicker.prototype.$saturation = null;
    ColorPicker.prototype.$hue = null;
    ColorPicker.prototype.$alpha = null;
    ColorPicker.prototype.$pointers = null;
    ColorPicker.prototype.$inputs = null;
    ColorPicker.prototype.$preview = null;

    /**
     * Main areas containing dropzones dimensions
     * @type {object}
     */
    ColorPicker.prototype.areas = {};

    /**
     * Main ID used for the Color Picker to let only one instance at the time
     * @type {string}
     */
    ColorPicker.prototype.id = '';

    /**
     * Color set at the initialization of the Color Picker
     * @type {string}
     */
    ColorPicker.prototype.maincolor = '';

    /**
     * List of all user's options
     * @type {array}
     */
    ColorPicker.prototype.options = null;

    /**
     * Define weather the user is picking or not
     * @type {boolean}
     */
    ColorPicker.prototype.picking = false;

    // initializers -------------------------------------------------------

    /**
     * Initialize the color picker
     */
    ColorPicker.prototype._initialize = function () {
        var _this = this;

        // initialize container
        _this.$container = $(_this.options.container);

        if (!_this.$container.length) {
            _this.options.container = 'body';
            _this.$container        = $(_this.options.container);
        } else if (1 < _this.$container.length) {
            _this.$container = _this.$container.eq(0);
        }

        // set main color init
        _this.maincolor = _this.options.defaultColor;

        // initialize Color Picker
        _this._initColorPicker();

        // initialize components
        _this._initComponents();

        // initialize default color
        _this._initDefaultColor();

        // initialize swatches
        _this._initSwatches();

        // initialize events
        _this._initEvents();

        document.poseidonColorPicker = {
            id: _this.id,
            this: _this,
        };
    };

    /**
     * Initialize Color Picker
     */
    ColorPicker.prototype._initColorPicker = function () {
        var _this = this;

        // set template's configurations
        const tpl = {
            id: _this.id,

            // main options
            //placement: _this.options.placement,
            options: {
                alpha: 'pos-cp--alpha-' + (_this.options.alpha ? 'enabled' : 'disabled'),
                hue: 'pos-cp--hue-' + (_this.options.hue ? 'enabled' : 'disabled'),
                //orientation: 'pos-cp--sliders-' + _this.options.orientation,
                preview: 'pos-cp--preview-' + (_this.options.preview ? 'enabled' : 'disabled'),
                saturation: 'pos-cp--saturation-' + (_this.options.saturation ? 'enabled' : 'disabled'),
                toggle: 'pos-cp--toggle-' + (_this.options.toggleButtons ? 'enabled' : 'disabled'),
                inline: 'pos-cp--inline-' + (_this.options.inline ? 'enabled' : 'disabled'),
            },

            // color and pickers
            color: _this.maincolor,
            pointers: {
                alpha: 'left:0',
                hue: 'left:0',
                saturation: 'left:0; top:0',
            },

            // icon and labels
            icon: '<i class="dashicons dashicons-leftright"></i>',
            inputs: _this.options.inputs ? '' : 'readonly="readonly" disabled',
            labels: {
                hex: 'hex',
                rgb: 'rgb',
                hsl: 'hsl',
                alpha: 'a',
            },
        };

        // initialize the Color Picker
        _this.$colorpicker = $(document.createElement('component'))
            .attr({
                id: tpl.id,
                class: 'poseidon-color-picker ' + Object.values(tpl.options).join(' '),
                //placement: tpl.placement,
                style: 'color:' + tpl.color,
            });

        // build the main template
        const template = ``
        + `<input aria-label="output" type="hidden" readonly="readonly" disabled />`

        + `<main role="saturationPanel" class="pos-cp-saturation">`
        + ` <span role="saturationPicker" class="pos-cp-pointer" style="${tpl.pointers.saturation}" x="0" y="0"></span>`
        + `</main>`

        + `<nav class="pos-cp-swatches"></nav>`

        + `<section class="pos-cp-sliders">`
        + ` <div role="huePanel" class="pos-cp-hue">`
        + `  <span role="huePicker" class="pos-cp-pointer" style="${tpl.pointers.hue}" x="0" y="0"></span>`
        + ` </div>`
        + ` <div role="alphaPanel" class="pos-cp-alpha">`
        + `  <span role="alphaPicker" class="pos-cp-pointer" style="${tpl.pointers.alpha}" x="0" y="0"></span>`
        + ` </div>`
        + `</section>`

        + `<section class="pos-cp-fields">`
        + ` <div class="pos-cp-inputs pos-cp--hex">`
        + `  <input aria-label="hex" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <span class="pos-cp-input--label">${tpl.labels.hex} ${tpl.icon}</span>`
        + ` </div>`
        + ` <div class="pos-cp-inputs pos-cp--rgb pos-cp--none">`
        + `  <input aria-label="r" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <input aria-label="g" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <input aria-label="b" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <input aria-label="a" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <span class="pos-cp-input--label">${tpl.labels.rgb}<b>${tpl.labels.alpha}</b> ${tpl.icon}</span>`
        + ` </div>`
        + ` <div class="pos-cp-inputs pos-cp--hsl pos-cp--none">`
        + `  <input aria-label="h" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <input aria-label="s" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <input aria-label="l" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <input aria-label="a" class="pos-cp-input--value" ${tpl.inputs} />`
        + `  <span class="pos-cp-input--label">${tpl.labels.hsl}<b>${tpl.labels.alpha}</b> ${tpl.icon}</span>`
        + ` </div>`
        + ` <aside class="pos-cp-preview" style="color:${tpl.color};"></aside>`
        + `</section>`;

        // append template into the Color Picker
        _this.$colorpicker.append(template);
    };

    /**
     * Initialize components
     */
    ColorPicker.prototype._initComponents = function () {
        var _this = this;

        // initialize components
        _this.$output     = _this.$colorpicker.find('> input[type="hidden"]');
        _this.$saturation = _this.$colorpicker.find('main.pos-cp-saturation');
        _this.$hue        = _this.$colorpicker.find('div.pos-cp-hue');
        _this.$alpha      = _this.$colorpicker.find('div.pos-cp-alpha');
        _this.$pointers   = _this.$colorpicker.find('span.pos-cp-pointer');
        _this.$inputs     = _this.$colorpicker.find('input.pos-cp-input--value');
        _this.$labels     = _this.$colorpicker.find('span.pos-cp-input--label');
        _this.$preview    = _this.$colorpicker.find('aside.pos-cp-preview');

        // update class element
        _this.$el.addClass(_this.options.element);
    };

    /**
     * Initialize default color
     */
    ColorPicker.prototype._initDefaultColor = function () {
        var _this = this;
        _this.maincolor = _this.getColor();
    };

    /**
     * Initialize events
     */
    ColorPicker.prototype._initEvents = function () {
        var _this = this;

        // bind custom event on element
        _this.$colorpicker.on('destroy', $.proxy(_this.eventDestroyColorPicker, _this));

        // bind click event on element
        _this.$el.on('click', $.proxy(_this.eventClickElement, _this));

        // bind click event on element
        _this.$output.on('input', $.proxy(_this.eventChangeOutput, _this));

        // bind input event on inputs
        _this.$inputs.on('input', $.proxy(_this.eventChangeInput, _this));

        // bind click event on labels
        _this.$labels.on('click', $.proxy(_this.eventClickLabel, _this));
    };

    /**
     * Initialize swatches
     */
    ColorPicker.prototype._initSwatches = function () {
        var _this = this;

        const slen = _this.options.swatches.length;

        if (!slen) {
            return;
        }

        const template = `<button class="pos-cp-swatch" style="color:__COLOR__;"></button>`,
            $swatches  = _this.$colorpicker.find('.pos-cp-swatches');

        let _swatches = '';

        for (let s = 0; s < slen; s++) {
            _swatches += template.replace('__COLOR__', _this.options.swatches[s]);
        }

        if ('' === _swatches) {
            return;
        }

        $swatches.append(_swatches);

        // bind click event on each swatch
        $swatches.find('button.pos-cp-swatch').on('click', $.proxy(_this.eventClickSwatch, _this));
    };

    // getters and setters ------------------------------------------------

    /**
     * Get color
     * @return {string}
     */
    ColorPicker.prototype.getColor = function () {
        var _this   = this;
        const value = _this.$el.attr('value');

        if ('undefined' !== typeof value && false !== value) {
            return value;
        }

        const input = _this.$el.find('input');

        if (input.length) {
            return input.attr('value');
        }

        return _this.maincolor;
    };

    /**
     * Get element
     * @param {string} element
     * @param {string} name
     * @return {nodeElement}
     */
    ColorPicker.prototype.getElement = function (element, name = 'hex') {
        var _this = this;
        let index = 0;

        // pointers: 0 = saturation, 1 = hue, 2 = alpha
        if ('pointer' === element) {
            index = 'hue' === name ? 1 : ('alpha' === name ? 2 : 0);
            return _this.$pointers[index];
        }

        // inputs: 0 = hex, 1 = r, 2 = g, 3 = b, 4 = a, 5 = h, 6 = s, 7 = l, 8 = z
        // `z` is a special case for the 2nd alpha input field
        names = 'rgbahslz';
        index = 'hex' !== name ? names.indexOf(name) + 1 : 0;

        return _this.$inputs[index];
    };

    /**
     * Get the pointer positions
     * @param {event} e
     * @return {object}
     */
    ColorPicker.prototype.getPointerPosition = function (e) {
        return {
            x: e.changedTouches ? e.changedTouches[0].pageX : e.pageX,
            y: e.changedTouches ? e.changedTouches[0].pageY : e.pageY
        };
    };

    /**
     * Get the pointer role
     * @param {event} e
     * @return {string}
     */
    ColorPicker.prototype.getRole = function (e) {
        var _role = $(e.currentTarget).attr('role');
        return 'saturationPanel' === _role ? 'saturation' : _role.replace(/(Picker|Panel)/g, '');
    };

    /**
     * UUID generator
     * @return {string}
     */
    ColorPicker.prototype.getUuid = function () {
        return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, function (c) {
            return (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16);
        });
    };

    /**
     * Set the color from the pointers' positions
     */
    ColorPicker.prototype.setColorFromPosition = function () {
        var _this = this;

        const hsva = {
            h: _this.getElement('pointer', 'hue').getAttribute('x') * 360 / _this.areas.width,
            s: _this.getElement('pointer', 'saturation').getAttribute('x') * 100 / _this.areas.width,
            v: 100 - (_this.getElement('pointer', 'saturation').getAttribute('y') * 100 / _this.areas.height),
            a: _this.getElement('pointer', 'alpha').getAttribute('x') * 100 / _this.areas.width / 100,
        };

        // get color into RGBA format
        const rgba = _this.hsv2rgb(hsva);

        _this.maincolor = `rgba(${rgba.r}, ${rgba.g}, ${rgba.b}, ${rgba.a})`;

        // update Color Picker's colors
        _this.updateColor(rgba, hsva);
    };

    /**
     * Set the pointers' positions from the main color
     */
    ColorPicker.prototype.setPositionFromColor = function () {
        var _this = this;

        // get rgba and hsva from color string
        const rgba = _this.str2rgb(_this.maincolor, _this.options);
        const hsva = _this.rgb2hsv(rgba);

        // get hue pointer position in pixels
        const pointers = {
            saturation: {
                x: _this.areas.width * hsva.s / 100,
                y: _this.areas.height - (_this.areas.height * hsva.v / 100),
            },
            hue: {
                x: hsva.h * _this.areas.width / 360,
            },
            alpha: {
                x: hsva.a * _this.areas.width,
            },
        };

        // update pointers
        _this.getElement('pointer', 'saturation').style.left = `${pointers.saturation.x}px`;
        _this.getElement('pointer', 'saturation').style.top  = `${pointers.saturation.y}px`;
        _this.getElement('pointer', 'hue').style.left   = `${pointers.hue.x}px`;
        _this.getElement('pointer', 'alpha').style.left = `${pointers.alpha.x}px`;

        // update attributes
        _this.getElement('pointer', 'saturation').setAttribute('x', pointers.saturation.x);
        _this.getElement('pointer', 'saturation').setAttribute('y', pointers.saturation.y);
        _this.getElement('pointer', 'hue').setAttribute('x', pointers.hue.x);
        _this.getElement('pointer', 'alpha').setAttribute('x', pointers.alpha.x);

        // update colors and inputs
        _this.updateColor(rgba, hsva);
    };

    /**
     * Update the Color Picker's input field and preview thumb
     * @param {Object} rgba
     * @param {Object} hsva
     */
    ColorPicker.prototype.updateColor = function (rgba = {}, hsva = {}) {
        var _this = this;
 
        const hex = _this.rgb2hex(rgba, _this.options); // chosen color + alpha
        const rgb = hex.substring(0, 7);                // chosen color
        const hue = `hsl(${hsva.h}, 100%, 50%)`;        // main color

        // update components with main color
        _this.$colorpicker.css('color', hue);
        _this.getElement('pointer', 'hue').style.color = hue;

        // update components with chosen color
        _this.getElement('pointer').style.color = rgb;
        _this.$alpha.css('color', rgb);

        // update components with chosen color + alpha
        _this.getElement('pointer', 'alpha').style.color = hex;
        _this.$preview.css('color', hex);

        const hsla = _this.hsv2hsl(hsva);

        // update inputs
        _this.getElement('input', 'hex').value = hex;
        _this.getElement('input', 'r').value = Math.round(rgba.r);
        _this.getElement('input', 'g').value = Math.round(rgba.g);
        _this.getElement('input', 'b').value = Math.round(rgba.b);
        _this.getElement('input', 'a').value = Math.round((rgba.a + Number.EPSILON) * 100) / 100;
        _this.getElement('input', 'h').value = Math.round(hsla.h);
        _this.getElement('input', 's').value = Math.round(hsla.s);
        _this.getElement('input', 'l').value = Math.round(hsla.l);
        _this.getElement('input', 'z').value = Math.round((hsla.a + Number.EPSILON) * 100) / 100;

        // update output
        _this.updateOutput();
    };

    /**
     * Update the Color Picker's positions from the main color
     * @todo: fix position
     */
    ColorPicker.prototype.updateColorPickerPosition = function () {
        var _this = this;

        // color picker's position and styles
        const coords = _this.$colorpicker[0].getBoundingClientRect();
        const styles = window.getComputedStyle(_this.$colorpicker[0]);

        // check inline mode
        if (!_this.options.inline) {
            // useful vars
            const el      = {
                height: _this.$el[0].offsetHeight,
                width:  _this.$el[0].offsetWidth,
            };

            // custom offset and scroll
            let offset  = {top: 0, x: 0, y: 0};
            let scrolly = window.scrollY;

            // build css positions
            let doc  = {height: 0, styles: {}, top: 0, width: 0};
            let left = coords.x;
            let top  = coords.y;

            // define position for a specific container
            if ('body' !== _this.options.container) {
                doc = {
                    height: _this.$container[0].clientHeight,
                    styles: window.getComputedStyle(_this.$container[0]),
                    top: _this.$container[0].scrollTop,
                    width: _this.$container[0].clientWidth,
                };
                doc.height -= parseFloat(doc.styles.marginTop);

                // define offset
                offset    = _this.$container[0].getBoundingClientRect();
                offset.y += parseFloat(doc.styles.borderTopWidth) + scrolly;

                // update positions
                left -= offset.x;
                top  -= offset.y;

                // offset top
                offset.top = top - coords.height + el.height;
            } else {
                doc = {
                    height: document.documentElement.clientHeight,
                    width: document.documentElement.clientWidth,
                };

                scrolly = 0;
                offset.top = coords.y - el.height;
            }

            // build left
            left += (left + el.width > doc.width) ? coords.width - el.width : 0;

            // build top
            if (top + el.height - scrolly > doc.height) {
                top = el.height <= (coords.top - offset.y - scrolly) ? offset.top : 0;
            }

            top += doc.top;

            // update color picker's position
            _this.$colorpicker.css({
                left: `${left}px`,
                top: `${top}px`,
            });
        }

        // define main area's dimensions
        let area = _this.$saturation[0];

        if (!_this.options.saturation) {
            area = !_this.options.hue ? _this.$alpha[0] : _this.$hue[0];
        }

        // update sizes
        _this.areas = {
            height: area.offsetHeight,
            width: area.offsetWidth,
            x: coords.x + parseFloat(styles.paddingLeft),
            y: coords.y + parseFloat(styles.paddingTop),
        };
    };

    /**
     * Update the output input field
     */
    ColorPicker.prototype.updateOutput = function () {
        var _this = this;

        // * hex: displays `#RRGGBB` or `#RRGGBBAA`
        let hex = _this.getElement('input', 'hex').value;

        // * rgb: displays `rgb(R, G, B)` or `rgba(R, G, B, A)`
        let rgb = 'rgb';
        rgb += _this.options.alpha || _this.options.alwaysAlpha ? 'a' : '';
        rgb += '(' + _this.getElement('input', 'r').value;
        rgb += ',' + _this.getElement('input', 'g').value;
        rgb += ',' + _this.getElement('input', 'b').value;
        rgb += _this.options.alpha || _this.options.alwaysAlpha ? ',' + _this.getElement('input', 'a').value : '';
        rgb += ')';

        // * hsl: displays `hsl(H, S, L)` or `hsla(H, S, L, A)`
        let hsl = 'hsl';
        hsl += _this.options.alpha || _this.options.alwaysAlpha ? 'a' : '';
        hsl += '(' + _this.getElement('input', 'h').value;
        hsl += ',' + _this.getElement('input', 's').value;
        hsl += ',' + _this.getElement('input', 'l').value;
        hsl += _this.options.alpha || _this.options.alwaysAlpha ? ',' + _this.getElement('input', 'z').value : '';
        hsl += ')';

        // fix maincolor display
        _this.maincolor = '' === _this.maincolor ? hex : _this.maincolor;

        // build value
        let value = '';

        if ('mixed' === _this.options.output) {
            value = _this.options.alpha ? rgb : hex;
            value = 'var(' === _this.maincolor.substring(0, 4).toLowerCase() ? _this.maincolor : value;
        } else if ('rgb' === _this.options.output) {
            value = rgb;
        } else if ('hsl' === _this.options.output) {
            value = hsl;
        } else {
            value = hex;
        }

        // update output with built value
        _this.$output.attr('value', value);
        _this.$output.trigger('input');
    };

    // events -------------------------------------------------------------

    /**
     * Event input on inputs
     * @param {event} e
     */
    ColorPicker.prototype.eventChangeInput = function (e) {
        e.preventDefault();

        var _this = this,
            color = e.currentTarget.value;

        _this.maincolor = color;

        // define pointers' positions from the main color
        _this.setPositionFromColor();
    };

    /**
     * Event change on output
     * @param {event} e
     */
    ColorPicker.prototype.eventChangeOutput = function (e) {
        e.preventDefault();

        var _this = this,
            color = e.currentTarget.value;

        // update element color css
        _this.$el.css('color', color);

        // update input value if exists
        const input = _this.$el.find('input');

        if (input.length) {
            input.attr('value', color);
        }

        // execute onChange function if set
        if ('function' === typeof _this.options.onChange) {
            _this.options.onChange.call(this, color, _this);
        }
    };

    /**
     * Event click on main element to open or close the Color Picker
     * @param {event} e
     */
    ColorPicker.prototype.eventClickElement = function (e) {
        e.preventDefault();
        e.stopPropagation();

        var _this = this;

        // check if the Color Picker is already opened
        if (_this.$colorpicker.hasClass(_this.options.opened)) {
            _this.$colorpicker.trigger('destroy');
            return;
        }

        let $oldColorPicker = $('.poseidon-color-picker').not('[id="' + _this.id + '"]');

        // check the other instances
        if ($oldColorPicker.length) {
            $.each($oldColorPicker, function (idx, elt) {
                $(elt).trigger('destroy');
            });
        }

        _this.maincolor = _this.getColor();

        // append Color Picker to container
        if (!_this.$colorpicker.hasClass(_this.options.init)) {
            _this.$container.append(_this.$colorpicker);
            _this.$colorpicker.addClass(_this.options.init);
        }

        // attach events
        _this._attach();

        // open the Color Picker
        _this.$colorpicker.addClass(_this.options.opened);

        // update sizes
        _this.updateColorPickerPosition();

        // define pointers' positions from the main color
        _this.setPositionFromColor();
    };

    /**
     * Event click on label element
     * @param {event} e
     */
    ColorPicker.prototype.eventClickLabel = function (e) {
        e.preventDefault();
        e.stopPropagation();

        var _this   = this,
            $inputs = _this.$colorpicker.find('div.pos-cp-inputs');

        var _class = e.currentTarget.parentNode.className;
        _class = _class.replace(/pos-cp-inputs|pos-cp--|\s/gi, '');

        $inputs.addClass('pos-cp--none');

        if ('hex' === _class) {
            $inputs.eq(1).removeClass(_this.options.hidden);
        } else if ('rgb' === _class) {
            $inputs.eq(2).removeClass(_this.options.hidden);
        } else {
            $inputs.eq(0).removeClass(_this.options.hidden);
        }
    };

    /**
     * Event click on swatch to repaint the Color Picker
     * @param {event} e
     */
    ColorPicker.prototype.eventClickSwatch = function (e) {
        e.preventDefault();
        e.stopPropagation();

        var _this = this;

        // get swatch color
        let color = e.currentTarget.getAttribute('style').replace(/color:|;/gi, '');

        // `currentColor` special CSS attribute case
        if ('currentcolor' === color.toLowerCase()) {
            color = $(e.currentTarget).css('color');
        }

        _this.maincolor = color;

        // define pointers' positions from the main color
        _this.setPositionFromColor();
    };

    /**
     * Event custom on Color Picker to detroy everything properly
     * @param {event} e
     */
    ColorPicker.prototype.eventDestroyColorPicker = function () {
        var _this = this;

        _this.$colorpicker.removeClass(_this.options.opened);
        _this._detach();
    };

    /**
     * Event custom on Color Picker to detroy everything properly
     * @param {event} e
     */
    ColorPicker.prototype.eventKeyDown = function (e) {
        var _this = this;
        const key = e.key.toLowerCase();

        // close the Color Picker on ESCAPE key
        if ('escape' === key) {
            _this.$el.trigger('click');
            return;
        }

        if (!_this.options.saturation && !_this.options.hue && !_this.options.alpha) {
            return;
        }

        const _moves = {
            arrowup: {x: 0, y: -1},
            arrowdown: {x: 0, y: 1},
            arrowleft: {x: -1, y: 0},
            arrowright: {x: 1, y: 0},
        };

        if (-1 === Object.keys(_moves).indexOf(key)) {
            return;
        }

        e.preventDefault();

        const _pointer  = _this.options.saturation ? 'saturation' : (_this.options.hue ? 'hue' : 'alpha'),
            _multiplier = e.shiftKey ? 10 : 1;

        // move saturation pointer only
        const $pointer = _this.getElement('pointer', _pointer);
        let x = $pointer.getAttribute('x') * 1 + _moves[key]['x'] * _multiplier;
        let y = $pointer.getAttribute('y') * 1 + _moves[key]['y'] * _multiplier;

        // check maxima
        x = x < 0 ? 0 : (x > _this.areas.width ? _this.areas.width : x);
        y = y < 0 ? 0 : (y > _this.areas.height ? _this.areas.height : y);

        // update position and attribute
        $pointer.style.left = `${x}px`;
        $pointer.setAttribute('x', x);
        $pointer.style.top  = `${y}px`;
        $pointer.setAttribute('y', y);

        _this.setColorFromPosition();
    };

    /**
     * Event mouse move on saturation panel
     * @param {event} e
     */
    ColorPicker.prototype.eventMovePointer = function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        // redefine `_this` and `_role` variables
        var _this = this,
            _role = document.poseidonColorPicker.role;

        // fix areas dimensions
        _this.updateColorPickerPosition();

        // set pointer's position
        const _pointer = _this.getPointerPosition(e);
        var   $pointer = _this.getElement('pointer', _role);

        // check x
        let x = _pointer.x - _this.areas.x;
        x = x < 0 ? 0 : (x > _this.areas.width ? _this.areas.width : x);

        $pointer.style.left = `${x}px`;
        $pointer.setAttribute('x', x);

        // check y
        let y = 0;

        if ('saturation' === _role) {
            y  = _pointer.y - _this.areas.y;
            y += 'body' !== _this.options.container ? _this.$container[0].scrollTop : 0;
            y  = y < 0 ? 0 : (y > _this.areas.height ? _this.areas.height : y);

            $pointer.style.top = `${y}px`;
            $pointer.setAttribute('y', y);
        }

        _this.setColorFromPosition();
    };

    // color functions ----------------------------------------------------

    /**
     * Convert HSV(A) representation to RGB(A) and HEX representation
     * @param {object} hsva
     */
    ColorPicker.prototype.hsv2rgb = function (hsva) {
        const value    = hsva.v / 100,
            saturation = hsva.s / 100;

        let chroma  = saturation * value,
            hueBy60 = hsva.h / 60,
            x       = chroma * (1 - Math.abs(hueBy60 % 2 - 1)),
            m       = value - chroma;

        chroma = (chroma + m);
        x = (x + m);

        const index = Math.floor(hueBy60) % 6,
            red     = Math.round([chroma, x, m, m, x, chroma][index] * 255),
            green   = Math.round([x, chroma, chroma, x, m, m][index] * 255),
            blue    = Math.round([m, m, x, chroma, chroma, x][index] * 255);

        return {
            r: red,
            g: green,
            b: blue,
            a: 'undefined' === typeof hsva.a ? 1 : hsva.a,
            hex: '#' + (16777216 | blue | (green << 8) | (red << 16)).toString(16).slice(1),
        };
    }

    /**
     * Convert HSV(A) representation to HSL(A) representation
     * @param {object} hsva
     */
    ColorPicker.prototype.hsv2hsl = function (hsva) {
        const value   = hsva.v / 100,
            lightness = value * (1 - (hsva.s / 100) / 2);

        let saturation = 0;

        if (lightness > 0 && lightness < 1) {
            saturation = Math.round((value - lightness) / Math.min(lightness, 1 - lightness) * 100);
        }

        return {
            h: hsva.h,
            s: saturation,
            l: Math.round(lightness * 100),
            a: 'undefined' === typeof hsva.a ? 1 : hsva.a,
        };
    }

    /**
     * Convert RGB(A) representation to HEX string
     * @param {object} rgba
     * @param {object} options
     */
    ColorPicker.prototype.rgb2hex = function (rgba, options) {
        let red   = rgba.r.toString(16);
        let green = rgba.g.toString(16);
        let blue  = rgba.b.toString(16);
        let alpha = '';

        // fix colors
        red   = rgba.r < 16 ? '0' + red : red;
        green = rgba.g < 16 ? '0' + green : green;
        blue  = rgba.b < 16 ? '0' + blue : blue;

        // check alpha
        if (options.alpha) {
            var rgba_a = ('undefined' === typeof rgba.a ? 1 : rgba.a) * 255 | 0;
            alpha  = rgba_a.toString(16);
            alpha  = rgba_a < 16 ? '0' + alpha : alpha;
            alpha  = 'ff' === alpha && !options.alwaysAlpha ? '' : alpha;
        }

        return '#' + red + green + blue + alpha;
    }

    /**
     * Convert RGB(A) representation to HSV(A) representation
     * @param {object} rgba
     */
    ColorPicker.prototype.rgb2hsv = function (rgba) {
        const red   = rgba.r / 255;
        const green = rgba.g / 255;
        const blue  = rgba.b / 255;

        const huemax = Math.max(red, green, blue);
        const huemin = Math.min(red, green, blue);

        const chroma = huemax - huemin;
        const value  = huemax;

        let hue = 0;
        let saturation = 0;

        if (chroma) {
            hue = huemax === red ? (green - blue) / chroma : hue;
            hue = huemax === green ? 2 + (blue - red) / chroma : hue;
            hue = huemax === blue ? 4 + (red - green) / chroma : hue;
            saturation = huemax ? chroma / huemax : saturation;
        }

        hue = Math.floor(hue * 60);

        return {
            h: hue < 0 ? hue + 360 : hue,
            s: Math.round(saturation * 100),
            v: Math.round(value * 100),
            a: 'undefined' === typeof rgba.a ? 1 : rgba.a,
        };
    }

    /**
     * Convert HEX string to RGB(A) representation
     * @param {string} color
     * @param {object} options
     */
    ColorPicker.prototype.str2rgb = function (color, options) {
        // retrieve color from var() CSS attribute
        if ('var(' === color.substring(0, 4).toLowerCase()) {
          let matchvar = /^var\(([a-zA-Z0-9-]{1,})(.*)\)/i.exec(color);
          color        = getComputedStyle(document.documentElement).getPropertyValue(matchvar[1]);
        }

        const regex = /^((rgba)|rgb)[\D]+([\d.]+)[\D]+([\d.]+)[\D]+([\d.]+)[\D]*?([\d.]+|$)/i;
        let match, rgba;

        // Default to black for invalid color strings
        const canvas = document.createElement('canvas').getContext('2d');
        canvas.fillStyle = '#000000';

        // Use canvas to convert the string to a valid color string
        canvas.fillStyle = color;
        match = regex.exec(canvas.fillStyle);

        if (match) {
          rgba = {
            r: match[3] * 1,
            g: match[4] * 1,
            b: match[5] * 1,
            a: options.alpha ? match[6] * 1 : 1,
          };
        } else {
          match = canvas.fillStyle.replace('#', '').match(/.{2}/g).map(h => parseInt(h, 16));

          rgba = {
            r: match[0],
            g: match[1],
            b: match[2],
            a: 1,
          };
        }

        return rgba;
    }

    // de•attach ----------------------------------------------------------

    /**
     * Attach the color picker's events
     */
    ColorPicker.prototype._attach = function () {
        var _this     = this,
            $document = $(document),
            _panels   = {
                alpha: _this.$alpha,
                hue: _this.$hue,
                saturation: _this.$saturation,
            };

        // custom eventListeners to make them removable from DOM
        const downMouse = function (e) {
            document.poseidonColorPicker.role = _this.getRole(e);

            _panels[document.poseidonColorPicker.role].on('mouseenter', enterMouse);
            _panels[document.poseidonColorPicker.role].on('mouseleave', leaveMouse);
            _panels[document.poseidonColorPicker.role].on('mousemove', moveMouse);
            _panels[document.poseidonColorPicker.role].on('mouseup', upMouse);

            _this.eventMovePointer(e);
        };
        const enterMouse = function (e) {
            $document.off('mousemove', moveMouse);
            $document.off('mouseup', upMouse);

            _panels[document.poseidonColorPicker.role].on('mousemove', moveMouse);
            _panels[document.poseidonColorPicker.role].on('mouseup', upMouse);

            _this.eventMovePointer(e);
        };
        const leaveMouse = function (e) {
            _panels[document.poseidonColorPicker.role].off('mousemove', moveMouse);
            _panels[document.poseidonColorPicker.role].off('mouseup', upMouse);

            $document.on('mousemove', moveMouse);
            $document.on('mouseup', upMouse);

            _this.eventMovePointer(e);
        };
        const moveMouse = function (e) {
            _this.eventMovePointer(e);
        };
        const upMouse = function (e) {
            _panels[document.poseidonColorPicker.role].off('mouseenter', enterMouse);
            _panels[document.poseidonColorPicker.role].off('mouseleave', leaveMouse);
            _panels[document.poseidonColorPicker.role].off('mousemove', moveMouse);
            _panels[document.poseidonColorPicker.role].off('mouseup', upMouse);

            $document.off('mousemove', moveMouse);
            $document.off('mouseup', upMouse);

            _this.eventMovePointer(e);

            // execute onMouseUp function if set
            if ('function' === typeof _this.options.onMouseUp) {
                _this.options.onMouseUp.call(this, _this.maincolor, _this);
            }

            document.poseidonColorPicker.role = null;
        };

        // bind mouse down events on panels
        _this.$saturation.on('mousedown', downMouse);
        _this.$hue.on('mousedown', downMouse);
        _this.$alpha.on('mousedown', downMouse);

        // bind preview panel to destroy the color picker
        _this.$preview.one('click', function (e) {
            e.stopPropagation();
            _this.$colorpicker.trigger('destroy');
        });

        // bind mouse down event on document to detroy the color picker
        $document.on('mousedown', function (e) {
            e.stopPropagation();
            const $self = $(e.target);

            if ($self.closest('.poseidon-color-picker').length || $self.hasClass('poseidon-color-picker')) {
                return;
            }

            if ($self.closest('.' + _this.options.element).length || $self.hasClass(_this.options.element)) {
                return;
            }

            _this.$colorpicker.trigger('destroy');
            $self.off(e);
        });

        // bind key press events
        $document.on('keydown', $.proxy(_this.eventKeyDown, _this));
    };

    /**
     * Detach the color picker's events
     */
    ColorPicker.prototype._detach = function () {
        var _this     = this,
            $document = $(document);

        // unbind events
        _this.$saturation.off();
        _this.$hue.off();
        _this.$alpha.off();
        $document.off('keydown', _this.eventKeyDown);
    };

    /**
     * Method declaration
     */
    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                // Default behavior to append the color picker's dialog
                container: 'body',

                // Enable or disable alpha support
                alpha: true,
                // Enable or disable hue support
                hue: true,
                // Enable or disable inputs change event
                inputs: true,
                // Enable or disable preview support
                preview: true,
                // Enable or disable saturation support
                saturation: true,
                // Enable or disable toggle buttons
                toggleButtons: true,

                // Set to true to always include the alpha in the color value
                alwaysAlpha: false,

                // Set the default color at the picker's initialization
                defaultColor: '#ff0000',

                // Set the color picker's display mode
                // When set to true, the color picker will be appended to the container as an inline component
                inline: false,

                // Callback functions to execute
                onChange: null,
                onMouseUp: null,

                    // @todo
                    // Set the sliders orientation. It can be:
                    // • horizontal: displays the sliders below the swatches
                    // • vertical: displays the sliders next to the saturation panel
                    // (not yet implemented in JS)
                    //orientation: 'horizontal',

                // Set the preferred color string output:
                // • mixed: displays `#RRGGBB` or `rgba(R, G, B, A)` (with alpha), or direct value from the swatches
                // • hex: displays `#RRGGBB` or `#RRGGBBAA`
                // • rgb: displays `rgb(R, G, B)` or `rgba(R, G, B, A)`
                // • hsl: displays `hsl(H, S, L)` or `hsla(H, S, L, A)`
                output: 'mixed',

                    // @todo
                    // Set the color picker placement around the main element. It can be:
                    // • bottom: force the placement at the bottom of the main element
                    // • top: force the placement at the top of the main element
                    // • auto: lets the script define the placement depending on scroll and page's height
                    // (not yet implemented in JS)
                    //placement: 'auto',

                // Set the desired color swatches to display
                swatches: [],
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new ColorPicker($(this), settings);
            });
        }
    };

    $.fn.poseidonColorPicker = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on poseidonColorPicker');
            return false;
        }
    };
})(window.jQuery);
