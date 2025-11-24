<?php
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

Loader::registerAutoLoadClasses('employees', [
    'Employees\EmployeesTable' => 'lib/Employeestable.php',
    'Employees\EventHandlers' => 'lib/EventHandlers.php',
]);

// Регистрация событий
$eventManager = EventManager::getInstance();
$eventManager->addEventHandler(
    'employees',
    'OnAfterEmployeeAdd',
    ['Employees\EventHandlers', 'onAfterAdd']
);
$eventManager->addEventHandler(
    'employees',
    'OnBeforeEmployeeUpdate', 
    ['Employees\EventHandlers', 'onBeforeUpdate']
);
?>
