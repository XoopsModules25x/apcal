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

if (!defined('APCAL_COMMENT_FUNCTIONS_INCLUDED')) {
    define('APCAL_COMMENT_FUNCTIONS_INCLUDED', 1);

    // comment callback functions

    /**
     * @param $event_id
     * @param $total_num
     * @return mixed
     */
    function apcal_comments_update($event_id, $total_num)
    {
        // record total_num
        global $xoopsDB, $cal;

        if (is_object($cal)) {
            $tablename = $cal->table;
        } else {
            $moduleDirName = basename(dirname(__DIR__));
            if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
                echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
            }
            $mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];
            $tablename   = $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_event");
        }

        $ret = $GLOBALS['xoopsDB']->query("UPDATE $tablename SET comments=$total_num WHERE id=$event_id");

        return $ret;
    }

    /**
     * @param $comment
     */
    function apcal_comments_approve(&$comment)
    {
        // notification mail here
    }
}
