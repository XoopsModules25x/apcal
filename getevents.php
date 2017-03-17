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
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 */

require_once __DIR__ . '/../../mainfile.php';

$xoopsErrorHandler->activated = false;
error_reporting(E_NONE);

header('Access-Control-Allow-Origin: *');

$locales = new apcal_locale();

$array   = array();
$catcrit = $_GET['c'] > 0 ? 'categories LIKE \'%' . str_pad($_GET['c'], 5, '0', STR_PAD_LEFT) . '%\' AND' : '';
$result  = $GLOBALS['xoopsDB']->queryF("SELECT id, start, end, summary, shortsummary FROM {$GLOBALS['xoopsDB']->prefix('apcal_event')} WHERE {$catcrit} end>UNIX_TIMESTAMP() ORDER BY start ASC LIMIT 0,{$_GET['n']}");
while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
    $start  = $row['start'];
    $startD = $locales->date_long_names[(int)gmstrftime('%d', $row['start'] + (date('I', $row['start']) * 3600))];
    $startM = $locales->month_long_names[(int)gmstrftime('%m', $row['start'] + (date('I', $row['start']) * 3600))];

    $endD = $locales->date_long_names[(int)gmstrftime('%d', $row['end'] + (date('I', $row['end']) * 3600))];
    $endM = $locales->month_long_names[(int)gmstrftime('%m', $row['end'] + (date('I', $row['end']) * 3600))];

    $row['start']   = $startD . ' ' . htmlentities($startM, ENT_QUOTES, 'UTF-8');
    $row['end']     = $endD . ' ' . htmlentities($endM, ENT_QUOTES, 'UTF-8');
    $row['summary'] = htmlentities($row['summary'], ENT_QUOTES, 'UTF-8');
    $row['link']    = $xoopsModuleConfig['apcal_useurlrewrite'] ? XOOPS_URL . '/modules/apcal/' . $row['shortsummary'] . '-' . date('j-n-Y', $start) : XOOPS_URL
                                                                                                                                                       . '/modules/apcal/?event_id='
                                                                                                                                                       . $row['id'];
    $array[]        = $row;
}
$c = $_GET['c']
     > 0 ? htmlentities($GLOBALS['xoopsDB']->fetchObject($GLOBALS['xoopsDB']->queryF("SELECT cat_title FROM {$GLOBALS['xoopsDB']->prefix('apcal_cat')} WHERE cid={$_GET['c']} LIMIT 0,1"))->cat_title,
                        ENT_QUOTES, 'UTF-8') : '';
$l = '</dl><div class="APfooter">'
     . _APCAL_PROVIDEDBY
     . ' <a href="'
     . XOOPS_URL
     . '" title="'
     . htmlentities($xoopsConfig['sitename'], ENT_QUOTES, 'UTF-8')
     . '" target="_blank">'
     . htmlentities($xoopsConfig['sitename'], ENT_QUOTES, 'UTF-8')
     . '</a><br><a href="'
     . _APCAL_APURL
     . '" title="'
     . _APCAL_AP
     . '" target="_blank">APCal</a> '
     . _APCAL_X
     . ' <a href="'
     . _APCAL_APURL2
     . '" title="'
     . _APCAL_AP
     . '" target="_blank">AP</a></div>';
echo check() ? json_encode(array($array, $l, '<div class="APtitle">' . $c . '</div>')) : '';

/**
 * Class apcal_locale
 */
class apcal_locale
{
    public $hour_names_24;
    public $hour_names_12;
    public $holidays;
    public $date_short_names;
    public $date_long_names;
    public $week_numbers;
    public $week_short_names;
    public $week_middle_names;
    public $week_long_names;
    public $month_short_names;
    public $month_middle_names;
    public $month_long_names;
    public $byday2langday_w;
    public $byday2langday_m;

    /**
     * apcal_locale constructor.
     */
    public function __construct()
    {
        include XOOPS_ROOT_PATH . '/modules/apcal/language/' . $GLOBALS['xoopsConfig']['language'] . '/apcal_vars.phtml';
    }
}

/**
 * @return int
 */
function check()
{
    global $l;

    return preg_match('/<a href="http:\/\/xoops.antique(s?)promotion.(com|ca)/', $l);
}
