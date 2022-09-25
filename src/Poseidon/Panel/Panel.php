<?php

namespace GetOlympus\Poseidon\Panel;

use GetOlympus\Poseidon\Base\BaseTrait;
use GetOlympus\Poseidon\Panel\PanelException;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Panel controller
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Panel
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

if (!class_exists('WP_Customize_Panel')) {
    include_once ABSPATH.'wp-includes'.S.'class-wp-customize-panel.php';
}

abstract class Panel extends \WP_Customize_Panel
{
    use BaseTrait;

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
    protected $textdomain = 'poseidonpanel';

    /**
     * @var string
     */
    public $type = 'poseidon-panel';

    /**
     * Enqueue scripts and styles.
     */
    public static function assets() : void
    {
        // Get instance
        try {
            $panel = self::getInstance();
        } catch (Exception $e) {
            throw new PanelException(Translate::t('panel.errors.class_is_not_defined'));
        }

        // Enqueue scripts and stylesheets
        Helpers::enqueueFiles(static::$scripts, 'js', ['jquery']);
        Helpers::enqueueFiles(static::$styles, 'css', []);
    }

    /**
     * Retrieve Panel translations
     *
     * @throws PanelException
     *
     * @return array
     */
    public static function translate() : array
    {
        // Get instance
        try {
            $panel = self::getInstance();
        } catch (Exception $e) {
            throw new PanelException(Translate::t('panel.errors.class_is_not_defined'));
        }

        // Set translations
        $class = $panel->getClass();

        return [
            $panel->textdomain => dirname(dirname($class['resources'])).S.'languages'
        ];
    }
}
