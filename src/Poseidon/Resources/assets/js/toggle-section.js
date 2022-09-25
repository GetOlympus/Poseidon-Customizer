/*!
 * toggle-section.js v0.0.1
 * https://github.com/GetOlympus/Poseidon-Customizer
 *
 * Let the section accessible or not depending on a toggle button.
 *
 * Copyright 2022 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

;(function (api, $) {
    "use stric";

    // this plugin works ONLY with WordPress wpCustomize
    if (!api) {
        return;
    }

    // useful variables
    var _attachEvents         = api.Section.prototype.attachEvents,
        _embed                = api.Section.prototype.embed,
        _isContextuallyActive = api.Section.prototype.isContextuallyActive,
        _type                 = 'poseidon-toggle-section';

    // extend Section
    api.Section = api.Section.extend({
        attachEvents: function () {
            if (_type !== this.params.type) {
                _attachEvents.call(this);
                return;
            }

            var section   = this,
                container = section.container,
                toggle    = container.find('input.pos-toggle-checkbox'),
                controlid = this.params.id + '-status';

            // expand or collapse accordion section on click
            container.find('.accordion-section-title, .customize-section-back').on('click keydown', function (e) {
                if (api.utils.isKeydownButNotEnterEvent(e)) {
                    return;
                }

                e.preventDefault();

                // check toggle status
                if (true !== toggle.prop('checked')) {
                    return;
                }

                // expand or collapse
                if (section.expanded()) {
                    section.collapse();
                } else {
                    section.expand();
                }
            });

            // control definition
            var setting = new api.Setting(
                    this.params.id + '-setting',
                    true === toggle.prop('checked')
                ),
                control = new api.Control(controlid, {
                    active: true,
                    label: this.params.title,
                    priority: 0,
                    section: this.params.id,
                    setting: setting,
                    type: 'checkbox',
                    value: toggle.val(),
                });

            // active status
            control.active.validate = function() {
                return true === toggle.prop('checked');
            };

            // add control
            api.control.add(control);
            control.active.set(true);
            control.container.hide();

            // bind onClick event
            toggle.on('click', function () {
                var $self    = $(this),
                    oldValue = $self.val();

                // update source value
                $self.val($self.prop('checked') ? 'on' : 'off');

                // update target checked property and status
                control.container.find('input').prop('checked', true === $self.prop('checked'));
                control.container.find('input').val($self.val());

                // update setting
                control.setting.set(true === $self.prop('checked'));

                // update the "Publish" button if needed
                if (true === $self.prop('checked') && 'off' === oldValue) {
                    api.state('saved').set(true);
                }
            });
        },
        embed: function () {
            _embed.call(this);
        },
        isContextuallyActive: function () {
            return _type !== this.params.type ? _isContextuallyActive.call(this) : true;
        }
    });
})(window.wp.customize, window.jQuery);
