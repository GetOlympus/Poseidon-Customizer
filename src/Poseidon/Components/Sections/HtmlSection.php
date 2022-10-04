<?php

namespace GetOlympus\Poseidon\Components\Sections;

use GetOlympus\Poseidon\Section\Section;

/**
 * Builds Html section.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class HtmlSection extends Section
{
    /**
     * @var string
     */
    public $html = '';

    /**
     * @var string
     */
    public $type = 'poseidon-html-section';

    /**
     * Gather the parameters passed to client JavaScript via JSON.
     *
     * @return array
     */
    public function json()
    {
        $json = parent::json();

        $json['html'] = $this->html;

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
            'body' => '{{{ data.html }}}',
        ];

        require(self::view().S.$this->template);
    }
}
