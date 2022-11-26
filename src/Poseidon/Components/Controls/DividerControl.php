<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Divider control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class DividerControl extends Control
{
    /**
     * @var array
     */
    protected $available_margins = ['none', 'small', 'medium', 'large'];

    /**
     * @var string
     */
    public $margins = 'none';

    /**
     * @var string
     */
    public $type = 'poseidon-divider-control';

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\_base.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Vars
        $vars = [
            'hide_header' => true,
        ];

        // Blocks
        $blocks = [
            'aside' => '<hr class="poseidon-divider '.$this->margins.'" />',
        ];

        require(self::view().S.$this->template);
    }

    /**
     * JSON
     */
    public function json() // phpcs:ignore
    {
        $json = parent::json();

        // Set variables from defaults
        $this->setVariables();

        $json['margins'] = 'none' === $this->margins ? '' : $this->margins;

        return $json;
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $this->margins = in_array($this->margins, $this->available_margins)
            ? $this->margins
            : $this->available_margins[0];
    }
}
