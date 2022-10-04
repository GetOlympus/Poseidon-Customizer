<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Slider control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class SliderControl extends Control
{
    /**
     * @var array
     */
    protected $available_units = ['', '%', 'em', 'pt', 'px', 'rem', 'vh', 'vw'];

    /**
     * @var array
     */
    public $custom_units = [];

    /**
     * @var array
     */
    protected $default_values = [
        'list' => [],
        'min'  => 0,
        'max'  => 100,
        'step' => 1,
        'unit' => '%',
    ];

    /**
     * @var string
     */
    public $type = 'poseidon-slider-control';

    /**
     * @var array
     */
    protected $units = [];

    /**
     * @var array
     */
    public $values = [];

    /**
     * An Underscore (JS) template for this control's content (but not its container).
     */
    protected function content_template() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Vars
        $vars = [
            'description' => $this->description,
            'title'       => $this->label,
        ];

        $vals = $this->value();
        $vals = is_null($vals) ? [
            'unit'  => $this->default_values['unit'],
            'value' => $this->default_values['min'],
        ] : $vals;

        $current = $this->values[$vals['unit']];

        // Blocks
        $blocks = [
            'body' => sprintf(
                '<input type="number" id="%s" value="%s" %s /><span></span>',
                $this->id,
                $this->id,
                $vals['value'],
                sprintf(
                    'min="%f" max="%f" step="%f"',
                    $current['min'],
                    $current['max'],
                    $current['step']
                ),
            ),
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

        $json['units']  = $this->units;
        $json['values'] = $this->values;

        return $json;
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        // Define values from defaults
        if (empty($this->values)) {
            $this->values = [
                $this->default_values
            ];
        }

        // Add your own units
        $this->available_units = array_merge($this->available_units, $this->custom_units);

        $units  = [];
        $values = [];

        // Iterate on values
        foreach ($this->values as $value) {
            $value['unit'] = isset($value['unit']) ? $value['unit'] : $this->default_values['unit'];

            // Accepts only 1 unit
            if (in_array($value['unit'], $units)) {
                continue;
            }

            // Fix unit from availables only
            $unit = in_array($value['unit'], $this->available_units) ? $value['unit'] : $this->available_units[0];

            $units[]  = $unit;
            $values[$unit] = [
                'list' => isset($value['list']) ? (array) $value['list'] : $this->default_values['list'],
                'min'  => isset($value['min']) ? (float) $value['min'] : $this->default_values['min'],
                'max'  => isset($value['max']) ? (float) $value['max'] : $this->default_values['max'],
                'step' => isset($value['step'])
                    ? ('any' === $value['step'] ? 'any' : abs((float) $value['step']))
                    : $this->default_values['step'],
                'unit' => $unit,
            ];
        }

        $this->units  = $units;
        $this->values = $values;
    }
}
