<?php

namespace GetOlympus\Poseidon\Utils;

/**
 * Synchronizer controller
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Utils
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class Synchronizer
{
    /**
     * @var Singleton
     */
    private static $instance;

    /**
     * Get singleton.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
