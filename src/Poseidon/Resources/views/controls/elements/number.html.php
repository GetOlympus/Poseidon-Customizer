<?php

$min   = isset($configs['option']['min']) ? $configs['option']['min'] : 0;
$max   = isset($configs['option']['max']) ? $configs['option']['max'] : 100;
$step  = isset($configs['option']['step']) ? $configs['option']['step'] : 1;

$id = bin2hex(random_bytes(10));

$ctn = sprintf(
    '<div id="%s" class="pos-number %s"><button class="minus">-</button>%s<button class="plus">+</button></div>',
    $id,
    'force-width',
    sprintf(
        '<input type="%s" name="%s" value="%s" min="%s" max="%s" step="%s" />',
        'number',
        $configs['name'],
        $configs['value'],
        $min,
        $max,
        $step,
    ),
);

$ctn .= sprintf(
    '
<script>
(function ($) {
    const _id = "%s";

    $("#" + _id).poseidonNumber({
        input: "input[type=\'number\']",
        minus: "button.minus",
        plus: "button.plus",
    });
})(window.jQuery);
</script>
    ',
    $id,
);

return $ctn;
