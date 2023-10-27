/*!
 * poseidon.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Customizer Communicator.
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($) {
    "use stric";

    /**
     * Restrics input for the set of matched elements to the given
     * inputFilter function.
     */
    $.fn.inputFilter = function (inputFilter) {
        return this.on('input keydown keyup mousedown mouseup select contextmenu drop', function () {

            if (inputFilter(this.value)) {
                this.oldValue          = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd   = this.selectionEnd;
            } else if (this.hasOwnProperty('oldValue')) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = '';
            }
        });
    };
})(window.jQuery);
