<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Title control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class TitleControl extends Control
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
    public $type = 'poseidon-title-control';

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        $heading = in_array($this->heading, $this->available_headings) ? $this->heading : 'h2';

        // View contents

        self::view('header', [
            'label' => sprintf(
                '<span class="%s">%s</span>',
                $heading,
                $this->label,
            ),
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);
    }
}
