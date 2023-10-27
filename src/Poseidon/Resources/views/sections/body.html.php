<?php

// Vars
$base_vars = [
    'class'   => '',
    'content' => '',
    'divider' => '',
    'id'      => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

echo sprintf(
    '<li id="%s" class="%s" data-divider="%s">%s</li>',
    sprintf(
        'accordion-section-%s',
        !empty($vars['id']) ? $vars['id'] : '{{ data.id }}',
    ),
    sprintf(
        'accordion-section control-section poseidon-section pos-s-wrap %s',
        !empty($vars['class']) ? $vars['class'] : '{{ data.type }}',
    ),
    '{{ data.divider }}',
    $vars['content'],
);

unset($base_vars);
unset($vars);
