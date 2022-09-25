<?php

namespace GetOlympus\Poseidon\Components;

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
     * @see Poseidon-Customizer\src\Poseidon\Resources\views\_base-section.html.php
     * @return void
     */
    protected function render_template() // phpcs:ignore
    {
        // Blocks
        $blocks = [
            'body' => <<<EOT
<# if (data.style == "button") { #>
    <button onclick="window.open('{{{ data.url }}}', '_blank'); return false;" title="{{{ data.alt }}}" class="button">
        {{{ data.icon_before }}}{{ data.title }}{{{ data.icon_after }}}
    </button>
<# } else { #>
    <a href="{{{ data.url }}}" title="{{{ data.alt }}}" target="_blank" class="button-link">
        {{{ data.icon_before }}}{{ data.title }}{{{ data.icon_after }}}
    </a>
<# } #>
EOT,
        ];

        require(self::view().S.$this->template);
    }
}
