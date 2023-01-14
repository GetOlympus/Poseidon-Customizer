<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;

/**
 * Builds Number control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class NumberControl extends Control
{
    /**
     * @var string
     */
    public $display = 'inline';

    /**
     * @var integer
     */
    public $max = 100;

    /**
     * @var integer
     */
    public $min = 0;

    /**
     * @var integer
     */
    public $step = 1;

    /**
     * @var string
     */
    public $type = 'poseidon-number-control';

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        $value = (int) $this->value();
        $value = is_null($value) || $this->min > $value ? $this->min : ($value > $this->max ? $this->max : $value);

        // View contents

        self::view('header', [
            'label' => $this->label,
        ]);

        self::view('body', [
            'id'      => $this->id,
            'class'   => 'pos-number',
            'content' => sprintf(
                '%s<input type="number" name="%s" value="%s" min="%s" max="%s" step="%s" />%s',
                '<button class="minus">-</button>',
                $this->id,
                $value,
                $this->min,
                $this->max,
                $this->step,
                '<button class="plus">+</button>',
            ),
        ]);

        self::view('footer', [
            'content' => $this->description,
        ]);

        self::view('script', [
            'content' => sprintf(
                '
(function ($) {
    const _id = "%s";

    $("#" + _id).poseidonNumber({
        input: "input[type=\'number\']",
        minus: "button.minus",
        plus: "button.plus",
    });
})(window.jQuery);
                ',
                $this->id
            ),
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

        $this->json['min']  = (int) $this->min;
        $this->json['max']  = (int) $this->max;
        $this->json['step'] = (int) $this->step;
    }*/

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $this->min = (int) $this->min;
        $this->max = (int) $this->max;

        // Fix min and max properties
        if ($this->min > $this->max) {
            $temp = $this->max;
            $this->max = $this->min;
            $this->min = $temp;
            unset($temp);
        }

        $this->step = (int) $this->step;
        $this->step = 1 > $this->step ? 1 : $this->step;
    }
}
