/*!
 * font-control.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    $(document).ready(function () {
        const $els = $('main.pos-c-body.font-body');

        if (!$els.length) {
            return;
        }

        $.each($els, function (idx, elt) {
            const $self = $(elt),
                $hidden = $self.find('input'),
                $select = $self.find('select'),
                $style  = $('#style-' + $self.attr('id'));

            // select change event
            $select.on('change', function (e) {
                const $selected = $select.find(':selected'),
                    _type  = $selected.parent('optgroup').attr('data-type'),
                    _sheet = $selected.parent('optgroup').attr('data-url'),
                    $link  = $self.find('link');

                let _text  = $selected.text(),
                    _value = $selected.val();

                if ($link.length) {
                    $link.remove();
                }

                if ('' === _text) {
                    return;
                }

                _value = '' == _value ? '-apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif' : _value;

                if ('' !== _sheet) {
                    $self.append('<link href="' + _sheet.replace('_FAMILY_', _value) + '" rel="stylesheet" />');
                }

                _text = _text.indexOf(' ') > -1 ? "'" + _text + "'" : _text;
                $style.html(':root{' + $hidden.prop('value') + ':' + _text + '}');
            });
        });
    });
})(window.jQuery);
