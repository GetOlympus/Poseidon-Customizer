<?php

namespace GetOlympus\Poseidon\Components\Controls;

use GetOlympus\Poseidon\Control\Control;
use GetOlympus\Poseidon\Utils\Translate;

/**
 * Builds Elements control.
 *
 * @package    OlympusPoseidonBuilder
 * @subpackage Components
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

class ElementsControl extends Control
{
    /**
     * @var array
     */
    protected $default_items = [];

    /**
     * @var string
     */
    public $display = 'block';

    /**
     * @var array
     */
    public $items = [];

    /**
     * @var string
     */
    protected $template = 'elements.html.php';

    /**
     * @var string
     */
    protected $textdomain = 'poseidon-elements';

    /**
     * @var string
     */
    public $type = 'poseidon-elements-control';

    /**
     * Render the control's content
     *
     * @see src\Poseidon\Resources\views\controls\elements.html.php
     * @return void
     */
    protected function render_content() // phpcs:ignore
    {
        // Set variables from defaults
        $this->setVariables();

        // Get values from user settings
        $vals = $this->value();
        $vals = is_null($vals) ? [] : $vals;

        // Vars
        $vars = [
            'title'       => $this->label,
            'description' => $this->description,
            'id'          => $this->id,
            'values'      => $vals,
            'items'       => $this->items,
            'unknown'     => Translate::t('elements.unknown_item', $this->textdomain),
        ];

        require(self::view().S.$this->template);
    }

    /**
     * JSON
     */
    public function json() // phpcs:ignore
    {
        $json = parent::json();

        // Set variables from defaults
        $this->setVariables();

        $json['description'] = $this->description;
        $json['items']       = (array) $this->items;

        return $json;
    }

    /**
     * Get default items
     *
     * @return array
     */
    protected function getDefaultItems()
    {
        return [
            'title'     => $this->getOptionTitle(),
            'thumbnail' => $this->getOptionThumbnail(),
            'excerpt'   => $this->getOptionExcerpt(),
            'read_more' => $this->getOptionReadMore(),
            'metas'     => $this->getOptionMetas(),
            'divider'   => $this->getOptionDivider(),
        ];
    }

    /**
     * Get image sizes
     *
     * @return array
     */
    protected function getImageSizes()
    {
        return get_intermediate_image_sizes();
    }

    /**
     * Get Author options
     *
     * @return array
     */
    protected function getOptionAuthor()
    {
        return [
            'label'   => Translate::t('elements.items.metas.items.author', $this->textdomain),
            'options' => [
                'avatar' => [
                    'label'   => Translate::t('elements.items.metas.items.author.avatar', $this->textdomain),
                    'type'    => 'toggle',
                    'display' => 'inline',
                    'divider' => 'bottom',
                    'value'   => 0,
                ],
                'size' => [
                    'label'      => Translate::t('elements.items.metas.items.author.size', $this->textdomain),
                    'type'       => 'number',
                    'conditions' => [['class', 'metas:items:author:avatar', 1]],
                    'display'    => 'inline',
                    'value'      => 25,
                    'min'        => 15,
                    'max'        => 50,
                ],
            ],
        ];
    }

    /**
     * Get Comments options
     *
     * @return array
     */
    protected function getOptionComments()
    {
        return [
            'label' => Translate::t('elements.items.metas.items.comments', $this->textdomain),
        ];
    }

    /**
     * Get Divider options
     *
     * @return array
     */
    protected function getOptionDivider()
    {
        return [
            'label'    => Translate::t('elements.items.divider', $this->textdomain),
            'clonable' => true,
        ];
    }

    /**
     * Get Excerpt options
     *
     * @return array
     */
    protected function getOptionExcerpt()
    {
        return [
            'label'   => Translate::t('elements.items.excerpt', $this->textdomain),
            'options' => [
                'source' => [
                    'label'   => false,
                    'type'    => 'choice',
                    'divider' => 'bottom',
                    'value'   => 'excerpt',
                    'choices' => [
                        'excerpt' => Translate::t('elements.items.excerpt.source.excerpt', $this->textdomain),
                        'full'    => Translate::t('elements.items.excerpt.source.full', $this->textdomain),
                    ],
                ],
                'length' => [
                    'label'   => Translate::t('elements.items.excerpt.length', $this->textdomain),
                    'type'    => 'number',
                    'display' => 'inline',
                    'value'   => 40,
                    'min'     => 1,
                    'max'     => 300,
                ],
            ],
        ];
    }

    /**
     * Get Metas options
     *
     * @return array
     */
    protected function getOptionMetas()
    {
        return [
            'label'    => Translate::t('elements.items.metas', $this->textdomain),
            'clonable' => true,
            'options'  => [
                'items' => [
                    'label'   => false,
                    'type'    => 'elements',
                    'divider' => 'bottom',
                    'items'   => [
                        'author'     => $this->getOptionAuthor(),
                        'comments'   => $this->getOptionComments(),
                        'postdate'   => $this->getOptionPostdate(),
                        'updatedate' => $this->getOptionUpdatedate(),
                        'taxonomy'   => $this->getOptionTaxonomy(),
                    ],
                ],
                'style' => [
                    'label'   => Translate::t('elements.items.metas.style', $this->textdomain),
                    'type'    => 'choice',
                    'divider' => 'bottom',
                    'value'   => 'simple',
                    'choices' => [
                        'simple' => Translate::t('elements.items.metas.style.simple', $this->textdomain),
                        'labels' => Translate::t('elements.items.metas.style.labels', $this->textdomain),
                        'icons'  => Translate::t('elements.items.metas.style.icons', $this->textdomain),
                    ],
                ],
                'separator' => [
                    'label'   => Translate::t('elements.items.metas.separator', $this->textdomain),
                    'type'    => 'choice',
                    'value'   => 'none',
                    'choices' => [
                        'none'  => Translate::t('elements.items.metas.separator.none', $this->textdomain),
                        'slash' => Translate::t('elements.items.metas.separator.slash', $this->textdomain),
                        'dash'  => Translate::t('elements.items.metas.separator.dash', $this->textdomain),
                        'dot'   => Translate::t('elements.items.metas.separator.dot', $this->textdomain),
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Postdate options
     *
     * @return array
     */
    protected function getOptionPostdate()
    {
        return [
            'label' => Translate::t('elements.items.metas.items.postdate', $this->textdomain),
        ];
    }

    /**
     * Get ReadMore options
     *
     * @return array
     */
    protected function getOptionReadMore()
    {
        return [
            'label'   => Translate::t('elements.items.readmore', $this->textdomain),
            'options' => [
                'style' => [
                    'label'   => false,
                    'type'    => 'choice',
                    'divider' => 'bottom',
                    'value'   => 'background',
                    'choices' => [
                        'simple'     => Translate::t('elements.items.readmore.style.simple', $this->textdomain),
                        'background' => Translate::t('elements.items.readmore.style.background', $this->textdomain),
                        'outline'    => Translate::t('elements.items.readmore.style.outline', $this->textdomain),
                    ],
                ],
                'label' => [
                    'label'   => Translate::t('elements.items.readmore.label', $this->textdomain),
                    'type'    => 'text',
                    'display' => 'inline',
                    'divider' => 'bottom',
                    'value'   => Translate::t('elements.items.readmore.label.value', $this->textdomain),
                ],
                'arrow' => [
                    'label'   => Translate::t('elements.items.readmore.arrow', $this->textdomain),
                    'type'    => 'toggle',
                    'display' => 'inline',
                    'divider' => 'bottom',
                    'value'   => 0,
                ],
                'alignment' => [
                    'label'   => Translate::t('elements.items.readmore.alignment', $this->textdomain),
                    'type'    => 'choice',
                    'value'   => 'left',
                    'choices' => [
                        'left'   => Translate::t('elements.items.readmore.alignment.left', $this->textdomain),
                        'center' => Translate::t('elements.items.readmore.alignment.center', $this->textdomain),
                        'right'  => Translate::t('elements.items.readmore.alignment.right', $this->textdomain),
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Taxonomy options
     *
     * @return array
     */
    protected function getOptionTaxonomy()
    {
        return [
            'label'   => Translate::t('elements.items.metas.items.taxonomy', $this->textdomain),
            'options' => [
                'tax' => [
                    'label'   => Translate::t('elements.items.metas.items.taxonomy.tax', $this->textdomain),
                    'type'    => 'select',
                    'display' => 'inline',
                    'divider' => 'bottom',
                    'value'   => 'category',
                    'choices' => [
                        'category' => Translate::t('elements.items.metas.items.taxonomy.category', $this->textdomain),
                        'tag'      => Translate::t('elements.items.metas.items.taxonomy.tag', $this->textdomain),
                    ],
                ],
                'style' => [
                    'label'   => Translate::t('elements.items.metas.items.taxonomy.style', $this->textdomain),
                    'type'    => 'select',
                    'display' => 'inline',
                    'value'   => 'category',
                    'choices' => [
                        'simple'    => Translate::t('elements.items.metas.items.taxonomy.simple', $this->textdomain),
                        'button'    => Translate::t('elements.items.metas.items.taxonomy.button', $this->textdomain),
                        'underline' => Translate::t('elements.items.metas.items.taxonomy.underline', $this->textdomain),
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Thumbnail options
     *
     * @return array
     */
    protected function getOptionThumbnail()
    {
        return [
            'label'   => Translate::t('elements.items.thumbnail', $this->textdomain),
            'options' => [
                'ratio-choice' => [
                    'label'   => false,
                    'type'    => 'choice',
                    'value'   => 'predefined',
                    'choices' => [
                        'original'   => Translate::t('elements.items.thumbnail.ratio.original', $this->textdomain),
                        'predefined' => Translate::t('elements.items.thumbnail.ratio.predefined', $this->textdomain),
                        'custom'     => Translate::t('elements.items.thumbnail.ratio.custom', $this->textdomain),
                    ],
                ],
                'ratio-original' => [
                    'label'      => false,
                    'name'       => 'ratio',
                    'type'       => 'description',
                    'divider'    => 'bottom',
                    'conditions' => [['class', 'thumbnail:ratio-choice', 'original']],
                    'content'    => Translate::t('elements.items.thumbnail.ratio.description', $this->textdomain),
                ],
                'ratio-predefined' => [
                    'label'      => false,
                    'name'       => 'ratio',
                    'type'       => 'select',
                    'divider'    => 'bottom',
                    'conditions' => [['class', 'thumbnail:ratio-choice', 'predefined']],
                    'value'      => '4/3',
                    'choices'    => [[
                        ''    => Translate::t('elements.items.thumbnail.ratio.square', $this->textdomain),
                        '1/1' => Translate::t('elements.items.thumbnail.ratio.1/1', $this->textdomain),
                    ], [
                        ''     => Translate::t('elements.items.thumbnail.ratio.landscape', $this->textdomain),
                        '4/3'  => Translate::t('elements.items.thumbnail.ratio.4/3', $this->textdomain),
                        '16/9' => Translate::t('elements.items.thumbnail.ratio.16/9', $this->textdomain),
                        '2/1'  => Translate::t('elements.items.thumbnail.ratio.2/1', $this->textdomain),
                    ], [
                        ''     => Translate::t('elements.items.thumbnail.ratio.portrait', $this->textdomain),
                        '3/4'  => Translate::t('elements.items.thumbnail.ratio.3/4', $this->textdomain),
                        '9/16' => Translate::t('elements.items.thumbnail.ratio.9/16', $this->textdomain),
                        '1/2'  => Translate::t('elements.items.thumbnail.ratio.1/2', $this->textdomain),
                    ]],
                ],
                'ratio-custom' => [
                    'label'      => false,
                    'name'       => 'ratio',
                    'type'       => 'text',
                    'divider'    => 'bottom',
                    'conditions' => [['class', 'thumbnail:ratio-choice', 'custom']],
                    'value'      => [
                        '4',
                        '3',
                    ],
                    'separator'  => ['/'],
                    'fields'     => [
                        Translate::t('elements.items.thumbnail.ratio.width', $this->textdomain),
                        Translate::t('elements.items.thumbnail.ratio.height', $this->textdomain),
                    ],
                ],
                'hover' => [
                    'label'   => Translate::t('elements.items.thumbnail.hover', $this->textdomain),
                    'type'    => 'select',
                    'display' => 'inline',
                    'divider' => 'bottom',
                    'value'   => 'none',
                    'choices' => [
                        'none'     => Translate::t('elements.items.thumbnail.hover.none', $this->textdomain),
                        'zoom-in'  => Translate::t('elements.items.thumbnail.hover.zoom-in', $this->textdomain),
                        'zoom-out' => Translate::t('elements.items.thumbnail.hover.zoom-out', $this->textdomain),
                    ],
                ],
                'size' => [
                    'label'   => Translate::t('elements.items.thumbnail.size', $this->textdomain),
                    'type'    => 'select',
                    'display' => 'inline',
                    'divider' => 'bottom',
                    'value'   => 'medium_large',
                    'choices' => $this->getImageSizes(),
                ],
                'paddings' => [
                    'label'   => Translate::t('elements.items.thumbnail.paddings', $this->textdomain),
                    'type'    => 'toggle',
                    'display' => 'inline',
                    'value'   => 0,
                ],
            ],
        ];
    }

    /**
     * Get Title options
     *
     * @return array
     */
    protected function getOptionTitle()
    {
        return [
            'label'   => Translate::t('elements.items.title', $this->textdomain),
            'options' => [
                'tag' => [
                    'label'   => Translate::t('elements.items.title.heading.tag', $this->textdomain),
                    'type'    => 'select',
                    'display' => 'inline',
                    'value'   => 'h2',
                    'choices' => [
                        'h1'  => Translate::t('elements.items.title.heading.tag.h1', $this->textdomain),
                        'h2'  => Translate::t('elements.items.title.heading.tag.h2', $this->textdomain),
                        'h3'  => Translate::t('elements.items.title.heading.tag.h3', $this->textdomain),
                        'h4'  => Translate::t('elements.items.title.heading.tag.h4', $this->textdomain),
                        'h5'  => Translate::t('elements.items.title.heading.tag.h5', $this->textdomain),
                        'h6'  => Translate::t('elements.items.title.heading.tag.h6', $this->textdomain),
                        'div' => Translate::t('elements.items.title.heading.tag.div', $this->textdomain),
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Updatedate options
     *
     * @return array
     */
    protected function getOptionUpdatedate()
    {
        return [
            'label' => Translate::t('elements.items.metas.items.updatedate', $this->textdomain),
        ];
    }

    /**
     * Set variables from defaults
     */
    protected function setVariables()
    {
        $this->default_items = $this->getDefaultItems();

        // Build items
        $items = is_array($this->items) ? $this->items : (empty($this->items) ? [] : [$this->items]);
        $items = empty($items) ? $this->default_items : $items;
        $this->items = [];

        foreach ($items as $key => $item) {
            if (!array_key_exists($key, $this->default_items)) {
                continue;
            }

            $key = array_key_exists($key, $this->items) ? bin2hex(random_bytes(10)) : $key;
            $this->items[$key] = $this->default_items[$key];
        }

        $this->items = empty($this->items) ? $this->default_items : $this->items;
    }
}
