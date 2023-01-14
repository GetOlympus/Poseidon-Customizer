<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builds Dimensions control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class DimensionsControl extends Control
{
    /**
     * @var array
     */
    protected $available_units = ['%', 'em', 'pt', 'px', 'rem', 'vh', 'vw'];

    /**
     * @var array
     */
    public $dimensions = [];

    /**
     * @var array
     */
    public $display = 'block';

    /**
     * @var boolean
     */
    public $lock = true;

    /**
     * @var integer
     */
    public $max = 100;

    /**
     * @var integer
     */
    public $min = 0;

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-dimensions';

    /**
     * @var string
     */
    public $type = 'poseidon-dimensions-control';

    /**
     * @var array
     */
    public $units = [];

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Get values from user settings
        $values = $this->value();
        $values = is_null($values) ? [] : $values;

        $values['values'] = isset($values['values'])
            ? $values['values']
            : [array_keys($this->dimensions)[0] => $this->min];

        $values['unit'] = isset($values['unit']) ? $values['unit'] : $this->units[0];

        // Build unit choices
        $choices = '';

        foreach ($this->units as $unit) {
            $choices .= sprintf(
                '<option value="%s"%s>%s</option>',
                $unit,
                $unit === $values['unit'] ? ' selected' : '',
                $unit
            );
        }

        // Works on dimensions
        $dimensions = '';

        foreach ($this->dimensions as $dimension => $details) {
            $dimensions .= sprintf(
                '<div>%s%s</div>',
                sprintf(
                    '<input type="number" name="%s[%s]" value="%s" min="%s" max="%s" step="1" />',
                    $this->id,
                    $dimension,
                    isset($values[$dimension]) ? $values[$dimension] : $details['value'],
                    $this->min,
                    $this->max,
                ),
                sprintf(
                    '<span>%s</span>',
                    $details['label'],
                ),
            );
        }

        // Works on configs
        $configs  = !$this->lock ? '' : sprintf(
            '<button class="pos-lock">%s</button>',
            '<span class="dashicons dashicons-unlock"></span>',
        );

        $configs .= sprintf(
            '<select name="%s[unit]"%s>%s</select><b></b>',
            $this->id,
            1 >= count($this->units) ? ' disabled' : '',
            $choices,
        );

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'content' => sprintf(
                '<div class="inputs">%s</div><div class="configs">%s</div>',
                $dimensions,
                $configs,
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

    $("#" + _id).poseidonDimensions({
        fields: "input[type=\'number\']",
        lock: "button.pos-lock",
        icon: {
            lock: "dashicons-lock",
            unlock: "dashicons-unlock",
        },
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

        $this->json['description'] = $this->description;
        $this->json['dimensions']  = (array) $this->dimensions;
        $this->json['lock']        = (bool) $this->lock;
        $this->json['min']         = (int) $this->min;
        $this->json['max']         = (int) $this->max;
        $this->json['units']       = is_array($this->units) ? $this->units : [$this->units];
    }*/

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $this->lock = (bool) $this->lock;
        $this->min  = (int) $this->min;
        $this->max  = (int) $this->max;

        // Fix negative min
        $this->min = 0 > $this->min ? 0 : $this->min;

        // Fix min and max properties
        if ($this->min > $this->max) {
            $temp = $this->max;
            $this->max = $this->min;
            $this->min = $temp;
            unset($temp);
        }

        // Build units
        $units = is_array($this->units) ? $this->units : [$this->units];
        $this->units = [];

        foreach ($units as $unit) {
            if (!in_array($unit, $this->available_units) || in_array($unit, $this->units)) {
                continue;
            }

            $this->units[] = $unit;
        }

        $this->units = empty($this->units) ? $this->available_units[0] : $this->units;

        // Build dimensions
        $dimensions = is_array($this->dimensions) ? $this->dimensions : [$this->dimensions];
        $this->dimensions = [];

        $labels = [
            'top'    => Translate::t('dimensions.top', $this->textdomain),
            'right'  => Translate::t('dimensions.right', $this->textdomain),
            'bottom' => Translate::t('dimensions.bottom', $this->textdomain),
            'left'   => Translate::t('dimensions.left', $this->textdomain),
        ];

        foreach ($dimensions as $dimension => $details) {
            if (is_int($dimension)) {
                $dimension = 4 > $dimension ? array_keys($labels)[$dimension] : array_keys($labels)[0];
            }

            if (array_key_exists($dimension, $this->dimensions)) {
                continue;
            }

            $details = !is_array($details) ? ['value' => $details] : $details;

            $this->dimensions[$dimension] = [
                'label' => isset($details['label']) ? $details['label'] : $labels[$dimension],
                'value' => isset($details['value']) ? $details['value'] : $this->min,
            ];
        }
    }
}
