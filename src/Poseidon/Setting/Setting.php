<?php

namespace GetOlympus\Poseidon\Setting;

use GetOlympus\Poseidon\Base\BaseTrait;
use GetOlympus\Poseidon\Setting\SettingException;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Setting controller
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Setting
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

if (!class_exists('WP_Customize_Setting')) {
    include_once ABSPATH.'wp-includes'.S.'class-wp-customize-setting.php';
}

abstract class Setting extends \WP_Customize_Setting
{
    use BaseTrait;

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-setting';

    /**
     * @var string
     */
    public $type = 'poseidon-setting';

    /**
     * Retrieve Setting translations
     *
     * @throws SettingException
     *
     * @return array
     */
    public static function translate() : array
    {
        // Get instance
        try {
            $setting = self::getInstance();
        } catch (Exception $e) {
            throw new SettingException(Translate::t('setting.errors.class_is_not_defined'));
        }

        // Set translations
        $class = $setting->getClass();

        return [
            $setting->textdomain => rtrim($class['root'], S).S.'languages'
        ];
    }
}
