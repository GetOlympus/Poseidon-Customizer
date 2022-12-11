<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Number control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class NumberControl extends Control
{
    /**
     * @var string
     */
    public $display = 'inline';

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
    protected $template = 'number.html.php';

    /**
     * @var string
     */
    public $type = 'poseidon-number-control';

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\number.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $value = (int) $this->value();
        $value = is_null($value) || $this->min > $value ? $this->min : ($value > $this->max ? $this->max : $value);

        // Vars
        $vars = [
            'title'       => $this->label,
            'description' => $this->description,

            'id'          => $this->id,
            'value'       => $value,
            'min'         => $this->min,
            'max'         => $this->max,
            'step'        => $this->step,
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

        $json['min']  = (int) $this->min;
        $json['max']  = (int) $this->max;
        $json['step'] = (int) $this->step;

        return $json;
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $this->min = (int) $this->min;
        $this->max = (int) $this->max;

        // Fix min and max properties
        if ($this->min > $this->max) {
            $temp = $this->max;
            $this->max = $this->min;
            $this->min = $temp;
            unset($temp);
        }

        $this->step = (int) $this->step;
        $this->step = 1 > $this->step ? 1 : $this->step;
    }
}
