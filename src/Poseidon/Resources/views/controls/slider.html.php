<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',

    'choices'     => '',
    'number'      => 1,
    'id'          => '',
    'min'         => '',
    'max'         => '',
    'step'        => '',
    'value'       => '',
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
    <?php
        echo sprintf(
            '<input type="range" name="%s[value]" value="%s" min="%s" max="%s" step="%s" /><div>%s%s%s</div>',
            $vars['id'],
            $vars['value'],
            $vars['min'],
            $vars['max'],
            $vars['step'],
            sprintf(
                '<input type="number" value="%s" min="%s" max="%s" step="%s" />',
                $vals['value'],
                $vars['min'],
                $vars['max'],
                $vars['step']
            ),
            sprintf(
                '<select name="%s[unit]"%s>%s</select>',
                $vars['id'],
                1 >= $vars['number'] ? ' disabled' : '',
                $vars['choices']
            ),
            '<b></b>'
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

    $('#' + _id).poseidonSlider({
        number: 'input[type="number"]',
        range: 'input[type="range"]',
        select: 'select',
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
