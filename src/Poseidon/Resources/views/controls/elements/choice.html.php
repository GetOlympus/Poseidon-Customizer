<div class="pos-choice group">
    <?php foreach ($option['choices'] as $val => $label) : ?>
        <span class="customize-inside-control-row">
            <?php echo sprintf(
                '<input type="radio" name="%s" id="%s" value="%s"%s />',
                $name,
                $id.'-'.$val,
                $val,
                $val == $value ? ' checked="checked"' : ''
            ); ?>

            <label for="<?php echo $id.'-'.$val ?>">
                <?php echo $label ?>
            </label>
        </span>
    <?php endforeach ?>
</div>
