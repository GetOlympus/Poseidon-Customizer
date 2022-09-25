<?php

namespace GetOlympus\Poseidon\Components;

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
    protected $template = 'toggle-section.html.php';

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
     * @see Poseidon-Customizer\src\Poseidon\Resources\views\toggle-section.html.php
     * @return void
     */
    protected function render_template() // phpcs:ignore
    {
        // Render template
        require(self::view().S.$this->template);
    }
}
