<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'insert') {
      tep_db_query("insert into " . TABLE_CURRENCIES . " (title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value) values ('" . $HTTP_POST_VARS['currency_title'] . "', '" . $HTTP_POST_VARS['currency_code'] . "', '" . $HTTP_POST_VARS['symbol_left'] . "', '" . $HTTP_POST_VARS['symbol_right'] . "', '" . $HTTP_POST_VARS['decimal_point'] . "', '" . $HTTP_POST_VARS['thousands_point'] . "', '" . $HTTP_POST_VARS['decimal_places'] . "', '" . $HTTP_POST_VARS['value'] . "')");
      header('Location: ' . tep_href_link(FILENAME_CURRENCIES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update " . TABLE_CURRENCIES . " set title = '" . $HTTP_POST_VARS['currency_title'] . "', code = '" . $HTTP_POST_VARS['currency_code'] . "', symbol_left = '" . $HTTP_POST_VARS['symbol_left'] . "', symbol_right = '" . $HTTP_POST_VARS['symbol_right'] . "', decimal_point = '" . $HTTP_POST_VARS['decimal_point'] . "', thousands_point = '" . $HTTP_POST_VARS['thousands_point'] . "', decimal_places = '" . $HTTP_POST_VARS['decimal_places'] . "', value = '" . $HTTP_POST_VARS['value'] . "' where currencies_id = '" . $HTTP_POST_VARS['currencies_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')), 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from " . TABLE_CURRENCIES . " where currencies_id = '" . $HTTP_POST_VARS['currencies_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action', 'info')), 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'update') {
      $currencies_query = tep_db_query("select currencies_id, code from " . TABLE_CURRENCIES);
      while ($currencies_values = tep_db_fetch_array($currencies_query)) {
        tep_db_query("update " . TABLE_CURRENCIES . " set value = '" . quotecurrency($currencies_values['code']) . "' where currencies_id = '" . $currencies_values['currencies_id'] . "'");
      }
      Header('Location: ' . tep_href_link(FILENAME_CURRENCIES));
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
<body onload="SetFocus();">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
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
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_CURRENCY_ID; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_CURRENCY_NAME; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_CURRENCY_CODES; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<? echo TABLE_HEADING_CURRENCY_VALUE; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $currencies_query_raw = "select currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES . " order by title";
  $currencies_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $currencies_query_raw, $currencies_query_numrows);
  $currencies_query = tep_db_query($currencies_query_raw);

  $rows = 0;
  while ($currencies = tep_db_fetch_array($currencies_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $currencies['currencies_id'])) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $cInfo = new currenciesInfo($currencies);
    }

    if ($currencies['currencies_id'] == @$cInfo->id) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $currencies['currencies_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td class="smallText">&nbsp;<? echo $currencies['currencies_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<? echo $currencies['title']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<? echo $currencies['code']; ?>&nbsp;</td>
                <td class="smallText" align="right">&nbsp;<? echo number_format($currencies['value'], 4); ?>&nbsp;</td>
<?
    if ($currencies['currencies_id'] == @$cInfo->id) {
?>
                <td align="center" class="smallText">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); ?>&nbsp;</td>
<?
    } else {
?>
                <td align="center" class="smallText">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $currencies['currencies_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?
    }
?>
              </tr>
<?
  }
?>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" class="smallText">&nbsp;<? echo $currencies_split->display_count($currencies_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CURRENCIES); ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $currencies_split->display_links($currencies_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
                  </tr>
<? 
  if (!$HTTP_GET_VARS['action']) {
?>
                 <tr>
                    <td class="main">&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action', 'info')) . 'action=update', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_update_currencies.gif', IMAGE_UPDATE_CURRENCIES); ?></a></td>
                    <td class="main" align="right"><a href="<? echo tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action', 'info')) . 'action=new', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_new_currency.gif', IMAGE_NEW_CURRENCY); ?></a>&nbsp;&nbsp;</td>
                  </tr>
<?
  }
?>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
  $info_box_contents = array();
  if ($cInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $cInfo->title . '</b>&nbsp;');
  if ((!$cInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_INFO_HEADING_NEW_CURRENCY . '</b>&nbsp;');

?>
              <tr class="boxHeading">
                <td><? new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'new') {
    $form = '<form name="currencies" action="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '" method="post">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_INSERT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_TITLE . '<br><input type="text" name="currency_title"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_CODE . '<br><input type="text" name="currency_code"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_SYMBOL_LEFT . '<br><input type="text" name="symbol_left"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_SYMBOL_RIGHT . '<br><input type="text" name="symbol_right"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_DECIMAL_POINT . '<br><input type="text" name="decimal_point"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_THOUSANDS_POINT . '<br><input type="text" name="thousands_point"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_DECIMAL_PLACES . '<br><input type="text" name="decimal_places"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_VALUE . '<br><input type="text" name="value"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="currencies" action="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="currencies_id" value="' . $cInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_TITLE . '<br><input type="text" name="currency_title" value="' . $cInfo->title . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_CODE . '<br><input type="text" name="currency_code" value="' . $cInfo->code . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_SYMBOL_LEFT . '<br><input type="text" name="symbol_left" value="' . $cInfo->symbol_left . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_SYMBOL_RIGHT . '<br><input type="text" name="symbol_right" value="' . $cInfo->symbol_right . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_DECIMAL_POINT . '<br><input type="text" name="decimal_point" value="' . $cInfo->decimal_point . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_THOUSANDS_POINT . '<br><input type="text" name="thousands_point" value="' . $cInfo->thousands_point . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_DECIMAL_PLACES . '<br><input type="text" name="decimal_places" value="' . $cInfo->decimal_places . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CURRENCY_VALUE . '<br><input type="text" name="value" value="' . $cInfo->value . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
    $form = '<form name="currencies" action="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="currencies_id" value="' . $cInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $cInfo->title . '</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CURRENCIES, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_CURRENCY_TITLE . '&nbsp;' . $cInfo->title);
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_CURRENCY_CODE . '&nbsp;' . $cInfo->code);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . '&nbsp;' . $cInfo->symbol_left);
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_CURRENCY_SYMBOL_RIGHT . '&nbsp;' . $cInfo->symbol_right);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_CURRENCY_DECIMAL_POINT . '&nbsp;' . $cInfo->decimal_point);
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_CURRENCY_THOUSANDS_POINT . '&nbsp;' . $cInfo->thousands_point);
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_CURRENCY_DECIMAL_PLACES . '&nbsp;' . $cInfo->decimal_places);
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_CURRENCY_VALUE . '&nbsp;' . number_format($cInfo->value, 4));
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_CURRENCY_EXAMPLE . '<br>&nbsp;' . tep_currency_format('30', true, CURRENCY_VALUE) . ' = ' . tep_currency_format('30', true, $cInfo->code));
  }
?>
              <tr><? echo $form; ?>
                <td class="box"><? new infoBox($info_box_contents); ?></td>
              <? if ($form) echo '</form>'; ?></tr>
              <tr>
                <td class="box"><? echo tep_black_line(); ?></td>
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
