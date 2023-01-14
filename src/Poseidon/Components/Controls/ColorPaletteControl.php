<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builds Color Palette control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ColorPaletteControl extends Control
{
    /**
     * @var array
     */
    public $configs = [];

    /**
     * @var string
     */
    public $css_var = '--poseidon-palette';

    /**
     * @var array
     */
    public $custom_palettes = [];

    /**
     * @var string
     */
    protected $default_color = '#000000';

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
     * @var integer
     */
    protected $default_number = 8;

    /**
     * @var integer
     */
    protected $default_css_var = '--poseidon-palette';

    /**
     * @var integer
     */
    public $number;

    /**
     * @var array
     */
    protected $palettes = [];

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-color-palette';

    /**
     * @var string
     */
    public $type = 'poseidon-color-palette-control';

    /**
     * @var boolean
     */
    public $use_default_palettes = true;

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Get values
        $values = $this->value();
        $values = is_null($values) || empty($values) ? $this->palettes[0] : $values;

        // Define current values and styles
        $current = isset($values['colors']) && is_array($values['colors']) ? $values : $this->palettes[0];
        $styles  = [];

        foreach ($current['colors'] as $i => $color) {
            $styles[] = sprintf('%s-%d: %s', $this->css_var, $i + 1, $color);
        }

        $current['palette'] = isset($current['palette']) ? $current['palette'] : $this->css_var.'-1';

        $labels = [
            'title'  => Translate::t('color-palette.title', $this->textdomain),
            'color1' => Translate::t('color-palette.color1', $this->textdomain),
            'color2' => Translate::t('color-palette.color2', $this->textdomain),
            'color3' => Translate::t('color-palette.color3', $this->textdomain),
            'color4' => Translate::t('color-palette.color4', $this->textdomain),
            'color5' => Translate::t('color-palette.color5', $this->textdomain),
            'color6' => Translate::t('color-palette.color6', $this->textdomain),
            'color7' => Translate::t('color-palette.color7', $this->textdomain),
            'color8' => Translate::t('color-palette.color8', $this->textdomain),
        ];

        // Works on main colors
        $colors = '';

        foreach ($current['colors'] as $i => $color) {
            $colors .= sprintf(
                '<div id="%s" class="pos-c-tooltip pos-c-colorpicker" style="color:%s" data-css-var="%s">%s%s%s</div>',
                $this->id.'-'.$i,
                $color,
                sprintf(
                    '%s-%d',
                    $this->css_var,
                    $i + 1
                ),
                sprintf(
                    '<input type="text" name="%s[colors][%d]" value="%s" />',
                    $this->id,
                    $i + 1,
                    $color
                ),
                sprintf(
                    '<span class="tooltip">%s<br/>%s</span>',
                    $i > 7 ? $labels['title'] : $labels['color'.($i + 1)],
                    $color
                ),
                sprintf(
                    '<style>:root {%s: %s}</style>',
                    sprintf(
                        '%s-%d',
                        $this->css_var,
                        $i + 1
                    ),
                    $color,
                ),
            );
        }

        // Works on palettes
        $palettes = '';

        foreach ($this->palettes as $p => $palette) {
            $palette['output'] = '';

            foreach ($palette['colors'] as $i => $color) {
                $palette['output'] .= sprintf(
                    '<div class="pos-c-colorpicker" style="color: %s" data-css-var="%s" data-color="%s"></div>',
                    $color,
                    sprintf(
                        '%s-%d',
                        $this->css_var,
                        $i + 1
                    ),
                    $color,
                );
            }

            $palettes .= sprintf(
                '<div class="palette%s" data-id="%s">
                    <h4>%s</h4>
                    <nav class="colors">%s</nav>
                </div>',
                $current['palette'] == $palette['palette'] ? ' checked' : '',
                $palette['palette'],
                sprintf($labels['title'], $p + 1),
                $palette['output'],
            );
        }

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'content' => sprintf(
                '
<input type="hidden" name="%s[palette]" value="%s" />
<input type="hidden" name="%s[css_var]" value="%s" />

<nav class="colors">%s</nav>

<a href="#dropdown" class="action" data-dropdown="%s-dropdown">
    <span class="dashicons dashicons-arrow-down-alt2"></span>
</a>

<aside id="%s-aside" class="pos-c-aside"></aside>

<aside id="%s-dropdown" class="pos-c-dropdown palettes">%s</aside>
                ',
                $this->id,
                $current['palette'],
                $this->id,
                $this->css_var,
                $colors,
                $this->id,
                $this->id,
                $this->id,
                $palettes,
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

    // main contents
    const $parent = $("#" + _id),
        $style    = $("#" + _id + "-styles"),
        $palettes = $("#" + _id + "-dropdown"),
        $colors   = $parent.find("> .colors > div");

    // update options
    options.container = "#" + _id + "-aside";
    options.inline    = true;
    options.onMouseUp = function (color, picker) {
        const cssvar = picker.$el.attr("data-css-var");
        picker.$el.find("style").html(":root{" + cssvar + ":" + color + "}");
    };

    /**
     * Color Picker
     */
    $.each($colors, function (idx, elt) {
        const $self = $(elt);
        options.defaultColor = $self.find("input").attr("value");
        $self.poseidonColorPicker(options);
    });

    /**
     * Dropdown
     */
    $parent.find("a.action").poseidonDropdown({fog: false});

    /**
     * Palettes
     */
    $palettes.find("div.palette").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $self    = $(e.currentTarget),
            $current = $palettes.find("div.palette.checked");

        if ($current.length) {
            $current.removeClass("checked");
        }

        $self.addClass("checked");

        // update color values
        $parent.find("> input[name=\"" + _id + "[palette]\"]").attr("value", $self.attr("data-id"));

        // update color styles
        $.each($self.find("> .colors > div"), function (idx, elt) {
            const $elt  = $(elt),
                $target = $($colors[idx]);

            const color = $elt.attr("data-color"),
                cssvar  = $target.attr("data-css-var");

            $target.css({color: color});
            $target.find("input").attr("value", color);
            $target.find("style").html(":root{" + cssvar + ":" + color + "}");
        });

        // close dropdown
        $palettes.removeClass("opened");
        $parent.find("a.action").removeClass("opened");
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

        $this->json['configs']  = $this->configs;
        $this->json['palettes'] = $this->palettes;
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
     * Get default palettes
     *
     * @return array
     */
    protected function getDefaultPalettes()
    {
        /**
         * Notes:
         *   Color 1 - primary brand color (links, emphasis)
         *   Color 2 - alternative color (hover)
         *   Color 3 - headings, subheadings and general titles
         *   Color 4 - general text paragraphs
         *   Color 5 - borders, dividers, icons
         *   Color 6 - subtle backgrounds
         *   Color 7 - main site's background
         *   Color 8 - alternative color (header / footer backgrounds)
         */
        $default_palettes = !$this->use_default_palettes ? $this->custom_palettes : array_merge([
            ['#2872fa', '#1559ed', '#3a4f66', '#192a3d', '#e1e8ed', '#f2f5f7', '#fafbfc', '#ffffff'],
            ['#3eaf7c', '#33a370', '#415161', '#2c3e50', '#e2e7ed', '#edeff2', '#f8f9fb', '#ffffff'],
            ['#fb7258', '#f74d67', '#6e6d76', '#0e0c1b', '#dfdfe2', '#f4f4f5', '#fbfbfb', '#ffffff'],
            ['#98c1d9', '#e84855', '#475671', '#293241', '#e7e9ef', '#f3f4f7', '#fbfbfc', '#ffffff'],
            ['#006466', '#065a60', '#7f8c9a', '#ffffff', '#1e2933', '#0f141a', '#141b22', '#1b242c'],
            ['#007f5f', '#55a630', '#365951', '#192c27', '#e6f0ee', '#f2f7f6', '#fbfcfc', '#ffffff'],
            ['#7456f1', '#5e3fde', '#4d5d6d', '#102136', '#e7ebee', '#f3f5f7', '#fbfbfc', '#ffffff'],
            ['#00509d', '#003f88', '#828487', '#28292a', '#e8ebed', '#f4f5f6', '#fbfbfc', '#ffffff'],
            ['#84a98c', '#52796f', '#cad2c5', '#84a98c', '#384b56', '#212b31', '#29363d', '#314149'],
            ['#ff6d00', '#ff8500', '#cfa9ef', '#e3cbf6', '#5a189a', '#240046', '#3c096c', '#410a75'],
            ['#ffcd05', '#fcb424', '#504e4a', '#0a0500', '#edeff2', '#f9fafb', '#fdfdfd', '#ffffff'],
            ['#a8977b', '#7f715c', '#3f4245', '#111518', '#eaeaec', '#f4f4f5', '#ffffff', '#ffffff'],
            ['#48bca2', '#25ad99', '#4f4f4f', '#0a0500', '#ebebeb', '#f5f5f5', '#ffffff', '#ffffff'],
            ['#ff6310', '#fd7c47', '#687279', '#111518', '#e9ebec', '#f4f5f6', '#ffffff', '#ffffff'],
            ['#fca311', '#23396c', '#707070', '#000000', '#e0e0e0', '#f1f1f1', '#fafafa', '#ffffff'],
        ], $this->custom_palettes);

        $palettes = [];
        $number   = $this->number;
        $css_var  = $this->css_var;

        foreach ($default_palettes as $id => $palette) {
            $colors = $number <= $this->default_number
                ? array_slice($palette, 0, $number)
                : array_merge(
                    $palette,
                    array_fill(
                        $this->default_number,
                        $number - $this->default_number,
                        $this->default_color
                    )
                );

            $palettes[] = [
                'palette' => $css_var.'-'.($id + 1),
                'colors'  => $colors,
            ];
        }

        return $palettes;
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

        // Define number of colors from palettes
        $this->number = 0 >= $this->number ? $this->default_number : abs((int) $this->number);

        // Define custom palettes
        $this->custom_palettes = is_array($this->custom_palettes) ? $this->custom_palettes : [$this->custom_palettes];

        // Define css_var used for CSS vars
        $this->css_var = empty($this->css_var) ? $this->default_css_var : (string) $this->css_var;

        // Define wether to use default palettes or not
        $this->use_default_palettes = is_bool($this->use_default_palettes) ? $this->use_default_palettes : true;

        // Set defaults palettes
        $this->palettes = $this->getDefaultPalettes();
    }
}
