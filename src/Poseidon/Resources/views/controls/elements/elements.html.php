<?php

if (empty($items)) {
    return;
}

?>

<div class="pos-c-sortable">
    <?php
        foreach ($items as $key => $item) :
            $active = isset($value[$key]['display']) && 1 == $value[$key]['display'];
    ?>
        <div id="<?php echo $id.'-'.$key ?>" class="sort-item closed<?php echo $active ? '' : ' disabled' ?>">
            <header class="sort-header">
                <label class="sort-action sort-display">
                    <?php echo sprintf(
                        '<input type="%s" name="%s[%s][%s]" value="1"%s />',
                        'checkbox',
                        $id,
                        $key,
                        'display',
                        $active ? ' checked="checked"' : ''
                    ) ?>

                    <?php echo $vars['icons']['show'] ?>
                    <?php echo $vars['icons']['hide'] ?>
                </label>

                <h2><?php echo $item['label'] ?></h2>

                <?php if (isset($item['clonable']) && true === $item['clonable']) : ?>
                    <span class="sort-action sort-clone"><?php echo $vars['icons']['clone'] ?></span>
                <?php endif ?>

                <?php if (isset($item['options'])) : ?>
                    <span class="sort-action sort-toggle"><?php echo $vars['icons']['toggle'] ?></span>
                <?php endif ?>

                <span class="sort-action sort-move"><?php echo $vars['icons']['move'] ?></span>
            </header>

            <?php if (isset($item['options'])) : ?>
                <main class="sort-main">
                    <?php
                        foreach ($item['options'] as $k => $option) {
                            $display = isset($option['display']) ? $option['display'] : 'block';
                            $divider = isset($option['divider']) ? $option['divider'] : 'none';
                            $value   = isset($option['value']) ? $option['value'] : '';
                            $items   = isset($option['items']) ? $option['items'] : [];
                            $name    = isset($option['name']) ? $option['name'] : sprintf(
                                '%s[%s][%s]',
                                $id,
                                $key,
                                $k
                            );
                            $id      = sprintf(
                                '%s-%s-%s',
                                $id,
                                $key,
                                $k
                            );

                            echo sprintf(
                                '<div class="sort-option %s" data-display="%s" data-divider="%s">',
                                $key.'-'.$k,
                                $display,
                                $divider,
                            );

                            if (false !== $option['label']) {
                                echo sprintf(
                                    '<h3>%s</h3>',
                                    $option['label']
                                );
                            }

                            if (!in_array($option['type'], $vars['tpls'])) {
                                echo sprintf(
                                    $vars['unknown'],
                                    $option['type'],
                                    implode('</code>, <code>', $vars['tpls'])
                                );
                            } else {
                                include __DIR__.S.$option['type'].'.html.php';
                            }

                            echo '</div>';
                        }
                    ?>
                </main>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</div>
