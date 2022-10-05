<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builds Buttons control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ButtonsControl extends Control
{
    /**
     * @var array
     */
    public $buttons = [];

    /**
     * @var string
     */
    protected $template = 'buttons.html.php';

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-buttons';

    /**
     * @var string
     */
    public $type = 'poseidon-buttons-control';

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\buttons.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $vars = [
            'title'       => $this->label,
            'description' => $this->description,
            'buttons'     => $this->buttons,
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

        $json['buttons'] = (array) $this->buttons;

        return $json;
    }

    /**
     * Get html attributes from array
     */
    protected function getAttrs($attributes)
    {
        return join(' ', array_map(
            function($key) use ($attributes) {
                if (is_bool($attributes[$key])) {
                    return $attributes[$key] ? $key : '';
                }

                return $key . '="' . $attributes[$key] . '"';
            },
            array_keys($attributes)
        ));
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        // Fix buttons as array
        $this->buttons = is_array($this->buttons) ? $this->buttons : [$this->buttons];
    }
}
