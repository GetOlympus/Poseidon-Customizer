/*!
 * dimensions-control.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    $(document).ready(function () {
        const $els = $('main.pos-c-body.dimensions-body');

        if (!$els.length) {
            return;
        }

        $.each($els, function (idx, elt) {
            const $self = $(elt);

            $self.poseidonDimensions({
                fields: 'input[type="number"]',
                lock: 'button.pos-lock',
                icon: {
                    lock: 'dashicons-lock',
                    unlock: 'dashicons-unlock',
                },
            });
        });
    });
})(window.jQuery);
