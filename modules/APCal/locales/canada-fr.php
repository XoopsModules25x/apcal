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
    if($f > 0) {$holidays[ "$y-$em-$f" ] = 'Vendredi Saint';}
    else {$f = 31 - $f; $holidays[ "$y-3-$f" ] = 'Vendredi Saint';}
    $holidays[ "$y-$em-$e" ] = 'P&acirc;ques';
    if($m > 31) {$m -= 31; $holidays[ "$y-4-$m" ] = 'Lundi de P&acirc;ques';}
    else {$holidays[ "$y-$em-$m" ] = 'Lundi de P&acirc;ques';}  
}
}

$this->holidays = array();
$start = intval(date('Y')) - 10;
$end = $start + 30;

for( $y = $start ; $y < $end ; $y ++ ) {
easter($y, $this->holidays);
$v = (intval(date('N', strtotime("$y-5-25"))) == 1) ? '25' : date('j', strtotime('Last Monday', strtotime("$y-5-25")));
$m = date('j', strtotime('+2 Sunday', strtotime("$y-5-1")));
$f = date('j', strtotime('+3 Sunday', strtotime("$y-6-1")));
$c = date('j', strtotime('+1 Monday', strtotime("$y-8-1")));
$l = date('j', strtotime('+1 Monday', strtotime("$y-9-1")));
$t = date('j', strtotime('+2 Monday', strtotime("$y-10-1")));

$this->holidays[ "$y-1-1" ] = 'Jour de l\'an';
$this->holidays[ "$y-2-14" ] = 'St-Valentin';
$this->holidays[ "$y-3-17" ] = 'St-Patrick';
$this->holidays[ "$y-4-22" ] = 'Jour de la terre';
$this->holidays[ "$y-5-$m" ] = 'F&ecirc;te des m&egrave;re';
$this->holidays[ "$y-5-$v" ] = 'F&ecirc;te de la reine';
$this->holidays[ "$y-6-$f" ] = 'F&ecirc;te des p&egrave;re';
$this->holidays[ "$y-6-24" ] = 'St-Jean-Baptiste';
$this->holidays[ "$y-7-1" ] = 'F&ecirc;te du Canada';
$this->holidays[ "$y-8-$c" ] = 'Cong&eacute; provincial';
$this->holidays[ "$y-9-$l" ] = 'F&ecirc;te du travail';
$this->holidays[ "$y-10-$t" ] = 'Action de gr&acirc;ce';
$this->holidays[ "$y-10-31" ] = 'Halloween';
$this->holidays[ "$y-11-11" ] = 'Jour du souvenir';
$this->holidays[ "$y-12-25" ] = 'No&euml;l';
$this->holidays[ "$y-12-26" ] = 'Boxing Day';
}

?>