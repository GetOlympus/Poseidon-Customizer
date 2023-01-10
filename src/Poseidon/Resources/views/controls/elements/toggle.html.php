<?php

$ctn = sprintf(
    '<input type="%s" name="%s" id="%s" value="1"%s%s />%s',
    'checkbox',
    $configs['name'],
    $configs['id'],
    ' class="pos-toggle-checkbox"',
    1 === $configs['value'] ? ' checked="checked"' : '',
    sprintf(
        '<label for="%s" class="%s">%s</label>',
        $configs['id'],
        'pos-toggle',
        '<span></span>'
    )
);

return $ctn;
