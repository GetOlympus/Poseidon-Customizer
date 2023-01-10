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
     * @var string
     */
    public $css_var = '--poseidon-font';

    /**
     * @var array
     * @see https://fonts.google.com/ 16 most popular
     * @see https://danmarshall.github.io/google-font-to-svg-path/
     * @see https://fontjoy.com/
     */
    protected $display_fonts = [];

    /**
     * @var array
     */
    public $fonts = ['system', 'googlefonts'];

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
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $opts  = '<option value="">-</option>';
        $sheet = '';
        $typos = '';
        $value = $this->value();

        foreach ($this->display_fonts as $group => $fonts) {
            $opts .= sprintf(
                '<optgroup label="%s" data-type="%s" data-url="%s">',
                array_values($fonts)[0],
                $group,
                $this->getStylesheetFromFont($group),
            );

            foreach ($fonts as $font => $label) {
                if (empty($font)) {
                    continue;
                }

                $sheet = $font === $value ? $group : $sheet;

                $opts .= sprintf(
                    '<option value="%s"%s>%s</option>',
                    $font,
                    $font === $value ? ' selected' : '',
                    $label
                );
            }

            $opts .= '</optgroup>';
        }

        $sheet = empty($sheet) ? '' : sprintf(
            '<link href="%s" rel="stylesheet"/>',
            str_replace('_FAMILY_', $value, $sheet),
        );

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'content' => sprintf(
                '<input %s /><select %s>%s</select>%s',
                // input
                sprintf(
                    'type="hidden" name="%s[prefix]" value="%s"',
                    $this->id,
                    $this->css_var,
                ),
                // select
                sprintf(
                    'name="%s[font]"',
                    $this->id,
                ),
                $opts,
                // html tags
                sprintf(
                    '<p style="font-family: var(%s);">%s</p>%s',
                    $this->css_var,
                    Translate::t('font.example', $this->textdomain),
                    $sheet,
                ),
            ),
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);

        self::view('style', [
            'id'       => $this->id,
            'property' => $this->css_var,
            'value'    => $value,
        ]);

        self::view('script', [
            'content' => sprintf(
                '
(function ($) {
    const _id      = "%s",
        $container = $("#" + _id),
        $hidden    = $container.find("input"),
        $select    = $container.find("select"),
        $style     = $("#style-" + _id);

    // select change event
    $select.on("change", function (e) {
        const $selected = $select.find(":selected"),
            _type  = $selected.parent("optgroup").attr("data-type"),
            _sheet = $selected.parent("optgroup").attr("data-url"),
            $link  = $container.find("link");

        let _text  = $selected.text(),
            _value = $selected.val();

        if ($link.length) {
            $link.remove();
        }

        if (_text === "") {
            return;
        }

        _value = \'\' == _value ? \'-apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif\' : _value;

        if (\'\' !== _sheet) {
            $container.append(\'<link href="\' + _sheet.replace(\'_FAMILY_\', _value) + \'" rel="stylesheet" />\');
        }

        _text = _text.indexOf(" ") > -1 ? "\'" + _text + "\'" : _text;
        $style.html(":root{" + $hidden.prop("value") + ":" + _text + "}");
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

        $this->json['fonts']   = $this->fonts;
        $this->json['css_var'] = $this->css_var;
    }*/

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
     * Retrieve stylesheet url from selected font
     *
     * @param  string  $font
     * @return string
     */
    protected function getStylesheetFromFont($font = '')
    {
        $all_urls = [
            'system'      => '',
            'googlefonts' => 'https://fonts.googleapis.com/css2?family=_FAMILY_&display=swap',
        ];

        if (empty($font) || !array_key_exists($font, $all_urls)) {
            return '';
        }

        return $all_urls[$font];
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        // Define fonts properly
        $this->fonts         = !is_array($this->fonts) ? [$this->fonts] : $this->fonts;
        $this->display_fonts = $this->getFonts($this->fonts);

        // Define CSS var
        $this->css_var = empty($this->css_var) ? '--poseidon-font' : (string) $this->css_var;
    }
}
