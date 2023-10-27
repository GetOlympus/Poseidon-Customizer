/*!
 * color-palette-control.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    $(document).ready(function () {
        const $els = $('main.pos-c-body.color-palette-body');

        if (!$els.length) {
            return;
        }

        $.each($els, function (idx, elt) {
            const $parent = $(elt),
                _id       = $parent.attr('id'),
                $style    = $('#' + _id + '-styles'),
                $palettes = $('#' + _id + '-dropdown'),
                $colors   = $parent.find('> .colors > div'),
                options   = JSON.parse($parent.find('> .colors').attr('color-picker'));

            // update options
            options.container = '#' + _id + '-aside';
            options.inline    = true;
            options.onMouseUp = function (color, picker) {
                const cssvar = picker.$el.attr('data-css-var');
                picker.$el.find('style').html(':root{' + cssvar + ':' + color + '}');
                picker.$el.find('span.tooltip b').text('#' + color);
            };

            /**
             * Color Picker
             */
            $.each($colors, function (idx, elt) {
                const $self = $(elt);
                options.defaultColor = $self.find('input').attr('value');
                $self.poseidonColorPicker(options);
            });

            /**
             * Dropdown
             */
            $parent.find('a.action').poseidonDropdown({fog: false});

            /**
             * Palettes
             */
            $palettes.find('div.palette').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var $self    = $(e.currentTarget),
                    $current = $palettes.find('div.palette.checked');

                if ($current.length) {
                    $current.removeClass('checked');
                }

                $self.addClass('checked');

                // update color values
                $parent.find('> input[name="' + _id + '[palette]"]').attr('value', $self.attr('data-id'));

                // update color styles
                $.each($self.find('> .colors > div'), function (idx, elt) {
                    const $elt  = $(elt),
                        $target = $($colors[idx]);

                    const color = $elt.attr('data-color'),
                        cssvar  = $target.attr('data-css-var');

                    $target.css({color: color});
                    $target.find('input').attr('value', color);
                    $target.find('style').html(':root{' + cssvar + ':' + color + '}');
                });

                // close dropdown
                $palettes.removeClass('opened');
                $parent.find('a.action').removeClass('opened');
            });
        });
    });
})(window.jQuery);
