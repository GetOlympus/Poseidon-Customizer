<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

// Blocks
$base_blocks = [
    'header' => false,
    'body'   => false,
    'footer' => false,
    'script' => false,
];

$blocks = isset($blocks) ? array_merge($base_blocks, $blocks) : $base_blocks;

?>

<header class="pos-c-header">
    <?php if ($blocks['header'] !== false) : ?>
        <?php echo $blocks['header'] ?>
    <?php else : ?>
        <label class="pos-c-title">
            <?php echo $vars['title'] ?>
        </label>

        <?php echo $vars['revert'] ?>
        <?php echo $vars['options'] ?>
    <?php endif ?>
</header>

<?php if ($blocks['body'] !== false) : ?>
    <main class="pos-c-body">
        <?php echo $blocks['body'] ?>
    </main>
<?php endif ?>

<?php if ($blocks['footer'] !== false || !empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php if ($blocks['footer'] !== false) : ?>
            <?php echo $blocks['footer'] ?>
        <?php else : ?>
            <?php echo $vars['description'] ?>
        <?php endif ?>
    </footer>
<?php endif ?>

<?php if ($blocks['script'] !== false) : ?>
    <script><?php echo $blocks['script'] ?></script>
<?php endif ?>

<?php

unset($base_blocks);
unset($base_vars);
unset($blocks);
unset($vars);
