<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/**
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      Antiques Promotion (http://www.antiquespromotion.ca)
 */

function xoops_module_install_APCal($xoopsModule)
{
    global $xoopsDB;

    $ret    = true;
    $errors = transferTable('event');
    if ($errors != '') {
        $ret = false;
    }
    //echo $errors ? 'Error inserting these ids in event:<br />'.$errors : 'Insertion succesful!<br />';
    $errors = transferTable('cat');
    if ($errors != '') {
        $ret = false;
    }
    //echo $errors ? 'Error inserting these ids in cat:<br />'.$errors : 'Insertion succesful!<br />';
    $errors = transferTable('plugins');
    if ($errors != '') {
        $ret = false;
    }
    //echo $errors ? 'Error inserting these ids in plugins:<br />'.$errors : 'Insertion succesful!<br />';
    setDefaultPerm();
    makeShortEventAftertransfer();
    makeShortCatAftertransfer();

    $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} SET start_date=NULL,end_date=NULL");
    $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} t, (SELECT id, shortsummary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')} x WHERE x.rrule_pid>0 GROUP BY x.shortsummary ORDER BY start) AS e SET t.rrule_pid=e.id WHERE t.shortsummary=e.shortsummary;");

    if (!is_dir(XOOPS_UPLOAD_PATH . '/APCal/')) {
        mkdir(XOOPS_UPLOAD_PATH . '/APCal/', 0755);
    }
    if (!is_dir(XOOPS_UPLOAD_PATH . '/APCal/thumbs/')) {
        mkdir(XOOPS_UPLOAD_PATH . '/APCal/thumbs/', 0755);
    }

    return $ret;
}

function makeShortEventAftertransfer()
{
    global $xoopsDB;

    $result = $GLOBALS['xoopsDB']->queryF("SELECT id, summary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $shortsummary = makeShort($row['summary']);
        $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} SET shortsummary='{$shortsummary}' WHERE id={$row['id']}");
    }
}

function makeShortCatAftertransfer()
{
    global $xoopsDB;

    $result = $GLOBALS['xoopsDB']->queryF("SELECT cid, cat_title FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $cat_shorttitle = makeShort($row['cat_title']);
        $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_cat')} SET cat_shorttitle='{$cat_shorttitle}' WHERE cid={$row['cid']}");
    }
}

function transferTable($tablename)
{
    global $xoopsDB;

    $errors = '';
    $result = $GLOBALS['xoopsDB']->queryF("SELECT * FROM {$GLOBALS['xoopsDB']->prefix('pical_'.$tablename)}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $fields  = '';
        $values  = '';
        $isFirst = true;
        foreach ($row as $field => $value) {
            if ($field != 'id' && $field != 'start_date' && $field != 'end_date') {
                $fields .= ($isFirst ? '' : ', ') . $field;
                $values .= ($isFirst ? '' : ', ') . $GLOBALS['xoopsDB']->quote($value);
                $isFirst = false;
            }
        }

        if (!$GLOBALS['xoopsDB']->queryF("INSERT INTO {$GLOBALS['xoopsDB']->prefix('apcal_'.$tablename)}($fields) VALUES ({$values})")) {
            $errors .= '&nbsp;&nbsp;' . $row['id'] . ' => ' . $GLOBALS['xoopsDB']->error() . '<br />';
        }
    }

    return $errors;
}

function setDefaultPerm()
{
    $moduleHnd     = xoops_getHandler('module');
    $module        =& $moduleHnd->getByDirname('APCal');
    $modid         = $module->getVar('mid');
    $gperm_handler = xoops_getHandler('groupperm');
    //$item_ids = array(1, 2, 4, 8, 32);

    $pical_cat    = $gperm_handler->getObjects(new Criteria('gperm_name', 'pical_cat'));
    $pical_global = $gperm_handler->getObjects(new Criteria('gperm_name', 'pical_global'));

    foreach ($pical_cat as $cat_perm) {
        $gperm =& $gperm_handler->create();
        $gperm->setVar('gperm_groupid', $cat_perm->getVar('gperm_groupid'));
        $gperm->setVar('gperm_name', 'apcal_cat');
        $gperm->setVar('gperm_modid', $modid);
        $gperm->setVar('gperm_itemid', $cat_perm->getVar('gperm_itemid'));
        $gperm_handler->insert($gperm);
        unset($gperm);
    }

    foreach ($pical_global as $global_perm) {
        $gperm =& $gperm_handler->create();
        $gperm->setVar('gperm_groupid', $global_perm->getVar('gperm_groupid'));
        $gperm->setVar('gperm_name', 'apcal_global');
        $gperm->setVar('gperm_modid', $modid);
        $gperm->setVar('gperm_itemid', $global_perm->getVar('gperm_itemid'));
        $gperm_handler->insert($gperm);
        unset($gperm);
    }

    /*foreach ($item_ids as $item_id) {
        $gperm =& $gperm_handler->create();
        $gperm->setVar('gperm_groupid', 1);
        $gperm->setVar('gperm_name', 'apcal_global');
        $gperm->setVar('gperm_modid', $modid);
        $gperm->setVar('gperm_itemid', $item_id);
        $gperm_handler->insert($gperm);
        unset($gperm);
    }*/
}

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
        'ß' => 'Ss',
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
        'ÿ' => 'y');

    $str = strip_tags($str);
    $str = strtr($str, $replacements);

    return str_replace(array(' ', '-', '/', "\\", "'", "\"", "\r", "\n", '&', '?', '!', '%', ',', '.'), '', $str);
}
