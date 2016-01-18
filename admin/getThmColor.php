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
 
require_once '../../../mainfile.php';
require_once XOOPS_ROOT_PATH.'/modules/APCal/class/cssParser.php';

error_reporting(0);
$xoopsLogger->activated = false;

$useDefault = isset($_GET['default']) && $_GET['default'] == 2;
$css = new CSSParser($_GET['filename']);

$color = $css->parseColor('a', 'color');
$colors['apcal_saturday_color'] = $color && !$useDefault ? $color : '#666666';
$colors['apcal_sunday_color']   = $color && !$useDefault ? $color : '#666666';
$colors['apcal_holiday_color']  = $color && !$useDefault ? $color : '#666666';

$color = $css->parseColor('odd', 'background');
$colors['apcal_saturday_bgcolor'] = $color && !$useDefault ? $color : '#E9E9E9';
$colors['apcal_sunday_bgcolor']   = $color && !$useDefault ? $color : '#E9E9E9';
$colors['apcal_holiday_bgcolor']  = $color && !$useDefault ? $color : '#E9E9E9';

$color = $css->parseColor('body', 'color');
$colors['apcal_weekday_color'] = $color && !$useDefault ? $color : '#000000';
$colors['apcal_calhead_color'] = $color && !$useDefault ? $color : '#000000';

$color = $css->parseColor('even', 'background');
$colors['apcal_weekday_bgcolor'] = $color && !$useDefault ? $color : '#dee3e7';
$colors['apcal_calhead_bgcolor'] = $color && !$useDefault ? $color : '#dee3e7';

$color = $css->parseColor('head', 'background');
$colors['apcal_targetday_bgcolor'] = $color && !$useDefault ? $color : '#6699FF';
$colors['apcal_allcats_color']     = $color && !$useDefault ? $color : '#6699FF';

$color = $css->parseColor('table', 'border');
$colors['apcal_frame_css'] = $color && !$useDefault ? $color : '#000000';

$colors['apcal_event_bgcolor'] = '#EEEEEE';
$colors['apcal_event_color'] = '#000000';
$colors['apcal_allcats_color'] = '#5555AA';

echo json_encode($colors);

?>