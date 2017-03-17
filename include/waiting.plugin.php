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
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package
 * @since
 * @author       XOOPS Development Team,
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS Root Path not defined');

$moduleDirName = basename(dirname(__DIR__));

eval('

function b_waiting_' . $moduleDirName . '(){
    return b_waiting_APCal_base( "' . $moduleDirName . '" ) ;
}

');

if (!function_exists('b_waiting_APCal_base')) {
    /**
     * @param $moduleDirName
     * @return array
     */
    function b_waiting_APCal_base($moduleDirName)
    {
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $block   = array();

        // get $mydirnumber
        if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
            echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
        }
        $mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

        $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_event") . ' WHERE admission<1 AND (rrule_pid=0 OR rrule_pid=id)');
        if ($result) {
            $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/admission.php";
            list($block['pendingnum']) = $GLOBALS['xoopsDB']->fetchRow($result);
            $block['lang_linkname'] = _PI_WAITING_EVENTS;
        }

        return $block;
    }
}
