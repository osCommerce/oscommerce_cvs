<?
  include('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'save') {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $orders_status_name_array = $HTTP_POST_VARS['orders_status_name'];
        $language_id = $languages[$i]['id'];
        $orders_status_name = $orders_status_name_array[$language_id];
        tep_db_query("update " . TABLE_ORDERS_STATUS . " set orders_status_name = '" . $orders_status_name . "' where orders_status_id = '" . $HTTP_POST_VARS['orders_status_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
      }
      header('Location: ' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action', 'info')) . 'info=' . $HTTP_POST_VARS['order_status_id'], 'NONSSL'));
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      if (tep_db_query("delete from " . ORDERS_STATUS . " where orders_status_id = '" . $HTTP_POST_VARS['orders_status_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action', 'info')), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action', 'info')) . 'error=DELETE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'insert') {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $orders_status_name_array = $HTTP_POST_VARS['orders_status_name'];
        $language_id = $languages[$i]['id'];
        $orders_status_name = $orders_status_name_array[$language_id];
        tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $orders_status_id . "', '" . $languages[$i]['id'] . "', '" . $orders_status_name . "')");
      }

      header('Location: ' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action', 'info')) . 'NONSSL'));
      tep_exit();
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body onload="SetFocus();" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" align="center"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></td>
                <td class="smallText"><b>&nbsp;<? echo TABLE_HEADING_ORDERS_STATUS; ?>&nbsp;</b></td>
                <td class="smallText" align="center"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $rows = 0;
  $orders_status_query_raw = "select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" .$languages_id . "' order by orders_status_id";
  $orders_status_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_status_query_raw, $orders_status_query_numrows);
  $orders_status_query = tep_db_query($orders_status_query_raw);
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $orders_status['orders_status_id'])) && (!$osInfo) && ($HTTP_GET_VARS['action'] != 'new')) {
      $osInfo_array = $orders_status;
      $osInfo = new ordersstatusInfo($osInfo_array);
    }

    if ($orders_status['orders_status_id'] == @$osInfo->id) {
      echo '              <tr bgcolor="#b0c8df" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, $orders_status['orders_status_id'], 'NONSSL') . '\'">' . "\n";
    } else {
      echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $orders_status['orders_status_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td class="smallText" align="center">&nbsp;<? echo $orders_status['orders_status_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<? echo $orders_status['orders_status_name']; ?>&nbsp;</td>
<?
    if ($orders_status['orders_status_id'] == @$osInfo->id) {
?>
                <td class="smallText" align="center">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?
    } else {
?>
                <td class="smallText" align="center">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $orders_status['orders_status_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?
    }
?>
              </tr>
<?
  }
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText">&nbsp;<? echo $orders_status_split->display_count($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?>&nbsp;</td>
                    <td class="smallText" align="right">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $orders_status_split->display_links($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td class="main" align="right" colspan="3"><a href="<? echo tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')) . 'action=new', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT); ?></a>&nbsp;</td>
              </tr>
<?
  if ($HTTP_GET_VARS['error']) {
?>
              <tr>
                <td class="main" colspan="3"><font color="#ff0000">&nbsp;<? echo ERROR_ACTION; ?>&nbsp;</font></td>
              </tr>
<?
  }
?>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $osInfo->name . '</b>&nbsp;');
?>
              <tr bgcolor="#81a2b6">
                <td>
                  <? new infoBoxHeading($info_box_contents); ?>
                </td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
/* here we display the appropiate info box on the right of the main table */
    switch ($HTTP_GET_VARS['action']) {
      case 'new':
        $form = '<form name="insert" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '" method="post">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INTRO . '<br>');

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_ORDERS_STATUS_NAME . ' (' . $languages[$i]['name'] . ')<br><input type="text" name="orders_status_name[' . $languages[$i]['id'] . ']"><br>');
        }
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_ORDERS_STATUS_ID . '<br>&nbsp;<input type="text" name="orders_status_id" value="' . $osInfo->id . '" size="2"><br>&nbsp;');
        $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;

      case 'edit':
        $form = '<form name="orders_status" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="original_orders_status_id" value="' . $osInfo->id . '">'  ."\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INTRO . '<br>&nbsp;');

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_ORDERS_STATUS_NAME . ' (' . $languages[$i]['name'] . ')<br>&nbsp;<input type="text" name="orders_status_name[' . $languages[$i]['id'] . ']" value="' . tep_get_orders_status_name($osInfo->id, $languages[$i]['id']) . '"><br>&nbsp;<br>&nbsp;');
        }

        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_ORDERS_STATUS_ID . '<br>&nbsp;<input type="text" name="orders_status_id" value="' . $osInfo->id . '" size="2"><br>&nbsp;');
   #    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_ORDERS_STATUS_NAME . '<br>&nbsp;<input type="text" name="orders_status_name" value="' . $osInfo->name . '"><br>&nbsp;<br>&nbsp;');
        $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;

      case 'delete':
        $form = '<form name="orders_status" action="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="orders_status_id" value="' . $osInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_INTRO . '<br>&nbsp;');
        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . tep_get_orders_status_name($osInfo->id, $languages[$i]['id']) . '&nbsp;(' . $languages[$i]['name'] . ')</b>');
        } 
   #    if ($osInfo->products_count > 0) $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $osInfo->products_count));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* display default info boxes */
        default:
          $info_box_contents = array();
          $languages = tep_get_languages();
          for ($i=0; $i<sizeof($languages); $i++) {
            $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_ORDERS_STATUS_NAME . ' (' . $languages[$i]['name'] . ')<br>&nbsp;' . tep_get_orders_status_name($osInfo->id, $languages[$i]['id']) . '<br>');
          }
          $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_ORDERS_STATUS_ID . '&nbsp;' . $osInfo->id . '<br>&nbsp;');
          $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
      } // end switch
// display box contents by creating an instance of "infoBox" (down a couple of lines)
?>
              <tr bgcolor="#b0c8df"><? echo $form; ?>
                <td>
                  <? new infoBox($info_box_contents); ?>
                </td>
              <? if ($form) echo '</form>'; ?></tr>
              <tr bgcolor="#b0c8df">
                <td><? echo tep_black_line(); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>