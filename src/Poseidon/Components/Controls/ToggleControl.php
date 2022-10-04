<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Toggle control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ToggleControl extends Control
{
    /**
     * @var string
     */
    public $display = 'inline';

    /**
     * @var string
     */
    public $type = 'poseidon-toggle-control';

    /**
     * Render the control's content
     *
     * @see Poseidon-Customizer\src\Poseidon\Resources\views\_base-control.html.php
     * @return void
     */
    protected function render_content()
    {
        // Vars
        $vars = [
            'description' => $this->description,
            'title'       => $this->label,
        ];

        $val = $this->value();

        // Blocks
        $blocks = [
            'body' => sprintf(
                '<input type="checkbox" name="%s" id="%s" value="%s" %s%s%s /><label for="%s" class="%s">%s</label>',
                $this->id,
                $this->id,
                $val,
                $this->get_link(),
                ' class="pos-toggle-checkbox"',
                $val ? ' checked="checked"' : '',
                $this->id,
                'pos-toggle',
                '<span></span>'
            ),
        ];

        require(self::view().S.$this->template);
    }
}
