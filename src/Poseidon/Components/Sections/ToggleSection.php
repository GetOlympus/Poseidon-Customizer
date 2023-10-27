<?php

namespace GetOlympus\Poseidon\Components\Sections;

use GetOlympus\Poseidon\Section\Section;

/**
 * Builds Toggle section.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ToggleSection extends Section
{
    /**
     * @var array
     */
    public static $scripts = [
        OL_POSEIDON_ASSETSPATH.'js'.S.'toggle-section.js',
    ];

    /**
     * @var boolean
     */
    public $switch = false;

    /**
     * @var string
     */
    public $type = 'poseidon-toggle-section';

    /**
     * Gather the parameters passed to client JavaScript via JSON.
     *
     * @return array
     */
    public function json()
    {
        $json = parent::json();

        $json['switch'] = (bool) $this->switch;

        return $json;
    }

    /**
     * An Underscore (JS) template for rendering this section.
     *
     * @return void
     */
    protected function render_template() // phpcs:ignore
    {
        $texts = [
            'title' => __('Press return or enter to open this section'),
            'back'  => __('Back'),
            'help'  => __('Help'),
        ];

        self::view([
            'class'   => sprintf(
                '%s pos-s-switch%s',
                '{{ data.type }}',
                '<# if (data.switch == 1) { #> active<# } #>',
            ),
            'content' => sprintf(
                '%s%s<h3 class="%s" tabindex="0">%s</h3><ul class="%s">%s</ul>',
                // input
                sprintf(
                    '<input type="checkbox" name="%s" id="%s" value="%s" class="%s"%s />',
                    '{{ data.id }}',
                    'accordion-section-toggle-{{ data.id }}',
                    '{{ data.switch ? "on" : "off" }}',
                    'pos-toggle-checkbox',
                    '<# if (data.switch == 1) { #> checked="checked"<# } #>',
                ),
                // label
                sprintf(
                    '<label for="%s" class="%s">%s</label>',
                    'accordion-section-toggle-{{ data.id }}',
                    'pos-toggle',
                    '<span></span>',
                ),
                // title
                'accordion-section-title',
                sprintf(
                    '%s<span class="screen-reader-text">%s</span>',
                    '{{ data.title }}',
                    $texts['title'],
                ),
                // list
                'accordion-section-content',
                sprintf(
                    '<li class="customize-section-description-container section-meta%s">%s%s</li>',
                    '<# if (data.description_hidden) { #> customize-info<# } #>',
                    // content
                    sprintf(
                        '<div class="%s">%s%s%s%s</div>',
                        'customize-section-title',
                        // button
                        sprintf(
                            '<button class="%s" tabindex="-1"><span class="%s">%s</span>',
                            'customize-section-back',
                            'screen-reader-text',
                            $texts['back'],
                        ),
                        // title
                        sprintf(
                            '<h3><span class="%s">%s</span>%s</h3>',
                            'customize-action',
                            '{{{ data.customizeAction }}}',
                            '{{ data.title }}',
                        ),
                        // description
                        sprintf(
                            '<# if (%s) { #><button type="%s" class="%s" aria-expanded="false">%s</button>%s<# } #>',
                            'data.description && data.description_hidden',
                            'button',
                            'customize-help-toggle dashicons dashicons-editor-help',
                            sprintf(
                                '<span class="screen-reader-text">%s</span>',
                                $texts['help'],
                            ),
                            sprintf(
                                '<div class="description customize-section-description">%s</div>',
                                '{{{ data.description }}}',
                            )
                        ),
                        // notification
                        '<div class="customize-control-notifications-container"></div>',
                    ),
                    // description
                    sprintf(
                        '<# if (data.description && ! data.description_hidden) { #>%s<# } #>',
                        '<div class="description customize-section-description">{{{ data.description }}}</div>',
                    ),
                ),
            ),
        ]);
    }
}
