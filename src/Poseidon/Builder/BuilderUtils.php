<?php

namespace GetOlympus\Poseidon\Builder;

/**
 * Builder utils
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Builder
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class BuilderUtils
{
    /**
     * Build code editor options
     *
     * @param  array   $options
     *
     * @return array
     */
    public static function getCodeEditor($options)
    {
        $options['classname'] = 'WP_Customize_Code_Editor_Control';

        // Options
        $options['code_type'] = 
            isset($options['code_type']) && in_array($options['code_type'], $this->available_types['settings'])
                ? $options['code_type']
                : $this->available_types['settings'][0];

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

        return $options;
    }

    /**
     * Build media options
     *
     * @param  array   $options
     *
     * @return array
     */
    public static function getMedia($options)
    {
        $options['classname'] = 'WP_Customize_Media_Control';

        // Options
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

        return $options;
    }
}
