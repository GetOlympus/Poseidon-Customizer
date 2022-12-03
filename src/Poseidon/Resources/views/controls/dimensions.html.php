<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',

    'id'          => '',
    'dimensions'  => [],
    'lock'        => true,
    'min'         => 0,
    'max'         => 0,
    'units'       => [],
    'number'      => 1,
    'choices'     => '',
    'values'      => [],
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
    <div class="inputs">
        <?php
            foreach ($vars['dimensions'] as $dimension => $details) {
                echo sprintf(
                    '<div>%s%s</div>',
                    sprintf(
                        '<input type="number" name="%s[%s]" value="%s" min="%s" max="%s" step="1" />',
                        $vars['id'],
                        $dimension,
                        isset($vars['values'][$dimension]) ? $vars['values'][$dimension] : $details['value'],
                        $vars['min'],
                        $vars['max']
                    ),
                    sprintf(
                        '<span>%s</span>',
                        $details['label']
                    )
                );
            }
        ?>
    </div>

    <div class="configs">
        <?php
            echo !$vars['lock'] ? '' : sprintf(
                '<button class="pos-lock">%s</button>',
                '<span class="dashicons dashicons-unlock"></span>'
            );

            echo sprintf(
                '<select name="%s[unit]"%s>%s</select><b></b>',
                $vars['id'],
                1 >= $vars['number'] ? ' disabled' : '',
                $vars['choices']
            )
        ?>
    </div>
</main>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<script>
(function ($) {
    const _id = '<?php echo $vars['id'] ?>';

    $('#' + _id).poseidonDimensions({
        fields: 'input[type="number"]',
        lock: 'button.pos-lock',
        icon: {
            lock: 'dashicons-lock',
            unlock: 'dashicons-unlock',
        },
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
