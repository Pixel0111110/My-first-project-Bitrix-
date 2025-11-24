<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class employees extends CModule
{
    public $MODULE_ID = 'employees';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('EMPLOYEES_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('EMPLOYEES_MODULE_DESCRIPTION');
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
    }

    public function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallDB()
    {
        global $DB;
        $this->errors = false;

        $this->errors = $DB->RunSQLBatch(__DIR__ . '/db/install.sql');
        
        if (!$this->errors) {
            return true;
        } else {
            return $this->errors;
        }
    }

    public function UnInstallDB()
    {
        global $DB;
        $this->errors = false;

        $this->errors = $DB->Query("DROP TABLE IF EXISTS b_employees");
        
        if (!$this->errors) {
            return true;
        } else {
            return $this->errors;
        }
    }

    public function InstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        
        // Логирование изменений
        $eventManager->registerEventHandler(
            'employees',
            'OnBeforeEmployeeUpdate',
            'employees',
            '\Employees\EventHandlers',
            'onBeforeUpdate'
        );
        
        $eventManager->registerEventHandler(
            'employees',
            'OnAfterEmployeeAdd',
            'employees',
            '\Employees\EventHandlers',
            'onAfterAdd'
        );

        return true;
    }

    public function UnInstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler('employees', 'OnBeforeEmployeeUpdate', 'employees', '\Employees\EventHandlers', 'onBeforeUpdate');
        $eventManager->unRegisterEventHandler('employees', 'OnAfterEmployeeAdd', 'employees', '\Employees\EventHandlers', 'onAfterAdd');
        
        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin',
            true, true
        );
        
        CopyDirFiles(
            __DIR__ . '/components',
            $_SERVER['DOCUMENT_ROOT'] . '/local/components',
            true, true
        );
        
        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        DeleteDirFiles(__DIR__ . '/components', $_SERVER['DOCUMENT_ROOT'] . '/local/components');
        
        return true;
    }
}
?>
