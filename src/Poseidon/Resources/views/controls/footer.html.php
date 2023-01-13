<?php

// Vars
$base_vars = [
    'class'   => '',
    'content' => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

echo sprintf(
    '<footer class="pos-c-footer%s">%s</footer>',
    !empty($vars['class']) ? ' '.$vars['class'] : '',
    $vars['content'],
);

unset($base_vars);
unset($vars);
