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
 
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

eval( '

function b_sitemap_'.$mydirname.'(){
	return b_sitemap_APCal_base( "'.$mydirname.'" ) ;
}

' ) ;

if( ! function_exists( 'b_sitemap_APCal_base' ) ) {

function b_sitemap_APCal_base( $mydirname )
{
	global $xoopsConfig , $xoopsDB , $xoopsUser ;
	$myts =& MyTextSanitizer::getInstance();

	// get $mydirnumber
	if( ! preg_match( '/^(\D+)(\d*)$/' , $mydirname , $regs ) ) echo ( "invalid dirname: " . htmlspecialchars( $mydirname ) ) ;
	$mydirnumber = $regs[2] === '' ? '' : intval( $regs[2] ) ;

	// setting physical & virtual paths
	$mod_path = XOOPS_ROOT_PATH."/modules/$mydirname" ;
	$mod_url = XOOPS_URL."/modules/$mydirname" ;

	// defining class of APCal
	if( ! class_exists( 'APCal_xoops' ) ) {
		require_once( "$mod_path/class/APCal.php" ) ;
		require_once( "$mod_path/class/APCal_xoops.php" ) ;
	}

	// creating an instance of APCal 
	$cal = new APCal_xoops( '' , $xoopsConfig['language'] , true ) ;
	$cal->use_server_TZ = true ;

	// setting properties of APCal
	$cal->conn = $xoopsDB->conn ;
	//$cal->table = $xoopsDB->prefix( APCAL_EVENT_TABLE ) ;
	include( "$mod_path/include/read_configs.php" ) ;

	$ret = array() ;
	foreach( $cal->categories as $cat ) {

		// only Top category is shown
		if( $cat->cat_depth > 1 ) continue ;

		$ret["parent"][] = array(
			"id" => $cat->cid ,
			"title" => $myts->htmlSpecialChars( $cat->cat_title ) ,
			"url" => "index.php?cid=$cat->cid"
		) ;

	}

	return $ret ;
}

}

?>
