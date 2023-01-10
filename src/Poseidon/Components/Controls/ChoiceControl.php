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
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Works on values
        $values = $this->value();
        $values = is_array($values) ? $values : [$values];

        $choices   = [];
        $inputs    = '';
        $separator = '-';
        $square    = $this->uniq ? '' : '[]';
        $type      = $this->uniq ? 'radio' : 'checkbox';

        foreach ($this->choices as $value => $label) {
            if (array_key_exists($value, $choices)) {
                continue;
            }

            $choices[$value] = [
                'content' => '',
                'for'     => $this->id.$separator.$value,
                'input'   => sprintf(
                    '<input type="%s" name="%s" id="%s" value="%s"%s />',
                    $type,
                    $this->id.$square,
                    $this->id.$separator.$value,
                    $value,
                    in_array($value, $values) ? ' checked="checked"' : '',
                ),
                'tooltip' => false,
            ];

            if (is_array($label)) {
                $mode   = 'image';
                $object = '';

                // Icon mode
                if (isset($label['icon'])) {
                    $mode   = 'group';
                    $object = sprintf(
                        '<i class="icon %s %s"></i>',
                        $this->getIconFamily($label['icon']),
                        !empty($label['icon']) ? $label['icon'] : 'dashicons-no-alt',
                    );
                } else if (isset($label['svg'])) {
                    $mode   = 'group';
                    $object = !empty($label['svg']) ? $label['svg'] : '';
                } else {
                    $mode   = 'image';
                    $object = sprintf(
                        '<img src="%s" alt="" />',
                        !empty($label['image']) ? $label['image'] : '',
                    );
                }

                // Update choice component
                $choices[$value]['tooltip'] = true;
                $choices[$value]['content'] = sprintf(
                    '%s<span class="tooltip">%s</span>',
                    $object,
                    isset($label['label']) ? wp_kses($label['label'], $this->allowed_html) : '',
                );

                // Fore mode
                $this->mode = $mode;
            } else {
                $choices[$value]['content'] = wp_kses($label, $this->allowed_html);
            }

            $inputs .= sprintf(
                '<span class="customize-inside-control-row%s">%s<label for="%s">%s</label></span>',
                $choices[$value]['tooltip'] ? ' pos-c-tooltip' : '',
                $choices[$value]['input'],
                $choices[$value]['for'],
                $choices[$value]['content'],
            );
        }

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('aside', [
            'content' => $this->uniq ? '' : sprintf(
                '<input type="hidden" name="%s" value="" />',
                $this->id
            ),
        ]);

        self::view('body', [
            'id'      => $this->id,
            'class'   => ' pos-choice '.$this->mode,
            'content' => $inputs,
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);
    }

    /**
     * JSON
     */
    /*public function to_json() // phpcs:ignore
    {
        parent::to_json();

        $this->json['choices'] = (array) $this->choices;
        $this->json['mode']    = $this->mode;
        $this->json['uniq']    = (bool) $this->uniq;
    }*/

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
