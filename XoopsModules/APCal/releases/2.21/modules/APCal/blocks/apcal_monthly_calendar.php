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
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 * @version     $Id:$
 */

if( ! defined( 'APCAL_BLOCK_MONTHLY_CALENDAR_INCLUDED' ) ) {

define( 'APCAL_BLOCK_MONTHLY_CALENDAR_INCLUDED' , 1 ) ;

function apcal_monthly_calendar_show( $options )
{
	global $xoopsConfig , $xoopsDB ;

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;

	// setting physical & virtual paths
	$mod_path = XOOPS_ROOT_PATH."/modules/$mydirname" ;
	$mod_url = XOOPS_URL."/modules/$mydirname" ;

	// defining class of APCal
	if( ! class_exists( 'APCal_xoops' ) ) {
		require_once( "$mod_path/class/APCal.php" ) ;
		require_once( "$mod_path/class/APCal_xoops.php" ) ;
	}

	// creating an instance of APCal 
	$cal = new APCal_xoops( "" , $xoopsConfig['language'] , true ) ;

	// ignoring cid from GET
	$cal->now_cid = 0 ;

	// setting properties of APCal
	$cal->conn = $xoopsDB->conn ;
	include( "$mod_path/include/read_configs.php" ) ;
	$cal->base_url = $mod_url ;
	$cal->base_path = $mod_path ;
	$cal->images_url = "$mod_url/images/$skin_folder" ;
	$cal->images_path = "$mod_path/images/$skin_folder" ;

    $original_level = error_reporting( E_ALL ^ E_NOTICE ) ;
    
	require_once( "$mod_path/include/patTemplate.php" ) ;
	$tmpl = new PatTemplate() ;
	$tmpl->readTemplatesFromFile( "$cal->images_path/block_monthly.tmpl.html" ) ;

	// setting skin folder
	$tmpl->addVar( "WholeBoard" , "SKINPATH" , $cal->images_url ) ;

	// setting language
	$tmpl->addVar( "WholeBoard" , "LANG_PREV_MONTH" , _MB_APCAL_PREV_MONTH ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_NEXT_MONTH" , _MB_APCAL_NEXT_MONTH ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_YEAR" , _MB_APCAL_YEAR ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_MONTH" , _MB_APCAL_MONTH ) ;
	$tmpl->addVar( "WholeBoard" , "LANG_JUMP" , _MB_APCAL_JUMP ) ;

	// Static parameter for the request
	$tmpl->addVar( "WholeBoard" , "GET_TARGET" , "$mod_url/index.php" ) ;
	$tmpl->addVar( "WholeBoard" , "QUERY_STRING" , '' ) ;

	// Variables required in header part etc.
	$tmpl->addVars( "WholeBoard" , $cal->get_calendar_information( 'M' ) ) ;

	// BODY of the calendar
	$tmpl->addVar( "WholeBoard" , "CALENDAR_BODY" , $cal->get_monthly_html( "$mod_url/index.php" ) ) ;

	// legends of long events
	foreach( $cal->long_event_legends as $bit => $legend ) {
		$tmpl->addVar( "LongEventLegends" , "BIT_MASK" , 1 << ( $bit - 1 ) ) ;
		$tmpl->addVar( "LongEventLegends" , "LEGEND_ALT" , _APCAL_MB_APCALALLDAY_EVENT . " $bit" ) ;
		$tmpl->addVar( "LongEventLegends" , "LEGEND" , $legend ) ;
		$tmpl->addVar( "LongEventLegends" , "SKINPATH" , $cal->images_url ) ;
		$tmpl->parseTemplate( "LongEventLegends" , "a" ) ;
	}

	// content generated from patTemplate
	$block['content'] = $tmpl->getParsedTemplate( "WholeBoard" ) ;
    error_reporting( $original_level ) ;

	return $block ;
}



function apcal_monthly_calendar_edit( $options )
{
	return '' ;
}

}

?>