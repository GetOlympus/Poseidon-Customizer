<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

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
    public $custom_palettes = [];

    /**
     * @var string
     */
    protected $default_color = '#ffffff';

    /**
     * @var integer
     */
    protected $default_number = 8;

    /**
     * @var integer
     */
    protected $default_prefix = 'poseidon-color';

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
    public $prefix;

    /**
     * @var string
     */
    protected $template = 'color-palette.html.php';

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
     * @see src\Poseidon\Resources\views\controls\_base.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
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
            $styles[] = sprintf('--%s-%d: %s', $this->prefix, $i + 1, $color);
        }

        // Vars
        $vars = [
            'title'       => $this->label,
            'description' => $this->description,
            'current'     => $current,
            'id'          => $this->id,
            'number'      => $this->number,
            'palettes'    => $this->palettes,
            'prefix'      => $this->prefix,
            'styles'      => $styles,
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

        $json['palettes'] = $this->palettes;

        return $json;
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
         *   Color 2 - alternative color (hover action)
         *   Color 3 - headings, subheadings and titles
         *   Color 4 - general text paragraphs
         *   Color 5 - borders
         *   Color 6 - subtle backgrounds (page hero, footer)
         *   Color 7 - site’s background
         *   Color 8 - lighter alternative color (header backgrounds)
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
        $prefix   = $this->prefix;

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
                'id'     => $prefix.'-'.($id + 1),
                'colors' => $colors,
            ];
        }

        return $palettes;
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        // Define number of colors from palettes
        $this->number = 0 >= $this->number ? $this->default_number : abs((int) $this->number);

        // Define custom palettes
        $this->custom_palettes = is_array($this->custom_palettes) ? $this->custom_palettes : [$this->custom_palettes];

        // Define prefix used for CSS vars
        $this->prefix = empty($this->prefix) ? $this->default_prefix : (string) $this->prefix;

        // Define wether to use default palettes or not
        $this->use_default_palettes = is_bool($this->use_default_palettes) ? $this->use_default_palettes : true;

        // Set defaults palettes
        $this->palettes = $this->getDefaultPalettes();
    }
}
