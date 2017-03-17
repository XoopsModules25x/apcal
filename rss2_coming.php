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

include __DIR__ . '/../../mainfile.php';
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');

// for "Duplicatable"
$moduleDirName = basename(__DIR__);
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

// setting physical & virtual paths
$mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
$mod_url  = XOOPS_URL . "/modules/$moduleDirName";

// defining class of APCal
require_once "$mod_path/class/APCal.php";
require_once "$mod_path/class/APCal_xoops.php";

// creating an instance of APCal
$cal                = new APCal_xoops(date('Y-n-j'), $xoopsConfig['language'], true);
$cal->use_server_TZ = true;

// ignoring cid from GET
// $cal->now_cid = 0 ;

// setting properties of APCal
$cal->conn = $GLOBALS['xoopsDB']->conn;
include "$mod_path/include/read_configs.php";
$cal->base_url    = $mod_url;
$cal->base_path   = $mod_path;
$cal->images_url  = "$mod_url/assets/images/$skin_folder";
$cal->images_path = "$mod_path/assets/images/$skin_folder";

$block = $cal->get_blockarray_coming_event("$mod_url/index.php", 10, true);

//mb_http_output( 'UTF-8' ) ;
//ob_start( 'mb_outputHandler' ) ;
ob_start('xoops_utf8_encode');

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
  <channel>
    <title>' . $xoopsModule->getVar('name') . ' - ' . htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES) . "</title>
    <link>$mod_url/</link>
    <description>" . htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES) . '</description>
    <lastBuildDate>' . formatTimestamp(time(), 'r') . '</lastBuildDate>
    <webMaster>' . $xoopsConfig['adminmail'] . '</webMaster>
    <editor>' . $xoopsConfig['adminmail'] . '</editor>
    <category>Calendar</category>
    <generator>APCal for XOOPS</generator>
    <language>' . _LANGCODE . "</language>\n";

foreach ($block['events'] as $event) {

    // start
    $start = date('n/j G:i', $event['start']);

    echo "
    <item>
      <title>$start {$event['summary']}</title>
      <link>$mod_url/?event_id={$event['id']}</link>
      <description>{$event['description']}</description>
      <pubDate>" . formatTimestamp($event['start'], 'r') . "</pubDate>
    </item>\n";
}

echo "
  </channel>
</rss>\n";
