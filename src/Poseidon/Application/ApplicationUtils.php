<?php

namespace GetOlympus\Poseidon\Application;

use GetOlympus\Poseidon\Utils\Translate;

/**
 * Application utils
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Application
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ApplicationUtils
{
    /**
     * Build options
     *
     * @param  array   $options
     * @param  array   $available_types
     * @param  array   $mime_types
     *
     * @return array
     */
    public static function buildOptions($options, $available_types, $mime_types)
    {
        if (!isset($options['type'])) {
            return $options;
        }

        // WP_Customize_Background_Position_Control
        if (in_array($options['type'], ['background_position', 'background-position'])) {
            return static::getBackgroundPosition($options);
        }

        // WP_Customize_Code_Editor_Control
        if (in_array($options['type'], ['code_editor', 'code-editor', 'editor'])) {
            return static::getCodeEditor($options, $available_types);
        }

        // WP_Customize_Color_Control
        if ('color' === $options['type']) {
            return static::getColor($options);
        }

        // WP_Customize_Cropped_Image_Control
        if (in_array($options['type'], ['cropped_image', 'cropped-image'])) {
            return static::getCroppedImage($options);
        }

        // WP_Customize_Date_Time_Control
        if (in_array($options['type'], ['date_time', 'date-time'])) {
            return static::getDateTime($options);
        }

        // WP_Customize_Image_Control
        if ('image' === $options['type']) {
            return static::getImage($options);
        }

        // WP_Customize_Media_Control
        if ('media' === $options['type']) {
            return static::getMedia($options, $mime_types);
        }

        return $options;
    }

    /**
     * Build background position options
     *
     * @param  array   $options
     *
     * @return array
     */
    public static function getBackgroundPosition($options)
    {
        /**
         * Does not work right now!
         * The control field does not appear on customizer
         */
        $options['classname'] = 'WP_Customize_Background_Position_Control';

        // Settings
        $options['settings'] = isset($options['settings']) ? $options['settings'] : [];
        $defaults = [
            'x' => [
                'default'           => 'center',
                'sanitize_callback' => ['Sanitizer', 'sanitizeBackgroundPositionX'],
                'theme_supports'    => 'custom-background',
            ],
            'y' => [
                'default'           => 'center',
                'sanitize_callback' => ['Sanitizer', 'sanitizeBackgroundPositionY'],
                'theme_supports'    => 'custom-background',
            ],
        ];

        foreach ($defaults as $name => $setting) {
            if (isset($options['settings'][$name])) {
                continue;
            }

            $options['settings'][$name] = $defaults[$name];
        }

        return $options;
    }

    /**
     * Build code editor options
     *
     * @param  array   $options
     * @param  array   $available_types
     *
     * @return array
     */
    public static function getCodeEditor($options, $available_types)
    {
        $options['classname'] = 'WP_Customize_Code_Editor_Control';

        // Options
        $options['code_type'] = 
            isset($options['code_type']) && in_array($options['code_type'], $available_types['settings'])
                ? $options['code_type']
                : $available_types['settings'][0];

        /**
         * Need to init a setting object and retrieve its generated custom ID
         */

        return $options;
    }

    /**
     * Build color options
     *
     * @param  array   $options
     *
     * @return array
     */
    public static function getColor($options)
    {
        $options['classname'] = 'WP_Customize_Color_Control';

        // Setting
        $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
        $options['setting'] = array_merge([
            'default'           => '#000000',
            'sanitize_callback' => ['Sanitizer', 'sanitizeColor'],
        ], $options['setting']);

        return $options;
    }

    /**
     * Build cropped image options
     *
     * @param  array   $options
     *
     * @return array
     */
    public static function getCroppedImage($options)
    {
        /**
         * Does not work right now!
         * The control field does not appear on customizer
         */
        $options['classname'] = 'WP_Customize_Cropped_Image_Control';

        // Options
        $options['flex_height'] = isset($options['flex_height']) ? $options['flex_height'] : false;
        $options['flex_width']  = isset($options['flex_width']) ? $options['flex_width'] : false;
        $options['height']      = isset($options['height']) ? $options['height'] : 150;
        $options['width']       = isset($options['width']) ? $options['width'] : 150;

        $options['button_labels'] = isset($options['button_labels']) ? $options['button_labels'] : [
            'select'       => Translate::t('application.labels.control_image_select'),
            'change'       => Translate::t('application.labels.control_image_change'),
            'default'      => Translate::t('application.labels.control_image_default'),
            'remove'       => Translate::t('application.labels.control_image_remove'),
            'placeholder'  => Translate::t('application.labels.control_image_placeholder'),
            'frame_title'  => Translate::t('application.labels.control_image_frame_title'),
            'frame_button' => Translate::t('application.labels.control_image_frame_button'),
        ];

        // Setting
        $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
        $options['setting'] = array_merge([
            'theme_supports' => 'custom-logo',
            'transport'      => 'postMessage',
        ], $options['setting']);

        return $options;
    }

    /**
     * Build date time options
     *
     * @param  array   $options
     *
     * @return array
     */
    public static function getDateTime($options)
    {
        $options['classname'] = 'WP_Customize_Date_Time_Control';

        // Options
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

        return $options;
    }

    /**
     * Build image options
     *
     * @param  array   $options
     *
     * @return array
     */
    public static function getImage($options)
    {
        $options['classname'] = 'WP_Customize_Image_Control';

        // Options
        $options['button_labels'] = isset($options['button_labels']) ? $options['button_labels'] : [
            'select'       => Translate::t('application.labels.control_image_select'),
            'change'       => Translate::t('application.labels.control_image_change'),
            'default'      => Translate::t('application.labels.control_image_default'),
            'remove'       => Translate::t('application.labels.control_image_remove'),
            'placeholder'  => Translate::t('application.labels.control_image_placeholder'),
            'frame_title'  => Translate::t('application.labels.control_image_frame_title'),
            'frame_button' => Translate::t('application.labels.control_image_frame_button'),
        ];

        // Setting
        $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
        $options['setting'] = array_merge([
            'sanitize_callback' => ['Sanitizer', 'sanitizeImage'],
        ], $options['setting']);

        return $options;
    }

    /**
     * Build media options
     *
     * @param  array   $options
     * @param  array   $mime_types
     *
     * @return array
     */
    public static function getMedia($options, $mime_types)
    {
        $options['classname'] = 'WP_Customize_Media_Control';

        // Options
        $options['mime_type'] = isset($options['mime_type']) ? $options['mime_type'] : 'image';
        $options['mime_type'] = !in_array($options['mime_type'], $mime_types)
            ? 'image'
            : $options['mime_type'];

        $options['button_labels'] = isset($options['button_labels']) ? $options['button_labels'] : [
            'select'       => Translate::t('application.labels.control_media_select'),
            'change'       => Translate::t('application.labels.control_media_change'),
            'default'      => Translate::t('application.labels.control_media_default'),
            'remove'       => Translate::t('application.labels.control_media_remove'),
            'placeholder'  => Translate::t('application.labels.control_media_placeholder'),
            'frame_title'  => Translate::t('application.labels.control_media_frame_title'),
            'frame_button' => Translate::t('application.labels.control_media_frame_button'),
        ];

        // Setting
        $options['setting'] = isset($options['setting']) ? $options['setting'] : [];
        $options['setting'] = array_merge([
            'sanitize_callback' => ['Sanitizer', 'sanitizeUrl'],
        ], $options['setting']);

        return $options;
    }
}
