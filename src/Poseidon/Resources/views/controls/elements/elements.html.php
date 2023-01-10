<?php

if (empty($configs['items'])) {
    return '';
}

$ctn = '';

// Iterate on items
foreach ($configs['items'] as $key => $item) {
    $active = isset($configs['value'][$key]['display']) && 1 == $configs['value'][$key]['display'];

    $ctn .= sprintf(
        '<div id="%s" class="sort-item closed%s">',
        $configs['id'].'-'.$key,
        $active ? '' : ' disabled',
    );

    // Header part
    $ctn .= sprintf(
        '<header class="sort-header">%s<h2>%s</h2>%s%s%s</header>',
        sprintf(
            '<label class="sort-action sort-display"><input type="%s" name="%s[%s][%s]" value="1"%s /> %s%s</label>',
            'checkbox',
            $configs['id'],
            $key,
            'display',
            $active ? ' checked="checked"' : '',
            $configs['icons']['show'],
            $configs['icons']['hide'],
        ),
        $item['label'],
        !isset($item['clonable']) || true !== $item['clonable'] ? '' : sprintf(
            '<span class="sort-action sort-clone">%s</span>',
            $configs['icons']['clone'],
        ),
        !isset($item['options']) ? '' : sprintf(
            '<span class="sort-action sort-toggle">%s</span>',
            $configs['icons']['toggle'],
        ),
        sprintf(
            '<span class="sort-action sort-move">%s</span>',
            $configs['icons']['move'],
        ),
    );

    // Main part
    if (isset($item['options'])) {
        $ctn .= '<main class="sort-main">';

        // Iterate on options
        foreach ($item['options'] as $k => $option) {
            $opts = [
                'display' => isset($option['display']) ? $option['display'] : 'block',
                'divider' => isset($option['divider']) ? $option['divider'] : 'none',
                'icons'   => $configs['icons'],
                'id'      => sprintf('%s-%s-%s', $configs['id'], $key, $k),
                'items'   => isset($option['items']) ? $option['items'] : [],
                'name'    => isset($option['name']) ? $option['name'] : sprintf('%s[%s][%s]', $configs['id'], $key, $k),
                'option'  => $option,
                'value'   => isset($option['value']) ? $option['value'] : '',
            ];

            $ctn .= sprintf(
                '<div class="sort-option %s" data-display="%s" data-divider="%s">',
                $key.'-'.$k,
                $opts['display'],
                $opts['divider'],
            );

            if (false !== $option['label']) {
                $ctn .= sprintf(
                    '<h3>%s</h3>',
                    $option['label']
                );
            }

            if (!in_array($option['type'], $templates)) {
                $ctn .= sprintf(
                    $unknown,
                    $option['type'],
                    implode('</code>, <code>', $templates)
                );
            } else {
                $ctn .= $this->displayContent($option['type'], $opts);
            }

            $ctn .= '</div>';
        }

        $ctn .= '</main>';
    }

    $ctn .= '</div>';
}

return '<div class="pos-c-sortable">'.$ctn.'</div>';
