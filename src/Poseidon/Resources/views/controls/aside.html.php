<?php

// Vars
$base_vars = [
    'content' => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

echo $vars['content'];

unset($base_vars);
unset($vars);
