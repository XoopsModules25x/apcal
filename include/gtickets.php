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
 *              (based on Marijuana's Oreteki XOOPS)
 *              nobunobu's suggestions are applied
 */

if (!class_exists('XoopsGTicket')) {
    /**
     * Class XoopsGTicket
     */
    class XoopsGTicket
    {
        public $_errors       = array();
        public $_latest_token = '';

        // render form as plain html

        /**
         * @param  string $salt
         * @param  int    $timeout
         * @param  string $area
         * @return string
         */
        public function getTicketHtml($salt = '', $timeout = 1800, $area = '')
        {
            return '<input type="hidden" name="XOOPS_G_TICKET" value="' . $this->issue($salt, $timeout, $area) . '" />';
        }

        // returns an object of XoopsFormHidden including theh ticket

        /**
         * @param  string $salt
         * @param  int    $timeout
         * @param  string $area
         * @return XoopsFormHidden
         */
        public function getTicketXoopsForm($salt = '', $timeout = 1800, $area = '')
        {
            return new XoopsFormHidden('XOOPS_G_TICKET', $this->issue($salt, $timeout, $area));
        }

        // add a ticket as Hidden Element into XoopsForm

        /**
         * @param        $form
         * @param string $salt
         * @param int    $timeout
         * @param string $area
         */
        public function addTicketXoopsFormElement(&$form, $salt = '', $timeout = 1800, $area = '')
        {
            $form->addElement(new XoopsFormHidden('XOOPS_G_TICKET', $this->issue($salt, $timeout, $area)));
        }

        // returns an array for xoops_confirm() ;

        /**
         * @param  string $salt
         * @param  int    $timeout
         * @param  string $area
         * @return array
         */
        public function getTicketArray($salt = '', $timeout = 1800, $area = '')
        {
            return array('XOOPS_G_TICKET' => $this->issue($salt, $timeout, $area));
        }

        // return GET parameter string.

        /**
         * @param  string $salt
         * @param  bool   $noamp
         * @param  int    $timeout
         * @param  string $area
         * @return string
         */
        public function getTicketParamString($salt = '', $noamp = false, $timeout = 1800, $area = '')
        {
            return ($noamp ? '' : '&amp;') . 'XOOPS_G_TICKET=' . $this->issue($salt, $timeout, $area);
        }

        // issue a ticket

        /**
         * @param  string $salt
         * @param  int    $timeout
         * @param  string $area
         * @return string
         */
        public function issue($salt = '', $timeout = 1800, $area = '')
        {
            global $xoopsModule;
            if ('' === $salt) {
                if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
                    // $salt = '$2y$07$' . strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
                    $salt = '$2y$07$' . str_replace('+', '.', base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)));
                }
            }
            // create a token
            list($usec, $sec) = explode(' ', microtime());
            $appendix_salt       = empty($_SERVER['PATH']) ? XOOPS_DB_NAME : $_SERVER['PATH'];
            $token               = crypt($salt . $usec . $appendix_salt . $sec, $salt);
            $this->_latest_token = $token;

            if (empty($_SESSION['XOOPS_G_STUBS'])) {
                $_SESSION['XOOPS_G_STUBS'] = array();
            }

            // limit max stubs 10
            if (count($_SESSION['XOOPS_G_STUBS']) > 10) {
                $_SESSION['XOOPS_G_STUBS'] = array_slice($_SESSION['XOOPS_G_STUBS'], -10);
            }

            // record referer if browser send it
            $referer = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['REQUEST_URI'];

            // area as module's dirname
            if (!$area && is_object(@$xoopsModule)) {
                $area = $xoopsModule->getVar('dirname');
            }

            // store stub
            $_SESSION['XOOPS_G_STUBS'][] = array(
                'expire'  => time() + $timeout,
                'referer' => $referer,
                'area'    => $area,
                'token'   => $token
            );

            // paid md5ed token as a ticket
            return md5($token . XOOPS_DB_PREFIX);
        }

        // check a ticket

        /**
         * @param  bool   $post
         * @param  string $area
         * @return bool
         */
        public function check($post = true, $area = '')
        {
            global $xoopsModule;

            $this->_errors = array();

            // CHECK: stubs are not stored in session
            if (empty($_SESSION['XOOPS_G_STUBS']) || !is_array($_SESSION['XOOPS_G_STUBS'])) {
                $this->clear();
                $this->_errors[] = 'Invalid Session';

                return false;
            }

            // get key&val of the ticket from a user's query
            if ($post) {
                $ticket = empty($_POST['XOOPS_G_TICKET']) ? '' : $_POST['XOOPS_G_TICKET'];
            } else {
                $ticket = empty($_GET['XOOPS_G_TICKET']) ? '' : $_GET['XOOPS_G_TICKET'];
            }

            // CHECK: no tickets found
            if (empty($ticket)) {
                $this->clear();
                $this->_errors[] = 'Irregular post found';

                return false;
            }

            // gargage collection & find a right stub
            $stubs_tmp                 = $_SESSION['XOOPS_G_STUBS'];
            $_SESSION['XOOPS_G_STUBS'] = array();
            foreach ($stubs_tmp as $stub) {
                // default lifetime 30min
                if ($stub['expire'] >= time()) {
                    if (md5($stub['token'] . XOOPS_DB_PREFIX) === $ticket) {
                        $found_stub = $stub;
                    } else {
                        // store the other valid stubs into session
                        $_SESSION['XOOPS_G_STUBS'][] = $stub;
                    }
                } else {
                    if (md5($stub['token'] . XOOPS_DB_PREFIX) === $ticket) {
                        // not CSRF but Time-Out
                        $timeout_flag = true;
                    }
                }
            }

            // CHECK: the right stub found or not
            if (empty($found_stub)) {
                $this->clear();
                if (empty($timeout_flag)) {
                    $this->_errors[] = 'Invalid Session';
                } else {
                    $this->_errors[] = 'Time out';
                }

                return false;
            }

            // set area if necessary
            // area as module's dirname
            if (!$area && is_object(@$xoopsModule)) {
                $area = $xoopsModule->getVar('dirname');
            }

            // check area or referer
            if (@$found_stub['area'] == $area) {
                $area_check = true;
            }
            if (!empty($found_stub['referer']) && true === strpos(@$_SERVER['HTTP_REFERER'], $found_stub['referer'])) {
                $referer_check = true;
            }

            // if ( empty( $area_check ) || empty( $referer_check ) ) { // restrict
            if (empty($area_check) && empty($referer_check)) { // loose
                $this->clear();
                $this->_errors[] = 'Invalid area or referer';

                return false;
            }

            // all green
            return true;
        }

        // clear all stubs
        public function clear()
        {
            $_SESSION['XOOPS_G_STUBS'] = array();
        }

        // Ticket Using

        /**
         * @return bool
         */
        public function using()
        {
            if (!empty($_SESSION['XOOPS_G_STUBS'])) {
                return true;
            } else {
                return false;
            }
        }

        // return errors

        /**
         * @param  bool $ashtml
         * @return array|string
         */
        public function getErrors($ashtml = true)
        {
            if ($ashtml) {
                $ret = '';
                foreach ($this->_errors as $msg) {
                    $ret .= "$msg<br>\n";
                }
            } else {
                $ret = $this->_errors;
            }

            return $ret;
        }

        // end of class
    }

    // create a instance in global scope
    $GLOBALS['xoopsGTicket'] = new XoopsGTicket();
}

if (!function_exists('admin_refcheck')) {

    //Admin Referer Check By Marijuana(Rev.011)
    /**
     * @param  string $chkref
     * @return bool
     */
    function admin_refcheck($chkref = '')
    {
        if (empty($_SERVER['HTTP_REFERER'])) {
            return true;
        } else {
            $ref = $_SERVER['HTTP_REFERER'];
        }
        $cr = XOOPS_URL;
        if ($chkref !== '') {
            $cr .= $chkref;
        }
        if (strpos($ref, $cr) !== 0) {
            return false;
        }

        return true;
    }
}
