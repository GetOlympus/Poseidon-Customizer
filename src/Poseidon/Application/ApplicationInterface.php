<?php

namespace GetOlympus\Poseidon\Application;

/**
 * Application interface
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Application
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

interface ApplicationInterface
{
    /**
     * Register a new custom component.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $type
     *
     * @throws ApplicationException
     */
    public function addComponent($name, $path = '', $type = 'controls') : void;

    /**
     * Adds a new value of control.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws ApplicationException
     */
    public function addControl($identifier, $options) : void;

    /**
     * Adds a new value of panel.
     *
     * @param  string  $identifier
     * @param  array   $options
     * @param  string  $page_redirect
     *
     * @throws ApplicationException
     */
    public function addPanel($identifier, $options, $page_redirect = '') : void;

    /**
     * Adds a new value of partial.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws ApplicationException
     */
    public function addPartial($identifier, $options) : void;

    /**
     * Adds a new value of section.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws ApplicationException
     */
    public function addSection($identifier, $options) : void;
}
