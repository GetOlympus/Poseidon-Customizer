/*!
 * color-control.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    $(document).ready(function () {
        const $els = $('main.pos-c-body.color-body');

        if (!$els.length) {
            return;
        }

        $.each($els, function (idx, elt) {
            const $self = $(elt),
                $color  = $self.find('[color-picker]'),
                options = JSON.parse($color.attr('color-picker'));

            // update options
            options.container    = '#' + $self.attr('id') + '-aside';
            options.inline       = true;
            options.defaultColor = $color.find('input').attr('value');

            // color picker events
            $color.poseidonColorPicker(options);
        });
    });
})(window.jQuery);
