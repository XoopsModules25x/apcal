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
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 * @return bool
 */

if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof XoopsUser)
    || !$GLOBALS['xoopsUser']->IsAdmin()
) {
    exit('Restricted access' . PHP_EOL);
}

/**
 * @param string $tablename
 *
 * @return bool
 */
function tableExists($tablename)
{
    $result = $GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");

    return $GLOBALS['xoopsDB']->getRowsNum($result) > 0;
}

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_apcal(XoopsModule $module)
{
    $moduleDirName = basename(dirname(__DIR__));
    $classUtility  = ucfirst($moduleDirName) . 'Utility';
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

    return true;
}

function xoops_module_update_apcal(XoopsModule $module)
{
    //    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));
    $capsDirName   = strtoupper($moduleDirName);

    if (!$GLOBALS['xoopsDB']->queryF("SELECT shortsummary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')}")) {
        if ($GLOBALS['xoopsDB']->queryF("ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_event')} ADD shortsummary VARCHAR(255) AFTER groupid")) {
        }
    }
    $result = $GLOBALS['xoopsDB']->queryF("SELECT id, summary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $shortsummary = makeShort($row['summary']);
        $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} SET shortsummary='{$shortsummary}' WHERE id={$row['id']}");
    }

    if (!$GLOBALS['xoopsDB']->queryF("SELECT cat_shorttitle FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')}")) {
        if ($GLOBALS['xoopsDB']->queryF("ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_cat')} ADD cat_shorttitle VARCHAR(255) AFTER enabled")) {
        }
    }
    $result = $GLOBALS['xoopsDB']->queryF("SELECT cid, cat_title FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')}");
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $cat_shorttitle = makeShort($row['cat_title']);
        $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_cat')} SET cat_shorttitle='{$cat_shorttitle}' WHERE cid={$row['cid']}");
    }

    if (!$GLOBALS['xoopsDB']->queryF("SELECT email,url,mainCategory, otherHours FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')}")) {
        $sql = "ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_event')} ";
        $sql .= 'ADD url VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\' AFTER location,';
        $sql .= 'ADD email VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\' AFTER url,';
        $sql .= 'ADD mainCategory SMALLINT( 5 ) UNSIGNED ZEROFILL NOT NULL DEFAULT \'00000\' AFTER dtstamp,';
        $sql .= 'ADD otherHours VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\' AFTER end';
        $GLOBALS['xoopsDB']->queryF($sql);
    }

    if (!$GLOBALS['xoopsDB']->queryF("SELECT color,canbemain FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')}")) {
        $sql = "ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_cat')} ";
        $sql .= 'ADD color VARCHAR( 7 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'#5555AA\' AFTER cat_desc,';
        $sql .= 'ADD canbemain TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT \'0\' AFTER autocreated';
        $GLOBALS['xoopsDB']->queryF($sql);
    }

    $sql = "CREATE TABLE IF NOT EXISTS {$GLOBALS['xoopsDB']->prefix('apcal_pictures')} ";
    $sql .= '(id int(10) unsigned NOT NULL AUTO_INCREMENT,';
    $sql .= 'event_id int(10) unsigned zerofill NOT NULL,';
    $sql .= 'picture varchar(255) NOT NULL,';
    $sql .= 'main_pic tinyint(1) unsigned NOT NULL DEFAULT \'0\',';
    $sql .= 'PRIMARY KEY (id)) ';
    $sql .= 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    $GLOBALS['xoopsDB']->queryF($sql);

    $sql = "CREATE TABLE IF NOT EXISTS {$GLOBALS['xoopsDB']->prefix('apcal_ro_events')} (
            roe_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            roe_eventid mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
            roe_number int(10) NOT NULL DEFAULT '0',
            roe_datelimit int(10) NOT NULL DEFAULT '0',
            roe_needconfirm INT(10) NOT NULL DEFAULT '0',
            roe_waitinglist INT(10) NOT NULL DEFAULT '0',
            roe_submitter int(10) NOT NULL DEFAULT '0',
            roe_date_created int(10) NOT NULL DEFAULT '0',
            PRIMARY KEY (roe_id),
            KEY event (roe_eventid))
            ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $GLOBALS['xoopsDB']->queryF($sql);

    $sql = "CREATE TABLE IF NOT EXISTS {$GLOBALS['xoopsDB']->prefix('apcal_ro_members')} (
            rom_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            rom_eventid mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
            rom_firstname varchar(200) DEFAULT NULL,
            rom_lastname varchar(200) DEFAULT NULL,
            rom_email varchar(200) DEFAULT NULL,
            rom_extrainfo1 varchar(200) DEFAULT NULL,
            rom_extrainfo2 varchar(200) DEFAULT NULL,
            rom_extrainfo3 varchar(200) DEFAULT NULL,
            rom_extrainfo4 varchar(200) DEFAULT NULL,
            rom_extrainfo5 varchar(200) DEFAULT NULL,
            rom_poster_ip  varchar(200) DEFAULT NULL,
            rom_status int(10) NOT NULL DEFAULT '0',
            rom_submitter int(10) NOT NULL DEFAULT '0',
            rom_date_created int(10) NOT NULL DEFAULT '0',
            PRIMARY KEY (rom_id),
            UNIQUE KEY UNQ_EMAIL (rom_eventid, rom_email),
            KEY event (rom_eventid))
            ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $GLOBALS['xoopsDB']->queryF($sql);

    $sql = "CREATE TABLE IF NOT EXISTS {$GLOBALS['xoopsDB']->prefix('apcal_ro_notify')} (
            ron_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            ron_eventid mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
            ron_email varchar(200) DEFAULT NULL,
            ron_submitter int(10) DEFAULT NULL,
            ron_date_created int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (ron_id),
            KEY event (ron_eventid))
            ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $GLOBALS['xoopsDB']->queryF($sql);

    $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} SET start_date=NULL,end_date=NULL");
    $GLOBALS['xoopsDB']->queryF("UPDATE {$GLOBALS['xoopsDB']->prefix('apcal_event')} t, (SELECT id, shortsummary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')} x WHERE x.rrule_pid>0 GROUP BY x.shortsummary ORDER BY start) AS e SET t.rrule_pid=e.id WHERE t.shortsummary=e.shortsummary;");

    //    fix problem from removed poster_ip
    $sql = "ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_ro_members')} ADD `rom_status` INT(1) NOT NULL DEFAULT '0' AFTER `rom_extrainfo5`;";
    $GLOBALS['xoopsDB']->queryF($sql);
    //    fix problem from removed poster_ip
    $sql = "ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_ro_members')} ADD `rom_poster_ip` VARCHAR(200) NULL DEFAULT '' AFTER `rom_extrainfo5`;";
    $GLOBALS['xoopsDB']->queryF($sql);

    //    fix problem from removed roe_waitinglist
    $sql = "ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_ro_events')} ADD `roe_waitinglist` INT(10) NOT NULL DEFAULT '0' AFTER `roe_datelimit`;";
    $GLOBALS['xoopsDB']->queryF($sql);

    //    fix problem from removed roe_waitinglist
    $sql = "ALTER TABLE {$GLOBALS['xoopsDB']->prefix('apcal_ro_events')} ADD `roe_needconfirm` INT(10) NOT NULL DEFAULT '0' AFTER `roe_datelimit`;";
    $GLOBALS['xoopsDB']->queryF($sql);

    //    if (!is_dir(XOOPS_UPLOAD_PATH . '/apcal/')) {
    //        mkdir(XOOPS_UPLOAD_PATH . '/apcal/', 0755);
    //    }
    //    if (!is_dir(XOOPS_UPLOAD_PATH . '/apcal/thumbs/')) {
    //        mkdir(XOOPS_UPLOAD_PATH . '/apcal/thumbs/', 0755);
    //    }

    require_once __DIR__ . '/config.php';
    $configurator = new ModuleConfigurator();
    $classUtility = ucfirst($moduleDirName) . 'Utility';
    if (!class_exists($classUtility)) {
        xoops_load('utility', $moduleDirName);
    }

    //delete old HTML templates
    if (count($configurator->templateFolders) > 0) {
        foreach ($configurator->templateFolders as $folder) {
            $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
            if (is_dir($templateFolder)) {
                $templateList = array_diff(scandir($templateFolder), array('..', '.'));
                foreach ($templateList as $k => $v) {
                    $fileInfo = new SplFileInfo($templateFolder . $v);
                    if ($fileInfo->getExtension() === 'html' && $fileInfo->getFilename() !== 'index.html') {
                        if (file_exists($templateFolder . $v)) {
                            unlink($templateFolder . $v);
                        }
                    }
                }
            }
        }
    }

    //  ---  DELETE OLD FILES ---------------
    if (count($configurator->oldFiles) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->oldFiles) as $i) {
            $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFiles[$i]);
            if (is_file($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    //  ---  DELETE OLD FOLDERS ---------------
    xoops_load('XoopsFile');
    if (count($configurator->oldFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->oldFolders) as $i) {
            $tempFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFolders[$i]);
            /* @var $folderHandler XoopsObjectHandler */
            $folderHandler = XoopsFile::getHandler('folder', $tempFolder);
            $folderHandler->delete($tempFolder);
        }
    }

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
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . '\' AND `tpl_file` LIKE \'%.html%\'';
    $GLOBALS['xoopsDB']->queryF($sql);

    /** @var XoopsGroupPermHandler $gpermHandler */
    $gpermHandler = xoops_getHandler('groupperm');

    return $gpermHandler->deleteByModule($module->getVar('mid'), 'item_read');

    return true;
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
