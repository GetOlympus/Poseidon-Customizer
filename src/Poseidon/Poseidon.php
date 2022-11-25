<?php declare(strict_types=1);

namespace GetOlympus\Poseidon;

use GetOlympus\Poseidon\Builder\Builder;

/**
 * Package constants.
 */

// Directory separator
defined('S') or define('S', DIRECTORY_SEPARATOR);
// The path
define('OL_POSEIDON_PATH', rtrim(dirname(dirname(dirname(__FILE__))), S).S);

// Bundle assets folder
define('OL_POSEIDON_ASSETSPATH', OL_POSEIDON_PATH.'app'.S.'assets'.S);
// Assets folder
define('OL_POSEIDON_DISTPATH', defined('DISTPATH') ? DISTPATH : OL_POSEIDON_ASSETSPATH);
// Blog home url
define('OL_POSEIDON_HOME', defined('OL_BLOG_HOME') ? OL_BLOG_HOME : get_option('home'));
// URI
define('OL_POSEIDON_URI', defined('WEBPATH') ? str_replace(WEBPATH, '/../', DISTPATH) : OL_POSEIDON_HOME.'/app/assets');

// Is admin
define('OL_POSEIDON_ISADMIN', defined('OL_ISADMIN') ? OL_ISADMIN : is_admin());
// Is debug
define('OL_POSEIDON_ISDEBUG', defined('WP_DEBUG') ? WP_DEBUG : false);
// Is preview mode
define('OL_POSEIDON_ISPREVIEW', defined('OL_ISPREVIEW') ? OL_ISPREVIEW : is_customize_preview());


/**
 * Olympus Poseidon Customizer.
 *
 * @package    OlympusPoseidonCustomizer
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

abstract class Poseidon extends Builder
{
    /**
     * @var array
     */
    protected $assets_pane = [
        'css' => [OL_POSEIDON_ASSETSPATH.'css'.S.'poseidon.css'],
        'js'  => [
            OL_POSEIDON_ASSETSPATH.'js'.S.'poseidon-colorpicker.js',
            OL_POSEIDON_ASSETSPATH.'js'.S.'poseidon-dropdown.js',
            OL_POSEIDON_ASSETSPATH.'js'.S.'poseidon-sidepanel.js',
            OL_POSEIDON_ASSETSPATH.'js'.S.'poseidon-controls.js',
            OL_POSEIDON_ASSETSPATH.'js'.S.'poseidon.js',
        ],
    ];

    /**
     * @var array
     */
    protected $assets_previewer = [
        'css' => [OL_POSEIDON_ASSETSPATH.'css'.S.'poseidon-previewer.css'],
        'js'  => [],
    ];

    /**
     * @var array
     */
    protected $custom_components = [
        'controls' => [
            /**
             * - ColorControl => new color field containing up to 4 colors from palette if needed
             * - ImageSelectControl => choose between images
             *
             */
            /*[
                'id'   => 'poseidon-background-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\BackgroundControl',
            ],*/
            [
                'id'   => 'poseidon-buttons-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\ButtonsControl',
            ],
            [
                'id'   => 'poseidon-color-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\ColorControl',
            ],
            [
                'id'   => 'poseidon-color-palette-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\ColorPaletteControl',
            ],
            /*[
                'id'   => 'poseidon-image-select-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\ImageSelectControl',
            ],*/
            [
                'id'   => 'poseidon-choice-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\ChoiceControl',
            ],
            [
                'id'   => 'poseidon-notice-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\NoticeControl',
            ],
            [
                'id'   => 'poseidon-slider-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\SliderControl',
            ],
            [
                'id'   => 'poseidon-title-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\TitleControl',
            ],
            [
                'id'   => 'poseidon-toggle-control',
                'name' => 'GetOlympus\Poseidon\Components\Controls\ToggleControl',
            ],
        ],
        'panels'   => [],
        'sections' => [
            [
                'id'   => 'poseidon-divider-section',
                'name' => 'GetOlympus\Poseidon\Components\Sections\DividerSection',
            ],
            [
                'id'   => 'poseidon-html-section',
                'name' => 'GetOlympus\Poseidon\Components\Sections\HtmlSection',
            ],
            [
                'id'   => 'poseidon-link-section',
                'name' => 'GetOlympus\Poseidon\Components\Sections\LinkSection',
            ],
            [
                'id'   => 'poseidon-title-section',
                'name' => 'GetOlympus\Poseidon\Components\Sections\TitleSection',
            ],
            [
                'id'   => 'poseidon-toggle-section',
                'name' => 'GetOlympus\Poseidon\Components\Sections\ToggleSection',
            ],
        ],
        'settings' => [],
    ];

    /**
     * @var array
     */
    protected $translations = [
        'olympus-poseidon'     => OL_POSEIDON_PATH.'languages',
        'image-select-control' => OL_POSEIDON_PATH.'languages',
        'toggle-control'       => OL_POSEIDON_PATH.'languages',
    ];
}
