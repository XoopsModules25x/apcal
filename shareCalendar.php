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
 
require '../../mainfile.php';

include XOOPS_ROOT_PATH.'/header.php';
require_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

$tpl = new XoopsTpl();
$form = new XoopsThemeForm(_APCAL_SHARECALENDARFORM, 'calendar', '', 'post');
$formCustom = new XoopsThemeForm(_APCAL_IFCUSTOM, 'custom', '', 'post');

$catSelect = new XoopsFormSelect(_APCAL_CATEGORIES, 'c', 0);
$catSelect->addOptionArray(getCategories());

$styleSelect = new XoopsFormRadio(_APCAL_STYLE, 't', 'default');
$styleSelect->addOption('default', _APCAL_DEFAULT);
$styleSelect->addOption('theme', _APCAL_THEME);
$styleSelect->addOption('custom', _APCAL_CUSTOM);
$styleSelect->setExtra('onclick="showCustomSettings();"');

$unitSelect = new XoopsFormSelect('', 'u', '%');
$unitSelect->addOption('%', '%');
$unitSelect->addOption('px', 'px');
$unitSelect->addOption('em', 'em');

$wTray = new XoopsFormElementTray(_APCAL_WIDTH);
$wTray->addElement(new XoopsFormText('', 'w', 10, 7, '100'), true);
$wTray->addElement($unitSelect);

$generateButton = new XoopsFormButton(_APCAL_GENERATEHINT, 'generate', _APCAL_GENERATE, 'submit');
$generateButton->setExtra('onclick="showHTMLCode();return false;"');

$form->addElement(new XoopsFormText(_APCAL_TITLE, 'h', 30, 60, $xoopsModule->getVar('name')), true);
$form->addElement($catSelect, true);
$form->addElement(new XoopsFormText(_APCAL_NBEVENTS, 'n', 5, 3, '10'), true);
$form->addElement($wTray, true);
$form->addElement($styleSelect, true);
//$form->insertBreak('');
//$form->insertBreak(_APCAL_IFCUSTOM);
//$form->insertBreak('');
$formCustom->addElement(new XoopsFormText(_APCAL_BORDER, 'APborder', 60, 255, 'border: 3px double #000000;'), false);
$formCustom->addElement(new XoopsFormText(_APCAL_TITLE, 'APtitle', 60, 255, 'color: #000000; font-size: 1.3em;'), false);
$formCustom->addElement(new XoopsFormText(_APCAL_TEXT, 'APtext', 60, 255, 'color: #000000; font-size: 1.0em;'), false);
$formCustom->addElement(new XoopsFormText(_APCAL_LINK, 'APlink', 60, 255, 'color: #0000FF; text-decoration: none;'), false);
$formCustom->addElement(new XoopsFormText(_APCAL_EVEN, 'APeven', 60, 255, 'background-color: #F2F2F2;'), false);
$formCustom->addElement(new XoopsFormText(_APCAL_ODD, 'APodd', 60, 255, 'background-color: #EBEBEB;'), false);
$form->addElement($generateButton, false);

$form->display();
echo '<div id="customSettings" style="display: none;">';
$formCustom->display();
echo '</div>';
echo '<br />'._APCAL_SHAREINFO.'<br />';
echo '<div id="htmlCode"></div>';
echo $tpl->fetch(XOOPS_ROOT_PATH.'/modules/APCal/templates/shareCalendar.html');

include XOOPS_ROOT_PATH.'/footer.php';

function getCategories()
{
    global $xoopsDB;
    
    $cats = array(0 => _APCAL_SHOWALLCAT);
    $result = $xoopsDB->queryF("SELECT cid, cat_title, cat_depth FROM {$xoopsDB->prefix('apcal_cat')} ORDER BY weight");

	while($cat = $xoopsDB->fetchObject($result))
    {
		$depth_desc = str_repeat('-', intval( $cat->cat_depth));
        $title = htmlspecialchars($cat->cat_title , ENT_QUOTES);
        $cats[$cat->cid] = "$depth_desc $title";
	}

    return $cats;
}

?>