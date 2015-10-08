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
 * @version     $Id:$
 */
 
if(!function_exists('easter'))
{
function easter($y, &$holidays)
{
    $e = 21 + easter_days($y);
    if($e > 31) {$e -= 31; $em = 4;}
    else {$em = 3;}
    $f = $e - 2;
    $m = $e + 1;
    if($f > 0) {$holidays[ "$y-$em-$f" ] = 'Good Friday';}
    else {$f = 31 - $f; $holidays[ "$y-3-$f" ] = 'Good Friday';}
    $holidays[ "$y-$em-$e" ] = 'Easter';
    if($m > 31) {$m -= 31; $holidays[ "$y-4-$m" ] = 'Easter Monday';}
    else {$holidays[ "$y-$em-$m" ] = 'Easter Monday';}  
}
}

$this->holidays = array();
$start = intval(date('Y')) - 10;
$end = $start + 30;

for( $y = $start ; $y < $end ; $y ++ ) {
    easter($y, $this->holidays);
    $k = date('j', strtotime('+3 Monday', strtotime("$y-1-1")));
    $w = date('j', strtotime('+3 Monday', strtotime("$y-2-1")));
    $me = date('j', strtotime('Last Saturday May', strtotime("$y-5-1"))); // Last Monday of May but there's a bug... Last Saturday is a hack.
    $mo = date('j', strtotime('+2 Sunday', strtotime("$y-5-1")));
    $f = date('j', strtotime('+3 Sunday', strtotime("$y-6-1")));
    $l = date('j', strtotime('+1 Monday', strtotime("$y-9-1")));
    $c = date('j', strtotime('+2 Monday', strtotime("$y-10-1")));
    $t = date('j', strtotime('+4 Thursday', strtotime("$y-11-1")));
    
	$this->holidays["$y-1-1"] = 'New Year\'s Day';   
    $this->holidays["$y-1-$k"] = 'Martin Luther King, Jr. Day';
    $this->holidays["$y-2-2"] = 'Groundhog Day';
    $this->holidays["$y-2-14"] = 'Valentine\'s Day';
    $this->holidays["$y-2-$w"] = 'Washington\'s Birthday';
    $this->holidays["$y-3-17"] = 'St. Patrick\'s';
    $this->holidays["$y-4-22"] = 'Earth Day';
    $this->holidays["$y-5-$mo"] = 'Mother\'s Day';
    $this->holidays["$y-5-$me"] = 'Memorial';
    $this->holidays["$y-6-$f"] = 'Father\'s Day';
	$this->holidays["$y-7-4"] = 'Independence Day';
    $this->holidays["$y-9-$l"] = 'Labour Day';
    $this->holidays["$y-10-$c"] = 'Columbus Day';
    $this->holidays["$y-10-31"] = 'Halloween';
	$this->holidays["$y-11-11"] = 'Veterans Day';
    $this->holidays["$y-11-$t"] = 'Thanksgiving';
	$this->holidays["$y-12-25"] = 'Christmas';
}

?>