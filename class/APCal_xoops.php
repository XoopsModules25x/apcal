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
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 */

if (!class_exists('APCal_xoops')) {
    /**
     * Class APCal_xoops
     */
    class APCal_xoops extends APCal
    {
        /**
         * @param $data
         * @return mixed
         */
        public function textarea_sanitizer_for_sql($data)
        {
            //  preventing double-addslashes()
            //  $myts = MyTextSanitizer::getInstance();
            //  return $myts->makeTareaData4Save($data);
            return $data;
        }

        /**
         * @param $data
         * @return mixed
         */
        public function textarea_sanitizer_for_show($data)
        {
            $myts = MyTextSanitizer::getInstance();

            return $myts->displayTarea($data);
        }

        /**
         * @param $data
         * @return mixed
         */
        public function textarea_sanitizer_for_edit($data)
        {
            $myts = MyTextSanitizer::getInstance();

            return $myts->makeTareaData4Edit($data);
        }

        /**
         * @param $data
         * @return mixed
         */
        public function textarea_sanitizer_for_export_ics($data)
        {
            $myts = MyTextSanitizer::getInstance();

            return $myts->displayTarea($data);
        }

        /**
         * @param $data
         * @return mixed
         */
        public function text_sanitizer_for_show($data)
        {
            $myts = MyTextSanitizer::getInstance();

            return $myts->htmlSpecialChars($data);
        }

        /**
         * @param $data
         * @return mixed
         */
        public function text_sanitizer_for_edit($data)
        {
            $myts = MyTextSanitizer::getInstance();

            return $myts->makeTboxData4Edit($data);
        }

        /**
         * @param         $name
         * @param         $ymd
         * @param  string $long_ymdn
         * @return string
         */
        public function get_formtextdateselect($name, $ymd, $long_ymdn = '')
        {
            require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

            // day of week starting
            $first_day = $this->week_start ? 1 : 0;

            if ($this->jscalendar === 'xoops') {
                $jstime = formatTimestamp($this->unixtime, 'F j Y, H:i:s');

                if ($this->week_start) {
                    $js_cal_week_start = 'true';
                }    // Monday
                else {
                    $js_cal_week_start = 'false';
                }                        // Sunday

                // <input type='reset' value='...' onclick='
                return "
            <input type='text' name='$name' id='$name' size='15' maxlength='15' value='$ymd' />
            <input type='image' src='$this->images_url/button_date_selecting.gif' onclick='

          var el = xoopsGetElementById(\"$name\");
          if (calendar != null) {
            calendar.hide();
            calendar.parseDate(el.value);
          } else {
            var cal = new Calendar($js_cal_week_start, new Date(\"$jstime\"), selected, closeHandler);
            calendar = cal;
            cal.setRange(2000, 2100);
            calendar.create();
            calendar.parseDate(el.value);
          }
          calendar.sel = el;
          calendar.showAtElement(el);
          Calendar.addEvent(document, \"mousedown\", checkCalendar);

          return false;

        ' />
        ";
            } else {
                return "
        <input type='text' name='$name' id='$name' size='12' maxlength='12' value='$ymd' />
        <img src='$this->images_url/button_date_selecting.gif' id='trigger_{$name}' style='cursor: pointer; vertical-align:bottom;' title='Date selector' />
        <span id='display_{$name}'>$long_ymdn</span>

        <script type='text/javascript'>
        Calendar.setup({
            inputField : '$name',
            button : 'trigger_{$name}',
            displayArea : 'display_{$name}',
            daFormat : '" . _APCAL_JSFMT_YMDN . "' ,
            ifFormat : '%Y-%m-%d',
            showsTime : false,
            align :'Br',
            step : 1 ,
            firstDay : $first_day ,
            singleClick : false
        });
        </script>
        ";
            }
        }

        /**
         * @param $uid
         * @return string
         */
        public function get_submitter_info($uid)
        {
            if ($uid <= 0) {
                return _GUESTS;
            }

            $poster = new XoopsUser($uid);

            // check if invalid uid
            if ($poster->uname() == '') {
                return '';
            }

            if ($this->nameoruname === 'uname') {
                $name = $poster->uname();
            } else {
                $name = trim($poster->name());
                if ($name == '') {
                    $name = $poster->uname();
                }
            }

            return "<a href='" . XOOPS_URL . "/userinfo.php?uid=$uid'>$name</a>";
        }

        // XOOPS���?�Х븡�����

        /**
         * @param $keywords
         * @param $andor
         * @param $limit
         * @param $offset
         * @param $uid
         * @return array
         */
        public function get_xoops_search_result($keywords, $andor, $limit, $offset, $uid)
        {
            // �����׻�
            $tzoffset = ($this->user_TZ - $this->server_TZ) * 3600;

            // ���ƥ��꡼��Ϣ��WHERE������
            $whr_categories = $this->get_where_about_categories();

            // CLASS��Ϣ��WHERE������
            $whr_class = $this->get_where_about_class();

            // ʸ�������
            if (!empty($keywords)) {
                switch (strtolower($andor)) {
                    case 'and':
                        $whr_text = '';
                        foreach ($keywords as $keyword) {
                            $whr_text .= "CONCAT(summary,' ',description) LIKE '%$keyword%' AND ";
                        }
                        $whr_text = substr($whr_text, 0, -5);
                        break;
                    case 'or':
                        $whr_text = '';
                        foreach ($keywords as $keyword) {
                            $whr_text .= "CONCAT(summary,' ',description) LIKE '%$keyword%' OR ";
                        }
                        $whr_text = substr($whr_text, 0, -4);
                        break;
                    default:
                        $whr_text = "CONCAT(summary,'  ',description) LIKE '%{$keywords[0]}%'";
                        break;
                }
            } else {
                $whr_text = '1';
            }

            // �桼��ID����
            if ($uid > 0) {
                $whr_uid = "uid=$uid";
            } else {
                $whr_uid = '1';
            }

            // XOOPS Search module
            $showcontext = empty($_GET['showcontext']) ? 0 : 1;
            $select4con  = $showcontext ? 'description' : "'' AS description";

            // SQLʸ����
            $sql = "SELECT id,uid,summary,UNIX_TIMESTAMP(dtstamp) AS udtstamp, start, end, allday, start_date, end_date, extkey0, $select4con FROM $this->table WHERE admission>0 AND (rrule_pid=0 OR rrule_pid=id) AND ($whr_categories) AND ($whr_class) AND ($whr_text) AND ($whr_uid) ORDER BY dtstamp DESC LIMIT $offset,$limit";
            // ������
            $rs = $GLOBALS['xoopsDB']->query($sql);

            $ret     = array();
            $context = '';
            $myts    = MyTextSanitizer::getInstance();
            while ($event = $GLOBALS['xoopsDB']->fetchObject($rs)) {
                if (isset($event->start_date)) {
                    $start_str = $event->start_date;
                } elseif ($event->allday) {
                    $start_str = $this->get_long_ymdn($event->start);
                } else {
                    $start_str = $this->get_long_ymdn($event->start + $tzoffset);
                }

                if (isset($event->end_date)) {
                    $end_str = $event->end_date;
                } elseif ($event->allday) {
                    $end_str = $this->get_long_ymdn($event->end - 300);
                } else {
                    $end_str = $this->get_long_ymdn($event->end + $tzoffset);
                }

                $date_desc = ($start_str == $end_str) ? $start_str : "$start_str - $end_str";

                // get context for module "search"
                if (function_exists('search_make_context') && $showcontext) {
                    $full_context = strip_tags($myts->displayTarea($event->description, 1, 1, 1, 1, 1));
                    if (function_exists('easiestml')) {
                        $full_context = easiestml($full_context);
                    }
                    $context = search_make_context($full_context, $keywords);
                }

                $ret[] = array(
                    'image'   => 'assets/images/apcal.gif',
                    'link'    => "index.php?action=View&amp;event_id=$event->id",
                    'title'   => "[$date_desc] $event->summary",
                    'time'    => $event->udtstamp,
                    'uid'     => $uid,
                    'context' => $context
                );
            }

            return $ret;
        }

        // Notifications
        // triggerEvent ���Ϥ�URI�ϡ�& �Ƕ��ڤ� (&amp; �ǤϤʤ�)
        /**
         * @param $event_id
         * @return bool
         */
        public function notify_new_event($event_id)
        {
            $rs    = $GLOBALS['xoopsDB']->query("SELECT summary,admission,categories,class,uid,groupid FROM $this->table WHERE id='$event_id'");
            $event = $GLOBALS['xoopsDB']->fetchObject($rs);

            // No notification if not admitted yet
            if (!$event->admission) {
                return false;
            }

            // Private events
            if ($event->class === 'PRIVATE') {
                if ($event->groupid > 0) {
                    $memberHandler = xoops_getHandler('member');
                    $user_list      = $memberHandler->getUsersByGroup($event->groupid);
                } else {
                    $user_list = array($event->uid);
                }
            } else {
                $user_list = array();
            }

            $notificationHandler = xoops_getHandler('notification');

            // �����٥�Ȥ���Ͽ�������ƥ��꡼�ˤΥȥꥬ��
            $notificationHandler->triggerEvent('global', 0, 'new_event', array(
                'EVENT_SUMMARY' => $event->summary,
                'EVENT_URI'     => "$this->base_url/index.php?action=View&event_id=$event_id"
            ), $user_list, null, 0);

            // �����٥�Ȥ���Ͽ�ʥ��ƥ��꡼��ˤΥȥꥬ��
            $cids = explode(',', $event->categories);
            foreach ($cids as $cid) {
                $cid = (int)$cid;
                if (isset($this->categories[$cid])) {
                    $notificationHandler->triggerEvent('category', $cid, 'new_event', array(
                        'EVENT_SUMMARY'  => $event->summary,
                        'CATEGORY_TITLE' => $this->text_sanitizer_for_show($this->categories[$cid]->cat_title),
                        'EVENT_URI'      => "$this->base_url/index.php?smode=List&cid=$cid"
                    ), $user_list, null, 0);
                }
            }

            return true;
        }

        // $this->caldate���ͽ��֥�å�������֤�

        /**
         * @param  string $get_target
         * @return array
         */
        public function get_blockarray_date_event($get_target = '')
        {
            // if( $get_target == '' ) $get_target = $_SERVER['SCRIPT_NAME'] ;

            // ������׻����Ĥġ�WHERE��δ�֤˴ؤ���������
            $tzoffset          = (int)(($this->user_TZ - $this->server_TZ) * 3600);
            $toptime_of_day    = $this->unixtime + $this->day_start - $tzoffset;
            $bottomtime_of_day = $toptime_of_day + 86400;
            $whr_term          = "(allday AND start<='$this->unixtime' AND end>'$this->unixtime') || ( ! allday AND start<'$bottomtime_of_day' AND (start='$toptime_of_day' OR end>'$toptime_of_day'))";

            // ���ƥ��꡼��Ϣ��WHERE������
            $whr_categories = $this->get_where_about_categories();

            // CLASS��Ϣ��WHERE������
            $whr_class = $this->get_where_about_class();

            // ����Υ������塼�����
            $yrs      = $GLOBALS['xoopsDB']->query("SELECT start,end,summary,id,uid,allday,location,contact,description,(start>='$toptime_of_day') AS is_start_date,(end<='$bottomtime_of_day') AS is_end_date FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start,end");
            $num_rows = $GLOBALS['xoopsDB']->getRowsNum($yrs);

            $block = array(
                'insertable'                      => $this->insertable,
                'num_rows'                        => $num_rows,
                'get_target'                      => $get_target,
                'images_url'                      => $this->images_url,
                'caldate'                         => $this->caldate,
                'lang_APCAL_MB_APCALCONTINUING'   => _APCAL_MB_APCALCONTINUING,
                'lang_APCAL_MB_APCALNOEVENT'      => _APCAL_MB_APCALNOEVENT,
                'lang_APCAL_MB_APCALADDEVENT'     => _APCAL_MB_APCALADDEVENT,
                'lang_APCAL_MB_APCALALLDAY_EVENT' => _APCAL_MB_APCALALLDAY_EVENT
            );

            while ($event = $GLOBALS['xoopsDB']->fetchObject($yrs)) {
                if (!$event->allday) {
                    // �̾磻�٥��
                    // $event->start,end �ϥ����л���  $start,$end �ϥ桼������
                    $start = $event->start + $tzoffset;
                    $end   = $event->end + $tzoffset;

                    // ����˳��Ϥ佪λ���뤫�ǥɥå�GIF���ؤ���
                    if ($event->is_start_date) {
                        $dot = 'dot_startday.gif';
                    } elseif ($event->is_end_date) {
                        $dot = 'dot_endday.gif';
                    } else {
                        $dot = 'dot_interimday.gif';
                    }

                    // $day_start ���꤬������Ρ�24:00�ʹߤν���
                    if ($event->is_start_date && $bottomtime_of_day - $event->start <= $this->day_start) {
                        $start_desc = $this->get_middle_hi($start, true);
                    } else {
                        $start_desc = $this->get_middle_hi($start);
                    }

                    if ($event->is_end_date) {
                        // $day_start ���꤬������Ρ�24:00�ʹߤν���
                        if ($bottomtime_of_day - $event->end <= $this->day_start) {
                            $end_desc = $this->get_middle_hi($end, true);
                        } else {
                            $end_desc = $this->get_middle_hi($end);
                        }
                    } else {
                        $end_desc = $this->get_middle_md($end);
                    }

                    // �̾磻�٥�Ȥ����󥻥å�
                    $block['events'][] = array(
                        'summary'       => $this->text_sanitizer_for_show($event->summary),
                        'location'      => $this->text_sanitizer_for_show($event->location),
                        'contact'       => $this->text_sanitizer_for_show($event->contact),
                        'description'   => $this->textarea_sanitizer_for_show($event->description),
                        'allday'        => $event->allday,
                        'start'         => $start,
                        'start_desc'    => $start_desc,
                        'end'           => $end,
                        'end_desc'      => $end_desc,
                        'id'            => $event->id,
                        'uid'           => $event->uid,
                        'dot_gif'       => $dot,
                        'is_start_date' => $event->is_start_date,
                        'is_end_date'   => $event->is_end_date
                    );
                } else {
                    // ����٥�Ȥ����󥻥å�
                    $block['events'][] = array(
                        'summary'       => $this->text_sanitizer_for_show($event->summary),
                        'location'      => $this->text_sanitizer_for_show($event->location),
                        'contact'       => $this->text_sanitizer_for_show($event->contact),
                        'description'   => $this->textarea_sanitizer_for_show($event->description),
                        'allday'        => $event->allday,
                        'start'         => $event->start,
                        'end'           => $event->end,
                        'id'            => $event->id,
                        'uid'           => $event->uid,
                        'dot_gif'       => 'dot_allday.gif',
                        'is_start_date' => $event->is_start_date,
                        'is_end_date'   => $event->is_end_date
                    );
                }
            }

            return $block;
        }

        // $this->caldate�ʹߤ�ͽ��֥�å�������֤�

        /**
         * @param  string $get_target
         * @param  int    $num
         * @param  bool   $for_coming
         * @param  int    $untildays
         * @return array
         */
        public function get_blockarray_coming_event($get_target = '', $num = 5, $for_coming = false, $untildays = 0)
        {
            // if( $get_target == '' ) $get_target = $_SERVER['SCRIPT_NAME'] ;
            $now = $for_coming ? time() : $this->unixtime + $this->day_start;

            // ������׻����Ƥ���
            $tzoffset = (int)(($this->user_TZ - $this->server_TZ) * 3600);

            if ($for_coming) {
                // �ֺ����ͽ��פΤߡ�����оݤ����ն����ǤϤʤ��������� (thx Chado)
                $whr_term = "end>'$now'";
            } elseif ($tzoffset == 0) {
                $whr_term = "end>'$now'";
            } else {
                // ������������ϡ�allday�ˤ�äƾ��ʬ��
                $whr_term = "(allday AND end>'$now') || ( ! allday AND ( start >= '$now' OR end>'" . ($now - $tzoffset) . "'))";
            }

            // ���ƥ��꡼��Ϣ��WHERE������
            $whr_categories = $this->get_where_about_categories();

            // CLASS��Ϣ��WHERE������
            $whr_class = $this->get_where_about_class();

            // ����μ���
            //            $yrs      = $xoopsDB->query("SELECT COUNT(*) FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class)");
            //            $num_rows = mysql_result($yrs, 0, 0);
            //            $yrs = $xoopsDB->query("SELECT start,end,summary,id,uid,allday,location,contact,description,mainCategory FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start LIMIT $num");

            $yrs       = $GLOBALS['xoopsDB']->query("SELECT COUNT(*) FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class)");
            $num_rows   = 0;
            $resultRow = $GLOBALS['xoopsDB']->fetchRow($yrs);
            if (false !== $resultRow && isset($resultRow[0])) {
                $num_rows = $resultRow[0];
            }
            $yrs = $GLOBALS['xoopsDB']->query("SELECT start,end,summary,id,uid,allday,location,contact,description,mainCategory FROM $this->table WHERE admission>0 AND ($whr_term) AND ($whr_categories) AND ($whr_class) ORDER BY start LIMIT $num");

            $block = array(
                'insertable'                       => $this->insertable,
                'num_rows'                         => $num_rows,
                'get_target'                       => $get_target,
                'images_url'                       => $this->images_url,
                'caldate'                          => $this->caldate,
                'lang_APCAL_MB_APCALCONTINUING'    => _APCAL_MB_APCALCONTINUING,
                'lang_APCAL_MB_APCALNOEVENT'       => _APCAL_MB_APCALNOEVENT,
                'lang_APCAL_MB_APCALADDEVENT'      => _APCAL_MB_APCALADDEVENT,
                'lang_APCAL_MB_APCALRESTEVENT_PRE' => _APCAL_MB_APCALRESTEVENT_PRE,
                'lang_APCAL_MB_APCALRESTEVENT_SUF' => _APCAL_MB_APCALRESTEVENT_SUF,
                'lang_APCAL_MB_APCALALLDAY_EVENT'  => _APCAL_MB_APCALALLDAY_EVENT
            );

            $count = 0;
            //            while ($event = $xoopsDB->fetchObject($yrs)) {
            while ($event = $GLOBALS['xoopsDB']->fetchObject($yrs)) {
                if (++$count > $num) {
                    break;
                }

                // ������$untildays�����Ǥ���С��������Ǥ��ڤ�
                if ($untildays > 0 && $event->start > $this->unixtime + $untildays * 86400) {
                    $num_rows = $count;
                    break;
                }

                // $event->start,end �ϥ����л���  $start,$end �ϥ桼������
                if ($event->allday) {
                    $can_time_disp  = false;
                    $start_for_time = $start_for_date = $event->start + $tzoffset;
                    $end_for_time   = $end_for_date = $event->end - 300 + $tzoffset;
                } else {
                    $can_time_disp  = $for_coming;
                    $start_for_time = $event->start + $tzoffset;
                    $start_for_date = $event->start + $tzoffset - $this->day_start;
                    $end_for_time   = $event->end + $tzoffset;
                    $end_for_date   = $event->end + $tzoffset - $this->day_start;
                }

                if ($event->start < $now) { // TODO zer0fill  $now + $tzoffset ���?��?
                    // already started
                    $distance   = 0;
                    $dot        = 'dot_started.gif';
                    $start_desc = '';
                    if ($event->end - $now < 86400 && $can_time_disp) {
                        if (date('G', $end_for_time) * 3600 <= $this->day_start) {
                            $end_desc = $this->get_middle_hi($end_for_time, true);
                        } else {
                            $end_desc = $this->get_middle_hi($end_for_time);
                        }
                    } else {
                        $end_desc = $this->get_middle_md($end_for_date);
                    }
                } elseif ($event->start - $now < 86400) {
                    // near event (24hour)
                    $dot = 'dot_today.gif';
                    if ($can_time_disp) {
                        if (date('G', $start_for_time) * 3600 < $this->day_start) {
                            $start_desc = $this->get_middle_hi($start_for_time, true);
                        } else {
                            $start_desc = $this->get_middle_hi($start_for_time);
                        }
                    } else {
                        $start_desc = $this->get_middle_md($start_for_date);
                    }
                    if ($event->end - $now < 86400 && $can_time_disp) {
                        if (date('G', $end_for_time) * 3600 <= $this->day_start) {
                            $end_desc = $this->get_middle_hi($end_for_time, true);
                        } else {
                            $end_desc = $this->get_middle_hi($end_for_time);
                        }
                        $distance = 1;
                    } else {
                        $end_desc = $this->get_middle_md($end_for_date);
                        $distance = 2;
                    }
                } else {
                    // far event (>1day)
                    $distance   = 3;
                    $dot        = 'dot_future.gif';
                    $start_desc = $this->get_middle_md($start_for_date);
                    $end_desc   = $this->get_middle_md($end_for_date);
                }

                $multiday = ((int)date('j', $end_for_time) > (int)date('j', $start_for_time)) ? true : false;

                $pic = $GLOBALS['xoopsDB']->fetchObject($GLOBALS['xoopsDB']->query("SELECT picture FROM {$this->pic_table} WHERE event_id={$event->id} AND main_pic=1 LIMIT 0,1"));
                $cat = $GLOBALS['xoopsDB']->fetchObject($GLOBALS['xoopsDB']->query("SELECT cat_title FROM {$this->cat_table} WHERE cid={$event->mainCategory} LIMIT 0,1"));

                $block['events'][] = array(
                    'summary'     => $this->text_sanitizer_for_show($event->summary),
                    'location'    => $this->text_sanitizer_for_show($event->location),
                    'contact'     => $this->text_sanitizer_for_show($event->contact),
                    'description' => $this->textarea_sanitizer_for_show($event->description),
                    'allday'      => $event->allday,
                    'start'       => $start_for_time,
                    'start_desc'  => $start_desc,
                    'end'         => $end_for_time,
                    'end_desc'    => $end_desc,
                    'id'          => $event->id,
                    'uid'         => $event->uid,
                    'dot_gif'     => $dot,
                    'distance'    => $distance,
                    'multiday'    => $multiday,
                    'picture'     => $pic ? $pic->picture : '',
                    'mainCat'     => $cat ? htmlentities($cat->cat_title, ENT_QUOTES, 'UTF-8') : ''
                );
            }

            $block['num_rows_rest'] = $num_rows - $count;

            return $block;
        }

        // ��������Ͽ���줿ͽ��֥�å�������֤�

        /**
         * @param  string $get_target
         * @param  int    $num
         * @return array
         */
        public function get_blockarray_new_event($get_target = '', $num = 5)
        {
            // if( $get_target == '' ) $get_target = $_SERVER['SCRIPT_NAME'] ;

            // tzoffset
            $tzoffset = ($this->user_TZ - $this->server_TZ) * 3600;

            // ���ƥ��꡼��Ϣ��WHERE������
            $whr_categories = $this->get_where_about_categories();

            // CLASS��Ϣ��WHERE������
            $whr_class = $this->get_where_about_class();

            // ��������˥������塼�����
            $yrs = $GLOBALS['xoopsDB']->query("SELECT id,uid,summary,UNIX_TIMESTAMP(dtstamp) AS udtstamp , start, end, allday, start_date, end_date FROM $this->table WHERE admission>0 AND ($whr_categories) AND ($whr_class) AND (rrule_pid=0 OR rrule_pid=id) ORDER BY dtstamp DESC");

            $num_rows = $GLOBALS['xoopsDB']->getRowsNum($yrs);

            $block = array(
                'insertable'                       => $this->insertable,
                'num_rows'                         => $num_rows,
                'get_target'                       => $get_target,
                'images_url'                       => $this->images_url,
                'caldate'                          => $this->caldate,
                'lang_APCAL_MB_APCALCONTINUING'    => _APCAL_MB_APCALCONTINUING,
                'lang_APCAL_MB_APCALNOEVENT'       => _APCAL_MB_APCALNOEVENT,
                'lang_APCAL_MB_APCALADDEVENT'      => _APCAL_MB_APCALADDEVENT,
                'lang_APCAL_MB_APCALRESTEVENT_PRE' => _APCAL_MB_APCALRESTEVENT_PRE,
                'lang_APCAL_MB_APCALRESTEVENT_SUF' => _APCAL_MB_APCALRESTEVENT_SUF,
                'lang_APCAL_MB_APCALALLDAY_EVENT'  => _APCAL_MB_APCALALLDAY_EVENT
            );

            $count = 0;
            while ($event = $GLOBALS['xoopsDB']->fetchObject($yrs)) {
                if (++$count > $num) {
                    break;
                }

                if (isset($event->start_date)) {
                    $start_str = $event->start_date;
                } elseif ($event->allday) {
                    $start_str = $this->get_long_ymdn($event->start);
                } else {
                    $start_str = $this->get_long_ymdn($event->start + $tzoffset);
                }

                if (isset($event->end_date)) {
                    $end_str = $event->end_date;
                } elseif ($event->allday) {
                    $end_str = $this->get_long_ymdn($event->end - 300);
                } else {
                    $end_str = $this->get_long_ymdn($event->end + $tzoffset);
                }

                $date_desc         = ($start_str == $end_str) ? $start_str : "$start_str - $end_str";
                $block['events'][] = array(
                    'summary'    => $this->text_sanitizer_for_show($event->summary),
                    'allday'     => $event->allday,
                    'start'      => $event->start,
                    'start_desc' => $start_str,
                    'end'        => $event->end,
                    'end_desc'   => $end_str,
                    'date_desc'  => $date_desc,
                    'post_date'  => formatTimestamp($event->udtstamp),
                    'uid'        => $event->uid,
                    'id'         => $event->id
                );
            }

            $block['num_rows_rest'] = $num_rows - $count;

            return $block;
        }

        // Get the events list view

        /**
         * @param         $tpl
         * @param  string $get_target
         * @return bool
         */
        public function assign_event_list(&$tpl, $get_target = '')
        {
            $pos = isset($_GET['pos']) ? (int)$_GET['pos'] : 0;
            $num = isset($_GET['num']) ? (int)$_GET['num'] : 20;

            $roimage = XOOPS_URL . '/modules/apcal/assets/images/regonline/regonline.png'; // added by goffy: image for online registration

            // �����Ƚ�
            $orders = array(
                'summary'      => _APCAL_TH_SUMMARY . ' ' . _APCAL_MB_APCALORDER_ASC,
                'summary DESC' => _APCAL_TH_SUMMARY . ' ' . _APCAL_MB_APCALORDER_DESC,
                'start'        => _APCAL_TH_STARTDATETIME . ' ' . _APCAL_MB_APCALORDER_ASC,
                'start DESC'   => _APCAL_TH_STARTDATETIME . ' ' . _APCAL_MB_APCALORDER_DESC,
                'dtstamp'      => _APCAL_TH_LASTMODIFIED . ' ' . _APCAL_MB_APCALORDER_ASC,
                'dtstamp DESC' => _APCAL_TH_LASTMODIFIED . ' ' . _APCAL_MB_APCALORDER_DESC,
                'uid'          => _APCAL_TH_SUBMITTER . ' ' . _APCAL_MB_APCALORDER_ASC,
                'uid DESC'     => _APCAL_TH_SUBMITTER . ' ' . _APCAL_MB_APCALORDER_DESC
            );
            if (isset($_GET['order']) && isset($orders[$_GET['order']])) {
                $order = $_GET['order'];
            } else {
                $order = 'start';
            }

            // tzoffset
            $tzoffset = ($this->user_TZ - $this->server_TZ) * 3600;

            $cat_desc = !empty($this->now_cid)
                        && !empty($this->categories[$this->now_cid]) ? $this->textarea_sanitizer_for_show($this->categories[$this->now_cid]->cat_desc) : '';

            $whr_categories = $this->get_where_about_categories();
            $whr_class      = $this->get_where_about_class();

            $categories_selform = $this->get_categories_selform($get_target);

            $ops = array(
                'after'  => _APCAL_MB_APCALOP_AFTER,
                'on'     => _APCAL_MB_APCALOP_ON,
                'before' => _APCAL_MB_APCALOP_BEFORE,
                'all'    => _APCAL_MB_APCALOP_ALL
            );

            $op             = empty($_GET['op']) ? '' : preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['op']);
            $tzoffset       = (int)(($this->user_TZ - $this->server_TZ) * 3600);
            $toptime_of_day = $this->unixtime + $this->day_start;
            switch ($op) {
                case 'all':
                    $whr_term = '1';
                    break;
                case 'before':
                    $whr_term = "(allday AND start<='$this->unixtime') || ( ! allday AND start<='" . ($toptime_of_day + 86400 - $tzoffset) . "')";
                    //$whr_term = "start<$this->unixtime" ;
                    break;
                default:
                case 'after':
                    $op       = 'after';
                    $whr_term = "(allday AND end>'$this->unixtime') || ( ! allday AND end>'" . ($toptime_of_day - $tzoffset) . "')";
                    //$whr_term = "end>$this->unixtime" ;
                    break;
                case 'on':
                    $whr_term = "(allday AND start<='$this->unixtime' AND end>'$this->unixtime') || ( ! allday AND start<='"
                                . ($toptime_of_day + 86400 - $tzoffset)
                                . "' AND end>'"
                                . ($toptime_of_day
                                   - $tzoffset)
                                . "')";
                    break;
            }

            // ���ձ黻�Ҥ���������
            $op_options = '';
            foreach ($ops as $op_id => $op_title) {
                if ($op_id == $op) {
                    $op_options .= "\t\t\t<option value='$op_id' selected>$op_title</option>\n";
                } else {
                    $op_options .= "\t\t\t<option value='$op_id'>$op_title</option>\n";
                }
            }

            // ǯ�������(2001��2020 �Ȥ���)
            $year_options = '';
            for ($y = 2001; $y <= 2020; ++$y) {
                if ($y == $this->year) {
                    $year_options .= "\t\t\t<option value='$y' selected>" . sprintf(strip_tags(_APCAL_FMT_YEAR), $y) . "</option>\n";
                } else {
                    $year_options .= "\t\t\t<option value='$y'>" . sprintf(strip_tags(_APCAL_FMT_YEAR), $y) . "</option>\n";
                }
            }

            // ��������
            $month_options = '';
            for ($m = 1; $m <= 12; ++$m) {
                if ($m == $this->month) {
                    $month_options .= "\t\t\t<option value='$m' selected>{$this->month_short_names[$m]}</option>\n";
                } else {
                    $month_options .= "\t\t\t<option value='$m'>{$this->month_short_names[$m]}</option>\n";
                }
            }

            // ��������
            $date_options = '';
            for ($d = 1; $d <= 31; ++$d) {
                if ($d == $this->date) {
                    $date_options .= "\t\t\t<option value='$d' selected>{$this->date_short_names[$d]}</option>\n";
                } else {
                    $date_options .= "\t\t\t<option value='$d'>{$this->date_short_names[$d]}</option>\n";
                }
            }

            $ymdo_selects = sprintf(_APCAL_FMT_YMDO, "<select name='apcal_year'>$year_options</select>", "<select name='apcal_month'>$month_options</select>",
                                    "<select name='apcal_date'>$date_options</select>", "<select name='op'>$op_options</select>");

            // �쥳���ɿ��μ���
            $whr      = "($whr_term) AND ($whr_categories) AND ($whr_class)";
            $yrs      = $GLOBALS['xoopsDB']->query("SELECT *,UNIX_TIMESTAMP(dtstamp) AS udtstamp , start, end, allday, start_date, end_date, extkey0 FROM $this->table WHERE $whr");
            $num_rows = $GLOBALS['xoopsDB']->getRowsNum($yrs);

            // �ܥ�����
            $yrs = $GLOBALS['xoopsDB']->query("SELECT *,UNIX_TIMESTAMP(dtstamp) AS udtstamp , start, end, allday, start_date, end_date, extkey0 FROM $this->table WHERE $whr ORDER BY $order LIMIT $pos,$num");

            // �ڡ���ʬ�����
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $nav      = new XoopsPageNav($num_rows, $num, $pos, 'pos', "smode=List&amp;cid=$this->now_cid&amp;num=$num&amp;order=$order&amp;op=$op&amp;caldate=$this->caldate");
            $nav_html = $nav->renderNav(10);
            if ($num_rows <= 0) {
                $nav_num_info = _NONE;
            } elseif ($pos + $num > $num_rows) {
                $nav_num_info = ($pos + 1) . "-$num_rows/$num_rows";
            } else {
                $nav_num_info = ($pos + 1) . '-' . ($pos + $num) . '/' . $num_rows;
            }

            // �����ѿ��Υ�������
            $tpl->assign(array(
                             'page_nav'              => $nav_html,
                             'page_nav_info'         => $nav_num_info,
                             'categories_selform'    => $categories_selform,
                             'cat_desc'              => $cat_desc,
                             'insertable'            => $this->insertable,
                             'get_target'            => $get_target,
                             'num'                   => $num,
                             'now_cid'               => $this->now_cid,
                             'num_rows'              => $num_rows,
                             'images_url'            => $this->images_url,
                             'mod_url'               => $this->base_url,
                             'caldate'               => $this->caldate,
                             'op'                    => $op,
                             'order'                 => $order,
                             'user_can_output_ics'   => $this->can_output_ics,
                             'print_link'            => "$this->base_url/print.php?cid=$this->now_cid&amp;smode=List&amp;num=$num&amp;pos=$pos&amp;order="
                                                        . urlencode($order)
                                                        . "&amp;caldate=$this->caldate",
                             'apcal_copyright'       => _MD_APCAL_COPYRIGHT,
                             'ymdo_selects'          => $ymdo_selects,
                             'calhead_bgcolor'       => $this->calhead_bgcolor,
                             'calhead_color'         => $this->calhead_color,
                             'alt_list'              => _APCAL_ICON_LIST,
                             'alt_daily'             => _APCAL_ICON_DAILY,
                             'alt_weekly'            => _APCAL_ICON_WEEKLY,
                             'alt_monthly'           => _APCAL_ICON_MONTHLY,
                             'alt_yearly'            => _APCAL_ICON_YEARLY,
                             'alt_print'             => _APCAL_BTN_PRINT,
                             'lang_checkeditems'     => _APCAL_MB_APCALLABEL_CHECKEDITEMS,
                             'lang_icalendar_output' => _APCAL_MB_APCALLABEL_OUTPUTICS,
                             'lang_button_export'    => _APCAL_BTN_EXPORT,
                             'lang_button_jump'      => _APCAL_BTN_JUMP,
                             'lang_order'            => $orders[$order],
                             'lang_summary'          => _APCAL_TH_SUMMARY,
                             'lang_startdatetime'    => _APCAL_TH_STARTDATETIME,
                             'lang_enddatetime'      => _APCAL_TH_ENDDATETIME,
                             'lang_location'         => _APCAL_TH_LOCATION,
                             'lang_contact'          => _APCAL_TH_CONTACT,
                             'lang_description'      => _APCAL_TH_DESCRIPTION,
                             'lang_categories'       => _APCAL_TH_CATEGORIES,
                             'lang_submitter'        => _APCAL_TH_SUBMITTER,
                             'lang_class'            => _APCAL_TH_CLASS,
                             'lang_rrule'            => _APCAL_TH_RRULE,
                             'lang_admissionstatus'  => _APCAL_TH_ADMISSIONSTATUS,
                             'lang_lastmodified'     => _APCAL_TH_LASTMODIFIED,
                             'lang_cursortedby'      => _APCAL_MB_APCALCURSORTEDBY,
                             'lang_sortby'           => _APCAL_MB_APCALSORTBY,
                             'ro_image'              => $roimage
                         ));

            // ���٥�ȥ�������롼��
            $count  = 0;
            $events = array();
            while ($event = $GLOBALS['xoopsDB']->fetchObject($yrs)) {
                if ($event->gmlat > 0 || $event->gmlong > 0) {
                    $this->gmPoints[] = array(
                        'summary'   => $event->summary,
                        'gmlat'     => $event->gmlat,
                        'gmlong'    => $event->gmlong,
                        'location'  => $event->location,
                        'contact'   => $event->contact,
                        'startDate' => date('j', $event->start),
                        'event_id'  => $event->id
                    );
                }
                if (++$count > $num) {
                    break;
                }

                // �Խ���ǽ���ɤ���
                $editable = ($this->isadmin || $event->uid == $this->user_id && $this->editable);
                // �Խ���ǽ�Ǥʤ�̤��ǧ�쥳���ɤ�ɽ�����ʤ�
                if (!$editable && $event->admission == 0) {
                    continue;
                }

                // ���ϻ���
                if (isset($event->start_date)) {
                    $start_date_desc = $event->start_date;
                    $start_time_desc = '';
                    $start           = 0;
                } elseif ($event->allday) {
                    $start_date_desc = $this->get_long_ymdn($event->start);
                    $start_time_desc = '';
                    $start           = $event->start;
                } else {
                    $start           = $event->start + $tzoffset;
                    $start_date_desc = $this->get_long_ymdn($start);
                    $start_time_desc = $this->get_middle_hi($start);
                }

                // ��λ����
                if (isset($event->end_date)) {
                    $end_date_desc = $event->end_date;
                    $end_time_desc = '';
                    $end           = 0x7fffffff;
                } elseif ($event->allday) {
                    $end_date_desc = $this->get_long_ymdn($event->end - 300);
                    $end_time_desc = '';
                    $end           = $event->end;
                } else {
                    $end           = $event->end + $tzoffset;
                    $end_date_desc = $this->get_long_ymdn($end);
                    $end_time_desc = $this->get_middle_hi($end);
                }

                // ����¾��ɽ����������
                $admission_status = $event->admission ? _APCAL_MB_APCALEVENT_ADMITTED : _APCAL_MB_APCALEVENT_NEEDADMIT;
                $last_modified    = $this->get_long_ymdn($event->udtstamp - (int)(($this->user_TZ - $this->server_TZ) * 3600));
                $description      = $this->textarea_sanitizer_for_show($event->description);
                $summary          = $this->text_sanitizer_for_show($event->summary);
                $location         = $this->text_sanitizer_for_show($event->location);
                $contact          = $this->text_sanitizer_for_show($event->contact);
                $eventURL         = $this->make_event_link($event->id, $get_target, date('Y-n-j', $event->start));
                // Get picture
                $pic     = $GLOBALS['xoopsDB']->fetchObject($GLOBALS['xoopsDB']->query("SELECT picture FROM {$this->pic_table} WHERE event_id={$event->id} AND main_pic=1 LIMIT 0,1"));
                $picture = $pic && $this->showPicList ? "<img src='" . XOOPS_UPLOAD_URL . "/apcal/{$pic->picture}' alt='{$summary}' height='50' style='vertical-align: middle;' />" : '';

                $events[] = array(
                    'count'           => $count,
                    'oddeven'         => $count & 1 == 1 ? 'odd' : 'even',
                    'eventURL'        => $eventURL,
                    'picture'         => $picture,
                    'summary'         => $summary,
                    'location'        => $location,
                    'contact'         => $contact,
                    'description'     => $description,
                    'admission'       => $admission_status,
                    'editable'        => $editable,
                    'allday'          => $event->allday,
                    'start'           => $start,
                    'start_date_desc' => $start_date_desc,
                    'start_time_desc' => $start_time_desc,
                    'end'             => $end,
                    'end_date_desc'   => $end_date_desc,
                    'end_time_desc'   => $end_time_desc,
                    'post_date'       => $last_modified,
                    'rrule'           => $this->rrule_to_human_language($event->rrule),
                    'uid'             => $event->uid,
                    'submitter_info'  => $this->get_submitter_info($event->uid),
                    'id'              => $event->id,
                    'target_id'       => ($event->rrule_pid > 0) ? $event->rrule_pid : $event->id,
                    'regonline'       => $event->extkey0 //added by goffy
                );
            }
            $tpl->assign('events', $events);

            $tpl->assign('YEARLYVIEW', $this->make_cal_link($get_target, 'Yearly', $this->now_cid, $this->caldate));
            $tpl->assign('MONTHLYVIEW', $this->make_cal_link($get_target, 'Monthly', $this->now_cid, $this->caldate));
            $tpl->assign('WEEKLYVIEW', $this->make_cal_link($get_target, 'Weekly', $this->now_cid, $this->caldate));
            $tpl->assign('DAILYVIEW', $this->make_cal_link($get_target, 'Daily', $this->now_cid, $this->caldate));
            $tpl->assign('isAdmin', $this->isadmin);
            $tpl->assign('showSubmitter', $this->nameoruname === 'none' ? false : true);

            return true;
        }

        // get public ICS via snoopy

        /**
         * @param         $uri
         * @param  bool   $force_http
         * @param  string $user_uri
         * @return string
         */
        public function import_ics_via_fopen($uri, $force_http = true, $user_uri = '')
        {
            $user_uri = empty($user_uri) ? '' : $uri;
            // changing webcal://* to http://*
            $uri = str_replace('webcal://', 'http://', $uri);

            if ($force_http) {
                if (0 !== strpos($uri, 'http://')) {
                    $uri = 'http://' . $uri;
                }
            }

            // temporary file for store ics via http
            $ics_cache_file = XOOPS_CACHE_PATH . '/apcal_getics_' . uniqid('');

            // http get via Snoopy
            $error_level_stored = error_reporting();
            error_reporting($error_level_stored & ~E_NOTICE);
            // includes Snoopy class for remote file access
            require_once XOOPS_ROOT_PATH . '/class/snoopy.php';
            $snoopy = new Snoopy;
            // TIMEOUT from config
            // $snoopy->read_timeout = $config['snoopy_timeout'] ;
            $snoopy->read_timeout = 10;
            // Set proxy if needed
            //if ( trim( $config['proxy_host'] ) != '' ) {
            //$snoopy->proxy_host = $config['proxy_host'] ;
            //$snoopy->proxy_port = $config['proxy_port'] > 0 ? (int)( $config['proxy_port'] ) : 8080 ;
            //$snoopy->user = $config['proxy_user'] ;
            //$snoopy->pass = $config['proxy_pass'] ;
            //}
            //URL fetch
            if (!$snoopy->fetch($uri) || !$snoopy->results) {
                return "-1:Could not open uri: $uri";
            }

            $data = $snoopy->results;
            error_reporting($error_level_stored);

            $fp = fopen($ics_cache_file, 'w');
            fwrite($fp, $data);
            fclose($fp);

            $ret = parent::import_ics_via_fopen($ics_cache_file, false, $uri);
            list($records, $calname, $tmpname) = explode(':', $ret, 3);
            @unlink($ics_cache_file);

            if ($records < 1) {
                return "$records:$calname:$uri";
            } else {
                return $ret;
            }
        }

        // returns assigned array for extensible mini calendar block

        /**
         * @param  int   $gifaday
         * @param  int   $just1gif
         * @param  array $plugins
         * @return array
         */
        public function get_minical_ex($gifaday = 2, $just1gif = 0, $plugins = array())
        {
            $db   = XoopsDatabaseFactory::getDatabaseConnection();
            $myts = MyTextSanitizer::getInstance();

            $tzoffset_s2u = (int)(($this->user_TZ - $this->server_TZ) * 3600);
            $now          = time();
            $user_now_Ynj = date('Y-n-j', $now + $tzoffset_s2u);

            // prev_month points the tail, next_month points the head
            $prev_month = date('Y-n-j', mktime(0, 0, 0, $this->month, 0, $this->year));
            $next_month = date('Y-n-j', mktime(0, 0, 0, $this->month + 1, 1, $this->year));

            $block = array(
                'xoops_url' => XOOPS_URL,
                'mod_url'   => $this->base_url,
                'root_url'  => '',

                'skinpath'         => $this->images_url,
                'frame_css'        => $this->frame_css,
                'month_name'       => $this->month_middle_names[$this->month],
                'year_month_title' => sprintf(_APCAL_FMT_YEAR_MONTH, $this->year, $this->month_middle_names[$this->month]),
                'prev_month'       => $prev_month,
                'next_month'       => $next_month,
                'lang_prev_month'  => _APCAL_MB_APCALPREV_MONTH,
                'lang_next_month'  => _APCAL_MB_APCALNEXT_MONTH,

                'calhead_bgcolor' => $this->calhead_bgcolor,
                'calhead_color'   => $this->calhead_color
            );

            $first_date = getdate(mktime(0, 0, 0, $this->month, 1, $this->year));
            $date       = (-$first_date['wday'] + $this->week_start - 7) % 7;
            $wday_end   = 7 + $this->week_start;

            // Loop of weeknames
            $daynames = array();
            for ($wday = $this->week_start; $wday < $wday_end; ++$wday) {
                if ($wday % 7 == 0) {
                    //  Sunday
                    $bgcolor = $this->sunday_bgcolor;
                    $color   = $this->sunday_color;
                } elseif ($wday == 6) {
                    //  Saturday
                    $bgcolor = $this->saturday_bgcolor;
                    $color   = $this->saturday_color;
                } else {
                    // Weekday
                    $bgcolor = $this->weekday_bgcolor;
                    $color   = $this->weekday_color;
                }

                // assigning weeknames
                $daynames[] = array(
                    'bgcolor' => $bgcolor,
                    'color'   => $color,
                    'dayname' => $this->week_short_names[$wday % 7]
                );
            }
            $block['daynames'] = $daynames;

            // get the result of plugins
            $plugin_returns   = array();
            $tzoffset_s2u     = (int)(($this->user_TZ - $this->server_TZ) * 3600);
            $block['plugins'] = $plugins;
            foreach ($plugins as $plugin) {
                $plugin_fullpath = $this->base_path . '/' . $this->plugins_path_monthly . '/' . $plugin['file'];
                if (file_exists($plugin_fullpath)) {
                    include $plugin_fullpath;
                }
            }

            // Loop of week (row)
            $weeks = array();
            for ($week = 0; $week < 6; ++$week) {
                $days = array();
                // Loop of day (col)
                for ($wday = $this->week_start; $wday < $wday_end; ++$wday) {
                    ++$date;

                    $time = mktime(0, 0, 0, $this->month, $date, $this->year);

                    // Out of the month
                    if (!checkdate($this->month, $date, $this->year)) {
                        $days[] = array(
                            'date' => date('j', $time),
                            'type' => 0
                        );
                        continue;
                    }

                    $link = "$this->year-$this->month-$date";

                    // COLORS of days
                    if (isset($this->holidays[$link])) {
                        // Holiday
                        $bgcolor = $this->holiday_bgcolor;
                        $color   = $this->holiday_color;
                    } elseif ($wday % 7 == 0) {
                        // Sunday
                        $bgcolor = $this->sunday_bgcolor;
                        $color   = $this->sunday_color;
                    } elseif ($wday == 6) {
                        // Saturday
                        $bgcolor = $this->saturday_bgcolor;
                        $color   = $this->saturday_color;
                    } else {
                        // Weekday
                        $bgcolor = $this->weekday_bgcolor;
                        $color   = $this->weekday_color;
                    }

                    // Hi-Lighting the SELECTED DATE
                    if ($link == $user_now_Ynj) {
                        $bgcolor = $this->targetday_bgcolor;
                    }

                    // Preparing the returns from plugins
                    $ex = empty($plugin_returns[$date]) ? array() : array_slice($plugin_returns[$date], 0, $gifaday);
                    // if( ! empty( $ex ) ) var_dump( $ex ) ;

                    // Assigning attribs of the day
                    $days[] = array(
                        'bgcolor' => $bgcolor,
                        'color'   => $color,
                        'link'    => $link,
                        'date'    => $date,
                        'type'    => 1,
                        'ex'      => $ex
                    );
                }
                $weeks[] = $days;
            }
            $block['weeks'] = $weeks;

            return $block;
        }

        // ���ꤵ�줿type�Υץ饰����������֤�

        /**
         * @param $type
         * @return array
         */
        public function get_plugins($type)
        {
            global $xoopsDB, $xoopsUser;

            // MyTextSanitizer
            $myts = MyTextSanitizer::getInstance();

            // allowed modules
            $modulepermHandler = xoops_getHandler('groupperm');
            $groups             = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
            $allowed_mids       = $modulepermHandler->getItemIds('module_read', $groups);

            // plugins
            $plugins = array();
            $prs     = $GLOBALS['xoopsDB']->query("SELECT pi_title,pi_dirname AS dirname,pi_file AS file,pi_dotgif AS dotgif,pi_options AS options FROM $this->plugin_table WHERE pi_type='"
                                                  . addslashes($type)
                                                  . "' AND pi_enabled ORDER BY pi_weight");
            while ($plugin = $GLOBALS['xoopsDB']->fetchArray($prs)) {
                $dirname4sql = addslashes($plugin['dirname']);
                $mrs         = $GLOBALS['xoopsDB']->query('SELECT mid,name FROM ' . $GLOBALS['xoopsDB']->prefix('modules') . " WHERE dirname='$dirname4sql'");
                if ($mrs && $GLOBALS['xoopsDB']->getRowsNum($mrs)) {
                    list($mid, $name) = $GLOBALS['xoopsDB']->fetchRow($mrs);
                    if (!in_array($mid, $allowed_mids)) {
                        continue;
                    }
                    $plugin['pi_title'] = $myts->htmlSpecialChars($plugin['pi_title']);
                    $plugin['name']     = $myts->htmlSpecialChars($name);
                    $plugin['mid']      = $mid;
                    $plugins[]          = $plugin;
                }
            }

            return $plugins;
        }

        // The End of Class
    }
}
