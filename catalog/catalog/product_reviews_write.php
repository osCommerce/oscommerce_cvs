<?php
/*
  $Id: product_reviews_write.php,v 1.40 2001/12/20 14:36:50 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_PRODUCT_REVIEWS_WRITE . '&products_id=' . $HTTP_GET_VARS['products_id'], 'SSL'));
  }

  if (@$HTTP_GET_VARS['action'] == 'process') {
    $customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
    $customer_values = tep_db_fetch_array($customer);
    $date_now = date('Ymd');
    tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . $HTTP_GET_VARS['products_id'] . "', '" . $customer_id . "', '" . addslashes($customer_values['customers_firstname']) . ' ' . addslashes($customer_values['customers_lastname']) . "', '" . $HTTP_POST_VARS['rating'] . "', now())");
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . $insert_id . "', '" . $languages_id . "', '" . htmlspecialchars($HTTP_POST_VARS['review']) . "')");

    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, $HTTP_POST_VARS['get_params'], 'NONSSL'));
  }

// lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = tep_get_all_get_params();
  $get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
  $get_params = substr($get_params, 0, -1); //remove trailing &
  if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
  } else {
    $get_params_back = $get_params;
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);
  $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';

  $product = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "'");
  $product_values = tep_db_fetch_array($product);

  $customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
  $customer_values = tep_db_fetch_array($customer);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
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
    <td width="100%" valign="top"><form name="product_reviews_write" method="post" action="<?php echo tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL'); ?>" onSubmit="return checkForm();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main">&nbsp;<b><?php echo SUB_TITLE_PRODUCT; ?></b>&nbsp;<?php echo $product_values['products_name']; ?>&nbsp;</td>
              </tr>
              <tr>
                <td class="main">&nbsp;<b><?php echo SUB_TITLE_FROM; ?></b>&nbsp;<?php echo $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname']; ?>&nbsp;</td>
              </tr>
            </table></td>
            <td align="right"><br><?php echo tep_image(DIR_WS_IMAGES . $product_values['products_image'], $product_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="main">&nbsp;<b><?php echo SUB_TITLE_REVIEW; ?></b>&nbsp;</td>
            <td class="main"><textarea name="review" wrap="soft" cols="60" rows="15"></textarea></td>
          </tr>
          <tr>
            <td align="right" colspan="2" class="smallText">&nbsp;<?php echo TEXT_NO_HTML; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br>&nbsp;<b><?php echo SUB_TITLE_RATING; ?></b>&nbsp;&nbsp;<?php echo TEXT_BAD; ?>&nbsp;<input type="radio" name="rating" value="1">&nbsp;<input type="radio" name="rating" value="2">&nbsp;<input type="radio" name="rating" value="3">&nbsp;<input type="radio" name="rating" value="4">&nbsp;<input type="radio" name="rating" value="5">&nbsp;<?php echo TEXT_GOOD; ?>&nbsp;</td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table><input type="hidden" name="get_params" value="<?php echo $get_params; ?>"></form></td>
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
