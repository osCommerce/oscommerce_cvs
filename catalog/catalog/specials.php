<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
<?
    $new = tep_db_query("select p.products_id, p.products_name, p.products_price, p.products_image, s.specials_new_products_price from products p, specials s where  p.products_status = '1' and s.products_id = p.products_id order by s.specials_date_added DESC limit " . MAX_DISPLAY_SPECIAL_PRODUCTS);
    $row = 0;
    while ($new_values = tep_db_fetch_array($new)) {
      $row++;
      echo '            <td align="center">' . FONT_STYLE_MAIN . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_values['products_id'], 'NONSSL') . '">' . tep_image($new_values['products_image'], $new_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_values['products_id'], 'NONSSL') . '">' . $new_values['products_name'] . '</a><br><s>' . tep_currency_format($new_values['products_price']) . '</s>&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($new_values['specials_new_products_price']) . '</font></font></td>' . "\n";
      if ((($row / 3) == floor($row / 3))) {
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td>&nbsp;</td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
      }    
    }
?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><?php echo FONT_STYLE_MAIN; ?><a href="<? echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_main_menu.gif', IMAGE_MAIN_MENU); ?></a>&nbsp;&nbsp;</font></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_right.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
