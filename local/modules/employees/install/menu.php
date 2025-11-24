<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$aMenu = [
    [
        'parent_menu' => 'global_menu_content',
        'sort' => 300,
        'text' => 'Сотрудники',
        'title' => 'Управление сотрудниками компании',
        'url' => 'employees_list.php?lang=' . LANGUAGE_ID,
        'items_id' => 'menu_employees',
        'module_id' => 'employees',
        'items' => []
    ]
];

return $aMenu;
?>
