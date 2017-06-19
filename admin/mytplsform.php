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

require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/../include/gtickets.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';

require_once __DIR__ . '/../include/Text_Diff.php';
require_once __DIR__ . '/../include/Text_Diff_Renderer.php';
require_once __DIR__ . '/../include/Text_Diff_Renderer_unified.php';

$xoops_system_path = XOOPS_ROOT_PATH . '/modules/system';

// initials
$db   = XoopsDatabaseFactory::getDatabaseConnection();
$myts = MyTextSanitizer::getInstance();

// determine language
$language = $xoopsConfig['language'];
if (!file_exists("$xoops_system_path/language/$language/admin/tplsets.php")) {
    $language = 'english';
}

// load language constants
// to prevent from notice that constants already defined
$error_reporting_level = error_reporting(0);
require_once "$xoops_system_path/constants.php";
require_once "$xoops_system_path/language/$language/admin.php";
require_once "$xoops_system_path/language/$language/admin/tplsets.php";
error_reporting($error_reporting_level);

// check $xoopsModule
if (!is_object($xoopsModule)) {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

// check access right (needs system_admin of tplset)
$syspermHandler = xoops_getHandler('groupperm');
if (!$syspermHandler->checkRight('system_admin', XOOPS_SYSTEM_TPLSET, $xoopsUser->getGroups())) {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

// tpl_file from $_GET
$tpl_file     = $myts->stripSlashesGPC(@$_GET['tpl_file']);
$tpl_file4sql = addslashes($tpl_file);

// tpl_file from $_GET
$tpl_tplset     = $myts->stripSlashesGPC(@$_GET['tpl_tplset']);
$tpl_tplset4sql = addslashes($tpl_tplset);

// get information from tplfile table
$sql = 'SELECT * FROM ' . $db->prefix('tplfile') . ' f NATURAL LEFT JOIN ' . $db->prefix('tplsource') . " s WHERE f.tpl_file='$tpl_file4sql' AND f.tpl_tplset='$tpl_tplset4sql'";
$tpl = $db->fetchArray($db->query($sql));
if (empty($tpl)) {
    die('Invalid tpl_file or tpl_tplset.');
}

//************//
// POST stage //
//************//
if (!empty($_POST['do_modify'])) {
    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    $result = $db->query('SELECT tpl_id FROM ' . $db->prefix('tplfile') . " WHERE tpl_file='$tpl_file4sql' AND tpl_tplset='$tpl_tplset4sql'");
    while (list($tpl_id) = $db->fetchRow($result)) {
        $sql = 'UPDATE ' . $db->prefix('tplsource') . " SET tpl_source='" . addslashes($myts->stripSlashesGPC($_POST['tpl_source'])) . "' WHERE tpl_id=$tpl_id";
        if (!$db->query($sql)) {
            die('SQL Error');
        }
        $db->query('UPDATE ' . $db->prefix('tplfile') . " SET tpl_lastmodified=UNIX_TIMESTAMP() WHERE tpl_id=$tpl_id");
        xoops_template_touch($tpl_id);
    }
    redirect_header('mytplsadmin.php?dirname=' . $tpl['tpl_module'], 1, _AM_APCALAM_APCALDBUPDATED);
    exit;
}

xoops_cp_header();
$mymenu_fake_uri = "/admin/mytplsadmin.php?dirname={$tpl['tpl_module']}";
if (file_exists('./mymenu.php')) {
    include __DIR__ . '/mymenu.php';
}

echo "<h3 style='text-align:left;'>"
     . _MD_APCAL_TPLSETS
     . ' : '
     . htmlspecialchars($tpl['tpl_type'], ENT_QUOTES)
     . ' : '
     . htmlspecialchars($tpl['tpl_file'], ENT_QUOTES)
     . ' ('
     . htmlspecialchars($tpl['tpl_tplset'], ENT_QUOTES)
     . ")</h3>\n";

// diff from file to selected DB template
$basefilepath        = XOOPS_ROOT_PATH . '/modules/' . $tpl['tpl_module'] . '/templates/' . ($tpl['tpl_type'] === 'block' ? 'blocks/' : '') . $tpl['tpl_file'];
$diff_from_file4disp = '';
if (file_exists($basefilepath)) {
    $diff     = new Text_Diff(file($basefilepath), explode("\n", $tpl['tpl_source']));
    $renderer = new Text_Diff_Renderer_unified();
    $diff_str = htmlspecialchars($renderer->render($diff), ENT_QUOTES);
    foreach (explode("\n", $diff_str) as $line) {
        if (ord($line) == 0x2d) {
            $diff_from_file4disp .= "<span style='color:red;'>" . $line . "</span>\n";
        } elseif (ord($line) == 0x2b) {
            $diff_from_file4disp .= "<span style='color:blue;'>" . $line . "</span>\n";
        } else {
            $diff_from_file4disp .= $line . "\n";
        }
    }
}

// diff from DB-default to selected DB template
$diff_from_default4disp = '';
if ($tpl['tpl_tplset'] !== 'default') {
    list($default_source) = $db->fetchRow($db->query('SELECT tpl_source FROM '
                                                     . $db->prefix('tplfile')
                                                     . ' NATURAL LEFT JOIN '
                                                     . $db->prefix('tplsource')
                                                     . " WHERE tpl_tplset='default' AND tpl_file='"
                                                     . addslashes($tpl['tpl_file'])
                                                     . "' AND tpl_module='"
                                                     . addslashes($tpl['tpl_module'])
                                                     . "'"));
    $diff     = new Text_Diff(explode("\n", $default_source), explode("\n", $tpl['tpl_source']));
    $renderer = new Text_Diff_Renderer_unified();
    $diff_str = htmlspecialchars($renderer->render($diff), ENT_QUOTES);
    foreach (explode("\n", $diff_str) as $line) {
        if (ord($line) == 0x2d) {
            $diff_from_default4disp .= "<span style='color:red;'>" . $line . "</span>\n";
        } elseif (ord($line) == 0x2b) {
            $diff_from_default4disp .= "<span style='color:blue;'>" . $line . "</span>\n";
        } else {
            $diff_from_default4disp .= $line . "\n";
        }
    }
}

echo "
    <form name='diff_form' id='diff_form' action='' method='get'>
    <input type='checkbox' name='display_diff2file' value='1' onClick=\"if (this.checked) {document.getElementById('diff2file').style.display='block'} else {document.getElementById('diff2file').style.display='none'};\" id='display_diff2file' checked />&nbsp;<label for='display_diff2file'>diff from file</label>
    <pre id='diff2file' style='display:block;border:1px solid black;'>$diff_from_file4disp</pre>
    <input type='checkbox' name='display_diff2default' value='1' onClick=\"if (this.checked) {document.getElementById('diff2default').style.display='block'} else {document.getElementById('diff2default').style.display='none'};\" id='display_diff2default' />&nbsp;<label for='display_diff2default'>diff from default</label>
    <pre id='diff2default' style='display:none;border:1px solid black;'>$diff_from_default4disp</pre>
    </form>\n";

echo "
<form name='MainForm' action='?tpl_file=" . htmlspecialchars($tpl['tpl_file'], ENT_QUOTES) . '&amp;tpl_tplset=' . htmlspecialchars($tpl['tpl_tplset'], ENT_QUOTES) . "' method='post'>
    " . $xoopsGTicket->getTicketHtml(__LINE__) . "
    <textarea name='tpl_source' wrap='off' style='width:600px;height:400px;'>" . htmlspecialchars($tpl['tpl_source'], ENT_QUOTES) . "</textarea>
    <br>
    <input type='submit' name='do_modify' value='" . _SUBMIT . "' />
    <input type='reset' name='reset' value='reset' />
</form>\n";

xoops_cp_footer();
