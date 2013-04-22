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

if( substr( XOOPS_VERSION , 6 , 3 ) < 2.1 ) {

	// Keep Block option values when update (by nobunobu)
	global $xoopsDB;
	$query = "SELECT mid FROM ".$xoopsDB->prefix('modules')." WHERE dirname='".$modversion['dirname']."' ";
	$result = $xoopsDB->query($query);
	$record= $xoopsDB->fetcharray($result);
	if ($record) {
		$mid = $record['mid'];
		$count = count($modversion['blocks']);
		/* $sql = "SELECT * FROM ".$xoopsDB->prefix('newblocks')." WHERE mid=".$mid." AND block_type ='D'";
		$fresult = $xoopsDB->query($sql);
		$n_funcnum = $count;
		while ($fblock = $xoopsDB->fetchArray($fresult)) {
			$bnum = 0;
			for ($i = 1 ; $i <= $count ; $i++) {
				if (($modversion['blocks'][$i]['file'] == $fblock['func_file']) and ($modversion['blocks'][$i]['show_func'] == $fblock['show_func'])) {
					$bnum = $i;
					break;
				}
			}
			if($bnum) {
				$n_funcnum++;
				$modversion['blocks'][$n_funcnum]['file'] = $fblock['func_file'];
				$modversion['blocks'][$n_funcnum]['name'] = $fblock['name'];
				$modversion['blocks'][$n_funcnum]['description'] = $fblock['name'];
				$modversion['blocks'][$n_funcnum]['show_func'] = $fblock['show_func'];
				$modversion['blocks'][$n_funcnum]['edit_func'] = $fblock['edit_func'];
				$modversion['blocks'][$n_funcnum]['template'] = $fblock['template'];
				if ($fblock['options']) {
					$old_vals=explode("|",$fblock['options']);
					$def_vals=explode("|",$modversion['blocks'][$bnum]['options']);
					if (count($old_vals) == count($def_vals)) {
						// the number of parameters is not changed
						$modversion['blocks'][$n_funcnum]['options'] = $fblock['options'];
						$local_msgs[] = "Option's values of the cloned block <b>".$fblock['name']."</b> will be kept. (value = <b>".$fblock['options']."</b>)";
					} else if (count($old_vals) < count($def_vals)){
						// the number of parameters is increased
						for ($j=0; $j < count($old_vals); $j++) {
							$def_vals[$j] = $old_vals[$j];
						}
						$modversion['blocks'][$n_funcnum]['options'] = implode("|",$def_vals);
						$local_msgs[] = "Option's values of the cloned block <b>".$fblock['name']."</b> will be kept and new options are added. (value = <b>".$modversion['blocks'][$fblock['func_num']]['options']."</b>)";
					} else {
						$modversion['blocks'][$n_funcnum]['options'] = implode("|",$def_vals);
						$local_msgs[] = "Option's values of the cloned block <b>".$fblock['name']."</b> will be reset to the default, because of some decrease of options. (value = <b>".$modversion['blocks'][$n_funcnum]['options']."</b>)";
					}
				}
				$sql = "UPDATE ".$xoopsDB->prefix('newblocks')." SET func_num='$n_funcnum' WHERE mid=".$mid." AND bid='".$fblock['bid']."'";
				$iret = $xoopsDB->query($sql);

			}
		} */
		
		$sql = "SELECT * FROM ".$xoopsDB->prefix('newblocks')." WHERE mid=".$mid." AND block_type <>'D' AND func_num > $count";
		$fresult = $xoopsDB->query($sql);
		while ($fblock = $xoopsDB->fetchArray($fresult)) {
			$local_msgs[] = "Non Defined Block <b>".$fblock['name']."</b> will be deleted";
			$sql = "DELETE FROM ".$xoopsDB->prefix('newblocks')." WHERE bid='".$fblock['bid']."'";
			$iret = $xoopsDB->query($sql);
		}
		
		for ($i = 1 ; $i <= $count ; $i++) {
			$sql = "SELECT name,options FROM ".$xoopsDB->prefix('newblocks')." WHERE mid=".$mid." AND func_num=".$i." AND show_func='".addslashes($modversion['blocks'][$i]['show_func'])."' AND func_file='".addslashes($modversion['blocks'][$i]['file'])."'";
			$fresult = $xoopsDB->query($sql);
			$fblock = $xoopsDB->fetchArray($fresult);
			if ( isset( $fblock['options'] ) ) {
				$old_vals=explode("|",$fblock['options']);
				$def_vals=explode("|",$modversion['blocks'][$i]['options']);
				if (count($old_vals) == count($def_vals)) {
					$modversion['blocks'][$i]['options'] = $fblock['options'];
					$local_msgs[] = "Option's values of the block <b>".$fblock['name']."</b> will be kept. (value = <b>".$fblock['options']."</b>)";
				} else if (count($old_vals) < count($def_vals)){
					for ($j=0; $j < count($old_vals); $j++) {
						$def_vals[$j] = $old_vals[$j];
					}
					$modversion['blocks'][$i]['options'] = implode("|",$def_vals);
					$local_msgs[] = "Option's values of the block <b>".$fblock['name']."</b> will be kept and new option(s) are added. (value = <b>".$modversion['blocks'][$i]['options']."</b>)";
				} else {
					$local_msgs[] = "Option's values of the block <b>".$fblock['name']."</b> will be reset to the default, because of some decrease of options. (value = <b>".$modversion['blocks'][$i]['options']."</b>)";
				}
			}
		}
	}

	global $msgs , $myblocksadmin_parsed_updateblock ;
	if( ! empty( $msgs ) && empty( $myblocksadmin_parsed_updateblock ) ) {
		$msgs = array_merge( $msgs , $local_msgs ) ;
		$myblocksadmin_parsed_updateblock = true ;
	}

}

?>