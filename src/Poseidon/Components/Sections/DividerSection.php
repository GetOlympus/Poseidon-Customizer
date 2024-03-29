<?php

namespace GetOlympus\Poseidon\Components\Sections;

use GetOlympus\Poseidon\Section\Section;

/**
 * Builds Divider section.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class DividerSection extends Section
{
    /**
     * @var array
     */
    protected $available_margins = ['none', 'small', 'medium', 'large'];

    /**
     * @var string
     */
    public $margins = 'none';

    /**
     * @var string
     */
    public $type = 'poseidon-divider-section';

    /**
     * Gather the parameters passed to client JavaScript via JSON.
     *
     * @return array
     */
    public function json()
    {
        $json = parent::json();

        $json['margins'] = in_array($this->margins, $this->available_margins) ? $this->margins : 'none';
        $json['margins'] = 'none' === $json['margins'] ? '' : $json['margins'];

        return $json;
    }

    /**
     * An Underscore (JS) template for rendering this section.
     *
     * @see src\Poseidon\Resources\views\sections\_base.html.php
     * @return void
     */
    protected function render_template() // phpcs:ignore
    {
        self::view([
            'content' => '<hr class="poseidon-divider {{ data.margins }}" />',
        ]);
    }
}
