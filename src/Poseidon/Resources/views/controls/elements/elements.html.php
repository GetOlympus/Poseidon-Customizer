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
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="show"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hide"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>',
        ),
        $item['label'],
        !isset($item['clonable']) || true !== $item['clonable'] ? '' : sprintf(
            '<span class="sort-action sort-clone">%s</span>',
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="clone"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>',
        ),
        !isset($item['options']) ? '' : sprintf(
            '<span class="sort-action sort-toggle">%s</span>',
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>',
        ),
        sprintf(
            '<span class="sort-action sort-move">%s</span>',
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="move"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" /></svg>',
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
