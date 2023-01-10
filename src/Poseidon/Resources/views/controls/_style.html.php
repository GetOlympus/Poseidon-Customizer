<?php

// Vars
$base_vars = [
    'selector' => ':root',
    'content'  => '',
    'id'       => '',
    'property' => '',
    'value'    => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

echo sprintf(
    '<style%s>%s {%s: "%s"}%s</style>',
    !empty($vars['id']) ? ' id="style-'.$vars['id'].'"' : '',
    $vars['selector'],
    $vars['property'],
    $vars['value'],
    $vars['content'],
);

unset($base_vars);
unset($vars);
