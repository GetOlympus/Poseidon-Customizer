<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Notice control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class NoticeControl extends Control
{
    /**
     * @var array
     */
    protected $allowed_html = [
        'a'      => [
            'href'   => [],
            'title'  => [],
            'class'  => [],
            'target' => [],
        ],
        'br'     => ['class' => []],
        'code'   => ['class' => []],
        'em'     => ['class' => []],
        'i'      => ['class' => []],
        'li'     => ['class' => []],
        'ol'     => ['class' => []],
        'span'   => ['class' => []],
        'strong' => ['class' => []],
        'u'      => ['class' => []],
        'ul'     => ['class' => []],
    ];

    /**
     * @var array
     */
    protected $available_icons = [
        'none'    => '',
        'info'    => 'dashicons-info-outline',
        'success' => 'dashicons-yes',
        'warning' => 'dashicons-warning',
        'danger'  => 'dashicons-dismiss',
    ];

    /**
     * @var string
     */
    public $status = 'none';

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-notice';

    /**
     * @var string
     */
    public $type = 'poseidon-notice-control';

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $icon = $this->available_icons[$this->status];
        $icon = empty($vars['icon']) ? '' : sprintf(
            '<i class="icon dashicons %s"></i>',
            $icon,
        );

        self::view('header', [
            'class' => $this->status,
            'icon'  => $icon,
            'label' => $this->label,
        ]);

        self::view('footer', [
            'class'   => $this->status,
            'content' => $this->description,
        ]);
    }

    /**
     * JSON
     */
    /*public function to_json() // phpcs:ignore
    {
        parent::to_json();

        // Set variables from defaults
        $this->setVariables();

        $this->json['icon']   = $this->available_icons[$this->status];
        $this->json['status'] = $this->status;
    }*/

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $this->description = wp_kses($this->description, $this->allowed_html);
        $this->status      = array_key_exists($this->status, $this->available_icons)
            ? $this->status
            : array_key_first($this->available_icons);
    }
}
