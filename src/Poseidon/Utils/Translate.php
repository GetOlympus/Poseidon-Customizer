<?php

namespace GetOlympus\Poseidon\Utils;

use GetOlympus\Hermes\Hermes;

/**
 * Translate controller
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Utils
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class Translate extends Hermes
{
    /**
     * Load translations.
     *
     * @param  array   $translations
     * @param  string  $locale
     */
    public static function l($translations = [], $locale = 'default') : void // phpcs:ignore
    {
        parent::l($translations, $locale);
    }

    /**
     * Noop typo from WordPress.
     *
     * @param  string  $single
     * @param  string  $plural
     * @param  integer $number
     * @param  string  $domain
     *
     * @return string
     */
    public static function n($single, $plural, $number = 1, $domain = 'olympus-poseidon') : string // phpcs:ignore
    {
        return parent::n($single, $plural, $number, $domain);
    }

    /**
     * Prepare noop typo from WordPress.
     *
     * @param  string  $single
     * @param  string  $plural
     * @param  string  $domain
     *
     * @return string
     */
    public static function noop($single, $plural, $domain = 'olympus-poseidon') : string // phpcs:ignore
    {
        return parent::noop($single, $plural, $domain);
    }

    /**
     * Translate typo.
     *
     * @param  string  $message
     * @param  string  $domain
     *
     * @return string
     */
    public static function t($message, $domain = 'olympus-poseidon') : string // phpcs:ignore
    {
        return parent::t($message, $domain);
    }
}
