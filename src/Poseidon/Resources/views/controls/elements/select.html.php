<?php

$ctn = sprintf(
    '<select name="%s">',
    $configs['name'],
);

foreach ($configs['option']['choices'] as $val => $label) {
    if (!is_array($label)) {
        $ctn .= sprintf(
            '<option value="%s"%s>%s</option>',
            $val,
            $val == $configs['value'] ? ' selected' : '',
            $label
        );

        continue;
    }

    $ctn .= sprintf(
        '<optgroup label="%s">',
        array_values($label)[0],
    );

    foreach ($label as $v => $l) {
        if (empty($v)) {
            continue;
        }

        $ctn .= sprintf(
            '<option value="%s"%s>%s</option>',
            $v,
            $v == $configs['value'] ? ' selected' : '',
            $l
        );
    }

    $ctn .= '</optgroup>';
}

$ctn .= '</select>';

return $ctn;
