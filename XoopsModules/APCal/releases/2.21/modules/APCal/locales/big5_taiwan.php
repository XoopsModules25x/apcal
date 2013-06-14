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
 * @author      Chia (http://www.cyai.net/)
 * @version     $Id:$
 */
 
$this->holidays = array(
'2005-2-8'=>'���i','2005-2-9'=>'�K�`','2005-2-10'=>'�^�Q�a','2005-2-11'=>'����','2005-2-12'=>'�ﯫ','2005-2-13'=>'�}��',
'2005-6-11'=>'�ݤȸ`','2005-9-18'=>'����`',
'2006-1-28'=>'���i','2006-1-29'=>'�K�`','2006-1-30'=>'�^�Q�a','2006-1-31'=>'����','2006-2-1'=>'�ﯫ','2006-2-2'=>'�}��',
'2006-5-31'=>'�ݤȸ`','2006-10-6'=>'����`',
'2007-2-17'=>'���i','2007-2-18'=>'�K�`','2007-2-19'=>'�^�Q�a','2007-2-20'=>'����','2007-2-21'=>'�ﯫ','2007-2-22'=>'�}��',
'2007-6-19'=>'�ݤȸ`','2007-9-25'=>'����`',
'2008-2-6'=>'���i','2008-2-7'=>'�K�`','2008-2-8'=>'�^�Q�a','2008-2-9'=>'����','2008-2-10'=>'�ﯫ','2008-2-11'=>'�}��',
'2008-6-8'=>'�ݤȸ`','2008-9-14'=>'����`',
'2009-1-25'=>'���i','2009-1-26'=>'�K�`','2009-1-27'=>'�^�Q�a','2009-1-28'=>'����','2009-1-29'=>'�ﯫ','2009-1-30'=>'�}��',
'2009-5-28'=>'�ݤȸ`','2009-10-3'=>'����`',
'2010-2-13'=>'���i','2010-2-14'=>'�K�`','2010-2-15'=>'�^�Q�a','2010-2-16'=>'����','2010-2-17'=>'�ﯫ','2010-2-18'=>'�}��',
'2010-6-16'=>'�ݤȸ`','2010-9-22'=>'����`',
	);

for( $y = 2001 ; $y < 2020 ; $y ++ ) {
$this->holidays[ "$y-1-1" ] = '����' ;
$this->holidays[ "$y-2-28" ] = '�M��������' ;
$this->holidays[ "$y-4-5" ] = '�M���`' ;
$this->holidays[ "$y-10-10" ] = '��y��' ;
}

?>