<?php

// Blocks
$base_blocks = [
    'header' => false,
    'body'   => false,
    'footer' => false,
];

$blocks = isset($blocks) ? array_merge($base_blocks, $blocks) : $base_blocks;

// Main css
$blocks['css'] = 'accordion-section control-section poseidon-section {{ data.type }} pos-s-wrap';

?>

<li id="accordion-section-{{ data.id }}" class="<?php echo $blocks['css'] ?>" data-divider="{{ data.divider }}">
    <?php echo $blocks['header'] !== false ? $blocks['header'] : '' ?>
    <?php echo $blocks['body']   !== false ? $blocks['body'] : '' ?>
    <?php echo $blocks['footer'] !== false ? $blocks['footer'] : '' ?>
</li>

<?php

unset($base_blocks);
unset($blocks);
