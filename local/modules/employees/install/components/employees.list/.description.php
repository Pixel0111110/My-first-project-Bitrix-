<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    'NAME' => Loc::getMessage('EMPLOYEES_COMPONENT_NAME'),
    'DESCRIPTION' => Loc::getMessage('EMPLOYEES_COMPONENT_DESCRIPTION'),
    'PATH' => [
        'ID' => 'employees',
        'NAME' => Loc::getMessage('EMPLOYEES_MODULE_NAME')
    ]
];
?>
