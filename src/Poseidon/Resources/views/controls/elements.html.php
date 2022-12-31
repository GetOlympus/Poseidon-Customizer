<?php

// Vars
$base_vars = [
    'title'       => '',
    'revert'      => '<button type="button" disabled class="pos-c-revert"></button>',
    'options'     => '',
    'description' => '',
    'id'          => '',
    'items'       => [],
    'values'      => [],
    'unknown'     => '',
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

$vars['icons'] = include __DIR__.S.'elements'.S.'_icons.php';
$vars['tpls']  = ['choice', 'description', 'elements', 'number', 'select', 'text', 'toggle'];

?>

<header class="pos-c-header">
    <label class="pos-c-title">
        <?php echo $vars['title'] ?>
    </label>

    <?php echo $vars['revert'] ?>
    <?php echo $vars['options'] ?>
</header>

<input type="hidden" name="<?php echo $vars['id'] ?>" value="" />

<main id="<?php echo $vars['id'] ?>" class="pos-c-body">
    <?php
        $display = 'block';
        $id      = $vars['id'];
        $items   = $vars['items'];
        $name    = $vars['id'];
        $value   = $vars['values'];

        include __DIR__.S.'elements'.S.'elements.html.php';
    ?>
</main>

<?php if (!empty($vars['description'])) : ?>
    <footer class="pos-c-footer">
        <?php echo $vars['description'] ?>
    </footer>
<?php endif ?>

<script>
(function ($) {
    const _closed = 'closed',
        _disabled = 'disabled',
        $sortable = $('.pos-c-sortable');

    $sortable.sortable({
        axis: 'y',
        cursor: 'grabbing',
        handle: 'span.sort-move',
        items: '> div.sort-item',
    });

    const $display = $sortable.find('.sort-display');
    $display.on('click', function (e) {
        e.stopPropagation();

        const $current = $(e.currentTarget),
            $item      = $current.closest('.sort-item'),
            _checked   = $current.find('input[type="checkbox"]').prop('checked');

        if (_checked) {
            $item.removeClass(_disabled);
        } else {
            $item.addClass(_disabled);
            $item.addClass(_closed);
        }
    });

    const $toggle = $sortable.find('.sort-toggle');
    $toggle.off().on('click', function (e) {
        e.stopPropagation();

        const $item = $(e.currentTarget).closest('.sort-item');

        if ($item.hasClass(_disabled)) {
            return;
        }

        if ($item.hasClass(_closed)) {
            $item.removeClass(_closed);
        } else {
            $item.addClass(_closed);
        }
    });
})(window.jQuery);
</script>

<?php

unset($base_vars);
unset($vars);
