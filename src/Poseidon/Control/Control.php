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
    public $css_var = [];

    /**
     * @var bool
     */
    protected static $register = false;

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
    protected $textdomain = 'poseidon-control';

    /**
     * @var string
     */
    public $type = 'poseidon-control';

    /**
     * @var array
     */
    public $wrapper = [
        'devices' => false,
        'display' => 'block',
        'divider' => '',
        'revert'  => false,
    ];

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
    public function content_template() // phpcs:ignore
    {}

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

        // Works on wrapper attributes
        $this->wrapper['devices'] = (int) (true === $this->wrapper['devices']);

        $this->wrapper['display'] = 1 === $this->wrapper['devices'] ? 'block' : (
            in_array($this->wrapper['display'], ['block', 'inline']) ? $this->wrapper['display'] : 'block'
        );

        $this->wrapper['divider'] = in_array($this->wrapper['divider'], ['bottom', 'top'])
            ? $this->wrapper['divider']
            : 'none';

        // All wrapper attributes
        $attrs = '';

        foreach ($this->wrapper as $key => $value) {
            $attrs .= sprintf(' data-%s="%s"', $key, $value);
        }

        // Render content
        echo sprintf('<li id="%s" class="%s"%s>', esc_attr($id), esc_attr($class), $attrs);
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
            $this->json['css_var'] = $this->css_var;
        } else if (!empty($this->css_var)) {
            $this->json['css_var'] = [];

            foreach ($this->css_var as $css_var) {
                $this->json['css_var'] = '--' !== substr($css_var, 0, 2) ? '--'.$css_var : $css_var;
            }
        }
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
            $vars = array_merge($control->wrapper, $vars);
        }

        // Display template
        require($class['resources'].S.'views'.S.'controls'.S.'_'.$block.'.html.php');
    }
}
