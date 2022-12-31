<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'current'     => [],
    'id'          => '',
    'configs'     => [],
    'labels'      => [],
    'number'      => 8,
    'palettes'    => [],
    'prefix'      => '',
    'styles'      => [],
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
    <input type="hidden" name="<?php echo $vars['id'] ?>[palette]" value="<?php echo $vars['current']['palette'] ?>" />
    <input type="hidden" name="<?php echo $vars['id'] ?>[prefix]" value="<?php echo $vars['prefix'] ?>" />

    <nav class="colors">
        <?php foreach ($vars['current']['colors'] as $i => $color) : ?>
            <?php echo sprintf(
                '<div id="%s" class="pos-c-tooltip pos-c-colorpicker" style="color:%s" data-css-var="%s">%s%s%s</div>',
                $vars['id'].'-'.$i,
                $color,
                sprintf(
                    '%s-%d',
                    $vars['prefix'],
                    $i + 1
                ),
                sprintf(
                    '<input type="text" name="%s[colors][%d]" value="%s" />',
                    $vars['id'],
                    $i + 1,
                    $color
                ),
                sprintf(
                    '<span class="tooltip">%s<br/>%s</span>',
                    $i > 7 ? $vars['labels']['title'] : $vars['labels']['color'.($i + 1)],
                    $color
                ),
                sprintf(
                    '<style>:root{%s:%s}</style>',
                    sprintf(
                        '%s-%d',
                        $vars['prefix'],
                        $i + 1
                    ),
                    $color
                )
            ) ?>
        <?php endforeach ?>
    </nav>

    <a href="#dropdown" class="action" data-dropdown="<?php echo $vars['id'] ?>-dropdown">
        <span class="dashicons dashicons-arrow-down-alt2"></span>
    </a>

    <aside id="<?php echo $vars['id'] ?>-aside" class="pos-c-aside"></aside>

    <aside id="<?php echo $vars['id'] ?>-dropdown" class="pos-c-dropdown palettes">
        <?php
            foreach ($vars['palettes'] as $p => $palette) :
                $checked = $vars['current']['palette'] == $palette['palette'] ? ' checked' : '';
        ?>
            <div class="palette<?php echo $checked ?>" data-id="<?php echo $palette['palette'] ?>">
                <h4><?php echo sprintf($vars['labels']['title'], $p + 1) ?></h4>

                <nav class="colors">
                    <?php foreach ($palette['colors'] as $i => $color) : ?>
                        <?php echo sprintf(
                            '<div class="pos-c-colorpicker" style="color:%s" data-css-var="%s" data-color="%s"></div>',
                            $color,
                            sprintf(
                                '%s-%d',
                                $vars['prefix'],
                                $i + 1
                            ),
                            $color
                        ) ?>
                    <?php endforeach ?>
                </nav>
            </div>
        <?php endforeach ?>
    </aside>
</main>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<script>
(function ($) {
    const _id   = '<?php echo $vars['id'] ?>',
        options = <?php echo json_encode($vars['configs']) ?>;

    // main contents
    const $parent = $('#' + _id + '-body'),
        $style    = $('#' + _id + '-styles'),
        $palettes = $('#' + _id + '-dropdown'),
        $colors   = $parent.find('> .colors > div');

    // update options
    options.container = '#' + _id + '-aside';
    options.inline    = true;
    options.onMouseUp = function (color, picker) {
        const cssvar = picker.$el.attr('data-css-var');
        picker.$el.find('style').html(':root{' + cssvar + ':' + color + '}');
    };

    /**
     * Color Picker
     */
    $.each($colors, function (idx, elt) {
        const $self = $(elt);
        options.defaultColor = $self.find('input').attr('value');
        $self.poseidonColorPicker(options);
    });

    /**
     * Dropdown
     */
    $parent.find('a.action').poseidonDropdown({fog: false});

    /**
     * Palettes
     */
    $palettes.find('div.palette').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $self    = $(e.currentTarget),
            $current = $palettes.find('div.palette.checked');

        if ($current.length) {
            $current.removeClass('checked');
        }

        $self.addClass('checked');

        // update color values
        $parent.find('> input[name="' + _id + '[palette]"]').attr('value', $self.attr('data-id'));

        // update color styles
        $.each($self.find('> .colors > div'), function (idx, elt) {
            const $elt  = $(elt),
                $target = $($colors[idx]);

            const color = $elt.attr('data-color'),
                cssvar  = $target.attr('data-css-var');

            $target.css({color: color});
            $target.find('input').attr('value', color);
            $target.find('style').html(':root{' + cssvar + ':' + color + '}');
        });

        // close dropdown
        $palettes.removeClass('opened');
        $parent.find('a.action').removeClass('opened');
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
