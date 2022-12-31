<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',

    'id'          => '',
    'value'       => '',
    'min'         => '',
    'max'         => '',
    'step'        => '',
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

<main id="<?php echo $vars['id'] ?>" class="pos-c-body pos-number">
    <?php
        echo sprintf(
            '%s<input type="%s" name="%s" value="%s" min="%s" max="%s" step="%s" />%s',
            '<button class="minus">-</button>',
            'number',
            $vars['id'],
            $vars['value'],
            $vars['min'],
            $vars['max'],
            $vars['step'],
            '<button class="plus">+</button>'
        );
    ?>
</main>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<script>
(function ($) {
    const _id = '<?php echo $vars['id'] ?>';

    $('#' + _id).poseidonNumber({
        input: 'input[type="number"]',
        minus: 'button.minus',
        plus: 'button.plus',
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
