<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'choices'     => [],
    'hidden'      => '',
    'mode'        => '',
    'tooltip'     => false,
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

?>

<?php if (!empty($vars['title'])) : ?>
    <header class="pos-c-header">
        <label class="pos-c-title">
            <?php echo $vars['title'] ?>
        </label>

        <?php echo $vars['revert'] ?>
        <?php echo $vars['options'] ?>
    </header>
<?php endif ?>

<?php if (!empty($vars['choices'])) : ?>
    <?php echo $vars['hidden'] ?>

    <main class="pos-c-body <?php echo $vars['mode'] ?>">
        <?php foreach ($vars['choices'] as $label) : ?>
            <span class="customize-inside-control-row <?php echo $vars['tooltip'] ? 'pos-c-tooltip' : '' ?>">
                <?php echo $label['field'] ?>

                <label for="<?php echo $label['for'] ?>">
                    <?php echo $label['label'] ?>
                </label>
            </span>
        <?php endforeach ?>
    </main>
<?php endif ?>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<?php

unset($base_vars);
unset($vars);
