<?php 

require_once '../../mainfile.php';

error_reporting(0);
$xoopsLogger->activated = false;

require_once XOOPS_ROOT_PATH.'/header.php';

if(isset($_POST['to']))
{
    $headers = 'From: '.$_POST['from']."\r\n";
     
    mail($_POST['to'], $_POST['subject'], $_POST['message'], $headers);
    echo '<script type="text/javascript">window.close();</script>';
}
else
{
    $tpl = new XoopsTpl();

    $tpl->assign('title', $_GET['title']);
    $tpl->assign('url', $_GET['url']);
    $tpl->assign('from', (isset($xoopsUser) && $xoopsUser != null ? $xoopsUser->getVar('email') : ''));

    echo $tpl->fetch(XOOPS_ROOT_PATH.'/modules/APCal/templates/apcal_tellafriend.html');
}

require_once XOOPS_ROOT_PATH.'/footer.php';

?>
