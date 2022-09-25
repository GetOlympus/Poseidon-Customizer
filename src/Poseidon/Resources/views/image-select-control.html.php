<?php

$input = sprintf('<input type="%s" name="%s" id="%s" value="%s" />',
    '{{ type }}',
    '{{ id ~ square }}',
    '{{ identifier }}',
    '{{ k }}',
    '{{ selected }}'
);

return '<div class="poseidon-simple-notice {{{ data.status }}}">
    <input type="hidden" name="{{ data.id }}" value="" {{ data.link }} />

    <# if (data.choices) { #>
        {# set num = 0 #}
        {# set square = multiple ? "[]" : "" #}
        {# set type = multiple ? "checkbox" : "radio" #}

        <div class="group col-{{ 2 == data.column ? "50" : "100" }}">
            {# for k, option in choices if option #}
                {# set identifier = id ~ "-" ~ num #}
                {# set selected = k in value ? " checked=\"checked\"" : "" #}

                <label for="{{ identifier }}">
                    <img src="{{ option.image }}" alt="" />
                    <span>'.$input.'{{ option.label|raw }}</span>
                </label>

                {# set num = num + 1 #}
            {# endfor #}
        </div>
    <# } else { #>
        {{ no_options }}
    <# } #>
</div>';
