<? include('includes/application_top.php'); ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr bgcolor="<? echo SUB_BAR_BACKGROUND_COLOR; ?>">
            <td nowrap><font face="<? echo SUB_BAR_FONT_FACE; ?>" size="<? echo SUB_BAR_FONT_SIZE; ?>" color="<? echo SUB_BAR_FONT_COLOR; ?>">&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
  if ($HTTP_GET_VARS['action'] == '') {
?>
          <tr>
            <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><a href="<? echo tep_href_link(FILENAME_MAIL, 'action=email_user', 'NONSSL'); ?>">eMail an Kunde(n) senden</a> | <a href="<? echo tep_href_link(FILENAME_MAIL, 'action=sendNewsletter', 'NONSSL'); ?>">Newsletter an Kunde(n) senden</a></td>
          </tr>
<?
  }

  if ($HTTP_GET_VARS['action'] == 'email_user') {
?>
          <tr>
            <td><table><form action="<? echo tep_href_link(FILENAME_MAIL, '', 'NONSSL'); ?>" method="get"><input type="hidden" name="action" value="send_email_to_user"><input type="hidden" name="all" value="0">
              <tr>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_CUSTOMER_NAME; ?></td>
<?
    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
?>
                <td><select class="textbox" name="customers_email_address"><option value=""><? echo TEXT_SELECTCUSTOMER; ?></option><option value="***"><? echo TEXT_ALLCUSTOMERS; ?></option><option value="**D"><? echo TEXT_NEWSLETTERCUSTOMERS; ?></option>
<?
    while(list($customers_email_address, $customers_firstname, $customers_lastname) = tep_db_fetch_array($mail_query)) {
      echo '<option value="' . $customers_email_address . '">' . $customers_lastname . ', ' . $customers_firstname . ' - (' . $customers_email_address . ')</option>';
    }
?>
                </select></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_EMAIL_FROM; ?></td>
                <td><input type="text" size="28" name="from" value="<? echo TEXT_EMAILFROM; ?>"></td>
              </tr>
              <tr>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_SUBJECT; ?></td>
                <td><input type="text" size="28" name="subject"></td>
              </tr>
              <tr>
                <td valign="top"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_MESSAGE; ?></td>
                <td><textarea wrap="virtual" cols="42" rows="12" name="message"></textarea></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><INPUT type="submit" value="<? echo TEXT_SEND_EMAIL; ?>"></form></td>
              </tr>
            </table></td>
          </tr>
<?
  }

  if ($HTTP_GET_VARS['action'] == 'send_email_to_user') {
?>
          <tr>
            <td><table cellpadding="0" border="0" cellspacing="0" width="100%">
<?
    if ($from=="") {
      $from = TEXT_EMAILFROM;
    }
    if ($customers_email_address =="") {
?>
              <tr>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b><? echo TEXT_NO_CUSTOMER_SELECTED; ?></b></td>
              </tr>
<?
    } else {
      if ($customers_email_address=="***") {
        $mail_query = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS);
        $mail_sent_to = TEXT_ALLCUSTOMERS;
      } elseif ($customers_email_address=="**D") {
        $mail_query = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter='1'");
        $mail_sent_to = TEXT_NEWSLETTERCUSTOMERS;
      } else {
        $mail_query_raw = "select customers_email_address, customers_lastname, customers_firstname from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $customers_email_address . "'";
        $mail_query = tep_db_query($mail_query_raw);
        $mail_sent_to = $customers_email_address;
      }
      $nb_emails = tep_db_num_rows($mail_query);
      while (list ($customers_email_address, $customers_lastname, $customers_firstname) = tep_db_fetch_array($mail_query)) {
    
        mail($customers_email_address, $subject, $message, 'Content-Type: text/plain; charset="iso-8859-15"' . "\n" . 'Content-Transfer-Encoding: 8bit' . "\n" . 'From: ' . $from);
?>
              <tr>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">eMail: <? echo $customers_email_address; ?></td>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_SUBJECT; ?><? echo $subject; ?></td>
              </tr>
<?
      }
?>
              <tr>
                <td colspan="2"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_MSGTO; ?> '<? echo $mail_sent_to; ?>', <? echo $nb_emails; ?> <? echo TEXT_EMAILSSENDED; ?></td>
              </tr>
<?
    }
?>
            </table></td>
          </tr>
<?
  }
?>
<!-- body_text_eof //-->
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
