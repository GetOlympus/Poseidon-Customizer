<?php

namespace GetOlympus\Poseidon\Builder;

use GetOlympus\Poseidon\Builder\Builder;
use GetOlympus\Poseidon\Utils\Helpers;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builder hook controller
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage BuilderHook
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class BuilderHook
{
    /**
     * @var array
     */
    protected $args = [];

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $components = [];

    /**
     * @var array
     */
    protected $pages = [];

    /**
     * @var array
     */
    protected $types = ['controls', 'panels', 'sections', 'settings'];

    /**
     * Constructor.
     *
     * @param  Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;

        // Get panels
        $panels = $this->builder->getModel()->getPanels();

        // Check panels
        if (!empty($panels)) {
            $homeurl = get_home_url();

            foreach ($panels as $key => $options) {
                if (!isset($options['redirect']) || empty($options['redirect'])) {
                    continue;
                }

                // Update pages
                $this->pages[] = [
                    'identifier' => $key,
                    'path'       => $homeurl,
                ];
            }

            /**
             * Filter the arguments list.
             *
             * @param  array   $args
             *
             * @return array
             */
            $this->args = apply_filters('ol.poseidon.builderhook_args', [
                'login_url'        => wp_login_url(),
                'lostpassword_url' => wp_lostpassword_url(),
                'register_url'     => wp_registration_url(),
                'site_url'         => get_option('siteurl'),
                'pages'            => $this->pages,
            ]);
        }

        // Customize and manipulate the Theme Customization admin screen
        add_action('customize_register', [$this, 'builderHookRegisterComponents'], 10, 1);
        add_action('customize_register', [$this, 'builderHookSetComponents'], 11, 1);

        // Add page redirect if necessary
        //add_filter('template_include', [$this, 'customizeTemplateRedirect'], 99);

        // Load customizer assets
        add_action('customize_preview_init', [$this, 'scriptsPreviewer']); // 25
        add_action('customize_controls_enqueue_scripts', [$this, 'scriptsEnqueuer'], 7); // 25
    }

    /**
     * Customize and manipulate the Theme Customization admin screen.
     * @see https://developer.wordpress.org/reference/hooks/customize_register/
     *
     * @param  object  $wp_customize
     *
     * @throws BuilderException
     */
    public function builderHookRegisterComponents($wp_customize) : void
    {
        /**
         * Fires before registering components through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_register_components_before', $wp_customize, $this);

        $components = $this->builder->getModel()->getComponents();

        // Check components
        if (empty($components)) {
            return;
        }

        // Iterate on all components
        foreach ($components as $name => $opts) {
            if (!in_array($opts['type'], $this->types) || in_array($name, $this->components)) {
                continue;
            }

            $this->components[] = $name;

            // WordPress default components don't need to be registered
            if ($opts['wordpress']) {
                continue;
            }

            // WordPress default components don't need any extra files
            if (!empty($opts['file'])) {
                require_once $opts['file'];
            }

            // Register components depending on type
            if ('panels' === $opts['type']) {
                $wp_customize->register_panel_type($name);
            } else if ('sections' === $opts['type']) {
                $wp_customize->register_section_type($name);
            } else if ('controls' === $opts['type']) {
                $wp_customize->register_control_type($name);
            }
        }

        /**
         * Fires after registering components through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_register_components_after', $wp_customize, $this);
    }

    /**
     * Customize and manipulate the Theme Customization admin screen.
     * @see https://developer.wordpress.org/reference/hooks/customize_register/
     *
     * @param  object  $wp_customize
     *
     * @throws BuilderException
     */
    public function builderHookSetComponents($wp_customize) : void
    {
        /**
         * Fires before setting components through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_set_components_before', $wp_customize, $this);

        // Adds everything needed in this order
        $this->addPanels($wp_customize);
        $this->addSections($wp_customize);
        $this->addSettings($wp_customize);
        $this->addControls($wp_customize);

        /**
         * Fires after setting components through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_set_components_after', $wp_customize, $this);
    }

    /**
     * Adds controls.
     * @see https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
     *
     * @param  object  $wp_customize
     */
    protected function addControls($wp_customize) : void
    {
        /**
         * Fires before adding controls through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_controls_before', $wp_customize, $this);

        /**
         * Filter the controls.
         *
         * @param  array   $controls
         *
         * @return array
         */
        $controls = apply_filters('ol.poseidon.builderhook_controls', $this->builder->getModel()->getControls());

        // Check controls
        if (empty($controls)) {
            return;
        }

        // Iterate on all controls
        foreach ($controls as $id => $options) {
            if (empty($options['classname'])) {
                $wp_customize->add_control($id, $options);
                continue;
            }

            // Check custom classname
            if (!in_array($options['classname'], $this->components)) {
                continue;
            }

            $wp_customize->add_control(new $options['classname']($wp_customize, $id, $options));
        }

        /**
         * Fires after adding controls through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_controls_after', $wp_customize, $this);
    }

    /**
     * Adds panels.
     * @see https://developer.wordpress.org/reference/classes/wp_customize_manager/add_panel/
     *
     * @param  object  $wp_customize
     */
    protected function addPanels($wp_customize) : void
    {
        /**
         * Fires before adding panels through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_panels_before', $wp_customize, $this);

        /**
         * Filter the panels.
         *
         * @param  array   $panels
         *
         * @return array
         */
        $panels = apply_filters('ol.poseidon.builderhook_panels', $this->builder->getModel()->getPanels());

        // Check panels
        if (empty($panels)) {
            return;
        }

        // Iterate on all panels
        foreach ($panels as $id => $options) {
            $wp_customize->add_panel($id, $options);
        }

        /**
         * Fires after adding panels through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_panels_after', $wp_customize, $this);
    }

    /**
     * Adds sections.
     * @see https://developer.wordpress.org/reference/classes/wp_customize_manager/add_section/
     *
     * @param  object  $wp_customize
     */
    protected function addSections($wp_customize) : void
    {
        /**
         * Fires before adding sections through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_sections_before', $wp_customize, $this);

        /**
         * Filter the sections.
         *
         * @param  array   $sections
         *
         * @return array
         */
        $sections = apply_filters('ol.poseidon.builderhook_sections', $this->builder->getModel()->getSections());

        // Check sections
        if (empty($sections)) {
            return;
        }

        // Iterate on all sections
        foreach ($sections as $id => $options) {
            if (empty($options['classname'])) {
                $wp_customize->add_section($id, $options);
                continue;
            }

            // Check custom classname
            if (!in_array($options['classname'], $this->components)) {
                continue;
            }

            $wp_customize->add_section(new $options['classname']($wp_customize, $id, $options));
        }

        /**
         * Fires after adding sections through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_sections_after', $wp_customize, $this);
    }

    /**
     * Adds settings.
     * @see https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
     *
     * @param  object  $wp_customize
     */
    protected function addSettings($wp_customize) : void
    {
        /**
         * Fires before adding settings through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_settings_before', $wp_customize, $this);

        /**
         * Filter the settings.
         *
         * @param  array   $settings
         *
         * @return array
         */
        $settings = apply_filters('ol.poseidon.builderhook_settings', $this->builder->getModel()->getSettings());

        // Check settings
        if (empty($settings)) {
            return;
        }

        // Iterate on all settings
        foreach ($settings as $id => $options) {
            $wp_customize->add_setting($id, $options);
        }

        /**
         * Fires after adding settings through customizer.
         *
         * @param  object  $wp_customize
         * @param  object  $this
         */
        do_action('ol.poseidon.builderhook_add_settings_after', $wp_customize, $this);
    }

    /**
     * Enqueue scripts.
     */
    public function scriptsEnqueuer() : void
    {
        if (!OL_POSEIDON_ISPREVIEW) {
            return;
        }

        // Works on pane assets
        $pane = $this->builder->getPaneAssets();

        if (empty($pane)) {
            return;
        }

        $name = explode('\\', get_class($this->builder));
        $file = Helpers::urlize(array_pop($name));

        // Enqueue scripts and stylesheets
        Helpers::enqueueFiles($pane['js'], 'js', ['jquery']);
        Helpers::enqueueFiles($pane['css'], 'css', []);

        // Works on components assets
        $components = $this->builder->getModel()->getComponents();

        // Check components
        if (empty($components)) {
            return;
        }

        // Iterate on all components
        foreach ($components as $name => $opts) {
            if (!method_exists($name, 'assets')) {
                continue;
            }

            $name::assets();
        }
    }

    /**
     * Preview styles.
     */
    public function scriptsPreviewer() : void
    {
        if (!OL_POSEIDON_ISPREVIEW) {
            return;
        }

        $previewer = $this->builder->getPreviewerAssets();

        if (empty($previewer)) {
            return;
        }

        $name = explode('\\', get_class($this->builder));
        $file = Helpers::urlize(array_pop($name));

        // Enqueue scripts and stylesheets
        Helpers::enqueueFiles($previewer['js'], 'js', ['jquery']);
        Helpers::enqueueFiles($previewer['css'], 'css', []);
    }
}
