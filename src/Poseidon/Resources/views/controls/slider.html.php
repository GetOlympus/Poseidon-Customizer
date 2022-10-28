<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',

    'choices'     => '',
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

<main class="pos-c-body">
    <?php
        echo sprintf(
            '<input type="range" id="%s" name="%s[value]" value="%s" min="%f" max="%f" step="%f" /><div>%s%s%s</div>',
                $vars['id'],
                $vars['id'],
                $vars['value'],
                $vars['min'],
                $vars['max'],
                $vars['step'],
                sprintf(
                    '<input type="number" value="%s" min="%f" max="%f" step="%f" />',
                    $vals['value'],
                    $vars['min'],
                    $vars['max'],
                    $vars['step']
                ),
                sprintf(
                    '<select name="%s[unit]">%s</select>',
                    $vars['id'],
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
    var $el     = $('#<?php echo $vars['id'] ?>').parent('.pos-c-body'),
        $range  = $el.find('input[type="range"]'),
        $number = $el.find('input[type="number"]'),
        $select = $el.find('select');

    $range.on('input', function () {
        $number.val($range.val());
    });
    $number.on('input', function () {
        $range.val($number.val());
    });
    $select.on('change', function () {
        var $option = $select.find('option:selected'),
            _min    = $option.attr('data-min'),
            _max    = $option.attr('data-max'),
            _step   = $option.attr('data-step'),
            _value  = $range.val();

        $range.attr('min', _min);
        $range.attr('max', _max);
        $range.attr('step', _step);

        $number.attr('min', _min);
        $number.attr('max', _max);
        $number.attr('step', _step);

        if (_value < _min) {
            $range.val(_min);
            $number.val(_min);
        } else if (_value > _max) {
            $range.val(_max);
            $number.val(_max);
        }
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
