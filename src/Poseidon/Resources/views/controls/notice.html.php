<?php

// Vars
$base_vars = [
    'status'      => 'none',
    'icon'        => '',
    'title'       => '',
    'description' => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

?>

<header class="pos-c-header <?php echo $vars['status'] ?>">
    <?php if (!empty($vars['icon'])) : ?>
        <i class="icon dashicons <?php echo $vars['icon'] ?>"></i>
    <?php endif ?>

    <label class="pos-c-title">
        <?php echo $vars['title'] ?>
    </label>
</header>

<footer class="pos-c-footer <?php echo $vars['status'] ?>">
    <?php echo $vars['description'] ?>
</footer>

<?php

unset($base_vars);
unset($vars);
