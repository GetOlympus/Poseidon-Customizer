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
    protected $template = 'dimensions.html.php';

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
     * @see src\Poseidon\Resources\views\controls\dimensions.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Get values from user settings
        $vals = $this->value();
        $vals = is_null($vals) ? [] : $vals;

        $vals['values'] = isset($vals['values']) ? $vals['values'] : [array_keys($this->dimensions)[0] => $this->min];
        $vals['unit']   = isset($vals['unit']) ? $vals['unit'] : $this->units[0];

        // Build unit choices
        $choices = '';

        foreach ($this->units as $unit) {
            $choices .= sprintf(
                '<option value="%s"%s>%s</option>',
                $unit,
                $unit === $vals['unit'] ? ' selected' : '',
                $unit
            );
        }

        // Vars
        $vars = [
            'title'       => $this->label,
            'description' => $this->description,
            'id'          => $this->id,

            'dimensions'  => $this->dimensions,
            'lock'        => $this->lock,
            'min'         => $this->min,
            'max'         => $this->max,
            'units'       => $this->units,
            'number'      => count($this->units),
            'choices'     => $choices,
            'values'      => $vals,
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

        $json['description'] = $this->description;
        $json['dimensions']  = (array) $this->dimensions;
        $json['lock']        = (bool) $this->lock;
        $json['min']         = (int) $this->min;
        $json['max']         = (int) $this->max;
        $json['units']       = is_array($this->units) ? $this->units : [$this->units];

        return $json;
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $this->lock = (bool) $this->lock;
        $this->min  = (int) $this->min;
        $this->max  = (int) $this->max;

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
