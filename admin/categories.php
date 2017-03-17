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
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

/**
 * @param $cat
 * @param $form_title
 * @param $action
 */

function display_edit_form($cat, $form_title, $action)
{
    global $cattree, $xoopsModuleConfig;

    // Beggining of XoopsForm
    $form = new XoopsThemeForm($form_title, 'MainForm', '');

    // Hidden
    $form->addElement(new XoopsFormHidden('action', htmlspecialchars($action, ENT_QUOTES)));
    $form->addElement(new XoopsFormHidden('cid', (int)$cat->cid));

    // Subject
    $form->addElement(new XoopsFormText(_AM_APCAL_CAT_TH_TITLE, 'cat_title', 60, 128, htmlspecialchars($cat->cat_title, ENT_QUOTES)), true);

    // Description
    $tarea_tray = new XoopsFormElementTray(_AM_APCAL_CAT_TH_DESC, '<br>');
    if (class_exists('XoopsFormEditor')) {
        $configs = array(
            'name'   => 'cat_desc',
            'value'  => htmlspecialchars($cat->cat_desc, ENT_QUOTES),
            'rows'   => 15,
            'cols'   => 60,
            'width'  => '100%',
            'height' => '400px',
            'editor' => 'tinymce'
        );
        $tarea_tray->addElement(new XoopsFormEditor('', 'cat_desc', $configs, false, $onfailure = 'textarea'));
    } else {
        $tarea_tray->addElement(new XoopsFormDhtmlTextArea('', 'cat_desc', htmlspecialchars($cat->cat_desc, ENT_QUOTES), 15, 60));
    }
    $form->addElement($tarea_tray);

    // Parent Category
    ob_start();
    $cattree->makeMySelBox('cat_title', 'weight', $cat->pid, 1, 'pid');
    $cat_selbox = ob_get_contents();
    ob_end_clean();
    $form->addElement(new XoopsFormLabel(_AM_APCAL_CAT_TH_PARENT, $cat_selbox));

    // Weight
    $form->addElement(new XoopsFormText(_AM_APCAL_CAT_TH_WEIGHT, 'weight', 6, 6, (int)$cat->weight), true);

    // Options
    $checkbox_tray       = new XoopsFormElementTray(_AM_APCAL_CAT_TH_OPTIONS, '<br>');
    $ismenuitem_checkbox = new XoopsFormCheckBox('', 'ismenuitem', (int)$cat->ismenuitem);
    $ismenuitem_checkbox->addOption(1, _AM_APCAL_CAT_TH_SUBMENU);
    $checkbox_tray->addElement($ismenuitem_checkbox);
    $canbemain_checkbox = new XoopsFormCheckBox('', 'canbemain', (int)$cat->canbemain);
    $canbemain_checkbox->addOption(1, _AM_APCAL_CANBEMAIN);
    $checkbox_tray->addElement($canbemain_checkbox);
    $form->addElement($checkbox_tray);

    // Color picker
    $color = isset($cat->color) ? $cat->color : $xoopsModuleConfig['apcal_allcats_color'];
    $form->addElement(new XoopsFormColorPicker(_AM_APCAL_COLOR, 'color', $color), false);

    // Last Modified
    $form->addElement(new XoopsFormLabel(_AM_APCAL_CAT_TH_LASTMODIFY, formatTimestamp($cat->udtstamp)));

    // Buttons
    $button_tray = new XoopsFormElementTray('', '&nbsp;');
    $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $button_tray->addElement(new XoopsFormButton('', 'reset', _CANCEL, 'reset'));
    $form->addElement($button_tray);

    // Ticket
    $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__);

    // End of XoopsForm
    $form->display();
}

// �ĥ꡼��ˤʤ�褦�ˡ�weight��Ʒ׻������ĥ꡼�ο�����¬�äƤ���
/**
 * @param $cat_table
 */
function rebuild_cat_tree($cat_table)
{
    global $conn, $xoopsDB;

    $rs      = $GLOBALS['xoopsDB']->query("SELECT cid,pid FROM $cat_table ORDER BY pid ASC,weight DESC");
    $cats[0] = array('cid' => 0, 'pid' => -1, 'next_key' => -1, 'depth' => 0);
    $key     = 1;
    while ($cat = $GLOBALS['xoopsDB']->fetchObject($rs)) {
        $cats[$key] = array('cid' => (int)$cat->cid, 'pid' => (int)$cat->pid, 'next_key' => $key + 1, 'depth' => 0);
        ++$key;
    }
    $sizeofcats = $key;

    $loop_check_for_key = 1024;
    for ($key = 1; $key < $sizeofcats; ++$key) {
        $cat    =& $cats[$key];
        $target =& $cats[0];
        if (--$loop_check_for_key < 0) {
            $loop_check = -1;
        } else {
            $loop_check = 4096;
        }

        while (1) {
            if ($cat['pid'] == $target['cid']) {
                $cat['depth']       = $target['depth'] + 1;
                $cat['next_key']    = $target['next_key'];
                $target['next_key'] = $key;
                break;
            } elseif (--$loop_check < 0) {
                $GLOBALS['xoopsDB']->query("UPDATE $cat_table SET pid='0' WHERE cid={$cat['cid']}");
                $cat['depth']       = 1;
                $cat['next_key']    = $target['next_key'];
                $target['next_key'] = $key;
                break;
            } elseif ($target['next_key'] < 0) {
                $cat_backup = $cat;
                array_splice($cats, $key, 1);
                array_push($cats, $cat_backup);
                --$key;
                break;
            }
            $target =& $cats[$target['next_key']];
        }
    }

    $cat =& $cats[0];
    for ($weight = 1; $weight < $sizeofcats; ++$weight) {
        $cat =& $cats[$cat['next_key']];
        $GLOBALS['xoopsDB']->query("UPDATE $cat_table SET weight=" . ($weight * 10) . ",cat_depth={$cat['depth']} WHERE cid={$cat['cid']}");
    }
}

require_once __DIR__ . '/admin_header.php';
//require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/../class/APCal.php';
require_once __DIR__ . '/../class/APCal_xoops.php';

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

// for "Duplicatable"
$moduleDirName = basename(dirname(__DIR__));
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";

// SERVER, GET �ѿ��μ���
$action = isset($_POST['action']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['action']) : '';
$done   = isset($_GET['done']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['done']) : '';
$disp   = isset($_GET['disp']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['disp']) : '';
$cid    = isset($_GET['cid']) ? (int)$_GET['cid'] : 0;

// MySQL�ؤ���³
$conn = $GLOBALS['xoopsDB']->conn;

// setting physical & virtual paths
$mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
$mod_url  = XOOPS_URL . "/modules/$moduleDirName";

// creating an instance of APCal
$cal = new APCal_xoops('', $xoopsConfig['language'], true);

// setting properties of APCal
$cal->conn = $conn;
include __DIR__ . '/../include/read_configs.php';
$cal->base_url    = $mod_url;
$cal->base_path   = $mod_path;
$cal->images_url  = "$mod_url/assets/images/$skin_folder";
$cal->images_path = "$mod_path/assets/images/$skin_folder";

// XOOPS��Ϣ�ν��
$myts          = MyTextSanitizer::getInstance();
$cattree       = new XoopsTree($cal->cat_table, 'cid', 'pid');
$gpermHandler = xoops_getHandler('groupperm');

// �ǡ����١��������ʤɤ���������
if ($action === 'insert') {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // ������Ͽ
    $sql  = "INSERT INTO $cal->cat_table SET ";
    $cols = array(
        'weight'     => 'I:N:0',
        'ismenuitem' => 'I:N:0',
        'canbemain'  => 'I:N:0',
        'cat_title'  => '255:J:1',
        'cat_desc'   => 'A:J:0',
        'pid'        => 'I:N:0'
    );
    $sql  .= $cal->get_sql_set($cols);
    $sql  .= ",cat_shorttitle='" . $cal->makeShort(utf8_decode($_POST['cat_title'])) . "'";
    $sql  .= ",color='" . $_POST['color'] . '\'';
    if (!$GLOBALS['xoopsDB']->query($sql)) {
        die($GLOBALS['xoopsDB']->error());
    }
    rebuild_cat_tree($cal->cat_table);
    $mes = urlencode(_AM_APCAL_MB_CAT_INSERTED);
    $cal->redirect("done=inserted&mes=$mes");
    exit;
} elseif ($action === 'update' && $_POST['cid'] > 0) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // ����
    $cid  = (int)$_POST['cid'];
    $sql  = "UPDATE $cal->cat_table SET ";
    $cols = array(
        'weight'     => 'I:N:0',
        'ismenuitem' => 'I:N:0',
        'canbemain'  => 'I:N:0',
        'cat_title'  => '255:J:1',
        'cat_desc'   => 'A:J:0',
        'pid'        => 'I:N:0'
    );
    $sql  .= $cal->get_sql_set($cols);
    $sql  .= ",cat_shorttitle='" . $cal->makeShort(utf8_decode($_POST['cat_title'])) . "'";
    $sql  .= ",color='" . $_POST['color'] . '\'';
    $sql  .= "WHERE cid='$cid'";
    if (!$GLOBALS['xoopsDB']->query($sql)) {
        die($GLOBALS['xoopsDB']->error());
    }
    rebuild_cat_tree($cal->cat_table);
    $mes = urlencode(_AM_APCAL_MB_CAT_UPDATED);
    $cal->redirect("done=updated&mes=$mes");
    exit;
} elseif (!empty($_POST['delcat'])) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // Delete
    $cid = (int)$_POST['delcat'];

    // Category2Group permission �κ�� (2.0.3 �����Ǥ⤦�ޤ�ư���褦��)
    // xoops_groupperm_deletebymoditem( $xoopsModule->mid() , 'apcal_cat' , $cid ) ;
    $criteria = new CriteriaCompo(new Criteria('gperm_modid', $xoopsModule->mid()));
    $criteria->add(new Criteria('gperm_name', 'apcal_cat'));
    $criteria->add(new Criteria('gperm_itemid', (int)$cid));
    $gpermHandler->deleteAll($criteria);

    // Category Notify �κ��
    // (ɬ�פǤ���г������٥�Ⱥ��ε�ǽ��)

    // �оݥ��ƥ��꡼�λҶ���WHERE����ɲä���Cat2Group Permission����
    $children = $cattree->getAllChildId($cid);
    $whr      = 'cid IN (';
    foreach ($children as $child) {
        // WHERE��ؤ��ɲ�
        $whr .= "$child,";
        // Category2Group permission �κ�� (2.0.3 �����Ǥ⤦�ޤ�ư���褦��)
        // xoops_groupperm_deletebymoditem( $xoopsModule->mid() , 'apcal_cat' , $child ) ;
        $criteria = new CriteriaCompo(new Criteria('gperm_modid', $xoopsModule->mid()));
        $criteria->add(new Criteria('gperm_name', 'apcal_cat'));
        $criteria->add(new Criteria('gperm_itemid', (int)$child));
        $gpermHandler->deleteAll($criteria);
    }
    $whr .= "$cid)";

    // cat�ơ��֥뤫��κ��
    if (!$GLOBALS['xoopsDB']->query("DELETE FROM $cal->cat_table WHERE $whr")) {
        die($GLOBALS['xoopsDB']->error());
    }
    rebuild_cat_tree($cal->cat_table);
    $mes = urlencode(sprintf(_AM_APCAL_FMT_CAT_DELETED, mysqli_affected_rows()));
    $cal->redirect("done=deleted&mes=$mes");
    exit;
} elseif (!empty($_POST['batch_update'])) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // �Хå����åץǡ���
    $affected = 0;
    foreach ($_POST['weights'] as $cid => $weight) {
        $weight  = (int)$weight;
        $cid     = (int)$cid;
        $enabled = !empty($_POST['enabled'][$cid]) ? 1 : 0;
        if (!$GLOBALS['xoopsDB']->query("UPDATE $cal->cat_table SET weight='$weight', enabled='$enabled' WHERE cid=$cid")) {
            die($GLOBALS['xoopsDB']->error());
        }
        $affected += mysqli_affected_rows();
    }
    if ($affected > 0) {
        rebuild_cat_tree($cal->cat_table);
    }
    $mes = urlencode(sprintf(_AM_APCAL_FMT_CAT_BATCHUPDATED, $affected));
    $cal->redirect("done=batch_updated&mes=$mes");
    exit;
}

// �ᥤ�������
xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));
// ɽ������ο���ʬ��
if ($disp === 'edit' && $cid > 0) {

    // ����оݥ��ƥ��꡼�ǡ����μ���
    $sql = "SELECT *,UNIX_TIMESTAMP(dtstamp) AS udtstamp FROM $cal->cat_table WHERE cid='$cid'";
    $crs = $GLOBALS['xoopsDB']->query($sql);
    $cat = $GLOBALS['xoopsDB']->fetchObject($crs);
    display_edit_form($cat, _AM_APCAL_MENU_CAT_EDIT, 'update');
} elseif ($disp === 'new') {

    // ��������Ʊ�����Υ��֥������Ȥ��Ѱ�

    /**
     * Class Dummy
     */
    class Dummy
    {
        public $cid        = 0;
        public $pid        = 0;
        public $cat_title  = '';
        public $cat_desc   = '';
        public $weight     = 0;
        public $ismenuitem = 0;
        public $canbemain  = 1;
        public $udtstamp   = 0;
    }

    $cat           = new Dummy();
    $cat->pid      = $cid;
    $cat->udtstamp = time();
    display_edit_form($cat, _AM_APCAL_MENU_CAT_NEW, 'insert');
} else {
    echo '<h4>' . _AM_APCAL_MENU_CATEGORIES . "</h4>\n";

    if (!empty($_GET['mes'])) {
        echo "<p><style='color: blue; '>" . htmlspecialchars($_GET['mes'], ENT_QUOTES) . '</style></p>';
    }

    echo "<p><a href='?disp=new&cid=0'>" . _AM_APCAL_MB_MAKETOPCAT . "<img src='../assets/images/cat_add.gif' width='18' height='15' alt='' /></a></p>\n";

    // ���ƥ��꡼�ǡ�������
    $cat_tree_array = $cattree->getChildTreeArray(0, 'weight ASC,cat_title');

    // TH Part
    echo "
    <form name='MainForm' action='' method='post' style='margin:10px;'>
    " . $xoopsGTicket->getTicketHtml(__LINE__) . "
    <input type='hidden' name='delcat' value='' />
    <table width='75%' class='outer' cellpadding='4' cellspacing='1'>
      <tr valign='middle'>
        <th>" . _AM_APCAL_CAT_TH_TITLE . '</th>
        <th>' . _AM_APCAL_CAT_TH_OPERATION . '</th>
        <th>' . _AM_APCAL_CAT_TH_ENABLED . '</th>
        <th>' . _AM_APCAL_CAT_TH_WEIGHT . '</th>
      </tr>
    ';

    // �ꥹ�Ƚ�����
    $oddeven = 'odd';
    foreach ($cat_tree_array as $cat_node) {
        $oddeven = ($oddeven === 'odd' ? 'even' : 'odd');
        extract($cat_node);

        $prefix         = str_replace('.', '&nbsp;--', substr($prefix, 1));
        $enable_checked = $enabled ? 'checked' : '';
        $cid            = (int)$cid;
        $cat_title      = $myts->htmlSpecialChars($cat_title);
        $del_confirm    = 'confirm("' . sprintf(_AM_APCAL_FMT_CATDELCONFIRM, $cat_title) . '")';
        echo "
      <tr>
        <td class='$oddeven' width='100%'><a href='?disp=edit&amp;cid=$cid'>$prefix&nbsp;$cat_title</a></td>
        <td class='$oddeven' align='center' nowrap='nowrap'>
          <a href='$mod_url/index.php?action=Edit&amp;cid=$cid' target='_blank'><img src='$cal->images_url/addevent.gif' border='0' width='14' height='12' /></a>
          &nbsp;
          <a href='?disp=edit&amp;cid=$cid'><img src='../assets/images/cat_edit.gif' width='18' height='15' alt='" . _AM_APCAL_MENU_CAT_EDIT . "' title='" . _AM_APCAL_MENU_CAT_EDIT . "' /></a>
          &nbsp;
          <a href='?disp=new&amp;cid=$cid'><img src='../assets/images/cat_add.gif' width='18' height='15' alt='" . _AM_APCAL_MENU_CAT_NEW . "' title='" . _AM_APCAL_MENU_CAT_NEW . "' /></a>
          &nbsp;
          <input type='button' value='" . _DELETE . "'  onclick='if ($del_confirm) {document.MainForm.delcat.value=\"$cid\"; submit();}' />
        </td>
        <td class='$oddeven' align='center'><input type='checkbox' name='enabled[$cid]' value='1' $enable_checked /></td>
        <td class='$oddeven' align='right'><input type='text' name='weights[$cid]' size='4' maxlength='6' value='$weight' /></td>
      </tr>\n";
    }

    // �ơ��֥�եå���
    echo "
      <tr>
        <td colspan='4' align='right' class='head'><input type='submit' name='batch_update' value='" . _AM_APCAL_BTN_UPDATE . "' /></td>
      </tr>
      <tr>
        <td colspan='8' align='right' valign='bottom' height='50'>" . _AM_APCAL_COPYRIGHT . '</td>
      </tr>
    </table>
    </form>
    ';
}

require_once __DIR__ . '/admin_footer.php';
