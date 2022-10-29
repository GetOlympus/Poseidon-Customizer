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
    protected $template = 'slider.html.php';

    /**
     * @var string
     */
    public $type = 'poseidon-slider-control';

    /**
     * @var array
     */
    public $values = [];

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\slider.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $default_vals = [
            'unit'  => $this->default_values['unit'],
            'value' => $this->default_values['min'],
        ];

        // Get values from user settings
        $vals = $this->value();
        $vals = is_null($vals) ? $default_vals : array_merge($default_vals, $vals);

        // Get current values from options
        $current_values = $this->values[$vals['unit']];

        // Build unit choices
        $choices = '';

        foreach ($this->values as $value) {
            $choices .= sprintf(
                '<option value="%s"%s data-min="%s" data-max="%s" data-step="%s">%s</option>',
                $value['unit'],
                $value['unit'] === $vals['unit'] ? ' selected' : '',
                $value['min'],
                $value['max'],
                $value['step'],
                $value['unit']
            );
        }

        // Check value
        if ($vals['value'] < $current_values['min']) {
            $vals['value'] = $current_values['min'];
        } else if ($vals['value'] > $current_values['max']) {
            $vals['value'] = $current_values['max'];
        }

        // Vars
        $vars = [
            'title'       => $this->label,
            'description' => $this->description,

            'choices'     => $choices,
            'id'          => $this->id,
            'min'         => $current_values['min'],
            'max'         => $current_values['max'],
            'step'        => $current_values['step'],
            'value'       => $vals['value'],
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

        $json['custom_units'] = is_array($this->custom_units) ? $this->custom_units : [$this->custom_units];
        $json['values']       = $this->values;

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

        $values = [];

        // Iterate on values
        foreach ($this->values as $value) {
            $value['unit'] = isset($value['unit']) ? $value['unit'] : $this->default_values['unit'];

            // Fix unit from availables only
            $unit = in_array($value['unit'], $this->available_units) ? $value['unit'] : $this->available_units[0];

            // Accepts only uniq units
            if (array_key_exists($value['unit'], $values)) {
                continue;
            }

            $values[$unit] = [
                'list' => isset($value['list']) ? (array) $value['list'] : $this->default_values['list'],
                'min'  => isset($value['min'])  ? $value['min'] : $this->default_values['min'],
                'max'  => isset($value['max'])  ? $value['max'] : $this->default_values['max'],
                'step' => isset($value['step'])
                    ? ('any' === $value['step'] ? 'any' : abs($value['step']))
                    : $this->default_values['step'],
                'unit' => $unit,
            ];
        }

        $this->values = $values;
    }
}
