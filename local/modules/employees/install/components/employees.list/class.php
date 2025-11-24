<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

class EmployeesListComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['CACHE_TIME'] = isset($arParams['CACHE_TIME']) ? $arParams['CACHE_TIME'] : 3600;
        $arParams['DEPARTMENT_FILTER'] = $arParams['DEPARTMENT_FILTER'] ?? '';
        
        return $arParams;
    }

    public function executeComponent()
    {
        try {
            if (!Loader::includeModule('employees')) {
                throw new Exception('Модуль employees не установлен');
            }

            if ($this->startResultCache()) {
                $this->getEmployees();
                $this->includeComponentTemplate();
            }
        } catch (Exception $e) {
            $this->abortResultCache();
            ShowError($e->getMessage());
        }
    }

    private function getEmployees()
    {
        $connection = Application::getConnection();
        $tableName = 'b_employees';
        
        $where = '';
        if (!empty($this->arParams['DEPARTMENT_FILTER'])) {
            $department = $connection->getSqlHelper()->forSql($this->arParams['DEPARTMENT_FILTER']);
            $where = "WHERE DEPARTMENT = '{$department}'";
        }
        
        $result = $connection->query("
            SELECT * FROM {$tableName} 
            {$where} 
            ORDER BY NAME ASC
        ");
        
        $this->arResult['EMPLOYEES'] = [];
        while ($row = $result->fetch()) {
            $this->arResult['EMPLOYEES'][] = $row;
        }
        
        $this->arResult['COUNT'] = count($this->arResult['EMPLOYEES']);
    }
}
?>
