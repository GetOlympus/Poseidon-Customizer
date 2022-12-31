<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builds Font control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class FontControl extends Control
{
    /**
     * @var array
     * @see https://fonts.google.com/ 16 most popular
     * @see https://danmarshall.github.io/google-font-to-svg-path/
     * @see https://fontjoy.com/
     */
    protected $default_fonts = [];

    /**
     * @var integer
     */
    protected $default_prefix = '--poseidon-font';

    /**
     * @var array
     */
    public $fonts = [];

    /**
     * @var string
     */
    public $prefix;

    /**
     * @var string
     */
    protected $template = 'font.html.php';

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-font';

    /**
     * @var string
     */
    public $type = 'poseidon-font-control';

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\font.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Get value
        $value = $this->value();

        // Vars
        $vars = [
            'title'       => $this->label,
            'description' => $this->description,
            'id'          => $this->id,
            'fonts'       => $this->fonts,
            'prefix'      => $this->prefix,
            'value'       => $value,
        ];

        require(self::view().S.$this->template);
    }

    /**
     * JSON
     */
    public function json() // phpcs:ignore
    {
        $json = parent::json();

        $json['fonts']  = $this->fonts;
        $json['prefix'] = $this->prefix;

        return $json;
    }

    /**
     * Retrieve formatted fonts
     *
     * @param  array   $fonts
     * @return array
     */
    protected function getFonts($fonts = [])
    {
        $all_fonts = [
            'system'      => [
                ''                => Translate::t('font.system.title', $this->textdomain),
                'system'          => Translate::t('font.system.system.title', $this->textdomain),
                'Arial'           => 'Arial',
                'Verdana'         => 'Verdana',
                'Trebuchet'       => 'Trebuchet',
                'Georgia'         => 'Georgia',
                'Times+New+Roman' => 'Times New Roman',
                'Palatino'        => 'Palatino',
                'Helvetica'       => 'Helvetica',
                'Myriad+Pro'      => 'Myriad Pro',
                'Lucida'          => 'Lucida',
                'Gill+Sans'       => 'Gill Sans',
                'Impact'          => 'Impact',
                'serif'           => 'serif',
                'monospace'       => 'monospace',
            ],
            /**
             * @see https://fonts.google.com/ 16 most popular
             */
            'googlefonts' => [
                ''                  => Translate::t('font.googlefonts.title', $this->textdomain),
                'Roboto'            => 'Roboto',
                'Open+Sans'         => 'Open Sans',
                'Noto+Sans+JP'      => 'Noto Sans JP',
                'Montserrat'        => 'Montserrat',
                'Lato'              => 'Lato',
                'Poppins'           => 'Poppins',
                'Source+Sans+Pro'   => 'Source Sans Pro',
                'Roboto+Condensed'  => 'Roboto Condensed',
                'Oswald'            => 'Oswald',
                'Roboto+Mono'       => 'Roboto Mono',
                'Raleway'           => 'Raleway',
                'Inter'             => 'Inter',
                'Noto+Sans'         => 'Noto Sans',
                'Ubuntu'            => 'Ubuntu',
                'Roboto+Slab'       => 'Roboto Slab',
                'Nunito+Sans'       => 'Nunito Sans',
                'Nunito'            => 'Nunito',
                'Playfair+Display'  => 'Playfair Display',
                'Merriweather'      => 'Merriweather',
                'PT+Sans'           => 'PT Sans',
                'Rubik'             => 'Rubik',
                'Mukta'             => 'Mukta',
                'Noto+Sans+KR'      => 'Noto Sans KR',
                'Work+Sans'         => 'Work Sans',
                'Kanit'             => 'Kanit',
                'Lora'              => 'Lora',
                'Fira+Sans'         => 'Fira Sans',
                'Nanum+Gothic'      => 'Nanum Gothic',
                'Noto+Sans+TC'      => 'Noto Sans TC',
                'Barlow'            => 'Barlow',
                'Quicksand'         => 'Quicksand',
                'Mulish'            => 'Mulish',
            ],
        ];

        if (empty($fonts)) {
            return $all_fonts;
        }

        $asked_fonts = [];

        foreach ($fonts as $font) {
            if (!array_key_exists($font, $all_fonts)) {
                continue;
            }

            $asked_fonts[$font] = $all_fonts[$font];
        }

        return $asked_fonts;
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        // Define fonts properly
        $this->fonts = !is_array($this->fonts) ? [$this->fonts] : $this->fonts;
        $this->fonts = $this->getFonts($this->fonts);

        // Define prefix used for CSS vars
        $this->prefix = empty($this->prefix) ? $this->default_prefix : (string) $this->prefix;
    }
}
