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
 
$this->holidays = array(
	'2005-3-7'=>'Labour Day','2005-3-25'=>'Good Friday',
	'2005-3-28'=>'Easter Monday','2005-6-6'=>'Foundation Day',
	'2005-9-26'=>'Queens Birthday','2005-12-26'=>'Christmas Day PH',
  '2005-12-27'=>'Boxing Day PH','2006-1-2'=>'New Years PH',
	'2006-3-6'=>'Labour Day','2006-4-14'=>'Good Friday',
	'2006-4-17'=>'Easter Monday','2006-6-5'=>'Foundation Day',
	'2006-10-2'=>'Queens Birthday','2007-3-5'=>'Labour Day',
	'2007-4-6'=>'Good Friday','2007-4-9'=>'Easter Monday',
	'2007-6-4'=>'Foundation Day','2007-10-1'=>'Queens Birthday'
);

for( $y = 2001 ; $y < 2020 ; $y ++ ) {
	$this->holidays[ "$y-1-1" ] = 'New Years Day' ;
	$this->holidays[ "$y-1-26" ] = 'Australia Day' ;
	$this->holidays[ "$y-4-25" ] = 'Anzac Day' ;
	$this->holidays[ "$y-12-25" ] = 'Christmas Day' ;
	$this->holidays[ "$y-12-26" ] = 'Boxing Day' ;
}

?>