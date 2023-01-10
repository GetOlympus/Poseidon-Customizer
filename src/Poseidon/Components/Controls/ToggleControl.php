<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Toggle control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ToggleControl extends Control
{
    /**
     * @var string
     */
    public $type = 'poseidon-toggle-control';

    /**
     * Constructor
     *
     * @param  WP_Customize_Manager $manager
     * @param  string               $id
     * @param  array                $args
     * @return void
     */
    public function __construct($manager, $id, $args = [])
    {
        parent::__construct($manager, $id, $args);

        // Update wrapper
        $this->wrapper['display'] = 'inline';
    }

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Vars
        $vars = [
            'description' => $this->description,
            'title'       => $this->label,
        ];

        $value = $this->value();

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'content' => sprintf(
                '<input type="checkbox" name="%s" id="%s" value="%s" %s%s%s /><label for="%s" class="%s">%s</label>',
                $this->id,
                $this->id,
                $value,
                $this->get_link(),
                ' class="pos-toggle-checkbox"',
                $value ? ' checked="checked"' : '',
                $this->id,
                'pos-toggle',
                '<span></span>'
            ),
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);
    }
}
