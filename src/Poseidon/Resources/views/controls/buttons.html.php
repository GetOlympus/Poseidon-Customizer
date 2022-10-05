<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'buttons'     => [],
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

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<?php if (!empty($vars['buttons'])) : ?>
    <main class="pos-c-body">
        <?php foreach ($vars['buttons'] as $button) : ?>
            <button <?php echo $button['attrs'] ?>>
                <?php echo $button['label'] ?>
            </button>
        <?php endforeach ?>
    </main>
<?php endif ?>

<?php

unset($base_vars);
unset($vars);
