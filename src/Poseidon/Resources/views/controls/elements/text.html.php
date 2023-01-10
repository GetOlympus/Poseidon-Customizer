<?php

$ctn    = '';
$script = false;

if (!is_array($configs['value'])) {
    $ctn = sprintf(
        '<input type="text" name="%s" value="%s" />',
        $configs['name'],
        $configs['value'],
    );
} else {
    $script = true;

    $separators = isset($configs['option']['separator']) ? $configs['option']['separator'] : ['/'];
    $fields     = isset($configs['option']['fields']) ? $configs['option']['fields'] : [];
    $output     = '';

    foreach ($configs['value'] as $k => $val) {
        $ctn .= sprintf(
            '<div><input type="text" value="%s" placeholder="%s" /></div>',
            $val,
            isset($fields[$k]) ? $fields[$k] : '',
        );

        $ctn    .= isset($separators[$k]) ? '<span>'.$separators[$k].'</span>' : '';
        $output .= '_OUT_'.(isset($separators[$k]) ? $separators[$k] : '');
    }

    $ctn = sprintf(
        '<input type="hidden" id="%s" name="%s" value="%s" data-output="%s" /><div class="pos-text">%s</div>',
        $configs['id'],
        $configs['name'],
        implode(array_values($separators)[0], $configs['value']),
        $output,
        $ctn,
    );
}

if (true === $script) {
    $ctn .= sprintf(
        '
<script>
(function ($) {
    const _id  = "%s",
        $input = $("#" + _id),
        $texts = $input.parent().find("input[type=\'text\']"),
        output = $input.attr("data-output");

    const updateValues = function () {
        let value = output;

        $.each($texts, function (idx, el) {
            value = value.replace("_OUT_", el.value);
        });

        $input.val(value);
    };

    $input.parent().find("input[type=\'text\']").on("input", function (e) {
        updateValues();
    });
})(window.jQuery);
</script>
        ',
        $configs['id'],
    );
}

return $ctn;
