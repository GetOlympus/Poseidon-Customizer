<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Title control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class TitleControl extends Control
{
    /**
     * @var string
     */
    protected $available_headings = ['h2', 'h3', 'h4', 'h5', 'h6'];

    /**
     * @var string
     */
    public $heading = 'h2';

    /**
     * @var string
     */
    public $type = 'poseidon-title-control';

    /**
     * Render the control's content
     *
     * @see Poseidon-Customizer\src\Poseidon\Resources\views\_base-control.html.php
     * @return void
     */
    protected function render_content()
    {
        $heading = in_array($this->heading, $this->available_headings) ? $this->heading : 'h2';

        // Vars
        $vars = [
            'description' => $this->description,
        ];

        $val = $this->value();

        // Blocks
        $blocks = [
            'header' => sprintf(
                '<span class="%s">%s</span>',
                $heading,
                $this->label
            ),
        ];

        require(self::view().S.$this->template);
    }
}
