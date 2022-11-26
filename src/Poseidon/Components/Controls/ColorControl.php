<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builds Color control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ColorControl extends Control
{
    /**
     * @var array
     */
    public $colors = [];

    /**
     * @var array
     */
    public $configs = [];

    /**
     * @var array
     */
    protected $default_configs = [
        'alpha'         => true,
        'hue'           => true,
        'preview'       => true,
        'saturation'    => true,
        'toggleButtons' => true,

        'alwaysAlpha'  => false,
        'defaultColor' => '#000000',
        'inline'       => true,
        'output'       => 'mixed',

        'swatches'     => [],
    ];

    /**
     * @var string
     */
    protected $template = 'color.html.php';

    /**
     * @var string
     */
    public $type = 'poseidon-color-control';

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\color.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Get values
        $values = $this->value();

        // Vars
        $vars = [
            'title'       => $this->label,
            'description' => $this->description,
            'id'          => $this->id,
            'colors'      => $this->colors,
            'configs'     => $this->configs,
            'value'       => $values,
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

        $json['colors']  = $this->colors;
        $json['configs'] = $this->configs;

        return $json;
    }

    /**
     * Fix variables with default config values
     *
     * @param  string  $name
     * @param  object  $value
     * @return object  $value
     */
    protected function fixConfig($name, $value)
    {
        $booleans     = ['alpha', 'hue', 'preview', 'saturation', 'toggleButtons', 'alwaysAlpha', 'inline'];
        $orientations = ['horizontal', 'vertical'];
        $outputs      = ['hex', 'rgb', 'hsl', 'mixed'];
        $placements   = ['bottom', 'top', 'auto'];

        if (in_array($name, $booleans)) {
            return (bool) $value;
        }

        if ('orientation' === $name) {
            return in_array($value, $orientations) ? $value : $orientations[0];
        }

        if ('output' === $name) {
            return in_array($value, $outputs) ? $value : $outputs[0];
        }

        if ('placement' === $name) {
            return in_array($value, $placements) ? $value : $placements[0];
        }

        return $value;
    }

    /**
     * Get color well formatted
     *
     * @param  string  $key
     * @param  object  $color
     * @return array   $color
     */
    protected function getColor($key, $color)
    {
        $index  = ['initial', 'hover', 'active', 'visited'];
        $labels = [
            'initial' => Translate::t('color.initial', $this->textdomain),
            'hover'   => Translate::t('color.hover', $this->textdomain),
            'active'  => Translate::t('color.active', $this->textdomain),
            'visited' => Translate::t('color.visited', $this->textdomain),
        ];

        if (is_int($key)) {
            $key = $key < 0 || 3 < $key ? $index[0] : $index[$key];
        }

        if (!is_array($color)) {
            $color = [
                'label' => $labels[$key],
                'color' => $color,
            ];
        }

        return [
            $key => [
                'label' => isset($color['label']) ? $color['label'] : $labels[$key],
                'color' => isset($color['color']) ? $color['color'] : $color[0],
            ],
        ];
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        // Define configs properly
        foreach($this->default_configs as $config => $value) {
            if (!array_key_exists($config, $this->configs)) {
                continue;
            }

            $this->configs[$config] = $this->fixConfig($config, $this->configs[$config]);
        }

        $this->configs = array_merge($this->default_configs, $this->configs);

        // Define colors properly
        $temp_colors  = !is_array($this->colors) ? [$this->colors] : $this->colors;
        $this->colors = [];

        foreach($temp_colors as $key => $color) {
            $this->colors = array_merge($this->colors, $this->getColor($key, $color));
        }
    }
}
