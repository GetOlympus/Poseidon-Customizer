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
     * @var array
     */
    public static $scripts = [
        OL_POSEIDON_ASSETSPATH.'js'.S.'slider-control.js',
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

        // Get unit and value from user settings
        $unit  = parent::valueCheck($this->value('unit'), true, $this->default_values['unit']);
        $value = parent::valueCheck($this->value(), true, $this->default_values['min']);

        // Get current values from options
        $current_values = isset($this->values[$unit]) ? $this->values[$unit] : reset($this->values);

        // Build unit choices
        $choices = '';

        foreach ($this->values as $val) {
            $choices .= sprintf(
                '<option value="%s"%s data-min="%s" data-max="%s" data-step="%s">%s</option>',
                $val['unit'],
                $val['unit'] === $unit ? ' selected' : '',
                $val['min'],
                $val['max'],
                $val['step'],
                $val['unit'],
            );
        }

        // Check value
        if ($value < $current_values['min']) {
            $value = $current_values['min'];
        } else if ($value > $current_values['max']) {
            $value = $current_values['max'];
        }

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'class'   => 'slider-body',
            'content' => sprintf(
                '<input type="range" name="%s[value]" value="%s" min="%s" max="%s" step="%s" %s /><div>%s%s%s</div>',
                $this->id,
                $value,
                $current_values['min'],
                $current_values['max'],
                $current_values['step'],
                $this->get_link(),
                sprintf(
                    '<input type="number" value="%s" min="%s" max="%s" step="%s" />',
                    $value,
                    $current_values['min'],
                    $current_values['max'],
                    $current_values['step'],
                ),
                sprintf(
                    '<select name="%s[unit]" %s%s>%s</select>',
                    $this->id,
                    $this->get_link('unit'),
                    1 >= count($this->values) ? ' disabled' : '',
                    $choices,
                ),
                '<b></b>',
            ),
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);
    }

    /**
     * Get the settings options
     *
     * @return array
     */
    public static function settings() : array
    {
        return [
            'default' => 'sanitize_text_field',
            'unit'    => 'sanitize_key',
        ];
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
