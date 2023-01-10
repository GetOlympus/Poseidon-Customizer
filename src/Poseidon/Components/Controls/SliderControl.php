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
    public $values = [];

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $default_vals = [
            'unit'  => $this->default_values['unit'],
            'value' => $this->default_values['min'],
        ];

        // Get values from user settings
        $values = $this->value();
        $values = is_null($values) ? $default_vals : array_merge($default_vals, $values);

        // Get current values from options
        $current_values = isset($this->values[$values['unit']]) ? $this->values[$values['unit']] : reset($this->values);

        // Build unit choices
        $choices = '';

        foreach ($this->values as $value) {
            $choices .= sprintf(
                '<option value="%s"%s data-min="%s" data-max="%s" data-step="%s">%s</option>',
                $value['unit'],
                $value['unit'] === $values['unit'] ? ' selected' : '',
                $value['min'],
                $value['max'],
                $value['step'],
                $value['unit']
            );
        }

        // Check value
        if ($values['value'] < $current_values['min']) {
            $values['value'] = $current_values['min'];
        } else if ($values['value'] > $current_values['max']) {
            $values['value'] = $current_values['max'];
        }

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'content' => sprintf(
                '<input type="range" name="%s[value]" value="%s" min="%s" max="%s" step="%s" /><div>%s%s%s</div>',
                $this->id,
                $values['value'], // $values['value']['value']
                $current_values['min'],
                $current_values['max'],
                $current_values['step'],
                sprintf(
                    '<input type="number" value="%s" min="%s" max="%s" step="%s" />',
                    $values['value'],
                    $current_values['min'],
                    $current_values['max'],
                    $current_values['step'],
                ),
                sprintf(
                    '<select name="%s[unit]"%s>%s</select>',
                    $this->id,
                    1 >= count($this->values) ? ' disabled' : '',
                    $choices,
                ),
                '<b></b>',
            ),
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);

        self::view('script', [
            'content' => sprintf(
                '
(function ($) {
    const _id = "%s";

    $("#" + _id).poseidonSlider({
        number: "input[type=\'number\']",
        range: "input[type=\'range\']",
        select: "select",
    });
})(window.jQuery);
                ',
                $this->id,
            ),
        ]);
    }

    /**
     * JSON
     */
    /*public function to_json() // phpcs:ignore
    {
        parent::to_json();

        // Set variables from defaults
        $this->setVariables();

        $this->json['custom_units'] = is_array($this->custom_units) ? $this->custom_units : [$this->custom_units];
        $this->json['values']       = $this->values;
    }*/

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
