/*!
 * number-control.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    $(document).ready(function () {
        const $els = $('main.pos-c-body.number-body');

        if (!$els.length) {
            return;
        }

        $.each($els, function (idx, elt) {
            const $self = $(elt);

            $self.poseidonNumber({
                input: 'input[type="number"]',
                minus: 'button.minus',
                plus: 'button.plus',
            });
        });
    });
})(window.jQuery);
