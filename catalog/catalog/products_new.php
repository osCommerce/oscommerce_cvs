<?php
/*
  $Id: products_new.php,v 1.3 2001/08/22 11:23:32 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

  $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>';
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<base href="<? echo (getenv('SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_products_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br>
<?php
  $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, s.specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by p.products_date_added DESC, pd.products_name";

  $products_new_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_PRODUCTS_NEW, $products_new_query_raw, $products_new_numrows);

  $products_new_query = tep_db_query($products_new_query_raw);
  while ($products_new = tep_db_fetch_array($products_new_query)) {
    $products_new_array[] = array('id' => $products_new['products_id'],
                                  'name' => $products_new['products_name'],
                                  'image' => $products_new['products_image'],
                                  'price' => $products_new['products_price'],
                                  'specials_price' => $products_new['specials_new_products_price'],
                                  'date_added' => tep_date_long($products_new['products_date_added']),
                                  'manufacturer' => $products_new['manufacturers_name']);
  }

  require(DIR_WS_MODULES  . 'products_new.php');
?>
        </td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
<?php
  if ($products_new_numrows > 0 && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText">&nbsp;<?php echo $products_new_split->display_count($products_new_numrows, MAX_DISPLAY_PRODUCTS_NEW, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $products_new_split->display_links($products_new_numrows, MAX_DISPLAY_PRODUCTS_NEW, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php
  require(DIR_WS_INCLUDES . 'column_right.php');
?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>