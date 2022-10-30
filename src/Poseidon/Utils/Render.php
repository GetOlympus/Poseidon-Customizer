<?php

namespace GetOlympus\Poseidon\Utils;

use GetOlympus\Hera\Hera;

/**
 * Render HTML entities.
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Utils
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class Render extends Hera
{
    /**
     * @var string
     */
    protected $distpath = OL_POSEIDON_DISTPATH.'/';

    /**
     * @var array
     */
    protected $paths = [
        'poseidon' => OL_POSEIDON_PATH.'src'.S.'Poseidon'.S.'Resources'.S.'views',
    ];

    /**
     * @var array
     */
    protected $styles = [];

    /**
     * @var string
     */
    protected $uri = OL_POSEIDON_URI;

    /**
     * @var bool
     */
    protected $usecache = OL_POSEIDON_ISDEBUG;
}
