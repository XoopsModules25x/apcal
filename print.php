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
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';

error_reporting(0);
$xoopsLogger->activated = false;

// for "Duplicatable"
$moduleDirName = basename(__DIR__);
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

$conn = $GLOBALS['xoopsDB']->conn;

// setting physical & virtual paths
$mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
$mod_url  = XOOPS_URL . "/modules/$moduleDirName";

// ���饹������ɤ߹���
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

// Include our module's language file
if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/' . $xoopsConfig['language'] . '/main.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/' . $xoopsConfig['language'] . '/main.php';
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/english/main.php';
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/english/modinfo.php';
}

$myts = MyTextSanitizer::getInstance();

if ($_GET['op'] == 'exportxls') {
    header("Content-type: application/vnd-ms-excel; charset=' . _CHARSET");
    // Defines the name of the export file "codelution-export.xls"
    header("Content-Disposition: attachment; filename=download.xls");
} else {
    header('Content-Type:text/html; charset=' . _CHARSET);
}
$tpl = new XoopsTpl();
$tpl->xoops_setTemplateDir(XOOPS_ROOT_PATH . '/themes');
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(0);

$tpl->assign('for_print', true);

$tpl->assign('charset', _CHARSET);
$tpl->assign('sitename', $xoopsConfig['sitename']);
$tpl->assign('site_url', XOOPS_URL);

$tpl->assign('lang_comesfrom', sprintf(_MB_APCAL_COMESFROM, $xoopsConfig['sitename']));

// �ڡ���ɽ����Ϣ�ν���ʬ��
if (!empty($_GET['event_id'])) {
    $tpl->assign('contents', $cal->get_schedule_view_html(true));
} else {
    switch ($_GET['smode']) {
        case 'ro_list':
            $tpl->assign('for_event_list', false);
            if (!empty($_REQUEST['eventid'])) {
                $eventid   = \Xmf\Request::getInt('eventid');
                $summary   = \Xmf\Request::getString('summary', '');
                $date      = \Xmf\Request::getString('date');
                $location  = \Xmf\Request::getString('location', '');
                $classname = '';

                $query = 'SELECT '
                    . $GLOBALS['xoopsDB']->prefix('users')
                    . '.uname, '
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                    . '.* FROM '
                    . $GLOBALS['xoopsDB']->prefix('users')
                    . ' RIGHT JOIN '
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                    . ' ON '
                    . $GLOBALS['xoopsDB']->prefix('users')
                    . '.uid = '
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                    . '.rom_submitter WHERE ((('
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                    . ".rom_eventid)=$eventid)) ORDER BY "
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                    . '.rom_date_created';

                $res      = $GLOBALS['xoopsDB']->query($query);
                $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

                if ($num_rows == 0) {
                    $ret = _APCAL_RO_NOMEMBERS;
                } else {
                    $ret .= "<table><tr><td colspan='5'>$summary</td></tr><tr><td colspan='5'>$date</td></tr><tr><td colspan='5'>$location</td></tr></table>";
                    $ret .= "
                        <table class='ro_table-'>
                            <tr>
                                <th width='100px' class='listeheader'>&nbsp;</th>
                                <th width='100px' class='listeheader'>" . _APCAL_RO_UNAME . "</th>
                                <th width='100px' class='listeheader'>" . _APCAL_RO_FIRSTNAME . "</th>
                                <th width='100px' class='listeheader'>" . _APCAL_RO_LASTNAME . "</th>
                                <th class='listeheader'>" . _APCAL_RO_EMAIL . '</th>';
                                if ($cal->ro_extrainfo1 !== '') {
                                    $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo1 . '</th>';
                                }
                                if ($cal->ro_extrainfo2 !== '') {
                                    $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo2 . '</th>';
                                }
                                if ($cal->ro_extrainfo3 !== '') {
                                    $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo3 . '</th>';
                                }
                                if ($cal->ro_extrainfo4 !== '') {
                                    $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo4 . '</th>';
                                }
                                if ($cal->ro_extrainfo5 !== '') {
                                    $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo5 . '</th>';
                                }
                                $ret .= "<th class='listeheader'>" . _APCAL_RO_STATUS . "</th>";
                    $ret .= '</tr>';
                    $counter = 0;
                    $line    = 0;
                    while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                        $rom_id = $member->rom_id;
                        $uname = $member->uname;
                        $firstname = $member->rom_firstname;
                        $lastname = $member->rom_lastname;
                        $email = $member->rom_email;
                        $extrainfo1 = $member->rom_extrainfo1;
                        $extrainfo2 = $member->rom_extrainfo2;
                        $extrainfo3 = $member->rom_extrainfo3;
                        $extrainfo4 = $member->rom_extrainfo4;
                        $extrainfo5 = $member->rom_extrainfo5;
                        $status = (int)$member->rom_status;
                        if ($line == 0) {
                            $classname = 'odd';
                            $line = 1;
                        } else {
                            $classname = 'even';
                            $line = 0;
                        }
                        $counter++;
                        $ret .= "<tr>
                    <td class='$classname'>$counter</td>
                    <td class='$classname'>$uname</td>
                    <td class='$classname'>$firstname</td>
                    <td class='$classname'>$lastname</td>
                    <td class='$classname'>$email</td>";
                        if ($cal->ro_extrainfo1 !== '') {
                            $ret .= "<td class='$classname'>$extrainfo1</td>";
                        }
                        if ($cal->ro_extrainfo2 !== '') {
                            $ret .= "<td class='$classname'>$extrainfo2</td>";
                        }
                        if ($cal->ro_extrainfo3 !== '') {
                            $ret .= "<td class='$classname'>$extrainfo3</td>";
                        }
                        if ($cal->ro_extrainfo4 !== '') {
                            $ret .= "<td class='$classname'>$extrainfo4</td>";
                        }
                        if ($cal->ro_extrainfo5 !== '') {
                            $ret .= "<td class='$classname'>$extrainfo5</td>";
                        }
                        $ret .= "<td class='$classname' style='text-align:center'>";
                        $unique_id = uniqid(mt_rand());
                        $ret .= "<div style='display:inline;'>";
                        if ($status == 1) {
                            $ret .= _APCAL_RO_STATUS_PENDING;
                        } else if ($status == 2) {
                            $ret .= _APCAL_RO_STATUS_LIST;
                        } else {
                            $ret .= _APCAL_RO_STATUS_OK;
                        }
                        $ret .= "</div>";
                        $ret .= '</td></tr>';
                    }
                    $ret .= "</table>\n<br>";
                }
            }

            $tpl->assign('contents', $ret);
            break;
        case 'Yearly':
            $tpl->assign('for_event_list', false);
            $tpl->assign('contents', $cal->get_yearly('', '', true));
            break;
        case 'Weekly':
            $tpl->assign('for_event_list', false);
            $tpl->assign('contents', $cal->get_weekly('', '', true));
            break;
        case 'Daily':
            $tpl->assign('for_event_list', false);
            $tpl->assign('contents', $cal->get_daily('', '', true));
            break;
        case 'List':
            $tpl->assign('for_event_list', true);
            $cal->assign_event_list($tpl);
            break;
        case 'Monthly':
        default:
            $tpl->assign('for_event_list', false);
            $tpl->assign('contents', $cal->get_monthly('', '', true));
            break;
    }
}

echo '<link rel="stylesheet" type="text/css" href="' . XOOPS_URL . '/modules/apcal/assets/css/apcal.css" />';
$tpl->display('db:apcal_print.tpl');
