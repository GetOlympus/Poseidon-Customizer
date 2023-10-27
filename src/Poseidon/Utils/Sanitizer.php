<?php

namespace GetOlympus\Poseidon\Utils;

/**
 * Sanitizer controller
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Utils
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class Sanitizer
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

    /**
     * Array sanitization
     *
     * @param  string  $input
     *
     * @return array
     */
    public static function sanitizeArray($input)
    {
        // Check value
        $input = self::sanitizeText($input);

        return !is_array($input) ? [$input] : $input;
    }

    /**
     * Background horizontal position sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeBackgroundPositionX($input)
    {
        // Check input
        return in_array($input, ['left', 'center', 'right']) ? $input : 'left';
    }

    /**
     * Background vertical position sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeBackgroundPositionY($input)
    {
        // Check input
        return in_array($input, ['top', 'center', 'bottom']) ? $input : 'top';
    }

    /**
     * Checkbox sanitization
     *
     * @param  bool    $input
     *
     * @return bool
     */
    public static function sanitizeCheckbox($input)
    {
        // Check status
        return isset($input) && true == $input;
    }

    /**
     * Color sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     *
     * @return string
     */
    public static function sanitizeColor($input, $setting)
    {
        // Check input
        if (empty($input)) {
            return $setting->default;
        }

        // Remove useless spaces
        $input = str_replace(' ', '', trim($input));

        // Check hex format
        if (sanitize_hex_color_no_hash($input)) {
            return '#'.$input;
        }

        // Check rgb() or rgba() format
        if (false !== strpos($input, 'rgb')) {
            if (false !== strpos($input, 'rgba')) {
                list($red, $green, $blue, $alpha) = sscanf($input, 'rgba(%d,%d,%d,%f)');
            } else {
                list($red, $green, $blue) = sscanf($input, 'rgb(%d,%d,%d)');
                $alpha = 1;
            }

            // Build value
            $input = 'rgba(';
            $input .= self::setInRange($red, 0, 255).',';
            $input .= self::setInRange($green, 0, 255).',';
            $input .= self::setInRange($blue, 0, 255).',';
            $input .= self::setInRange($alpha, 0, 1);
            $input .= ')';

            return $input;
        }

        // Check hsl() or hsla() format
        if (false !== strpos($input, 'hsl')) {
            if (false !== strpos($input, 'hsla')) {
                list($hue, $saturation, $lightness, $alpha) = sscanf($input, 'hsla(%s,%s,%s,%f)');
            } else {
                list($hue, $saturation, $lightness) = sscanf($input, 'hsl(%s,%s,%s)');
                $alpha = 1;
            }

            // Fix values
            $hue        = str_replace('deg', '', $hue);
            $saturation = str_replace('%', '', $saturation);
            $lightness  = str_replace('%', '', $lightness);

            // Build value
            $input = 'hsla(';
            $input .= self::setInRange($red, 0, 360).',';
            $input .= self::setInRange($green, 0, 100).'%,';
            $input .= self::setInRange($blue, 0, 100).'%,';
            $input .= self::setInRange($alpha, 0, 1);
            $input .= ')';

            return $input;
        }

        // Check var() or env() format
        if (2 < strlen($input) && ('var(--' === substr($input, 0, 6) || 'env(' === substr($input, 0, 4))) {
            return $input;
        }

        return null;
    }

    /**
     * Date time sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     *
     * @return string
     */
    public static function sanitizeDatetime($input, $setting)
    {
        // Set date format
        $format = $setting->manager->get_control($setting->id)->include_time ? 'Y-m-d H:i:s' : 'Y-m-d';

        // Define date
        $date = DateTime::createFromFormat($format, $input);

        return !$date ? DateTime::createFromFormat($format, $setting->default) : $date->format($format);
    }

    /**
     * Dropdown pages sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     *
     * @return string
     */
    public static function sanitizeDropdownPages($input, $setting)
    {
        // Integer format & page status
        $input  = absint($input);
        $status = get_post_status($input);

        return 'publish' === $status ? $input : $setting->default;
    }

    /**
     * File sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     * @param  array   $mimetypes
     *
     * @return string
     */
    public static function sanitizeFile($input, $setting, $mimetypes = [])
    {
        // Allowed file types
        $mimetypes = !empty($mimetypes) ? $mimetypes : [
            'gif'          => 'image/gif',
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png'          => 'image/png',
        ];

        // Check filetype from filename
        $filename = wp_check_filetype($input, $mimetypes);

        return $filename['ext'] ? $input : $setting->default;
    }

    /**
     * Float sanitization
     *
     * @param  string  $input
     *
     * @return float
     */
    public static function sanitizeFloat($input)
    {
        // Filter_var value
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Google fonts sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeGooglefonts($input)
    {
        // Decode input
        $input = json_decode($input, true);

        // Array case
        if (is_array($input)) {
            foreach ($input as $k => $value) {
                $input[$k] = sanitize_text_field($value);
            }

            return json_encode($input);
        }

        return json_encode(sanitize_text_field($input));
    }

    /**
     * HTML sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeHtml($input)
    {
        global $allowedposttags;

        return wp_kses($input, $allowedposttags);
    }

    /**
     * Image sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     *
     * @return string
     */
    public static function sanitizeImage($input, $setting)
    {
        // Allowed file types
        $mimetypes = [
            'apng'           => 'image/apng',
            'avif'           => 'image/avif',
            'bmp'            => 'image/bmp',
            'cur'            => 'image/x-icon',
            'gif'            => 'image/gif',
            'ico'            => 'image/x-icon',
            'jfif|pjpeg|pjp' => 'image/jpeg',
            'jpg|jpeg|jpe'   => 'image/jpeg',
            'png'            => 'image/png',
            'tif|tiff'       => 'image/tiff',
            'svg'            => 'image/svg+xml',
            'webp'           => 'image/webp',
        ];

        return esc_url_raw(self::sanitizeFile($input, $setting, $mimetypes));
    }

    /**
     * Integer sanitization
     *
     * @param  string  $input
     *
     * @return int
     */
    public static function sanitizeInteger($input)
    {
        // Cast value
        return (int) $input;
    }

    /**
     * Javascript sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeJavascript($input)
    {
        // Base64 value
        return base64_encode($input);
    }

    /**
     * Javascript for output sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeJavascriptOutput($input)
    {
        // Escape value
        return self::sanitizeTextarea(self::sanitizeJavascript($input));
    }

    /**
     * Multi check sanitization
     *
     * @param  array   $input
     *
     * @return array
     */
    public static function sanitizeMulticheck($input, $setting)
    {
        // Check values
        $values = !is_array($input) ? explode(',', $input) : $input;

        return !empty($values) ? array_map('sanitize_text_field', $values) : [];
    }

    /**
     * Multi choices sanitization
     *
     * @param  array   $input
     * @param  object  $setting
     *
     * @return array
     */
    public static function sanitizeMultichoices($input, $setting)
    {
        $temp = self::sanitizeArray($input);

        // Retrieve list choices
        $choices = $setting->manager->get_control($setting->id)->choices;

        // Iterate on input choices to remove unused
        foreach ($temp as $key => $value) {
            if (array_key_exists($value, $choices)) {
                continue;
            }

            unset($input[$key]);
        }

        return is_array($input) ? $input : $setting->default;
    }

    /**
     * No HTML sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeNoHtml($input)
    {
        // Remove HTML
        return wp_filter_nohtml_kses($input);
    }

    /**
     * Number sanitization
     *
     * @param  string  $input
     *
     * @return int
     */
    public static function sanitizeNumber($input)
    {
        // Cast value
        return is_numeric($input) ? (int) $input : 0;
    }

    /**
     * Number to blank sanitization
     *
     * @param  string  $input
     *
     * @return mixed
     */
    public static function sanitizeNumberBlank($input)
    {
        // Cast value
        return is_numeric($input) ? (int) $input : '';
    }

    /**
     * Radio sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     *
     * @return string
     */
    public static function sanitizeRadio($input, $setting)
    {
        // Slug format
        $input = sanitize_key($input);

        // Retrieve list choices
        $choices = $setting->manager->get_control($setting->id)->choices;

        return array_key_exists($input, $choices) ? $input : $setting->default;
    }

    /**
     * Range sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     *
     * @return string
     */
    public static function sanitizeRange($input, $setting)
    {
        // Set attributes
        $attrs = $setting->manager->get_control($setting->id)->input_attrs;

        // Define extrema
        $min    = isset($attrs['min']) ? $attrs['min'] : $input;
        $max    = isset($attrs['max']) ? $attrs['max'] : $input;
        $step   = isset($attrs['step']) ? $attrs['step'] : 1;
        $number = floor($input / $step) * $step;

        return self::setInRange($number, $min, $max);
    }

    /**
     * Select sanitization
     *
     * @param  string  $input
     * @param  object  $setting
     *
     * @return string
     */
    public static function sanitizeSelect($input, $setting)
    {
        // Slug format
        $input = sanitize_key($input);

        // Retrieve list choices
        $choices = $setting->manager->get_control($setting->id)->choices;

        return array_key_exists($input, $choices) ? $input : $setting->default;
    }

    /**
     * Slider sanitization
     *
     * @param  string  $input
     *
     * @return bool
     */
    public static function sanitizeSlider($input)
    {
        // Check value
        return !intval($input) && 0 !== intval($input) ? null : true;
    }

    /**
     * Text sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeText($input)
    {
        // Check input
        $input = false !== strpos($input, ',') ? explode(',', $input) : $input;

        // Array case
        if (is_array($input)) {
            foreach ($input as $k => $value) {
                $input[$k] = sanitize_text_field($value);
            }

            return implode(',', $input);
        }

        return sanitize_text_field($input);
    }

    /**
     * Textarea sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeTextarea($input)
    {
        // Escape value
        return esc_textarea($input);
    }

    /**
     * Toggle sanitization
     *
     * @param  string  $input
     *
     * @return bool
     */
    public static function sanitizeToggle($input)
    {
        // Check status
        return true === $input ? 1 : 0;
    }

    /**
     * Unit sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeUnit($input)
    {
        // Check input
        return in_array($input, ['%', 'em', 'pt', 'px', 'rem', 'vh', 'vw']) ? $input : '%';
    }

    /**
     * URL sanitization
     *
     * @param  string  $input
     *
     * @return string
     */
    public static function sanitizeUrl($input)
    {
        // Check input
        $input = false !== strpos($input, ',') ? explode(',', $input) : $input;

        // Array case
        if (is_array($input)) {
            foreach ($input as $k => $value) {
                $input[$k] = esc_url_raw($value);
            }

            return implode(',', $input);
        }

        return esc_url_raw($input);
    }

    /**
     * Set number in specified range
     *
     * @param  number  $input
     * @param  number  $min
     * @param  number  $max
     *
     * @return number
     */
    public static function setInRange($input, $min, $max)
    {
        // Check right number
        return $input < $min ? $min : ($input > $max ? $max : $input);
    }
}
