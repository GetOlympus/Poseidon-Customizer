<?php

namespace GetOlympus\Poseidon\Components\Sections;

use GetOlympus\Poseidon\Section\Section;

/**
 * Builds Title section.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class TitleSection extends Section
{
    /**
     * @var string
     */
    protected $available_headings = ['h2', 'h3', 'h4', 'h5', 'h6'];

    /**
     * @var string
     */
    public $heading = 'h2';

    /**
     * @var string
     */
    public $type = 'poseidon-title-section';

    /**
     * Gather the parameters passed to client JavaScript via JSON.
     *
     * @return array
     */
    public function json()
    {
        $json = parent::json();

        $json['heading']     = in_array($this->heading, $this->available_headings) ? $this->heading : 'h2';
        $json['title']       = $this->title;
        $json['description'] = $this->description;

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
            'content' => '<span class="pos-s-header {{ data.heading }}">{{ data.title }}</span>',
        ]);
    }
}
