<?php

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$mod_path = XOOPS_ROOT_PATH . '/modules/' . basename(__DIR__);
if (!class_exists('APCal')) {
    require_once($mod_path . '/class/APCal.php');
}
$cal = new APCal('', $xoopsConfig['language'], true);
include($mod_path . '/include/read_configs.php');

if ($cal->enabletellafriend) {
    require_once XOOPS_ROOT_PATH . '/header.php';

    $verify = false;
    if (isset($_POST['to'])) {
        xoops_load('captcha');
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if ($xoopsCaptcha->verify() && ($xoopsUser || isset($_POST['xoopscaptcha']))) {
            $verify = true;
        }
    } else {
        $verify = true;
    }

    if (isset($_POST['to']) && $verify) {
        $headers = 'From: ' . $_POST['from'] . "\r\n";

        mail($_POST['to'], $_POST['subject'], $_POST['message'], $headers);
        echo '<script type="text/javascript">window.close();</script>';
    } else {
        $captcha = new XoopsFormCaptcha('', 'xoopscaptcha');

        $tpl = new XoopsTpl();

        $tpl->assign('title', $_GET['title']);
        $tpl->assign('url', $_GET['url']);
        $tpl->assign('from', (isset($xoopsUser) && $xoopsUser != null ? $xoopsUser->getVar('email') : ''));
        $tpl->assign('captcha', $captcha->render());
        $tpl->assign('captchaMsg', !$verify && $xoopsCaptcha ? $xoopsCaptcha->getMessage() : '');
        $tpl->assign('vars', $_POST);

        echo $tpl->fetch(XOOPS_ROOT_PATH . '/modules/APCal/templates/apcal_tellafriend.tpl');
    }

    require_once XOOPS_ROOT_PATH . '/footer.php';
}
