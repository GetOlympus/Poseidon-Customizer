<?php

namespace GetOlympus\Poseidon\Components\Sections;

use GetOlympus\Poseidon\Section\Section;

/**
 * Builds Link section.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class LinkSection extends Section
{
    /**
     * @var array
     */
    protected $available_styles = ['link', 'button'];

    /**
     * @var string
     */
    public $icon = '';

    /**
     * @var string
     */
    public $position = 'after';

    /**
     * @var string
     */
    public $style = 'link';

    /**
     * @var string
     */
    public $type = 'poseidon-link-section';

    /**
     * @var string
     */
    public $url = '';

    /**
     * Gather the parameters passed to client JavaScript via JSON.
     *
     * @return array
     */
    public function json()
    {
        $json = parent::json();

        $json['alt']   = esc_textarea($this->title);
        $json['style'] = in_array($this->style, $this->available_styles) ? $this->style : 'link';
        $json['url']   = esc_url($this->url);

        if (!empty($this->icon) && 'before' === $this->position) {
            $json['icon_after']  = '';
            $json['icon_before'] = '<i class="icon before '.$this->icon.'"></i> ';
        } else if (!empty($this->icon) && 'after' === $this->position) {
            $json['icon_after']  = '<i class="icon after '.$this->icon.'"></i>';
            $json['icon_before'] = '';
        }

        return $json;
    }

    /**
     * An Underscore (JS) template for rendering this section.
     *
     * @return void
     */
    protected function render_template() // phpcs:ignore
    {
        self::view([
            'content' => sprintf(
                '<# if (data.style == "button") { #>%s<# } else { #>%s<# } #>',
                sprintf(
                    '<button onclick="%s" title="%s" class="button">%s</button>',
                    'window.open(\'{{{ data.url }}}\', \'_blank\'); return false;',
                    '{{{ data.alt }}}',
                    '{{{ data.icon_before }}}{{ data.title }}{{{ data.icon_after }}}',
                ),
                sprintf(
                    '<a href="%s" title="%s" target="_blank" class="button-link">%s</a>',
                    '{{{ data.url }}}',
                    '{{{ data.alt }}}',
                    '{{{ data.icon_before }}}{{ data.title }}{{{ data.icon_after }}}',
                ),
            ),
        ]);
    }
}
