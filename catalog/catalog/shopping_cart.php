<?php
/*
  $Id: shopping_cart.php,v 1.53 2001/12/01 19:36:45 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

  $location = ' : <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
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
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_cart.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $cart_empty = ($cart->count_contents() > 0) ? 0 : 1;

  if (!$cart_empty) {
  //------------------------------------------------
  // Set up column widths and colspan
  //------------------------------------------------
    if (PRODUCT_LIST_MODEL) {
      $col_width = array ('width="7%"', 'width="8%"', 'width="12%"', 'width="61%"', 'width="12%"' );
      $colspan = 5;
    } else {
      $col_width = array ('width="7%"', 'width="8%"', 'width="73%"', 'width="12%"' );
      $colspan = 4;
    }
?>
<form name="cart_quantity" method="post" action="<?php echo tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product', 'NONSSL'); ?>">
          <tr>
            <td <?php $col_idx=0; echo $col_width[$col_idx++]; ?> align="center" class="smallText"><b>&nbsp;<?php echo TABLE_HEADING_REMOVE; ?>&nbsp;</b></td>
            <td <?php echo $col_width[$col_idx++]; ?> align="center" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_QUANTITY; ?>&nbsp;</td>
<?php
    if (PRODUCT_LIST_MODEL) {
?>
            <td <?php echo $col_width[$col_idx++]; ?> class="tableHeading">&nbsp;<?php echo TABLE_HEADING_MODEL; ?>&nbsp;</td>
<?php
    }
?>
            <td <?php echo $col_width[$col_idx++]; ?> class="tableHeading">&nbsp;<?php echo TABLE_HEADING_PRODUCTS; ?>&nbsp;</td>
            <td <?php echo $col_width[$col_idx++]; ?> align="right" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_TOTAL; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="<?php echo $colspan; ?>"><?php echo tep_black_line(); ?></td>
          </tr>
<?php
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $col_idx=0;
      $products_name = $products[$i]['name'];
      echo '          <tr>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="center" valign="top"><input type="checkbox" name="cart_delete[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="center" valign="top"><input type="text" name="cart_quantity[]" value="' . $products[$i]['quantity'] . '" maxlength="2" size="2"><input type="hidden" name="products_id[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
      if (PRODUCT_LIST_MODEL) echo '            <td ' . $col_width[$col_idx++] . ' valign="top" class="main">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '">' . $products[$i]['model'] . '</a>&nbsp;</td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' valign="top" class="main">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '"><b>' . $products_name . '</b></a>' . "\n";

      if (STOCK_CHECK == 'true') {
        echo check_stock($products[$i]['id'], $products[$i]['quantity']);
      }

//------display customer choosen option --------
      $attributes_exist = '0';
      if ($cart->contents[$products[$i]['id']]['attributes']) {
        $attributes_exist = '1';
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          echo "\n" . '<br><small><i>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp;' . $attributes_values['products_options_values_name'] . '</i></small>';
          echo '<input type="hidden" name="id[' . $products[$i]['id'] . '][' . $option . ']" value="' . $value . '">';
        }
      }
//------display customer choosen option eof-----
      echo '</td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="right" valign="top" class="main">&nbsp;<b>' . $currencies->format($products[$i]['quantity'] * $products[$i]['price']) . '</b>&nbsp;';
//------display customer choosen option --------
      if ($attributes_exist == '1') {
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_values_id = '" . $value . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          if ($attributes_values['options_values_price'] != '0') {
            echo "\n" . '<br><small><i>' . $attributes_values['price_prefix'] . $currencies->format($products[$i]['quantity'] * $attributes_values['options_values_price']) . '</i></small>&nbsp;';
          } else {
            echo "\n" . '<br>&nbsp;';
          }
        }
      }
//------display customer choosen option eof-----
      echo '</td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td colspan="<?php echo $colspan; ?>"><?php echo tep_black_line(); ?>
<?php
    if ($any_out_of_stock) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
            echo "<br><center><font size=\"1\" color=\"crimson\" face=\"verdana\"><center>".OUT_OF_STOCK_CAN_CHECKOUT."</font><br><br></center>";
      } else {
            echo "<br><center><font size=\"1\" color=\"crimson\" face=\"verdana\"><center>".OUT_OF_STOCK_CANT_CHECKOUT."</font><br><br></center>";
      }
    }
?></td>
          </tr>
          <tr>
            <td colspan="<?php echo $colspan; ?>" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" class="tableHeading">&nbsp;<?php echo SUB_TITLE_SUB_TOTAL; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<?php echo $currencies->format($cart->show_total()); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="<?php echo $colspan; ?>"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="<?php echo $colspan; ?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART); ?></td>
                <td align="right" class="main"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><?php echo tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT); ?></a></td>
              </tr>
            </table></td>
          </tr>
</form>
<?php
  } else {
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '" class="main">&nbsp;' . TEXT_CART_EMPTY . '&nbsp;</td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '">' . tep_black_line() . '</td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '" align="right" class="tableHeading"><br>&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>&nbsp;&nbsp;</td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
