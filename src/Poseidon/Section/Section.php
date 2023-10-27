<?php

namespace GetOlympus\Poseidon\Section;

use GetOlympus\Poseidon\Base\BaseTrait;
use GetOlympus\Poseidon\Section\SectionException;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Section controller
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Section
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

if (!class_exists('WP_Customize_Section')) {
    include_once ABSPATH.'wp-includes'.S.'class-wp-customize-section.php';
}

abstract class Section extends \WP_Customize_Section
{
    use BaseTrait;

    /**
     * @var array
     */
    protected $available_dividers = ['bottom', 'top'];

    /**
     * @var string
     */
    public $divider = '';

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
    protected $textdomain = 'poseidon-section';

    /**
     * @var string
     */
    public $type = 'poseidon-section';

    /**
     * Enqueue scripts and styles.
     */
    public static function assets() : void
    {
        // Get instance
        try {
            $section = self::getInstance();
        } catch (Exception $e) {
            throw new SectionException(Translate::t('section.errors.class_is_not_defined'));
        }

        // Enqueue scripts
        if (empty(static::$scripts)) {
            $type = $section->type;

            add_action('admin_print_footer_scripts', function () use ($type) {
                echo sprintf(
                    '<script type="text/javascript">(function($,api){%s})(jQuery,wp.customize);</script>',
                    sprintf(
                        'api.sectionConstructor[\'%s\']=api.Section.extend({%s,%s});',
                        $type,
                        'attachEvents: function(){}',
                        'isContextuallyActive: function(){return true;}',
                    ),
                );
            });
        }

        // Enqueue scripts and stylesheets
        Helpers::enqueueFiles(static::$scripts, 'js', ['jquery']);
        Helpers::enqueueFiles(static::$styles, 'css', []);
    }

    /**
     * Gather the parameters passed to client JavaScript via JSON.
     *
     * @return array
     */
    public function json()
    {
        $json = parent::json();

        // Override all other vars
        $keys = array_keys(get_object_vars($this));
        $excluded = [
            'active_callback',
            'available_dividers',
            'capability',
            'controls',
            'instance_number',
            'manager',
            'model',
            'theme_supports',
        ];

        foreach ($keys as $key) {
            if (in_array($key, $excluded) || isset($json[$key])) {
                continue;
            }

            $json[$key] = $this->$key;
        }

        // Set divider
        $json['divider'] = in_array($json['divider'], $this->available_dividers) ? $json['divider'] : '';

        return $json;
    }

    /**
     * Retrieve Section translations
     *
     * @throws SectionException
     *
     * @return array
     */
    public static function translate() : array
    {
        // Get instance
        try {
            $section = self::getInstance();
        } catch (Exception $e) {
            throw new SectionException(Translate::t('section.errors.class_is_not_defined'));
        }

        // Get class details
        $class = $section->getClass();

        return [
            $section->textdomain => rtrim($class['root'], S).S.'languages'
        ];
    }

    /**
     * Displays Section content
     *
     * @param  array   $vars
     *
     * @throws SectionException
     */
    public static function view($vars) : void
    {
        // Get instance
        try {
            $section = self::getInstance();
        } catch (Exception $e) {
            throw new SectionException(Translate::t('section.errors.class_is_not_defined'));
        }

        // Get class details
        $class = $section->getClass();

        // Update vars depending on block
        $vars = array_merge([
            'class'   => '{{ data.type }}',
            'content' => '',
            'divider' => '{{ data.divider }}',
            'id'      => '{{ data.id }}',
        ], $vars);

        // Display template
        require($class['resources'].S.'views'.S.'sections'.S.'body.html.php');
    }
}
