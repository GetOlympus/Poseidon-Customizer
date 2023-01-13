<?php

// Vars
$base_vars = [
    'class'   => '',
    'content' => '',
    'devices' => false,
    'icon'    => '',
    'label'   => '',
    'revert'  => false,
];

$vars = isset($vars) ? array_merge($base_vars, $vars) : $base_vars;

?>

<header class="pos-c-header<?php echo !empty($vars['class']) ? ' '.$vars['class'] : '' ?>">
    <?php echo $vars['icon'] ?>

    <?php if (!empty($vars['label'])) : ?>
        <label class="pos-c-title">
            <?php echo $vars['label'] ?>
        </label>
    <?php endif ?>

    <?php if ($vars['revert']) : ?>
        <button type="button" disabled class="pos-c-revert"></button>
    <?php endif ?>

    <?php if ($vars['devices']) : ?>
        <ul class="pos-c-options">
            <li class="desktop">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
            </li>
            <li class="tablet">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 002.25-2.25v-15a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 4.5v15a2.25 2.25 0 002.25 2.25z" /></svg>
            </li>
            <li class="mobile">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
            </li>
        </ul>
    <?php endif ?>

    <?php echo $vars['content'] ?>
</header>

<?php

unset($base_vars);
unset($vars);
