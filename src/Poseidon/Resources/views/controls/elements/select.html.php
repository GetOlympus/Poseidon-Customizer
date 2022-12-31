<select name="<?php echo $name ?>">
    <?php
        foreach ($option['choices'] as $val => $label) {
            if (!is_array($label)) {
                echo sprintf(
                    '<option value="%s"%s>%s</option>',
                    $val,
                    $val == $value ? ' selected' : '',
                    $label
                );
                continue;
            }

            echo sprintf(
                '<optgroup label="%s">',
                array_values($label)[0],
            );

            foreach ($label as $v => $l) {
                if (empty($v)) {
                    continue;
                }

                echo sprintf(
                    '<option value="%s"%s>%s</option>',
                    $v,
                    $v == $value ? ' selected' : '',
                    $l
                );
            }

            echo '</optgroup>';
        }
    ?>
</select>
