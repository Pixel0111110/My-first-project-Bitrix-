<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

// Закомментируйте загрузку сообщений
// Loc::loadMessages(__FILE__);
// Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/employees/lang/ru/install/index.php');

Loader::includeModule('employees');

$request = Context::getCurrent()->getRequest();
$connection = Application::getConnection();
$tableName = 'b_employees';

$ID = (int)$request->get('ID');
$isEdit = $ID > 0;
$error = '';
$success = '';

// Данные сотрудника
$employee = [
    'NAME' => '',
    'POSITION' => '',
    'DEPARTMENT' => '',
    'EMAIL' => '',
    'PHONE' => '',
    'SALARY' => ''
];

// Загружаем данные если редактирование
if ($isEdit) {
    $result = $connection->query("SELECT * FROM {$tableName} WHERE ID = {$ID}");
    $employee = $result->fetch();
    if (!$employee) {  
        $error = 'Сотрудник не найден'; // Прямой текст
        $isEdit = false;
    }
} 

// Обработка формы
if ($request->isPost() && check_bitrix_sessid()) {
    $fields = [
        'NAME' => trim($request->getPost('NAME')),
        'POSITION' => trim($request->getPost('POSITION')),
        'DEPARTMENT' => trim($request->getPost('DEPARTMENT')),
        'EMAIL' => trim($request->getPost('EMAIL')),
        'PHONE' => trim($request->getPost('PHONE')),
        'SALARY' => (float)str_replace(',', '.', $request->getPost('SALARY')),
        'DATE_UPDATE' => new \Bitrix\Main\Type\DateTime()
    ];

    // Валидация
    if (empty($fields['NAME'])) {
        $error = 'Не заполнено поле "ФИО"';
    } elseif (empty($fields['POSITION'])) {
        $error = 'Не заполнено поле "Должность"';
    } else {
        try {
            if ($isEdit) {
                // Обновление
                $connection->queryUpdate($tableName, $fields, "WHERE ID = {$ID}");
                $success = 'Данные сотрудника успешно обновлены';
                
                // Логирование 
                \Employees\EventHandlers::logAction($ID, 'UPDATE', 'Данные сотрудника обновлены');
            } else {
                // Добавление
                $fields['DATE_CREATE'] = new \Bitrix\Main\Type\DateTime();
                $fields['CREATED_BY'] = $GLOBALS['USER']->GetID();
                
                $result = $connection->queryInsert($tableName, $fields);
                $ID = $connection->getInsertedId();
                $success = 'Сотрудник успешно добавлен';
                $isEdit = true;
                
                // Логирование
                \Employees\EventHandlers::logAction($ID, 'ADD', 'Сотрудник добавлен');
            }
                
            // Перезагружаем данные
            if ($isEdit) {
                $result = $connection->query("SELECT * FROM {$tableName} WHERE ID = {$ID}");
                $employee = $result->fetch();
            }
        } catch (Exception $e) {
            $error = 'Ошибка сохранения: ' . $e->getMessage();
        }
    }
}               
            
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
?>
                
<div class="adm-detail-content">
    <div class="adm-toolbar">   
        <div class="adm-toolbar-left">
            <div class="adm-toolbar-title">
                <?= $isEdit ? 'Редактирование сотрудника' : 'Добавление сотрудника' ?>
            </div>
        </div>
        <div class="adm-toolbar-right">
            <a href="employees_list.php?lang=<?= LANG ?>" class="adm-btn">
                Вернуться к списку
            </a>
        </div>
    </div>
            
    <?php if ($error): ?>
        <div class="adm-info-message-wrap adm-info-message-red">
            <div class="adm-info-message">
                <div class="adm-info-message-title"><?= $error ?></div>
            </div>
        </div>
    <?php endif; ?>
        
    <?php if ($success): ?>
        <div class="adm-info-message-wrap adm-info-message-green">
            <div class="adm-info-message">
                <div class="adm-info-message-title"><?= $success ?></div>
            </div>
        </div>
    <?php endif; ?>
        
    <form method="POST" class="adm-detail-form">
        <?= bitrix_sessid_post() ?>
        <div class="adm-detail-tabs">
            <div class="adm-detail-tab active" data-tab="main">
                Основные
            </div>
        </div>
    
        <div class="adm-detail-content-wrap">
            <div class="adm-detail-content-block">
                <div class="adm-detail-row">
                    <div class="adm-detail-label">
                        ФИО *
                    </div>
                    <div class="adm-detail-value">
                        <input type="text"
                               name="NAME"
                               value="<?= htmlspecialcharsbx($employee['NAME']) ?>"
                               class="adm-input"
                               required
                               size="50">   
                    </div>
                </div>
                    
                <div class="adm-detail-row">
                    <div class="adm-detail-label">
                        Должность *
                    </div>
                    <div class="adm-detail-value">
                        <input type="text"
                               name="POSITION"
                               value="<?= htmlspecialcharsbx($employee['POSITION']) ?>"
                               class="adm-input"
                               required
                               size="50">   
                    </div>
                </div>
                    
                <div class="adm-detail-row">
                    <div class="adm-detail-label">
                        Отдел
                    </div>
                    <div class="adm-detail-value">
                        <input type="text"
                               name="DEPARTMENT"
                               value="<?= htmlspecialcharsbx($employee['DEPARTMENT']) ?>"
                               class="adm-input"
                               size="30">
                    </div>
                </div>
                        
                <div class="adm-detail-row">
                    <div class="adm-detail-label">
                        Email
                    </div>
                    <div class="adm-detail-value">
                        <input type="email"
                               name="EMAIL"
                               value="<?= htmlspecialcharsbx($employee['EMAIL']) ?>"
                               class="adm-input"
                               size="30">
                    </div>
                </div>
                        
                <div class="adm-detail-row">
                    <div class="adm-detail-label">
                        Телефон
                    </div>
                    <div class="adm-detail-value">
                        <input type="text"
                               name="PHONE"
                               value="<?= htmlspecialcharsbx($employee['PHONE']) ?>"
                               class="adm-input"
                               size="20">
                    </div>
                </div>
                        
                <div class="adm-detail-row">
                    <div class="adm-detail-label">
                        Зарплата
                    </div>
                    <div class="adm-detail-value">
                        <input type="number"
                               name="SALARY"
                               value="<?= htmlspecialcharsbx($employee['SALARY']) ?>"
                               class="adm-input"
                               step="0.01"
                               min="0"
                               size="10">
                    </div>
                </div>
            </div>
        </div>
                               
        <div class="adm-detail-content-buttons">
            <button type="submit" class="adm-btn adm-btn-save">
                <?= $isEdit ? 'Сохранить' : 'Добавить' ?>
            </button>
                               
            <?php if ($isEdit): ?>
                <a href="employees_list.php?action=delete&ID=<?= $ID ?>&<?= bitrix_sessid_get() ?>"
                   class="adm-btn adm-btn-delete"
                   onclick="return confirm('Вы уверены, что хотите удалить сотрудника?')">
                    Удалить
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>                         
            
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
?>
