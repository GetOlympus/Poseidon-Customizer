<?php

// Vars
$base_vars = [
    'attrs'   => [],
    'class'   => '',
    'content' => '',
    'id'      => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

$vars['id']    = empty($vars['id']) ? '' : ' id="'.$vars['id'].'"';
$vars['class'] = empty($vars['class']) ? '' : ' '.$vars['class'];

$vars['attrs'] = empty($vars['attrs']) ? '' : ' '.implode(' ', array_map(
    function ($v, $k) {
        return sprintf('%s="%s"', $k, $v);
    },
    $vars['attrs'],
    array_keys($vars['attrs'])
));

echo sprintf(
    '<main%s class="pos-c-body%s"%s>%s</main>',
    $vars['id'],
    !empty($vars['class']) ? ' '.$vars['class'] : '',
    !empty($vars['attrs']) ? ' '.$vars['attrs'] : '',
    $vars['content'],
);

unset($base_vars);
unset($vars);
