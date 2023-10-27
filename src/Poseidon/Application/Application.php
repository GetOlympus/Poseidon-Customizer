<?php

namespace GetOlympus\Poseidon\Application;

use GetOlympus\Poseidon\Base\BaseTrait;
use GetOlympus\Poseidon\Application\ApplicationException;
use GetOlympus\Poseidon\Application\ApplicationHook;
use GetOlympus\Poseidon\Application\ApplicationInterface;
use GetOlympus\Poseidon\Application\ApplicationModel;
use GetOlympus\Poseidon\Application\ApplicationUtils;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Sanitizer;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Application controller
 *
 * @package    OlympusPoseidonApplication
 * @subpackage Application
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

abstract class Application implements ApplicationInterface
{
    use BaseTrait;

    /**
     * @var array
     */
    protected $assets_pane = [];

    /**
     * @var array
     */
    protected $assets_previewer = [];

    /**
     * @var array
     */
    protected $available_types = [
        'default' => [
            'text', 'email', 'url', 'number', 'range', 'hidden', 'date',
            'textarea', 'checkbox', 'dropdown-pages', 'radio', 'select',
        ],
        'special' => [
            'background_position', 'background-position', 'code_editor', 'code-editor', 'color',
            'cropped_image', 'cropped-image', 'date_time', 'date-time', 'editor', 'image', 'media',
            'nav_menu_auto_add', 'nav-menu-auto-add', 'nav_menu', 'nav-menu', 'nav_menu_item',
            'nav-menu-item', 'nav_menu_location', 'nav-menu-location', 'nav_menu_locations',
            'nav-menu-locations', 'nav_menu_name', 'nav-menu-name', 'theme', 'sidebar_widgets',
            'sidebar-widgets', 'widget_form', 'widget-form',
        ],
        'settings' => [
            'text/css',
        ],
    ];

    /**
     * @var array
     */
    protected $custom_components = [];

    /**
     * @var array
     */
    protected $default_components = ['controls', 'panels', 'sections', 'settings'];

    /**
     * @var array
     */
    protected $default_transports = ['refresh', 'postMessage'];

    /**
     * @var array
     */
    protected $default_types = ['option', 'theme_mod'];

    /**
     * @var string
     */
    protected $locale = 'default';

    /**
     * @var array
     */
    protected $mime_types = ['image', 'audio', 'video', 'application', 'text'];

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * Constructor.
     *
     * @param  array   $components
     */
    public function __construct($components = [])
    {
        // Initialize ApplicationModel
        $this->model = new ApplicationModel();

        // Translate and make all works
        $this->translate();
        $this->setVars();
        $this->register();
    }

    /**
     * Register a new custom component.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $type
     * @param  boolean $is_wordpress
     *
     * @throws ApplicationException
     */
    public function addComponent($name, $path = '', $type = 'controls', $is_wordpress = false) : void
    {
        // Check name
        if (empty($name)) {
            throw new ApplicationException(Translate::t('application.errors.component_name_is_empty'));
        }

        // Check type
        if (!in_array($type, $this->default_components)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.component_type_is_unknown'),
                $type,
                implode('</code>, <code>', $this->default_components)
            ));
        }

        $file = '';

        // Get component to know if identifier is already used or not
        $component = $this->getModel()->getComponents($name);

        // Check component
        if (!empty($component)) {
            if (!empty($path)) {
                throw new ApplicationException(sprintf(
                    Translate::t('application.errors.component_name_is_already_used'),
                    $name
                ));
            }

            return;
        }

        // Check path
        if (!empty($path)) {
            // Set real file path
            $file = realpath($path);

            if (!file_exists($file)) {
                throw new ApplicationException(sprintf(
                    Translate::t('application.errors.component_path_does_not_exist'),
                    $name
                ));
            }
        }

        /**
         * Filter the component name.
         *
         * @param  string  $name
         *
         * @return string
         */
        $name = apply_filters('ol.poseidon.application_component_name', $name);

        // Add component
        $this->getModel()->setComponents($name, [
            'file'      => $file,
            'type'      => $type,
            'wordpress' => $is_wordpress,
        ]);
    }

    /**
     * Adds a new value of control.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws ApplicationException
     */
    public function addControl($identifier, $options) : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new ApplicationException(Translate::t('application.errors.control_identifier_is_empty'));
        }

        // Check section option
        if (!isset($options['section']) || empty($options['section'])) {
            throw new ApplicationException(Translate::t('application.errors.control_section_is_required'));
        }

        // Get control to know if identifier is already used or not
        $control = $this->getModel()->getControls($identifier);

        if (!empty($control)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.control_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'label'           => Translate::t('application.labels.control_title'),
            'description'     => '',
            'active_callback' => '',
            'allow_addition'  => false,
            'capability'      => '',
            'choices'         => [],
            'input_attrs'     => [],
            'instance_number' => 0,
            'priority'        => 10,
            'section'         => '',
            'settings'        => [],
            'type'            => 'text',
            'classname'       => '',
        ], $options);

        // Check control options
        $options = $this->checkOptions($options, 'controls');
        $options = $this->checkSettings($options, $identifier);

        /**
         * Filter the control options.
         *
         * The dynamic portion of the hook name, `$identifier`, refers to the
         * uniq name of the current control.
         *
         * @var    string  $identifier
         * @param  array   $options
         *
         * @return array
         */
        $options = apply_filters('ol.poseidon.application_control_'.$identifier.'_options', $options);

        // Build all types
        $types = array_merge($this->available_types['default'], $this->available_types['special']);

        // Check type
        if (empty($options['classname']) && !in_array($options['type'], $types)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.control_type_is_unknown'),
                $options['type'],
                implode('</code>, <code>', $types)
            ));
        }

        // Add control
        $this->getModel()->setControls($identifier, $options);
    }

    /**
     * Adds a new value of panel.
     *
     * @param  string  $identifier
     * @param  array   $options
     * @param  string  $page_redirect
     *
     * @throws ApplicationException
     */
    public function addPanel($identifier, $options, $page_redirect = '') : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new ApplicationException(Translate::t('application.errors.panel_identifier_is_empty'));
        }

        // Get panel to know if identifier is already used or not
        $panel = $this->getModel()->getPanels($identifier);

        if (!empty($panel)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.panel_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'title'           => Translate::t('application.labels.panel_title'),
            'description'     => '',
            'priority'        => 160,
            'capability'      => 'edit_theme_options',
            'theme_supports'  => '',
            'type'            => '',
            'active_callback' => [],
        ], $options, [
            'redirect'        => $page_redirect,
        ]);

        unset($options['sections']);

        $options['type'] = empty($options['type']) ? 'default' : $options['type'];

        /**
         * Filter the panel options.
         *
         * The dynamic portion of the hook name, `$identifier`, refers to the
         * uniq name of the current panel.
         *
         * @var    string  $identifier
         * @param  array   $options
         *
         * @return array
         */
        $options = apply_filters('ol.poseidon.application_panel_'.$identifier.'_options', $options);

        // Add panel
        $this->getModel()->setPanels($identifier, $options);
    }

    /**
     * Adds a new value of partial.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws ApplicationException
     */
    public function addPartial($identifier, $options) : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new ApplicationException(Translate::t('application.errors.partial_identifier_is_empty'));
        }

        // Get partial to know if identifier is already used or not
        $partial = $this->getModel()->getPartials($identifier);

        if (!empty($partial)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.partial_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'type'                => '',
            'selector'            => '',
            'settings'            => [],
            'primary_setting'     => '',
            'capability'          => 'edit_theme_options',
            'render_callback'     => '',
            'container_inclusive' => true,
            'fallback_refresh'    => true,
        ], $options);

        /**
         * Filter the partial options.
         *
         * The dynamic portion of the hook name, `$identifier`, refers to the
         * uniq name of the current partial.
         *
         * @var    string  $identifier
         * @param  array   $options
         *
         * @return array
         */
        $options = apply_filters('ol.poseidon.application_partial_'.$identifier.'_options', $options);

        // Add partial
        $this->getModel()->setPartials($identifier, $options);
    }

    /**
     * Adds a new value of section.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws ApplicationException
     */
    public function addSection($identifier, $options) : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new ApplicationException(Translate::t('application.errors.section_identifier_is_empty'));
        }

        // Get section to know if identifier is already used or not
        $section = $this->getModel()->getSections($identifier);

        if (!empty($section)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.section_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'title'              => Translate::t('application.labels.section_title'),
            'description'        => '',
            'active_callback'    => [],
            'capability'         => 'edit_theme_options',
            'description_hidden' => false,
            'section'            => '',
            'panel'              => '',
            'priority'           => 160,
            'theme_supports'     => '',
            'type'               => '',
            'classname'          => '',
        ], $options);

        // Add section options
        $options = $this->checkOptions($options, 'sections');
        unset($options['controls']);

        $options['type'] = empty($options['type']) ? 'default' : $options['type'];

        /**
         * Filter the section options.
         *
         * The dynamic portion of the hook name, `$identifier`, refers to the
         * uniq name of the current section.
         *
         * @var    string  $identifier
         * @param  array   $options
         *
         * @return array
         */
        $options = apply_filters('ol.poseidon.application_section_'.$identifier.'_options', $options);

        // Get panel depending on panel option
        if (isset($options['panel']) && !empty($options['panel'])) {
            $panel = $this->getModel()->getPanels($options['panel']);

            // Check panel
            if (empty($panel)) {
                throw new ApplicationException(sprintf(
                    Translate::t('application.errors.section_panel_does_not_exist'),
                    $options['panel']
                ));
            }
        }

        // Add section
        $this->getModel()->setSections($identifier, $options);
    }

    /**
     * Adds a new value of setting.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws ApplicationException
     */
    public function addSetting($identifier, $options) : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new ApplicationException(Translate::t('application.errors.setting_identifier_is_empty'));
        }

        // Get setting to know if identifier is already used or not
        $setting = $this->getModel()->getSettings($identifier);

        if (!empty($setting)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.setting_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'capability'           => 'edit_theme_options',
            'default'              => '',
            'dirty'                => false,
            'sanitize_callback'    => '',
            'sanitize_js_callback' => '',
            'theme_supports'       => '',
            'transport'            => 'refresh',
            'type'                 => 'theme_mod',
            'validate_callback'    => '',
        ], $options);

        /**
         * Filter the setting options.
         *
         * The dynamic portion of the hook name, `$identifier`, refers to the
         * uniq name of the current setting.
         *
         * @var    string  $identifier
         * @param  array   $options
         *
         * @return array
         */
        $options = apply_filters('ol.poseidon.application_setting_'.$identifier.'_options', $options);

        // Check type
        if (!in_array($options['type'], $this->default_types)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.setting_type_is_unknown'),
                $options['type'],
                implode('</code>, <code>', $this->default_types)
            ));
        }

        // Check transport
        if (!in_array($options['transport'], $this->default_transports)) {
            throw new ApplicationException(sprintf(
                Translate::t('application.errors.setting_transport_is_unknown'),
                $options['transport'],
                implode('</code>, <code>', $this->default_transports)
            ));
        }

        // Add setting
        $this->getModel()->setSettings($identifier, $options);
    }

    /**
     * Retrieve options and add component if needed.
     *
     * @param  array   $options
     * @param  string  $type
     *
     * @return array
     */
    protected function checkOptions($options, $type = 'controls') : array
    {
        /**
         * Fires before control options.
         *
         * @param  array   $options
         */
        do_action('ol.poseidon.application_options_before', $options);

        // Check control type
        if ('controls' === $type && in_array($options['type'], $this->available_types['default'])) {
            // Check options
            $options['choices']      = isset($options['choices']) ? $options['choices'] : [];
            $options['input_attrs']  = isset($options['input_attrs']) ? $options['input_attrs'] : [];

            return $options;
        }

        $options['classname'] = '';

        // Checks if the component is a default WordPress one
        $is_wordpress = in_array($options['type'], $this->available_types['special']);

        // Build options
        $options = ApplicationUtils::buildOptions($options, $this->available_types, $this->mime_types);

        /**
         * Poseidon types
         */
        if (empty($options['classname'])) {
            foreach($this->custom_components[$type] as $component) {
                if ($component['id'] !== $options['type']) {
                    continue;
                }

                $options['classname'] = $component['name'];
                $options['path']      = isset($component['path']) ? $component['path'] : '';
            }
        }

        // Add component
        if (!empty($options['classname'])) {
            $path = isset($options['path']) ? $options['path'] : '';
            $this->addComponent($options['classname'], $path, $type, $is_wordpress);
        }

        /**
         * Fires after control options.
         *
         * @param  array   $options
         */
        do_action('ol.poseidon.application_options_after', $options);

        return $options;
    }

    /**
     * Retrieve control setting name.
     *
     * @param  array   $options
     * @param  string  $identifier
     *
     * @return mixed
     */
    protected function checkSettings($options, $identifier)
    {
        /**
         * Fires before setting options.
         *
         * @param  array   $options
         */
        do_action('ol.poseidon.application_settings_before', $options);

        // Check classname
        if (!isset($options['classname']) || empty($options['classname'])) {
            return $options;
        }

        // Get settings
        $settings = $options['classname']::settings();
        $configs  = [];

        // Build configs if exists
        if (isset($settings['_configs'])) {
            $configs = $settings['_configs'];
            unset($settings['_configs']);
        }

        // Initialize settings
        $options['settings'] = [];

        foreach ($settings as $key => $callback) {
            $key = $identifier.'-'.$key;

            // Check to add setting
            $setting = $this->getModel()->getSettings($key);

            if (empty($setting)) {
                $options['settings'][] = $key;

                // Add setting into model
                $this->addSetting($key, array_merge($configs, [
                    'sanitize_callback' => $callback
                ]));
            }
        }

        /**
         * Fires after setting options.
         *
         * @param  array   $options
         */
        do_action('ol.poseidon.application_settings_after', $options);

        return $options;
    }

    /**
     * Return pane assets.
     *
     * @return array
     */
    public function getPaneAssets() : array
    {
        // Get pane
        return $this->assets_pane;
    }

    /**
     * Return previewer assets.
     *
     * @return array
     */
    public function getPreviewerAssets() : array
    {
        // Get previewer
        return $this->assets_previewer;
    }

    /**
     * Initialize translations.
     */
    protected function translate() : void
    {
        foreach ($this->custom_components as $type => $components) {
            foreach ($components as $component) {
                $t = $component['name']::translate();
                $this->translations = array_merge($this->translations, $t);
            }
        }

        // Get all translations with default MO file
        Translate::l($this->translations, $this->locale);
    }

    /**
     * Register Application.
     *
     * @throws ApplicationException
     */
    protected function register() : void
    {
        /**
         * Fires before hook registration.
         *
         * @param  object  $this
         */
        do_action('ol.poseidon.application_register_before', $this);

        // Initialize hook
        new ApplicationHook($this);

        /**
         * Fires after hook registration.
         *
         * @param  object  $this
         */
        do_action('ol.poseidon.application_register_after', $this);
    }

    /**
     * Prepare variables.
     */
    abstract protected function setVars() : void;
}
