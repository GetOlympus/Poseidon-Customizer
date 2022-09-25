<?php

namespace GetOlympus\Poseidon\Components;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Color Picker control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ColorPickerControl extends Control
{
    /**
     * @var string
     */
    //public $description = '';

    /**
     * @var integer
     */
    public $max = 100;

    /**
     * @var integer
     */
    public $min = 0;

    /**
     * @var integer
     */
    public $step = 1;

    /**
     * @var string
     */
    protected $template = 'slider-control.html.php';

    /**
     * @var string
     */
    public $type = 'poseidon-slider-control';

    /**
     * An Underscore (JS) template for this control's content (but not its container).
     */
    protected function content_template() // phpcs:ignore
    {
        require(self::view().S.$this->template);
    }

    /**
     * JSON
     */
    public function json() // phpcs:ignore
    {
        $json = parent::json();

        $json['heading']     = in_array($this->heading, $this->available_headings) ? $this->heading : 'h2';
        $json['description'] = $this->description;

        return $json;
    }
}
