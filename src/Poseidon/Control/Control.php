<?php

namespace GetOlympus\Poseidon\Control;

use GetOlympus\Poseidon\Base\BaseTrait;
use GetOlympus\Poseidon\Control\ControlException;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Control controller
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
    public $available_displays = ['block', 'inline'];

    /**
     * @var array
     */
    public $available_dividers = ['none', 'bottom', 'top'];

    /**
     * @var array
     */
    public $css_var = [];

    /**
     * @var bool
     */
    public $devices = false;

    /**
     * @var string
     */
    public $display = 'block';

    /**
     * @var string
     */
    public $divider = '';

    /**
     * @var bool
     */
    protected static $register = false;

    /**
     * @var bool
     */
    public $revert = false;

    /**
     * @var array
     */
    public static $scripts = [];

    /**
     * @var array
     */
    public static $styles = [];

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
     * @param  WP_Customize_Manager $manager
     * @param  string               $id
     * @param  array                $args
     * @return void
     */
    public function __construct($manager, $id, $args = [])
    {
        parent::__construct($manager, $id, $args);

        // Update wrapper's options
        $this->devices = (bool) true === $this->devices;
        $this->display = in_array($this->display, $this->available_displays)
            ? $this->display
            : $this->available_displays[0];
        $this->divider = in_array($this->divider, $this->available_dividers)
            ? $this->divider
            : $this->available_dividers[0];
        $this->revert  = (bool) true === $this->revert;
    }

    /**
     * An Underscore (JS) template for this control's content (but not its container)
     *
     * @return void
     */
    public function content_template() // phpcs:ignore
    {}

    /**
     * Enqueue control related scripts/styles.
     *
     * @return void
     */
    public function enqueue()
    {
        // Check scripts
        if (!empty(static::$scripts)) {
            Helpers::enqueueFiles(static::$scripts, 'js', ['jquery']);
        }

        // Check styles
        if (!empty(static::$styles)) {
            Helpers::enqueueFiles(static::$styles, 'css', []);
        }
    }

    /**
     * Render the control's content
     *
     * @return void
     */
    public function render_content() // phpcs:ignore
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

        // All wrapper's attributes
        $attrs   = '';
        $wrapper = [
            'devices' => (int) (true === $this->devices),
            'display' => 1 === $this->devices ? 'block' : $this->display,
            'divider' => $this->divider,
            'revert'  => $this->revert,
        ];

        foreach ($wrapper as $key => $value) {
            $attrs .= sprintf(' data-%s="%s"', $key, $value);
        }

        // Render content
        echo sprintf('<li id="%s" class="%s"%s>', esc_attr($id), esc_attr($class), $attrs);
        $this->render_content();
        echo '</li>';
    }

    /**
     * Retrieve Control register status
     *
     * @throws ControlException
     *
     * @return bool
     */
    public static function register() : bool
    {
        // Get instance
        try {
            $control = self::getInstance();
        } catch (Exception $e) {
            throw new ControlException(Translate::t('control.errors.class_is_not_defined'));
        }

        return $control::$register;
    }

    /**
     * Get the settings options
     *
     * @return array
     */
    public static function settings() : array
    {
        return [
            '_configs' => [
                'transport' => 'refresh',
                'type'      => 'theme_mod',
            ],
            'default'  => 'sanitize_text_field',
        ];
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON
     *
     * @return void
     */
    public function to_json() // phpcs:ignore
    {
        parent::to_json();

        // Default value
        $this->json['default'] = is_string($this->setting)
            ? $this->setting
            : (isset($this->setting->default) ? $this->setting->default : '');

        if (isset($this->default)) {
            $this->json['default'] = $this->default;
        }

        // Default keys
        $this->json['id']      = $this->id;
        $this->json['choices'] = $this->choices;
        $this->json['link']    = $this->get_link();
        $this->json['value']   = $this->value();

        // Override value by default
        $this->json['value'] = is_null($this->json['value']) ? $this->json['default'] : $this->json['value'];

        // CSS var value
        if (!empty($this->css_var) && is_string($this->css_var)) {
            $this->json['css_var'] = '--' !== substr($this->css_var, 0, 2) ? '--'.$this->css_var : $this->css_var;
        } else if (!empty($this->css_var)) {
            $this->json['css_var'] = [];

            foreach ($this->css_var as $css_var) {
                $this->json['css_var'] = '--' !== substr($css_var, 0, 2) ? '--'.$css_var : $css_var;
            }
        }
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
            $control->textdomain => rtrim($class['root'], S).S.'languages'
        ];
    }

    /**
     * Fix value default
     *
     * @param  mixed   $value
     * @param  bool    $single
     * @param  string  $default
     * @return mixed
     */
    public static function valueCheck($value, $single = true, $default = '') : mixed
    {
        $single = (bool) true === $single;

        // Array case
        if (!$single) {
            $default = empty($default) ? [] : $default;

            $value = is_null($value) ? [] : $value;
            $value = empty($value) ? $default : $value;

            foreach ($default as $key => $val) {
                $value[$key] = isset($value[$key]) ? $value[$key] : $val;
            }

            return $value;
        }

        // Single case
        $value = is_null($value) ? '' : $value;
        $value = empty($value) ? $default : $value;

        return $value;
    }

    /**
     * Displays partial block
     *
     * @param  string  $block
     * @param  array   $vars
     *
     * @throws ControlException
     */
    public static function view($block, $vars) : void
    {
        // Get instance
        try {
            $control = self::getInstance();
        } catch (Exception $e) {
            throw new ControlException(Translate::t('control.errors.class_is_not_defined'));
        }

        // Get class details
        $class = $control->getClass();

        // Set block
        $block = in_array($block, ['header', 'body', 'footer', 'aside', 'script', 'style']) ? $block : 'aside';

        // Update vars depending on block
        if ('header' === $block) {
            $vars = array_merge([
                'devices' => $control->devices,
                'display' => $control->devices,
                'divider' => $control->divider,
                'revert'  => $control->revert,
            ], $vars);
        }

        // Display template
        require($class['resources'].S.'views'.S.'controls'.S.$block.'.html.php');
    }
}
