<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
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
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

$this->holidays = array(
    '2006-4-14' => 'Good Friday',
    '2007-4-6'  => 'Good Friday',
    '2008-3-21' => 'Good Friday',
    '2009-4-10' => 'Good Friday',

    '2006-4-17' => 'Easter Monday',
    '2007-4-9'  => 'Easter Monday',
    '2008-3-24' => 'Easter Monday',
    '2009-4-13' => 'Easter Monday',

    '2006-6-5' => 'Queens Birthday',
    '2007-6-4' => 'Queens Birthday',
    '2008-6-2' => 'Queens Birthday',
    '2009-6-1' => 'Queens Birthday',

    '2006-10-23'  => 'Labour Day',
    '2007-10-22'  => 'Labour Day',
    '2008-10-27 ' => 'Labour Day',
    '2009-10-26 ' => 'Labour Day',);

for ($y = 2006; $y < 2020; ++$y) {
    $this->holidays["$y-1-1"]   = 'New Years Day';
    $this->holidays["$y-1-2"]   = 'Day after New Years Day';
    $this->holidays["$y-2-6"]   = 'Waitangi Day';
    $this->holidays["$y-4-25"]  = 'Anzac Day';
    $this->holidays["$y-12-25"] = 'Christmas Day';
    $this->holidays["$y-12-26"] = 'Boxing Day';

    $this->holidays["$y-1-29 "] = 'Auckland Anniversary Day';
    $this->holidays["$y-3-31 "] = 'Taranaki Anniversary Day';
    $this->holidays["$y-11-1"]  = 'Hawkes Bay Anniversary Day';
    $this->holidays["$y-1-22"]  = 'Wellington Anniversary Day';
    $this->holidays["$y-11-1"]  = 'Marlborough Anniversary Day';
    $this->holidays["$y-2-1"]   = 'Nelson Anniversary Day';
    $this->holidays["$y-12-16"] = 'Canterbury Anniversary Day';
    $this->holidays["$y-12-1"]  = 'Westland Anniversary Day';
    $this->holidays["$y-3-23"]  = 'Otago Anniversary Day';
    $this->holidays["$y-1-17"]  = 'Southland Anniversary Day';
    $this->holidays["$y-11-30"] = 'Chatham Islands Anniversary Day';
}