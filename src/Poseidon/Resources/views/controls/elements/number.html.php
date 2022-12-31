<?php

$min   = isset($option['min']) ? $option['min'] : 0;
$max   = isset($option['max']) ? $option['max'] : 100;
$step  = isset($option['step']) ? $option['step'] : 1;
$input = sprintf(
    '<input type="%s" name="%s" value="%s" min="%s" max="%s" step="%s" />',
    'number',
    $name,
    $value,
    $min,
    $max,
    $step,
);

$id = bin2hex(random_bytes(10));

?>

<div id="<?php echo $id ?>" class="pos-number force-width">
    <button class="minus">-</button>
    <?php echo $input ?>
    <button class="plus">+</button>
</div>

<script>
(function ($) {
    const _id = '<?php echo $id ?>';

    $('#' + _id).poseidonNumber({
        input: 'input[type="number"]',
        minus: 'button.minus',
        plus: 'button.plus',
    });
})(window.jQuery);
</script>
