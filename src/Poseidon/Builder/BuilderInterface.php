<?php

namespace GetOlympus\Poseidon\Builder;

/**
 * Builder interface
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Builder
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

interface BuilderInterface
{
    /**
     * Register a new custom component.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $type
     *
     * @throws BuilderException
     */
    public function addComponent($name, $path = '', $type = 'controls') : void;

    /**
     * Adds a new value of control.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws BuilderException
     */
    public function addControl($identifier, $options) : void;

    /**
     * Adds a new value of panel.
     *
     * @param  string  $identifier
     * @param  array   $options
     * @param  string  $page_redirect
     *
     * @throws BuilderException
     */
    public function addPanel($identifier, $options, $page_redirect = '') : void;

    /**
     * Adds a new value of section.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws BuilderException
     */
    public function addSection($identifier, $options) : void;

    /**
     * Adds a new value of setting.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws BuilderException
     */
    public function addSetting($identifier, $options) : void;
}
