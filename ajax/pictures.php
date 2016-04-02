<?php

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$xoopsLogger->activated = false;
restore_error_handler();
error_reporting(0);

if ($GLOBALS['xoopsDB']->queryF("DELETE FROM {$GLOBALS['xoopsDB']->prefix('apcal_pictures')} WHERE event_id={$_GET['e']} AND id={$_GET['p']}")) {
    $count = $GLOBALS['xoopsDB']->queryF("SELECT COUNT(id) AS count FROM {$GLOBALS['xoopsDB']->prefix('apcal_pictures')} WHERE event_id={$_GET['e']} AND main_pic=0");

    echo $GLOBALS['xoopsDB']->fetchObject($count)->count;
} else {
    echo -1;
}
