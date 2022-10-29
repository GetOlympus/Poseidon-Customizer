<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'id'          => '',
    'number'      => 8,
    'palettes'    => [],
    'prefix'      => '',
    'value'       => [],
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

$vars['current'] = isset($vars['value']['colors']) && is_array($vars['value']['colors'])
    ? $vars['value']
    : $vars['palettes'][0];

$vars['style'] = [];

?>

<header class="pos-c-header">
    <label class="pos-c-title">
        <?php echo $vars['title'] ?>
    </label>

    <?php echo $vars['revert'] ?>
    <?php echo $vars['options'] ?>
</header>

<main class="pos-c-body">
    <input type="hidden" name="<?php echo $vars['id'] ?>[id]" value="<?php echo $vars['current']['id'] ?>" />

    <nav class="colors">
        <?php foreach ($vars['current']['colors'] as $i => $color) : ?>
            <div class="pos-c-tooltip" style="background-color:<?php echo $color ?>">
                <?php
                    echo sprintf(
                        '<input type="hidden" name="%s[colors][%d]" value="%s" />',
                        $vars['id'],
                        $i,
                        $color
                    );

                    $vars['style'][] = sprintf(
                        '--%s-%d:%s',
                        $vars['prefix'],
                        $i,
                        $color
                    );
                ?>
                <span class="tooltip"><?php echo $color ?></span>
            </div>
        <?php endforeach ?>
    </nav>

    <button class=""><span class="dashicons dashicons-arrow-down-alt2"></span></button>
</main>

<div class="dropdown"></div>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<style id="<?php echo $vars['id'] ?>-styles" data-prefix="<?php echo $vars['prefix'] ?>">
    :root {<?php echo implode(';', $vars['style']) ?>}
</style>

<?php

unset($base_vars);
unset($vars);
