<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'id'          => '',
    'fonts'       => [],
    'prefix'      => '',
    'value'       => '',
    'optgroup'    => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

?>

<header class="pos-c-header">
    <label class="pos-c-title">
        <?php echo $vars['title'] ?>
    </label>

    <?php echo $vars['revert'] ?>
    <?php echo $vars['options'] ?>
</header>

<main id="<?php echo $vars['id'] ?>" class="pos-c-body">
    <input type="hidden" name="<?php echo $vars['id'] ?>[prefix]" value="<?php echo $vars['prefix'] ?>" />

    <select name="<?php echo $vars['id'] ?>[font]">
        <option value="">-</option>
        <?php
            foreach ($vars['fonts'] as $groupfont => $fonts) {
                echo sprintf(
                    '<optgroup label="%s" data-type="%s">',
                    array_values($fonts)[0],
                    $groupfont
                );

                foreach ($fonts as $value => $label) {
                    if (empty($value)) {
                        continue;
                    }

                    $vars['optgroup'] = $value === $vars['value'] ? $groupfont : $vars['optgroup'];

                    echo sprintf(
                        '<option value="%s"%s>%s</option>',
                        $value,
                        $value === $vars['value'] ? ' selected' : '',
                        $label
                    );
                }

                echo '</optgroup>';
            }
        ?>
    </select>

    <style>:root{<?php echo $vars['prefix'] ?>:'<?php echo $vars['value'] ?>'}</style>
    <p style="font-family:var(<?php echo $vars['prefix'] ?>);">Whereas recognition of the inherent dignity</p>

    <?php if (!empty($vars['value']) && !empty($vars['optgroup'])) : ?>
        <link href="https://fonts.googleapis.com/css2?family=<?php echo $vars['value'] ?>&display=swap" rel="stylesheet"/>
    <?php endif ?>
</main>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<script>
(function ($) {
    const _id      = '<?php echo $vars['id'] ?>',
        $container = $('#' + _id),
        $hidden    = $container.find('input'),
        $select    = $container.find('select'),
        $style     = $container.find('style');

    // select change event
    $select.on('change', function (e) {
        const $selected = $select.find(':selected'),
            _type  = $selected.parent('optgroup').attr('data-type'),
            $link  = $container.find('link');

        let _text  = $selected.text(),
            _value = $selected.val();

        if ($link.length) {
            $link.remove();
        }

        if (_text === '') {
            return;
        }

        _value = '' == _value ? '-apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif' : _value;

        if ('googlefonts' === _type) {
            const _font = 'https://fonts.googleapis.com/css2?family=%s&display=swap';
            $container.append('<link href="' + _font.replace('%s', _value) + '" rel="stylesheet" />');
        }

        _text = _text.indexOf(' ') > -1 ? '\'' + _text + '\'' : _text;
        $style.html(':root{' + $hidden.prop('value') + ':' + _text + '}');
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
