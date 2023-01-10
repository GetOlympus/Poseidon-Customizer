<?php

$ctn = '';

// Iterate on configs
foreach ($configs['option']['choices'] as $val => $label) {
    $ctn .= sprintf(
        '<span class="customize-inside-control-row"><input type="radio" name="%s" id="%s" value="%s"%s />%s</span>',
        $configs['name'],
        $configs['id'].'-'.$val,
        $val,
        $val == $configs['value'] ? ' checked="checked"' : '',
        sprintf(
            '<label for="%s">%s</label>',
            $configs['id'].'-'.$val,
            $label,
        ),
    );
}

return '<div class="pos-choice group">'.$ctn.'</div>';
