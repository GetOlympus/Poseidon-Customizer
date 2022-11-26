<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'id'          => '',
    'colors'      => [],
    'configs'     => [],
    'value'       => [],
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

<main id="<?php echo $vars['id'] ?>-body" class="pos-c-body">
    <?php foreach ($vars['colors'] as $i => $color) : ?>
        <?php echo sprintf(
            '<div id="%s" class="pos-c-tooltip pos-c-colorpicker" style="color:%s" data-picker>%s%s</div>',
            $vars['id'].'-'.$i,
            $color['color'],
            sprintf(
                '<input type="text" name="%s[%d]" value="%s" />',
                $vars['id'],
                $i,
                $color['color']
            ),
            sprintf(
                '<span class="tooltip">%s</span>',
                $color['label']
            )
        ) ?>
    <?php endforeach ?>
</main>

<aside id="<?php echo $vars['id'] ?>-aside" class="pos-c-aside"></aside>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<script>
(function ($) {
    const _id   = '<?php echo $vars['id'] ?>',
        options = <?php echo json_encode($vars['configs']) ?>;

    // update options
    options.container = '#' + _id + '-aside';
    options.inline    = true;

    // color picker events
    $.each($('#' + _id + '-body div[data-picker]'), function (idx, elt) {
        const $self = $(elt);
        options.defaultColor = $self.find('input').attr('value');
        $self.poseidonColorPicker(options);
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
