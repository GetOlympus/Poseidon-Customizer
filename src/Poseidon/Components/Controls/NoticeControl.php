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
    protected $template = 'notice.html.php';

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
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Vars
        $vars = [
            'status'      => $this->status,
            'icon'        => $this->available_icons[$this->status],
            'title'       => $this->label,
            'description' => $this->description,
        ];

        require(self::view().S.$this->template);
    }

    /**
     * JSON
     */
    public function json() // phpcs:ignore
    {
        $json = parent::json();

        // Set variables from defaults
        $this->setVariables();

        $json['description'] = $this->description;
        $json['icon']        = $this->available_icons[$this->status];
        $json['status']      = $this->status;

        return $json;
    }

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
