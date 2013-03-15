# ------------------------------------------------------------------------ #
#               XOOPS - PHP Content Management System                      #
#                   Copyright (c) 2000 XOOPS.org                           #
#                      <http://www.xoops.org/>                             #
# ------------------------------------------------------------------------ #
# This program is free software; you can redistribute it and/or modify     #
# it under the terms of the GNU General Public License as published by     #
# the Free Software Foundation; either version 2 of the License, or        #
# (at your option) any later version.                                      #
#                                                                          #
# You may not change or alter any portion of this comment or credits       #
# of supporting developers from this source code or any supporting         #
# source code which is considered copyrighted (c) material of the          #
# original comment or credit authors.                                      #
#                                                                          #
# This program is distributed in the hope that it will be useful,          #
# but WITHOUT ANY WARRANTY; without even the implied warranty of           #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            #
# GNU General Public License for more details.                             #
#                                                                          #
# You should have received a copy of the GNU General Public License        #
# along with this program; if not, write to the Free Software              #
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA #
# ------------------------------------------------------------------------ #
 

# @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
# @license     http://www.fsf.org/copyleft/gpl.html GNU public license
# @author      Antiques Promotion (http://www.antiquespromotion.ca)
# @version     $Id:$

CREATE TABLE `apcal_cat` (
  `cid` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `weight` smallint(5) NOT NULL DEFAULT '0',
  `exportable` tinyint(4) NOT NULL DEFAULT '1',
  `autocreated` tinyint(4) NOT NULL DEFAULT '0',
  `canbemain` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ismenuitem` tinyint(4) NOT NULL DEFAULT '0',
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `cat_shorttitle` varchar(255) NOT NULL DEFAULT '',
  `cat_title` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` text NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#5555AA',
  `dtstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cat_extkey0` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `cat_depth` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cat_style` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`),
  KEY `weight` (`weight`),
  KEY `cat_depth` (`cat_depth`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `apcal_event` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `groupid` smallint(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `shortsummary` varchar(255) NOT NULL DEFAULT '',
  `summary` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `gmlat` float NOT NULL DEFAULT '0',
  `gmlong` float NOT NULL DEFAULT '0',
  `gmzoom` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `organizer` varchar(255) NOT NULL DEFAULT '',
  `sequence` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `tzid` varchar(255) NOT NULL DEFAULT 'GMT',
  `description` text NOT NULL,
  `dtstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mainCategory` smallint(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `categories` varchar(255) NOT NULL DEFAULT '',
  `transp` tinyint(4) NOT NULL DEFAULT '1',
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `admission` tinyint(4) NOT NULL DEFAULT '0',
  `class` varchar(255) NOT NULL DEFAULT 'PUBLIC',
  `rrule` varchar(255) NOT NULL DEFAULT '',
  `rrule_pid` int(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `unique_id` varchar(255) NOT NULL DEFAULT '',
  `allday` tinyint(4) NOT NULL DEFAULT '0',
  `start` int(10) unsigned NOT NULL DEFAULT '0',
  `end` int(10) unsigned NOT NULL DEFAULT '0',
  `otherHours` varchar(255) NOT NULL DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `cid` smallint(5) unsigned zerofill NOT NULL DEFAULT '00000',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `event_tz` float(2,1) NOT NULL DEFAULT '0.0',
  `server_tz` float(2,1) NOT NULL DEFAULT '0.0',
  `poster_tz` float(2,1) NOT NULL DEFAULT '0.0',
  `extkey0` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `extkey1` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  PRIMARY KEY (`id`),
  KEY `admission` (`admission`),
  KEY `allday` (`allday`),
  KEY `start` (`start`),
  KEY `end` (`end`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `dtstamp` (`dtstamp`),
  KEY `unique_id` (`unique_id`),
  KEY `cid` (`cid`),
  KEY `event_tz` (`event_tz`),
  KEY `server_tz` (`server_tz`),
  KEY `poster_tz` (`poster_tz`),
  KEY `uid` (`uid`),
  KEY `groupid` (`groupid`),
  KEY `class` (`class`),
  KEY `rrule_pid` (`rrule_pid`),
  KEY `categories` (`categories`),
  KEY `admission_2` (`admission`),
  KEY `allday_2` (`allday`),
  KEY `start_2` (`start`),
  KEY `end_2` (`end`),
  KEY `start_date_2` (`start_date`),
  KEY `end_date_2` (`end_date`),
  KEY `dtstamp_2` (`dtstamp`),
  KEY `unique_id_2` (`unique_id`),
  KEY `cid_2` (`cid`),
  KEY `event_tz_2` (`event_tz`),
  KEY `server_tz_2` (`server_tz`),
  KEY `poster_tz_2` (`poster_tz`),
  KEY `uid_2` (`uid`),
  KEY `groupid_2` (`groupid`),
  KEY `class_2` (`class`),
  KEY `rrule_pid_2` (`rrule_pid`),
  KEY `categories_2` (`categories`),
  KEY `admission_3` (`admission`),
  KEY `allday_3` (`allday`),
  KEY `start_3` (`start`),
  KEY `end_3` (`end`),
  KEY `start_date_3` (`start_date`),
  KEY `end_date_3` (`end_date`),
  KEY `dtstamp_3` (`dtstamp`),
  KEY `unique_id_3` (`unique_id`),
  KEY `cid_3` (`cid`),
  KEY `event_tz_3` (`event_tz`),
  KEY `server_tz_3` (`server_tz`),
  KEY `poster_tz_3` (`poster_tz`),
  KEY `uid_3` (`uid`),
  KEY `groupid_3` (`groupid`),
  KEY `class_3` (`class`),
  KEY `rrule_pid_3` (`rrule_pid`),
  KEY `categories_3` (`categories`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `apcal_pictures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned zerofill NOT NULL,
  `picture` varchar(255) NOT NULL,
  `main_pic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `apcal_plugins` (
  `pi_id` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `pi_title` varchar(255) NOT NULL DEFAULT '',
  `pi_type` varchar(8) NOT NULL DEFAULT '',
  `pi_dirname` varchar(50) NOT NULL DEFAULT '',
  `pi_file` varchar(50) NOT NULL DEFAULT '',
  `pi_dotgif` varchar(255) NOT NULL DEFAULT '',
  `pi_options` varchar(255) NOT NULL DEFAULT '',
  `pi_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `pi_weight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pi_id`),
  KEY `pi_weight` (`pi_weight`),
  KEY `pi_type` (`pi_type`),
  KEY `pi_dirname` (`pi_dirname`),
  KEY `pi_file` (`pi_file`),
  KEY `pi_options` (`pi_options`),
  KEY `pi_enabled` (`pi_enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `apcal_ro_events` (
  `roe_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `roe_eventid` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `roe_number` int(10) NOT NULL DEFAULT '0',
  `roe_datelimit` int(10) NOT NULL DEFAULT '0',
  `roe_submitter` int(10) NOT NULL DEFAULT '0',
  `roe_date_created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`roe_id`),
  KEY `event` (`roe_eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE `apcal_ro_members` (
  `rom_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rom_eventid` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `rom_firstname` varchar(200) DEFAULT NULL,
  `rom_lastname` varchar(200) DEFAULT NULL,
  `rom_email` varchar(200) DEFAULT NULL,
  `rom_extrainfo1` varchar(200) DEFAULT NULL,
  `rom_extrainfo2` varchar(200) DEFAULT NULL,
  `rom_extrainfo3` varchar(200) DEFAULT NULL,
  `rom_extrainfo4` varchar(200) DEFAULT NULL,
  `rom_extrainfo5` varchar(200) DEFAULT NULL,
  `rom_submitter` int(10) NOT NULL DEFAULT '0',
  `rom_date_created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rom_id`),
  UNIQUE KEY `UNQ_EMAIL` (`rom_eventid`,`rom_email`),
  KEY `event` (`rom_eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE `apcal_ro_notify` (
  `ron_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ron_eventid` mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
  `ron_email` varchar(200) DEFAULT NULL,
  `ron_submitter` int(10) DEFAULT NULL,
  `ron_date_created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ron_id`),
  KEY `event` (`ron_eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;