<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'current'     => [],
    'id'          => '',
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
    <input type="hidden" name="<?php echo $vars['id'] ?>[id]" value="<?php echo $vars['current']['id'] ?>" />

    <nav class="colors">
        <?php foreach ($vars['current']['colors'] as $i => $color) : ?>
            <div class="pos-c-tooltip" style="background-color:<?php echo $color ?>">
                <?php
                    echo sprintf(
                        '<input type="hidden" name="%s[colors][%d]" value="%s" />',
                        $vars['id'],
                        $i + 1,
                        $color
                    );
                ?>
                <span class="tooltip"><?php echo $color ?></span>
            </div>
        <?php endforeach ?>
    </nav>

    <a href="#dropdown" class="action" data-dropdown="<?php echo $vars['id'] ?>-dropdown">
        <span class="dashicons dashicons-arrow-down-alt2"></span>
    </a>

    <aside id="<?php echo $vars['id'] ?>-dropdown" class="pos-c-dropdown palettes">
        <?php foreach ($vars['palettes'] as $p => $palette) : ?>
            <div class="palette<?php echo $vars['current']['id'] == $palette['id'] ? ' checked' : '' ?>">
                <h4>Palette <?php echo $p + 1 ?></h4>

                <nav class="colors" data-id="<?php echo $palette['id'] ?>">
                    <?php
                        foreach ($palette['colors'] as $i => $color) {
                            echo sprintf(
                                '<div style="background-color:%s" data-var="%s" data-color="%s"></div>',
                                $color,
                                sprintf(
                                    '--%s-%d:%s',
                                    $vars['prefix'],
                                    $i + 1,
                                    $color
                                ),
                                $color
                            );
                        }
                    ?>
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

<style id="<?php echo $vars['id'] ?>-styles" data-prefix="<?php echo $vars['prefix'] ?>">
    :root {<?php echo implode(';', $vars['styles']) ?>}
</style>

<script>
(function ($) {
    var $parent   = $('#<?php echo $vars['id'] ?>-body'),
        $colors   = $parent.find('> .colors > div'),
        $palettes = $('#<?php echo $vars['id'] ?>-dropdown'),
        $style    = $('#<?php echo $vars['id'] ?>-styles');

    // dropdown events
    $parent.find('a.action').poseidonDropdown({fog: false});

    // palettes events
    $palettes.find('div.palette').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $self    = $(this),
            $current = $palettes.find('div.palette.checked');

        if ($current.length) {
            $current.removeClass('checked');
        }

        $self.addClass('checked');

        // update hidden values
        $parent.find('> input[type="hidden"]').attr('value', $self.find('.colors').attr('data-id'));

        // works on colors
        var $new_colors = $self.find('> .colors > div'),
            _new_styles = '';

        $.each($new_colors, function (idx, elt) {
            var $color = $(elt);

            $($colors[idx]).find('input').attr('value', $color.attr('data-color'));
            $($colors[idx]).css({
                backgroundColor: $color.attr('data-color')
            });

            _new_styles += $color.attr('data-var')+';';
        });

        // update CSS vars
        $style.html(':root{' + _new_styles + '}');

        // close dropdown
        $('#<?php echo $vars['id'] ?>-dropdown').removeClass('opened');
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
