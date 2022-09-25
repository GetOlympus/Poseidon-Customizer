<?php

namespace GetOlympus\Poseidon\Builder;

/**
 * Builder model
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Builder
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class BuilderModel
{
    /**
     * @var array
     */
    protected $components = [];

    /**
     * @var array
     */
    protected $controls = [];

    /**
     * @var array
     */
    protected $panels = [];

    /**
     * @var array
     */
    protected $sections = [];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Gets the value of components.
     *
     * @param  string  $identifier
     *
     * @return array
     */
    public function getComponents($identifier = '') : array
    {
        if (!empty($identifier)) {
            return isset($this->components[$identifier]) ? (array) $this->components[$identifier] : [];
        }

        return $this->components;
    }

    /**
     * Sets the value of components.
     *
     * @param  string  $identifier
     * @param  array   $options
     */
    public function setComponents($identifier, $options) : void
    {
        $this->components[$identifier] = $options;
    }

    /**
     * Gets the value of controls.
     *
     * @param  string  $identifier
     *
     * @return array
     */
    public function getControls($identifier = '') : array
    {
        if (!empty($identifier)) {
            return isset($this->controls[$identifier]) ? $this->controls[$identifier] : [];
        }

        return $this->controls;
    }

    /**
     * Sets the value of controls.
     *
     * @param  string  $identifier
     * @param  array   $options
     */
    public function setControls($identifier, $options) : void
    {
        $this->controls[$identifier] = $options;
    }

    /**
     * Gets the value of panels.
     *
     * @param  string  $identifier
     *
     * @return array
     */
    public function getPanels($identifier = '') : array
    {
        if (!empty($identifier)) {
            return isset($this->panels[$identifier]) ? $this->panels[$identifier] : [];
        }

        return $this->panels;
    }

    /**
     * Sets the value of panels.
     *
     * @param  string  $identifier
     * @param  array   $options
     */
    public function setPanels($identifier, $options) : void
    {
        $this->panels[$identifier] = $options;
    }

    /**
     * Gets the value of sections.
     *
     * @param  string  $identifier
     *
     * @return array
     */
    public function getSections($identifier = '') : array
    {
        if (!empty($identifier)) {
            return isset($this->sections[$identifier]) ? $this->sections[$identifier] : [];
        }

        return $this->sections;
    }

    /**
     * Sets the value of sections.
     *
     * @param  string  $identifier
     * @param  array   $options
     */
    public function setSections($identifier, $options) : void
    {
        $this->sections[$identifier] = $options;
    }

    /**
     * Gets the value of settings.
     *
     * @param  string  $identifier
     *
     * @return array
     */
    public function getSettings($identifier = '') : array
    {
        if (!empty($identifier)) {
            return isset($this->settings[$identifier]) ? $this->settings[$identifier] : [];
        }

        return $this->settings;
    }

    /**
     * Sets the value of settings.
     *
     * @param  string  $identifier
     * @param  array   $options
     */
    public function setSettings($identifier, $options) : void
    {
        $this->settings[$identifier] = $options;
    }
}
