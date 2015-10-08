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
	'2001-2-26'=>1,'2001-2-27'=>1,'2001-4-13'=>1,'2001-6-14'=>1,
	'2002-2-11'=>1,'2002-2-12'=>1,'2002-3-29'=>1,'2002-5-30'=>1,
	'2003-3-3'=>1,'2003-3-4'=>1,'2003-4-18'=>1,'2003-6-19'=>1,
	'2004-2-23'=>1,'2004-2-24'=>1,'2004-4-9'=>1,'2004-6-10'=>1,
	'2005-2-7'=>1,'2005-2-8'=>1,'2005-3-25'=>1,'2005-5-26'=>1,
	'2006-2-27'=>1,'2006-2-28'=>1,'2006-4-14'=>1,'2006-6-15'=>1,
	'2007-2-19'=>1,'2007-2-20'=>1,'2007-4-6'=>1,'2007-6-7'=>1,
	'2008-2-4'=>1,'2008-2-5'=>1,'2008-3-21'=>1,'2008-5-22'=>1,
	'2009-2-23'=>1,'2009-2-24'=>1,'2009-4-10'=>1,'2009-6-11'=>1,
	'2010-2-15'=>1,'2010-2-16'=>1,'2010-4-2'=>1,'2010-6-3'=>1,
	);
	
for( $y = 2001 ; $y <= 2010 ; $y ++ ) {
	$this->holidays[ "{$y}-1-1" ] = 1 ;
	$this->holidays[ "{$y}-4-21" ] = 1 ;
	$this->holidays[ "{$y}-5-1" ] = 1 ;
	$this->holidays[ "{$y}-9-7" ] = 1 ;
	$this->holidays[ "{$y}-10-12" ] = 1 ;
	$this->holidays[ "{$y}-11-2" ] = 1 ;
	$this->holidays[ "{$y}-11-15" ] = 1 ;
	$this->holidays[ "{$y}-12-25" ] = 1 ;
}

?>