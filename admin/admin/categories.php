<?php
/*
  $Id: categories.php,v 1.54 2001/06/05 23:35:14 bhalsted Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
// update category
      case 'save':            tep_db_query("update " . TABLE_CATEGORIES . " set sort_order = '" . $HTTP_POST_VARS['sort_order'] . "', parent_id = '" . $HTTP_POST_VARS['parent_id'] . "', date_added = '" . $HTTP_POST_VARS['date_added'] . "', last_modified = now() where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $categories_name_array = $HTTP_POST_VARS['categories_name'];
                                $language_id = $languages[$i]['id'];
                                $categories_name = $categories_name_array[$language_id];
                                tep_db_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_name = '" . $categories_name . "' where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
                              }

                              if ($categories_image != 'none') {
                                tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = 'images/" . $categories_image_name . "' where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                                $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $categories_image_name;
                                if (file_exists($image_location)) @unlink($image_location);
                                copy($categories_image, $image_location);
                              }

                              tep_redirect(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'info=' . $HTTP_POST_VARS['categories_id']);
                              break;
// delete category
      case 'deleteconfirm':   if ($HTTP_POST_VARS['categories_id']) {
                                tep_db_query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                                tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                              } elseif ($HTTP_POST_VARS['products_id']) {
                                $products_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                $products_categories = tep_db_fetch_array($products_categories_query);

                                if ($products_categories['total'] > 1) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "' and categories_id = '" . $current_category_id . "'");
                                } else {
                                  tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                  tep_db_query("delete from " . TABLE_PRODUCTS . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                  tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                  tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                }
                              }

                              tep_redirect(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')));
                              break;
// move category
      case 'moveconfirm':     if ($HTTP_POST_VARS['categories_id']) {
                                tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . $HTTP_POST_VARS['move_to_category_id'] . "', last_modified = now() where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                              } elseif ($HTTP_POST_VARS['products_id']) {
                                tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . $HTTP_POST_VARS['move_to_category_id'] . "' where products_id = '" . $HTTP_POST_VARS['products_id'] . "' and categories_id = '" . $current_category_id . "'");
                              }

                              tep_redirect(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')));
                              break;
// insert category
      case 'insert_category': tep_db_query("insert into " . TABLE_CATEGORIES . " (parent_id, sort_order, date_added) values ('" . $current_category_id . "', '" . $HTTP_POST_VARS['sort_order'] . "', now())");
                              $categories_id = tep_db_insert_id();

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $categories_name_array = $HTTP_POST_VARS['categories_name'];
                                $language_id = $languages[$i]['id'];
                                $categories_name = $categories_name_array[$language_id];
                                tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name) values ('" . $categories_id . "', '" . $languages[$i]['id'] . "', '" . $categories_name . "')");
                              }

                              if ($categories_image != 'none') {
                                tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = 'images/" . $categories_image_name . "' where categories_id = '" . $categories_id . "'");
                                $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $categories_image_name;
                                if (file_exists($image_location)) @unlink($image_location);
                                copy($categories_image, $image_location);
                              }

                              tep_redirect(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')));
                              break;
// insert product
      case 'insert_product':  $products_date_available = $HTTP_POST_VARS['year'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];

                              $products_date_available = (date('Ymd') < $products_date_available) ? $products_date_available : '';

                              tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model, products_image, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values ('" . $HTTP_POST_VARS['products_quantity'] . "', '" . $HTTP_POST_VARS['products_model'] . "', '" . $HTTP_POST_VARS['products_image'] . "', '" . $HTTP_POST_VARS['products_price'] . "', '" . $HTTP_POST_VARS['products_date_added'] . "', '" . $products_date_available . "', '" . $HTTP_POST_VARS['products_weight'] . "', '" . $HTTP_POST_VARS['products_status'] . "', '" . $HTTP_POST_VARS['products_tax_class_id'] . "', '" . $HTTP_POST_VARS['manufacturers_id'] . "')");
                              $new_products_id = tep_db_insert_id();
                              tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $new_products_id . "', '" . $current_category_id . "')");

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $language_id = $languages[$i]['id'];
                                tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url) values ('" . $new_products_id . "', '" . $language_id . "' , '".$HTTP_POST_VARS['products_name'][$language_id]."' , '".$HTTP_POST_VARS['products_description'][$language_id]."' , '".$HTTP_POST_VARS['products_url'][$language_id]."')");
                              }

                              tep_redirect(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')));
                              break;
// update product
      case 'update_product':  $products_date_available = $HTTP_POST_VARS['year'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];

                              $products_date_available = (date('Ymd') < $products_date_available) ? $products_date_available : '';

                              tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $HTTP_POST_VARS['products_quantity'] . "', products_model = '" . $HTTP_POST_VARS['products_model'] . "', products_image = '" . $HTTP_POST_VARS['products_image'] . "', products_price = '" . $HTTP_POST_VARS['products_price'] . "', products_date_available = '" . $products_date_available . "', products_weight = '" . $HTTP_POST_VARS['products_weight'] . "', products_tax_class_id = '" . $HTTP_POST_VARS['products_tax_class_id'] . "', products_status = '" . $HTTP_POST_VARS['products_status'] . "', manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "'");

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $language_id = $languages[$i]['id'];
                                $products_name = $HTTP_POST_VARS['products_name'][$language_id];
                                $products_description = $HTTP_POST_VARS['products_description'][$language_id];
                                $products_url = $HTTP_POST_VARS['products_url'][$language_id];
                                tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_name = '" . $products_name . "', products_description = '" . $products_description . "', products_url = '" . $products_url . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "' and language_id = '". $language_id . "'");
                              }

                              tep_redirect(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'pinfo=' . $HTTP_GET_VARS['pID']);
                              break;
// copy product to another cateogry
      case 'copy_to_confirm': tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['categories_id'] . "')");
                              tep_redirect(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')));
                              break;
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?
  if ($HTTP_GET_VARS['action'] == 'new_product') {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?
  }
?>
</head>
<body onload="SetFocus();" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="popupcalendar" class="text"></div>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="blacklink">' . TOP_BAR_TITLE . '</a>'; ?>
<?
// output a navigation path of categories entered
  if ($cPath) {
    if (!ereg('_', $cPath)) $cPath_array = array($cPath);
    $cPath_new = '';
    for($i=0;$i<sizeof($cPath_array);$i++) {
      if ($cPath_new == '') {
        $cPath_new .= $cPath_array[$i];
      } else {
        $cPath_new .= '_' . $cPath_array[$i];
      }
      if ($i != (sizeof($cPath_array)-1)) $cPath_back = $cPath_new; // for a back button function (search for it below)
      $parent_categories_name = tep_get_category_name($cPath_array[$i], $languages_id);
      echo ' -> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath_new, 'NONSSL') . '" class="blacklink">' . $parent_categories_name . '</a>';
    }
  }
?>
            &nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'new_product') {
    if ($HTTP_GET_VARS['pID']) {
      $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "'");
      $product = tep_db_fetch_array($product_query);

      $pInfo = new productInfo($product);
    } elseif ($HTTP_POST_VARS) {
/* not in use at the moment! this should be used when the user presses 'BACK' on the products preview page.. */
      $pInfo = new productInfo($HTTP_POST_VARS);
    } else {
      $pInfo = new productInfo(array());
    }

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_id");

    if ($parent_categories_name == '') $parent_categories_name = 'Top Level Categories';
?>
      <tr><form name="new_product" enctype="multipart/form-data" <? echo 'action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_product_preview', 'NONSSL') . '"'; ?> method="post">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo sprintf(TEXT_NEW_PRODUCT, $parent_categories_name); ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
	  <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_STATUS; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="radio" name="products_status" value="1" 
<?
	if (@$pInfo->status == '1' && $product['products_status'] == '1') {  
	  echo ' CHECKED';
	} ?>>&nbsp;<? echo TEXT_PRODUCT_AVAILABLE; ?>&nbsp;<input type="radio" name="products_status" value="0"
<?
	if (@$pInfo->status == '0' && $product['products_status'] == '0') {  
	  echo ' CHECKED';
	} ?>>&nbsp;<? echo TEXT_PRODUCT_NOT_AVAILABLE; ?>&nbsp;
	    </font></td>
          </tr>
          <tr>
            <td nowrap valign="middle"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br>&nbsp;<small>(dd/mm/yyyy)</small>&nbsp;</font></td>
            <td nowrap valign="middle"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input class="cal-TextBox" size="2" maxlength="2" type="text" name="day" value="<?php echo $pInfo->date_available_caljs_day; ?>"><input class="cal-TextBox" size="2" maxlength="2" type="text" name="month" value="<?php echo $pInfo->date_available_caljs_month; ?>"><input class="cal-TextBox" size="4" maxlength="4" type="text" name="year" value="<? echo $pInfo->date_available_caljs_year; ?>"><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_product','dteWhen','BTN_date');return false;"><img align="absmiddle" border="0" name="BTN_date" src="<?php echo DIR_WS_IMAGES; ?>cal_date_up.gif" width="22" height="17"></a>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_MANUFACTURER; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<select name="manufacturers_id"><option value=""></option><? while ($manufacturers = tep_db_fetch_array($manufacturers_query)) { echo '<option value="' . $manufacturers['manufacturers_id'] . '"'; if (@$pInfo->manufacturers_id == $manufacturers['manufacturers_id']) echo ' SELECTED'; echo '>' . $manufacturers['manufacturers_name'] . '</option>'; } ?></select>&nbsp;</font></td>
          </tr>
<?
    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
      echo '          <tr>' . "\n" .
           '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_PRODUCTS_NAME . ' (' . $languages[$i]['name'] . ')</font></td>' . "\n" .
           '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<input type="text" name="products_name[' . $languages[$i]['id'] . ']" value="' . tep_get_products_name($pInfo->id, $languages[$i]['id']) . '"></font></td>' . "\n" .
           '          </tr>' . "\n";
    }
?>
          <tr>
            <td colspan="2"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
<?
    for ($i=0; $i<sizeof($languages); $i++) {
      echo '          <tr>' . "\n" .
           '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_PRODUCTS_DESCRIPTION . ' (' . $languages[$i]['name'] . ')</font></td>' . "\n" .
           '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<textarea name="products_description[' . $languages[$i]['id'] . ']" cols="50" rows="10">' . tep_get_products_description($pInfo->id, $languages[$i]['id']) . '</textarea></font></td>' . "\n" .
           '          </tr>' . "\n";
    }
?>
          <tr>
            <td colspan="2"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_QUANTITY; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="products_quantity" value="<? echo @$pInfo->quantity; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_MODEL; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="products_model" value="<? echo @$pInfo->model; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_IMAGE; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="file" name="products_image" size="20">&nbsp;<br>&nbsp;<? echo @$pInfo->image; ?><input type="hidden" name="products_previous_image" value="<? echo @$pInfo->image; ?>"></font></td>
          </tr>
<?
    for ($i=0; $i<sizeof($languages); $i++) {
      echo '          <tr>' . "\n" .
           '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_PRODUCTS_URL . ' (' . $languages[$i]['name'] . ')</font></td>' . "\n" .
           '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<input type="text" name="products_url[' . $languages[$i]['id'] . ']" value="' . tep_get_products_url($pInfo->id, $languages[$i]['id']) . '">' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</font></td>' . "\n" .
           '          </tr>' . "\n";
    }
?>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_PRICE; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="products_price" value="<? echo @$pInfo->price; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_TAX_CLASS; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<select name="products_tax_class_id"><option value="0">None Selected</option><? while ($tax_class = tep_db_fetch_array($tax_class_query)) { echo '<option value="' . $tax_class['tax_class_id'] . '"'; if (@$pInfo->tax_class == $tax_class['tax_class_id']) echo ' SELECTED'; echo '>' . $tax_class['tax_class_title'] . '</option>'; } ?></select>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCTS_WEIGHT; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="products_weight" value="<? echo @$pInfo->weight; ?>">&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td nowrap align="right"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="hidden" name="products_date_added" value="<? if (@$pInfo->date_added) { echo $pInfo->date_added; } else { echo date('Ymd'); } ?>"><? echo tep_image_submit(DIR_WS_IMAGES . 'button_preview.gif', '66', '20', '0', IMAGE_PREVIEW); ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID', 'pinfo', 'info')) . 'pinfo=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>'; ?>&nbsp;</font></td>
      </form></tr>
<?
  } elseif ($HTTP_GET_VARS['action'] == 'new_product_preview') {
    if ($HTTP_POST_VARS) {
      $manufacturer_query = tep_db_query("select manufacturers_name, manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      $manufacturer = tep_db_fetch_array($manufacturer_query);

      $pInfo_array = tep_array_merge((array)$HTTP_POST_VARS, (array)$manufacturer);
      $pInfo = new productInfo($pInfo_array);

      // Copy image only if modified
      if ($products_image && ($products_image != 'none')) {
        $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $products_image_name;
        if (file_exists($image_location)) @unlink($image_location);
        copy($products_image, $image_location);
        $products_image_name = 'images/' . $products_image_name;
      } else {
        $products_image_name = $products_previous_image;
      }

    } else {
      $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_date_available, p.products_status, p.manufacturers_id, m.manufacturers_name, m.manufacturers_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.manufacturers_id = m.manufacturers_id and p.products_id = pd.products_id and pd.products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      $product = tep_db_fetch_array($product_query);

      $pInfo = new productInfo($product);
      $products_image_name = $pInfo->image;
    }

    $form_action = 'insert_product';
    if ($HTTP_GET_VARS['pID']) $form_action = 'update_product';
?>
      <form name="<? echo $form_action; ?>" enctype="multipart/form-data" <? echo 'action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post">
<?
    if ($HTTP_GET_VARS['read'] == 'only') {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;
<?
        echo tep_get_products_name($pInfo->id, $languages[$i]['id']) . ' (' . $languages[$i]['name'] . ')<br>&nbsp;@ ' . tep_currency_format($pInfo->price);
?>&nbsp;</font></td>
            <td align="right" nowrap><? echo tep_image(DIR_WS_CATALOG . $pInfo->manufacturers_image, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $pInfo->manufacturer); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td wrap><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">
<?
        echo tep_image(DIR_WS_CATALOG . $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0' . '" align="right" hspace="5" vspace="5', tep_get_products_name($pInfo->id, $languages[$i]['id'])) .
             ' (' . $languages[$i]['name'] . ')<br>' . tep_get_products_description($pInfo->id, $languages[$i]['id']) . '<br><br>';
?></font></td>
      </tr>
<?
        if ($pInfo->url) {
?>
      <tr>
        <td nowrap><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">
<?
          echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, tep_get_products_url($pInfo->id, $languages[$i]['id'])) . ' (' . $languages[$i]['name'] . ')<br>';
?></font></td>
      </tr>
<?
        }

        if ($pInfo->date_available > date('Ymd')) {
?>
      <tr>
        <td align="center" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->date_available)); ?></font></td>
      </tr>
<?
        } else {
?>
      <tr>
        <td align="center" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->date_added)); ?></font></td>
      </tr>
<?
        }
?>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
<?
      }
    } else {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>"><? echo $products_name[$languages[$i]['id']] . ' (' . $languages[$i]['name'] . ')<br>@ ' . tep_currency_format($pInfo->price); ?></font></td>
            <td align="right" nowrap><? echo tep_image(DIR_WS_CATALOG . $pInfo->manufacturers_image, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $pInfo->manufacturer); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td wrap><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo tep_image(DIR_WS_CATALOG . $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0' . '" align="right" hspace="5" vspace="5', $products_name[$languages[$i]['id']]) . ' (' . $languages[$i]['name'] . ')<br>' . $products_description[$languages[$i]['id']]; ?></font></td>
      </tr>
<?
        if ($pInfo->url) {
?>
      <tr>
        <td nowrap><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $products_url[$languages[$i]['id']]) . ' (' . $languages[$i]['name'] . ')'; ?></font></td>
      </tr>
<?
        }

        if ($pInfo->date_available > date('Ymd')) {
?>
      <tr>
        <td align="center" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->date_available)); ?></font></td>
      </tr>
<?
        } else {
?>
      <tr>
        <td align="center" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->date_added)); ?></font></td>
      </tr>
<?
        }
?>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
<?
      }
    }

    if ($HTTP_GET_VARS['read'] == 'only') {
      if ($HTTP_GET_VARS['origin']) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = tep_get_all_get_params(array('action', 'pID', 'read', 'pinfo', 'info')) . 'pinfo=' . $HTTP_GET_VARS['pID'];
      }
?>
      <tr>
        <td align="right" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', '66', '20', '0', IMAGE_BACK) . '</a>'; ?>&nbsp;</font></td>
      </tr>
<?
    } else {
?>
      <tr>
        <td align="right" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">
<?
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars(stripslashes($value)) . '">';
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        echo '<input type="hidden" name="products_name[' . $languages[$i]['id'] . ']" value="' . htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])) . '">' .
             '<input type="hidden" name="products_description[' . $languages[$i]['id'] . ']" value="' . htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])) . '">' .
             '<input type="hidden" name="products_url[' . $languages[$i]['id'] . ']" value="' . htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])) . '">';
      }
      echo '<input type="hidden" name="products_image" value="' . htmlspecialchars(stripslashes($products_image_name)) . '">';

      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=new_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', '66', '20', '0', IMAGE_BACK) . '</a>&nbsp;';
      if ($HTTP_GET_VARS['pID']) {
        echo tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_UPDATE);
      } else {
        echo tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT);
      }
      echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'info=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';
?>&nbsp;</font></td>
      </form></tr>
<?
    }
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
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
                <td width="10%" align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
                <td width="80%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?>&nbsp;</b></font></td>
                <td width="10%" align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
    $categories_count = 0;
    $rows = 0;
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['pinfo'] && !$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $categories['categories_id'])) && (!$cInfo) && (!$pInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
// count category childs
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
// count category proucts
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = tep_array_merge($categories, $category_childs, $category_products);
        $cInfo = new categoryInfo($cInfo_array);
      }

      if ($categories['categories_id'] == @$cInfo->id) {
        echo '              <tr bgcolor="#b0c8df" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id']), 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'info=' . $categories['categories_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td width="10%" align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $categories['categories_id']; ?>&nbsp;</font></td>
                <td width="80%" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id']), 'NONSSL') . '" class="blacklink"><u>' . $categories['categories_name'] . '</u></a>'; ?></b>&nbsp;</font></td>
<?
      if ($categories['categories_id'] == @$cInfo->id) {
?>
                <td width="10%" align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, ''); ?>&nbsp;</font></td>
<?
      } else {
?>
                <td width="10%" align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'info=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</font></td>
<?
      }
?>
              </tr>
<?
    }

    $products_count = 0;
//  $rows = 0; // this shouldnt be reset
    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by pd.products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['pinfo'] && !$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['pinfo'] == $products['products_id'])) && (!$pInfo) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(r.reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_EXTRA . " re where re.products_id = '" . $products['products_id'] . "' and re.reviews_id = r.reviews_id");
        if ($reviews_query != '') $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = tep_array_merge($products, $reviews);
        $pInfo = new productInfo($pInfo_array);
      }

      if ($products['products_id'] == @$pInfo->id) {
        echo '              <tr bgcolor="#b0c8df" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'pID=' . $products['products_id'] . '&action=new_product_preview&read=only', 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr bgcolor="#e9e9e9" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#e9e9e9\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'pinfo=' . $products['products_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td width="10%" align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products['products_id']; ?>&nbsp;</font></td>
                <td width="80%" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID', 'info', 'pinfo')) . 'pID=' . $products['products_id'] . '&action=new_product_preview&read=only', 'NONSSL') . '" class="blacklink"><u>' . $products['products_name'] . '</u></a>'; ?>&nbsp;</font></td>
<?
      if ($products['products_id'] == @$pInfo->id) {
?>
                <td width="10%" align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, ''); ?>&nbsp;</font></td>
<?
      } else {
?>
                <td width="10%" align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'pinfo=' . $products['products_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</font></td>
<?
      }
?>
              </tr>
<?
    }

    if ($rows > 0) {
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
    }
    if (!$cPath_back) {
      $cPath_back = '';
    } else {
      $cPath_back = 'cPath=' . $cPath_back;
    }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_CATEGORIES; ?> <? echo $categories_count; ?>&nbsp;<br>&nbsp;<? echo TEXT_PRODUCTS; ?>&nbsp;<? echo $products_count; ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? if ($cPath) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', '66', '20', '0', IMAGE_BACK) . '</a>&nbsp;'; ?><? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_category.gif', '103', '20', '0', IMAGE_NEW_CATEGORY) . '</a>'; ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_product.gif', '103', '20', '0', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</font></td>
                  </tr>
                </table></td>
              </tr>
<?
    if ($HTTP_GET_VARS['error']) {
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="3"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="#ff0000">&nbsp;<? echo ERROR_ACTION; ?>&nbsp;</font></td>
              </tr>
<?
    }
?>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
    $info_box_contents = array();
    if ($cInfo && !$pInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $cInfo->name . '</b>');
    if ($pInfo && !$cInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . tep_get_products_name($pInfo->id, $languages_id) . '</b>');
    if (!$pInfo && !$cInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . EMPTY_CATEGORY . '</b>');
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
/* edit category box contents */
      case 'edit_category':
        $form = '<form name="categories" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="date_added" value="' . $cInfo->date_added . '"><input type="hidden" name="categories_id" value="' . $cInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_EDIT_INTRO . '<br>');

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . ' (' . $languages[$i]['name'] . ')<br><input type="text" name="categories_name[' . $languages[$i]['id'] . ']" value="' . tep_get_category_name($cInfo->id, $languages[$i]['id']) . '"><br>');
        }

        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br><input type="file" name="categories_image" size="20" style="font-size:10px"><br>' . $cInfo->image . '<br><br>' . TEXT_EDIT_SORT_ORDER . '<br><input type="text" name="sort_order" size="2" value="' . $cInfo->sort_order . '"><br>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_EDIT_PARENT_ID . '<br><input type="text" name="parent_id" value="' . $cInfo->parent_id . '"><br>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');

        break;
/* delete category box contents */
      case 'delete_category':
        $form = '<form name="categories" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="categories_id" value="' . $cInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_CATEGORY_INTRO);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<b>' . $cInfo->name . '</b>');
        if ($cInfo->childs_count > 0) $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        if ( ($cInfo->products_count > 0) || ($cInfo->childs_count > 0) ) {
          $button = '';
        } else {
          $button = tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE);
        }
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . $button . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');

        break;
/* delete product box contents */
      case 'delete_product':
        $form = '<form name="products" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $pInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_PRODUCT_INTRO);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<b>' . $pInfo->name . '</b>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');

        break;
/* move category box contents */
      case 'move_category':
        $form = '<form name="categories" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=moveconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="categories_id" value="' . $cInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->name));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_MOVE, $cInfo->name) . '<br>&nbsp;' . tep_categories_pull_down('name="move_to_category_id" style="font-size:10px"', $cInfo->id));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<br>' . TEXT_MOVE_NOTE);
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');

        break;
/* move product box contents */
      case 'move_product':
        $products_categories_array = tep_products_categories_array($pInfo->id, true);

        $form = '<form name="products" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=moveconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $pInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->name));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_MOVE, $pInfo->name) . '<br>&nbsp;' . tep_categories_pull_down('name="move_to_category_id" style="font-size:10px"', $products_categories_array));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<br>' . TEXT_MOVE_NOTE);
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');

        break;
/* new category box contents */
      case 'new_category':
        $form = '<form name="insert_category" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=insert_category', 'NONSSL') . '" method="post">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_NEW_CATEGORY_INTRO . '<br>');

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_CATEGORIES_NAME . ' (' . $languages[$i]['name'] . ')<br><input type="text" name="categories_name[' . $languages[$i]['id'] . ']"><br>');
        }

        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br><input type="file" name="categories_image" size="20" style="font-size:10px"><br>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_SORT_ORDER . '<br><input type="text" name="sort_order" size="2"><br>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');

        break;
/* display copy_to info box */
      case 'copy_to':
        $products_categories_array = tep_products_categories_array($pInfo->id, true);

        $form = '<form name="copy_to" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=copy_to_confirm', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $pInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_COPY_TO_INTRO . '<br>&nbsp;');
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_CURRENT_CATEGORIES . '<br>&nbsp;' . tep_products_categories_info_box($pInfo->id));
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_CATEGORIES . '<br>&nbsp;' . tep_categories_pull_down('name="categories_id" style="font-size:10px"', $products_categories_array) . '<br>&nbsp;');
        $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_copy.gif', '66', '20', '0', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');

        break;
/* display default info boxes */
      default:
        if ($rows > 0) {
          if ($cInfo) { // category info box contents
            $info_box_contents = array();
            $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=edit_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=delete_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=move_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE) . '</a>');
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added) . '<br>&nbsp;' . TEXT_LAST_MODIFIED . tep_date_short($cInfo->last_modified));
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . tep_info_image($cInfo->image, $cInfo->name) . '<br>&nbsp;' . $cInfo->image);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>&nbsp;' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif ($pInfo) { // product info box contents
            $info_box_contents = array();
            $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_product&pID=' . $pInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=delete_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=move_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE) . '</a>');
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->date_added));
            if (date('Ymd') < $pInfo->date_available) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->date_available) . '<br>');
            $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_LAST_MODIFIED);
	        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS_STATUS . ' ' . $pInfo->status);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($pInfo->image, $pInfo->name) . '<br>&nbsp;' . $pInfo->image);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS_PRICE_INFO . ' ' . tep_currency_format($pInfo->price) . '<br>&nbsp;' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->quantity);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
            $info_box_contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=copy_to', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_copy_to.gif', '66', '20', '0', IMAGE_COPY_TO) . '</a>');
          }
        } else { // create-category-or-product box contents
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
        }
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
<?
  }
?>
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
