<?php
defined('B_PROLOG_INCLUDED') or die();

$aMenu = array(
    array(
        'parent_menu' => 'global_menu_content',
        'sort' => 500,
        'text' => GetMessage('EMPLOYEES_MENU_TITLE'),
        'title' => GetMessage('EMPLOYEES_MENU_TITLE'),
        'url' => 'employees_list.php?lang=' . LANGUAGE_ID,
        'more_url' => array(
            'employees_list.php',
            'employees_edit.php'
        ),
        'items_id' => 'menu_employees'
    )
);

return $aMenu;
?>
