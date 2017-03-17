<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link http://xoops.org/ XOOPS Project}
 * @license      {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package
 * @since
 * @author       XOOPS Development Team,
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 * @param $xoopsModule
 * @return bool
 */

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_apcal(XoopsModule $module)
{
    $moduleDirName = basename(dirname(__DIR__));
    $classUtility     = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($classUtility)) {
        xoops_load('utility', $moduleDirName);
    }
    //check for minimum XOOPS version
    if (!$classUtility::checkVerXoops($module)) {
        return false;
    }

    // check for minimum PHP version
    if (!$classUtility::checkVerPhp($module)) {
        return false;
    }

    $mod_tables = $module->getInfo('tables');
    foreach ($mod_tables as $table) {
        $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
    }

    return true;
}
function xoops_module_install_apcal(XoopsModule $xoopsModule)
{
    require_once  __DIR__ . '/../../../mainfile.php';
    require_once  __DIR__ . '/../include/config.php';

    if (!isset($moduleDirName)) {
        $moduleDirName = basename(dirname(__DIR__));
    }

    if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
    } else {
        $moduleHelper = Xmf\Module\Helper::getHelper('system');
    }

    // Load language files
    $moduleHelper->loadLanguage('admin');
    $moduleHelper->loadLanguage('modinfo');

    $configurator = new ModuleConfigurator();
    $classUtility    = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($classUtility)) {
        xoops_load('utility', $moduleDirName);
    }
//------------------------------------
    $ret    = true;
    $errors = transferTable('event');
    if ($errors != '') {
        $ret = false;
    }
    //echo $errors ? 'Error inserting these ids in event:<br>'.$errors : 'Insertion succesful!<br>';
    $errors = transferTable('cat');
    if ($errors != '') {
        $ret = false;
    }
    //echo $errors ? 'Error inserting these ids in cat:<br>'.$errors : 'Insertion succesful!<br>';
    $errors = transferTable('plugins');
    if ($errors != '') {
        $ret = false;
    }
    //echo $errors ? 'Error inserting these ids in plugins:<br>'.$errors : 'Insertion succesful!<br>';
    setDefaultPerm();
    makeShortEventAftertransfer();
    makeShortCatAftertransfer();

    $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} SET start_date=NULL,end_date=NULL");
    $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} t, (SELECT id, shortsummary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')} x WHERE x.rrule_pid>0 GROUP BY x.shortsummary ORDER BY start) AS e SET t.rrule_pid=e.id WHERE t.shortsummary=e.shortsummary;");

//    if (!is_dir(XOOPS_UPLOAD_PATH . '/apcal/')) {
//        mkdir(XOOPS_UPLOAD_PATH . '/apcal/', 0755);
//    }
//    if (!is_dir(XOOPS_UPLOAD_PATH . '/apcal/thumbs/')) {
//        mkdir(XOOPS_UPLOAD_PATH . '/apcal/thumbs/', 0755);
//    }

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $classUtility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->blankFiles) > 0) {
        $file = __DIR__ . '/../assets/images/blank.png';
        foreach (array_keys($configurator->blankFiles) as $i) {
            $dest = $configurator->blankFiles[$i] . '/blank.png';
            $classUtility::copyFile($file, $dest);
        }
    }
    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);

    return $ret;
}

function makeShortEventAftertransfer()
{
    $result = $GLOBALS['xoopsDB']->queryF("SELECT id, summary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $shortsummary = makeShort($row['summary']);
        $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} SET shortsummary='{$shortsummary}' WHERE id={$row['id']}");
    }
}

function makeShortCatAftertransfer()
{

    $result = $GLOBALS['xoopsDB']->queryF("SELECT cid, cat_title FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $cat_shorttitle = makeShort($row['cat_title']);
        $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_cat')} SET cat_shorttitle='{$cat_shorttitle}' WHERE cid={$row['cid']}");
    }
}

/**
 * @param $tablename
 * @return string
 */
function transferTable($tablename)
{
    $errors = '';
    $result = $GLOBALS['xoopsDB']->queryF("SELECT * FROM {$GLOBALS['xoopsDB']->prefix('pical_'.$tablename)}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $fields  = '';
        $values  = '';
        $isFirst = true;
        foreach ($row as $field => $value) {
            if ($field != 'id' && $field != 'start_date' && $field != 'end_date') {
                $fields  .= ($isFirst ? '' : ', ') . $field;
                $values  .= ($isFirst ? '' : ', ') . $GLOBALS['xoopsDB']->quote($value);
                $isFirst = false;
            }
        }

        if (!$GLOBALS['xoopsDB']->queryF("INSERT INTO {$GLOBALS['xoopsDB']->prefix('apcal_'.$tablename)}($fields) VALUES ({$values})")) {
            $errors .= '&nbsp;&nbsp;' . $row['id'] . ' => ' . $GLOBALS['xoopsDB']->error() . '<br>';
        }
    }

    return $errors;
}

function setDefaultPerm()
{
    $moduleHnd     = xoops_getHandler('module');
    $module        = $moduleHnd->getByDirname('APCal');
    $modid         = $module->getVar('mid');
    $gpermHandler = xoops_getHandler('groupperm');
    //$item_ids = array(1, 2, 4, 8, 32);

    $pical_cat    = $gpermHandler->getObjects(new Criteria('gperm_name', 'pical_cat'));
    $pical_global = $gpermHandler->getObjects(new Criteria('gperm_name', 'pical_global'));

    foreach ($pical_cat as $cat_perm) {
        $gperm = $gpermHandler->create();
        $gperm->setVar('gperm_groupid', $cat_perm->getVar('gperm_groupid'));
        $gperm->setVar('gperm_name', 'apcal_cat');
        $gperm->setVar('gperm_modid', $modid);
        $gperm->setVar('gperm_itemid', $cat_perm->getVar('gperm_itemid'));
        $gpermHandler->insert($gperm);
        unset($gperm);
    }

    foreach ($pical_global as $global_perm) {
        $gperm = $gpermHandler->create();
        $gperm->setVar('gperm_groupid', $global_perm->getVar('gperm_groupid'));
        $gperm->setVar('gperm_name', 'apcal_global');
        $gperm->setVar('gperm_modid', $modid);
        $gperm->setVar('gperm_itemid', $global_perm->getVar('gperm_itemid'));
        $gpermHandler->insert($gperm);
        unset($gperm);
    }

    /*foreach ($item_ids as $item_id) {
        $gperm = $gpermHandler->create();
        $gperm->setVar('gperm_groupid', 1);
        $gperm->setVar('gperm_name', 'apcal_global');
        $gperm->setVar('gperm_modid', $modid);
        $gperm->setVar('gperm_itemid', $item_id);
        $gpermHandler->insert($gperm);
        unset($gperm);
    }*/
}

/**
 * @param $str
 * @return mixed
 */
function makeShort($str)
{
    $replacements = array(
        'Š' => 'S',
        'š' => 's',
        'Ž' => 'Z',
        'ž' => 'z',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'A',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ø' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ý' => 'Y',
        'Þ' => 'B',
        'ß' => 'ss',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'a',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'o',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ý' => 'y',
        'ý' => 'y',
        'þ' => 'b',
        'ÿ' => 'y'
    );

    $str = strip_tags($str);
    $str = strtr($str, $replacements);

    return str_replace(array(' ', '-', '/', "\\", "'", '"', "\r", "\n", '&', '?', '!', '%', ',', '.'), '', $str);
}
