<?php

$content = '';
$script  = false;

if (!is_array($value)) {
    $content = sprintf(
        '<input type="text" name="%s" value="%s" />',
        $name,
        $value,
    );
} else {
    $script = true;

    $separators = isset($option['separator']) ? $option['separator'] : ['/'];
    $fields     = isset($option['fields']) ? $option['fields'] : [];
    $output     = '';

    foreach ($value as $k => $val) {
        $content .= sprintf(
            '<div><input type="text" value="%s" placeholder="%s" /></div>',
            $val,
            isset($fields[$k]) ? $fields[$k] : '',
        );

        $content .= isset($separators[$k]) ? '<span>'.$separators[$k].'</span>' : '';
        $output  .= '%s'.(isset($separators[$k]) ? $separators[$k] : '');
    }

    $content = sprintf(
        '<input type="hidden" id="%s" name="%s" value="%s" data-output="%s" /><div class="pos-text">%s</div>',
        $id,
        $name,
        implode(array_values($separators)[0], $value),
        $output,
        $content,
    );
}

echo $content;

?>

<?php if (true === $script) : ?>
<script>
(function ($) {
    const _id  = '<?php echo $id ?>',
        $input = $('#' + _id),
        $texts = $input.parent().find('input[type="text"]'),
        output = $input.attr('data-output');

    const updateValues = function () {
        let value = output;

        $.each($texts, function (idx, el) {
            value = value.replace('%s', el.value);
        });

        $input.val(value);
    };

    $input.parent().find('input[type="text"]').on('input', function (e) {
        updateValues();
    });
})(window.jQuery);
</script>
<?php endif ?>
