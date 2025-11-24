<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Loader::includeModule('employees');

$request = Context::getCurrent()->getRequest();
$connection = Application::getConnection();
$tableName = 'b_employees';

// Обработка действий
if ($request->isPost() && check_bitrix_sessid()) {
    if ($request->getPost('action') == 'delete' && $request->getPost('ID')) {
        $id = (int)$request->getPost('ID');
        $connection->queryExecute("DELETE FROM {$tableName} WHERE ID = {$id}");
        
        // Логирование
        \Employees\EventHandlers::logAction($id, 'DELETE', 'Сотрудник удален');
    }
}

// Получение списка сотрудников
$employees = [];
$result = $connection->query("SELECT * FROM {$tableName} ORDER BY ID DESC");
while ($row = $result->fetch()) {
    $employees[] = $row;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
?>

<div class="adm-detail-content">
    <div class="adm-toolbar">
        <div class="adm-toolbar-left">
            <div class="adm-toolbar-title"><?= Loc::getMessage('EMPLOYEES_LIST_TITLE') ?></div>
        </div>
        <div class="adm-toolbar-right">
            <a href="employees_edit.php?lang=<?= LANG ?>" class="adm-btn adm-btn-add">
                <?= Loc::getMessage('EMPLOYEES_ADD_BUTTON') ?>
            </a>
        </div>
    </div>

    <div class="adm-list-table">
        <table class="adm-list-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= Loc::getMessage('EMPLOYEES_NAME') ?></th>
                    <th><?= Loc::getMessage('EMPLOYEES_POSITION') ?></th>
                    <th><?= Loc::getMessage('EMPLOYEES_DEPARTMENT') ?></th>
                    <th><?= Loc::getMessage('EMPLOYEES_EMAIL') ?></th>
                    <th><?= Loc::getMessage('EMPLOYEES_ACTIONS') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= $employee['ID'] ?></td>
                    <td><?= htmlspecialcharsbx($employee['NAME']) ?></td>
                    <td><?= htmlspecialcharsbx($employee['POSITION']) ?></td>
                    <td><?= htmlspecialcharsbx($employee['DEPARTMENT']) ?></td>
                    <td><?= htmlspecialcharsbx($employee['EMAIL']) ?></td>
                    <td>
                        <a href="employees_edit.php?ID=<?= $employee['ID'] ?>&lang=<?= LANG ?>" 
                           class="adm-btn adm-btn-edit">
                            <?= Loc::getMessage('EMPLOYEES_EDIT_BUTTON') ?>
                        </a>
                        <form method="POST" style="display: inline-block; margin-left: 5px;">
                            <?= bitrix_sessid_post() ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="ID" value="<?= $employee['ID'] ?>">
                            <button type="submit" class="adm-btn adm-btn-delete" 
                                    onclick="return confirm('<?= Loc::getMessage('EMPLOYEES_DELETE_CONFIRM') ?>')">
                                <?= Loc::getMessage('EMPLOYEES_DELETE_BUTTON') ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        <?= Loc::getMessage('EMPLOYEES_NO_ITEMS') ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
?>
