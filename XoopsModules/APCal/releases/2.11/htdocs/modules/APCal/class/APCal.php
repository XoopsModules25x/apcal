<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Antiques Promotion (http://www.antiquespromotion.ca)
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 * @version     $Id:$
 */

if( ! class_exists( 'APCal' ) ) {

define( 'APCAL_EVENT_TABLE' , 'apcal_event' ) ;
define( 'APCAL_CAT_TABLE' , 'apcal_cat' ) ;

include_once XOOPS_ROOT_PATH.'/modules/APCal/include/ro_contacthandler.php'; // added by goffy convert name(s) in field contact in a links to member account
require_once XOOPS_ROOT_PATH.'/modules/APCal/class/thumb.php';

class APCal
{
	// SKELTON (they will be defined in language files)
	var $holidays = array() ;
	var $date_short_names = array() ;
	var $date_long_names = array() ;
	var $week_numbers = array() ;
	var $week_short_names = array() ;
	var $week_middle_names = array() ;
	var $week_long_names = array() ;
	var $month_short_names = array() ;
	var $month_middle_names = array() ;
	var $month_long_names = array() ;
	var $byday2langday_w = array() ;
	var $byday2langday_m = array() ;

	// LOCALES
	var $locale = '' ;			// locale for APCal original
	var $locale4system = '' ;	// locale for UNIX systems (deprecated)

	// COLORS/STYLES  public
	var $holiday_color = '#CC0000';
	var $holiday_bgcolor = '#FFEEEE';
	var $sunday_color = '#CC0000' ;
	var $sunday_bgcolor = '#FFEEEE';
	var $saturday_color = '#0000FF';
	var $saturday_bgcolor = '#EEF7FF';
	var $weekday_color = '#000099';
	var $weekday_bgcolor = '#FFFFFF';
	var $targetday_bgcolor = '#CCFF99';
	var $calhead_color = '#009900';
	var $calhead_bgcolor = '#CCFFCC';
	var $frame_css = '#000000';
    var $allcats_color = '#5555AA';
    var $event_color = '#000000';
    var $event_bgcolor = '#EEEEEE';

    // GOOGLE MAPS
    var $gmlat = 0;
    var $gmlng = 0;
    var $gmzoom = 12;
    var $gmheight = 350;
    var $gmPoints = array();
    
    // PICTURES
    var $picWidth = 150;
    var $picHeight = 150;
    var $nbPictures = 5;

    var $showPicMonthly = 1;
    var $showPicWeekly = 1;
    var $showPicDaily = 1;
    var $showPicList = 1;

    var $widerDays = array('Saturday', 'Sunday');
    
    var $useurlrewrite = 0;
    var $enablecalmap = 1;
    var $enableeventmap = 1;
    var $enablesharing = 1;
    var $eventNavEnabled = 1;
    var $displayCatTitle = 1;
    var $enablesocial = true;
    
    var $default_view = 'Monthly';

	// TIMEZONES
	var $server_TZ = 9 ;			// Server's  Timezone Offset (hour)
	var $user_TZ = 9 ;				// User's Timezone Offset (hour)
	var $use_server_TZ = false ;	// if 'caldate' is generated in Server's time
    var $displayTimezone = 0;

	// AUTHORITIES
	var $insertable = true ;		// can insert a new event
	var $editable = true ;			// can update an event he posted
	var $deletable = true ;			// can delete an event he posted
	var $user_id = -1 ;				// User's ID
	var $isadmin = false ;			// Is admin or not

	// ANOTHER public properties
	var $conn ;					// MySQLï¿½È¤ï¿½ï¿½ï¿½Â³ï¿½Ï¥ï¿½É¥ï¿½ (Í½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ò¤¹¤ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½)
	var $table = 'apcal_event' ;		// table name for events
	var $cat_table = 'apcal_cat' ;		// table name for categories
    var $pic_table = 'apcal_pictures' ;		// table name for pictures
	var $plugin_table = 'apcal_plugin' ;	// table name for plugins
	var $base_url = '' ;
	var $base_path = '' ;
	var $images_url = '/include/APCal/images' ;	// ï¿½ï¿½ï¿½Î¥Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ spacer.gif, arrow*.gif ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½ï¿½Æ¤ï¿½ï¿½ï¿½
	var $images_path = 'include/APCal/images' ;
	var $jscalendar = 'jscalendar' ; // DHTML Date/Time Selector
	var $jscalendar_lang_file = 'calendar-jp.js' ; // language file of the jscalh
	var $can_output_ics = true ;	// icsï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï¤ï¿½ï¿½ï¿½Ä¤ï¿½ï¿½ë¤«ï¿½É¤ï¿½ï¿½ï¿½
    var $ics_new_cal = true;
	var $connection = 'http' ;		// http ï¿½ï¿½ https ï¿½ï¿½
	var $max_rrule_extract = 100 ;	// rrule ï¿½ï¿½Å¸ï¿½ï¿½ï¿½Î¾ï¿½Â¿ï¿½(COUNT)
	var $week_start = 0 ;			// ï¿½ï¿½ï¿½Î³ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ 0ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ 1ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	var $week_numbering = 0 ;		// ï¿½ï¿½ï¿½Î¿ï¿½ï¿½ï¿½ï¿½ï¿½ 0ï¿½Ê¤ï¿½î¤´ï¿½ï¿½ 1ï¿½Ê¤ï¿½Ç¯ï¿½ï¿½ï¿½Ì»ï¿½
	var $day_start = 0 ;			// ï¿½ï¿½ï¿½Õ¤Î¶ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã±ï¿½Ì¡ï¿½
	var $use24 = true ;				// 24ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½trueï¿½ï¿½12ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½false
	var $now_cid = 0 ;				// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	var $categories = array() ;		// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ç½ï¿½Ê¥ï¿½ï¿½Æ¥ï¿½ï¿½ê¥ªï¿½Ö¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
    var $canbemain_cats = array();
	var $groups = array() ;			// PRIVATEï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ç½ï¿½Ê¥ï¿½ï¿½ë¡¼ï¿½×¤ï¿½Ï¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	var $nameoruname = 'name' ;		// ï¿½ï¿½Æ¼Ô¤ï¿½É½ï¿½ï¿½ï¿½Ê¥?ï¿½ï¿½ï¿½ï¿½Ì¾ï¿½ï¿½ï¿½Ï¥ï¿½É¥ï¿½Ì¾ï¿½ï¿½ï¿½ï¿½
	var $proxysettings = '' ;		// Proxy setting
	var $last_summary = '' ;		// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ì¾ï¿½ò»²¾È¤ï¿½ï¿½ë¤¿ï¿½ï¿½Î¥×¥ï¿½Ñ¥Æ¥ï¿½
	var $plugins_path_monthly = 'plugins/monthly' ;
	var $plugins_path_weekly = 'plugins/weekly' ;
	var $plugins_path_daily = 'plugins/daily' ;

	// private members
	var $year ;
	var $month ;
	var $date ;
	var $day ;			// 0:Sunday ... 6:Saturday
	var $daytype ;		// 0:weekdays 1:saturday 2:sunday 3:holiday
	var $caldate ;		// everytime 'Y-n-j' formatted
	var $unixtime ;
	var $long_event_legends = array() ;
	var $language = "japanese" ;

	// ï¿½ï¿½ï¿½ï¿½Õ¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ñ¥ï¿½ï¿½ï¿½
	var $original_id ;	// $_GET['event_id']ï¿½ï¿½ï¿½ï¿½ï¿½Ä¾ï¿½ï¿½Ë»ï¿½ï¿½È²ï¿½Ç½
    
// added by goffy: vars for online registration
    var $table_ro_members = '_apcal_ro_members';    // table for eventmembers
    var $table_ro_events = '_apcal_ro_events';      // table for events, where online registration is possible (max registration, email notify in case off add/remove eventmembers
    var $table_ro_notify = '_apcal_ro_notify';      // table for persons, which should be informed about registrations by email
    var $redirecturl='';                            // variable für redirect
    var $registered=0;                              // var whether user is already regristrated for this event or not
    var $regonline=0;                               // var, whether online registration is activated or not
    var $roimage=0;                                 // var for image to mark events with online registration
    var $eventmembers='';                           // first var for show additional info
    var $eventmembersall='';                        // second var for show additional info
// end goffy
    var $enableregistration = 1;


/*******************************************************************/
/*        CONSTRUCTOR etc.                                         */
/*******************************************************************/

// Constructor
function APCal( $target_date = "" , $language = "japanese" , $reload = false )
{
	// ï¿½ï¿½ï¿½Õ¤Î¥ï¿½ï¿½Ã¥ï¿½
	if( $target_date ) {
		$this->set_date( $target_date ) ;
	} else if( isset( $_GET[ 'caldate' ] ) ) {
		$this->set_date( $_GET[ 'caldate' ] ) ;
	} else if( isset( $_POST[ 'apcal_jumpcaldate' ] ) && isset( $_POST[ 'apcal_year' ] ) ) {
		if( empty( $_POST[ 'apcal_month' ] ) ) {
			// Ç¯ï¿½Î¤ß¤ï¿½POSTï¿½ï¿½ï¿½ì¤¿ï¿½ï¿½ï¿½
			$month = 1 ;
			$date = 1 ;
		} else if( empty( $_POST[ 'apcal_date' ] ) ) {
			// Ç¯ï¿½ï¿½ï¿½î¤¬POSTï¿½ï¿½ï¿½ì¤¿ï¿½ï¿½ï¿½
			$month = intval( $_POST[ 'apcal_month' ] ) ;
			$date = 1 ;
		} else {
			// Ç¯ï¿½ï¿½ï¿½î¡¦ï¿½ï¿½POSTï¿½ï¿½ï¿½ì¤¿ï¿½ï¿½ï¿½
			$month = intval( $_POST[ 'apcal_month' ] ) ;
			$date = intval( $_POST[ 'apcal_date' ] ) ;
		}
		$year = intval( $_POST[ 'apcal_year' ] ) ;
		$this->set_date( "$year-$month-$date" ) ;
		$caldate_posted = true ;
	} else {
		$this->set_date( date( 'Y-n-j' ) ) ;
		$this->use_server_TZ = true ;
	}

	// SSLï¿½ï¿½Í­Ìµï¿½ï¿½$_SERVER['HTTPS'] ï¿½Ë¤ï¿½È½ï¿½ï¿½
	if( defined( 'XOOPS_URL' ) ) {
		$this->connection = substr( XOOPS_URL , 0 , 8 ) == 'https://' ? 'https' : 'http' ;
	} else if( ! empty( $_SERVER['HTTPS'] ) ) {
		$this->connection = 'https' ;
	} else {
		$this->connection = 'http' ;
	}

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½ï¿½ï¿½Î¼ï¿½ï¿½ï¿½
	$this->now_cid = ! empty( $_GET['cid'] ) ? intval( $_GET['cid'] ) : 0 ;

	// POSTï¿½Ç¥Ð¥ï¿½Ð¥ï¿½ï¿½ï¿½ï¿½ï¿½Õ¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ì¤¿ï¿½ï¿½ç¡¢ï¿½ï¿½ï¿½ê¤¬ï¿½ï¿½ï¿½ï¿½Ð¥ï¿½?ï¿½É¤ï¿½Ô¤ï¿½
	if( ! empty( $caldate_posted ) && $reload && ! headers_sent() ) {
		$reload_str = "Location: $this->connection://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?caldate=$this->caldate&{$_SERVER['QUERY_STRING']}" ;
		$needed_post_vars = array( 'op' , 'order' , 'cid' , 'num' , 'txt' ) ;
		foreach( $needed_post_vars as $post ) {
			if( isset( $_POST[ $post ] ) ) $reload_str .= "&$post=".urlencode( $_POST[ $post ] ) ;
		}
		$reload_str4header = strtr( $reload_str , "\r\n\0" , "   " ) ;
		header( $reload_str4header ) ;
		exit ;
	}

	// APCal.php ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Â¸ï¿½ß¤ï¿½ï¿½ï¿½Ç¥ï¿½ï¿½ì¥¯ï¿½È¥ï¿½Î°ï¿½Ä¾ï¿½ï¿½Ù¡ï¿½ï¿½ï¿½ï¿½È¤ï¿½ï¿½ï¿½
	$this->base_path = dirname( dirname( __FILE__ ) ) ;

	// ï¿½ï¿½ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É¤ß¹ï¿½ï¿½ï¿½
	if ( file_exists( "$this->base_path/language/$language/apcal_vars.phtml" ) ) {
		include "$this->base_path/language/$language/apcal_vars.phtml" ;
		include_once "$this->base_path/language/$language/apcal_constants.php" ;
		$this->language = $language ;
		$this->jscalendar_lang_file = _APCAL_JS_CALENDAR ;
	} else if( file_exists( "$this->base_path/language/english/apcal_vars.phtml") ) {
		include "$this->base_path/language/english/apcal_vars.phtml" ;
		include_once "$this->base_path/language/english/apcal_constants.php" ;
		$this->language = "english" ;
		$this->jscalendar_lang_file = 'calendar-en.js' ;
	}

	// ï¿½?ï¿½ï¿½ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É¹ï¿½
	if( ! empty( $this->locale ) ) $this->read_locale() ;
}


function make_cal_link($get_target='', $smode='Monthly', $cid=0, $caldate='')
{
    global $xoopsModule;
    
    if($cid < 0) {$cid = $this->now_cid;}
    if($caldate == '') {$caldate = $this->caldate;}
    if($smode == '') {$smode = $this->default_view;}
    
    $isAllCat = $cid == 0;
    $isDefaultView = $smode == $this->default_view;
    $isToday = date('Y-n-j') == $caldate;
    
    if($this->conn)
        $cat = mysql_query("SELECT cat_shorttitle FROM $this->cat_table WHERE cid=$cid LIMIT 0,1", $this->conn);
    else 
        $cat = false;

	if($cat && mysql_num_rows($cat))
    {
        $cat = mysql_fetch_object($cat);
        $cat = urlencode(urlencode($cat->cat_shorttitle));
    }
    else
    {
        $cat = isset($xoopsModule) && !empty($xoopsModule) ? urlencode(urlencode($xoopsModule->getVar('name'))) : 0;
    }

    if($this->useurlrewrite)
    {
        if    (!$isAllCat && !$isDefaultView && !$isToday) {$link = XOOPS_URL."/modules/APCal/$cat-$smode-$caldate";}
        elseif(!$isAllCat && !$isDefaultView &&  $isToday) {$link = XOOPS_URL."/modules/APCal/$cat-$smode";}
        elseif(!$isAllCat &&  $isDefaultView && !$isToday) {$link = XOOPS_URL."/modules/APCal/$cat-$caldate";}
        elseif(!$isAllCat &&  $isDefaultView &&  $isToday) {$link = XOOPS_URL."/modules/APCal/$cat";}
        elseif( $isAllCat && !$isDefaultView && !$isToday) {$link = XOOPS_URL."/modules/APCal/$smode-$caldate";}
        elseif( $isAllCat && !$isDefaultView &&  $isToday) {$link = XOOPS_URL."/modules/APCal/$smode";}
        elseif( $isAllCat &&  $isDefaultView && !$isToday) {$link = XOOPS_URL."/modules/APCal/$caldate";}
        else {$link = XOOPS_URL."/modules/APCal/";}
        
        return $link; 
    }
    else
        return ($get_target == '' ? XOOPS_URL."/modules/APCal/" : $get_target)."?cid=$cid&smode=$smode&caldate=$caldate";
}

function make_event_link($event_id, $get_target='', $caldate='')
{
    if($caldate == '') {$caldate = $this->caldate;}
    
    $event = mysql_query( "SELECT shortsummary, start FROM $this->table WHERE id=$event_id LIMIT 0,1", $this->conn ) ;

	if($event && mysql_num_rows($event))
    {
        $event = mysql_fetch_object($event);
        $date = date('j-n-Y', $event->start);
        $event = urlencode(urlencode($event->shortsummary));
    }
    else
    {
        $event = $event_id;
        $date = $caldate != '' ? date('j-n-Y', strtotime($caldate)) : date('j-n-Y');
    }

    if($this->useurlrewrite)
        return XOOPS_URL."/modules/APCal/$event-$date";
    else
        return ($get_target == '' ? XOOPS_URL."/modules/APCal/" : $get_target)."?event_id=$event_id&action=View&caldate=$caldate";
}

function urlencode($str)
{
    $str = urlencode($str);
    return str_replace(array('%26', '3D'), array('&', '='), $str);
}

function makeShort($str)
{
    $replacements = array('Å '=>'S', 'Å¡'=>'s', 'Å½'=>'Z', 'Å¾'=>'z', 'Ã€'=>'A', 'Ã�'=>'A', 'Ã‚'=>'A', 'Ãƒ'=>'A', 'Ã„'=>'A', 'Ã…'=>'A', 'Ã†'=>'A', 'Ã‡'=>'C', 'Ãˆ'=>'E', 'Ã‰'=>'E',
                          'ÃŠ'=>'E', 'Ã‹'=>'E', 'ÃŒ'=>'I', 'Ã�'=>'I', 'ÃŽ'=>'I', 'Ã�'=>'I', 'Ã‘'=>'N', 'Ã’'=>'O', 'Ã“'=>'O', 'Ã”'=>'O', 'Ã•'=>'O', 'Ã–'=>'O', 'Ã˜'=>'O', 'Ã™'=>'U',
                          'Ãš'=>'U', 'Ã›'=>'U', 'Ãœ'=>'U', 'Ã�'=>'Y', 'Ãž'=>'B', 'ÃŸ'=>'Ss', 'Ã '=>'a', 'Ã¡'=>'a', 'Ã¢'=>'a', 'Ã£'=>'a', 'Ã¤'=>'a', 'Ã¥'=>'a', 'Ã¦'=>'a', 'Ã§'=>'c',
                          'Ã¨'=>'e', 'Ã©'=>'e', 'Ãª'=>'e', 'Ã«'=>'e', 'Ã¬'=>'i', 'Ã­'=>'i', 'Ã®'=>'i', 'Ã¯'=>'i', 'Ã°'=>'o', 'Ã±'=>'n', 'Ã²'=>'o', 'Ã³'=>'o', 'Ã´'=>'o', 'Ãµ'=>'o',
                          'Ã¶'=>'o', 'Ã¸'=>'o', 'Ã¹'=>'u', 'Ãº'=>'u', 'Ã»'=>'u', 'Ã½'=>'y', 'Ã½'=>'y', 'Ã¾'=>'b', 'Ã¿'=>'y');

    $str = strtr($str, $replacements);
    $str = strip_tags($str);
    
    return str_replace(array(" ", "-", "/", "\\", "'", "\"", "\r", "\n", "&", "?", "!", "%", ",", "."), "", $str);
}

// APCalï¿½ï¿½ï¿½Ñ¥?ï¿½ï¿½ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É¤ß¹ï¿½ï¿½ï¿½
function read_locale()
{
	if( file_exists( "$this->base_path/locales/{$this->locale}.php" ) ) {
		include "$this->base_path/locales/{$this->locale}.php" ;
	}
}


// year,month,day,caldate,unixtime ï¿½ò¥»¥Ã¥È¤ï¿½ï¿½ï¿½
function set_date( $setdate )
{
	if( ! ( preg_match( "/^([0-9][0-9]+)[-.\/]?([0-1]?[0-9])[-.\/]?([0-3]?[0-9])$/" , $setdate , $regs ) && checkdate( $regs[2] , $regs[3] , $regs[1] ) ) ) {
		preg_match( "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/" , date( 'Y-m-d' ) , $regs ) ;
		$this->use_server_TZ = true ;
	}
	$this->year = $year = intval( $regs[1] ) ;
	$this->month = $month = intval( $regs[2] ) ;
	$this->date = $date = intval( $regs[3] ) ;
	$this->caldate = "$year-$month-$date" ;
	$this->unixtime = mktime(0,0,0,$month,$date,$year) ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½×¤Î¥ï¿½ï¿½Ã¥ï¿½
	// ï¿½Ä¥ï¿½ï¿½é¡¼ï¿½Î¸ï¿½
	if( $month <= 2 ) {
		$year -- ;
		$month += 12 ;
	}
	$day = ( $year + floor( $year / 4 ) - floor( $year / 100 ) + floor( $year / 400 ) + floor( 2.6 * $month + 1.6 ) + $date ) % 7 ;

	$this->day = $day ;
	if( $day == 0 ) $this->daytype = 2 ;
	else if( $day == 6 ) $this->daytype = 1 ;
	else $this->daytype = 0 ;

	if( isset( $this->holidays[ $this->caldate ] ) ) $this->daytype = 3 ;
}



// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¼ï¿½ï¿½à¤«ï¿½ï¿½ï¿½Ø·Ê¿ï¿½ï¿½ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function daytype_to_colors( $daytype )
{
	switch( $daytype ) {
		case 3 :
			//	Holiday
			return array( $this->holiday_bgcolor , $this->holiday_color ) ;
		case 2 :
			//	Sunday
			return array( $this->sunday_bgcolor , $this->sunday_color ) ;
		case 1 :
			//	Saturday
			return array( $this->saturday_bgcolor , $this->saturday_color ) ;
		case 0 :
		default :
			// Weekday
			return array( $this->weekday_bgcolor , $this->weekday_color ) ;
	}
}



// SQLï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Õ¤ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¼ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ë¥¯ï¿½é¥¹ï¿½Ø¿ï¿½
function get_daytype( $date )
{
	preg_match( "/^([0-9][0-9]+)[-.\/]?([0-1]?[0-9])[-.\/]?([0-3]?[0-9])$/" , $date , $regs ) ;
	$year = intval( $regs[1] ) ;
	$month = intval( $regs[2] ) ;
	$date = intval( $regs[3] ) ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½3
	if( isset( $this->holidays[ "$year-$month-$date" ] ) ) return 3 ;

	// ï¿½Ä¥ï¿½ï¿½é¡¼ï¿½Î¸ï¿½
	if ($month <= 2) {
		$year -- ;
		$month += 12;
	}
	$day = ( $year + floor( $year / 4 ) - floor( $year / 100 ) + floor( $year / 400 )+ floor( 2.6 * $month + 1.6 ) + $date ) % 7 ;

	if( $day == 0 ) return 2 ;
	else if( $day == 6 ) return 1 ;
	else return 0 ;
}



/*******************************************************************/
/*        ï¿½Ö¥ï¿½Ã¥ï¿½ï¿½ï¿½É½ï¿½ï¿½ï¿½Ø¿ï¿½                                       */
/*******************************************************************/

// $this->caldateï¿½ï¿½ï¿½Í½ï¿½ï¿½ ï¿½ï¿½ï¿½Ö¤ï¿½
function get_date_schedule( $get_target = '' )
{
	// if( $get_target == '' ) $get_target = $_SERVER['SCRIPT_NAME'] ;

	$ret = '' ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½×»ï¿½ï¿½ï¿½ï¿½Ä¤Ä¡ï¿½WHEREï¿½ï¿½Î´ï¿½Ö¤Ë´Ø¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$tzoffset = ( $this->user_TZ - $this->server_TZ ) * 3600 ;
	if( $tzoffset == 0 ) {
		// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½ï¿½ ï¿½ï¿½MySQLï¿½ï¿½ï¿½ï¿½Ù¤ò¤«¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½ï¿½á¡¢ï¿½ï¿½ï¿½ï¿½ï¿½Ç¾ï¿½ï¿½Ê¬ï¿½ï¿½ï¿½ï¿½ï¿½È¤ï¿½)
		$whr_term = "start<'".($this->unixtime + 86400)."' AND end>'$this->unixtime'" ;
	} else {
		// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï¡ï¿½alldayï¿½Ë¤ï¿½Ã¤Æ¾ï¿½ï¿½Ê¬ï¿½ï¿½
		$whr_term = "( allday AND start<='$this->unixtime' AND end>'$this->unixtime') OR ( ! allday AND start<'".($this->unixtime + 86400 - $tzoffset )."' AND end>'".($this->unixtime - $tzoffset )."')" ;
	}

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_categories = $this->get_where_about_categories() ;

	// CLASSï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_class = $this->get_where_about_class() ;

	// ï¿½ï¿½ï¿½ï¿½Î¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½å¡¼ï¿½ï¿½ï¿½ï¿½ï¿½
	$yrs = mysql_query( "SELECT start,end,summary,id,allday FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start,end" , $this->conn ) ;
	$num_rows = mysql_num_rows( $yrs ) ;

	if( $num_rows == 0 ) $ret .= _APCAL_MB_NOEVENT."\n" ;
	else while( $event = mysql_fetch_object( $yrs ) ) {

		$summary = $this->text_sanitizer_for_show( $event->summary ) ;

		if( $event->allday ) {
			// ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½ï¿½
			$ret .= "
	       <table border='0' cellpadding='0' cellspacing='0' width='100%'>
	         <tr>
	           <td><img border='0' src='$this->images_url/dot_allday.gif' /> &nbsp; </td>
	           <td><font size='2'><a href='$get_target?cid=$this->now_cid&amp;smode=Daily&amp;action=View&amp;event_id=$event->id&amp;caldate=$this->caldate' class='calsummary_allday'>$summary</a></font></td>
	         </tr>
	       </table>\n" ;
		} else {
			// ï¿½Ì¾ï¥¤ï¿½Ù¥ï¿½ï¿½
			$event->start += $tzoffset ;
			$event->end += $tzoffset ;
			$ret .= "
	       <dl>
	         <dt>
	           <font size='2'>".$this->get_todays_time_description( $event->start , $event->end , $this->caldate , false , true )."</font>
	         </dt>
	         <dd>
	           <font size='2'><a href='$get_target?cid=$this->now_cid&amp;smode=Daily&amp;action=View&amp;event_id=$event->id&amp;caldate=$this->caldate' class='calsummary'>$summary</a></font>
	         </dd>
	       </dl>\n" ;
		}
	}

	// Í½ï¿½ï¿½ï¿½ï¿½É²Ã¡Ê±ï¿½É®ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	if( $this->insertable ) $ret .= "
	       <dl>
	         <dt>
	           &nbsp; <font size='2'><a href='$get_target?smode=Daily&amp;action=Edit&amp;caldate=$this->caldate'><img src='$this->images_url/addevent.gif' border='0' width='14' height='12' />"._APCAL_MB_ADDEVENT."</a></font>
	         </dt>
	       </dl>\n" ;

	return $ret ;
}



// $this->caldateï¿½Ê¹ß¤ï¿½Í½ï¿½ï¿½ ï¿½ï¿½ï¿½ï¿½ï¿½ $num ï¿½ï¿½ï¿½Ö¤ï¿½
function get_coming_schedule( $get_target = '' , $num = 5 )
{
	// if( $get_target == '' ) $get_target = $_SERVER['SCRIPT_NAME'] ;

	$ret = '' ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½×»ï¿½ï¿½ï¿½ï¿½Ä¤Ä¡ï¿½WHEREï¿½ï¿½Î´ï¿½Ö¤Ë´Ø¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$tzoffset = ( $this->user_TZ - $this->server_TZ ) * 3600 ;
	if( $tzoffset == 0 ) {
		// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½ï¿½ ï¿½ï¿½MySQLï¿½ï¿½ï¿½ï¿½Ù¤ò¤«¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½ï¿½á¡¢ï¿½ï¿½ï¿½ï¿½ï¿½Ç¾ï¿½ï¿½Ê¬ï¿½ï¿½ï¿½ï¿½ï¿½È¤ï¿½)
		$whr_term = "end>'$this->unixtime'" ;
	} else {
		// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï¡ï¿½alldayï¿½Ë¤ï¿½Ã¤Æ¾ï¿½ï¿½Ê¬ï¿½ï¿½
		$whr_term = "(allday AND end>'$this->unixtime') OR ( ! allday AND end>'".($this->unixtime - $tzoffset )."')" ;
	}

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_categories = $this->get_where_about_categories() ;

	// CLASSï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_class = $this->get_where_about_class() ;

	// ï¿½ï¿½ï¿½ï¿½Ê¹ß¤Î¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½å¡¼ï¿½ï¿½ï¿½ï¿½ï¿½
	$yrs = mysql_query( "SELECT start,end,summary,id,allday FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start" , $this->conn ) ;
	$num_rows = mysql_num_rows( $yrs ) ;

	if( $num_rows == 0 ) $ret .= _APCAL_MB_NOEVENT."\n" ;
	else for( $i = 0 ; $i < $num ; $i ++ ) {
		$event = mysql_fetch_object( $yrs ) ;
		if( $event == false ) break ;
		$summary = $this->text_sanitizer_for_show( $event->summary ) ;

		if( $event->allday ) {
			// ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½ï¿½
			$ret .= "
	       <dl>
	         <dt>
	           <font size='2'><img border='0' src='$this->images_url/dot_allday.gif' /> ".$this->get_middle_md( $event->start )."</font>
	         </dt>
	         <dd>
	           <font size='2'><a href='$get_target?cid=$this->now_cid&amp;smode=Daily&amp;action=View&amp;event_id=$event->id&amp;caldate=$this->caldate' class='calsummary_allday'>$summary</a></font>
	         </dd>
	       </dl>\n" ;
		} else {
			// ï¿½Ì¾ï¥¤ï¿½Ù¥ï¿½ï¿½
			$event->start += $tzoffset ;
			$event->end += $tzoffset ;
			$ret .= "
	       <dl>
	         <dt>
	           <font size='2'>".$this->get_coming_time_description( $event->start , $this->unixtime )."</font>
	         </dt>
	         <dd>
	           <font size='2'><a href='$get_target?cid=$this->now_cid&amp;smode=Daily&amp;action=View&amp;event_id=$event->id&amp;caldate=$this->caldate' class='calsummary'>$summary</a></font>
	         </dd>
	       </dl>\n" ;
		}
	}

	// ï¿½Ä¤ï¿½ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½
	if( $num_rows > $num ) $ret .= "
           <table border='0' cellspacing='0' cellpadding='0' width='100%'>
            <tr>
             <td align='right'><small>"._APCAL_MB_RESTEVENT_PRE.($num_rows-$num)._APCAL_MB_RESTEVENT_SUF."</small></td>
            </tr>
           </table>\n" ;

	// Í½ï¿½ï¿½ï¿½ï¿½É²Ã¡Ê±ï¿½É®ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	if( $this->insertable ) $ret .= "
	       <dl>
	         <dt>
	           &nbsp; <font size='2'><a href='$get_target?smode=Daily&amp;action=Edit&amp;caldate=$this->caldate'><img src='$this->images_url/addevent.gif' border='0' width='14' height='12' />"._APCAL_MB_ADDEVENT."</a></font>
	         </dt>
	       </dl>\n" ;

	return $ret ;
}



// ï¿½ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ñ¥ï¿½ï¿½Ù¥ï¿½È¼ï¿½ï¿½ï¿½ï¿½Ø¿ï¿½
function get_flags_date_has_events( $range_start_s , $range_end_s , $mode = '' )
{
	// ï¿½ï¿½ï¿½é¤«ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ¤ï¿½ï¿½ï¿½
	/* for( $time = $start ; $time < $end ; $time += 86400 ) {
		$ret[ date( 'j' , $time ) ] = 0 ;
	} */
	for( $i = 0 ; $i <= 31 ; $i ++ ) {
		$ret[ $i ] = 0 ;
	}

	// add margin -86400 and +86400 
	$range_start_s -= 86400 ;
	$range_end_s += 86400 ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½×»ï¿½
	$tzoffset_s2u = intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) ;
	//$gmtoffset = intval( $this->server_TZ * 3600 ) ;

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_categories = $this->get_where_about_categories() ;

	// CLASSï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_class = $this->get_where_about_class() ;

/*	$yrs = mysql_query( "SELECT start,end,allday FROM $this->table WHERE admission > 0 AND start < ".($end + 86400)." AND end > ".($start - 86400)." AND ($whr_categories) AND ($whr_class)" , $this->conn ) ;
	while( $event = mysql_fetch_object( $yrs ) ) {
		$time = $event->start > $start ? $event->start : $start ;
		if( ! $event->allday ) {
			$time += $tzoffset ;
			$event->end += $tzoffset ;
		}
		$time -= ( $time + $gmtoffset ) % 86400 ;
		while( $time < $end && $time < $event->end ) {
			$ret[ date( 'j' , $time ) ] = 1 ;
			$time += 86400 ;
		}
	}*/

	

	// ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½È°Ê³ï¿½ï¿½Î½ï¿½ï¿½ï¿½
	$result = mysql_query( "SELECT summary,id,start,location,contact,gmlat,gmlong FROM $this->table WHERE admission > 0 AND start >= $range_start_s AND start < $range_end_s AND ($whr_categories) AND ($whr_class) AND allday <= 0" , $this->conn ) ;
    while( list( $title , $id , $server_time, $location, $contact, $gmlat, $gmlong ) = mysql_fetch_row( $result ) ) {
        if($mode == 'NO_YEAR' && ($gmlat > 0 || $gmlong > 0))
            $this->gmPoints[] = array('summary' => $title, 'gmlat' => $gmlat, 'gmlong' => $gmlong, 'location' => $location, 'contact' => $contact, 'startDate' => date('j', $server_time), 'event_id' => $id);
		$user_time = $server_time + $tzoffset_s2u ;
		if( date( 'n' , $user_time ) != $this->month ) continue ;
		$ret[ date('j',$user_time) ] = 1 ;
	}


	// ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½ï¿½ï¿½ï¿½ï¿½Ñ¤Î½ï¿½ï¿½ï¿½
	$result = mysql_query( "SELECT summary,id,start,end,location,contact,gmlat,gmlong FROM $this->table WHERE admission > 0 AND start >= $range_start_s AND start < $range_end_s AND ($whr_categories) AND ($whr_class) AND allday > 0" , $this->conn ) ;

	while( list( $title , $id , $start_s , $end_s, $location, $contact, $gmlat, $gmlong ) = mysql_fetch_row( $result ) ) {
		if( $start_s < $range_start_s ) $start_s = $range_start_s ;
		if( $end_s > $range_end_s ) $end_s = $range_end_s ;

		while( $start_s < $end_s ) {
			$user_time = $start_s + $tzoffset_s2u ;
			if( date( 'n' , $user_time ) == $this->month ) {
                if($mode == 'NO_YEAR' && ($gmlat > 0 || $gmlong > 0))
                    $this->gmPoints[] = array('summary' => $title, 'gmlat' => $gmlat, 'gmlong' => $gmlong, 'location' => $location, 'contact' => $contact, 'startDate' => date('j', $user_time), 'event_id' => $id);
				$ret[ date('j',$user_time) ] = 1 ;
			}
			$start_s += 86400 ;
		}
	}

	return $ret ;
}



// ï¿½ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½ï¿½ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
function get_mini_calendar_html( $get_target = '' , $query_string = '' , $mode = '' )
{
	// ï¿½Â¹Ô»ï¿½ï¿½Ö·ï¿½Â¬ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	// list( $usec , $sec ) = explode( " " , microtime() ) ;
	// $apcalstarttime = $sec + $usec ;

	// $PHP_SELF = $_SERVER['SCRIPT_NAME'] ;
	// if( $get_target == '' ) $get_target = $PHP_SELF ;

	require_once( "$this->base_path/include/patTemplate.php" ) ;
	$tmpl = new PatTemplate() ;
	$tmpl->setBasedir( "$this->images_path" ) ;

	// É½ï¿½ï¿½ï¿½â¡¼ï¿½É¤Ë±ï¿½ï¿½ï¿½ï¿½Æ¡ï¿½ï¿½Æ¥ï¿½×¥ì¡¼ï¿½È¥Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ò¿¶¤ï¿½Ê¬ï¿½ï¿½
	switch( $mode ) {
		case 'NO_YEAR' :
			// Ç¯ï¿½ï¿½É½ï¿½ï¿½ï¿½ï¿½
			$tmpl->readTemplatesFromFile( "minical_for_yearly.tmpl.html" ) ;
			$target_highlight_flag = false ;
			break ;
		case 'NO_NAVIGATE' :
			// ï¿½ï¿½Ö¤Î²ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
			$tmpl->readTemplatesFromFile( "minical_for_monthly.tmpl.html" ) ;
			$target_highlight_flag = false ;
			break ;
		default :
			// ï¿½Ì¾ï¿½Î¥ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¥ï¿½Ã¥ï¿½ï¿½ï¿½
			$tmpl->readTemplatesFromFile( "minical.tmpl.html" ) ;
			$target_highlight_flag = true ;
			break ;
	}

	// ï¿½ï¿½ï¿½ï¿½Î³ï¿½ï¿½ï¿½Ù¥ï¿½È¤ï¿½ï¿½Ã¤Æ¤ï¿½ï¿½ë¤«ï¿½É¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$event_dates = $this->get_flags_date_has_events( mktime(0,0,0,$this->month,1,$this->year) , mktime(0,0,0,$this->month+1,1,$this->year) , $mode ) ;

	// ï¿½ï¿½ï¿½ï¿½Ï·ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï·ï¿½ï¿½È¤ï¿½ï¿½ï¿½
	$prev_month = date("Y-n-j", mktime(0,0,0,$this->month,0,$this->year));
	$next_month = date("Y-n-j", mktime(0,0,0,$this->month+1,1,$this->year));

	// $tmpl->addVar( "WholeBoard" , "PHP_SELF" , '' ) ;
    $tmpl->addVar( "WholeBoard" , "DAY_URL" , substr($this->make_cal_link($get_target, 'Monthly', $this->now_cid, ' '), 0, -1)) ;
	$tmpl->addVar( "WholeBoard" , "GET_TARGET" , $get_target ) ;
	$tmpl->addVar( "WholeBoard" , "QUERY_STRING" , $query_string ) ;

	$tmpl->addVar( "WholeBoard" , "MB_PREV_MONTH" , _APCAL_MB_PREV_MONTH ) ;
	$tmpl->addVar( "WholeBoard" , "MB_NEXT_MONTH" , _APCAL_MB_NEXT_MONTH ) ;
	$tmpl->addVar( "WholeBoard" , "MB_LINKTODAY" , _APCAL_MB_LINKTODAY ) ;

	$tmpl->addVar( "WholeBoard" , "SKINPATH" , $this->images_url ) ;
	$tmpl->addVar( "WholeBoard" , "FRAME_CSS" , $this->frame_css ) ;
//	$tmpl->addVar( "WholeBoard" , "YEAR" , $this->year ) ;
//	$tmpl->addVar( "WholeBoard" , "MONTH" , $this->month ) ;
	$tmpl->addVar( "WholeBoard" , "MONTH_NAME" , $this->month_middle_names[ $this->month ] ) ;
	$tmpl->addVar( "WholeBoard" , "YEAR_MONTH_TITLE" , sprintf( _APCAL_FMT_YEAR_MONTH , $this->year , $this->month_middle_names[ $this->month ] ) ) ;
	$tmpl->addVar( "WholeBoard" , "PREV_MONTH" , $prev_month ) ;
	$tmpl->addVar( "WholeBoard" , "NEXT_MONTH" , $next_month ) ;

	$tmpl->addVar( "WholeBoard" , "CALHEAD_BGCOLOR" , $this->calhead_bgcolor ) ;
	$tmpl->addVar( "WholeBoard" , "CALHEAD_COLOR" , $this->calhead_color ) ;


	$first_date = getdate(mktime(0,0,0,$this->month,1,$this->year));
	$date = ( - $first_date['wday'] + $this->week_start - 7 ) % 7 ;
	$wday_end = 7 + $this->week_start ;

	// ï¿½ï¿½ï¿½ï¿½Ì¾ï¿½ë¡¼ï¿½ï¿½
	$rows = array() ;
	for( $wday = $this->week_start ; $wday < $wday_end ; $wday ++ ) {
		if( $wday % 7 == 0 ) { 
			//	Sunday
			$bgcolor = $this->sunday_bgcolor ;
			$color = $this->sunday_color ;
		} elseif( $wday == 6 ) { 
			//	Saturday
			$bgcolor = $this->saturday_bgcolor ;
			$color = $this->saturday_color ;
		} else { 
			// Weekday
			$bgcolor = $this->weekday_bgcolor ;
			$color = $this->weekday_color ;
		}

		// ï¿½Æ¥ï¿½×¥ì¡¼ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ø¤Î¥Ç¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
		array_push( $rows , array(
			"BGCOLOR" => $bgcolor ,
			"COLOR" => $color ,
			"DAYNAME" => $this->week_short_names[ $wday % 7 ] ,
		) ) ;
	}

	// ï¿½Æ¥ï¿½×¥ì¡¼ï¿½È¤Ë¥Ç¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$tmpl->addRows( "DayNameLoop" , $rows ) ;
	$tmpl->parseTemplate( "DayNameLoop" , 'w' ) ;

	// ï¿½ï¿½ (row) ï¿½ë¡¼ï¿½ï¿½
	for( $week = 0 ; $week < 6 ; $week ++ ) {

		$rows = array() ;

		// ï¿½ï¿½ (col) ï¿½ë¡¼ï¿½ï¿½
		for( $wday = $this->week_start ; $wday < $wday_end ; $wday ++ ) {
			$date ++ ;
			if( ! checkdate($this->month,$date,$this->year) ) {
				// ï¿½ï¿½ï¿½ï¿½Ï°Ï³ï¿½
				array_push( $rows , array(
					"GET_TARGET" => $get_target ,
					"QUERY_STRING" => $query_string ,
					"SKINPATH" => $this->images_url ,
					"DATE" => date( 'j' , mktime( 0 , 0 , 0 , $this->month , $date , $this->year ) ) ,
					"DATE_TYPE" => 0
				) ) ;
				continue ;
			}

			$link = "$this->year-$this->month-$date" ;

			// ï¿½ï¿½ï¿½ï¿½×¤Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½è¿§ï¿½ï¿½ï¿½ï¿½Ê¬ï¿½ï¿½
			if( isset( $this->holidays[$link] ) ) {
				//	Holiday
				$bgcolor = $this->holiday_bgcolor ;
				$color = $this->holiday_color ;
			} elseif( $wday % 7 == 0 ) { 
				//	Sunday
				$bgcolor = $this->sunday_bgcolor ;
				$color = $this->sunday_color ;
			} elseif( $wday == 6 ) { 
				//	Saturday
				$bgcolor = $this->saturday_bgcolor ;
				$color = $this->saturday_color ;
			} else { 
				// Weekday
				$bgcolor = $this->weekday_bgcolor ;
				$color = $this->weekday_color ;
			}

			// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ø·Ê¿ï¿½ï¿½Ï¥ï¿½ï¿½é¥¤ï¿½È½ï¿½ï¿½ï¿½
			if( $date == $this->date && $target_highlight_flag ) $bgcolor = $this->targetday_bgcolor ;

			// ï¿½Æ¥ï¿½×¥ì¡¼ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ø¤Î¥Ç¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
			array_push( $rows , array(
				"GET_TARGET" => $get_target ,
				"QUERY_STRING" => $query_string ,
                "DAY_URL" => substr($this->make_cal_link($get_target, ($mode == 'NO_YEAR' ? 'Daily' : 'Monthly'), $this->now_cid, ' '), 0, -1),

				"BGCOLOR" => $bgcolor ,
				"COLOR" => $color ,
				"LINK" => $link ,
				"DATE" => $date ,
				"DATE_TYPE" => $event_dates[ $date ] + 1
			) ) ;
		}
		// ï¿½Æ¥ï¿½×¥ì¡¼ï¿½È¤Ë¥Ç¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
		$tmpl->addRows( "DailyLoop" , $rows ) ;
		$tmpl->parseTemplate( "DailyLoop" , 'w' ) ;
		$tmpl->parseTemplate( "WeekLoop" , 'a' ) ;
	}

	$ret = $tmpl->getParsedTemplate() ;

	// ï¿½Â¹Ô»ï¿½ï¿½Öµï¿½Ï¿
	// list( $usec , $sec ) = explode( " " , microtime() ) ;
	// error_log( "MiniCalendar " . ( $sec + $usec - $apcalstarttime ) . "sec." , 0 ) ;

	return $ret ;
}



/*******************************************************************/
/*        ï¿½á¥¤ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½ï¿½Ø¿ï¿½                                         */
/*******************************************************************/

// Ç¯ï¿½Ö¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¤ï¿½É½ï¿½ï¿½ï¿½ï¿½patTemplateï¿½ï¿½ï¿½ï¿½)
function get_yearly( $get_target = '' , $query_string = '' , $for_print = false )
{
	// $PHP_SELF = $_SERVER['SCRIPT_NAME'] ;
	// if( $get_target == '' ) $get_target = $PHP_SELF ;

	require_once( "$this->base_path/include/patTemplate.php" ) ;
	$tmpl = new PatTemplate() ;
	$tmpl->readTemplatesFromFile( "$this->images_path/yearly.tmpl.html" ) ;

	// setting skin folder
	$tmpl->addVar( "WholeBoard" , "SKINPATH" , $this->images_url ) ;

	// Static parameter for the request
	$tmpl->addVar( "WholeBoard" , "GET_TARGET" , $get_target ) ;
	$tmpl->addVar( "WholeBoard" , "QUERY_STRING" , $query_string ) ;
	$tmpl->addVar( "WholeBoard" , "PRINT_LINK" , "$this->base_url/print.php?cid=$this->now_cid&amp;smode=Yearly&amp;caldate=$this->caldate" ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_PRINT" , _APCAL_BTN_PRINT ) ;
	if( $for_print ) $tmpl->addVar( "WholeBoard" , "PRINT_ATTRIB" , "width='0' height='0'" ) ;

    $jumpScript = "<script type='text/javascript'>\n";
    $jumpScript .= "function submitCat(cid, smode, caldate)\n";
    $jumpScript .= "{\n";
    if($this->useurlrewrite)
    {
        $jumpScript .= "document.selectDate.action = '".XOOPS_URL."/' + cid + '/' + smode + '/' + caldate;\n";
        $jumpScript .= "document.selectDate.method = 'POST';\n";
    }
    $jumpScript .= "return true;\n";
    $jumpScript .= "}\n";
    $jumpScript .= "</script>\n";

    $prevYear = date("Y-n-j", mktime(0,0,0,$this->month,$this->date,$this->year-1));
	$nextYear = date("Y-n-j", mktime(0,0,0,$this->month,$this->date,$this->year+1));
    $tmpl->addVar( "WholeBoard" , "JUMPLINK" , $this->make_cal_link($get_target, 'Yearly', $this->now_cid, date('Y-n-j')));
    $tmpl->addVar( "WholeBoard" , "TODAYLINK" , $this->make_cal_link($get_target, 'Yearly', $this->now_cid, date('Y-n-j')));
    $tmpl->addVar( "WholeBoard" , "PREVIOUSYEARLINK" , $this->make_cal_link($get_target, 'Yearly', $this->now_cid, $prevYear));
    $tmpl->addVar( "WholeBoard" , "NEXTYEARLINK" , $this->make_cal_link($get_target, 'Yearly', $this->now_cid, $nextYear));
    $tmpl->addVar( "WholeBoard" , "MONTHLYVIEW" , $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "WEEKLYVIEW" , $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "DAILYVIEW" , $this->make_cal_link($get_target, 'Daily', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "LISTVIEW" , $this->make_cal_link($get_target, 'List', $this->now_cid, $this->caldate));

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½ï¿½ï¿½Ü¥Ã¥ï¿½ï¿½ï¿½
	$tmpl->addVar( "WholeBoard" , "CATEGORIES_SELFORM" , $this->get_categories_selform( $get_target ) ) ;
	$tmpl->addVar( "WholeBoard" , "CID" , $this->now_cid ) ;

	// Variables required in header part etc.
	$tmpl->addVars( "WholeBoard" , $this->get_calendar_information( 'Y' ) ) ;

	$tmpl->addVar( "WholeBoard" , "LANG_JUMP" , _APCAL_BTN_JUMP ) ;

	// ï¿½Æ·ï¿½Î¥ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	// $this->caldate ï¿½Î¥Ð¥Ã¥ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
	$backuped_caldate = $this->caldate ;

	// 12ï¿½ï¿½ï¿½ï¿½Ê¬ï¿½Î¥ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ë¡¼ï¿½ï¿½
	for( $m = 1 ; $m <= 12 ; $m ++ ) {
		$this->set_date( date("Y-n-j", mktime(0,0,0,$m,1,$this->year)) ) ;
		$tmpl->addVar( "WholeBoard" , "MINICAL$m" , $this->get_mini_calendar_html( $get_target , $query_string , "NO_YEAR" ) ) ;
	}

	// $this->caldate ï¿½Î¥ê¥¹ï¿½È¥ï¿½
	$this->set_date( $backuped_caldate ) ;

	// content generated from patTemplate
	$ret = $tmpl->getParsedTemplate( "WholeBoard" ) ;

	return $ret ;
}



// ï¿½ï¿½Ö¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¤ï¿½É½ï¿½ï¿½ï¿½ï¿½patTemplateï¿½ï¿½ï¿½ï¿½)
function get_monthly( $get_target = '' , $query_string = '' , $for_print = false )
{
	// $PHP_SELF = $_SERVER['SCRIPT_NAME'] ;
	// if( $get_target == '' ) $get_target = $PHP_SELF ;
    
    if(isset($_POST['startDate'])) 
    {
        $date = explode('-', $_POST['startDate']);
        $this->year = $date[0];
        $this->month = $date[1];
        $this->day = $date[2];
        $this->caldate = $_POST['startDate'];
    }
    
	require_once( "$this->base_path/include/patTemplate.php" ) ;
	$tmpl = new PatTemplate() ;
	$tmpl->readTemplatesFromFile( "$this->images_path/monthly.tmpl.html" ) ;

	// setting skin folder
	$tmpl->addVar( "WholeBoard" , "SKINPATH" , $this->images_url ) ;

	// Static parameter for the request
	$tmpl->addVar( "WholeBoard" , "GET_TARGET" , $get_target ) ;
	$tmpl->addVar( "WholeBoard" , "QUERY_STRING" , $query_string ) ;
	$tmpl->addVar( "WholeBoard" , "YEAR_MONTH_TITLE" , sprintf( _APCAL_FMT_YEAR_MONTH , $this->year , $this->month_middle_names[ $this->month ] ) ) ;
	$tmpl->addVar( "WholeBoard" , "PRINT_LINK" , "$this->base_url/print.php?cid=$this->now_cid&amp;smode=Monthly&amp;caldate=$this->caldate" ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_PRINT" , _APCAL_BTN_PRINT ) ;
	if( $for_print ) $tmpl->addVar( "WholeBoard" , "PRINT_ATTRIB" , "width='0' height='0'" ) ;

    $prevYear = date("Y-n-j", mktime(0,0,0,$this->month,$this->date,$this->year-1));
	$nextYear = date("Y-n-j", mktime(0,0,0,$this->month,$this->date,$this->year+1));
	$prevMonth = date("Y-n-j", mktime(0,0,0,$this->month,0,$this->year));
	$nextMonth = date("Y-n-j", mktime(0,0,0,$this->month+1,1,$this->year));
    $tmpl->addVar( "WholeBoard" , "TODAYLINK" , $this->make_cal_link($get_target, 'Monthly', $this->now_cid, date('Y-n-j')));
    $tmpl->addVar( "WholeBoard" , "PREVIOUSYEARLINK" , $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $prevYear));
    $tmpl->addVar( "WholeBoard" , "PREVIOUSMONTHLINK" , $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $prevMonth));
    $tmpl->addVar( "WholeBoard" , "NEXTYEARLINK" , $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $nextYear));
    $tmpl->addVar( "WholeBoard" , "NEXTMONTHLINK" , $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $nextMonth));
    $tmpl->addVar( "WholeBoard" , "YEARLYVIEW" , $this->make_cal_link($get_target, 'Yearly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "WEEKLYVIEW" , $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "DAILYVIEW" , $this->make_cal_link($get_target, 'Daily', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "LISTVIEW" , $this->make_cal_link($get_target, 'List', $this->now_cid, $this->caldate));

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½ï¿½ï¿½Ü¥Ã¥ï¿½ï¿½ï¿½
	$tmpl->addVar( "WholeBoard" , "CATEGORIES_SELFORM" , $this->get_categories_selform( $get_target ) ) ;
	$tmpl->addVar( "WholeBoard" , "CID" , $this->now_cid ) ;

	// Variables required in header part etc.
	$tmpl->addVars( "WholeBoard" , $this->get_calendar_information( 'M' ) ) ;

	$tmpl->addVar( "WholeBoard" , "LANG_JUMP" , _APCAL_BTN_JUMP ) ;

	// BODY of the calendar
	$tmpl->addVar( "WholeBoard" , "CALENDAR_BODY" , $this->get_monthly_html( $get_target , $query_string, $for_print ) ) ;

	// legends of long events
	foreach( $this->long_event_legends as $bit => $legend ) {
		$tmpl->addVar( "LongEventLegends" , "BIT_MASK" , 1 << ( $bit - 1 ) ) ;
		$tmpl->addVar( "LongEventLegends" , "LEGEND_ALT" , _APCAL_MB_ALLDAY_EVENT . " $bit" ) ;
		$tmpl->addVar( "LongEventLegends" , "LEGEND" , $legend ) ;
		$tmpl->addVar( "LongEventLegends" , "SKINPATH" , $this->images_url ) ;
		$tmpl->parseTemplate( "LongEventLegends" , "a" ) ;
	}

	// ï¿½ï¿½î¡¦ï¿½ï¿½ï¿½Î¥ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	// $this->caldate ï¿½Î¥Ð¥Ã¥ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
	$backuped_caldate = $this->caldate ;
	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Õ¤ò¥»¥Ã¥È¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¥ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ò¥»¥Ã¥ï¿½
	$this->set_date( date("Y-n-j", mktime(0,0,0,$this->month,0,$this->year)) ) ;
	$tmpl->addVar( "WholeBoard" , "PREV_MINICAL" , $this->get_mini_calendar_html( $get_target , $query_string , "NO_NAVIGATE" ) ) ;
	// ï¿½ï¿½ï¿½Ï¤ï¿½ï¿½ï¿½ï¿½Õ¤ò¥»¥Ã¥È¤ï¿½ï¿½ï¿½ï¿½ß¥Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½
	$this->set_date( date("Y-n-j", mktime(0,0,0,$this->month+2,1,$this->year)) ) ;
	$tmpl->addVar( "WholeBoard" , "NEXT_MINICAL" , $this->get_mini_calendar_html( $get_target , $query_string , "NO_NAVIGATE" ) ) ;
	// $this->caldate ï¿½Î¥ê¥¹ï¿½È¥ï¿½
	$this->set_date( $backuped_caldate ) ;

	// content generated from patTemplate
	$ret = $tmpl->getParsedTemplate( "WholeBoard" ) ;

	return $ret ;
}



// ï¿½ï¿½ï¿½Ö¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¤ï¿½É½ï¿½ï¿½ï¿½ï¿½patTemplateï¿½ï¿½ï¿½ï¿½)
function get_weekly( $get_target = '' , $query_string = '' , $for_print = false )
{
	// $PHP_SELF = $_SERVER['SCRIPT_NAME'] ;
	// if( $get_target == '' ) $get_target = $PHP_SELF ;

	require_once( "$this->base_path/include/patTemplate.php" ) ;
	$tmpl = new PatTemplate() ;
	$tmpl->readTemplatesFromFile( "$this->images_path/weekly.tmpl.html" ) ;

	// setting skin folder
	$tmpl->addVar( "WholeBoard" , "SKINPATH" , $this->images_url ) ;

	// Static parameter for the request
	$tmpl->addVar( "WholeBoard" , "GET_TARGET" , $get_target ) ;
	$tmpl->addVar( "WholeBoard" , "QUERY_STRING" , $query_string ) ;
	$tmpl->addVar( "WholeBoard" , "PRINT_LINK" , "$this->base_url/print.php?cid=$this->now_cid&amp;smode=Weekly&amp;caldate=$this->caldate" ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_PRINT" , _APCAL_BTN_PRINT ) ;
	if( $for_print ) $tmpl->addVar( "WholeBoard" , "PRINT_ATTRIB" , "width='0' height='0'" ) ;

	$prevMonth = date("Y-n-j", mktime(0,0,0,$this->month,0,$this->year));
	$nextMonth = date("Y-n-j", mktime(0,0,0,$this->month+1,1,$this->year));
	$prevWeek = date("Y-n-j", mktime(0,0,0,$this->month,$this->date-7,$this->year));
	$nextWeek = date("Y-n-j", mktime(0,0,0,$this->month,$this->date+7,$this->year));
    $tmpl->addVar( "WholeBoard" , "TODAYLINK" , $this->make_cal_link($get_target, 'Weekly', $this->now_cid, date('Y-n-j')));
    $tmpl->addVar( "WholeBoard" , "PREVIOUSMONTHLINK" , $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $prevMonth));
    $tmpl->addVar( "WholeBoard" , "PREVIOUSWEEKLINK" , $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $prevWeek)) ;
    $tmpl->addVar( "WholeBoard" , "NEXTWEEKLINK" , $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $nextWeek));
    $tmpl->addVar( "WholeBoard" , "NEXTMONTHLINK" , $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $nextMonth));
    $tmpl->addVar( "WholeBoard" , "YEARLYVIEW" , $this->make_cal_link($get_target, 'Yearly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "MONTHLYVIEW" , $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard" , "DAILYVIEW" , $this->make_cal_link($get_target, 'Daily', $this->now_cid, $this->caldate)) ;
    $tmpl->addVar( "WholeBoard" , "LISTVIEW" , $this->make_cal_link($get_target, 'List', $this->now_cid, $this->caldate));

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½ï¿½ï¿½Ü¥Ã¥ï¿½ï¿½ï¿½
	$tmpl->addVar( "WholeBoard" , "CATEGORIES_SELFORM" , $this->get_categories_selform( $get_target ) ) ;
	$tmpl->addVar( "WholeBoard" , "CID" , $this->now_cid ) ;

	// Variables required in header part etc.
	$tmpl->addVars( "WholeBoard" , $this->get_calendar_information( 'W' ) ) ;

	$tmpl->addVar( "WholeBoard" , "LANG_JUMP" , _APCAL_BTN_JUMP ) ;

	// BODY of the calendar
	$tmpl->addVar( "WholeBoard" , "CALENDAR_BODY" , $this->get_weekly_html( $get_target , $query_string ) ) ;

	// content generated from patTemplate
	$ret = $tmpl->getParsedTemplate( "WholeBoard" ) ;

	return $ret ;
}



// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¤ï¿½É½ï¿½ï¿½ï¿½ï¿½patTemplateï¿½ï¿½ï¿½ï¿½)
function get_daily( $get_target = '' , $query_string = '' , $for_print = false )
{
	// $PHP_SELF = $_SERVER['SCRIPT_NAME'] ;
	// if( $get_target == '' ) $get_target = $PHP_SELF ;

	require_once( "$this->base_path/include/patTemplate.php" ) ;
	$tmpl = new PatTemplate() ;
	$tmpl->readTemplatesFromFile( "$this->images_path/daily.tmpl.html" ) ;

	// setting skin folder
	$tmpl->addVar( "WholeBoard" , "SKINPATH" , $this->images_url ) ;

	// Static parameter for the request
	$tmpl->addVar( "WholeBoard" , "GET_TARGET" , $get_target ) ;
	$tmpl->addVar( "WholeBoard" , "QUERY_STRING" , $query_string ) ;
	$tmpl->addVar( "WholeBoard" , "PRINT_LINK" , "$this->base_url/print.php?cid=$this->now_cid&amp;smode=Daily&amp;caldate=$this->caldate" ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_PRINT" , _APCAL_BTN_PRINT ) ;
	if( $for_print ) $tmpl->addVar( "WholeBoard" , "PRINT_ATTRIB" , "width='0' height='0'" ) ;

	$prevMonth = date("Y-n-j", mktime(0,0,0,$this->month,0,$this->year));
	$nextMonth = date("Y-n-j", mktime(0,0,0,$this->month+1,1,$this->year));
	$prevDay = date("Y-n-j", mktime(0,0,0,$this->month,$this->date-1,$this->year));
	$nextDay = date("Y-n-j", mktime(0,0,0,$this->month,$this->date+1,$this->year));
    $tmpl->addVar( "WholeBoard" , "TODAYLINK" , $this->make_cal_link($get_target, 'Daily', $this->now_cid, date('Y-n-j')));
    $tmpl->addVar( "WholeBoard", "PREVIOUSMONTHLINK", $this->make_cal_link($get_target, 'Daily', $this->now_cid, $prevMonth));
    $tmpl->addVar( "WholeBoard", "PREVIOUSDAYLINK", $this->make_cal_link($get_target, 'Daily', $this->now_cid, $prevDay));
    $tmpl->addVar( "WholeBoard", "NEXTDAYLINK", $this->make_cal_link($get_target, 'Daily', $this->now_cid, $nextDay));
    $tmpl->addVar( "WholeBoard", "NEXTMONTHLINK", $this->make_cal_link($get_target, 'Daily', $this->now_cid, $nextMonth));
    $tmpl->addVar( "WholeBoard", "YEARLYVIEW", $this->make_cal_link($get_target, 'Yearly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard", "MONTHLYVIEW", $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $this->caldate));
    $tmpl->addVar( "WholeBoard", "WEEKLYVIEW", $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $this->caldate)) ;
    $tmpl->addVar( "WholeBoard", "LISTVIEW", $this->make_cal_link($get_target, 'List', $this->now_cid, $this->caldate));

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½ï¿½ï¿½Ü¥Ã¥ï¿½ï¿½ï¿½
	$tmpl->addVar( "WholeBoard" , "CATEGORIES_SELFORM" , $this->get_categories_selform( $get_target ) ) ;
	$tmpl->addVar( "WholeBoard" , "CID" , $this->now_cid ) ;

	// Variables required in header part etc.
	$tmpl->addVars( "WholeBoard" , $this->get_calendar_information( 'D' ) ) ;

	$tmpl->addVar( "WholeBoard" , "LANG_JUMP" , _APCAL_BTN_JUMP ) ;

	// BODY of the calendar
	$tmpl->addVar( "WholeBoard" , "CALENDAR_BODY" , $this->get_daily_html( $get_target , $query_string ) ) ;

	// content generated from patTemplate
	$ret = $tmpl->getParsedTemplate( "WholeBoard" ) ;

	return $ret ;
}



// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¥Ø¥Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É¬ï¿½×¤Ê¾ï¿½ï¿½ï¿½ï¿½Ï¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½ï¿½Ê·ï¿½Ö¡ï¿½ï¿½ï¿½ï¿½Ö¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ì¡ï¿½
function get_calendar_information( $mode = 'M' )
{
	$ret = array() ;

	// ï¿½ï¿½ï¿½Ü¾ï¿½ï¿½ï¿½
	$ret[ 'TODAY' ] = date( "Y-n-j" ) ;		// GIJ TODO ï¿½×¼ï¿½Ä¾ï¿½ï¿½ï¿½Ê»È¤ï¿½Ê¤ï¿½ï¿½ï¿½ï¿½ï¿½
	$ret[ 'CALDATE' ] = $this->caldate ;
	$ret[ 'DISP_YEAR' ] = sprintf( _APCAL_FMT_YEAR , $this->year ) ;
	$ret[ 'DISP_MONTH' ] = $this->month_middle_names[ $this->month ] ;
	$ret[ 'DISP_DATE' ] = $this->date_long_names[ $this->date ] ;
	$ret[ 'DISP_DAY' ] = "({$this->week_middle_names[ $this->day ]})" ;
	list( $bgcolor , $color ) =  $this->daytype_to_colors( $this->daytype ) ;
	$ret[ 'DISP_DAY_COLOR' ] = $color ;
	$ret[ 'COPYRIGHT' ] = APCAL_COPYRIGHT ;

	// ï¿½Ø¥Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¥ï¿½ï¿½é¡¼
	$ret[ 'CALHEAD_BGCOLOR' ]  =  $this->calhead_bgcolor ;
	$ret[ 'CALHEAD_COLOR' ] = $this->calhead_color ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½alt(title)
	$ret[ 'ICON_LIST' ] = _APCAL_ICON_LIST ;
	$ret[ 'ICON_DAILY' ] = _APCAL_ICON_DAILY ;
	$ret[ 'ICON_WEEKLY' ] = _APCAL_ICON_WEEKLY ;
	$ret[ 'ICON_MONTHLY' ] = _APCAL_ICON_MONTHLY ;
	$ret[ 'ICON_YEARLY' ] = _APCAL_ICON_YEARLY ;

	// ï¿½ï¿½Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¥ï¿½Ã¥ï¿½
	$ret[ 'MB_PREV_YEAR' ] = _APCAL_MB_PREV_YEAR ;
	$ret[ 'MB_NEXT_YEAR' ] = _APCAL_MB_NEXT_YEAR ;
	$ret[ 'MB_PREV_MONTH' ] = _APCAL_MB_PREV_MONTH ;
	$ret[ 'MB_NEXT_MONTH' ] = _APCAL_MB_NEXT_MONTH ;
	$ret[ 'MB_PREV_WEEK' ] = _APCAL_MB_PREV_WEEK ;
	$ret[ 'MB_NEXT_WEEK' ] = _APCAL_MB_NEXT_WEEK ;
	$ret[ 'MB_PREV_DATE' ] = _APCAL_MB_PREV_DATE ;
	$ret[ 'MB_NEXT_DATE' ] = _APCAL_MB_NEXT_DATE ;
	$ret[ 'MB_LINKTODAY' ] = _APCAL_MB_LINKTODAY ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½Ø¤Î¥ï¿½ï¿½
	$ret[ 'PREV_YEAR' ] = date("Y-n-j", mktime(0,0,0,$this->month,$this->date,$this->year-1));
	$ret[ 'NEXT_YEAR' ] = date("Y-n-j", mktime(0,0,0,$this->month,$this->date,$this->year+1));
	$ret[ 'PREV_MONTH' ] = date("Y-n-j", mktime(0,0,0,$this->month,0,$this->year));
	$ret[ 'NEXT_MONTH' ] = date("Y-n-j", mktime(0,0,0,$this->month+1,1,$this->year));
	$ret[ 'PREV_WEEK' ] = date("Y-n-j", mktime(0,0,0,$this->month,$this->date-7,$this->year)) ;
	$ret[ 'NEXT_WEEK' ] = date("Y-n-j", mktime(0,0,0,$this->month,$this->date+7,$this->year)) ;
	$ret[ 'PREV_DATE' ] = date("Y-n-j", mktime(0,0,0,$this->month,$this->date-1,$this->year)) ;
	$ret[ 'NEXT_DATE' ] = date("Y-n-j", mktime(0,0,0,$this->month,$this->date+1,$this->year)) ;

	// ï¿½ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ñ¥Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½Î³Æ¥ï¿½ï¿½ï¿½È¥?ï¿½ï¿½
	// Ç¯ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î½ï¿½ï¿½ï¿½ï¿½
	if( empty( $_POST[ 'apcal_year' ] ) ) $year = $this->year ;
	else  $year = intval( $_POST[ 'apcal_year' ] ) ;
	if( empty( $_POST[ 'apcal_month' ] ) ) $month = $this->month ;
	else $month = intval( $_POST[ 'apcal_month' ] ) ;
	if( empty( $_POST[ 'apcal_date' ] ) ) $date = $this->date ;
	else $date = intval( $_POST[ 'apcal_date' ] ) ;

	// Ç¯ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½(2001ï¿½ï¿½2020 ï¿½È¤ï¿½ï¿½ï¿½)
	$year_options = "" ;
	for( $y = 2001 ; $y <= 2020 ; $y ++ ) {
		if( $y == $year ) {
			$year_options .= "\t\t\t<option value='$y' selected='selected'>".sprintf(strip_tags(_APCAL_FMT_YEAR),$y)."</option>\n" ;
		} else {
			$year_options .= "\t\t\t<option value='$y'>".sprintf(strip_tags(_APCAL_FMT_YEAR),$y)."</option>\n" ;
		}
	}
	$ret[ 'YEAR_OPTIONS' ] = $year_options ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$month_options = "" ;
	for( $m = 1 ; $m <= 12 ; $m ++ ) {
		if( $m == $month ) {
			$month_options .= "\t\t\t<option value='$m' selected='selected'>{$this->month_short_names[$m]}</option>\n" ;
		} else {
			$month_options .= "\t\t\t<option value='$m'>{$this->month_short_names[$m]}</option>\n" ;
		}
	}
	$ret[ 'MONTH_OPTIONS' ] = $month_options ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	if( $mode == 'W' || $mode == 'D' ) {
		$date_options = "" ;
		for( $d = 1 ; $d <= 31 ; $d ++ ) {
			if( $d == $date ) {
				$date_options .= "\t\t\t<option value='$d' selected='selected'>{$this->date_short_names[$d]}</option>\n" ;
			} else {
				$date_options .= "\t\t\t<option value='$d'>{$this->date_short_names[$d]}</option>\n" ;
			}
		}

		$ret[ 'YMD_SELECTS' ] = sprintf( _APCAL_FMT_YMD , "<select name='apcal_year'>{$ret['YEAR_OPTIONS']}</select> &nbsp; " , "<select name='apcal_month'>{$ret['MONTH_OPTIONS']}</select> &nbsp; " , "<select name='apcal_date'>$date_options</select> &nbsp; " ) ;
		if( $this->week_numbering ) {
			if( $this->day == 0 && ! $this->week_start ) $weekno = date( 'W' , $this->unixtime + 86400 ) ;
			else $weekno = date( 'W' , $this->unixtime ) ;
			$ret[ 'YMW_TITLE' ] = sprintf( _APCAL_FMT_YW , $this->year , $weekno ) ;
		} else {
			$week_number = floor( ( $this->date - ( $this->day - $this->week_start + 7 ) % 7 + 12 ) / 7 ) ;
			$ret[ 'YMW_TITLE' ] = sprintf( _APCAL_FMT_YMW , $this->year , $this->month_middle_names[ $this->month ] , $this->week_numbers[ $week_number ] ) ;
		}
		$ret[ 'YMD_TITLE' ] = sprintf( _APCAL_FMT_YMD , $this->year , $this->month_middle_names[ $this->month ] , $this->date_long_names[$date] ) ;
	}

	return $ret ;
}

function get_monthly_html($get_target='', $query_string='', $for_print = false)
{
    //global $xoopsTpl;
    $tpl = new XoopsTpl();

    // Set days width
    $this->widerDays = unserialize($this->widerDays);
    $total = 0;
    $widths = array('Monday' => 1, 'Tuesday' => 1, 'Wednesday' => 1, 'Thursday' => 1, 'Friday' => 1, 'Saturday' => 1, 'Sunday' => 1);
    foreach($this->widerDays as $day) {$widths[$day] = 1.5;} 
    foreach($widths as $width) {$total += $width;}
    foreach($widths as $day => $width) {$widths[$day] = (100 * $width / $total).'%';}

    // Where clause - Start / End
	$mtop_unixtime = mktime(0, 0, 0, $this->month, 1, $this->year);
	$mtop_weekno = date('W', $mtop_unixtime);
	if($mtop_weekno >= 52) $mtop_weekno = 1;
	$first_date = getdate($mtop_unixtime);
	$date = (-$first_date['wday'] + $this->week_start - 7) % 7;
	$wday_end = 7 + $this->week_start ;
	$last_date = date('t', $this->unixtime);
	$mlast_unixtime = mktime(0, 0, 0, $this->month + 1, 1, $this->year);

	$tzoffset = intval(($this->user_TZ - $this->server_TZ ) * 3600);
	if($tzoffset == 0) {$whr_term = "start<='$mlast_unixtime' AND end>'$mtop_unixtime'";} 
    else {$whr_term = "(allday AND start<='$mlast_unixtime' AND end>'$mtop_unixtime') OR (!allday AND start<='".($mlast_unixtime - $tzoffset)."' AND end>'".($mtop_unixtime - $tzoffset)."')";}

    // Where clause - Categories
	$whr_categories = $this->get_where_about_categories();
    
    // Where clause - Class
	$whr_class = $this->get_where_about_class();

    // ???
	$rs = mysql_query("SELECT DISTINCT unique_id FROM $this->table WHERE ($whr_term) AND ($whr_categories) AND ($whr_class) AND (allday & 2) LIMIT 4", $this->conn);
	$long_event_ids = array();
	$bit = 1;
	while($event = mysql_fetch_object($rs)) 
    {
		$long_event_ids[$bit] = $event->unique_id;
		$bit++;
	}

    $cats_color['00000'] = $this->allcats_color;
    foreach($this->canbemain_cats as $i => $cat)
    {
        $cats_color[$cat->cid] = $cat->color;
        $this->canbemain_cats[$i]->link = $this->make_cal_link($get_target, 'Monthly', $cat->cid, $this->caldate);
    }

    // Get all events in the month in the category with the class
	$yrs = mysql_query("SELECT id,start,end,summary,location,contact,id,allday,admission,uid,unique_id,mainCategory,categories,gmlat,gmlong,extkey0 FROM $this->table WHERE ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start", $this->conn);
	$numrows_yrs = mysql_num_rows($yrs);
    $events = array();
    $eventsids = array();
    $slots = 0;
    if($numrows_yrs)
    {
        $lastDay = date('t', mktime(0, 0, 0, $this->month, 1, $this->year));
        
        while($event = mysql_fetch_object($yrs)) 
        {
            $startDay = date('j', $event->start);
            $endDay = date('j', $event->end);
            $endHour = date('H:i:s', $event->end);
            
            $startDay = $event->start < mktime(0, 0, 0, $this->month, 1, $this->year) ? 1 : $startDay;
            $endDay = $endDay != $startDay && $endHour == '00:00:00' ? $endDay - 1 : $endDay;
            $endDay = $event->end > mktime(0, 0, 0, $this->month, $lastDay, $this->year) ? $lastDay : $endDay;
            $week_end = $this->week_start + 6;

            // Get picture
            $pic = mysql_fetch_object(mysql_query("SELECT picture FROM {$this->pic_table} WHERE event_id={$event->id} AND main_pic=1 LIMIT 0,1"));
            
            if($event->admission) 
            {
                // Put markers on map
                if($event->gmlat > 0 || $event->gmlong > 0)
                {
                    $this->gmPoints[$event->id.startDay] = array('summary' => $event->summary, 'gmlat' => $event->gmlat, 'gmlong' => $event->gmlong, 'location' => $event->location, 'contact' => $event->contact, 'startDate' => $startDay, 'event_id' => $event->id);
                    if($endDay != $startDay && $endHour != '00:00:00')
                    {
                        $nbDays = date('j', $event->end) - $startDay;
                        for($i=1; $i<=$nbDays; $i++)
                            $this->gmPoints[$event->id.($startDay+$i)] = array('summary' => $event->summary, 'gmlat' => $event->gmlat, 'gmlong' => $event->gmlong, 'location' => $event->location, 'contact' => $event->contact, 'startDate' => ($startDay+$i), 'event_id' => $event->id);
                    }
                }
                
                // Categories
                $categories = explode(',', $event->categories);
                //$e['cat'] = $this->text_sanitizer_for_show($this->categories[intval($categories[0])]->cat_title);
                // Summary
                $event->summary = $this->text_sanitizer_for_show($event->summary);
                $summary = /*mb_strcut(*/$event->summary/*, 0, 44)*/;
                /*if($summary != $event->summary) $summary .= "..";*/
                // Event ID
                $event_id = $event->id;
                //$e['week'] = date('W', $event->start) - date('W', mktime(0, 0, 0, $this->month, 1, $this->year));
                // Events array
                $events[$event_id]['summary'] = $summary;
                $events[$event_id]['extkey0'] = intval($event->extkey0); //added by goffy
            }
            elseif($this->isadmin || ($this->user_id > 0 && $this->user_id == $event->uid))
            {
                $event_id = $event->id;
                $events[$event_id]['summary'] = sprintf( _APCAL_NTC_NUMBEROFNEEDADMIT , '');
            }
            $events[$event_id]['link'] = $this->make_event_link($event->id, $get_target);
            $events[$event_id]['location'] = $this->text_sanitizer_for_show($event->location);
            $events[$event_id]['start'] = $this->get_middle_md($event->start + $tzoffset).' '.($event->allday != 1 ? $this->get_middle_hi($event->start + $tzoffset) : '');
            $events[$event_id]['end'] = ($event->allday != 1 ? $this->get_middle_md($event->end + $tzoffset) : $this->get_middle_md($event->end - 3600)).' '.($event->allday != 1 ? $this->get_middle_hi($event->end + $tzoffset) : '');
            $events[$event_id]['cat'] = ($event->mainCategory && key_exists($event->mainCategory, $cats_color)) ? $event->mainCategory : '00000';
            $events[$event_id]['duration'] = $endDay - $startDay + 1;
            $events[$event_id]['picture'] = $pic && $this->showPicMonthly ? XOOPS_UPLOAD_URL."/APCal/{$pic->picture}" : '';
            
            // Find the best slot for the event
            $i = 0;
            $ok = false;
            while(!$ok)
            {
                $ok = true;
                for($d=$startDay; $d<=$endDay; $d++)
                {
                    if(isset($eventsids[$d][$i])) {$ok = false;}
                }
                if(!$ok) {$i++;}
            }
            
            // Assign event to day
            for($d=$startDay; $d<=$endDay; $d++)
            {
                $wday = date('w', mktime(0, 0, 0, $this->month, $d, $this->year));
                
                if($d == $startDay)
                {
                    $wday_left = $week_end == 7 && $wday == 0 ? 0 : $week_end - $wday;
                    $duration = min($events[$event_id]['duration'], $wday_left + 1);
                    $eventsids[$d][$i] = array('id' => $event_id, 'first' => 1, 'duration' => $duration);
                }
                elseif($wday == $this->week_start)
                {
                    $duration = min($endDay - $d + 1, 7);
                    $eventsids[$d][$i] = array('id' => $event_id, 'first' => 1, 'duration' => $duration);
                }
                else
                    $eventsids[$d][$i] = array('id' => $event_id, 'first' => 0);
                    
                $slots = max($slots, count($eventsids[$d]));
            }
        }
    }    
    $roimage = XOOPS_URL.'/modules/APCal/images/regonline/regonline.png'; // added by goffy: general comments for online registration
    
    // Header
    $tpl->assign('images_url', $this->images_url);
    $tpl->assign('widths', $widths);
    $tpl->assign('week_start', $this->week_start);
    $tpl->assign('week_middle_names', $this->week_middle_names);
    
    // Colors
    $tpl->assign('colors', array($this->sunday_color, $this->weekday_color, $this->weekday_color, $this->weekday_color, $this->weekday_color, $this->weekday_color, $this->saturday_color, $this->sunday_color));
    $tpl->assign('bgcolors', array($this->sunday_bgcolor, $this->weekday_bgcolor, $this->weekday_bgcolor, $this->weekday_bgcolor, $this->weekday_bgcolor, $this->weekday_bgcolor, $this->saturday_bgcolor, $this->sunday_bgcolor));
    $tpl->assign('frame_css', $this->frame_css);
    $tpl->assign('holiday_color', $this->holiday_color);
    $tpl->assign('holiday_bgcolor', $this->holiday_bgcolor);
    $tpl->assign('targetday_bgcolor', $this->targetday_bgcolor);
    $tpl->assign('event_color', $this->event_color);
    $tpl->assign('event_bgcolor', $this->event_bgcolor);

    $tpl->assign('categories', $this->canbemain_cats);
    $tpl->assign('cats_color', $cats_color);
    
    // Loops
    $tpl->assign('week_start', $this->week_start);
    $tpl->assign('week_end', $wday_end);
    $tpl->assign('day', $date);
    $tpl->assign('last_day', $last_date);
    $tpl->assign('week_numbering', $this->week_numbering);
    $tpl->assign('weekno', $mtop_weekno);
    $tpl->assign('selectedday', intval($this->date));
    $tpl->assign('holidays', $this->holidays);
    
    // Days
    $tpl->assign('insertable', $this->insertable);
    $tpl->assign('days', array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'));
    
    // Links
    $tpl->assign('cid', $this->now_cid);
    $tpl->assign('year', $this->year);
    $tpl->assign('month', $this->month);
    $tpl->assign('cal_date', $this->caldate);
    
    // Events
    $tpl->assign('slots', $slots);
    $tpl->assign('events', $events);
    $tpl->assign('e', $eventsids);

    $tpl->assign('for_print', $for_print);
    
    $tpl->assign('cal', $this);
    
    //added by goffy: registration online
    $tpl->assign('ro_image', $roimage);

	return $tpl->fetch(XOOPS_ROOT_PATH.'/modules/APCal/templates/apcal_monthly.html');
}

// HTML output for weekly view
function get_weekly_html($get_target='')
{
	$roimage = XOOPS_URL.'/modules/APCal/images/regonline/regonline.png'; // added by goffy: image for online registration  
    $ret = "
	 <table border='0' cellspacing='0' cellpadding='0' width='100%' style='border-collapse:collapse;margin:0px;'>
	 <tr>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='10' height='10' /></td>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='80' height='10' /></td>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='80' height='10' /></td>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='80' height='10' /></td>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='80' height='10' /></td>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='80' height='10' /></td>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='80' height='10' /></td>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='80' height='10' /></td>
	 </tr>\n" ;

	$wtop_date = $this->date - ( $this->day - $this->week_start + 7 ) % 7 ;
	$wtop_unixtime = mktime(0,0,0,$this->month,$wtop_date,$this->year) ;
	$wlast_unixtime = mktime(0,0,0,$this->month,$wtop_date+7,$this->year) ;

	// get the result of plugins
	$plugin_returns = array() ;
	if( strtolower( get_class( $this ) ) == 'apcal_xoops' ) {
		$db =& Database::getInstance() ;
		$myts =& MyTextSanitizer::getInstance() ;
		$now = time() ;
		$just1gif = 0 ;

		$tzoffset_s2u = intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) ;
		$plugins = $this->get_plugins( "weekly" ) ;
		foreach( $plugins as $plugin ) {
			$include_ret = @include( $this->base_path . '/' . $this->plugins_path_weekly . '/' . $plugin['file'] ) ;
			if( $include_ret === false ) {
				// weekly emulator by monthly plugin
				$wtop_month = date( 'n' , $wtop_unixtime ) ;
				$wlast_month = date( 'n' , $wlast_unixtime - 86400 ) ;
				$year_backup = $this->year ;
				$month_backup = $this->month ;
				if( $wtop_month == $wlast_month ) {
					@include( $this->base_path . '/' . $this->plugins_path_monthly . '/' . $plugin['file'] ) ;
				} else {
					$plugin_returns_backup = $plugin_returns ;
					$this->year = date( 'Y' , $wtop_unixtime ) ;
					$this->month = $wtop_month ;
					@include( $this->base_path . '/' . $this->plugins_path_monthly . '/' . $plugin['file'] ) ;
					for( $d = 1 ; $d < 21 ; $d ++ ) {
						$plugin_returns[ $d ] = @$plugin_returns_backup[ $d ] ;
					}
					$plugin_returns_backup = $plugin_returns ;
					$this->year = date( 'Y' , $wlast_unixtime ) ;
					$this->month = $wlast_month ;
					@include( $this->base_path . '/' . $this->plugins_path_monthly . '/' . $plugin['file'] ) ;
					for( $d = 8 ; $d < 32 ; $d ++ ) {
						$plugin_returns[ $d ] = @$plugin_returns_backup[ $d ] ;
					}
					$this->year = $year_backup ;
					$this->month = $month_backup ;
				}
			}
		}
	}

	$tzoffset = intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) ;
	if( $tzoffset == 0 ) {
		$whr_term = "start<='$wlast_unixtime' AND end>'$wtop_unixtime'" ;
	} else {
		$whr_term = "(allday AND start<='$wlast_unixtime' AND end>'$wtop_unixtime') OR ( ! allday AND start<='".( $wlast_unixtime - $tzoffset )."' AND end>'".( $wtop_unixtime - $tzoffset )."')" ;
	}

	$whr_categories = $this->get_where_about_categories() ;
	$whr_class = $this->get_where_about_class() ;

	$ars = mysql_query( "SELECT * FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start" , $this->conn ) ;
	$numrows_ars = mysql_num_rows( $ars ) ;
	$wrs = mysql_query( "SELECT * FROM $this->table WHERE admission=0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start" , $this->conn ) ;
	$numrows_wrs = mysql_num_rows( $wrs ) ;

	$now_date = $wtop_date ;
	$wday_end = 7 + $this->week_start ;
	for( $wday = $this->week_start ; $wday < $wday_end ; $wday ++ , $now_date ++ ) {

		$now_unixtime = mktime( 0 , 0 , 0 , $this->month , $now_date , $this->year ) ;
		$toptime_of_day = $now_unixtime + $this->day_start - $tzoffset ;
		$bottomtime_of_day = $toptime_of_day + 86400 ;
		$link = date( "Y-n-j" , $now_unixtime ) ;
		$date = date( "j" , $now_unixtime ) ;
		$disp = $this->get_middle_md( $now_unixtime ) ;
		$disp .= "<br />({$this->week_middle_names[$wday]})" ;
		$date_part_append = '' ;
		$event_str = "
				<table cellpadding='0' cellspacing='2' style='margin:0px;'>
				  <tr>
				    <td><img src='$this->images_url/spacer.gif' alt='' border='0' width='120' height='4' /></td>
				    <td><img src='$this->images_url/spacer.gif' alt='' border='0' width='360' height='4' /></td>
				  </tr>
		\n" ;

		if( $numrows_ars > 0 ) mysql_data_seek( $ars , 0 ) ;
		while( $event = mysql_fetch_object( $ars ) ) {
            if($event->gmlat > 0 || $event->gmlong > 0)
                    $this->gmPoints[] = array('summary' => $event->summary, 'gmlat' => $event->gmlat, 'gmlong' => $event->gmlong, 'location' => $event->location, 'contact' => $event->contact, 'startDate' => date('j', $event->start), 'event_id' =>$event->id);

			if( $event->allday ) {
				if( $event->start >= $now_unixtime + 86400 || $event->end <= $now_unixtime ) continue ;
			} else {
				if( $event->start >= $bottomtime_of_day || $event->start != $toptime_of_day && $event->end <= $toptime_of_day ) continue ;

				$event->is_start_date = $event->start >= $toptime_of_day ;
				$event->is_end_date = $event->end <= $bottomtime_of_day ;
			}

			$summary = $this->text_sanitizer_for_show( $event->summary ) ;

            // Get picture
                $pic = mysql_fetch_object(mysql_query("SELECT picture FROM {$this->pic_table} WHERE event_id={$event->id} AND main_pic=1 LIMIT 0,1"));
                $picture = $pic && $this->showPicWeekly ? "<img src='".XOOPS_UPLOAD_URL."/APCal/{$pic->picture}' alt='{$summary}' height='50' style='vertical-align: middle;' />" : '';

			if( $event->allday ) {
				if( $event->allday & 4 ) {
					$date_part_append .= "<font size='2'><a href='{$this->make_event_link($event->id, $get_target)}' class='cal_summary_specialday'><font color='$this->holiday_color'>$summary</a></font>";
                    if($event->extkey0 == 1) {$event_str .= "&nbsp;&nbsp;<img src='{$roimage}' height='15px' alt='"._APCAL_RO_ONLINE_POSS."' title='"._APCAL_RO_ONLINE_POSS."' />";} // added by goffy: mark this event, that online registration is active
                    $event_str .= "</a></font><br />\n" ;
					continue ;
				} else {
					$time_part = "             <img border='0' src='$this->images_url/dot_allday.gif' />" ;
					$summary_class = "calsummary_allday" ;
				}
			} else {
				$time_part = $this->get_time_desc_for_a_day( $event , $tzoffset , $bottomtime_of_day - $this->day_start , true , true ) ;
				$summary_class = "calsummary" ;
			}

			$event_str .= "
				  <tr>
				    <td valign='top' align='center'>
				      <pre style='margin:0px;'><font size='2'>$time_part</font></pre>
				    </td>
				    <td valign='top'>
                      $picture
				      <font size='2'><a href='{$this->make_event_link($event->id, $get_target)}' class='$summary_class'>$summary</a></font>";
            if($event->extkey0 == 1) {$event_str .= "&nbsp;&nbsp;<img src='{$roimage}' height='15px' alt='"._APCAL_RO_ONLINE_POSS."' title='"._APCAL_RO_ONLINE_POSS."' />";} // added by goffy: mark this event, that online registration is active
            $event_str .= "
				    </td>
				  </tr>
			\n" ;
		}

		if( $this->isadmin || $this->user_id > 0 ) {

			if( $numrows_wrs > 0 ) mysql_data_seek( $wrs , 0 ) ;
			while( $event = mysql_fetch_object( $wrs ) ) {
				if( $event->allday ) {
					if( $event->start >= $now_unixtime + 86400 || $event->end <= $now_unixtime ) continue ;
				} else {
					if( $event->start >= $bottomtime_of_day || $event->start != $toptime_of_day && $event->end <= $toptime_of_day ) continue ;
					$event->is_start_date = $event->start >= $toptime_of_day ;
					$event->is_end_date = $event->end <= $bottomtime_of_day ;
				}

				$summary = $this->text_sanitizer_for_show( $event->summary ) ;

                // Get picture
                $pic = mysql_fetch_object(mysql_query("SELECT picture FROM {$this->pic_table} WHERE event_id={$event->id} AND main_pic=1 LIMIT 0,1"));
                $picture = $pic && $this->showPicWeekly ? "<img src='".XOOPS_UPLOAD_URL."/APCal/{$pic->picture}' alt='{$summary}' height='50' style='vertical-align: middle;' />" : '';

				if( $event->allday ) {
					$time_part = "             <img border='0' src='$this->images_url/dot_notadmit.gif' />" ;
					$summary_class = "calsummary_allday" ;
				} else {
					$time_part = $this->get_time_desc_for_a_day( $event , $tzoffset , $bottomtime_of_day - $this->day_start , true , false ) ;
					$summary_class = "calsummary" ;
				}

				$event_str .= "
					  <tr>
					    <td valign='top' align='center'>
					      <pre style='margin:0px;'><font size='2'>$time_part</font></pre>
					    </td>
					    <td valign='top'>
                          $picture
					      <font size='2'><a href='{$this->make_event_link($event->id, $get_target)}' class='$summary_class'><font color='#00FF00'>$summary("._APCAL_MB_EVENT_NEEDADMIT.")</a></font>";         
                if($event->extkey0 == 1) {$event_str .= "&nbsp;&nbsp;<img src='{$roimage}' height='15px' alt='"._APCAL_RO_ONLINE_POSS."' title='"._APCAL_RO_ONLINE_POSS."' />";} // added by goffy: mark this event, that online registration is active
                $event_str .= "                  
					    </td>
					  </tr>
				\n" ;
			}
		}

		// drawing the result of plugins
		if( ! empty( $plugin_returns[ $date ] ) ) {
			foreach( $plugin_returns[ $date ] as $item ) {
				$event_str .= "
				  <tr>
				    <td></td>
				    <td valign='top'>
			          <font size='2'><a href='{$item['link']}' class='$summary_class'><img src='$this->images_url/{$item['dotgif']}' alt='{$item['title']}>' />{$item['title']}</a></font>
				    </td>
				  </tr>\n" ;
			}
		}

		if( $this->insertable ) $event_str .= "
				  <tr>
				    <td valign='bottom' colspan='2'>
				      &nbsp; <font size='2'><a href='$get_target?cid=$this->now_cid&amp;smode=Weekly&amp;action=Edit&amp;caldate=$link'><img src='$this->images_url/addevent.gif' border='0' width='14' height='12' />"._APCAL_MB_ADDEVENT."</a></font>
				    </td>
				  </tr>
		\n" ;

		$event_str .= "\t\t\t\t</table>\n" ;

		if( isset( $this->holidays[ $link ] ) ) {
			//	Holiday
			$bgcolor = $this->holiday_bgcolor ;
			$color = $this->holiday_color ;
			if( $this->holidays[ $link ] != 1 ) {
				$date_part_append .= "<font color='$this->holiday_color'>{$this->holidays[ $link ]}</font>\n" ;
			}
		} elseif( $wday % 7 == 0 ) { 
			//	Sunday
			$bgcolor = $this->sunday_bgcolor ;
			$color = $this->sunday_color ;
		} elseif( $wday == 6 ) { 
			//	Saturday
			$bgcolor = $this->saturday_bgcolor ;
			$color = $this->saturday_color ;
		} else { 
			// Weekday
			$bgcolor = $this->weekday_bgcolor ;
			$color = $this->weekday_color ;
		}

		// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ø·Ê¿ï¿½ï¿½Ï¥ï¿½ï¿½é¥¤ï¿½È½ï¿½ï¿½ï¿½
		if( $link == $this->caldate ) $body_bgcolor = $this->targetday_bgcolor ;
		else $body_bgcolor = $bgcolor ;

		$ret .= "
	 <tr>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='10' height='80' /></td>
	   <td bgcolor='$bgcolor' align='center' valign='middle' style='vertical-align:middle;text-align:center;$this->frame_css background-color:$bgcolor;'>
	     <a href='{$this->make_cal_link($get_target, 'Daily', $this->now_cid, $link)}' class='calbody'><font size='3' color='$color'><b><span class='calbody'>$disp</span></b></font></a><br />
	     $date_part_append
	   </td>
	   <td valign='top' colspan='6' bgcolor='$body_bgcolor' style='$this->frame_css background-color:$body_bgcolor'>
	     $event_str
	   </td>
	 </tr>\n" ;
	}

	$ret .= "\t </table>\n";

	return $ret ;
}



// Get the html for the daily view
function get_daily_html($get_target='')
{
    $roimage = XOOPS_URL.'/modules/APCal/images/regonline/regonline.png'; // added by goffy: image for online registration
    // get the result of plugins
	$plugin_returns = array() ;
	if( strtolower( get_class( $this ) ) == 'apcal_xoops' ) {
		$db =& Database::getInstance() ;
		$myts =& MyTextSanitizer::getInstance() ;
		$now = time() ;
		$just1gif = 0 ;

		$tzoffset_s2u = intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) ;
		$plugins = $this->get_plugins( "daily" ) ;
		foreach( $plugins as $plugin ) {
			$include_ret = @include( $this->base_path . '/' . $this->plugins_path_daily . '/' . $plugin['file'] ) ;
			if( $include_ret === false ) {
				// daily emulator by monthly plugin
				@include( $this->base_path . '/' . $this->plugins_path_monthly . '/' . $plugin['file'] ) ;
			}
		}
	}

	list( $bgcolor , $color ) =  $this->daytype_to_colors( $this->daytype ) ;

	$ret = "
	<table border='0' cellspacing='0' cellpadding='0' width='100%' style='margin:0px;'>
	 <tr>
	 <td width='100%' class='calframe'>
	 <table border='0' cellspacing='0' cellpadding='0' width='100%' style='margin:0px;'>
	 <tr>
	   <td colspan='8'><img src='$this->images_url/spacer.gif' alt='' width='570' height='10' /></td>
	 </tr>
	 <tr>
	   <td><img src='$this->images_url/spacer.gif' alt='' width='10' height='350' /></td>
	   <td colospan='7' valign='top' bgcolor='$bgcolor' style='$this->frame_css;background-color:$bgcolor'>
	     <table border='0' cellpadding='0' cellspacing='0' style='margin:0px;'>
	       <tr>
	         <td><img src='$this->images_url/spacer.gif' alt='' width='120' height='10' /></td>
	         <td><img src='$this->images_url/spacer.gif' alt='' width='440' height='10' /></td>
	       </tr>
	\n" ;

	// WHERE Clause - Date
	$tzoffset = intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) ;
	$toptime_of_day = $this->unixtime + $this->day_start - $tzoffset ;
	$bottomtime_of_day = $toptime_of_day + 86400 ;
	$whr_term = "(allday AND start<='$this->unixtime' AND end>'$this->unixtime') OR ( ! allday AND start<'$bottomtime_of_day' AND (start='$toptime_of_day' OR end>'$toptime_of_day'))" ;

	// WHERE Clause - Categories
	$whr_categories = $this->get_where_about_categories() ;

	// WHERE Clause - Class
	$whr_class = $this->get_where_about_class() ;

	// MySQL Query
	$yrs = mysql_query( "SELECT *,(start>='$toptime_of_day') AS is_start_date,(end<='$bottomtime_of_day') AS is_end_date FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start,end" , $this->conn ) ;
	$num_rows = mysql_num_rows( $yrs ) ;

	if( $num_rows == 0 ) $ret .= "<tr><td></td><td>"._APCAL_MB_NOEVENT."</td></tr>\n" ;
	else while( $event = mysql_fetch_object( $yrs ) ) {
        // Get picture
        $pic = mysql_fetch_object(mysql_query("SELECT picture FROM {$this->pic_table} WHERE event_id={$event->id} AND main_pic=1 LIMIT 0,1"));
        $picture = $pic && $this->showPicDaily ? "<img src='".XOOPS_UPLOAD_URL."/APCal/{$pic->picture}' alt='{$summary}' height='50' style='vertical-align: middle;' />" : '';
        
        // Google map
        if($event->gmlat > 0 || $event->gmlong > 0)
            $this->gmPoints[] = array('summary' => $event->summary, 'gmlat' => $event->gmlat, 'gmlong' => $event->gmlong, 'location' => $event->location, 'contact' => $event->contact, 'startDate' => date('j', $event->start), 'event_id' =>$event->id);

		if( $event->allday ) {
			$time_part = "             <img border='0' src='$this->images_url/dot_allday.gif' />" ;
		} else {
			$time_part = $this->get_time_desc_for_a_day( $event , $tzoffset , $bottomtime_of_day - $this->day_start , true , true ) ;
		}

		$description = $this->textarea_sanitizer_for_show( $event->description ) ;
		$summary = $this->text_sanitizer_for_show( $event->summary ) ;
		$summary_class = $event->allday ? "calsummary_allday" : "calsummary" ;

		$ret .= "
	       <tr>
	         <td valign='middle' align='center'>
	           <pre style='margin:0px;'><font size='3'>$time_part</font></pre>
	         </td>
	         <td valign='middle'>
               <a href='{$this->make_event_link($event->id, $get_target)}'>{$picture}</a>
	           <font size='3'><a href='{$this->make_event_link($event->id, $get_target)}' class='$summary_class'>$summary</a></font>";
       if($event->extkey0 == 1) $ret .= "&nbsp;&nbsp;<img src='{$roimage}' height='15px' alt='"._APCAL_RO_ONLINE_POSS."' title='"._APCAL_RO_ONLINE_POSS."'>" ;	// added by goffy: mark this event, that online registration is active
       $ret .= "<br />
	           <font size='2'>$description</font><br />
	           &nbsp; 
	         </td>
	       </tr>\n" ;
	}
    
	if( $this->isadmin || $this->user_id > 0 ) {
	  $whr_uid = $this->isadmin ? "1" : "uid=$this->user_id " ;
	  $yrs = mysql_query( "SELECT start,end,summary,id,allday,admission,uid,description,(start>='$toptime_of_day') AS is_start_date,(end<='$bottomtime_of_day') AS is_end_date FROM $this->table WHERE admission=0 AND $whr_uid AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start,end" , $this->conn ) ;

	  while( $event = mysql_fetch_object( $yrs ) ) {

		if( $event->allday ) {
			$time_part = "             <img border='0' src='$this->images_url/dot_notadmit.gif' />" ;
		} else {
			$time_part = $this->get_time_desc_for_a_day( $event , $tzoffset , $bottomtime_of_day - $this->day_start , true , false ) ;
		}

		$summary = $this->text_sanitizer_for_show( $event->summary ) ;

		$summary_class = $event->allday ? "calsummary_allday" : "calsummary" ;

		$ret .= "
	       <tr>
	         <td valign='top' align='center'>
	           <pre style='margin:0px;'><font size='3'>$time_part</font></pre>
	         </td>
	         <td vlalign='top'>
	           <font size='3'><a href='{$this->make_event_link($event->id, $get_target)}' class='$summary_class'><font color='#00FF00'>{$summary}</a></font>";    
        if($event->extkey0 == 1) $ret .= "&nbsp;&nbsp;<img src='{$roimage}' height='15px' alt='"._APCAL_RO_ONLINE_POSS."' title='"._APCAL_RO_ONLINE_POSS."'>" ;	// added by goffy: mark this event, that online registration is active
        $ret .= " ("._APCAL_MB_EVENT_NEEDADMIT.")
	         </td>
	       </tr>\n" ;
	  }
	}

	// drawing the result of plugins
	if( ! empty( $plugin_returns[ $this->date ] ) ) {
		foreach( $plugin_returns[ $this->date ] as $item ) {
			$ret .= "
	       <tr>
	         <td></td>
	         <td valign='top'>
	           <font size='3'><a href='{$item['link']}' class='$summary_class'><img src='$this->images_url/{$item['dotgif']}' alt='{$item['title']}>' />{$item['title']}</a></font><br />
	           <font size='2'>{$item['description']}</font><br />
	           &nbsp; 
	         </td>
	       </tr>\n" ;
		}
	}

	// Í½ï¿½ï¿½ï¿½ï¿½É²Ã¡Ê±ï¿½É®ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	if( $this->insertable ) $ret .= "
	       <tr>
	         <td valign='bottom' colspan='2'>
	           &nbsp; <font size='2'><a href='$get_target?cid=$this->now_cid&amp;smode=Daily&amp;action=Edit&amp;caldate=$this->caldate'><img src='$this->images_url/addevent.gif' border='0' width='14' height='12' />"._APCAL_MB_ADDEVENT."</a></font>
	         </td>
	       </tr>\n" ;

	$ret .= "
	     </table>
	   </td>
	 </tr>
	 </table>
	 </td>
	 </tr>
	</table>\n" ;
    
	return $ret ;
}



/*******************************************************************/
/*        ï¿½á¥¤ï¿½ï¿½ï¿½ï¿½ ï¿½Ê¸ï¿½ï¿½Ì¥Ç¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½  */
/*******************************************************************/

function savepictures($event_id)
{
    xoops_load('xoopsmediauploader');
    $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH.'/APCal', array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/bmp'), $_POST['MAX_FILE_SIZE'], 4048, 4048);
    $uploader->setPrefix('APCal');
    $err = array();
    foreach($_POST['files'] as $i => $file)
    {
        $main_pic = $file == 'picture0' ? 1 : 0;
        if ($uploader->fetchMedia($file))
        {
            if(!$uploader->upload())
                $err[] = $uploader->getErrors();
            else
            {
                $filename = $uploader->getSavedFileName();
                $result = mysql_query("INSERT INTO {$this->pic_table}(event_id, picture, main_pic) VALUES ({$event_id}, '{$filename}', {$main_pic})", $this->conn);
                if(!$result)
                    $err[] = sprintf(_FAILSAVEIMG, $i);
                else
                    Thumb::save($filename, $this->picWidth, $this->picHeight);
            }
        }
        else
        {
            $err[] = sprintf(_FAILFETCHIMG, $i);
            $err = array_merge($err, $uploader->getErrors(false));
        }
    }
    
    //FOR DEBUG: var_dump($err);
}

// Show an event
function get_schedule_view_html($for_print=false)
{
	global $xoopsTpl;

	$smode = empty( $_GET['smode'] ) ? 'Monthly' : preg_replace('/[^a-zA-Z0-9_-]/','',$_GET['smode']) ;
	$editable = $this->editable ;
	$deletable = $this->deletable ;

	$whr_categories = $this->get_where_about_categories() ;

	// CLASS
	$whr_class = $this->get_where_about_class() ;

	if( empty( $_GET['event_id'] ) ) die( _APCAL_ERR_INVALID_EVENT_ID ) ;
	$this->original_id = $event_id = intval( $_GET['event_id'] ) ;
	$yrs = mysql_query( "SELECT *,UNIX_TIMESTAMP(dtstamp) AS udtstamp FROM $this->table WHERE id='$event_id' AND ($whr_categories) AND ($whr_class)" , $this->conn ) ;
	if( mysql_num_rows( $yrs ) < 1 ) die( _APCAL_ERR_INVALID_EVENT_ID ) ;
	$event = mysql_fetch_object( $yrs ) ;

	// rrule
	if( trim( $event->rrule ) != '' ) {
		if( $event->rrule_pid != $event->id ) {
			$event->id = $event->rrule_pid ;
			$yrs = mysql_query( "SELECT id,start,start_date FROM $this->table WHERE id='$event->rrule_pid' AND ($whr_categories) AND ($whr_class)" , $this->conn ) ;
			if( mysql_num_rows( $yrs ) >= 1 ) {
				$event->id = $event->rrule_pid ;
				$parent_event = mysql_fetch_object( $yrs ) ;
				$this->original_id = $parent_event->id ;
				$is_extracted_record = true ;
			} else {
				$parent_event =& $event ;
			}
		}
		$rrule = $this->rrule_to_human_language( $event->rrule ) ;
	} else {
		$rrule = '' ;
	}

	// Admin
	if( $event->uid != $this->user_id && ! $this->isadmin ) {
		$editable = false ;
		$deletable = false ;
	}

	// editable
	if( ! $event->admission && ! $editable ) die( _APCAL_ERR_NOPERM_TO_SHOW ) ;

	if( $editable && ! $for_print ) {
		$edit_button = "
			<form method='get' action='".XOOPS_URL."/modules/APCal/index.php' style='margin:0px;'>
				<input type='hidden' name='smode' value='$smode' />
				<input type='hidden' name='action' value='Edit' />
				<input type='hidden' name='event_id' value='$event->id' />
				<input type='hidden' name='caldate' value='{$_GET['date']}' />
				<input type='submit' value='"._APCAL_BTN_EDITEVENT."' />
			</form>\n" ;
	} else $edit_button = "" ;

	if( $deletable && ! $for_print ) {
		$delete_button = "
			<form method='post' action='".XOOPS_URL."/modules/APCal/index.php' name='MainForm' style='margin:0px;'>
				<input type='hidden' name='smode' value='$smode' />
				<input type='hidden' name='last_smode' value='$smode' />
				<input type='hidden' name='event_id' value='$event->id' />
				<input type='hidden' name='subevent_id' value='$event_id' />
				<input type='hidden' name='caldate' value='$this->caldate' />
				<input type='hidden' name='last_caldate' value='{$_GET['date']}' />
				<input type='submit' name='delete' value='"._APCAL_BTN_DELETE."' onclick='return confirm(\""._APCAL_CNFM_DELETE_YN."\")' />
				".( ! empty( $is_extracted_record ) ? "<input type='submit' name='delete_one' value='"._APCAL_BTN_DELETE_ONE."' onclick='return confirm(\""._APCAL_CNFM_DELETE_YN."\")' />" : "" )."
				".$GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )."
			</form>\n" ;
	} else $delete_button = "" ;

	// iCalendar
	if( $this->can_output_ics && ! $for_print ) {
		$php_self4disp = strtr( @$_SERVER['PHP_SELF'] , '<>\'"' , '    ' ) ;
		$ics_output_button = "
			<a href='http://{$_SERVER['HTTP_HOST']}$php_self4disp?fmt=single&amp;event_id=$event->id&amp;output_ics=1' target='_blank'><img border='0' src='$this->images_url/output_ics_win.gif' alt='"._APCAL_BTN_OUTPUTICS_WIN."' title='"._APCAL_BTN_OUTPUTICS_WIN."' /></a>
			<a href='webcal://{$_SERVER['HTTP_HOST']}$php_self4disp?fmt=single&amp;event_id=$event->id&amp;output_ics=1' target='_blank'><img border='0' src='$this->images_url/output_ics_mac.gif' alt='"._APCAL_BTN_OUTPUTICS_MAC."' title='"._APCAL_BTN_OUTPUTICS_MAC."' /></a>\n" ;
	} else $ics_output_button = "" ;

	if( $event->allday ) {
		$tzoffset = 0 ;
		$event->end -= 300 ;
		$start_time_str = /*"("._APCAL_MB_ALLDAY_EVENT.")"*/'' ;
		$end_time_str = "" ;
	} else {
		$tzoffset = intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) ;
		$disp_user_tz = $this->get_tz_for_display( $this->user_TZ ) ;
		$start_time_str = $this->get_middle_hi( $event->start + $tzoffset ) . " $disp_user_tz" ;
		$end_time_str = $this->get_middle_hi( $event->end + $tzoffset ) . " $disp_user_tz" ;
		if( $this->user_TZ != $event->event_tz ) {
			$tzoffset_s2e = intval( ( $event->event_tz - $this->server_TZ ) * 3600 ) ;
			$disp_event_tz = $this->get_tz_for_display( $event->event_tz ) ;
			$start_time_str .= " &nbsp; &nbsp; <small>" . $this->get_middle_dhi( $event->start + $tzoffset_s2e ) . " $disp_event_tz</small>" ;
			$end_time_str .= " &nbsp; &nbsp; <small>" . $this->get_middle_dhi( $event->end + $tzoffset_s2e ) . " $disp_event_tz</small>" ;
		}
	}

    $start_date_str = $this->get_long_ymdn( $event->start + $tzoffset ) ;
    $end_date_str = $this->get_long_ymdn( $event->end + $tzoffset ) ;

	$start_datetime_str = "$start_date_str&nbsp;$start_time_str" ;
	$end_datetime_str = "$end_date_str&nbsp;$end_time_str" ;

	if( trim( $event->rrule ) != '' ) {
		if( isset( $parent_event ) && $parent_event != $event ) {
			if( isset( $parent_event->start_date ) ) {
				$parent_date_str = $parent_event->start_date ; // GIJ TODO
			} else {
				$parent_date_str = $this->get_long_ymdn( $parent_event->start + $tzoffset ) ;
			}
			$rrule .= "<br /><a href='?action=View&amp;event_id=$parent_event->id' target='_blank'>"._APCAL_MB_LINK_TO_RRULE1ST. " $parent_date_str</a>" ;
		} else {
			$rrule .= '<br /> '._APCAL_MB_RRULE1ST ;
		}
	}

	$cat_titles4show = '' ;
	$cids = explode( "," , $event->categories ) ;
	foreach( $cids as $cid ) {
		$cid = intval( $cid ) ;
		if( isset( $this->categories[ $cid ] ) ) $cat_titles4show .= "<a href='{$this->make_cal_link('', '', $cid, date('Y-n-j', $event->start))}'>".$this->text_sanitizer_for_show( $this->categories[ $cid ]->cat_title )."</a>, " ;
	}
	if( $cat_titles4show != '' ) $cat_titles4show = substr( $cat_titles4show , 0 , -2 ) ;

	$submitter_info = $this->get_submitter_info( $event->uid ) ;

	if( $event->class == 'PRIVATE' ) {
		$groupid = intval( $event->groupid ) ;
		if( $groupid == 0 ) $group = _APCAL_OPT_PRIVATEMYSELF ;
		else if( isset( $this->groups[ $groupid ] ) ) $group = sprintf( _APCAL_OPT_PRIVATEGROUP , $this->groups[ $groupid ] ) ;
		else $group = _APCAL_OPT_PRIVATEINVALID ;
		$class_status = _APCAL_MB_PRIVATE . sprintf( _APCAL_MB_PRIVATETARGET , $group ) ;
	} else {
		$class_status = _APCAL_MB_PUBLIC ;
	}

	$admission_status = $event->admission ? _APCAL_MB_EVENT_ADMITTED : _APCAL_MB_EVENT_NEEDADMIT ;
	$last_modified = $this->get_long_ymdn( $event->udtstamp - intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) ) ;
	$description = $this->textarea_sanitizer_for_show( $event->description ) ;
	$summary = $this->text_sanitizer_for_show( $event->summary ) ;
	$location = $this->text_sanitizer_for_show( $event->location ) ;
	$contact = $this->text_sanitizer_for_show( $event->contact ) ;  
    $contact = convertmycontacts($contact); // added one line by goffy: converting the contact name(s) into a link to member account this is not necessary for online registration
    $email = $this->text_sanitizer_for_show( $event->email ) ;
    $url = $this->text_sanitizer_for_show( $event->url ) ;
    $otherHour = explode('-', $event->otherHours);
    $otherHour = $otherHour[0] == '' ? array() : $otherHour;
    foreach($otherHour as $day)
    {
        $h = explode(':', $day);
        $d = $this->get_long_ymdn($event->start + ($h[0] * 3600 * 24) + $tzoffset);
        $otherHours .= '<br />'.$d.'&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf('%02d', $h[1]).':'.sprintf('%02d', $h[2]).' - '.sprintf('%02d', $h[3]).':'.sprintf('%02d', $h[4]);
    }

	$this->last_summary = $summary ;
    
    /********************************************************************/
/* added by goffy: code for online registration                     */
/********************************************************************/
	$this->regonline=intval($event->extkey0);
    $registered=0;
    if ($this->regonline == 1) {
	$result_ro = mysql_query( "SELECT " .XOOPS_DB_PREFIX.$this->table_ro_events. ".roe_number 
				FROM " .XOOPS_DB_PREFIX.$this->table_ro_events. "
				WHERE (((roe_eventid)=" . $event->id . "))"
				 , $this->conn ) ;
	$row = mysql_fetch_row( $result_ro ) ;
	$itemstotal=$row[0];
	if( $itemstotal == 0 ) {
		//$eventmembersall = "No limit for online registration";
	} else {
		$eventmembersall = _APCAL_RO_QUANTITY2.": ". $itemstotal."<br />" ;
	}
    
	$result_ro = mysql_query( "SELECT Count(rom_id) AS countevents 
				FROM " .XOOPS_DB_PREFIX.$this->table_ro_members. "
				WHERE (((rom_eventid)=" . $event->id . "))"
				 , $this->conn ) ;
	$row = mysql_fetch_row( $result_ro ) ;
	$itemstotal=$row[0];
	if( $itemstotal == 0 ) {
		$eventmembersall .= _APCAL_RO_NOMEMBERS;
		$eventmembers = "";
	} else {
		$eventmembersall .= _APCAL_RO_ONLINE.": ". $itemstotal;
		if (!$this->user_id==0) {
			$eventmembers .= "<BR />"._APCAL_RO_UNAME.":";
		} else {
			$eventmembers = "";
		}	
	}
	
	if (!$this->user_id==0) {
        $result_ro = mysql_query( "SELECT " .XOOPS_DB_PREFIX."_users.uname, 
                    " .XOOPS_DB_PREFIX."_users.uid, count(rom_id) as counter   
                    FROM " .XOOPS_DB_PREFIX.$this->table_ro_members. 
                    " INNER JOIN " .XOOPS_DB_PREFIX."_users ON " .XOOPS_DB_PREFIX.$this->table_ro_members. 
                    ".rom_submitter = " .XOOPS_DB_PREFIX."_users.uid 
                    WHERE (((" .XOOPS_DB_PREFIX.$this->table_ro_members. ".rom_eventid)=" . $event->id . ")) GROUP BY 1,2"
                     , $this->conn ) ;
        $num_rows = mysql_num_rows( $result_ro ) ;
		$baseurl=XOOPS_URL;

		while( $row = mysql_fetch_row( $result_ro ) ) {
			$uname=$row[0];
			$uid=$row[1];
			$counter=$row[2];
			$eventmembers = ( substr($eventmembers, strlen($eventmembers)-1, 1) == ':' ) ?  $eventmembers .= " " : $eventmembers .= ", ";
			$eventmembers .= "<a href='" .XOOPS_URL. "/userinfo.php?uid=" . $uid . "' title=" . $uname . ">" . $uname . "</a>";

			if( $this->user_id == $uid ) {$registered=1;}			
			if( $counter>1) {$eventmembers .= " (".$counter.")";}
		}

		if( ! empty( $_SERVER['HTTPS'] ) ) {
			$this->redirecturl = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
		} else {
			$this->redirecturl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
		}
	
        $eventmembers_form = "
        <form method='post' action='ro_regonlinehandler.php' name='roformmembers1' style='margin:0px;'>
            <input type='hidden' name='eventid' value='$event->id' />
            <input type='hidden' name='uid' value='$this->user_id' />
            <input type='hidden' name='eventurl' value='$this->redirecturl' />
            <input type='hidden' name='summary' value='$summary' />
            <input type='hidden' name='date' value='$start_date_str' />
            <input type='hidden' name='location' value='$location' />
            <div style='float:right;'>";
        if( $registered==1 ) {
            $eventmembers_form .= "<input type='submit' name='form_add' value='"._APCAL_RO_BTN_ADDMORE."' />";
        } else {
            $eventmembers_form .= "<input type='submit' name='form_add' value='"._APCAL_RO_BTN_ADD."' />&nbsp;";
        }
        if( $editable && ! $for_print && $itemstotal>0) $eventmembers_form .="<input type='submit' name='list' value='"._APCAL_RO_BTN_LISTMEMBERS."' />";	
        $eventmembers_form .= "</div></form>\n" ;
		
	}else{
		$eventmembers_form = "<br />"._APCAL_RO_ONLY_MEMBERS;
	}

	
		$eventmembertable= "
		<tr>
			<td class='head'>"._APCAL_RO_ONLINE."</td>
			<td class='even'>
				<div style='float:left; margin: 2px;'>$eventmembersall$eventmembers</div>
				<div style='float:left; margin: 2px;'>$eventmembers_form</div>
			</td>
		</tr>";
	} else {
		$eventmembertable= "
		<tr>
			<td class='head'>"._APCAL_RO_ONLINE."</td>
			<td class='even'>
				<div style='float:left; margin: 2px;'>"._APCAL_RO_ONLINE_NO."</div>
			</td>
		</tr>";
	}
/*******************************************************************/
/* end added by goffy:                                             */
/*******************************************************************/
    
    $pictures = '';
    $pics = mysql_query("SELECT picture FROM {$this->pic_table} WHERE event_id={$event_id} ORDER BY main_pic DESC, id ASC LIMIT 0,{$this->nbPictures}", $this->conn);
    while($pic = mysql_fetch_object($pics))
    {
        if(!Thumb::exists($pic->picture)) {Thumb::save($pic->picture, $this->picWidth, $this->picHeight);}
        $pictures .=
            '<div style="padding: 10px 0;">
                <a href="'.XOOPS_UPLOAD_URL.'/APCal/'.$pic->picture.'" class="highslide" onclick="return hs.expand(this)">
                    <img src="'.XOOPS_UPLOAD_URL.'/APCal/thumbs/'.$pic->picture.'" alt="Image" />
                </a>
             </div>';
    }

    if($xoopsTpl)
    {
        $prevEvent = mysql_query("SELECT id,start FROM $this->table WHERE id<{$event->id} AND start={$event->start} ORDER BY id DESC LIMIT 0,1", $this->conn);
        $prevEvent = mysql_fetch_object($prevEvent);
        if(!$prevEvent)
        {
            $prevEvent = mysql_query("SELECT id,start FROM $this->table WHERE start<{$event->start} ORDER BY start DESC LIMIT 0,1", $this->conn);
            $prevEvent = mysql_fetch_object($prevEvent);
        }
        $prevEvent = $prevEvent ? $this->make_event_link($prevEvent->id) : false;
        $xoopsTpl->assign('prevEvent', $prevEvent);
        
        $nextEvent = mysql_query("SELECT id,start FROM $this->table WHERE id>{$event->id} AND start={$event->start} ORDER BY id ASC LIMIT 0,1", $this->conn);
        $nextEvent = mysql_fetch_object($nextEvent);
        if(!$nextEvent)
        {
            $nextEvent = mysql_query("SELECT id,start FROM $this->table WHERE start>{$event->start} ORDER BY start ASC LIMIT 0,1", $this->conn);
            $nextEvent = mysql_fetch_object($nextEvent);
        }
        $nextEvent = $nextEvent ? $this->make_event_link($nextEvent->id) : false;
        $xoopsTpl->assign('nextEvent', $nextEvent);

        $xoopsTpl->assign('title', $summary);
        $xoopsTpl->assign('location', $location);
        $xoopsTpl->assign('contact', $contact);
        $xoopsTpl->assign('email', $email);
        $xoopsTpl->assign('url', $url);
        $xoopsTpl->assign('startdate', date('Y-n-j', $event->start));
        $xoopsTpl->assign('calLink', $this->make_cal_link('', '', 0, date('Y-n-j', $event->start)));
        $xoopsTpl->assign('GMLat', $event->gmlat);
        $xoopsTpl->assign('GMLong', $event->gmlong);
        $xoopsTpl->assign('GMZoom', $event->gmzoom);
        $xoopsTpl->assign('GMheight', $this->gmheight.'px');
        $xoopsTpl->assign('eventNavEnabled', $this->eventNavEnabled);
        $xoopsTpl->assign('picsWidth', $pictures != '' ? ($this->picWidth + 10).'px' : 0);
        $xoopsTpl->assign('picsMargin', $pictures != '' ? ($this->picWidth + 20).'px' : 0);
        $xoopsTpl->assign('pictures', $pictures);
    }

	$ret = "
	<table border='0' cellpadding='0' cellspacing='2'>";
    $ret .= ($summary != '') ? "
	<tr>
		<td class='head'>"._APCAL_TH_SUMMARY."</td>
		<td class='even'>$summary</td>
	</tr>" : '';
    $ret .= "
    <tr>
        <td class='head'>"._APCAL_TH_STARTDATETIME."</td>
        <td class='even'>$start_datetime_str</td>
    </tr>
    <tr>  
        <td class='head'>"._APCAL_TH_ENDDATETIME."</td>
        <td class='even'>$end_datetime_str</td>
        </tr>";
    $ret .= ($location != '') ? "
	<tr>
		<td class='head'>"._APCAL_TH_LOCATION."</td>
		<td class='even'>$location</td>
	</tr>" : '';
    $ret .= ($contact != '') ? "
	<tr>
		<td class='head'>"._APCAL_TH_CONTACT."</td>
		<td class='even'>$contact</td>
	</tr>" : '';
    $ret .= ($email != '') ? "
    <tr>
		<td class='head'>"._APCAL_TH_EMAIL."</td>
		<td class='even'><a href='mailto:$email'>$email</a></td>
	</tr>" : '';
    $ret .= ($url != '') ? "
    <tr>
		<td class='head'>"._APCAL_TH_URL."</td>
		<td class='even'><a href='$url' target='_blank'>$url</a></td>
	</tr>" : '';
    $ret .= ($description != '' || $otherHours != '') ? "
	<tr>
		<td class='head'>"._APCAL_TH_DESCRIPTION."</td>
		<td class='even'>$description<br />$otherHours</td>
	</tr>" : '';
    $ret .= ($cat_titles4show != '') ? "
	<tr>
		<td class='head'>"._APCAL_TH_CATEGORIES."</td>
		<td class='even'>$cat_titles4show</td>
	</tr>" : '';
    $ret .= ($this->isadmin ? "
	<tr>
		<td class='head'>"._APCAL_TH_SUBMITTER."</td>
		<td class='even'>$submitter_info</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_CLASS."</td>
		<td class='even'>$class_status</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_RRULE."</td>
		<td class='even'>$rrule</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_ADMISSIONSTATUS."</td>
		<td class='even'>$admission_status</td>
	</tr>
    " : '')."
	<tr>
		<td class='head'>"._APCAL_TH_LASTMODIFIED."</td>
        <td class='even'>$last_modified</td>
	</tr>".
    ($this->enableregistration ? $eventmembertable : '') // goffy
	."<tr>
		<td></td>
		<td align='center'>
			<div style='float:left; margin: 2px;'>$edit_button</div>
			<div style='float:left; margin: 2px;'>$delete_button</div>
			<div style='float:left; margin: 2px;'>$ics_output_button</div>
		</td>
	</tr>
	<tr>
		<td><img src='$this->images_url/spacer.gif' alt='' width='150' height='4' /></td>		<td width='100%'></td>
	</tr>
	<tr>
		<td width='100%' align='right' colspan='2'>".APCAL_COPYRIGHT."</td>
	</tr>
	</table>
    \n" ;

	return $ret ;
}

// Edit an event form
function get_schedule_edit_html( )
{
	$editable = $this->editable ;
	$deletable = $this->deletable ;
	$smode = empty( $_GET['smode'] ) ? 'Monthly' : preg_replace('/[^a-zA-Z0-9_-]/','',$_GET['smode']) ;

	if( ! empty( $_GET[ 'event_id' ] ) ) {

		if( ! $this->editable ) die( "Not allowed" ) ;

		$event_id = intval( $_GET[ 'event_id' ] ) ;
		$yrs = mysql_query( "SELECT * FROM $this->table WHERE id='$event_id'" , $this->conn ) ;
		if( mysql_num_rows( $yrs ) < 1 ) die( _APCAL_ERR_INVALID_EVENT_ID ) ;
		$event = mysql_fetch_object( $yrs ) ;

		if( $event->uid != $this->user_id && ! $this->isadmin ) {
			$editable = false ;
			$deletable = false ;
		}

		$description = $this->textarea_sanitizer_for_edit( $event->description ) ;
		$summary = $this->text_sanitizer_for_edit( $event->summary ) ;
		$location = $this->text_sanitizer_for_edit( $event->location ) ;
        $gmlat = $event->gmlat != 0 ? $event->gmlat : 0;
        $gmlong = $event->gmlong != 0 ? $event->gmlong : 0;
        $gmzoom = $event->gmzoom > 0 ? $event->gmzoom : 0;
		$contact = $this->text_sanitizer_for_edit( $event->contact ) ;
        $email = $this->text_sanitizer_for_edit( $event->email ) ;
        $url = $this->text_sanitizer_for_edit( $event->url ) ;
		$categories = $event->categories ;
        $mainCategory = $event->mainCategory;
		if( $event->class == 'PRIVATE' ) {
			$class_private = "checked='checked'" ;
			$class_public = '' ;
			$select_private_disabled = '' ;
		} else {
			$class_private = '' ;
			$class_public = "checked='checked'" ;
			$select_private_disabled = "disabled='disabled'" ;
		}
		$groupid = $event->groupid ;
		$rrule = $event->rrule ;
		$admission_status = $event->admission ? _APCAL_MB_EVENT_ADMITTED : _APCAL_MB_EVENT_NEEDADMIT ;
		$update_button = $editable ? "<input name='update' type='submit' value='"._APCAL_BTN_SUBMITCHANGES."' />" : "" ;
		$insert_button = "<input name='saveas' type='submit' value='"._APCAL_BTN_SAVEAS."' onclick='return confirm(\""._APCAL_CNFM_SAVEAS_YN."\")' />" ;
		$delete_button = $deletable ? "<input name='delete' type='submit' value='"._APCAL_BTN_DELETE."' onclick='return confirm(\""._APCAL_CNFM_DELETE_YN."\")' />" : "" ;
		$tz_options = $this->get_tz_options( $event->event_tz ) ;
		$poster_tz = $event->poster_tz ;
        
        // added by goffy for online registration
		if( ! empty( $_SERVER['HTTPS'] ) ) {
			$this->redirecturl = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
		} else {
			$this->redirecturl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
		}
        
        $regonline_label=_APCAL_RO_ENABLE_ONLINE;
        $regonline_state = ($event->extkey0==1) ? _APCAL_RO_ONLINE_YES : _APCAL_RO_ONLINE_NO;

        $regonline_state.="
        <form method='post' action='ro_regonlinehandler.php' name='roformactivate1' style='margin:0px;'>
            <input type='hidden' name='eventid' value='$event->id' />
            <input type='hidden' name='uid' value='$this->user_id' />
            <input type='hidden' name='eventurl' value='$this->redirecturl' />
            <input type='hidden' name='title' value='$event->summary' />
            <input type='hidden' name='eventdate' value='$event->start' />
            <input type='hidden' name='location' value='$event->location' />
            <div align='left'>";
            if ($event->extkey0==1) {
                $regonline_state.="
                <input type='submit' name='form_activate' value='"._APCAL_RO_BTN_RO_EDIT."' />
                <input type='submit' name='deactivate_x' value='"._APCAL_RO_BTN_RO_DEACTIVATE."' />";
            } else {
                $regonline_state.="<input type='submit' name='form_activate' value='"._APCAL_RO_BTN_RO_ACTIVATE."' />";
            }
            $regonline_state.="</div>
        </form>";
        
        $ro_form_edit = "<table>
            <tr>
                <td class='odd' colspan='2'></td>
            </tr>
            <tr>
                <td class='head'>"._APCAL_RO_ENABLE_ONLINE."</td>
                <td class='even'>".$regonline_state."</td>
            </tr></table>" ;
        $ro_form_new ="";
        // end added by goffy

        $diff = date('j', $event->end) - date('j', $event->start);
        if($event->otherHours != '' && $event->allday <= 0)
        {
            $diffhours_checkbox = "checked='checked'";
            $otherHours = explode('-', $event->otherHours);
            foreach($otherHours as $h)
            {
                $h = explode(':', $h);
                $startHours .= "<span name='StartSpan'>"._APCAL_DAY.' '.($h[0]+1);
                $startHours .= " <select name='StartH[]'>".$this->get_options_for_hour($h[1]).'</select>';
                $startHours .= " <select name='StartM[]'>".$this->get_options_for_min($h[2]).'</select></span>';
                $endHours .= "<span name='EndSpan'>"._APCAL_DAY.' '.($h[0]+1);
                $endHours .= " <select name='EndH[]'>".$this->get_options_for_hour($h[3]).'</select>';
                $endHours .= " <select name='EndM[]'>".$this->get_options_for_min($h[4]).'</select></span>';
            }
        }
        elseif($diff > 0 && $event->allday <= 0)
        {
            $samehours_checkbox = "checked='checked'";
            for($i=0; $i<$diff; $i++)
            {
                $startHours .= _APCAL_DAY.' '.($i+2);
                $startHours .= " <select name='StartH[]' disabled>".$this->get_options_for_hour(9).'</select>';
                $startHours .= " <select name='StartM[]' disabled>".$this->get_options_for_min(0).'</select>';
                $endHours .= _APCAL_DAY.' '.($i+2);
                $endHours .= " <select name='EndH[]' disabled>".$this->get_options_for_hour(17).'</select>';
                $endHours .= " <select name='EndM[]' disabled>".$this->get_options_for_min(0).'</select>';
            }
        }

		if( $event->allday ) {
			$select_timezone_disabled = "disabled='disabled'" ;
			$allday_checkbox = "checked='checked'" ;
			$allday_select = "disabled='disabled'" ;
			$allday_bit1 = ( $event->allday & 2 ) ? "checked='checked'" : "" ;
			$allday_bit2 = ( $event->allday & 4 ) ? "checked='checked'" : "" ;
			$allday_bit3 = ( $event->allday & 8 ) ? "checked='checked'" : "" ;
			$allday_bit4 = ( $event->allday & 16 ) ? "checked='checked'" : "" ;
			if( isset( $event->start_date ) ) {
				$start_ymd = $start_long_ymdn = $event->start_date ;
			} else {
				$start_ymd = date( "Y-m-d" , $event->start ) ;
				$start_long_ymdn = $this->get_long_ymdn( $event->start ) ;
			}
			$start_hour = 0 ;
			$start_min = 0 ;
			if( isset( $event->end_date ) ) {
				$end_ymd = $end_long_ymdn = $event->end_date ;
			} else {
				$end_ymd = date( "Y-m-d" , $event->end - 300 ) ;
				$end_long_ymdn = $this->get_long_ymdn( $event->end - 300 ) ;
			}
			$end_hour = 23 ;
			$end_min = 55 ;
		} else {
			$select_timezone_disabled = "" ;
			$tzoffset_s2e = intval( ( $event->event_tz - $this->server_TZ ) * 3600 ) ;
			$event->start += $tzoffset_s2e ;
			$event->end += $tzoffset_s2e ;
			$allday_checkbox = "" ;
            if(!isset($samehours_checkbox) && !isset($samehours_checkbox)) {$samehours_checkbox = "checked='checked'";}
			$allday_select = "" ;
			$allday_bit1 = $allday_bit2 = $allday_bit3 = $allday_bit4 = "" ;
			$start_ymd = date( "Y-m-d" , $event->start ) ;
			$start_long_ymdn = $this->get_long_ymdn( $event->start ) ;
			$start_hour = date( "H" , $event->start ) ;
			$start_min = date( "i" , $event->start ) ;
			$end_ymd = date( "Y-m-d" , $event->end ) ;
			$end_long_ymdn = $this->get_long_ymdn( $event->end ) ;
			$end_hour = date( "H" , $event->end ) ;
			$end_min = date( "i" , $event->end ) ;
		}
	} else {
		if( ! $this->insertable ) die( "Not allowed" ) ;

		$event_id = 0 ;

		$editable = true ;
		$summary = '' ;
		$select_timezone_disabled = "" ;
		$location = '' ;
        $gmlat = 0;
        $gmlong = 0;
        $gmzoom = 0;
		$contact = '' ;
        $email = '';
        $url = '';
		$class_private = '' ;
		$class_public = "checked='checked'" ;
		$select_private_disabled = "disabled='disabled'" ;
		$groupid = 0 ;
		$rrule = '' ;
		$description = '' ;
		$categories = $this->now_cid > 0 ? sprintf("%05d," , $this->now_cid) : '';
        $mainCategory = $this->now_cid > 0 ? sprintf("%05d,", $this->now_cid) : 0;
		$start_ymd = $end_ymd = $this->caldate ;
		$start_long_ymdn = $end_long_ymdn = $this->get_long_ymdn( $this->unixtime ) ;
		$start_hour = 9 ;
		$start_min = 0 ;
		$end_hour = 17 ;
		$end_min = 0 ;
		$admission_status = _APCAL_MB_EVENT_NOTREGISTER ;
		$update_button = '' ;
		$insert_button = "<input name='insert' type='submit' value='"._APCAL_BTN_NEWINSERTED."' />" ;
		$delete_button = '' ;
		$allday_checkbox = $allday_select = "" ;
		$allday_bit1 = $allday_bit2 = $allday_bit3 = $allday_bit4 = "" ;
		$tz_options = $this->get_tz_options( $this->user_TZ ) ;
		$poster_tz = $this->user_TZ ;
        
        // added by goffy for online registration
		$regonline_label=_APCAL_RO_ONLINE2;
		$regonline_state=_APCAL_RO_ONLINE_NO;
		$ro_form_edit = "";
		$ro_form_new ="
            <tr>
				<td class='head'>"._APCAL_RO_ONLINE2."</td>
				<td class='even'>
					<input type='radio' name='ro_activate' value='yes' > "._APCAL_RO_ONLINE_ACTIVATE."<br/>
					<input type='radio' name='ro_activate' value='no' checked> "._APCAL_RO_ONLINE_DEACTIVATE."
				</td>
			</tr>" ;
        // end goffy
	}

	// Start Date
	$textbox_start_date = $this->get_formtextdateselect( 'StartDate' , $start_ymd , $start_long_ymdn ) ;

	// Start Hour
	$select_start_hour = "<select name='StartHour' $allday_select>\n" ;
	$select_start_hour .= $this->get_options_for_hour( $start_hour ) ;
	$select_start_hour .= "</select>" ;

	// Start Minutes
	$select_start_min = "<select name='StartMin' $allday_select>\n" ;
	for( $m = 0 ; $m < 60 ; $m += 5 ) {
		if( $m == $start_min ) $select_start_min .= "<option value='$m' selected='selected'>" . sprintf( "%02d" , $m ) . "</option>\n" ;
		else $select_start_min .= "<option value='$m'>" . sprintf( "%02d" , $m ) . "</option>\n" ;
	}
	$select_start_min .= "</select>" ;

	// End Date
	$textbox_end_date = $this->get_formtextdateselect( 'EndDate' , $end_ymd , $end_long_ymdn ) ;

	// End Hour
	$select_end_hour = "<select name='EndHour' $allday_select>\n" ;
	$select_end_hour .= $this->get_options_for_hour( $end_hour ) ;
	$select_end_hour .= "</select>" ;

	// End Minutes
	$select_end_min = "<select name='EndMin' $allday_select>\n" ;
	for( $m = 0 ; $m < 60 ; $m += 5 ) {
		if( $m == $end_min ) $select_end_min .= "<option value='$m' selected='selected'>" . sprintf( "%02d" , $m ) . "</option>\n" ;
		else $select_end_min .= "<option value='$m'>" . sprintf( "%02d" , $m ) . "</option>\n" ;
	}
	$select_end_min .= "</select>" ;

	// Checkbox for selecting Categories
	$category_checkboxes = '' ;
	foreach( $this->categories as $cid => $cat ) {
		$cid4sql = sprintf( "%05d," , $cid ) ;
		$cat_title4show = $this->text_sanitizer_for_show( $cat->cat_title ) ;
		if( $cat->cat_depth < 2 ) {
			$category_checkboxes .= "<div style='float:left; margin:2px;'>\n" ;
		}
		$category_checkboxes .= str_repeat( '-' , $cat->cat_depth - 1 ) . "<input type='checkbox' name='cids[]' value='$cid' ".(strstr($categories,$cid4sql)?"checked='checked'":"")." />$cat_title4show<br />\n" ;
	}
	$category_checkboxes = substr( str_replace( '<div' , '</div><div' , $category_checkboxes ) , 6 ) . "</div>\n" ;

    // Select for selecting main category
    $category_select = "<select name='mainCategory'>\n";
    $category_select .= "<option  value='0' ".($mainCategory == 0 ? "selected='selected'" : '')." />"._APCAL_NONE."</option>\n" ;
	foreach($this->canbemain_cats as $cid => $cat) {
		$cat_title4show = $this->text_sanitizer_for_show($cat->cat_title);
		$category_select .= "<option  value='$cid' ".($mainCategory == $cid ? "selected='selected'" : '')." />".str_repeat('&nbsp;&nbsp;', $cat->cat_depth - 1)." $cat_title4show</option>\n" ;
	}
	$category_select .= "</select>\n" ;

	// target for "class = PRIVATE"
	$select_private = "<select name='groupid' $select_private_disabled>\n<option value='0'>"._APCAL_OPT_PRIVATEMYSELF."</option>\n" ;
	foreach( $this->groups as $sys_gid => $gname ) {
		$option_desc = sprintf( _APCAL_OPT_PRIVATEGROUP , $gname ) ;
		if( $sys_gid == $groupid ) $select_private .= "<option value='$sys_gid' selected='selected'>$option_desc</option>\n" ;
		else $select_private .= "<option value='$sys_gid'>$option_desc</option>\n" ;
	}
	$select_private .= "</select>" ;

	if( defined( 'XOOPS_ROOT_PATH' ) ) {
		include_once( XOOPS_ROOT_PATH . "/include/xoopscodes.php" ) ;
		ob_start();
		$GLOBALS["description_text"] = $description;
		xoopsCodeTarea("description_text",50,6);
		$description_textarea = ob_get_contents();
		ob_end_clean();
	} else {
		$description_textarea = "<textarea name='description' cols='50' rows='6' wrap='soft'>$description</textarea>" ;
	}

    // MAIN PICTURE
    $picture = $event_id > 0 ? mysql_query("SELECT id, picture FROM {$this->pic_table} WHERE event_id={$event_id} AND main_pic=1 LIMIT 0,1", $this->conn) : false;
    if(mysql_num_rows($picture))
    {
        $picture = mysql_fetch_object($picture);
        $mainPic =
            '<div id=mainPicture>
                <a href="'.XOOPS_UPLOAD_URL.'/APCal/'.$picture->picture.'">
                    <img src="'.XOOPS_URL.'/modules/APCal/thumbs/phpThumb.php?src='.XOOPS_UPLOAD_PATH.'/APCal/'.$picture->picture.'&h=120&w=120" alt="" />
                 </a>
                 <a href="javascript:deletePic(\''.XOOPS_URL.'\', '.$picture->id.', '.$event_id.', 1, '.$this->nbPictures.');" title="Delete picture">
                     <img src="'.XOOPS_URL.'/modules/APCal/images/delete.png" border="0" alt="Delete picture" />
                 </a>
             </div>';
    }
    else
    {
        $mainPic = 
            '<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
            <input type="file" name="picture0" id="picture0" />
            <input type="hidden" name="files[]" id="files[]" value="picture0">';
    }
    
    // OTHER PICTURES
    $nbPictures = $event_id > 0 ? mysql_fetch_object(mysql_query("SELECT COUNT(id) AS count FROM {$this->pic_table} WHERE event_id={$event_id} AND main_pic=0", $this->conn))->count : 0;
    $picturesList = '';
    if($nbPictures > 0)
    {
        $pictures = mysql_query("SELECT id, picture FROM {$this->pic_table} WHERE event_id={$event_id} AND main_pic=0 ORDER BY id ASC", $this->conn);
        while($pic = mysql_fetch_object($pictures))
        {
            $picturesList .=
                '<span id="pic'.$pic->id.'">
                    <a href="'.XOOPS_UPLOAD_URL.'/APCal/'.$pic->picture.'">
                        <img src="'.XOOPS_URL.'/modules/APCal/thumbs/phpThumb.php?src='.XOOPS_UPLOAD_PATH.'/APCal/'.$pic->picture.'&h=120&w=120" alt="" />
                     </a>
                     <a href="javascript:deletePic(\''.XOOPS_URL.'\', '.$pic->id.', '.$event_id.', 0, '.$this->nbPictures.');" title="Delete the picture">
                         <img src="'.XOOPS_URL.'/modules/APCal/images/delete.png" border="0" alt="Delete the picture" />
                     </a>
                 </span>';
        }
    }
    $pictures = '<div id="picList">';
    $maxInput = $this->nbPictures-$nbPictures;
    for($i=1; $i<$maxInput; $i++)
    {
        $pictures .= 
            '<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
            <input type="file" name="picture'.$i.'" id="picture'.$i.'" />
            <input type="hidden" name="files[]" id="files[]" value="picture'.$i.'">
            <br />';
    }
    $pictures .= '</div>';

	// FORM DISPLAY
    $caldate = explode('-', $_GET['caldate']);
    $caldate = strlen($caldate[0]) > 2 ? $caldate[0].'-'.$caldate[1].'-'.$caldate[2] : $caldate[2].'-'.$caldate[1].'-'.$caldate[0];
	$ret = "
<h2>"._APCAL_MB_TITLE_EVENTINFO." <small>-"._APCAL_MB_SUBTITLE_EVENTEDIT."-</small></h2>
<form action='{$this->make_cal_link('', $smode, 0, $caldate)}' method='post' name='MainForm' enctype='multipart/form-data'>
	".$GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )."
	<input type='hidden' name='caldate' value='{$caldate}' />
	<input type='hidden' name='event_id' value='$event_id' />
	<input type='hidden' name='last_smode' value='$smode' />
	<input type='hidden' name='last_caldate' value='$this->caldate' />
	<input type='hidden' name='poster_tz' value='$poster_tz' />
    <input type='hidden' name='gmlatitude' value='$this->gmlat' />
    <input type='hidden' name='gmlongitude' value='$this->gmlng' />
    <input type='hidden' name='gmzoomlevel' value='$this->gmzoom' />
	<table border='0' cellpadding='0' cellspacing='2'>
	<tr>
		<td class='head'>"._APCAL_TH_SUMMARY."</td>
		<td class='even'><input type='text' name='summary' size='60' maxlength='250' value='$summary' /></td>
	</tr>"
    .($this->displayTimezone ? 
    "<tr>
		<td class='head'>"._APCAL_TH_TIMEZONE."</td>
		<td class='even'><select name='event_tz' $select_timezone_disabled>$tz_options</select></td>
	</tr>" : '').
    "<tr>
		<td class='head'>"._APCAL_TH_STARTDATETIME."</td>
		<td class='even'>
			$textbox_start_date &nbsp;
			{$select_start_hour} {$select_start_min}"._APCAL_MB_MINUTE_SUF."</select>
            <span id='start_datetime'>$startHours</span>
		</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_ENDDATETIME."</td>
		<td class='even'>
			$textbox_end_date &nbsp; 
			{$select_end_hour} {$select_end_min}"._APCAL_MB_MINUTE_SUF."
            <span id='end_datetime'>$endHours</span>
		</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_ALLDAYOPTIONS."</td>
		<td class='even'>
			<input type='radio' name='allday_bits[]' value='2' {$allday_checkbox} onClick='document.MainForm.event_tz.disabled=document.MainForm.StartHour.disabled=document.MainForm.StartMin.disabled=document.MainForm.EndHour.disabled=document.MainForm.EndMin.disabled=true;enableSelects(true);' />"._APCAL_MB_ALLDAY_EVENT." &nbsp;
            <input type='radio' name='allday_bits[]' value='0' {$samehours_checkbox} onClick='document.MainForm.event_tz.disabled=document.MainForm.StartHour.disabled=document.MainForm.StartMin.disabled=document.MainForm.EndHour.disabled=document.MainForm.EndMin.disabled=false;enableSelects(true);' />"._APCAL_SAMEHOURS." &nbsp;
            <input type='radio' name='allday_bits[]' value='8' {$diffhours_checkbox} onClick='document.MainForm.event_tz.disabled=document.MainForm.StartHour.disabled=document.MainForm.StartMin.disabled=document.MainForm.EndHour.disabled=document.MainForm.EndMin.disabled=false;enableSelects(false);' />"._APCAL_DIFFERENTHOURS."
		</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_LOCATION."</td>
		<td class='even'><input type='text' name='location' size='40' maxlength='250' value='$location' /></td>
	</tr>
        <tr>
		<td class='head'></td>
		<td class='even' valign='top'>
                    <a href='' onclick=\"window.open('".XOOPS_URL."/modules/APCal/getCoords.html', '_blank', 'height=450,width=450,modal=yes,alwaysRaised=yes');return false;\">
                        <img src='".XOOPS_URL."/modules/APCal/images/gmap.png' />"
                        ._APCAL_TH_GETCOORDS.
                    "</a>
                </td>
	</tr>
        <tr>
		<td class='head'>"._APCAL_TH_LATITUDE."</td>
		<td class='even'><input type='text' name='gmlat' size='40' maxlength='250' value='$gmlat' /></td>
	</tr>
        <tr>
		<td class='head'>"._APCAL_TH_LONGITUDE."</td>
		<td class='even'><input type='text' name='gmlong' size='40' maxlength='250' value='$gmlong' /></td>
	</tr>
        <tr>
		<td class='head'>"._APCAL_TH_ZOOM."</td>
		<td class='even'><input type='text' name='gmzoom' size='40' maxlength='250' value='$gmzoom' /></td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_CONTACT."</td>
		<td class='even'><input type='text' name='contact' size='50' maxlength='250' value='$contact' /></td>
	</tr>
    <tr>
		<td class='head'>"._APCAL_TH_EMAIL."</td>
		<td class='even'><input type='text' name='email' size='50' maxlength='250' value='$email' /></td>
	</tr>
    <tr>
		<td class='head'>"._APCAL_TH_URL."</td>
		<td class='even'><input type='text' name='url' size='50' maxlength='250' value='$url' /></td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_DESCRIPTION."</td>
		<td class='even'>$description_textarea</td>
	</tr>
    <tr>
		<td class='head'>"._APCAL_MAINPICTURE."</td>
		<td class='even'>$mainPic</td>
	</tr>
    <tr>
		<td class='head'>"._APCAL_PICTURES."</td>
		<td class='even'>$pictures<br />$picturesList</td>
	</tr>
    <tr>
		<td class='head'>"._APCAL_TH_MAINCATEGORY."</td>
		<td class='even'>$category_select</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_CATEGORIES."</td>
		<td class='even'>$category_checkboxes</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_CLASS."</td>
		<td class='even'><input type='radio' name='class' value='PUBLIC' $class_public onClick='document.MainForm.groupid.disabled=true' />"._APCAL_MB_PUBLIC." &nbsp;  &nbsp; <input type='radio' name='class' value='PRIVATE' $class_private onClick='document.MainForm.groupid.disabled=false' />"._APCAL_MB_PRIVATE.sprintf( _APCAL_MB_PRIVATETARGET , $select_private )."</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_RRULE."</td>
		<td class='even'>" . $this->rrule_to_form( $rrule , $end_ymd ) . "</td>
	</tr>
	<tr>
		<td class='head'>"._APCAL_TH_ADMISSIONSTATUS."</td>
		<td class='even'>$admission_status</td>
	</tr>\n" ;

    if($this->enableregistration) {$ret .= $ro_form_new;} //added one line by goffy
    
	if( $editable ) {
	$ret .= "
	<tr>
		<td style='text-align:center' colspan='2'>
			<input name='reset' type='reset' value='"._APCAL_BTN_RESET."' />
			$update_button
			$insert_button
			$delete_button
		</td>
	</tr>\n" ;
	}

	$ret .= "
	<tr>
		<td><img src='$this->images_url/spacer.gif' alt='' width='150' height='4' /></td>		<td width='100%'></td>
	</tr>
	</table>
</form>
\n";

if($this->enableregistration) {$ret .= $ro_form_edit;} // splitted and added one line by goffy
$ret .= "<table>
        <tr><td><img src='$this->images_url/spacer.gif' alt='' height='4' /></td></tr>
        <tr><td width='100%' align='right'>".APCAL_COPYRIGHT."</td></tr>
	</table>";

$ret .= "
<script type='text/javascript'>
    function addHours()
    {
        var startDate = document.MainForm.StartDate.value.split('-');
        var endDate = document.MainForm.EndDate.value.split('-');
        startDate = new Date(startDate[0], startDate[1]-1, startDate[2]).getTime();
        endDate = new Date(endDate[0], endDate[1]-1, endDate[2]).getTime();
        var diff = (endDate - startDate) / 3600000 / 24;
        var diffBefore = document.getElementsByName('StartH[]').length;
        var start = document.getElementById('start_datetime');
        var end = document.getElementById('end_datetime');
        var maxDays = 30;

        if(diffBefore < diff) {
            for(var i=diffBefore; i<diff&&i<=maxDays; i++) {
                var startNode = document.createElement('span');
                var endNode = document.createElement('span');

                startNode.innerHTML += \""._APCAL_DAY." \"+(i+2);
                startNode.innerHTML += \"<select name='StartH[]' disabled>".str_replace("\n", '', $this->get_options_for_hour($start_hour))."</select>\";
                startNode.innerHTML += \"<select name='StartM[]' disabled>".str_replace("\n", '', $this->get_options_for_min($start_min))."</select>\";
                endNode.innerHTML += \""._APCAL_DAY." \"+(i+2);
                endNode.innerHTML += \"<select name='EndH[]' disabled>".str_replace("\n", '', $this->get_options_for_hour($end_hour))."</select>\";
                endNode.innerHTML += \"<select name='EndM[]' disabled>".str_replace("\n", '', $this->get_options_for_min($end_min))."</select>\";

                start.appendChild(startNode);
                end.appendChild(endNode);
            }
        }
        else if(diff >= 0 && diff<=maxDays) {
            var StartSpan = document.getElementById('start_datetime').getElementsByTagName('span');
            var EndSpan = document.getElementById('end_datetime').getElementsByTagName('span');
            for(var i=diffBefore-1; i>=diff; i--) {
                StartSpan[i].outerHTML = '';
                EndSpan[i].outerHTML = '';
            }
        }
        enableSelects(!document.getElementsByName('allday_bits[]')[2].checked);
    }

    function enableSelects(disabled)
    {
        var StartH = document.getElementsByName('StartH[]');
        var StartM = document.getElementsByName('StartM[]');
        var EndH = document.getElementsByName('EndH[]');
        var EndM = document.getElementsByName('EndM[]');
        var nbItems = StartH.length;

        for(var i=0; i<nbItems; i++)
        {
            StartH[i].disabled = disabled;
            StartM[i].disabled = disabled;
            EndH[i].disabled = disabled;
            EndM[i].disabled = disabled;
        }
    }

    function checkChange()
    {
        var newStart = document.MainForm.StartDate.value;
        var newEnd = document.MainForm.EndDate.value;
        if(oldStart != newStart || oldEnd != newEnd) {addHours();}
        oldStart = newStart;
        oldEnd = newEnd;
    }
    var oldStart = document.MainForm.StartDate.value;
    var oldEnd = document.MainForm.EndDate.value;
    setInterval(checkChange, 750);
</script>\n
";

	return $ret ;
}

// Save an event
function update_schedule( $set_sql_append = '' , $whr_sql_append = '' , $notify_callback = null )
{
	if( $_POST[ 'summary' ] == "" ) $_POST[ 'summary' ] = _APCAL_MB_NOSUBJECT ;

	list( $start , $start_date , $use_default ) = $this->parse_posted_date( $this->mb_convert_kana( $_POST[ 'StartDate' ] , "a" ) , $this->unixtime ) ;
	list( $end , $end_date , $use_default ) = $this->parse_posted_date( $this->mb_convert_kana( $_POST[ 'EndDate' ] , "a" ) , $this->unixtime ) ;

	$allday = 1 ;
	if( isset( $_POST[ 'allday_bits' ] ) ) {
		$bits = $_POST[ 'allday_bits' ] ;
		if( is_array( $bits ) ) foreach( $bits as $bit ) {
			if( $bit > 0 && $bit < 8 ) {
				$allday += pow( 2 , intval( $bit ) ) ;
			}
		}
	}

	if($start_date || $end_date) {
		if( $start_date ) $date_append = ", start_date='$start_date'" ;
		else $date_append = ", start_date=null" ;
		if( $end_date ) $date_append .= ", end_date='$end_date'" ;
		else {
			$date_append .= ", end_date=null" ;
			$end += 86400 ;
		}
		$set_sql_date = "start='$start', end='$end', allday='$allday' $date_append" ;
		$allday_flag = true ;
	} else if($_POST['allday_bits'][0] > 0) {
		if( $start > $end ) list( $start , $end ) = array( $end , $start ) ;
		$end += 86400 ;
		$set_sql_date = "start='$start', end='$end', allday='$allday', start_date=null, end_date=null" ;
		$allday_flag = true ;
	} else {
		if( ! isset( $_POST['event_tz'] ) ) $_POST['event_tz'] = $this->user_TZ ;
		$tzoffset_e2s = intval( ( $this->server_TZ - $_POST['event_tz'] ) * 3600 ) ;
		//$tzoffset_e2s = intval( date( 'Z' , $start ) - $_POST['event_tz'] * 3600 ) ;

		$start += $_POST[ 'StartHour' ] * 3600 + $_POST[ 'StartMin' ] * 60 + $tzoffset_e2s ;
		$end += $_POST[ 'EndHour' ] * 3600 + $_POST[ 'EndMin' ] * 60 + $tzoffset_e2s ;
		if( $start > $end ) list( $start , $end ) = array( $end , $start ) ;
		$set_sql_date = "start='$start', end='$end', allday=0, start_date=null, end_date=null" ;
		$allday_flag = false ;
	}

    $otherHours = '';
    if(isset($_POST['allday_bits'][0]) && $_POST['allday_bits'][0] == '8')
    {
        $otherHours = array();
        foreach($_POST['StartH'] as $i => $startH)
        {
            $otherHours[] = ($i+1).':'.$startH.':'.$_POST['StartM'][$i].':'.$_POST['EndH'][$i].':'.$_POST['EndM'][$i];
        }
        $otherHours = implode('-', $otherHours);
    }


	$set_sql_date .= ",server_tz='$this->server_TZ'" ;

	if( ! isset( $_POST[ 'description' ] ) && isset( $_POST[ 'description_text' ] ) ) {
		$_POST[ 'description' ] = $_POST[ 'description_text' ] ;
	}

	$_POST[ 'categories' ] = '' ;
	$cids = is_array( @$_POST['cids'] ) ? $_POST['cids'] : array() ;
    if(!in_array($_POST['mainCategory'], $cids)) {$cids[] = $_POST['mainCategory'];}
	foreach( $cids as $cid ) {
		$cid = intval( $cid ) ;
		while( isset( $this->categories[ $cid ] ) ) {
			$cid4sql = sprintf( "%05d," , $cid ) ;
			if( stristr( $_POST[ 'categories' ] , $cid4sql ) === false ) {
				$_POST[ 'categories' ] .= sprintf( "%05d," , $cid ) ;
			}
			$cid = intval( $this->categories[ $cid ]->pid ) ;
		}
	}

	// RRULE
	$rrule = $this->rrule_from_post( $start , $allday_flag ) ;

	$cols = array("summary" => "255:J:1", "location" => "255:J:0", "contact" => "255:J:0", "email" => "255:J:0", "url" => "255:J:0", "description" => "A:J:0", "categories" => "255:E:0", "class" => "255:E:0", "groupid" => "I:N:0", "poster_tz" => "F:N:0", "event_tz" => "F:N:0");

	$set_str = $this->get_sql_set( $cols ) . ", $set_sql_date $set_sql_append" ;
    $set_str .= ",shortsummary='".$this->makeShort(utf8_decode($_POST['summary']))."'";
    $set_str .= ",mainCategory='".$_POST['mainCategory']."'";
    $set_str .= ",otherHours='".$otherHours."'";

	// Check update or insert
	$event_id = intval( $_POST[ 'event_id' ] ) ;
	if( $event_id > 0 ) {
		$rs = mysql_query( "SELECT rrule_pid FROM $this->table WHERE id='$event_id' $whr_sql_append" , $this->conn ) ;
		if( ! ( $event = mysql_fetch_object( $rs ) ) ) die( "Record Not Exists." ) ;
		if( $event->rrule_pid > 0 ) {
			if( ! mysql_query( "DELETE FROM $this->table WHERE rrule_pid='$event->rrule_pid' AND id<>'$event_id'" , $this->conn ) ) echo mysql_error() ;
		}

		// UPDATE
		if( $rrule != '' ) $set_str .= ", rrule_pid=id" ;
		$sql = "UPDATE $this->table SET $set_str , rrule='$rrule' , sequence=sequence+1, gmlat='{$_POST['gmlat']}', gmlong='{$_POST['gmlong']}', gmzoom='{$_POST['gmzoom']}' WHERE id='$event_id' $whr_sql_append" ;
		if( ! mysql_query( $sql , $this->conn ) ) echo mysql_error() ;

		// RRULE
		if( $rrule != '' ) {
			$this->rrule_extract( $event_id ) ;
		}

		// ï¿½ï¿½ï¿½Ù¤Æ¤ò¹¹¿ï¿½ï¿½å¡¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Õ¤Î¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½?ï¿½ï¿½
		$last_smode = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_smode'] ) ;
		//$last_caldate = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_caldate'] ) ;
		$new_caldate = $start_date ? $start_date : date( 'Y-n-j' , $start ) ;
		//$this->redirect( "smode=$last_smode&caldate=$new_caldate" ) ;

	} else {
		$sql = "INSERT INTO $this->table SET $set_str , rrule='$rrule' , sequence=0, gmlat='{$_POST['gmlat']}', gmlong='{$_POST['gmlong']}', gmzoom='{$_POST['gmzoom']}'" ;
		if( ! mysql_query( $sql , $this->conn ) ) echo mysql_error() ;
		// unique_id,rrule_pid
		$event_id = mysql_insert_id( $this->conn ) ;
		$unique_id = 'apcal060-' . md5( "{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}$event_id") ;
		$rrule_pid = $rrule ? $event_id : 0 ;
		mysql_query( "UPDATE $this->table SET unique_id='$unique_id',rrule_pid='$rrule_pid' WHERE id='$event_id'" , $this->conn ) ;

		// RRULE
		if( $rrule != '' ) {
			$this->rrule_extract( $event_id ) ;
		}

		if( isset( $notify_callback ) ) $this->$notify_callback( $event_id ) ;

		$last_smode = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_smode'] ) ;
		$last_caldate = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_caldate'] ) ;
		//$this->redirect( "smode=$last_smode&caldate=$last_caldate" ) ;
        
        // Save pictures
        if(isset($_POST['saveas']))
        {
            $result = mysql_query("SELECT * FROM {$this->pic_table} WHERE event_id={$_POST['event_oldid']}", $this->conn);
            while($pic = mysql_fetch_object($result))
            {
                mysql_query("INSERT INTO {$this->pic_table}(event_id, picture, main_pic) VALUES ({$event_id}, '{$pic->picture}', {$pic->main_pic})", $this->conn);
            }
        }
        else
            $this->savepictures($event_id);
        
        // added by goffy for registration online automatically redirect to form for set up parameters for online registration, if online registration is selected 
        $ro_redirect = $_POST[ 'ro_activate' ];
        if ($ro_redirect=="yes") {
            if( ! empty( $_SERVER['HTTPS'] ) ) {
                $this->redirecturl = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
            } else {
                $this->redirecturl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
            }
        
            $call_ro="ro_regonlinehandler.php?op=show_form_activate";
            $call_ro.="&uid=$uid";
            $call_ro.="&eventid=$event_id";
            $call_ro.="&title=".$_POST[ 'summary' ];
            $call_ro.="&eventdate=$start";//.$_POST[ 'StartDate' ]." ".$_POST[ 'StartHour' ].":".$_POST[ 'StartMin' ];
            $call_ro.="&eventurl=".$this->redirecturl;
            $call_ro.="&smode=$last_smode";
            $call_ro.="&caldate=$last_caldate";
            redirect_header($call_ro, 3, _APCAL_RO_SUCCESS_NEW_EVENT. "<br/>"._APCAL_RO_REDIRECT);
        } else {
            redirect_header($this->redirecturl. "?smode=$last_smode&caldate=$last_caldate", 3, _APCAL_RO_SUCCESS_NEW_EVENT);
        }
        // end goffy

	}
}

// Delete an event
function delete_schedule( $whr_sql_append = '' , $eval_after = null )
{
	if( ! empty( $_POST[ 'event_id' ] ) ) {

		$event_id = intval( $_POST[ 'event_id' ] ) ;
        
        $this->delete_regonline( $event_id ); // added one line by goffy
		$rs = mysql_query( "SELECT rrule_pid FROM $this->table WHERE id='$event_id' $whr_sql_append" , $this->conn ) ;
		if( ! ( $event = mysql_fetch_object( $rs ) ) ) die( "Record Not Exists." ) ;
		if( $event->rrule_pid > 0 ) {
			if( ! mysql_query( "DELETE FROM $this->table WHERE rrule_pid='$event->rrule_pid' $whr_sql_append" , $this->conn ) ) echo mysql_error() ;
			// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É²Ã½ï¿½ï¿½ï¿½ï¿½evalï¿½Ç¼ï¿½ï¿½ï¿½ï¿½ï¿½ (XOOPSï¿½Ç¤Ï¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½È¤Îºï¿½ï¿½ï¿½
			if( mysql_affected_rows() > 0 && isset( $eval_after ) ) {
				$id = $event->rrule_pid ;
				eval( $eval_after ) ;
			}
		} else {
			if( ! mysql_query( "DELETE FROM $this->table WHERE id='$event_id' $whr_sql_append" , $this->conn ) ) echo mysql_error() ;
			// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É²Ã½ï¿½ï¿½ï¿½ï¿½evalï¿½Ç¼ï¿½ï¿½ï¿½ï¿½ï¿½ (XOOPSï¿½Ç¤Ï¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½È¤Îºï¿½ï¿½ï¿½
			if( mysql_affected_rows() == 1 && isset( $eval_after ) ) {
				$id = $event_id ;
				eval( $eval_after ) ;
			}
		}

	}
	$last_smode = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_smode'] ) ;
	$last_caldate = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_caldate'] ) ;
    $this->redirect( "smode=$last_smode&caldate=$last_caldate" ) ;
}



// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½å¡¼ï¿½ï¿½Î°ï¿½ï¿½ï¿½ï¿½ï¿½RRULEï¿½Î»Ò¶ï¿½ï¿½ì¥³ï¿½ï¿½ï¿½É¡ï¿½
function delete_schedule_one( $whr_sql_append = '' )
{
	if( ! empty( $_POST[ 'subevent_id' ] ) ) {

		$event_id = intval( $_POST[ 'subevent_id' ] ) ;     
        $this->delete_regonline( $event_id ); // added one line by goffy

		if( ! mysql_query( "DELETE FROM $this->table WHERE id='$event_id' AND rrule_pid <> id $whr_sql_append" , $this->conn ) ) echo mysql_error() ;

	}
	$last_smode = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_smode'] ) ;
	$last_caldate = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_POST['last_caldate'] ) ;
	$this->redirect( "smode=$last_smode&caldate=$last_caldate" ) ;
}

//added function by goffy
function delete_regonline( $event_id ) {
    
    //delete data from table apcal_ro_members
    $query = "DELETE ".XOOPS_DB_PREFIX.$this->table_ro_members.".* FROM ".XOOPS_DB_PREFIX.$this->table_ro_members." WHERE ((".XOOPS_DB_PREFIX.$this->table_ro_members.".rom_eventid)=$event_id)";
    $res = mysql_query($query);	

    //delete data from table apcal_ro_notify
    $query = "DELETE ".XOOPS_DB_PREFIX.$this->table_ro_notify.".* FROM ".XOOPS_DB_PREFIX.$this->table_ro_notify." WHERE ((".XOOPS_DB_PREFIX.$this->table_ro_notify.".ron_eventid)=$event_id)";
    $res = mysql_query($query);
    
    //delete data from table apcal_ro_events
    $query = "DELETE ".XOOPS_DB_PREFIX.$this->table_ro_events.".* FROM ".XOOPS_DB_PREFIX.$this->table_ro_events." WHERE ((".XOOPS_DB_PREFIX.$this->table_ro_events.".roe_eventid)=$event_id)";
    $res = mysql_query($query);
}

function redirect($query)
{
	// character white list and black list against 'javascript'
	if( ! preg_match( '/^[a-z0-9=&_-]*$/i' , $query )  || stristr( $query , 'javascript' ) ) {
		header( strtr( "Location: $this->connection://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}" , "\r\n\0" , "   " ) ) ;
		exit ;
	}

	if( headers_sent() ) {
		echo "
			<html>
			<head>
			<title>redirection</title>
			<meta http-equiv='Refresh' content='0; url=?$query' />
			</head>
			<body>
			<p>
				<a href='?$query'>push here if not redirected</a>
			</p>
			</body>
			</html>";
	} else {
		header( strtr( "Location: $this->connection://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?$query" , "\r\n\0" , "   " ) ) ;
	}
	exit ;
}


// -12.0ï¿½ï¿½12.0ï¿½Þ¤Ç¤ï¿½ï¿½Í¤ï¿½ï¿½ï¿½ï¿½ï¿½Æ¡ï¿½(GMT+HH:MM) ï¿½È¤ï¿½ï¿½ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
function get_tz_for_display( $offset )
{
	return $this->displayTimezone ? "(GMT" . ( $offset >= 0 ? "+" : "-" ) . sprintf( "%02d:%02d" , abs( $offset ) , abs( $offset ) * 60 % 60 ) . ")" : '';
}


// -12.0ï¿½ï¿½12.0ï¿½Þ¤Ç¤ï¿½Timzone SELECTï¿½Ü¥Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½OptionÊ¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
function get_tz_options( $selected = 0 )
{
	$tzs = array( '-12','-11','-10','-9','-8','-7','-6',
		'-5','-4','-3.5','-3','-2','-1',
		'0','1','2','3','3.5','4','4.5','5','5.5',
		'6','7','8','9','9.5','10','11','12') ;

	$ret = '' ;
	foreach( $tzs as $tz ) {
		if( $tz == $selected ) $ret .= "\t<option value='$tz' selected='selected'>".$this->get_tz_for_display( $tz )."</option>\n" ;
		else $ret .= "\t<option value='$tz'>".$this->get_tz_for_display( $tz )."</option>\n" ;
	}

	return $ret ;
}


// -12.0ï¿½ï¿½12.0ï¿½Þ¤Ç¤ï¿½ï¿½Í¤ï¿½ï¿½ï¿½ï¿½ï¿½Æ¡ï¿½array(TZOFFSET,TZID)ï¿½ï¿½ï¿½Ö¤ï¿½
function get_timezone_desc( $tz )
{
	if( $tz == 0 ) {
		$tzoffset = "+0000" ;
		$tzid = "GMT" ;
	} else if( $tz > 0 ) {
		$tzoffset = sprintf( "+%02d%02d" , $tz , $tz * 60 % 60 ) ;
		$tzid = "Etc/GMT-" . sprintf( "%d" , $tz ) ;
	} else {
		$tz = abs( $tz ) ;
		$tzoffset = sprintf( "-%02d%02d" , $tz , $tz * 60 % 60 ) ;
		$tzid = "Etc/GMT+" . sprintf( "%d" , $tz ) ;
	}

	return array( $tzoffset , $tzid ) ;
}


// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½ï¿½ï¿½Ê¸ï¿½ï¿½Ü¥Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½à¤´ï¿½Èºï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_categories_selform( $get_target = '' , $smode = null )
{
	global $xoopsModule;
    
    if( empty( $this->categories ) ) return '' ;

	if( empty( $smode ) ) $smode = isset( $_GET['smode'] ) ? $_GET['smode'] : 'Monthly' ;
	$smode = preg_replace('/[^a-zA-Z0-9_-]/','',$smode) ;

	$op = empty( $_GET['op'] ) ? '' : preg_replace('/[^a-zA-Z0-9_-]/','',$_GET['op']) ;

    $ret = "<script type='text/javascript'>\n";
    $ret .= "function submitCat(cid, smode, caldate)\n";
    $ret .= "{\n";
    if($this->useurlrewrite)
    {
        $ret .= "var defaultView = '".($this->default_view)."';\n";
        $ret .= "var today = '".date('Y-n-j')."';\n";
        
        $ret .= "if     (cid != 'All' && smode != defaultView && caldate != today) {document.catSel.action = '".XOOPS_URL."/modules/APCal/' + cid + '-' + smode + '-' + caldate;}\n";
        $ret .= "else if(cid != 'All' && smode != defaultView && caldate == today) {document.catSel.action = '".XOOPS_URL."/modules/APCal/' + cid + '-' + smode;}\n";
        $ret .= "else if(cid != 'All' && smode == defaultView && caldate != today) {document.catSel.action = '".XOOPS_URL."/modules/APCal/' + cid + '-' + caldate;}\n";
        $ret .= "else if(cid == 'All' && smode != defaultView && caldate != today) {document.catSel.action = '".XOOPS_URL."/modules/APCal/' + smode + '-' + caldate;}\n";
        $ret .= "else if(cid == 'All' && smode != defaultView && caldate == today) {document.catSel.action = '".XOOPS_URL."/modules/APCal/' + smode;}\n";
        $ret .= "else if(cid == 'All' && smode == defaultView && caldate != today) {document.catSel.action = '".XOOPS_URL."/modules/APCal/' + caldate;}\n";
        $ret .= "else if(cid != 'All' && smode == defaultView && caldate == today) {document.catSel.action = '".XOOPS_URL."/modules/APCal/' + cid;}\n";
        $ret .= "else {document.catSel.action = '".XOOPS_URL."/modules/APCal/';}\n";
        
        //$ret .= "document.catSel.action = '".XOOPS_URL."/' + cid + '/' + smode + '/' + caldate;\n";
        $ret .= "document.catSel.method = 'POST';\n";
    }
    $ret .= "document.catSel.submit();\n";
    $ret .= "}\n";
    $ret .= "</script>\n";
	$ret .= "<form action='$get_target' name='catSel' method='GET' style='margin:0px;'>\n" ;
	$ret .= "<input type='hidden' name='caldate' value='$this->caldate' />\n" ;
	$ret .= "<input type='hidden' name='smode' value='$smode' />\n" ;
	$ret .= "<input type='hidden' name='op' value='$op' />\n" ;
	$ret .= "<select name='cid' onchange='submitCat(document.catSel.cid.value, document.catSel.smode.value, document.catSel.caldate.value);'>\n" ;
	$ret .= ($this->useurlrewrite) ? "\t<option value='All'>"._APCAL_MB_SHOWALLCAT."</option>\n" : "\t<option value='0'>"._APCAL_MB_SHOWALLCAT."</option>\n";
	foreach( $this->categories as $cid => $cat ) {
		$selected = $this->now_cid == $cid ? "selected='selected'" : "" ;
		$depth_desc = str_repeat( '-' , intval( $cat->cat_depth ) ) ;
		$cat_title4show = $this->text_sanitizer_for_show( $cat->cat_title ) ;
		$ret .= ($this->useurlrewrite) ? "\t<option value='".urlencode(urlencode($cat->cat_shorttitle))."' $selected>$depth_desc $cat_title4show</option>\n" : "\t<option value='$cid' $selected>$depth_desc $cat_title4show</option>\n";
	}
	$ret .= "</select>\n</form>\n" ;

	return $ret ;
}


// Ç¯ï¿½ï¿½ï¿½ï¿½Î¥Æ¥ï¿½ï¿½ï¿½ï¿½È¥Ü¥Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï¤ï¿½ï¿½ï¿½ï¿½ï¿½Æ¡ï¿½UnixTimestampï¿½ï¿½ï¿½Ö¤ï¿½
function parse_posted_date( $date_desc , $default_unixtime )
{
	if( ! ereg( "^([0-9][0-9]+)[-./]?([0-1]?[0-9])[-./]?([0-3]?[0-9])$" , $date_desc , $regs ) ) {
		$unixtime = $default_unixtime ;
		$use_default = true ;
		$iso_date = '' ;
	} else if( $regs[1] >= 2038 ) {
		// 2038Ç¯ï¿½Ê¹ß¤Î¾ï¿½ï¿½ 2038/1/1 ï¿½Ë¥ï¿½ï¿½Ã¥ï¿½
		$unixtime = mktime( 0 , 0 , 0 , 1 , 1 , 2038 ) ;
		$use_default = false ;
		$iso_date = "{$regs[1]}-{$regs[2]}-{$regs[3]}" ;
	} else if( $regs[1] <= 1970 ) {
		// 1970Ç¯ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½ 1970/12/31ï¿½Ë¥ï¿½ï¿½Ã¥ï¿½
		$unixtime = mktime( 0 , 0 , 0 , 12 , 31 , 1970 ) ;
		$use_default = false ;
		$iso_date = "{$regs[1]}-{$regs[2]}-{$regs[3]}" ;
	} else if( ! checkdate( $regs[2] , $regs[3] , $regs[1] ) ) {
		$unixtime = $default_unixtime ;
		$use_default = true ;
		$iso_date = '' ;
	} else {
		$unixtime = mktime( 0 , 0 , 0 , $regs[2] , $regs[3] , $regs[1] ) ;
		$use_default = false ;
		$iso_date = '' ;
	}

	return array( $unixtime , $iso_date , $use_default ) ;
}


// timezoneï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ¡ï¿½RFC2445ï¿½ï¿½VTIMEZONEï¿½ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
function get_vtimezones_str( $timezones )
{
	if( empty( $timezones ) ) {

		return 
"BEGIN:VTIMEZONE\r
TZID:GMT\r
BEGIN:STANDARD\r
DTSTART:19390101T000000\r
TZOFFSETFROM:+0000\r
TZOFFSETTO:+0000\r
TZNAME:GMT\r
END:STANDARD\r
END:VTIMEZONE\r\n" ;

	} else {

		$ret = "" ;
		foreach( $timezones as $tz => $dummy ) {

			list( $for_tzoffset , $for_tzid ) = $this->get_timezone_desc( $tz ) ;

			$ret .= 
"BEGIN:VTIMEZONE\r
TZID:$for_tzid\r
BEGIN:STANDARD\r
DTSTART:19390101T000000\r
TZOFFSETFROM:$for_tzoffset\r
TZOFFSETTO:$for_tzoffset\r
TZNAME:$for_tzid\r
END:STANDARD\r
END:VTIMEZONE\r\n" ;

		}
		return $ret ;
	}
}


// Ï¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ë¼ï¿½ê¡¢$_POSTï¿½ï¿½ï¿½ï¿½INSERT,UPDATEï¿½Ñ¤ï¿½SETÊ¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ë¥¯ï¿½é¥¹ï¿½Ø¿ï¿½
function get_sql_set( $cols )
{
	$ret = "" ;

	foreach( $cols as $col => $types ) {

		list( $field , $lang , $essential ) = explode( ':' , $types ) ;

		// Ì¤ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½''ï¿½È¸ï¿½ï¿½Ê¤ï¿½
		if( ! isset( $_POST[ $col ] ) ) $data = '' ;
		else if( get_magic_quotes_gpc() ) $data = stripslashes( $_POST[ $col ] ) ;
		else $data = $_POST[ $col ] ;

		// É¬ï¿½Ü¥Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½É¤Î¥ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
		if( $essential && $data === '' ) {
			die( sprintf( _APCAL_ERR_LACKINDISPITEM , $col ) ) ;
		}

		// ï¿½ï¿½ï¿½ì¡¦ï¿½ï¿½ï¿½ï¿½Ê¤É¤ï¿½ï¿½Ì¤Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½
		switch( $lang ) {
			case 'N' :	// ï¿½ï¿½ï¿½ï¿½ (ï¿½ï¿½ï¿½ï¿½ï¿½ , ï¿½ï¿½ï¿½ï¿½)
				$data = intval( str_replace( "," , "" , $data ) ) ;
				break ;
			case 'J' :	// ï¿½ï¿½ï¿½Ü¸ï¿½Æ¥ï¿½ï¿½ï¿½ï¿½ï¿½ (È¾ï¿½Ñ¥ï¿½ï¿½Ê¢ï¿½ï¿½ï¿½ï¿½Ñ¤ï¿½ï¿½ï¿½)
				$data = $this->mb_convert_kana( $data , "KV" ) ;
				break ;
			case 'E' :	// È¾ï¿½Ñ±Ñ¿ï¿½ï¿½ï¿½Î¤ï¿½
				$data = $this->mb_convert_kana( $data , "as" ) ;
				break ;
		}

		// ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½É¤Î·ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½
		switch( $field ) {
			case 'A' :	// textarea
				$ret .= "$col='".addslashes($data)."'," ;
				break ;
			case 'I' :	// integer
				$data = intval( $data ) ;
				$ret .= "$col='$data'," ;
				break ;
			case 'F' :	// float
				$data = doubleval( $data ) ;
				$ret .= "$col='$data'," ;
				break ;
			default :	// varchar(ï¿½Ç¥Õ¥ï¿½ï¿½ï¿½ï¿½)ï¿½Ï¿ï¿½ï¿½Í¤Ë¤ï¿½ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
				if( $field < 1 ) $field = 255 ;
				$data = mb_strcut( $data , 0 , $field ) ;
				$ret .= "$col='".addslashes($data)."'," ;
		}
	}

	// ï¿½Ç¸ï¿½ï¿½ , ï¿½ï¿½ï¿½ï¿½
	$ret = substr( $ret , 0 , -1 ) ;

	return $ret ;
}



// unixtimestampï¿½ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ß¤Î¸ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½ï¿½ï¿½ï¿½ì¤¿Ä¹ï¿½ï¿½É½ï¿½ï¿½ï¿½ï¿½ YMDN ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_long_ymdn( $time )
{
	return sprintf(
		_APCAL_FMT_YMDN , // format
		date( 'Y' , $time ) , // Y
		$this->month_long_names[ date( 'n' , $time ) ] , // M
		$this->date_long_names[ date( 'j' , $time ) ] , // D
		$this->week_long_names[ date( 'w' , $time ) ] // N
	) ;
}



// unixtimestampï¿½ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ß¤Î¸ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½ï¿½ï¿½ï¿½ì¤¿É¸ï¿½ï¿½Ä¹É½ï¿½ï¿½ï¿½ï¿½ MD ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_middle_md( $time )
{
	return sprintf(
		_APCAL_FMT_MD , // format
		$this->month_middle_names[ date( 'n' , $time ) ] , // M
		$this->date_short_names[ date( 'j' , $time ) ] // D
	) ;
}



// unixtimestampï¿½ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ß¤Î¸ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½ï¿½ï¿½ï¿½ì¤¿ DHI ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_middle_dhi( $time , $is_over24 = false )
{
	$hour_offset = $is_over24 ? 24 : 0 ;

	$hour4disp = $this->use24 ? $this->hour_names_24[ date( 'G' , $time ) + $hour_offset ] : $this->hour_names_12[ date( 'G' , $time ) + $hour_offset ] ;

	return sprintf(
		_APCAL_FMT_DHI ,
		$this->date_short_names[ date( 'j' , $time ) ] , // D
		$hour4disp , // H
		date( _APCAL_DTFMT_MINUTE , $time ) // I
	) ;
}



// unixtimestampï¿½ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ß¤Î¸ï¿½ï¿½ï¿½ï¿½É½ï¿½ï¿½ï¿½ï¿½ï¿½ì¤¿ HI ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_middle_hi( $time , $is_over24 = false )
{
	$hour_offset = $is_over24 ? 24 : 0 ;

	$hour4disp = $this->use24 ? $this->hour_names_24[ date( 'G' , $time ) + $hour_offset ] : $this->hour_names_12[ date( 'G' , $time ) + $hour_offset ] ;

	return sprintf(
		_APCAL_FMT_HI ,
		$hour4disp , // H
		date( _APCAL_DTFMT_MINUTE , $time ) // I
	) ;
}

// Make <option>s for selecting "HOUR" (default_hour must be 0-23)
function get_options_for_hour($default_hour=0)
{
	$ret = '';
	for($h = 0; $h < 24; $h ++)
    {
		$ret .= $h == $default_hour ? "<option value='$h' selected='selected'>" : "<option value='$h'>";
		$ret .= $this->use24 ? $this->hour_names_24[$h] : $this->hour_names_12[$h];
		$ret .= "</option>\n";
	}

	return $ret;
}

// Make <option>s for selecting "MIN" (default_min must be 0-60 by 5)
function  get_options_for_min($default_min=0)
{
	$ret = '' ;
	for($m=0; $m<60; $m+=5)
    {
        $ret .= $m == $default_min ? "<option value='$m' selected='selected'>" : "<option value='$m'>";
        $ret .= sprintf( "%02d" , $m ).'</option>';
	}
    
	return $ret;
}

// unixtimestampï¿½ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½(timestampï¿½ï¿½ï¿½ï¿½)ï¿½Ê¹ß¤ï¿½Í½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_coming_time_description( $start , $now , $admission = true )
{
	// ï¿½ï¿½Ç§ï¿½ï¿½Í­Ìµï¿½Ë¤ï¿½Ã¤Æ¥É¥Ã¥ï¿½GIFï¿½ï¿½ï¿½Ø¤ï¿½ï¿½ï¿½
	if( $admission ) $dot = "" ;
	else $dot = "<img border='0' src='$this->images_url/dot_notadmit.gif' />" ;

	if( $start >= $now && $start - $now < 86400 ) {
		// 24ï¿½ï¿½ï¿½Ö°ï¿½ï¿½ï¿½Î¥ï¿½ï¿½Ù¥ï¿½ï¿½
		if( ! $dot ) $dot = "<img border='0' src='$this->images_url/dot_today.gif' />" ;
		$ret = "$dot <b>" . $this->get_middle_hi( $start ) . "</b>"._APCAL_MB_TIMESEPARATOR ;
	} else if( $start < $now ) {
		// ï¿½ï¿½ï¿½Ç¤Ë³ï¿½ï¿½Ï¤ï¿½ï¿½ì¤¿ï¿½ï¿½ï¿½Ù¥ï¿½ï¿½
		if( ! $dot ) $dot = "<img border='0' src='$this->images_url/dot_started.gif' />" ;
		$ret = "$dot "._APCAL_MB_CONTINUING ;
	} else {
		// ï¿½ï¿½ï¿½ï¿½Ê¹ß¤Ë³ï¿½ï¿½Ï¤Ë¤Ê¤ë¥¤ï¿½Ù¥ï¿½ï¿½
		if( ! $dot ) $dot = "<img border='0' src='$this->images_url/dot_future.gif' />" ;
//		$ret = "$dot " . date( "n/j H:i" , $start ) . _APCAL_MB_TIMESEPARATOR ;
		$ret = "$dot " . $this->get_middle_md( $start ) . " " . $this->get_middle_hi( $start ) . _APCAL_MB_TIMESEPARATOR ;
	}

	return $ret ;
}



// ï¿½ï¿½ï¿½Ä¤ï¿½unixtimestampï¿½ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½(Y-n-jï¿½ï¿½ï¿½ï¿½)ï¿½ï¿½Í½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê´ï¿½Ë¥ï¿½ï¿½ß¡ï¿½
function get_todays_time_description( $start , $end , $ynj , $justify = true , $admission = true , $is_start_date = null , $is_end_date = null , $border_for_2400 = null )
{
	if( ! isset( $is_start_date ) ) $is_start_date = ( date( "Y-n-j" , $start ) == $ynj ) ;
	if( ! isset( $is_end_date ) ) $is_end_date = ( date( "Y-n-j" , $end ) == $ynj ) ;
	if( ! isset( $border_for_2400 ) ) $this->unixtime - intval( ( $this->user_TZ - $this->server_TZ ) * 3600 ) + 86400 ;

	// $day_start ï¿½ï¿½ï¿½ê¤¬ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¡ï¿½24:00ï¿½Ê¹ß¤Î½ï¿½ï¿½ï¿½
	if( $is_start_date && $start > $border_for_2400 ) {
		$start_desc = $this->get_middle_hi( $start , true ) ;
	} else $start_desc = $this->get_middle_hi( $start ) ;

	if( $is_end_date && $end > $border_for_2400 ) {
		$end_desc = $this->get_middle_hi( $end , true ) ;
	} else $end_desc = $this->get_middle_hi( $end ) ;

	$stuffing = $justify ? '     ' : '' ;

	// Í½ï¿½ï¿½ï¿½ï¿½Ö»ï¿½ï¿½ï¿½ï¿½Í­Ìµï¿½ï¿½ï¿½ï¿½Ç§ï¿½ï¿½Í­Ìµï¿½Ë¤ï¿½Ã¤Æ¥É¥Ã¥ï¿½GIFï¿½ï¿½ï¿½Ø¤ï¿½ï¿½ï¿½
	if( $admission ) {
		if( $is_start_date ) $dot = "<img border='0' src='$this->images_url/dot_startday.gif' />" ;
		else if( $is_end_date ) $dot = "<img border='0' src='$this->images_url/dot_endday.gif' />" ;
		else $dot = "<img border='0' src='$this->images_url/dot_interimday.gif' />" ;
	} else $dot = "<img border='0' src='$this->images_url/dot_notadmit.gif' />" ;

	if( $is_start_date ) {
		if( $is_end_date ) $ret = "$dot {$start_desc}"._APCAL_MB_TIMESEPARATOR."{$end_desc}" ;
		else $ret = "$dot {$start_desc}"._APCAL_MB_TIMESEPARATOR."{$stuffing}" ;
	} else {
		if( $is_end_date ) $ret = "$dot {$stuffing}"._APCAL_MB_TIMESEPARATOR."{$end_desc}" ;
		else $ret = "$dot "._APCAL_MB_CONTINUING ;
	}

	return $ret ;
}


// $eventï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ì¤ï¿½ï¿½é¡¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Í½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ì¾ï¥¤ï¿½Ù¥ï¿½È¤Î¤ß¡ï¿½
function get_time_desc_for_a_day( $event , $tzoffset , $border_for_2400 , $justify = true , $admission = true )
{
	$start = $event->start + $tzoffset ;
	$end = $event->end + $tzoffset ;

	// $day_start ï¿½ï¿½ï¿½ê¤¬ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¡ï¿½24:00ï¿½Ê¹ß¤Î½ï¿½ï¿½ï¿½
	if( $event->is_start_date && $event->start >= $border_for_2400 ) {
		$start_desc = $this->get_middle_hi( $start , true ) ;
	} else $start_desc = $this->get_middle_hi( $start ) ;

	if( $event->is_end_date && $event->end >= $border_for_2400 ) {
		$end_desc = $this->get_middle_hi( $end , true ) ;
	} else $end_desc = $this->get_middle_hi( $end ) ;

	$stuffing = $justify ? '     ' : '' ;

	// Í½ï¿½ï¿½ï¿½ï¿½Ö»ï¿½ï¿½ï¿½ï¿½Í­Ìµï¿½ï¿½ï¿½ï¿½Ç§ï¿½ï¿½Í­Ìµï¿½Ë¤ï¿½Ã¤Æ¥É¥Ã¥ï¿½GIFï¿½ï¿½ï¿½Ø¤ï¿½ï¿½ï¿½
	if( $admission ) {
		if( $event->is_start_date ) $dot = "<img border='0' src='$this->images_url/dot_startday.gif' />" ;
		else if( $event->is_end_date ) $dot = "<img border='0' src='$this->images_url/dot_endday.gif' />" ;
		else $dot = "<img border='0' src='$this->images_url/dot_interimday.gif' />" ;
	} else $dot = "<img border='0' src='$this->images_url/dot_notadmit.gif' />" ;

	if( $event->is_start_date ) {
		if( $event->is_end_date ) $ret = "$dot {$start_desc}"._APCAL_MB_TIMESEPARATOR."{$end_desc}" ;
		else $ret = "$dot {$start_desc}"._APCAL_MB_TIMESEPARATOR."{$stuffing}" ;
	} else {
		if( $event->is_end_date ) $ret = "$dot {$stuffing}"._APCAL_MB_TIMESEPARATOR."{$end_desc}" ;
		else $ret = "$dot "._APCAL_MB_CONTINUING ;
	}

	return $ret ;
}


// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï¥Ü¥Ã¥ï¿½ï¿½ï¿½ï¿½Î´Ø¿ï¿½ (JavaScriptï¿½ï¿½ï¿½ï¿½ï¿½Ï¤ï¿½ï¿½ï¿½Ý¤ï¿½Overrideï¿½Ð¾ï¿½)

function get_formtextdateselect( $name , $value )
{
	return "<input type='text' name='$name' size='12' value='$value' style='ime-mode:disabled' />" ;
}



// $this->images_urlï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½style.cssï¿½ï¿½ï¿½É¤ß¹ï¿½ï¿½ß¡ï¿½ï¿½ï¿½ï¿½Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ°ï¿½ï¿½Ï¤ï¿½
function get_embed_css( )
{
	$css_filename = "$this->images_path/style.css" ;
	if( ! is_readable( $css_filename ) ) return "" ;
	else return strip_tags( join( "" , file( $css_filename ) ) ) ;
}



// ï¿½ï¿½Æ¼Ô¤ï¿½É½ï¿½ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½ (Overrideï¿½Ð¾ï¿½)
function get_submitter_info( $uid )
{
	return '' ;
}



// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ï¿½Ø·ï¿½ï¿½ï¿½WHEREï¿½Ñ¾ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_where_about_categories()
{
	if( $this->isadmin ) {
		if( empty( $this->now_cid ) ) {
			// ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½$cidï¿½ï¿½ï¿½ê¤¬ï¿½Ê¤ï¿½ï¿½ï¿½Ð¾ï¿½ï¿½True
			return "1" ;
		} else {
			// ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½$cidï¿½ï¿½ï¿½ê¤¬ï¿½ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½LIKEï¿½ï¿½ï¿½ï¿½
			return "categories LIKE '%".sprintf("%05d,",$this->now_cid)."%'" ;
		}
	} else {
		if( empty( $this->now_cid ) ) {
			// ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½ï¿½ï¿½ï¿½ï¿½Ô°Ê³ï¿½ï¿½ï¿½$cidï¿½ï¿½ï¿½ê¤¬ï¿½Ê¤ï¿½ï¿½ï¿½Ð¡ï¿½CAT2GROUPï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
			$limit_from_perm = "categories='' OR " ;
			foreach( $this->categories as $cid => $cat ) {
				$limit_from_perm .= "categories LIKE '%".sprintf("%05d,",$cid)."%' OR " ;
			}
			$limit_from_perm = substr( $limit_from_perm , 0 , -3 ) ;
			return $limit_from_perm ;
		} else {
			// ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½ï¿½ï¿½ï¿½ï¿½Ô°Ê³ï¿½ï¿½ï¿½$cidï¿½ï¿½ï¿½ê¤¬ï¿½ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½Â¥ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½$cidï¿½ï¿½ï¿½ï¿½
			if( isset( $this->categories[ $this->now_cid ] ) ) {
				return "categories LIKE '%".sprintf("%05d,",$this->now_cid)."%'" ;
			} else {
				// ï¿½ï¿½ï¿½ê¤µï¿½ì¤¿cidï¿½ï¿½ï¿½ï¿½ï¿½Â¤Ë¤Ê¤ï¿½
				return '0' ;
			}
		}
	}
}



// CLASS(ï¿½ï¿½ï¿½ï¿½ï¿½)ï¿½Ø·ï¿½ï¿½ï¿½WHEREï¿½Ñ¾ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
function get_where_about_class()
{
	if( $this->isadmin ) {
		// ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤Ê¤ï¿½ï¿½ï¿½True
		return "1" ;
	} else if( $this->user_id <= 0 ) {
		// ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½È¤Ê¤ï¿½ï¿½(PUBLIC)ï¿½ì¥³ï¿½ï¿½ï¿½É¤Î¤ï¿½
		return "class='PUBLIC'" ;
	} else {
		// ï¿½Ì¾ï¿½æ¡¼ï¿½ï¿½ï¿½Ê¤é¡¢PUBLICï¿½ì¥³ï¿½ï¿½ï¿½É¤ï¿½ï¿½ï¿½ï¿½æ¡¼ï¿½ï¿½IDï¿½ï¿½ï¿½ï¿½ï¿½×¤ï¿½ï¿½ï¿½ì¥³ï¿½ï¿½ï¿½É¡ï¿½ï¿½Þ¤ï¿½ï¿½Ï¡ï¿½ï¿½ï¿½Â°ï¿½ï¿½ï¿½Æ¤ï¿½ï¿½ë¥°ï¿½ë¡¼ï¿½ï¿½IDï¿½Î¤ï¿½ï¿½ï¿½ï¿½Î°ï¿½Ä¤ï¿½ï¿½ì¥³ï¿½ï¿½ï¿½É¤Î¥ï¿½ï¿½ë¡¼ï¿½ï¿½IDï¿½È°ï¿½ï¿½×¤ï¿½ï¿½ï¿½ì¥³ï¿½ï¿½ï¿½ï¿½
		$ids = ' ' ;
		foreach( $this->groups as $id => $name ) {
			$ids .= "$id," ;
		}
		$ids = substr( $ids , 0 , -1 ) ;
		if( intval( $ids ) == 0 ) $group_section = '' ;
		else $group_section = "OR groupid IN ($ids)" ;
		return "(class='PUBLIC' OR uid=$this->user_id $group_section)" ;
	}
}



// mb_convert_kanaï¿½Î½ï¿½ï¿½ï¿½
function mb_convert_kana( $str , $option )
{
	// convert_kana ï¿½Î½ï¿½ï¿½ï¿½Ï¡ï¿½ï¿½ï¿½ï¿½Ü¸ï¿½Ç¤Î¤ß¹Ô¤ï¿½
	if( $this->language != 'japanese' || ! function_exists( 'mb_convert_kana' ) ) {
		return $str ;
	} else {
		return mb_convert_kana( $str , $option ) ;
	}
}



/*******************************************************************/
/*   ï¿½ï¿½ï¿½Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ï¢ï¿½Î´Ø¿ï¿½ (ï¿½ï¿½ï¿½Ö¥ï¿½ï¿½é¥¹ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Overrideï¿½Ð¾ï¿½)   */
/*******************************************************************/

function textarea_sanitizer_for_show( $data )
{
	return nl2br( htmlspecialchars( $data , ENT_QUOTES ) ) ;
}

function text_sanitizer_for_show( $data )
{
	return htmlspecialchars( $data , ENT_QUOTES ) ;
}

function textarea_sanitizer_for_edit( $data )
{
	return htmlspecialchars( $data , ENT_QUOTES ) ;
}

function text_sanitizer_for_edit( $data )
{
	return htmlspecialchars( $data , ENT_QUOTES ) ;
}

function textarea_sanitizer_for_export_ics( $data )
{
	return $data ;
}


/*******************************************************************/
/*        iCalendar ï¿½ï¿½ï¿½ï¿½Ø¿ï¿½                                       */
/*******************************************************************/

// iCalendarï¿½ï¿½ï¿½ï¿½ï¿½Ç¤Î¥Ð¥Ã¥ï¿½ï¿½ï¿½ï¿½Ï¥×¥ï¿½Ã¥È¥Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ñ¥Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
// $_POST['ids']ï¿½Ç»ï¿½ï¿½ï¿½
function output_ics_confirm( $post_target , $target = '_self' )
{
	// POSTï¿½Ç¼ï¿½ï¿½ï¿½ï¿½ï¿½Ã¤ï¿½idï¿½ï¿½ï¿½ï¿½ï¿½event_idsï¿½ï¿½ï¿½ï¿½È¤ï¿½ï¿½ï¿½POSTï¿½ï¿½ï¿½ï¿½
	$hiddens = "" ;
	foreach( $_POST[ 'ids' ] as $id ) {
		$id = intval( $id ) ;
		$hiddens .= "<input type='hidden' name='event_ids[]' value='$id' />\n" ;
	}
	// webcalï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$webcal_url = str_replace( 'http://' , 'webcal://' , $post_target ) ;
	// ï¿½ï¿½Ç§ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
	return "
	<div style='text-align:center;width:100%;'>&nbsp;<br /><b>"._APCAL_MB_ICALSELECTPLATFORM."</b><br />&nbsp;</div>
	<table border='0' cellpadding='5' cellspacing='2' width='100%'>
	<tr>
	<td align='right' width='50%'>
	<form action='$post_target?output_ics=1' method='post' target='$target'>
		$hiddens
		<input type='submit' name='do_output' value='"._APCAL_BTN_OUTPUTICS_WIN."' />
	</form>
	</td>
	<td align='left' width='50%'>
	<form action='$webcal_url?output_ics=1' method='post' target='$target'>
		$hiddens
		<input type='submit' name='do_output' value='"._APCAL_BTN_OUTPUTICS_MAC."' />
	</form>
	</td>
	</tr>
	</table><br /><br />\n" ;
}


// iCalendarï¿½ï¿½ï¿½ï¿½ï¿½Ç¤Î½ï¿½ï¿½ï¿½ (mbstringÉ¬ï¿½ï¿½)
// ï¿½ï¿½ï¿½Ï¤ï¿½ï¿½ï¿½ï¿½Î¤ß¤Î¾ï¿½ï¿½ï¿½$_GET['event_id']ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½ï¿½$_POST['event_ids']
function output_ics( )
{
	// $event_id ï¿½ï¿½ï¿½ï¿½ï¿½ê¤µï¿½ï¿½Æ¤ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½Ð½ï¿½Î»
	if( empty( $_GET[ 'event_id' ] ) && empty( $_POST[ 'event_ids' ] ) ) die( _APCAL_ERR_INVALID_EVENT_ID ) ;

	// iCalendarï¿½ï¿½ï¿½Ïµï¿½ï¿½Ä¤ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½Ð½ï¿½Î»
	if( ! $this->can_output_ics ) die( _APCAL_ERR_NOPERM_TO_OUTPUTICS ) ;
	if( isset( $_GET[ 'event_id' ] ) ) {
		// $_GET[ 'event_id' ] ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î»ï¿½ï¿½ï¿½Î¾ï¿½ï¿½
		$event_id = intval( $_GET['event_id'] ) ;
		$event_ids = array( $event_id ) ;
		$rs = mysql_query( "SELECT summary AS udtstmp FROM $this->table WHERE id='$event_id'" , $this->conn ) ;
		if( mysql_num_rows( $rs ) < 1 ) die( _APCAL_ERR_INVALID_EVENT_ID ) ;
		$summary = mysql_result( $rs , 0 , 0 ) ;
		// ï¿½ï¿½Ì¾ ï¿½ï¿½ X-WR-CALNAME ï¿½È¤ï¿½ï¿½ï¿½
		$x_wr_calname = $summary ;
		// ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½Ì¾ï¿½Ë»È¤ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¸ï¿½ï¿½Ïºï¿½ï¿½
		if( function_exists( "mb_ereg_replace" ) ) {
			$summary = mb_ereg_replace( '[<>|"?*,:;\\/]' , '' , $summary ) ;
		} else {
			$summary = ereg_replace( '[<>|"?*,:;\\/]' , '' , $summary ) ;
		}
		// ï¿½Ø»ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½Ã¤ï¿½ï¿½ï¿½Ì¾.ics ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½Ì¾ï¿½È¤ï¿½ï¿½ï¿½ (ï¿½ï¿½SJISï¿½Ñ´ï¿½)
		$output_filename = mb_convert_encoding($summary , "ASCII" ).'.ics';
        $output_filename = str_replace(' ', '', $output_filename);
	} else if( is_array( $_POST[ 'event_ids' ] ) ) {
		// $_POST[ 'event_ids' ] ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½
		$event_ids = array_unique( $_POST[ 'event_ids' ] ) ;
		// events-ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½(GMT) ï¿½ï¿½ X-WR-CALNAME ï¿½È¤ï¿½ï¿½ï¿½
		$x_wr_calname = 'events-' . gmdate( 'Ymd\THis\Z' ) ;
		// events-ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½.ics ï¿½ï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½Ì¾ï¿½È¤ï¿½ï¿½ï¿½
		$output_filename = $x_wr_calname . '.ics' ;
	} else die( _APCAL_ERR_INVALID_EVENT_ID ) ;

	// HTTP header
    header("Pragma: public");
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private", false);
    header("Content-Type: text/calendar"); 
    header("Content-Disposition: attachment; filename=\"{$output_filename}\";" ); 

	// iCalendarï¿½Ø¥Ã¥ï¿½
	$ical_header = "BEGIN:VCALENDAR\r
CALSCALE:GREGORIAN\r
X-WR-TIMEZONE;VALUE=TEXT:GMT\r
PRODID:ANTIQUES PROMOTION - APCal -\r";
$ical_header .= $this->ics_new_cal ? "X-WR-CALNAME;VALUE=TEXT:$x_wr_calname\r" : '';
$ical_header .= "VERSION:2.0\r
METHOD:PUBLISH\r\n" ;

	// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_categories = $this->get_where_about_categories() ;

	// CLASSï¿½ï¿½Ï¢ï¿½ï¿½WHEREï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$whr_class = $this->get_where_about_class() ;

	// ï¿½ï¿½ï¿½Ù¥ï¿½ï¿½ï¿½ï¿½Î¥ë¡¼ï¿½ï¿½
	$vevents_str = "" ;
	$timezones = array() ;
	foreach( $event_ids as $event_id ) {

		$event_id = intval( $event_id ) ;
		$sql = "SELECT *,UNIX_TIMESTAMP(dtstamp) AS udtstmp,DATE_ADD(end_date,INTERVAL 1 DAY) AS end_date_offseted FROM $this->table WHERE id='$event_id' AND ($whr_categories) AND ($whr_class)" ;
		if( ! $rs = mysql_query( $sql , $this->conn ) ) echo mysql_error() ;
		$event = mysql_fetch_object( $rs ) ;
		if( ! $event ) continue ;

		if( isset( $event->start_date ) ) {
			// 1970ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½2038Ç¯ï¿½Ê¹ß¤ï¿½ï¿½ï¿½ï¿½Õ¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã¼ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½ï¿½
			$dtstart = str_replace( '-' , '' , $event->start_date ) ;
			if( isset( $event->end_date_offseted ) ) {
				$dtend = str_replace( '-' , '' , $event->end_date_offseted ) ;
			} else {
				$dtend = date( 'Ymd' , $event->end ) ;
			}
			$dtstart_opt = $dtend_opt = ";VALUE=DATE" ;
		} else if( $event->allday ) {
			// ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½È¡Ê»ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½ï¿½ï¿½
			$dtstart = date( 'Ymd' , $event->start ) ;
			if( isset( $event->end_date_offseted ) ) {
				$dtend = str_replace( '-' , '' , $event->end_date_offseted ) ;
			} else {
				$dtend = date( 'Ymd' , $event->end ) ;
			}
			// ï¿½ï¿½ï¿½Ï¤È½ï¿½Î»ï¿½ï¿½Æ±ï¿½ï¿½Î¾ï¿½ï¿½Ï¡ï¿½ï¿½ï¿½Î»ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½é¤¹
			if( $dtstart == $dtend ) $dtend = date( 'Ymd' , $event->end + 86400 ) ;
			$dtstart_opt = $dtend_opt = ";VALUE=DATE" ;
		} else {
			if( $event->rrule ) {
				// ï¿½Ì¾ï¥¤ï¿½Ù¥ï¿½È¤ï¿½RRULEï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½ï¿½TZï¿½Ç½ï¿½ï¿½ï¿½
				$tzoffset = intval( ( $this->server_TZ - $event->event_tz ) * 3600 ) ;
				list( , $tzid ) = $this->get_timezone_desc( $event->event_tz ) ;
				$dtstart = date( 'Ymd\THis' , $event->start - $tzoffset ) ;
				$dtend = date( 'Ymd\THis' , $event->end - $tzoffset ) ;
				$dtstart_opt = $dtend_opt = ";TZID=$tzid" ;
				// ï¿½ï¿½ï¿½ï¿½Ë¡ï¿½ï¿½ï¿½ï¿½ï¿½VTIMEZONEï¿½ï¿½ï¿½ï¿½ï¿½
				$timezones[$event->event_tz] = 1 ;
			} else {
				// ï¿½Ì¾ï¥¤ï¿½Ù¥ï¿½È¤ï¿½RRULEï¿½ï¿½Ìµï¿½ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ð¤Î»ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½GMTÉ½ï¿½ï¿½
				$tzoffset = $this->server_TZ * 3600 ;
				$dtstart = date( 'Ymd\THis\Z' , $event->start - $tzoffset ) ;
				$dtend = date( 'Ymd\THis\Z' , $event->end - $tzoffset ) ;
				$dtstart_opt = $dtend_opt = "" ;
			}
		}

		// DTSTAMPï¿½Ï¾ï¿½ï¿½GMT
		$dtstamp = date( 'Ymd\THis\Z' , $event->udtstmp - $this->server_TZ * 3600 ) ;

		// DESCRIPTIONï¿½ï¿½ folding , \rï¿½ï¿½ï¿½ ï¿½ï¿½ï¿½ï¿½ï¿½ \n -> \\n ï¿½Ñ´ï¿½, ï¿½ï¿½ï¿½Ë¥ï¿½ï¿½ï¿½ï¿½ï¿½
		// (folding Ì¤ï¿½ï¿½ï¿½ï¿½) TODO
		$description = str_replace( "\r" , '' , $event->description ) ;
		$description = str_replace( "\n" , '\n' , $description ) ;
		$description = $this->textarea_sanitizer_for_export_ics( $description ) ;

		// ï¿½ï¿½ï¿½Æ¥ï¿½ï¿½ê¡¼ï¿½ï¿½É½ï¿½ï¿½
		$categories = '' ;
		$cids = explode( "," , $event->categories ) ;
		foreach( $cids as $cid ) {
			$cid = intval( $cid ) ;
			if( isset( $this->categories[ $cid ] ) ) $categories .= $this->categories[ $cid ]->cat_title . "," ;
		}
		if( $categories != '' ) $categories = substr( $categories , 0 , -1 ) ;

		// RRULEï¿½Ô¤Ï¡ï¿½RRULEï¿½ï¿½ï¿½ï¿½È¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
		$rrule_line = $event->rrule ? "RRULE:{$event->rrule}\r\n" : "" ;

		// ï¿½ï¿½ï¿½Ù¥ï¿½È¥Ç¡ï¿½ï¿½ï¿½ï¿½Î½ï¿½ï¿½ï¿½
		$vevents_str .= "BEGIN:VEVENT\r
DTSTART{$dtstart_opt}:{$dtstart}\r
DTEND{$dtend_opt}:{$dtend}\r
LOCATION:{$event->location}\r
TRANSP:OPAQUE\r
SEQUENCE:{$event->sequence}\r
UID:{$event->unique_id}\r
DTSTAMP:{$dtstamp}\r
CATEGORIES:{$categories}\r
DESCRIPTION:{$description}\r
SUMMARY:{$event->summary}\r
{$rrule_line}PRIORITY:{$event->priority}\r
CLASS:{$event->class}\r
END:VEVENT\r\n" ;

	}

	// VTIMEZONE
	$vtimezones_str = $this->get_vtimezones_str( $timezones ) ;

	// iCalendarï¿½Õ¥Ã¥ï¿½
	$ical_footer = "END:VCALENDAR\r\n" ;

	$ical_data = "$ical_header$vtimezones_str$vevents_str$ical_footer" ;

	// mbstring ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¤ß¡ï¿½UTF-8 ï¿½Ø¤ï¿½ï¿½Ñ´ï¿½
	if( extension_loaded( 'mbstring' ) ) {
		mb_http_output( "pass" ) ;
		$ical_data = mb_convert_encoding( $ical_data , "UTF-8" ) ;
	}

	echo $ical_data ;

	exit ;
}



function import_ics_via_fopen( $uri , $force_http = true , $user_uri = '' )
{
	if( strlen( $uri ) < 5 ) return "-1:" ;
	$user_uri = empty( $user_uri ) ? '' : $uri ;
	// webcal://* ï¿½ï¿½ connectionÌ¤ï¿½ï¿½ï¿½ï¿½â¡¢ï¿½ï¿½ï¿½Ù¤ï¿½ http://* ï¿½ï¿½ï¿½ï¿½ï¿½
	$uri = str_replace( "webcal://" , "http://" , $uri ) ;

	if( $force_http ) {
		if( substr( $uri , 0 , 7 ) != 'http://' ) $uri = "http://" . $uri ;
	}

	// iCal parser ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½
	include_once "$this->base_path/class/iCal_parser.php" ;
	$ical = new iCal_parser() ;
	$ical->language = $this->language ;
	$ical->timezone = ( $this->server_TZ >= 0 ? "+" : "-" ) . sprintf( "%02d%02d" , abs( $this->server_TZ ) , abs( $this->server_TZ ) * 60 % 60 ) ;
	list( $ret_code , $message , $filename ) = explode( ":" , $ical->parse( $uri , $user_uri ) , 3 ) ;
	if( $ret_code != 0 ) {
		// ï¿½Ñ¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤Ê¤ï¿½-1ï¿½È¥ï¿½ï¿½é¡¼ï¿½ï¿½Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
		return "-1: $message : $filename" ;
	}
	$setsqls = $ical->output_setsqls() ;

	$count = 0 ;
	foreach( $setsqls as $setsql ) {
		$sql = "INSERT INTO $this->table SET $setsql,admission=1,uid=$this->user_id,poster_tz='$this->user_TZ',server_tz='$this->server_TZ'" ;

		if( ! mysql_query( $sql , $this->conn ) ) die( mysql_error() ) ;
		$this->update_record_after_import( mysql_insert_id( $this->conn ) ) ;

		$count ++ ;
	}

	return "$count: $message:" ;
}



function import_ics_via_upload( $userfile )
{
	// icsï¿½Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ò¥¯¥é¥¤ï¿½ï¿½ï¿½ï¿½È¥Þ¥ï¿½ï¿½ó¤«¤é¥¢ï¿½Ã¥×¥?ï¿½É¤ï¿½ï¿½ï¿½ï¿½É¹ï¿½ï¿½ï¿½
	include_once "$this->base_path/class/iCal_parser.php" ;
	$ical = new iCal_parser() ;
	$ical->language = $this->language ;
	$ical->timezone = ( $this->server_TZ >= 0 ? "+" : "-" ) . sprintf( "%02d%02d" , abs( $this->server_TZ ) , abs( $this->server_TZ ) * 60 % 60 ) ;
	list( $ret_code , $message , $filename ) = explode( ":" , $ical->parse( $_FILES[ $userfile ][ 'tmp_name' ] , $_FILES[ $userfile ][ 'name' ] ) , 3 ) ;
	if( $ret_code != 0 ) {
		// ï¿½Ñ¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ô¤Ê¤ï¿½-1ï¿½È¥ï¿½ï¿½é¡¼ï¿½ï¿½Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
		return "-1: $message : $filename" ;
	}
	$setsqls = $ical->output_setsqls() ;

	$count = 0 ;
	foreach( $setsqls as $setsql ) {
		$sql = "INSERT INTO $this->table SET $setsql,admission=1,uid=$this->user_id,poster_tz='$this->user_TZ',server_tz='$this->server_TZ'" ;

		if( ! mysql_query( $sql , $this->conn ) ) die( mysql_error() ) ;
		$this->update_record_after_import( mysql_insert_id( $this->conn ) ) ;

		$count ++ ;
	}

	return "$count: $message :" ;
}



// ï¿½ï¿½ï¿½ì¥³ï¿½ï¿½ï¿½É¤ï¿½ï¿½É¤ß¹ï¿½ï¿½ß¸ï¿½Ë¹Ô¤ï¿½ï¿½ï¿½ï¿½ï¿½ ï¿½ï¿½rruleï¿½ï¿½Å¸ï¿½ï¿½ï¿½ï¿½categoriesï¿½ï¿½cidï¿½ï¿½ï¿½Ê¤É¡ï¿½
function update_record_after_import( $event_id )
{
	$rs = mysql_query( "SELECT categories,rrule FROM $this->table WHERE id='$event_id'" , $this->conn ) ;
	$event = mysql_fetch_object( $rs ) ;

	// categories ï¿½ï¿½ cidï¿½ï¿½ ( '\,' -> ',' ï¿½ï¿½Outlookï¿½Ðºï¿½)
	$event->categories = str_replace( '\,' , ',' , $event->categories ) ;
	$cat_names = explode( ',' , $event->categories ) ;
	for( $i = 0 ; $i < sizeof( $cat_names ) ; $i ++ ) {
		$cat_names[ $i ] = trim( $cat_names[ $i ] ) ;
	}
	$categories = '' ;
	foreach( $this->categories as $cid => $cat ) {
		if( in_array( $cat->cat_title , $cat_names ) ) {
			$categories .= sprintf( "%05d," , $cid ) ;
		}
	}

	// rrule_pid ï¿½Î½ï¿½ï¿½ï¿½
	$rrule_pid = $event->rrule ? $event_id : 0 ;

	// ï¿½ì¥³ï¿½ï¿½ï¿½É¹ï¿½ï¿½ï¿½
	mysql_query( "UPDATE $this->table SET categories='$categories',rrule_pid='$rrule_pid' WHERE id='$event_id'" , $this->conn ) ;

	// RRULEï¿½ï¿½ï¿½é¡¢ï¿½Ò¥ì¥³ï¿½ï¿½ï¿½É¤ï¿½Å¸ï¿½ï¿½
	if( $event->rrule != '' ) {
		$this->rrule_extract( $event_id ) ;
	}

	// GIJ TODO category ï¿½Î¼ï¿½Æ°ï¿½ï¿½Ï¿ class,groupid ï¿½Î½ï¿½ï¿½ï¿½
}


/*******************************************************************/
/*        RRULE ï¿½ï¿½ï¿½ï¿½Ø¿ï¿½                                           */
/*******************************************************************/

// rruleï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ë¥¯ï¿½é¥¹ï¿½Ø¿ï¿½
function rrule_to_human_language( $rrule )
{
	$rrule = trim( $rrule ) ;
	if( $rrule == '' ) return '' ;

	// rrule ï¿½Î³ï¿½ï¿½ï¿½ï¿½Ç¤ï¿½ï¿½Ñ¿ï¿½ï¿½ï¿½Å¸ï¿½ï¿½
	$rrule = strtoupper( $rrule ) ;
	$rules = split( ';' , $rrule ) ;
	foreach( $rules as $rule ) {
		list( $key , $val ) = explode( '=' , $rule , 2 ) ;
		$key = trim( $key ) ;
		$$key = trim( $val ) ;
	}

	if( empty( $FREQ ) ) $FREQ = 'DAILY' ;
	if( empty( $INTERVAL ) || $INTERVAL <= 0 ) $INTERVAL = 1 ;

	// ï¿½ï¿½ï¿½Ù¾ï¿½ï¿½ï¿½ï¿½ï¿½
	$ret_freq = '' ;
	$ret_day = '' ;
	switch( $FREQ ) {
		case 'DAILY' :
			if( $INTERVAL == 1 ) $ret_freq = _APCAL_RR_EVERYDAY ;
			else $ret_freq = sprintf( _APCAL_RR_PERDAY , $INTERVAL ) ;
			break ;
		case 'WEEKLY' :
			if( empty( $BYDAY ) ) break ;	// BYDAY É¬ï¿½ï¿½
			$ret_day = strtr( $BYDAY , $this->byday2langday_w ) ;
			if( $INTERVAL == 1 ) $ret_freq = _APCAL_RR_EVERYWEEK ;
			else $ret_freq = sprintf( _APCAL_RR_PERWEEK , $INTERVAL ) ;
			break ;
		case 'MONTHLY' :
			if( isset( $BYMONTHDAY ) ) {
				$ret_day = "" ;
				$monthdays = explode( ',' , $BYMONTHDAY ) ;
				foreach( $monthdays as $monthday ) {
					$ret_day .= $this->date_long_names[ $monthday ] . "," ;
				}
				$ret_day = substr( $ret_day , 0 , -1 ) ;
			} else if( isset( $BYDAY ) ) {
				$ret_day = strtr( $BYDAY , $this->byday2langday_m ) ;
			} else {
				break ;		// BYDAY ï¿½Þ¤ï¿½ï¿½ï¿½ BYMONTHDAY É¬ï¿½ï¿½
			}
			if( $INTERVAL == 1 ) $ret_freq = _APCAL_RR_EVERYMONTH ;
			else $ret_freq = sprintf( _APCAL_RR_PERMONTH , $INTERVAL ) ;
			break ;
		case 'YEARLY' :
			$ret_day = "" ;
			if( ! empty( $BYMONTH ) ) {
				$months = explode( ',' , $BYMONTH ) ;
				foreach( $months as $month ) {
					$ret_day .= $this->month_long_names[ $month ] . "," ;
				}
				$ret_day = substr( $ret_day , 0 , -1 ) ;
			}
			if( isset( $BYDAY ) ) {
				$ret_day .= ' ' . strtr( $BYDAY , $this->byday2langday_m ) ;
			}
			if( $INTERVAL == 1 ) $ret_freq = _APCAL_RR_EVERYYEAR ;
			else $ret_freq = sprintf( _APCAL_RR_PERYEAR , $INTERVAL ) ;
			break ;
	}

	// ï¿½ï¿½Î»ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$ret_terminator = '' ;
	// UNTIL ï¿½ï¿½ COUNT ï¿½ï¿½Î¾ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ COUNT Í¥ï¿½ï¿½
	if( isset( $COUNT ) && $COUNT > 0 ) {
		$ret_terminator = sprintf( _APCAL_RR_COUNT , $COUNT ) ;
	} else if( isset( $UNTIL ) ) {
		// UNTIL ï¿½Ï¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ç¤ï¿½ï¿½ï¿½ï¿½Ìµï¿½ï¿½ï¿½Ç¸ï¿½ï¿½Ê¤ï¿½
		$year = substr( $UNTIL , 0 , 4 ) ;
		$month = substr( $UNTIL , 4 , 2 ) ;
		$date = substr( $UNTIL , 6 , 2 ) ;
		$ret_terminator = sprintf( _APCAL_RR_UNTIL , "$year-$month-$date" ) ;
	}

	return "$ret_freq $ret_day $ret_terminator" ;
}



// rruleï¿½ï¿½ï¿½Ô½ï¿½ï¿½Ñ¥Õ¥ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Å¸ï¿½ï¿½ï¿½ï¿½ï¿½ë¥¯ï¿½é¥¹ï¿½Ø¿ï¿½
function rrule_to_form( $rrule , $until_init )
{
	// ï¿½Æ½ï¿½ï¿½ï¿½Í¤ï¿½ï¿½ï¿½ï¿½ï¿½
	$norrule_checked = '' ;
	$daily_checked = '' ;
	$weekly_checked = '' ;
	$monthly_checked = '' ;
	$yearly_checked = '' ;
	$norrule_checked = '' ;
	$noterm_checked = '' ;
	$count_checked = '' ;
	$until_checked = '' ;
	$daily_interval_init = 1 ;
	$weekly_interval_init = 1 ;
	$monthly_interval_init = 1 ;
	$yearly_interval_init = 1 ;
	$count_init = 1 ;
	$wdays_checked = array( 'SU'=>'' , 'MO'=>'' , 'TU'=>'' , 'WE'=>'' , 'TH'=>'' , 'FR'=>'' , 'SA'=>'' ) ;
	$byday_m_init = '' ;
	$bymonthday_init = '' ;
	$bymonths_checked = array( 1=>'' , '' , '' , '' , '' , '' , '' , '' , '' , '' , '' , '' ) ;

	if( trim( $rrule ) == '' ) {
		$norrule_checked = "checked='checked'" ;
	} else {

		// rrule ï¿½Î³ï¿½ï¿½ï¿½ï¿½Ç¤ï¿½ï¿½Ñ¿ï¿½ï¿½ï¿½Å¸ï¿½ï¿½
		$rrule = strtoupper( $rrule ) ;
		$rules = split( ';' , $rrule ) ;
		foreach( $rules as $rule ) {
			list( $key , $val ) = explode( '=' , $rule , 2 ) ;
			$key = trim( $key ) ;
			$$key = trim( $val ) ;
		}

		if( empty( $FREQ ) ) $FREQ = 'DAILY' ;
		if( empty( $INTERVAL ) || $INTERVAL <= 0 ) $INTERVAL = 1 ;

		// ï¿½ï¿½ï¿½Ù¾ï¿½ï¿½ï¿½ï¿½ï¿½
		switch( $FREQ ) {
			case 'DAILY' :
				$daily_interval_init = $INTERVAL ;
				$daily_checked = "checked='checked'" ;
				break ;
			case 'WEEKLY' :
				if( empty( $BYDAY ) ) break ;	// BYDAY É¬ï¿½ï¿½
				$weekly_interval_init = $INTERVAL ;
				$weekly_checked = "checked='checked'" ;
				$wdays = explode( ',' , $BYDAY , 7 ) ;
				foreach( $wdays as $wday ) {
					if( isset( $wdays_checked[ $wday ] ) ) $wdays_checked[ $wday ] = "checked='checked'" ;
				}
				break ;
			case 'MONTHLY' :
				if( isset( $BYDAY ) ) {
					$byday_m_init = $BYDAY ;
				} else if( isset( $BYMONTHDAY ) ) {
					$bymonthday_init = $BYMONTHDAY ;
				} else {
					break ;	// BYDAY ï¿½Þ¤ï¿½ï¿½ï¿½ BYMONTHDAY É¬ï¿½ï¿½
				}
				$monthly_interval_init = $INTERVAL ;
				$monthly_checked = "checked='checked'" ;
				break ;
			case 'YEARLY' :
				if( empty( $BYMONTH ) ) $BYMONTH = '' ;
				if( isset( $BYDAY ) ) $byday_m_init = $BYDAY ;
				$yearly_interval_init = $INTERVAL ;
				$yearly_checked = "checked='checked'" ;
				$months = explode( ',' , $BYMONTH , 12 ) ;
				foreach( $months as $month ) {
					$month = intval( $month ) ;
					if( $month > 0 && $month <= 12 ) $bymonths_checked[ $month ] = "checked='checked'" ;
				}
				break ;
		}

		// ï¿½ï¿½Î»ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
		// UNTIL ï¿½ï¿½ COUNT ï¿½ï¿½Î¾ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ COUNT Í¥ï¿½ï¿½
		if( isset( $COUNT ) && $COUNT > 0 ) {
			$count_init = $COUNT ;
			$count_checked = "checked='checked'" ;
		} else if( isset( $UNTIL ) ) {
			// UNTIL ï¿½Ï¡ï¿½ï¿½ï¿½ï¿½ï¿½Ç¡ï¿½ï¿½ï¿½ï¿½Ç¤ï¿½ï¿½ï¿½ï¿½Ìµï¿½ï¿½ï¿½Ç¸ï¿½ï¿½Ê¤ï¿½
			$year = substr( $UNTIL , 0 , 4 ) ;
			$month = substr( $UNTIL , 4 , 2 ) ;
			$date = substr( $UNTIL , 6 , 2 ) ;
			$until_init = "$year-$month-$date" ;
			$until_checked = "checked='checked'" ;
		} else {
			// Î¾ï¿½Ô¤È¤ï¿½ï¿½ï¿½ê¤¬ï¿½Ê¤ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½Î»ï¿½ï¿½ï¿½Ê¤ï¿½
			$noterm_checked = "checked='checked'" ;
		}

	}

	// UNTIL ï¿½ï¿½ï¿½ï¿½ê¤¹ï¿½ë¤¿ï¿½ï¿½Î¥Ü¥Ã¥ï¿½ï¿½ï¿½
	$textbox_until = $this->get_formtextdateselect( 'rrule_until' , $until_init ) ;

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½ï¿½Ü¥Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½Å¸ï¿½ï¿½
	$wdays_checkbox = '' ;
	foreach( $this->byday2langday_w as $key => $val ) {
		$wdays_checkbox .= "<input type='checkbox' name='rrule_weekly_bydays[]' value='$key' {$wdays_checked[$key]} />$val &nbsp; \n" ;
	}

	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½ï¿½Ü¥Ã¥ï¿½ï¿½ï¿½ï¿½ï¿½Å¸ï¿½ï¿½
	$bymonth_checkbox = "<table border='0' cellpadding='2'><tr>\n" ;
	foreach( $bymonths_checked as $key => $val ) {
		$bymonth_checkbox .= "<td><input type='checkbox' name='rrule_bymonths[]' value='$key' $val />{$this->month_short_names[$key]}</td>\n" ;
		if( $key == 6 ) $bymonth_checkbox .= "</tr>\n<tr>\n" ;
	}
	$bymonth_checkbox .= "</tr></table>\n" ;

	// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½OPTIONï¿½ï¿½Å¸ï¿½ï¿½
	$byday_m_options = '' ;
	foreach( $this->byday2langday_m as $key => $val ) {
		if( $byday_m_init == $key ) {
			$byday_m_options .= "<option value='$key' selected='selected'>$val</option>\n" ;
		} else {
			$byday_m_options .= "<option value='$key'>$val</option>\n" ;
		}
	}

	return "
			<input type='radio' name='rrule_freq' value='none' $norrule_checked />"._APCAL_RR_R_NORRULE."<br />
			<br />
			<fieldset>
				<legend class='blockTitle'>"._APCAL_RR_R_YESRRULE."</legend>
				<fieldset>
					<legend class='blockTitle'><input type='radio' name='rrule_freq' value='daily' $daily_checked />"._APCAL_RR_FREQDAILY."</legend>
					"._APCAL_RR_FREQDAILY_PRE." <input type='text' size='2' name='rrule_daily_interval' value='$daily_interval_init' /> "._APCAL_RR_FREQDAILY_SUF."
				</fieldset>
				<br />
				<fieldset>
					<legend class='blockTitle'><input type='radio' name='rrule_freq' value='weekly' $weekly_checked />"._APCAL_RR_FREQWEEKLY."</legend>
					"._APCAL_RR_FREQWEEKLY_PRE."<input type='text' size='2' name='rrule_weekly_interval' value='$weekly_interval_init' /> "._APCAL_RR_FREQWEEKLY_SUF." <br />
					$wdays_checkbox
				</fieldset>
				<br />
				<fieldset>
					<legend class='blockTitle'><input type='radio' name='rrule_freq' value='monthly' $monthly_checked />"._APCAL_RR_FREQMONTHLY."</legend>
					"._APCAL_RR_FREQMONTHLY_PRE."<input type='text' size='2' name='rrule_monthly_interval' value='$monthly_interval_init' /> "._APCAL_RR_FREQMONTHLY_SUF." &nbsp; 
					<select name='rrule_monthly_byday'>
						<option value=''>"._APCAL_RR_S_NOTSELECTED."</option>
						$byday_m_options
					</select> &nbsp; "._APCAL_RR_OR." &nbsp; 
					<input type='text' size='10' name='rrule_bymonthday' value='$bymonthday_init' />"._APCAL_NTC_MONTHLYBYMONTHDAY."
				</fieldset>
				<br />
				<fieldset>
					<legend class='blockTitle'><input type='radio' name='rrule_freq' value='yearly' $yearly_checked />"._APCAL_RR_FREQYEARLY."</legend>
					"._APCAL_RR_FREQYEARLY_PRE."<input type='text' size='2' name='rrule_yearly_interval' value='$yearly_interval_init' /> "._APCAL_RR_FREQYEARLY_SUF." <br />
					$bymonth_checkbox <br />
					<select name='rrule_yearly_byday'>
						<option value=''>"._APCAL_RR_S_SAMEASBDATE."</option>
						$byday_m_options
					</select>
				</fieldset>
				<br />
				<input type='radio' name='rrule_terminator' value='noterm' $noterm_checked onClick='document.MainForm.rrule_until.disabled=true;document.MainForm.rrule_count.disabled=true;' />"._APCAL_RR_R_NOCOUNTUNTIL." &nbsp; ".sprintf( _APCAL_NTC_EXTRACTLIMIT , $this->max_rrule_extract )."  <br />
				<input type='radio' name='rrule_terminator' value='count' $count_checked onClick='document.MainForm.rrule_until.disabled=true;document.MainForm.rrule_count.disabled=false;' />"._APCAL_RR_R_USECOUNT_PRE." <input type='text' size='3' name='rrule_count' value='$count_init' /> "._APCAL_RR_R_USECOUNT_SUF."<br />
				<input type='radio' name='rrule_terminator' value='until' $until_checked onClick='document.MainForm.rrule_until.disabled=false;document.MainForm.rrule_count.disabled=true;' />"._APCAL_RR_R_USEUNTIL." $textbox_until
			</fieldset>
  \n" ;
}



// POSTï¿½ï¿½ï¿½ì¤¿rruleï¿½ï¿½Ï¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Í¤ï¿½RRULEÊ¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½È¤ß¾å¤²ï¿½ë¥¯ï¿½é¥¹ï¿½Ø¿ï¿½
function rrule_from_post( $start , $allday_flag )
{
	// ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½Ìµï¿½ï¿½ï¿½Ê¤é¡¢Ìµï¿½ï¿½ï¿½Ç¶ï¿½Ê¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½
	if( $_POST['rrule_freq'] == 'none' ) return '' ;

	// ï¿½ï¿½ï¿½Ù¾ï¿½ï¿½
	switch( strtoupper( $_POST['rrule_freq'] ) ) {
		case 'DAILY' :
			$ret_freq = "FREQ=DAILY;INTERVAL=" . abs( intval( $_POST['rrule_daily_interval'] ) ) ;
			break ;
		case 'WEEKLY' :
			$ret_freq = "FREQ=WEEKLY;INTERVAL=" . abs( intval( $_POST['rrule_weekly_interval'] ) ) ;
			if( empty( $_POST['rrule_weekly_bydays'] ) ) {
				// ï¿½ï¿½ï¿½ï¿½Î»ï¿½ï¿½ê¤¬ï¿½ï¿½Ä¤ï¿½Ê¤ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ±ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½
				$bydays = array_keys( $this->byday2langday_w ) ;
				$byday = $bydays[ date( 'w' , $start ) ] ;
			} else {
				$byday = '' ;
				foreach( $_POST['rrule_weekly_bydays'] as $wday ) {
					if( preg_match( '/[^\w]+/' , $wday ) ) die( "Some injection was tried" ) ;
					$byday .= substr( $wday , 0 , 2 ) . ',' ;
				}
				$byday = substr( $byday , 0 , -1 ) ;
			}
			$ret_freq .= ";BYDAY=$byday" ;
			break ;
		case 'MONTHLY' :
			$ret_freq = "FREQ=MONTHLY;INTERVAL=" . abs( intval( $_POST['rrule_monthly_interval'] ) ) ;
			if( $_POST['rrule_monthly_byday'] != '' ) {
				// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½
				$byday = substr( trim( $_POST['rrule_monthly_byday'] ) , 0 , 4 ) ;				if( preg_match( '/[^\w-]+/' , $byday ) ) die( "Some injection was tried" ) ;
				$ret_freq .= ";BYDAY=$byday" ;
			} else if( $_POST['rrule_bymonthday'] != '' ) {
				// ï¿½ï¿½ï¿½Õ¤Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½
				$bymonthday = preg_replace( '/[^0-9,]+/' , '' , $_POST['rrule_bymonthday'] ) ;
				$ret_freq .= ";BYMONTHDAY=$bymonthday" ;
			} else {
				// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Õ¤Î»ï¿½ï¿½ê¤¬ï¿½Ê¤ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ±ï¿½ï¿½ï¿½ï¿½ï¿½Õ¤È¤ï¿½ï¿½ï¿½
				$ret_freq .= ";BYMONTHDAY=" . date( 'j' , $start ) ;
			}
			break ;
		case 'YEARLY' :
			$ret_freq = "FREQ=YEARLY;INTERVAL=" . abs( intval( $_POST['rrule_yearly_interval'] ) ) ;
			if( empty( $_POST['rrule_bymonths'] ) ) {
				// ï¿½ï¿½Î»ï¿½ï¿½ê¤¬ï¿½ï¿½Ä¤ï¿½Ê¤ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ±ï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½
				$bymonth = date( 'n' , $start ) ;
			} else {
				$bymonth = '' ;
				foreach( $_POST['rrule_bymonths'] as $month ) {
					$bymonth .= intval( $month ) . ',' ;
				}
				$bymonth = substr( $bymonth , 0 , -1 ) ;
			}
			if( $_POST['rrule_yearly_byday'] != '' ) {
				// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½ï¿½
				$byday = substr( trim( $_POST['rrule_yearly_byday'] ) , 0 , 4 ) ;
				if( preg_match( '/[^\w-]+/' , $byday ) ) die( "Some injection was tried" ) ;
				$ret_freq .= ";BYDAY=$byday" ;
			}
			$ret_freq .= ";BYMONTH=$bymonth" ;
			break ;
		default :
			return '' ;
	}

	// ï¿½ï¿½Î»ï¿½ï¿½ï¿½
	if( empty( $_POST['rrule_terminator'] ) ) $_POST['rrule_terminator'] = '' ;
	switch( strtoupper( $_POST['rrule_terminator'] ) ) {
		case 'COUNT' :
			$ret_term = ';COUNT=' . abs( intval( $_POST['rrule_count'] ) ) ;
			break ;
		case 'UNTIL' :
			// UNTILï¿½ï¿½Unixtimeï¿½ï¿½
			list( $until , $until_date , $use_default ) = $this->parse_posted_date( $this->mb_convert_kana( $_POST[ 'rrule_until' ] , "a" ) , $this->unixtime ) ;
			// 1970ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½2038Ç¯ï¿½Ê¹ß¤Ê¤é¡¢UNTILÌµï¿½ï¿½
			if( $until_date ) {
				$ret_term = '' ;
			} else {
				if( ! $allday_flag ) {
					// ï¿½ï¿½ï¿½ï¿½Ù¥ï¿½È¤Ç¤Ê¤ï¿½ï¿½ï¿½ï¿½Æ±ï¿½ï¿½ï¿½23:59:59ï¿½ï¿½Î»ï¿½ï¿½ï¿½ï¿½È¸ï¿½ï¿½Ê¤ï¿½ï¿½Æ¡ï¿½ UTC ï¿½Ø»ï¿½ï¿½ï¿½ï¿½×»ï¿½ï¿½ï¿½ï¿½ï¿½
					$event_tz = isset( $_POST['event_tz'] ) ? $_POST['event_tz'] : $this->user_TZ ;
					$until = $until - intval( $event_tz * 3600 ) + 86400 - 1 ;
				}
				$ret_term = ';UNTIL=' . date( 'Ymd\THis\Z' , $until ) ;
			}
			break ;
		case 'NOTERM' :
		default :
			$ret_term = '' ;
			break ;
	}

	// WKSTï¿½Ï¡ï¿½ï¿½ï¿½Æ°ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$ret_wkst = $this->week_start ? ';WKST=MO' : ';WKST=SU' ;

	return $ret_freq . $ret_term . $ret_wkst ;
}


// ï¿½Ï¤ï¿½ï¿½ì¤¿event_idï¿½ï¿½ï¿½ï¿½(ï¿½ï¿½)ï¿½È¤ï¿½ï¿½Æ¡ï¿½RRULEï¿½ï¿½Å¸ï¿½ï¿½ï¿½ï¿½ï¿½Æ¥Ç¡ï¿½ï¿½ï¿½ï¿½Ù¡ï¿½ï¿½ï¿½ï¿½ï¿½È¿ï¿½ï¿½
function rrule_extract( $event_id )
{
	$yrs = mysql_query( "SELECT *,TO_DAYS(end_date)-TO_DAYS(start_date) AS date_diff FROM $this->table WHERE id='$event_id'" , $this->conn ) ;
	if( mysql_num_rows( $yrs ) < 1 ) return ;
	$event = mysql_fetch_object( $yrs ) ;

	if( $event->rrule == '' ) return ;

	// rrule ï¿½Î³ï¿½ï¿½ï¿½ï¿½Ç¤ï¿½ï¿½Ñ¿ï¿½ï¿½ï¿½Å¸ï¿½ï¿½
	$rrule = strtoupper( $event->rrule ) ;
	$rules = split( ';' , $rrule ) ;
	foreach( $rules as $rule ) {
		list( $key , $val ) = explode( '=' , $rule , 2 ) ;
		$key = trim( $key ) ;
		$$key = trim( $val ) ;
	}

	// ï¿½ï¿½ï¿½ï¿½ï¿½Ë¤ï¿½Ã¤Æ¡ï¿½RRULEï¿½ï¿½ï¿½ï¿½ï¿½Õ»ï¿½ï¿½ê¤¬ï¿½É¤ï¿½ï¿½Ö¤ï¿½ï¿½ï¿½ï¿½ï¿½ë¤«ï¿½Î·×»ï¿½ 
	if( $event->allday ) {
		$tzoffset_date = 0 ;
	} else {
		// ï¿½ï¿½ï¿½Ù¥ï¿½È¼ï¿½ï¿½È¤ï¿½TZï¿½ï¿½Å¸ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
		$tzoffset_s2e = intval( ( $event->event_tz - $this->server_TZ ) * 3600 ) ;
		$tzoffset_date = date( 'z' , $event->start + $tzoffset_s2e ) - date( 'z' , $event->start ) ;
		if( $tzoffset_date > 1 ) $tzoffset_date = -1 ;
		else if( $tzoffset_date < -1 ) $tzoffset_date = 1 ;
	}

	if( empty( $FREQ ) ) $FREQ = 'DAILY' ;
	if( empty( $INTERVAL ) || $INTERVAL <= 0 ) $INTERVAL = 1 ;

	// ï¿½Ù¡ï¿½ï¿½ï¿½ï¿½È¤Ê¤ï¿½SQLÊ¸
	$base_sql = "INSERT INTO $this->table SET uid='$event->uid',groupid='$event->groupid',shortsummary='".$this->makeShort(utf8_decode(addslashes($event->summary)))."',summary='".addslashes($event->summary)."',location='".addslashes($event->location)."',gmlat='{$event->gmlat}',gmlong='{$event->gmlong}',gmzoom='{$event->gmzoom}',organizer='".addslashes($event->organizer)."',sequence='$event->sequence',contact='".addslashes($event->contact)."',email='".addslashes($event->email)."',url='".addslashes($event->url)."',tzid='$event->tzid',description='".addslashes($event->description)."',dtstamp='$event->dtstamp',mainCategory='{$event->mainCategory}',categories='".addslashes($event->categories)."',transp='$event->transp',priority='$event->priority',admission='$event->admission',class='$event->class',rrule='".addslashes($event->rrule)."',unique_id='$event->unique_id',allday='$event->allday',start_date=null,end_date=null,cid='$event->cid',event_tz='$event->event_tz',server_tz='$event->server_tz',poster_tz='$event->poster_tz',extkey0='$event->extkey0',extkey1='$event->extkey1',rrule_pid='$event_id'" ;

	// ï¿½ï¿½Î»ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	// ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
	$count = $this->max_rrule_extract ;
	if( isset( $COUNT ) && $COUNT > 0 && $COUNT < $count ) {
		$count = $COUNT ;
	}
	// Å¸ï¿½ï¿½ï¿½ï¿½Î»ï¿½ï¿½
	if( isset( $UNTIL ) ) {
		// UNTIL ï¿½Ï¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ç¤ï¿½ï¿½ï¿½ï¿½Ìµï¿½ï¿½ï¿½Ç¸ï¿½ï¿½Ê¤ï¿½
		$year = substr( $UNTIL , 0 , 4 ) ;
		$month = substr( $UNTIL , 4 , 2 ) ;
		$date = substr( $UNTIL , 6 , 2 ) ;
		if( ! checkdate( $month , $date , $year ) ) $until = 0x7FFFFFFF ;
		else {
			$until = gmmktime( 23 , 59 , 59 , $month , $date , $year , 0 ) ;
			if( ! $event->allday ) {
				// ï¿½ï¿½ï¿½ï¿½ï¿½Ð»ï¿½ï¿½Ö¤È¥ï¿½ï¿½Ù¥ï¿½È»ï¿½ï¿½Ö¤ï¿½ï¿½ï¿½ï¿½Õ¤ï¿½ï¿½Û¤Ê¤ï¿½ï¿½ï¿½Ë¤ï¿½UNTILï¿½â¤ºï¿½é¤¹
				$until -= intval( $tzoffset_date * 86400 ) ;
				// UTC -> server_TZ ï¿½Î»ï¿½ï¿½ï¿½ï¿½×»ï¿½ï¿½Ï¹Ô¤ï¿½Ê¤ï¿½
				// $until -= intval( $this->server_TZ * 3600 ) ;
			}
		}
	} else $until = 0x7FFFFFFF ;

	// WKST
	if( empty( $WKST ) ) $WKST = 'MO' ;

	// UnixTimestampï¿½Ï°Ï³ï¿½ï¿½Î½ï¿½ï¿½ï¿½
	if( isset( $event->start_date ) ) {
		// ï¿½ï¿½ï¿½Ï¤ä½ªÎ»ï¿½ï¿½2038Ç¯ï¿½Ê¹ß¤Ê¤ï¿½Å¸ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½
		if( date( 'Y' , $event->start ) >= 2038 ) return ;
		if( date( 'Y' , $event->end ) >= 2038 ) return ;

		// 1971Ç¯ï¿½ï¿½Æ±ï¿½ï¿½Æ±ï¿½ï¿½ï¿½Å¸ï¿½ï¿½ï¿½Ù¡ï¿½ï¿½ï¿½ï¿½ï¿½startï¿½È¤ï¿½ï¿½ï¿½
		$event->start = mktime( 0 , 0 , 0 , substr( $event->start_date , 5 , 2 ) , substr( $event->start_date , 8 , 2 ) , 1970 + 1 ) ;

		// endï¿½ï¿½1970ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤é¡¢ï¿½ï¿½ï¿½ï¿½È¤Ã¤ï¿½È¿ï¿½Ç¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ç¤Ê¤ï¿½ï¿½ï¿½ï¿½Ï¤È¤ê¤¢ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ TODO
		if( isset( $event->end_date ) ) {
			$event->end = $event->start + ( $event->date_diff + 1 ) * 86400 ;
		}
	}

	// ï¿½ï¿½ï¿½Ù¾ï¿½ï¿½ï¿½ï¿½ï¿½
	$sqls = array() ;
	switch( $FREQ ) {
		case 'DAILY' :
			$gmstart = $event->start + date( "Z" , $event->start ) ;
			$gmend = $event->end + date( "Z" , $event->end ) ;
			for( $c = 1 ; $c < $count ; $c ++ ) {
				$gmstart += $INTERVAL * 86400 ;
				$gmend += $INTERVAL * 86400 ;
				if( $gmstart > $until ) break ;
				$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
			}
			break ;
			
		case 'WEEKLY' :
			$gmstart = $event->start + date( "Z" , $event->start ) ;
			$gmstartbase = $gmstart ;
			$gmend = $event->end + date( "Z" , $event->end ) ;
			$duration = $gmend - $gmstart ;
			$wtop_date = gmdate( 'j' , $gmstart ) - gmdate( 'w' , $gmstart ) ;
			if( $WKST != 'SU' ) $wtop_date = $wtop_date == 7 ? 1 : $wtop_date + 1 ;
			$secondofday = $gmstart % 86400 ;
			$month = gmdate( 'm' , $gmstart ) ;
			$year = gmdate( 'Y' , $gmstart ) ;
			$week_top = gmmktime( 0 , 0 , 0 , $month , $wtop_date , $year ) ;
			$c = 1 ;
			// ï¿½ï¿½ï¿½Í²ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Îºï¿½ï¿½ï¿½
			$temp_dates = explode( ',' , $BYDAY ) ;
			$wdays = array_keys( $this->byday2langday_w ) ;
			if( $WKST != 'SU' ) {
				// rotate wdays for creating array starting with Monday
				$sun_date = array_shift( $wdays ) ;
				array_push( $wdays , $sun_date ) ;
			}
			$dates = array() ;
			foreach( $temp_dates as $date ) {
				// measure for bug of PHP<4.2.0
				if( in_array( $date , $wdays ) ) {
					$dates[] = array_search( $date , $wdays ) ;
				}
			}
			sort( $dates ) ;
			$dates = array_unique( $dates ) ;
			if( ! count( $dates ) ) return ;
			while( 1 ) {
				foreach( $dates as $date ) {
					// ï¿½ï¿½ï¿½ï¿½ï¿½Ð»ï¿½ï¿½Ö¤È¥ï¿½ï¿½Ù¥ï¿½È»ï¿½ï¿½Ö¤ï¿½ï¿½ï¿½ï¿½ï¿½Û¤Ê¤ï¿½ï¿½ï¿½Î½ï¿½ï¿½ï¿½ï¿½É²ï¿½
					$gmstart = $week_top + ( $date - $tzoffset_date ) * 86400 + $secondofday ;
					if( $gmstart <= $gmstartbase ) continue ;
					$gmend = $gmstart + $duration ;
					if( $gmstart > $until ) break 2 ;
					if( ++ $c > $count ) break 2 ;
					$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
				}
				$week_top += $INTERVAL * 86400 * 7 ;
			}
			break ;

		case 'MONTHLY' :
			$gmstart = $event->start + date( "Z" , $event->start ) ;
			$gmstartbase = $gmstart ;
			$gmend = $event->end + date( "Z" , $event->end ) ;
			$duration = $gmend - $gmstart ;
			$secondofday = $gmstart % 86400 ;
			$month = gmdate( 'm' , $gmstart ) ;
			$year = gmdate( 'Y' , $gmstart ) ;
			$c = 1 ;
			if( isset( $BYDAY ) && ereg( '^(-1|[1-4])(SU|MO|TU|WE|TH|FR|SA)' , $BYDAY , $regs ) ) {
				// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½(BYDAY)ï¿½Î¾ï¿½ï¿½ï¿½Ê£ï¿½ï¿½ï¿½Ô²Ä¡ï¿½
				// ï¿½ï¿½Åªï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¹ï¿½ï¿½ï¿½ï¿½ï¿½
				$wdays = array_keys( $this->byday2langday_w ) ;
				$wday = array_search( $regs[2] , $wdays ) ;
				$first_ymw = gmdate( 'Ym' , $gmstart ) . intval( ( gmdate( 'j' , $gmstart ) - 1 ) / 7 ) ;
				if( $regs[1] == -1 ) {
					// ï¿½Ç½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½Î¥ë¡¼ï¿½ï¿½
					$monthday_bottom = gmmktime( 0 , 0 , 0 , $month , 0 , $year ) ;
					while( 1 ) {
						for( $i = 0 ; $i < $INTERVAL ; $i ++ ) {
							$monthday_bottom += gmdate( 't' , $monthday_bottom + 86400 ) * 86400 ;
						}
						// ï¿½Ç½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ä´ï¿½Ù¤ï¿½
						$last_monthdays_wday = gmdate( 'w' , $monthday_bottom ) ;
						$date_back = $wday - $last_monthdays_wday ;
						if( $date_back > 0 ) $date_back -= 7 ;
						// ï¿½ï¿½ï¿½ï¿½ï¿½Ð»ï¿½ï¿½Ö¤È¥ï¿½ï¿½Ù¥ï¿½È»ï¿½ï¿½Ö¤ï¿½ï¿½ï¿½ï¿½ï¿½Û¤Ê¤ï¿½ï¿½ï¿½Î½ï¿½ï¿½ï¿½ï¿½É²ï¿½
						$gmstart = $monthday_bottom + ( $date_back - $tzoffset_date ) * 86400 + $secondofday ;
						if( $gmstart <= $gmstartbase ) continue ;
						$gmend = $gmstart + $duration ;
						if( $gmstart > $until ) break ;
						if( ++ $c > $count ) break ;
						$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
					}
				} else {
					// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½Î¥ë¡¼ï¿½ï¿½
					$monthday_top = gmmktime( 0 , 0 , 0 , $month , 1 , $year ) ;
					$week_number_offset = ( $regs[1] - 1 ) * 7 * 86400 ;
					while( 1 ) {
						for( $i = 0 ; $i < $INTERVAL ; $i ++ ) {
							$monthday_top += gmdate( 't' , $monthday_top ) * 86400 ;
						}
						// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ä´ï¿½Ù¤ï¿½
						$week_numbers_top_wday = gmdate( 'w' , $monthday_top + $week_number_offset ) ;
						$date_ahead = $wday - $week_numbers_top_wday ;
						if( $date_ahead < 0 ) $date_ahead += 7 ;
						// ï¿½ï¿½ï¿½ï¿½ï¿½Ð»ï¿½ï¿½Ö¤È¥ï¿½ï¿½Ù¥ï¿½È»ï¿½ï¿½Ö¤ï¿½ï¿½ï¿½ï¿½ï¿½Û¤Ê¤ï¿½ï¿½ï¿½Î½ï¿½ï¿½ï¿½ï¿½É²ï¿½
						$gmstart = $monthday_top + $week_number_offset + ( $date_ahead - $tzoffset_date ) * 86400 + $secondofday ;
						if( $gmstart <= $gmstartbase ) continue ;
						$gmend = $gmstart + $duration ;
						if( $gmstart > $until ) break ;
						if( ++ $c > $count ) break ;
						$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
					}
				}
			} else if( isset( $BYMONTHDAY ) ) {
				// ï¿½ï¿½ï¿½Õ»ï¿½ï¿½ï¿½(BYMONTHDAY)ï¿½Î¾ï¿½ï¿½ï¿½Ê£ï¿½ï¿½ï¿½Ä¡ï¿½
				$monthday_top = gmmktime( 0 , 0 , 0 , $month , 1 , $year ) ;
				// BYMONTHDAY ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ¡ï¿½$datesï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½
				$temp_dates = explode( ',' , $BYMONTHDAY ) ;
				$dates = array() ;
				foreach( $temp_dates as $date ) {
					if( $date > 0 && $date <= 31 ) $dates[] = intval( $date ) ;
				}
				sort( $dates ) ;
				$dates = array_unique( $dates ) ;
				if( ! count( $dates ) ) return ;
				while( 1 ) {
					$months_day = gmdate( 't' , $monthday_top ) ;
					foreach( $dates as $date ) {
						// ï¿½ï¿½ÎºÇ½ï¿½ï¿½ï¿½Õ¥?ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
						if( $date > $months_day ) $date = $months_day ;
						// ï¿½ï¿½ï¿½ï¿½ï¿½Ð»ï¿½ï¿½Ö¤È¥ï¿½ï¿½Ù¥ï¿½È»ï¿½ï¿½Ö¤ï¿½ï¿½ï¿½ï¿½Õ¤ï¿½ï¿½Û¤Ê¤ï¿½ï¿½ï¿½Î½ï¿½ï¿½ï¿½ï¿½É²ï¿½
						$gmstart = $monthday_top + ( $date - 1 - $tzoffset_date ) * 86400 + $secondofday ;
						if( $gmstart <= $gmstartbase ) continue ;
						$gmend = $gmstart + $duration ;
						if( $gmstart > $until ) break 2 ;
						if( ++ $c > $count ) break 2 ;
						$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
					}
					for( $i = 0 ; $i < $INTERVAL ; $i ++ ) {
						$monthday_top += gmdate( 't' , $monthday_top ) * 86400 ;
					}
				}
			} else {
				// Í­ï¿½ï¿½ï¿½$BYDAYï¿½ï¿½$BYMONTHDAYï¿½ï¿½Ìµï¿½ï¿½ï¿½ï¿½Ð¡ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¤ï¿½ï¿½ï¿½ï¿½ï¿½Ê¤ï¿½
				return ;
			}
			break ;
			
		case 'YEARLY' :
			$gmstart = $event->start + date( "Z" , $event->start ) ;
			$gmstartbase = $gmstart ;
			$gmend = $event->end + date( "Z" , $event->end ) ;
			$duration = $gmend - $gmstart ;
			$secondofday = $gmstart % 86400 ;
			$gmmonth = gmdate( 'n' , $gmstart ) ;

			// empty BYMONTH
			if( empty( $BYMONTH ) ) $BYMONTH = $gmmonth ;

			// BYMONTH ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ¡ï¿½$monthsï¿½ï¿½ï¿½ï¿½Ë¤ï¿½ï¿½ï¿½ï¿½BYMONTHï¿½ï¿½Ê£ï¿½ï¿½ï¿½Ä¡ï¿½
			$temp_months = explode( ',' , $BYMONTH ) ;
			$months = array() ;
			foreach( $temp_months as $month ) {
				if( $month > 0 && $month <= 12 ) $months[] = intval( $month ) ;
			}
			sort( $months ) ;
			$months = array_unique( $months ) ;
			if( ! count( $months ) ) return ;

			if( isset( $BYDAY ) && ereg( '^(-1|[1-4])(SU|MO|TU|WE|TH|FR|SA)' , $BYDAY , $regs ) ) {
				// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½ï¿½Ê£ï¿½ï¿½ï¿½Ô²Ä¡ï¿½
				// ï¿½ï¿½Åªï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ö¹ï¿½ï¿½ï¿½ï¿½ï¿½
				$wdays = array_keys( $this->byday2langday_w ) ;
				$wday = array_search( $regs[2] , $wdays ) ;
				$first_ym = gmdate( 'Ym' , $gmstart ) ;
				$year = gmdate( 'Y' , $gmstart ) ;
				$c = 1 ;
				if( $regs[1] == -1 ) {
					// ï¿½Ç½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½Î¥ë¡¼ï¿½ï¿½
					while( 1 ) {
						foreach( $months as $month ) {
							// ï¿½Ç½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ä´ï¿½Ù¤ï¿½
							$last_monthdays_wday = gmdate( 'w' , gmmktime( 0 , 0 , 0 , $month + 1 , 0 , $year ) ) ;
							$date_back = $wday - $last_monthdays_wday ;
							if( $date_back > 0 ) $date_back -= 7 ;
							$gmstart = gmmktime( 0 , 0 , 0 , $month + 1 , $date_back - $tzoffset_date , $year ) + $secondofday ;
							// ï¿½ï¿½ï¿½ï¿½Æ±ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
							if( gmdate( 'Ym' , $gmstart ) <= $first_ym ) continue ;
							$gmend = $gmstart + $duration ;
							if( $gmstart > $until ) break 2 ;
							if( ++ $c > $count ) break 2 ;
							$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
						}
						$year += $INTERVAL ;
						if( $year >= 2038 ) break ;
					}
				} else {
					// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Î¾ï¿½ï¿½Î¥ë¡¼ï¿½ï¿½
					$week_numbers_top_date = 1 + ( $regs[1] - 1 ) * 7 ;
					while( 1 ) {
						foreach( $months as $month ) {
							// ï¿½ï¿½Nï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ä´ï¿½Ù¤ï¿½
							$week_numbers_top_wday = gmdate( 'w' , gmmktime( 0 , 0 , 0 , $month , $week_numbers_top_date , $year ) ) ;
							$date_ahead = $wday - $week_numbers_top_wday ;
							if( $date_ahead < 0 ) $date_ahead += 7 ;
							$gmstart = gmmktime( 0 , 0 , 0 , $month , $week_numbers_top_date + $date_ahead - $tzoffset_date , $year ) + $secondofday ;
							// ï¿½ï¿½ï¿½ï¿½Æ±ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½É¤ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
							if( gmdate( 'Ym' , $gmstart ) <= $first_ym ) continue ;
							$gmend = $gmstart + $duration ;
							if( $gmstart > $until ) break 2 ;
							if( ++ $c > $count ) break 2 ;
							$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
						}
						$year += $INTERVAL ;
						if( $year >= 2038 ) break ;
					}
				}
			} else {
				// ï¿½ï¿½ï¿½Õ»ï¿½ï¿½ï¿½Î¾ï¿½ï¿½Î¥ë¡¼ï¿½×¡ï¿½Ê£ï¿½ï¿½ï¿½Ô²Ä¡ï¿½
				$first_date = gmdate( 'j' , $gmstart ) ;
				$year = gmdate( 'Y' , $gmstart ) ;
				$c = 1 ;
				while( 1 ) {
					foreach( $months as $month ) {
						$date = $first_date ;
						// ï¿½ï¿½ÎºÇ½ï¿½ï¿½ï¿½Õ¥?ï¿½ï¿½ï¿½ï¿½ï¿½Ã¥ï¿½
						while( ! checkdate( $month , $date , $year ) && $date > 0 ) $date -- ;
						// $date ï¿½ï¿½ gmdate('j') ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Æ¤ï¿½ï¿½ë¤¿ï¿½á¡¢$tzoffset_date ï¿½Î½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
						$gmstart = gmmktime( 0 , 0 , 0 , $month , $date , $year ) + $secondofday ;
						if( $gmstart <= $gmstartbase ) continue ;
						$gmend = $gmstart + $duration ;
						if( $gmstart > $until ) break 2 ;
						if( ++ $c > $count ) break 2 ;
						$sqls[] = $base_sql . ",start=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmstart)."'),end=UNIX_TIMESTAMP('".gmdate("Y-m-d H:i:s", $gmend)."')";
					}
					$year += $INTERVAL ;
					if( $year >= 2038 ) break ;
				}
			}
			break ;
			
		default :
			return ;
	}

	// echo "<pre>" ; var_dump( $sqls ) ; echo "</pre>" ; exit ;
	foreach( $sqls as $sql ) {
		mysql_query( $sql , $this->conn ) ;
	}
    
    $result = mysql_query("SELECT id FROM {$this->table} WHERE rrule_pid={$event_id}", $this->conn);
    $pics = mysql_query("SELECT * FROM {$this->pic_table} WHERE event_id={$event_id}", $this->conn);
    while($event = mysql_fetch_object($result))
    {
        mysql_query("DELETE FROM {$this->pic_table} WHERE event_id={$event->id}", $this->conn);
        mysql_data_seek($pics, 0);
        while($pic= mysql_fetch_object($pics))
        {
            mysql_query("INSERT INTO {$this->pic_table} SET event_id='{$event->id}', picture='{$pic->picture}', main_pic='{$pic->main_pic}'", $this->conn);
        }
    }
}


// The End of Class
}

}

?>
