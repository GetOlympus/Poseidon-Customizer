<?php

// Vars
$base_vars = [
    'content' => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

$vars['echo'] = '<script' === substr($vars['content'], 0, 7) ? '%s' : '<script>%s</script>';

echo sprintf(
    $vars['echo'],
    $vars['content'],
);

unset($base_vars);
unset($vars);
