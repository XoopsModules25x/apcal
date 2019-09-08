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

require_once __DIR__ . '/admin_header.php';
//require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/../class/APCal.php';
require_once __DIR__ . '/../class/APCal_xoops.php';

// for "Duplicatable"
$moduleDirName = basename(dirname(__DIR__));
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";

// SERVER, GET �ѿ��μ���
$tz   = isset($_GET['tz']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['tz']) : 'y';
$pos  = isset($_GET['pos']) ? (int)$_GET['pos'] : 0;
$num  = isset($_GET['num']) ? (int)$_GET['num'] : 20;
$done = isset($_GET['done']) ? $_GET['done'] : '';

// MySQL�ؤ���³
$conn = $GLOBALS['xoopsDB']->conn;

// setting physical & virtual paths
$mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
$mod_url  = XOOPS_URL . "/modules/$moduleDirName";

// creating an instance of APCal
$cal = new APCal_xoops('', $xoopsConfig['language'], true);

// setting properties of APCal
$cal->conn = $conn;
include __DIR__ . '/../include/read_configs.php';
$cal->base_url    = $mod_url;
$cal->base_path   = $mod_path;
$cal->images_url  = "$mod_url/assets/images/$skin_folder";
$cal->images_path = "$mod_path/assets/images/$skin_folder";

// Timezone �ν���
$serverTZ  = $cal->server_TZ;
$userTZ    = $xoopsUser->timezone();
$tzoptions = "
    <option value='s'>" . _AM_APCAL_TZOPT_SERVER . "</option>
    <option value='g'>" . _AM_APCAL_TZOPT_GMT . "</option>
    <option value='y'>" . _AM_APCAL_TZOPT_USER . "</option>\n";
switch ($tz) {
    case 's':
        $tzoffset  = 0;
        $tzdisp    = ($serverTZ >= 0 ? '+' : '-') . sprintf('%02d:%02d', abs($serverTZ), abs($serverTZ) * 60 % 60);
        $tzoptions = str_replace("'s'>", "'s' selected>", $tzoptions);
        break;
    case 'g':
        $tzoffset  = -$serverTZ * 3600;
        $tzdisp    = 'GMT';
        $tzoptions = str_replace("'g'>", "'g' selected>", $tzoptions);
        break;
    default:
    case 'y':
        $tzoffset  = ($userTZ - $serverTZ) * 3600;
        $tzdisp    = ($userTZ >= 0 ? '+' : '-') . sprintf('%02d:%02d', abs($userTZ), abs($userTZ) * 60 % 60);
        $tzoptions = str_replace("'y'>", "'y' selected>", $tzoptions);
        break;
}

// �ǡ����١��������ʤɤ���������
if (isset($_POST['http_import']) && !empty($_POST['import_uri'])) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // http���ͥ�������ͳ�ޤ��ϥ?����ե������iCalendar����ݡ���
    list($records, $calname, $tmpname) = explode(':', $cal->import_ics_via_fopen($_POST['import_uri'], false), 3);
    if ($records <= 0) {
        $mes = urlencode("$calname : $tmpname");
        $cal->redirect("done=error&mes=$mes");
        exit;
    } else {
        $mes = urlencode(sprintf("$records " . _AM_APCAL_FMT_IMPORTED, $calname));
        $cal->redirect("done=imported&mes=$mes");
        exit;
    }
} elseif (isset($_POST['local_import']) && isset($_FILES['user_ics']['tmp_name'])
          && is_readable($_FILES['user_ics']['tmp_name'])
) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // �ե����륢�åץ?�ɤˤ��iCalendar����ݡ���
    list($records, $calname, $tmpname) = explode(':', $cal->import_ics_via_upload('user_ics'), 3);
    if ($records <= 0) {
        $mes = urlencode("$calname : " . $_FILES['user_ics']['name']);
        $cal->redirect("done=error&mes=$mes");
        exit;
    } else {
        $mes = urlencode(sprintf("$records " . _AM_APCAL_FMT_IMPORTED, $calname));
        $cal->redirect("done=imported&mes=$mes");
        exit;
    }
} elseif (isset($_POST['delete'])) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // �쥳���ɤκ��
    if (isset($_POST['ids']) && is_array($_POST['ids'])) {
        $whr = '';
        foreach ($_POST['ids'] as $id) {
            $whr .= "id=$id OR rrule_pid=$id OR ";
            xoops_comment_delete($xoopsModule->mid(), $id);
        }
        $sql = "DELETE FROM $cal->table WHERE ($whr 0) AND (rrule_pid=0 OR rrule_pid=id)";
        $GLOBALS['xoopsDB']->query($sql);
        $records = $GLOBALS['xoopsDB']->getAffectedRows($conn);
        $sql     = "DELETE FROM $cal->table WHERE $whr 0 ";
        if (!$GLOBALS['xoopsDB']->query($sql)) {
            echo $GLOBALS['xoopsDB']->error();
        } else {
            $mes = urlencode("$records " . _AM_APCAL_MES_DELETED);
        }
    } else {
        $mes = '';
    }
    $cal->redirect("done=deleted&mes=$mes");
    exit;
}

// ����ݡ��Ȥ���ľ��Υ쥳���ɿ���$mes��������
if ($done === 'imported' && isset($_GET['mes'])) {
    $new_imported = (int)$_GET['mes'];
} else {
    $new_imported = 0;
}

// クエリ（１時間以内のレコードだけを表示）
$older_limit = time() - 3600;
$whr         = "UNIX_TIMESTAMP(dtstamp) > $older_limit AND (rrule_pid=0 OR rrule_pid=id)";

//$rs          = $xoopsDB->query("SELECT COUNT(id) FROM $cal->table WHERE $whr");
//$numrows     = mysql_result($rs, 0, 0);
//$rs          = $xoopsDB->query("SELECT * FROM $cal->table WHERE $whr ORDER BY dtstamp DESC LIMIT $pos,$num");

$rs        = $GLOBALS['xoopsDB']->query("SELECT COUNT(id) FROM $cal->table WHERE $whr");
$numrows   = 0;
$resultRow = $GLOBALS['xoopsDB']->fetchRow($rs);
if (false !== $resultRow && isset($resultRow[0])) {
    $numrows = $resultRow[0];
}
$rs = $GLOBALS['xoopsDB']->query("SELECT * FROM $cal->table WHERE $whr ORDER BY  dtstamp DESC LIMIT $pos,$num");

// ページ分割処理
include XOOPS_ROOT_PATH . '/class/pagenav.php';
$nav      = new XoopsPageNav($numrows, $num, $pos, 'pos', "tz=$tz&amp;num=$num");
$nav_html = $nav->renderNav(10);
if ($numrows <= 0) {
    $nav_num_info = _NONE;
} elseif ($pos + $num > $numrows) {
    $nav_num_info = ($pos + 1) . "-$numrows/$numrows";
} else {
    $nav_num_info = ($pos + 1) . '-' . ($pos + $num) . '/' . $numrows;
}

// �ᥤ�������
xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));

// 3�Ĥ�ɽ�������Τǥ��ꥢ���Ƥ���
$xoopsGTicket->clear();

echo '
<h4>' . _AM_APCAL_ICALENDAR_IMPORT . "</h4>
<p><style='color: blue; '>" . (isset($_GET['mes']) ? htmlspecialchars($_GET['mes'], ENT_QUOTES) : '') . "</style></p>
<form class='apcalForm' action='?tz=$tz&amp;num=$num' method='post'>
  " . _AM_APCAL_LABEL_IMPORTFROMWEB . "<br>
  <input type='text' name='import_uri' size='80'>
  <input type='submit' name='http_import' value='" . _APCAL_BTN_IMPORT . "'>
  " . $xoopsGTicket->getTicketHtml(__LINE__) . "
</form>
<form class='apcalForm' action='?tz=$tz&amp;num=$num' method='post' enctype='multipart/form-data'>
  " . _AM_APCAL_LABEL_UPLOADFROMFILE . "<br>
  <input type='hidden' name='MAX_FILE_SIZE' value='65536'>
  <input type='file' name='user_ics' size='72'>
  <input type='submit' name='local_import' value='" . _APCAL_BTN_UPLOAD . "'>
  " . $xoopsGTicket->getTicketHtml(__LINE__) . "
</form>
<form class='apcalForm' action='' method='get' style='margin-bottom:0px;text-align:left'>
  <select name='tz' onChange='submit();'>$tzoptions</select>
  <input type='hidden' name='num' value='$num' />
</form>
<table width='100%' cellpadding='0' cellspacing='0' border='0'>
  <tr>
    <td align='left'>
      $nav_num_info
    </td>
    <td>
      <form class='apcalForm' action='' method='get' style='margin-bottom:0px;text-align:right'>
        $nav_html &nbsp;
        <input type='hidden' name='num' value='$num' />
        <input type='hidden' name='tz' value='$tz' />
      </form>
    </td>
  </tr>
</table>
<form class='apcalForm' id='MainForm' name='MainForm' action='?tz=$tz&amp;num=$num' method='post' style='margin-top:0px;'>
" . $xoopsGTicket->getTicketHtml(__LINE__) . "
<table width='100%' class='outer' cellpadding='4' cellspacing='1'>
  <tr valign='middle'>
    <th>" . _AM_APCAL_IO_TH0 . '</th>
    <th>' . _AM_APCAL_IO_TH1 . "<br>($tzdisp)</th>
    <th>" . _AM_APCAL_IO_TH2 . "<br>($tzdisp)</th>
    <th>" . _AM_APCAL_IO_TH3 . '</th>
    <th>' . _AM_APCAL_IO_TH4 . '</th>
    <th>' . _AM_APCAL_IO_TH5 . "</th>
    <th></th>
    <th><input type='checkbox' name='dummy' onclick=\"with(document.MainForm){for (i=0;i<length;i++) {if (elements[i].type=='checkbox') {elements[i].checked=this.checked;}}}\" /></th>
  </tr>
";

// �ꥹ�Ƚ�����
$myts    = MyTextSanitizer::getInstance();
$oddeven = 'odd';
$count   = 0;
while ($event = $GLOBALS['xoopsDB']->fetchObject($rs)) {
    $oddeven = ($oddeven === 'odd' ? 'even' : 'odd');
    if (++$count < $new_imported) {
        $newer_style = "style='background-color:#FFFFCC;'";
    } else {
        $newer_style = '';
    }
    if ($event->allday) {
        $start_desc = date(_AM_APCAL_DTFMT_LIST_ALLDAY, $event->start) . '<br>(' . _APCAL_MB_ALLDAY_EVENT . ')';
        $end_desc   = date(_AM_APCAL_DTFMT_LIST_ALLDAY, $event->end - 300) . '<br>(' . _APCAL_MB_ALLDAY_EVENT . ')';
    } else {
        $start_desc = date(_AM_APCAL_DTFMT_LIST_NORMAL, $event->start + $tzoffset);
        $end_desc   = date(_AM_APCAL_DTFMT_LIST_NORMAL, $event->end + $tzoffset);
    }
    $summary4disp = $myts->htmlSpecialChars($event->summary);
    echo "
  <tr>
    <td class='$oddeven' $newer_style>" . $xoopsUser->getUnameFromId($event->uid) . "</td>
    <td class='$oddeven' nowrap='nowrap' $newer_style>$start_desc</td>
    <td class='$oddeven' nowrap='nowrap' $newer_style>$end_desc</td>
    <td class='$oddeven' $newer_style><a href='$mod_url/index.php?action=View&amp;event_id=$event->id'>$summary4disp</a></td>
    <td class='$oddeven' $newer_style>" . $cal->rrule_to_human_language($event->rrule) . "</td>
    <td class='$oddeven' $newer_style>" . ($event->admission ? _YES : _NO) . "</td>
    <td class='$oddeven' align='right' $newer_style><a href='$mod_url/index.php?action=Edit&amp;event_id=$event->id' target='_blank'><img src='$cal->images_url/addevent.gif' border='0' width='14' height='12' /></a></td>
    <td class='$oddeven' align='right' $newer_style><input type='checkbox' name='ids[]' value='$event->id' /></td>
  </tr>\n";
}

echo "
  <tr>
    <td colspan='8' align='right' class='head'>"
     . _AM_APCAL_LABEL_IO_CHECKEDITEMS
     . ' &nbsp; '
     . _AM_APCAL_LABEL_IO_DELETE
     . "<input type='submit' name='delete' value='"
     . _DELETE
     . "' onclick='return confirm(\""
     . _AM_APCAL_CONFIRM_DELETE
     . "\")' /></td>
  </tr>
  <tr>
    <td colspan='8' align='right' valign='bottom' height='50'>"
     . _AM_APCAL_COPYRIGHT
     . '</td>
  </tr>
</table>
</form>
';

require_once __DIR__ . '/admin_footer.php';
