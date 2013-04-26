<?php

require_once '../../../mainfile.php';

$xoopsLogger->activated = false;
restore_error_handler();
error_reporting(0);

if($xoopsDB->queryF("DELETE FROM {$xoopsDB->prefix('apcal_pictures')} WHERE event_id={$_GET['e']} AND id={$_GET['p']}"))
{
    $count = $xoopsDB->queryF("SELECT COUNT(id) AS count FROM {$xoopsDB->prefix('apcal_pictures')} WHERE event_id={$_GET['e']} AND main_pic=0");
    
    echo $xoopsDB->fetchObject($count)->count;
}
else
{
     echo -1;
}

?>