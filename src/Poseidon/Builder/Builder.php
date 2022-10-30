<?php

namespace GetOlympus\Poseidon\Builder;

use GetOlympus\Poseidon\Base\BaseTrait;
use GetOlympus\Poseidon\Builder\BuilderException;
use GetOlympus\Poseidon\Builder\BuilderHook;
use GetOlympus\Poseidon\Builder\BuilderInterface;
use GetOlympus\Poseidon\Builder\BuilderModel;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Sanitizer;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builder controller
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Builder
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

abstract class Builder implements BuilderInterface
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
        // Work on admin only
        if (!OL_POSEIDON_ISADMIN) {
            return;
        }

        // Initialize BuilderModel
        $this->model = new BuilderModel();

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
     * @throws BuilderException
     */
    public function addComponent($name, $path = '', $type = 'controls', $is_wordpress = false) : void
    {
        // Check name
        if (empty($name)) {
            throw new BuilderException(Translate::t('builder.errors.component_name_is_empty'));
        }

        // Check type
        if (!in_array($type, $this->default_components)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.component_type_is_unknown'),
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
                throw new BuilderException(sprintf(
                    Translate::t('builder.errors.component_name_is_already_used'),
                    $name
                ));
            }

            return;
        }

        // Check path
        if (!empty($path) && !file_exists($file = realpath($path))) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.component_path_does_not_exists'),
                $name
            ));
        }

        /**
         * Filter the component name.
         *
         * @param  string  $name
         *
         * @return string
         */
        $name = apply_filters('ol.poseidon.builder_component_name', $name);

        // Add component
        $this->getModel()->setComponents($name, [
            'file'      => $file,
            'type'      => $type,
            'wordpress' => $is_wordpress,
        ]);

        // Add translation
        if (!class_exists($name)) {
            $t = $name::translate();
            $this->translations = array_merge($this->translations, $t);
        }
    }

    /**
     * Adds a new value of control.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws BuilderException
     */
    public function addControl($identifier, $options) : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new BuilderException(Translate::t('builder.errors.control_identifier_is_empty'));
        }

        // Check section option
        if (!isset($options['section']) || empty($options['section'])) {
            throw new BuilderException(Translate::t('builder.errors.control_section_is_required'));
        }

        // Get control to know if identifier is already used or not
        $control = $this->getModel()->getControls($identifier);

        if (!empty($control)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.control_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'label'           => Translate::t('builder.labels.control_title'),
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
        $options = $this->checkSettingsName($options, $identifier);

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
        $options = apply_filters('ol.poseidon.builder_control_'.$identifier.'_options', $options);

        // Build all types
        $types = array_merge($this->available_types['default'], $this->available_types['special']);

        // Check type
        if (empty($options['classname']) && !in_array($options['type'], $types)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.control_type_is_unknown'),
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
     * @throws BuilderException
     */
    public function addPanel($identifier, $options, $page_redirect = '') : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new BuilderException(Translate::t('builder.errors.panel_identifier_is_empty'));
        }

        // Get panel to know if identifier is already used or not
        $panel = $this->getModel()->getPanels($identifier);

        if (!empty($panel)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.panel_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'title'           => Translate::t('builder.labels.panel_title'),
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
        $options = apply_filters('ol.poseidon.builder_panel_'.$identifier.'_options', $options);

        // Add panel
        $this->getModel()->setPanels($identifier, $options);
    }

    /**
     * Adds a new value of section.
     *
     * @param  string  $identifier
     * @param  array   $options
     *
     * @throws BuilderException
     */
    public function addSection($identifier, $options) : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new BuilderException(Translate::t('builder.errors.section_identifier_is_empty'));
        }

        // Get section to know if identifier is already used or not
        $section = $this->getModel()->getSections($identifier);

        if (!empty($section)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.section_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'title'              => Translate::t('builder.labels.section_title'),
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
        $options = apply_filters('ol.poseidon.builder_section_'.$identifier.'_options', $options);

        // Get panel depending on panel option
        if (isset($options['panel']) && !empty($options['panel'])) {
            $panel = $this->getModel()->getPanels($options['panel']);

            // Check panel
            if (empty($panel)) {
                throw new BuilderException(sprintf(
                    Translate::t('builder.errors.section_panel_does_not_exist'),
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
     * @throws BuilderException
     */
    public function addSetting($identifier, $options) : void
    {
        // Check identifier
        if (empty($identifier)) {
            throw new BuilderException(Translate::t('builder.errors.setting_identifier_is_empty'));
        }

        // Get setting to know if identifier is already used or not
        $setting = $this->getModel()->getSettings($identifier);

        if (!empty($setting)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.setting_identifier_is_already_used'),
                $identifier
            ));
        }

        // Merge options with defaults
        $options = array_merge([
            'capability'           => 'edit_theme_options',
            'default'              => null,
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
        $options = apply_filters('ol.poseidon.builder_setting_'.$identifier.'_options', $options);

        // Check type
        if (!in_array($options['type'], $this->default_types)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.setting_type_is_unknown'),
                $options['type'],
                implode('</code>, <code>', $this->default_types)
            ));
        }

        // Check transport
        if (!in_array($options['transport'], $this->default_transports)) {
            throw new BuilderException(sprintf(
                Translate::t('builder.errors.setting_transport_is_unknown'),
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
        do_action('ol.poseidon.builder_options_before', $options);

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

        switch ($options['type']) {
            case 'code_editor':
            case 'code-editor':
            case 'editor':
                /**
                 * Need to init a setting object and retrieve its generated custom ID
                 */
                // WP_Customize_Code_Editor_Control
                $options['classname'] = 'WP_Customize_Code_Editor_Control';
                $options['code_type'] = 
                    isset($options['code_type']) && in_array($options['code_type'], $this->available_types['settings'])
                        ? $options['code_type']
                        : $this->available_types['settings'][0];

                // Settings to set here.
                break;
            case 'color':
                // WP_Customize_Color_Control
                $options['classname'] = 'WP_Customize_Color_Control';

                // Setting
                $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
                $options['setting'] = array_merge([
                    'default'           => '#000000',
                    'sanitize_callback' => ['Sanitizer', 'sanitizeColor'],
                ], $options['setting']);
                break;
            case 'date_time':
            case 'date-time':
                // WP_Customize_Date_Time_Control
                $options['classname'] = 'WP_Customize_Date_Time_Control';

                $options['allow_past_date'] = isset($options['allow_past_date']) ? $options['allow_past_date'] : true;
                $options['include_time']    = isset($options['include_time']) ? $options['include_time'] : true;
                $options['max_year']        = isset($options['max_year']) ? $options['max_year'] : '9999';
                $options['min_year']        = isset($options['min_year']) ? $options['min_year'] : '1000';
                $options['twelve_hour_format'] = isset($options['twelve_hour_format'])
                    ? $options['twelve_hour_format']
                    : false;

                // Setting
                $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
                $options['setting'] = array_merge([
                    'default'           => date('Y-m-d H:i:s'),
                    'sanitize_callback' => ['Sanitizer', 'sanitizeDatetime'],
                ], $options['setting']);

                break;
            case 'image':
                // WP_Customize_Image_Control
                $options['classname'] = 'WP_Customize_Image_Control';

                $options['button_labels'] = isset($options['button_labels']) ? $options['button_labels'] : [
                    'select'       => Translate::t('builder.labels.control_image_select'),
                    'change'       => Translate::t('builder.labels.control_image_change'),
                    'default'      => Translate::t('builder.labels.control_image_default'),
                    'remove'       => Translate::t('builder.labels.control_image_remove'),
                    'placeholder'  => Translate::t('builder.labels.control_image_placeholder'),
                    'frame_title'  => Translate::t('builder.labels.control_image_frame_title'),
                    'frame_button' => Translate::t('builder.labels.control_image_frame_button'),
                ];

                // Setting
                $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
                $options['setting'] = array_merge([
                    'sanitize_callback' => ['Sanitizer', 'sanitizeImage'],
                ], $options['setting']);

                break;
            case 'media':
                // WP_Customize_Media_Control
                $options['classname'] = 'WP_Customize_Media_Control';

                $options['mime_type'] = isset($options['mime_type']) ? $options['mime_type'] : 'image';
                $options['mime_type'] = !in_array($options['mime_type'], $this->mime_types)
                    ? 'image'
                    : $options['mime_type'];

                $options['button_labels'] = isset($options['button_labels']) ? $options['button_labels'] : [
                    'select'       => Translate::t('builder.labels.control_media_select'),
                    'change'       => Translate::t('builder.labels.control_media_change'),
                    'default'      => Translate::t('builder.labels.control_media_default'),
                    'remove'       => Translate::t('builder.labels.control_media_remove'),
                    'placeholder'  => Translate::t('builder.labels.control_media_placeholder'),
                    'frame_title'  => Translate::t('builder.labels.control_media_frame_title'),
                    'frame_button' => Translate::t('builder.labels.control_media_frame_button'),
                ];

                // Setting
                $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
                $options['setting'] = array_merge([
                    'sanitize_callback' => ['Sanitizer', 'sanitizeUrl'],
                ], $options['setting']);

                break;
        }

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
        do_action('ol.poseidon.builder_options_after', $options);

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
    protected function checkSettingsName($options, $identifier)
    {
        if (
            (!isset($options['setting']) || empty($options['setting'])) &&
            (!isset($options['settings']) || empty($options['settings']))
        ) {
            return $options;
        }

        // Works on control with only one setting
        if (isset($options['setting']) && !empty($options['setting'])) {
            $setting = $this->getModel()->getSettings($identifier);

            // Add setting if not exists
            if (empty($setting)) {
                $this->addSetting($identifier, $options['setting']);
            }

            unset($options['setting']);
            unset($options['settings']);

            return $options;
        }

        // Works on control with multiple settings
        $settings = [];
        $rand     = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Get settings to know if setting is already used or not
        foreach($options['settings'] as $name => $setting_options) {
            $name = is_string($name) ? $name : substr(str_shuffle(str_repeat($rand, ceil(20 / strlen($rand)))), 1, 20);

            $setting = $this->getModel()->getSettings($identifier.'-'.$name);

            // Add settings if not exists
            if (empty($setting)) {
                $this->addSetting($identifier.'-'.$name, $setting_options);
            }

            $settings[$name] = $identifier.'-'.$name;
        }

        $options['settings'] = $settings;

        unset($options['setting']);

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
        // Get all translations with default MO file
        Translate::l($this->translations, $this->locale);
    }

    /**
     * Register Builder.
     *
     * @throws BuilderException
     */
    protected function register() : void
    {
        /**
         * Fires before hook registration.
         *
         * @param  object  $this
         */
        do_action('ol.poseidon.builder_register_before', $this);

        // Initialize hook
        new BuilderHook($this);

        /**
         * Fires after hook registration.
         *
         * @param  object  $this
         */
        do_action('ol.poseidon.builder_register_after', $this);
    }

    /**
     * Prepare variables.
     */
    abstract protected function setVars() : void;
}
