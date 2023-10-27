/*!
 * elements-control.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    $(document).ready(function () {
        const $els = $('main.pos-c-body.elements-body');

        if (!$els.length) {
            return;
        }

        $.each($els, function (idx, elt) {
            const $self   = $(elt),
                $sortable = $self.find('.pos-c-sortable'),
                _disabled = 'disabled',
                _closed   = 'closed';

            // make sortable list
            $sortable.sortable({
                axis: 'y',
                cursor: 'grabbing',
                handle: 'span.sort-move',
                items: '> div.sort-item',
            });

            // build display button actions
            const $display = $sortable.find('.sort-display');
            $display.on('click', function (e) {
                e.stopPropagation();

                const $current = $(e.currentTarget),
                    $item      = $current.closest('.sort-item'),
                    _checked   = $current.find('input[type="checkbox"]').prop('checked');

                if (_checked) {
                    $item.removeClass(_disabled);
                } else {
                    $item.addClass(_disabled);
                    $item.addClass(_closed);
                }
            });

            // build toggle button actions
            const $toggle = $sortable.find('.sort-toggle');
            $toggle.off().on('click', function (e) {
                e.stopPropagation();

                const $item = $(e.currentTarget).closest('.sort-item');

                if ($item.hasClass(_disabled)) {
                    return;
                }

                if ($item.hasClass(_closed)) {
                    $item.removeClass(_closed);
                } else {
                    $item.addClass(_closed);
                }
            });
        });
    });
})(window.jQuery);
