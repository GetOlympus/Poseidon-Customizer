<?php

$blocks = [
    'texts' => [
        'title' => __('Press return or enter to open this section'),
        'back'  => __('Back'),
        'help'  => __('Help'),
    ],
    'class' => sprintf(
        'accordion-section control-section %s pos-s-switch%s',
        '{{ data.type }}',
        '<# if (data.switch == 1) { #> active<# } #>'
    ),
    'input' => sprintf(
        '<input type="checkbox" name="%s" id="%s" value="%s" class="%s"%s />',
        '{{ data.id }}',
        'accordion-section-toggle-{{ data.id }}',
        '{{ data.switch ? "on" : "off" }}',
        'pos-toggle-checkbox',
        '<# if (data.switch == 1) { #> checked="checked"<# } #>'
    ),
    'label' => sprintf(
        '<label for="%s" class="%s">%s</label>',
        'accordion-section-toggle-{{ data.id }}',
        'pos-toggle',
        '<span></span>'
    ),
];

?>

<li id="accordion-section-{{ data.id }}" class="<?php echo $blocks['class'] ?>" data-divider="{{ data.divider }}">
    <?php echo $blocks['input'] ?>
    <?php echo $blocks['label'] ?>

    <h3 class="accordion-section-title" tabindex="0">
        {{ data.title }}
        <span class="screen-reader-text"><?php echo $blocks['texts']['title'] ?></span>
    </h3>

    <ul class="accordion-section-content">
        <li class="customize-section-description-container section-meta<# if (data.description_hidden) { #> customize-info<# } #>">
            <div class="customize-section-title">
                <button class="customize-section-back" tabindex="-1">
                    <span class="screen-reader-text"><?php echo $blocks['texts']['back'] ?></span>
                </button>

                <h3>
                    <span class="customize-action">{{{ data.customizeAction }}}</span>
                    {{ data.title }}
                </h3>

                <# if (data.description && data.description_hidden) { #>
                    <button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false">
                        <span class="screen-reader-text"><?php echo $blocks['texts']['help'] ?></span>
                    </button>

                    <div class="description customize-section-description">{{{ data.description }}}</div>
                <# } #>

                <div class="customize-control-notifications-container"></div>
            </div>

            <# if (data.description && ! data.description_hidden) { #>
                <div class="description customize-section-description">
                    {{{ data.description }}}
                </div>
            <# } #>
        </li>
    </ul>
</li>

<?php

unset($blocks);
