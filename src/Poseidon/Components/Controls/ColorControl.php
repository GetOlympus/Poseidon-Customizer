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
    protected $textdomain = 'poseidon-color';

    /**
     * @var string
     */
    public $type = 'poseidon-color-control';

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $colors = '';

        foreach ($this->colors as $i => $color) {
            $colors .= sprintf(
                '<div id="%s" class="pos-c-tooltip pos-c-colorpicker" style="color: %s" data-picker>%s%s</div>',
                $this->id.'-'.$i,
                $color['color'],
                sprintf(
                    '<input type="text" name="%s" value="%s" />',
                    $this->id.'['.$i.']',
                    $color['color'],
                ),
                sprintf(
                    '<span class="tooltip">%s</span>',
                    $color['label'],
                ),
            );
        }

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'content' => $colors,
        ]);

        self::view('aside', [
            'content' => sprintf(
                '<aside id="%s-aside" class="pos-c-aside"></aside>',
                $this->id,
            ),
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);

        self::view('script', [
            'content' => sprintf(
                '
(function ($) {
    const _id   = "%s",
        options = %s;

    // update options
    options.container = "#" + _id + "-aside";
    options.inline    = true;

    // color picker events
    $.each($("#" + _id + " div[data-picker]"), function (idx, elt) {
        const $self = $(elt);
        options.defaultColor = $self.find("input").attr("value");
        $self.poseidonColorPicker(options);
    });
})(window.jQuery);
                ',
                $this->id,
                json_encode($this->configs),
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

        $this->json['colors']  = $this->colors;
        $this->json['configs'] = $this->configs;
    }*/

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
