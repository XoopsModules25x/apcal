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
 * @author      Antiques Promotion (http://www.antiquespromotion.ca)
 */
class cssParser
{
    public $_css = '';

    public function __construct($filename = 'style.css')
    {
        global $xoopsConfig;

        $this->_css = file_get_contents(XOOPS_THEME_PATH . '/' . $xoopsConfig['theme_set'] . '/' . $filename);
    }

    public function parseColor($selector, $style)
    {
        $match = array();

        if (preg_match('/[\.\S]*' . $selector . '([^a-zA-Z0-9]{1}[\.\S]*{|{)([\s\S][^{}]+)(})/', $this->_css, $match)) {
            preg_match('/[^-]*' . $style . '([^;]*):([^;]*)#([a-zA-Z0-9]+)/', $match[2], $match);
        }
        if (is_array($match) && key_exists(3, $match)) {
            return '#' . (strlen($match[3]) == 3 ? $match[3][0] . $match[3][0] . $match[3][1] . $match[3][1] . $match[3][2] . $match[3][2] : $match[3]);
        }

        return false;
    }
}
