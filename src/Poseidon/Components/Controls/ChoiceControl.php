<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Choice control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ChoiceControl extends Control
{
    /**
     * @var array
     */
    protected $allowed_html = [
        'a'      => [
            'href'   => [],
            'title'  => [],
            'class'  => [],
            'style'  => [],
            'target' => [],
        ],
        'em'     => ['class' => [], 'style' => []],
        'i'      => ['class' => [], 'style' => []],
        'strong' => ['class' => [], 'style' => []],
        'u'      => ['class' => [], 'style' => []],
    ];

    /**
     * @var array
     */
    protected $available_modes = ['default', 'group', 'image'];

    /**
     * @var array
     */
    public $choices = [];

    /**
     * @var string
     */
    public $mode = '';

    /**
     * @var string
     */
    protected $template = 'choice.html.php';

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-choice';

    /**
     * @var string
     */
    public $type = 'poseidon-choice-control';

    /**
     * @var boolean
     */
    public $uniq = false;

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\choice.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $vars = [
            'title'       => $this->label,
            'description' => $this->description,
            'mode'        => $this->mode,
            'hidden'      => $this->uniq ? '' : sprintf(
                '<input type="hidden" name="%s" value="" />',
                $this->id
            ),
        ];

        $vals = $this->value();
        $vals = is_array($vals) ? $vals : [$vals];

        $type      = $this->uniq ? 'radio' : 'checkbox';
        $square    = $this->uniq ? '' : '[]';
        $separator = '-';

        $vars['choices'] = [];

        foreach ($this->choices as $value => $label) {
            if (array_key_exists($value, $vars['choices'])) {
                continue;
            }

            $vars['choices'][$value] = [
                'for'   => $this->id.$separator.$value,
                'label' => '',
                'field' => sprintf(
                    '<input type="%s" name="%s" id="%s" value="%s"%s />',
                    $type,
                    $this->id.$square,
                    $this->id.$separator.$value,
                    $value,
                    in_array($value, $vals) ? ' checked="checked"' : ''
                ),
            ];

            // Icon or Image
            if (is_array($label)) {
                $object = '';

                if (isset($label['icon'])) {
                    // Icon case
                    $vars['mode']    = 'group';
                    $vars['tooltip'] = true;

                    $object = sprintf(
                        '<i class="icon %s %s"></i>',
                        $this->getIconFamily($label['icon']),
                        !empty($label['icon']) ? $label['icon'] : 'dashicons-no-alt'
                    );
                } else if (isset($label['image'])) {
                    // Image case
                    $vars['mode']    = 'image';
                    $vars['tooltip'] = true;

                    $object = sprintf(
                        '<img src="%s" alt="" />',
                        !empty($label['image']) ? $label['image'] : ''
                    );
                }

                $vars['choices'][$value]['label'] = sprintf(
                    '%s<span class="tooltip">%s</span>',
                    $object,
                    isset($label['label']) ? wp_kses($label['label'], $this->allowed_html) : ''
                );

                continue;
            }

            // Default
            $vars['choices'][$value]['label'] = wp_kses($label, $this->allowed_html);
        }

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

        $json['choices']     = (array) $this->choices;
        $json['description'] = $this->description;
        $json['mode']        = $this->mode;
        $json['uniq']        = (bool) $this->uniq;

        return $json;
    }

    /**
     * Get icon's family name
     *
     * @param  string  $icon
     * @return string  $family
     */
    protected function getIconFamily($icon)
    {
        if (empty($icon)) {
            return '';
        }

        $families = [
            'dashicons',
            'pos-i-border',
        ];

        foreach ($families as $family) {
            if ($family !== substr($icon, 0, strlen($family))) {
                continue;
            }

            return $family;
        }

        return '';
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $default = $this->available_modes[0];

        $this->choices     = is_array($this->choices) ? $this->choices : [$this->choices];
        $this->description = wp_kses($this->description, $this->allowed_html);
        $this->mode        = in_array($this->mode, $this->available_modes) ? $this->mode : $default;
        $this->uniq        = (bool) $this->uniq;
    }
}
