<?php
/*
  $Id: login.php,v 1.54 2001/12/01 19:36:45 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action'] == 'process') {
    // Build a string to append to every URL
    $origin_info = '';
    if ($HTTP_POST_VARS['origin']) {
      $origin_info .= 'origin=' . $HTTP_POST_VARS['origin'] . '&';
    }
    if ($HTTP_POST_VARS['connection']) {
      $origin_info .= 'connection=' . $HTTP_POST_VARS['connection'] . '&';
    }
    if ($HTTP_POST_VARS['products_id']) {
      $origin_info .= 'products_id=' . $HTTP_POST_VARS['products_id'] . '&';
    }
    if ($HTTP_POST_VARS['order_id']) {
      $origin_info .= 'order_id=' . $HTTP_POST_VARS['order_id'] . '&';
    }
    if ($HTTP_POST_VARS['emailproduct']) {
      $origin_info .= 'emailproduct=' . $HTTP_POST_VARS['emailproduct'] . '&';
    }
    if ($HTTP_POST_VARS['send_to']) {
      $origin_info .= 'send_to=' . $HTTP_POST_VARS['send_to'] . '&';
    }
    if ($HTTP_POST_VARS['email_address']) {
      $origin_info .= 'email_address=' . $HTTP_POST_VARS['email_address'] . '&';
    }
    $origin_info = ereg_replace("&$", '', $origin_info);
    // Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "'");
    if ($HTTP_POST_VARS['user'] == 'new') {
      if (!tep_db_num_rows($check_customer_query)) {
        tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, $origin_info));
      } else {
        tep_redirect(tep_href_link(FILENAME_LOGIN, 'login=fail_email&' . $origin_info));
      }
    } else {
      if (tep_db_num_rows($check_customer_query)) {
        $check_customer = tep_db_fetch_array($check_customer_query);
        // Check that password is good
        $pass_ok = validate_password($HTTP_POST_VARS['password'], $check_customer['customers_password']);
        if ($pass_ok != true) {
          tep_redirect(tep_href_link(FILENAME_LOGIN, 'login=fail&' . $origin_info));
        } else {
          $customer_id = $check_customer['customers_id'];
          $customer_default_address_id = $check_customer['customers_default_address_id'];
          $customer_first_name = $check_customer['customers_firstname'];
          tep_session_register('customer_id');
          tep_session_register('customer_default_address_id');
          tep_session_register('customer_first_name');

          if ($HTTP_POST_VARS['setcookie'] == '1') {
            setcookie('email_address', $HTTP_POST_VARS['email_address'], time()+2592000);
            setcookie('password', $HTTP_POST_VARS['password'], time()+2592000);
            setcookie('first_name', $customer_first_name, time()+2592000);
          } elseif ( ($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password']) ) {
            setcookie('email_address', '');
            setcookie('password', '');
            setcookie('first_name', '');
          }

          $date_now = date('Ymd');
          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . $customer_id . "'");

// restore cart contents
          $cart->restore_contents();

          if (@$HTTP_POST_VARS['origin']) {
            if (@$HTTP_POST_VARS['products_id']) {
              tep_redirect(tep_href_link($HTTP_POST_VARS['origin'], 'products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
            } elseif (@$HTTP_POST_VARS['order_id']) {
              tep_redirect(tep_href_link($HTTP_POST_VARS['origin'], 'order_id=' . $HTTP_POST_VARS['order_id'], 'NONSSL'));
            } elseif (@$HTTP_POST_VARS['emailproduct']) {
              tep_redirect(tep_href_link($HTTP_POST_VARS['origin'], 'action=where&products_id=' . $HTTP_POST_VARS['emailproduct'] . '&send_to=' . $HTTP_POST_VARS['send_to'], 'NONSSL'));
            } else {
              $connection = ($HTTP_POST_VARS['connection']) ? $HTTP_POST_VARS['connection'] : 'NONSSL';
              tep_redirect(tep_href_link($HTTP_POST_VARS['origin'], '', $connection));
            }
          } else {
            tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
          }
        }
      } else {
        tep_redirect(tep_href_link(FILENAME_LOGIN, 'login=fail&' . $origin_info, 'NONSSL'));
      }
    }
  } else {
   require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
   $location = ' : <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<?php echo FILENAME_INFO_SHOPPING_CART; ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
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
            <td rowspan="2" align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><form name="login" method="post" action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process&email_address=' . $HTTP_POST_VARS['email_address'], 'NONSSL'); ?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($HTTP_GET_VARS['login'] == 'fail') {
?>
          <tr>
            <td colspan="2" class="smallText"><?php echo TEXT_LOGIN_ERROR; ?>&nbsp;<br>&nbsp;</td>
          </tr>
<?php
  } elseif ($HTTP_GET_VARS['login'] == 'fail_email') {
?>
          <tr>
            <td colspan="2" class="smallText"><?php echo TEXT_LOGIN_ERROR_EMAIL; ?>&nbsp;<br>&nbsp;</td>
          </tr>
<?php
  } elseif ($cart->count_contents()) {
?>
          <tr>
            <td colspan="2" class="smallText"><?php echo TEXT_VISITORS_CART; ?>&nbsp;<br>&nbsp;</td>
          </tr>
<?
  }
?>
          <tr>
            <td align="right" class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS2; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="email_address" maxlength="96" value="<?php if ($HTTP_GET_VARS['email_address']) echo $HTTP_GET_VARS['email_address']; elseif (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['email_address']; } ?>">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="main"><input type="radio" name="user" value="new"<?php if ((!$HTTP_COOKIE_VARS['email_address'] || !$HTTP_COOKIE_VARS['password']) && (!$HTTP_GET_VARS['email_address'])) { echo ' checked'; } ?>></td>
            <td class="main">&nbsp;<?php echo TEXT_NEW_CUSTOMER; ?>&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="main"><input type="radio" name="user" value="returning"<?php if (($HTTP_COOKIE_VARS['email_address'] && $HTTP_COOKIE_VARS['password']) || ($HTTP_GET_VARS['email_address'])) { echo ' checked'; } ?>></td>
            <td class="main">&nbsp;<?php echo TEXT_RETURNING_CUSTOMER; ?>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main">&nbsp;<input type="password" name="password" maxlength="40" value="<?php if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['password']; } ?>">&nbsp;</td>
          </tr>
          <tr><label for="setcookie">
            <td align="right" class="main"><input type="checkbox" name="setcookie" value="1" id="setcookie" <?php if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo 'CHECKED'; } ?>></td>
            <td class="main">&nbsp;<?php echo TEXT_COOKIE; ?></td>
          </label></tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><br><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top" class="smallText">&nbsp;<a href="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'NONSSL'); ?>"><?php echo TEXT_PASSWORD_FORGOTTEN; ?></a></td>
            <td align="right" class="smallText"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;</td>
          </tr>
        </table>
<?php 
  if ($HTTP_GET_VARS['origin']) { 
    echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; 
  }
  if ($HTTP_GET_VARS['connection']) { 
    echo '<input type="hidden" name="connection" value="' . $HTTP_GET_VARS['connection'] . '">'; 
  } 
  if ($HTTP_GET_VARS['products_id']) { 
    echo '<input type="hidden" name="products_id" value="' . $HTTP_GET_VARS['products_id'] . '">'; 
  }
  if ($HTTP_GET_VARS['send_to']) { 
    echo '<input type="hidden" name="send_to" value="' . $HTTP_GET_VARS['send_to'] . '">'; 
  } 
  if ($HTTP_GET_VARS['order_id']) { 
    echo '<input type="hidden" name="order_id" value="' . $HTTP_GET_VARS['order_id'] . '">'; 
  } 
  if ($HTTP_GET_VARS['emailproduct']) { 
    echo '<input type="hidden" name="emailproduct" value="' . $HTTP_GET_VARS['emailproduct'] . '">'; 
  } 
?></form></td>
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
<?php
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
