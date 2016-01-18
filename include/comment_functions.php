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
 
if( ! defined( 'APCAL_COMMENT_FUNCTIONS_INCLUDED' ) ) {

define( 'APCAL_COMMENT_FUNCTIONS_INCLUDED' , 1 ) ;

// comment callback functions

function apcal_comments_update( $event_id , $total_num ) {

	// record total_num
	global $xoopsDB , $cal ;

	if( is_object( $cal ) ) {
		$tablename = $cal->table ;
	} else {
		$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
		if( ! preg_match( '/^(\D+)(\d*)$/' , $mydirname , $regs ) ) echo ( "invalid dirname: " . htmlspecialchars( $mydirname ) ) ;
		$mydirnumber = $regs[2] === '' ? '' : intval( $regs[2] ) ;
		$tablename = $xoopsDB->prefix("apcal{$mydirnumber}_event") ;
	}

	$ret = $xoopsDB->query( "UPDATE $tablename SET comments=$total_num WHERE id=$event_id" ) ;
	return $ret ;
}

function apcal_comments_approve( &$comment )
{
	// notification mail here
}

}

?>