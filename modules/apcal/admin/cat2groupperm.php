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
 
require_once( '../../../include/cp_header.php' ) ;
require_once( 'mygrouppermform.php' ) ;
require_once( XOOPS_ROOT_PATH."/class/xoopstree.php" ) ;

// for "Duplicatable"
$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
if( ! preg_match( '/^(\D+)(\d*)$/' , $mydirname , $regs ) ) echo ( "invalid dirname: " . htmlspecialchars( $mydirname ) ) ;
$mydirnumber = $regs[2] === '' ? '' : intval( $regs[2] ) ;

require_once( XOOPS_ROOT_PATH."/modules/$mydirname/include/gtickets.php" ) ;

// the names of tables
$cat_table = $xoopsDB->prefix( "apcal{$mydirnumber}_cat" ) ;

// language files
$language = $xoopsConfig['language'] ;
if( ! file_exists( XOOPS_ROOT_PATH . "/modules/system/language/$language/admin/blocksadmin.php") ) $language = 'english' ;
include_once( XOOPS_ROOT_PATH . "/modules/system/language/$language/admin.php" ) ;

if( ! empty( $_POST['submit'] ) ) {

	// Ticket Check
	if ( ! $xoopsGTicket->check( true , 'myblocksadmin' ) ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	include( "mygroupperm.php" ) ;
	redirect_header( XOOPS_URL."/modules/$mydirname/admin/cat2groupperm.php" , 1 , _MD_APCALAM_APCALDBUPDATED );
	exit ;
}


// creating Objects of XOOPS
$myts =& MyTextSanitizer::getInstance();
$cattree = new XoopsTree( $cat_table , "cid" , "pid" ) ;
$form = new MyXoopsGroupPermForm( _AM_APCAL_MENU_CAT2GROUP , $xoopsModule->mid() , 'apcal_cat' , _AM_APCAL_CAT2GROUPDESC ) ;

$cat_tree_array = $cattree->getChildTreeArray( 0 , 'weight ASC,cat_title' ) ;

foreach( $cat_tree_array as $cat ) {
	$form->addItem( intval( $cat['cid'] ) , $myts->htmlSpecialChars( $cat['cat_title'] ) , intval( $cat['pid'] ) ) ;
}

xoops_cp_header();
require_once XOOPS_ROOT_PATH.'/modules/APCal/admin/displayMenu.php';
echo $form->render() ;
xoops_cp_footer();

?>
