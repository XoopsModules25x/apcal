<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package
 * @since
 * @author       XOOPS Development Team,
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 */

if (!function_exists('easter')) {
    /**
     * @param $y
     * @param $holidays
     */
    function easter($y, &$holidays)
    {
        $e = 21 + easter_days($y);
        if ($e > 31) {
            $e  -= 31;
            $em = 4;
        } else {
            $em = 3;
        }
        $f = $e - 2;
        $m = $e + 1;
        if ($f > 0) {
            $holidays["$y-$em-$f"] = 'Good Friday';
        } else {
            $f                   = 31 - $f;
            $holidays["$y-3-$f"] = 'Good Friday';
        }
        $holidays["$y-$em-$e"] = 'Easter';
        if ($m > 31) {
            $m                   -= 31;
            $holidays["$y-4-$m"] = 'Easter Monday';
        } else {
            $holidays["$y-$em-$m"] = 'Easter Monday';
        }
    }
}

$this->holidays = array();
$start          = (int)date('Y') - 10;
$end            = $start + 30;

for ($y = $start; $y < $end; ++$y) {
    easter($y, $this->holidays);
    $v = ((int)date('N', strtotime("$y-5-25")) == 1) ? '25' : date('j', strtotime('Last Monday', strtotime("$y-5-25")));
    $m = date('j', strtotime('+2 Sunday', strtotime("$y-5-1")));
    $f = date('j', strtotime('+3 Sunday', strtotime("$y-6-1")));
    $c = date('j', strtotime('+1 Monday', strtotime("$y-8-1")));
    $l = date('j', strtotime('+1 Monday', strtotime("$y-9-1")));
    $t = date('j', strtotime('+2 Monday', strtotime("$y-10-1")));

    $this->holidays["$y-1-1"]   = 'New Years Day';
    $this->holidays["$y-2-14"]  = 'Valentine\'s Day';
    $this->holidays["$y-3-17"]  = 'St. Patrick\'s Day';
    $this->holidays["$y-4-22"]  = 'Earth Day';
    $this->holidays["$y-5-$m"]  = 'Mother\'s Day';
    $this->holidays["$y-5-$v"]  = 'Victoria Day';
    $this->holidays["$y-6-$f"]  = 'Father\'s Day';
    $this->holidays["$y-7-1"]   = 'Canada Day';
    $this->holidays["$y-8-$c"]  = 'Civic Holiday';
    $this->holidays["$y-9-$l"]  = 'Labour Day';
    $this->holidays["$y-10-$t"] = 'Thanksgiving';
    $this->holidays["$y-10-31"] = 'Halloween';
    $this->holidays["$y-11-11"] = 'Rememberance Day';
    $this->holidays["$y-12-25"] = 'Christmas Day';
    $this->holidays["$y-12-26"] = 'Boxing Day';
}
