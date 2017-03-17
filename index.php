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
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

require(dirname(dirname(__DIR__)) . '/mainfile.php');
$original_level = error_reporting(E_ALL ^ E_NOTICE);

if ((!isset($_GET['action']) || $_GET['action'] == '') && isset($_GET['cid']) && !is_numeric($_GET['cid'])) {
    $cat_title = addslashes($_GET['cid']);
    $cat       = $GLOBALS['xoopsDB']->queryF("SELECT cid FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')} WHERE cat_shorttitle LIKE '$cat_title' LIMIT 0,1");

    if ($cat && mysqli_num_rows($cat)) {
        $cat         = $GLOBALS['xoopsDB']->fetchObject($cat);
        $_GET['cid'] = $cat->cid;
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'View' && !is_numeric($_GET['event_id']) && isset($_GET['date'])) {
    $summary = addslashes($_GET['event_id']);
    $date    = isset($_GET['date']) ? strtotime($_GET['date']) : time();
    $event   = $GLOBALS['xoopsDB']->queryF("SELECT id FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')} WHERE shortsummary='$summary' AND UNIX_TIMESTAMP(DATE(FROM_UNIXTIME(start)))=$date LIMIT 0,1");

    if ($event && mysqli_num_rows($event)) {
        $event            = $GLOBALS['xoopsDB']->fetchObject($event);
        $_GET['event_id'] = $event->id;
    }
}

// for "Duplicatable"
$moduleDirName = basename(__DIR__);
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

require_once(XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php");

// ����ƥ�����
// $xoopsConfig[ 'language' ] = 'french' ;

// MySQL�ؤ���³
// $conn = mysqli_connect( XOOPS_DB_HOST , XOOPS_DB_USER , XOOPS_DB_PASS ) || die( "Could not connect." ) ;
// mysqli_select_db( XOOPS_DB_NAME , $conn ) ;
$conn = $GLOBALS['xoopsDB']->conn;

// setting physical & virtual paths
$mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
$mod_url  = XOOPS_URL . "/modules/$moduleDirName";

// ���饹������ɤ߹���
if (!class_exists('APCal_xoops')) {
    require_once("$mod_path/class/APCal.php");
    require_once("$mod_path/class/APCal_xoops.php");
}

// GET,POST�ѿ��μ�����������
if (empty($_GET['action']) && !empty($_GET['event_id'])) {
    $_GET['action'] = 'View';
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}

// creating an instance of APCal
$cal = new APCal_xoops('', $xoopsConfig['language'], true);

// setting properties of APCal
$cal->conn = $conn;
include("$mod_path/include/read_configs.php");
$cal->base_url    = $mod_url;
$cal->base_path   = $mod_path;
$cal->images_url  = "$mod_url/assets/images/$skin_folder";
$cal->images_path = "$mod_path/assets/images/$skin_folder";
$cal->frame_css   = 'border-color: ' . $cal->frame_css . ';';

// �ǡ����١��������ط��ν���ʤ�����⡢Location�����Ф���
if (isset($_POST['update'])) {
    // ����
    if (!$editable) {
        die(_MB_APCAL_ERR_NOPERMTOUPDATE);
    }
    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }
    $cal->update_schedule("$admission_update_sql", $whr_sql_append);
} elseif (isset($_POST['insert']) || isset($_POST['saveas'])) {
    // saveas �ޤ��� ������Ͽ
    if (!$insertable) {
        die(_MB_APCAL_ERR_NOPERMTOINSERT);
    }
    $_POST['event_oldid'] = $_POST['event_id'];
    $_POST['event_id']    = '';
    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }
    $cal->update_schedule(",uid='$user_id' $admission_insert_sql", '', 'notify_new_event');
} elseif (!empty($_POST['delete'])) {
    // ���
    if (!$deletable) {
        die(_MB_APCAL_ERR_NOPERMTODELETE);
    }
    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }
    $cal->delete_schedule($whr_sql_append, 'global $xoopsModule; xoops_comment_delete($xoopsModule->mid(),$id);');
} elseif (!empty($_POST['delete_one'])) {
    // �����
    if (!$deletable) {
        die(_MB_APCAL_ERR_NOPERMTODELETE);
    }
    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }
    $cal->delete_schedule_one($whr_sql_append);
} elseif (!empty($_GET['output_ics']) /* || ! empty( $_POST[ 'output_ics' ] ) */) {
    // output ics
    $cal->output_ics();
}

// smode�ν���
if (!empty($_GET['smode'])) {
    $smode = $_GET['smode'];
} else {
    $smode = $default_view;
}

// XOOP�إå��������ν���
if ($action == 'View') {
    $xoopsOption['template_main'] = "apcal{$mydirnumber}_event_detail.tpl";
} else {
    // View�ʳ��Ǥϥ����ȶػ�
    $xoopsModuleConfig['com_rule'] = 0;
    if ($smode == 'List' && $action != 'Edit') {
        $xoopsOption['template_main'] = "apcal{$mydirnumber}_event_list.tpl";
    }
}

// XOOPS�إå�����
include(XOOPS_ROOT_PATH . '/header.php');
$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="' . XOOPS_URL . '/modules/APCal/assets/css/apcal.css" />' . $xoopsTpl->get_template_vars('xoops_module_header'));

// embed style sheet �ν��� (thx Ryuji)
$xoopsTpl->assign('xoops_module_header', "<style><!-- \n" . $cal->get_embed_css() . "\n--></style>\n" . $xoopsTpl->get_template_vars('xoops_module_header'));

// ���?�顼�˥�󥯤�ؤĤ餻�ʤ� follow -> nofollow
$meta_robots = str_replace(',follow', ',nofollow', $xoopsTpl->get_template_vars('xoops_meta_robots'));
$xoopsTpl->assign('xoops_meta_robots', $meta_robots);

// �¹Ի��ַ�¬��������
// list( $usec , $sec ) = explode( " " , microtime() ) ;
// $apcalstarttime = $sec + $usec ;

// �ڡ���ɽ����Ϣ�ν���ʬ��
if ($action == 'Edit') {
    if (is_dir(XOOPS_ROOT_PATH . '/modules/APCal/assets/js/jscalendar')) {
        // jscalendar in module dir (recommended)
        $jscalurl = XOOPS_URL . '/modules/APCal/assets/js/jscalendar';
        $xoopsTpl->assign('xoops_module_header', '
                <link rel="stylesheet" type="text/css" media="all" href="' . $jscalurl . '/calendar-system.css" />
                <script type="text/javascript" src="' . $jscalurl . '/calendar.js"></script>
                <script type="text/javascript" src="' . $jscalurl . '/lang/' . $cal->jscalendar_lang_file . '"></script>
                <script type="text/javascript" src="' . $jscalurl . '/calendar-setup.js"></script>
            ' . $xoopsTpl->get_template_vars('xoops_module_header'));
        $cal->jscalendar = 'jscalendar';
    } elseif (is_file(XOOPS_ROOT_PATH . '/include/calendarjs.php')) {
        // older jscalendar in XOOPS 2.0.x core
        include XOOPS_ROOT_PATH . '/include/calendarjs.php';
        $cal->jscalendar = 'xoops';
    } elseif (is_dir(XOOPS_ROOT_PATH . '/class/calendar')) {
        // jscalendar in XOOPS 2.2 core
        $jscalurl = XOOPS_URL . '/class/calendar';
        $xoopsTpl->assign('xoops_module_header', '
                <link rel="stylesheet" type="text/css" media="all" href="' . $jscalurl . '/CSS/calendar-blue.css" title="system" />
                <script type="text/javascript" src="' . $jscalurl . '/calendar.js"></script>
                <script type="text/javascript" src="' . $jscalurl . '/lang/' . $cal->jscalendar_lang_file . '"></script>
                <script type="text/javascript" src="' . $jscalurl . '/calendar-setup.js"></script>
            ' . $xoopsTpl->get_template_vars('xoops_module_header'));
        $cal->jscalendar = 'jscalendar';
    } else {
        // older jscalendar in XOOPS 2.0.x core
        include XOOPS_ROOT_PATH . '/include/calendarjs.php';
        $cal->jscalendar = 'xoops';
    }
    $xoopsTpl->assign('xoops_module_header', '<script type="text/javascript" src="' . XOOPS_URL . '/modules/APCal/ajax/pictures.js"></script>' . $xoopsTpl->get_template_vars('xoops_module_header'));
    echo $cal->get_schedule_edit_html();
} elseif ($action == 'View') {
    // echo $cal->get_schedule_view_html( ) ;
    $xoopsTpl->assign('detail_body', $cal->get_schedule_view_html());
    $xoopsTpl->assign('xoops_pagetitle', $cal->last_summary);
    $xoopsTpl->assign('xoops_default_comment_title', 'Re: ' . $cal->last_summary);
    $xoopsTpl->assign('print_link', "$mod_url/print.php?event_id={$_GET['event_id']}&amp;action=View");
    $xoopsTpl->assign('skinpath', "$cal->images_url");
    $xoopsTpl->assign('lang_print', _MB_APCAL_ALT_PRINTTHISEVENT);
    $HTTP_GET_VARS['event_id'] = $_GET['event_id'] = $cal->original_id;
    include XOOPS_ROOT_PATH . '/include/comment_view.php';
    // patch for commentAny
    $commentany = $xoopsTpl->get_template_vars('commentany');
    if (!empty($commentany['com_itemid'])) {
        $commentany['com_itemid'] = $cal->original_id;
        $xoopsTpl->assign('commentany', $commentany);
    }
} elseif (isset($_POST['output_ics_confirm']) && !empty($_POST['ids']) && is_array($_POST['ids'])) {
    echo $cal->output_ics_confirm("$mod_url/");
} else {
    switch ($smode) {
        case 'Yearly' :
            $calDisplay = $cal->get_yearly(XOOPS_URL . '/modules/APCal/');
            break;
        case 'Weekly' :
            $calDisplay = $cal->get_weekly(XOOPS_URL . '/modules/APCal/');
            break;
        case 'Daily' :
            $calDisplay = $cal->get_daily(XOOPS_URL . '/modules/APCal/');
            break;
        case 'List' :
            $cal->assign_event_list($xoopsTpl, XOOPS_URL . '/modules/APCal/');
            break;
        case 'Monthly' :
        default :
            $calDisplay = $cal->get_monthly(XOOPS_URL . '/modules/APCal/');
            break;
    }
}

$xoopsTpl->assign('showSocial', $cal->enablesocial);
$xoopsTpl->assign('showTellaFriend', $cal->enabletellafriend);

if ($action == 'View') {
    $event_id   = isset($_GET['event_id']) && $_GET['event_id'] > 0 ? $_GET['event_id'] : 0;
    $event      = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->queryF("SELECT summary, description, location, categories, contact, start FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')} WHERE id={$event_id} LIMIT 0,1"));
    $cats       = explode(',', $event['categories']);
    $categories = array();
    foreach ($cats as $cat) {
        $title = $GLOBALS['xoopsDB']->fetchObject($GLOBALS['xoopsDB']->queryF("SELECT cat_title FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')} WHERE cid={$cat} LIMIT 0,1"));
        if ($title) {
            $categories[] = $title->cat_title;
        }
    }

    if (!empty($event['description'])) {
        $metaDesc = explode(' ', $event['description']);
        $metaDesc = array_slice($metaDesc, 0, 20);
        $xoTheme->addMeta('meta', 'description', implode(' ', $metaDesc));
    } else {
        $desc = $event['summary'];
        $desc .= !empty($categories) ? ' - ' . implode(' ', $categories) : '';
        $desc .= !empty($event['location']) ? ' - ' . $event['location'] : '';
        $desc .= !empty($event['start']) ? ' - ' . $cal->get_long_ymdn($event['start']) : '';
        $desc .= !empty($event['contact']) ? ' - ' . $event['contact'] : '';
        $metaDesc = explode(' ', $desc);
        $metaDesc = array_slice($metaDesc, 0, 20);
        $xoTheme->addMeta('meta', 'description', implode(' ', $metaDesc));
    }

    $title = $event['summary'];
    $title .= !empty($categories) ? ' - ' . implode(' ', $categories) : '';
    $title .= !empty($event['location']) ? ' - ' . $event['location'] : '';
    $title = strlen($title) > 60 ? substr($title, 0, 59) : $title;
    $xoopsTpl->assign('xoops_pagetitle', $title);

    $xoopsTpl->assign('showMap', $cal->enableeventmap);
} elseif ($action == '') {
    $cid          = isset($_GET['cid']) && $_GET['cid'] > 0 ? $_GET['cid'] : 0;
    $cat          = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->queryF("SELECT cat_title, cat_desc FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')} WHERE cid={$cid} LIMIT 0,1"));
    $date         = isset($_GET['caldate']) ? $_GET['caldate'] : date('Y-n-j');
    $date         = explode('-', $date);
    $dateTitle    = (isset($_GET['smode']) && $_GET['smode'] == 'Yearly' ? '' : $cal->month_long_names[$date[1]] . ' ') . $date[0];
    $catNameTitle = isset($_GET['cid']) && $_GET['cid'] > 0 ? $cat['cat_title'] : $xoopsModule->getVar('name');

    $pageTitle = $catNameTitle . ' ' . $dateTitle;
    $pageTitle = strlen($pageTitle) > 60 ? substr($pageTitle, 0, 59) : $pageTitle;

    $metaDesc = explode(' ', $catNameTitle . ' ' . $dateTitle . ' - ' . strip_tags($cat['cat_desc']));
    $metaDesc = array_slice($metaDesc, 0, 20);

    $xoopsTpl->assign('xoops_pagetitle', $pageTitle);
    $xoTheme->addMeta('meta', 'description', implode(' ', $metaDesc));

    if (isset($cat) && $smode != 'List' && !empty($catNameTitle) && $cal->displayCatTitle) {
        echo '<h1>' . $catNameTitle . '</h1>';
    }
    if (isset($cat) && $smode != 'List' && !empty($cat['cat_desc'])) {
        echo $cat['cat_desc'] . '<br /><br />';
    }

    if ($cal->enablecalmap == 1 && is_array($cal->gmPoints) && !empty($cal->gmPoints)) {
        $tpl = new XoopsTpl();
        $tpl->assign('GMlatitude', $cal->gmlat);
        $tpl->assign('GMlongitude', $cal->gmlng);
        $tpl->assign('GMzoom', $cal->gmzoom);
        $tpl->assign('GMheight', $cal->gmheight . 'px');
        $tpl->assign('GMPoints', $cal->gmPoints);
        if ($smode == 'List') {
            $xoopsTpl->assign('map', $tpl->fetch(XOOPS_ROOT_PATH . '/modules/APCal/templates/googlemap.tpl'));
        } else {
            $tpl->display(XOOPS_ROOT_PATH . '/modules/APCal/templates/googlemap.tpl');
        }
    }
    if ($cal->enablesocial && $smode != 'List') {
        $smode = empty($_GET['smode']) ? $cal->defaultView : preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['smode']);
        echo '<div class="socialNetworks">
                <span class="print">
                    <a href="' . $cal->base_url . '/print.php?cid=' . $cal->now_cid . '&smode=' . $smode . '&caldate=' . $cal->caldate . '" target="_blank">
                        <img src="' . $cal->images_url . '/print.gif" alt="' . _APCAL_BTN_PRINT . '" border="0" ' . PRINT_ATTRIB . ' />
                    </a>
                </span>';
        if ($cal->enabletellafriend) {
            echo '<span class="tellafriend">
                        <a href="" title="' . _APCAL_TELLAFRIEND . '" onclick="window.open(\'' . XOOPS_URL . '/modules/APCal/tellafriend.php?url=\'+encodeURIComponent(location.href)+\'&title=\'+encodeURIComponent(document.title), \'_blank\',\'toolbar=no,width=800,height=450\'); return false;">
                            <img src="' . XOOPS_URL . '/modules/APCal/assets/images/tellafriend.png" height="20" width="20" alt="' . _APCAL_TELLAFRIEND . '" title="' . _APCAL_TELLAFRIEND . '" />
                        </a>
                    </span>';
        }
        echo '<span class="delicious">
                    <a href="http://www.delicious.com/save" title="Delicious" onclick="window.open(\'http://www.delicious.com/save?v=5&noui&jump=close&url=\'+encodeURIComponent(location.href)+\'&title=\'+encodeURIComponent(document.title), \'delicious\',\'toolbar=no,width=550,height=550\'); return false;">
                        <img src="' . XOOPS_URL . '/modules/APCal/assets/images/delicious.png" height="20" width="20" alt="Delicious" title="Delicious" />
                    </a>
                </span>
                <span class="googleplus">
                    <script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: \'' . _APCAL_GPLUS_LNG . '\'}</script>
                    <g:plusone size="medium" count="false" href="' . XOOPS_URL . '/modules/APCal"></g:plusone>
                </span>
                <span class="linkedIn">
                    <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
                    <script type="IN/Share" data-url="' . XOOPS_URL . '/modules/APCal"></script>
                </span>
                <span class="twitter">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="' . XOOPS_URL . '/modules/APCal" data-count="none">Tweet</a>
                    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                </span>
                <span class="facebook">
                    <script type="text/javascript" src="http://connect.facebook.net/' . _APCAL_FB_LNG . '/all.js#xfbml=1"></script>
                    <div class="fb-like" data-href="' . XOOPS_URL . '/modules/APCal" data-send="false" data-layout="button_count" data-action="recommend" data-show-faces="false"></div>
                </span>
            </div>';
        //<a name="fb_share" type="button" share_url="http://www.example.com/page.html"></a>
        //<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
    }
    if ($cal->enablesharing && $smode != 'List') {
        echo '<div class="share"><a href="' . XOOPS_URL . '/modules/APCal/shareCalendar.php" title="' . _APCAL_SHARECALENDAR . '"><img src="' . XOOPS_URL . '/modules/APCal/assets/images/share.png" /><span style="line-height: 32px; margin-bottom: 15px;">' . _APCAL_SHARECALENDAR . '</span></a></div>';
    }
}

if (isset($calDisplay)) {
    echo $calDisplay;
}

error_reporting($original_level);

// XOOPS footer
include(XOOPS_ROOT_PATH . '/footer.php');
