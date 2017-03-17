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
 * @package      AM Reviews
 * @since
 * @author       XOOPS Development Team, GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

include __DIR__ . '/../../mainfile.php';

// for "Duplicatable"
$moduleDirName = basename(__DIR__);
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

// MySQL¤Ø¤ÎÀÜÂ³
$conn = $GLOBALS['xoopsDB']->conn;

// setting physical & virtual paths
$mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName ";
$mod_url  = XOOPS_URL . "/modules/$moduleDirName ";

// ¥¯¥é¥¹ÄêµÁ¤ÎÆÉ¤ß¹þ¤ß
if (!class_exists('APCal_xoops')) {
    require_once "$mod_path/class/APCal.php";
    require_once "$mod_path/class/APCal_xoops.php";
}

// creating an instance of APCal
$cal = new APCal_xoops('', $xoopsConfig['language'], true);

// setting properties of APCal
$cal->conn = $conn;
include "$mod_path/include/read_configs.php";
$cal->base_url    = $mod_url;
$cal->base_path   = $mod_path;
$cal->images_url  = "$mod_url/assets/images/$skin_folder";
$cal->images_path = "$mod_path/assets/images/$skin_folder";

$event_id = empty($_GET['com_itemid']) ? 0 : (int)$_GET['com_itemid'];
if ($event_id > 0) {
    $rs = $GLOBALS['xoopsDB']->query("SELECT summary,rrule_pid FROM $cal->table WHERE id=$event_id");
    list($title, $rrule_pid) = $GLOBALS['xoopsDB']->fetchRow($rs);
    $com_replytitle = $title;

    // RRULE events
    if ($rrule_pid != 0) {
        $_GET['com_itemid']          = $rrule_pid;
        $HTTP_GET_VARS['com_itemid'] = $rrule_pid;
    }

    include XOOPS_ROOT_PATH . '/include/comment_new.php';
}
