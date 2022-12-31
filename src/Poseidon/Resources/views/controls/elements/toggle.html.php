<?php

echo sprintf(
    '<input type="%s" name="%s" id="%s" value="1"%s%s />%s',
    'checkbox',
    $name,
    $id,
    ' class="pos-toggle-checkbox"',
    1 === $value ? ' checked="checked"' : '',
    sprintf(
        '<label for="%s" class="%s">%s</label>',
        $id,
        'pos-toggle',
        '<span></span>'
    )
);
