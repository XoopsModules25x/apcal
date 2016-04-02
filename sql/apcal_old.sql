# ------------------------------------------------------------------------ #
#               XOOPS - PHP Content Management System                      #
#                   Copyright (c) 2000-2016 XOOPS.org                           #
#                      <http://xoops.org/>                             #
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


# @copyright   XOOPS Project (http://xoops.org)
# @license     http://www.fsf.org/copyleft/gpl.html GNU public license
# @author      Antiques Promotion (http://www.antiquespromotion.ca)

CREATE TABLE `apcal_cat` (
  `cid`            SMALLINT(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `pid`            SMALLINT(5) UNSIGNED ZEROFILL NOT NULL DEFAULT '00000',
  `weight`         SMALLINT(5)                   NOT NULL DEFAULT '0',
  `exportable`     TINYINT(4)                    NOT NULL DEFAULT '1',
  `autocreated`    TINYINT(4)                    NOT NULL DEFAULT '0',
  `ismenuitem`     TINYINT(4)                    NOT NULL DEFAULT '0',
  `enabled`        TINYINT(4)                    NOT NULL DEFAULT '1',
  `cat_shorttitle` VARCHAR(255)                  NOT NULL DEFAULT '',
  `cat_title`      VARCHAR(255)                  NOT NULL DEFAULT '',
  `cat_desc`       TEXT                          NOT NULL,
  `color`          VARCHAR(7)                    NOT NULL DEFAULT '#5555AA',
  `dtstamp`        TIMESTAMP                     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cat_extkey0`    INT(10) UNSIGNED ZEROFILL     NOT NULL DEFAULT '0000000000',
  `cat_depth`      TINYINT(3) UNSIGNED           NOT NULL DEFAULT '0',
  `cat_style`      VARCHAR(255)                  NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`),
  KEY `weight` (`weight`),
  KEY `cat_depth` (`cat_depth`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `apcal_event` (
  `id`           INT(10) UNSIGNED ZEROFILL      NOT NULL AUTO_INCREMENT,
  `uid`          MEDIUMINT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT '00000000',
  `groupid`      SMALLINT(5) UNSIGNED ZEROFILL  NOT NULL DEFAULT '00000',
  `shortsummary` VARCHAR(255)                   NOT NULL DEFAULT '',
  `summary`      VARCHAR(255)                   NOT NULL DEFAULT '',
  `location`     VARCHAR(255)                   NOT NULL DEFAULT '',
  `url`          VARCHAR(255)                   NOT NULL DEFAULT '',
  `email`        VARCHAR(255)                   NOT NULL DEFAULT '',
  `gmlat`        FLOAT                          NOT NULL DEFAULT '0',
  `gmlong`       FLOAT                          NOT NULL DEFAULT '0',
  `gmzoom`       TINYINT(3) UNSIGNED            NOT NULL DEFAULT '0',
  `organizer`    VARCHAR(255)                   NOT NULL DEFAULT '',
  `sequence`     VARCHAR(255)                   NOT NULL DEFAULT '',
  `contact`      VARCHAR(255)                   NOT NULL DEFAULT '',
  `tzid`         VARCHAR(255)                   NOT NULL DEFAULT 'GMT',
  `description`  TEXT                           NOT NULL,
  `dtstamp`      TIMESTAMP                      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `categories`   VARCHAR(255)                   NOT NULL DEFAULT '',
  `transp`       TINYINT(4)                     NOT NULL DEFAULT '1',
  `priority`     TINYINT(4)                     NOT NULL DEFAULT '0',
  `admission`    TINYINT(4)                     NOT NULL DEFAULT '0',
  `class`        VARCHAR(255)                   NOT NULL DEFAULT 'PUBLIC',
  `rrule`        VARCHAR(255)                   NOT NULL DEFAULT '',
  `rrule_pid`    INT(8) UNSIGNED ZEROFILL       NOT NULL DEFAULT '00000000',
  `unique_id`    VARCHAR(255)                   NOT NULL DEFAULT '',
  `allday`       TINYINT(4)                     NOT NULL DEFAULT '0',
  `start`        INT(10) UNSIGNED               NOT NULL DEFAULT '0',
  `end`          INT(10) UNSIGNED               NOT NULL DEFAULT '0',
  `start_date`   DATE                                    DEFAULT NULL,
  `end_date`     DATE                                    DEFAULT NULL,
  `cid`          SMALLINT(5) UNSIGNED ZEROFILL  NOT NULL DEFAULT '00000',
  `comments`     MEDIUMINT(8) UNSIGNED          NOT NULL DEFAULT '0',
  `event_tz`     FLOAT(2, 1)                    NOT NULL DEFAULT '0.0',
  `server_tz`    FLOAT(2, 1)                    NOT NULL DEFAULT '0.0',
  `poster_tz`    FLOAT(2, 1)                    NOT NULL DEFAULT '0.0',
  `extkey0`      INT(10) UNSIGNED ZEROFILL      NOT NULL DEFAULT '0000000000',
  `extkey1`      INT(10) UNSIGNED ZEROFILL      NOT NULL DEFAULT '0000000000',
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
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `apcal_pictures` (
  `id`       INT(10) UNSIGNED          NOT NULL AUTO_INCREMENT,
  `event_id` INT(10) UNSIGNED ZEROFILL NOT NULL,
  `picture`  VARCHAR(255)              NOT NULL,
  `main_pic` TINYINT(1) UNSIGNED       NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `apcal_plugins` (
  `pi_id`         SMALLINT(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `pi_title`      VARCHAR(255)                  NOT NULL DEFAULT '',
  `pi_type`       VARCHAR(8)                    NOT NULL DEFAULT '',
  `pi_dirname`    VARCHAR(50)                   NOT NULL DEFAULT '',
  `pi_file`       VARCHAR(50)                   NOT NULL DEFAULT '',
  `pi_dotgif`     VARCHAR(255)                  NOT NULL DEFAULT '',
  `pi_options`    VARCHAR(255)                  NOT NULL DEFAULT '',
  `pi_enabled`    TINYINT(4)                    NOT NULL DEFAULT '0',
  `pi_weight`     SMALLINT(5) UNSIGNED          NOT NULL DEFAULT '0',
  `last_modified` TIMESTAMP                     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pi_id`),
  KEY `pi_weight` (`pi_weight`),
  KEY `pi_type` (`pi_type`),
  KEY `pi_dirname` (`pi_dirname`),
  KEY `pi_file` (`pi_file`),
  KEY `pi_options` (`pi_options`),
  KEY `pi_enabled` (`pi_enabled`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;
