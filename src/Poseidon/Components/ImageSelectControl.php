<?php

namespace GetOlympus\Poseidon\Components;

use GetOlympus\Poseidon\Control\Control;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builds Image Select control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ImageSelectControl extends Control
{
    /**
     * @var integer
     */
    public $column = 1;

    /**
     * @var boolean
     */
    public $multiple = false;

    /**
     * @var string
     */
    protected $template = 'image-select-control.html.php';

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-image-select';

    /**
     * @var string
     */
    public $type = 'poseidon-image-select-control';

    /**
     * An Underscore (JS) template for this control's content (but not its container).
     */
    protected function content_template() // phpcs:ignore
    {
        require(self::view().S.$this->template);
    }

    /**
     * JSON
     */
    public function json() // phpcs:ignore
    {
        $json  = parent::json();
        $value = esc_attr($this->value());

        $json['no_options'] = Translate::t('imageselect.errors.no_options', $this->textdomain);
        $json['column']     = 2 === $this->column ? 2 : 1;
        $json['multiple']   = $this->multiple;
        $json['value']      = !is_array($value) ? [$value] : $value;

        return $json;
    }
}
