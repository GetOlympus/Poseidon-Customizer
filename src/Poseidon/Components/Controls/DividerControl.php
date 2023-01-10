<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Divider control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class DividerControl extends Control
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
    public $type = 'poseidon-divider-control';

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->margins = in_array($this->margins, $this->available_margins)
            ? $this->margins
            : $this->available_margins[0];

        // View contents

        self::view('aside', [
            'content' => sprintf(
                '<hr class="poseidon-divider %s" />',
                $this->margins,
            ),
        ]);
    }

    /**
     * JSON
     */
    /*public function to_json() // phpcs:ignore
    {
        parent::to_json();
        $this->json['margins'] = 'none' === $this->margins ? '' : $this->margins;
    }*/
}
