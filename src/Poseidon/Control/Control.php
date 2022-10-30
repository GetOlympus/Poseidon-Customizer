<?php

namespace GetOlympus\Poseidon\Control;

use GetOlympus\Poseidon\Base\BaseTrait;
use GetOlympus\Poseidon\Control\ControlException;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Abstract class to define all Control context with authorized controls, how to
 * write some functions and every usefull checks
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Control
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

if (!class_exists('WP_Customize_Control')) {
    include_once ABSPATH.'wp-includes'.S.'class-wp-customize-control.php';
}

abstract class Control extends \WP_Customize_Control
{
    use BaseTrait;

    /**
     * @var array
     */
    protected $available_displays = ['block', 'inline'];

    /**
     * @var array
     */
    protected $available_dividers = ['bottom', 'top'];

    /**
     * @var string
     */
    public $display = 'block';

    /**
     * @var string
     */
    public $divider = '';

    /**
     * @var boolean
     */
    protected $responsive = false;

    /**
     * @var array
     */
    protected static $scripts = [];

    /**
     * @var array
     */
    protected static $styles = [];

    /**
     * @var string
     */
    protected $template = '_base.html.php';

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-control';

    /**
     * @var string
     */
    public $type = 'poseidon-control';

    /**
     * Constructor
     *
     * @param $manager
     * @param $id
     * @param $args
     */
    public function __construct($manager, $id, $args = []) // phpcs:ignore
    {
        parent::__construct($manager, $id, $args);
    }

    /**
     * Enqueue scripts and styles
     *
     * @throws ControlException
     */
    public static function assets() : void
    {
        // Get instance
        try {
            $control = self::getInstance();
        } catch (Exception $e) {
            throw new ControlException(Translate::t('control.errors.class_is_not_defined'));
        }

        // Enqueue scripts and stylesheets
        Helpers::enqueueFiles(static::$scripts, 'js', ['jquery']);
        Helpers::enqueueFiles(static::$styles, 'css', []);
    }

    /**
     * An Underscore (JS) template for this control's content (but not its container)
     *
     * @return void
     */
    protected function content_template() // phpcs:ignore
    {}

    /**
     * Render the control's content
     *
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {}

    /**
     * Renders the control wrapper and calls $this->render_content() for the internals.
     *
     * @return void
     */
    protected function render()
    {
        // Main ID & class
        $id    = str_replace(['[', ']'], ['-', ''], 'customize-control-'.$this->id);
        $class = 'customize-control poseidon-control '.$this->type.' pos-c-wrap';

        // Display & divider
        $attrs  = ' data-display="'.$this->display.'"';
        $attrs .= !empty($this->divider) ? ' data-divider="'.$this->divider.'"' : '';

        echo sprintf(
            '<li id="%s" class="%s"%s>',
            esc_attr($id),
            esc_attr($class),
            $attrs
        );
        $this->render_content();
        echo '</li>';
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON
     *
     * @return void
     */
    public function to_json() // phpcs:ignore
    {
        parent::to_json();

        // Set vars into JSON
        $this->json['default'] = is_string($this->setting) ? $this->setting : $this->setting->default;

        if (isset($this->default)) {
            $this->json['default'] = $this->default;
        }

        $this->json['id']    = $this->id;
        $this->json['link']  = $this->get_link();
        $this->json['value'] = $this->value();

        // Override all other vars
        $keys = array_keys(get_object_vars($this));
        $excluded = [
            'active_callback',
            'available_displays',
            'available_dividers',
            'json',
            'manager',
            'model',
            'template',
        ];

        foreach ($keys as $key) {
            if (in_array($key, $excluded)) {
                continue;
            }

            $this->json[$key] = $this->$key;
        }

        // Set responsive, display & divider
        $this->json['responsive'] = true === $this->json['responsive'];
        $this->json['display']    = $this->json['responsive'] ? 'block' : (
            in_array($this->json['display'], $this->available_displays) ? $this->json['display'] : 'block'
        );
        $this->json['divider']    = in_array($this->json['divider'], $this->available_dividers)
            ? $this->json['divider']
            : '';
    }

    /**
     * Retrieve Control translations
     *
     * @throws ControlException
     *
     * @return array
     */
    public static function translate() : array
    {
        // Get instance
        try {
            $control = self::getInstance();
        } catch (Exception $e) {
            throw new ControlException(Translate::t('control.errors.class_is_not_defined'));
        }

        // Get class details
        $class = $control->getClass();

        return [
            $control->textdomain => dirname(dirname($class['resources'])).S.'languages'
        ];
    }

    /**
     * Retrieve Control view template
     *
     * @throws ControlException
     *
     * @return string
     */
    public static function view() : string
    {
        // Get instance
        try {
            $control = self::getInstance();
        } catch (Exception $e) {
            throw new ControlException(Translate::t('control.errors.class_is_not_defined'));
        }

        // Get class details
        $class = $control->getClass();

        return $class['resources'].S.'views'.S.'controls';
    }
}
