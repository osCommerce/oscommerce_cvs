<?php
/*
  $Id: install_4.php,v 1.1 2002/01/02 13:02:39 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<p><span class="pageHeading">osCommerce</span><br><font color="#9a9a9a">Open Source E-Commerce Solutions</font></p>

<p class="pageTitle">New Install</p>

<p><b>Step 2: osCommerce Configuration</b></p>

<?php
  $dir_fs_www_root_array = explode('/', dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']));
  $dir_fs_www_root = array();
  for ($i=0; $i<sizeof($dir_fs_www_root_array)-2; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_www_root = implode('/', $dir_fs_www_root);

  $dir_ws_www_root_array = explode('/', dirname($HTTP_SERVER_VARS['REQUEST_URI']));
  $dir_ws_www_root = array();
  for ($i=0; $i<sizeof($dir_ws_www_root_array)-1; $i++) {
    $dir_ws_www_root[] = $dir_ws_www_root_array[$i];
  }
  $dir_ws_www_root = implode('/', $dir_ws_www_root);

  if ( (!$fp = @fopen($dir_fs_www_root . $HTTP_POST_VARS['DIR_WS_CATALOG'] . 'includes/configure.php', 'w')) || (!$fp = @fopen($dir_fs_www_root . $HTTP_POST_VARS['DIR_WS_ADMIN'] . 'includes/configure.php', 'w')) ) {
?>

<p>The following error has occurred:</p>

<p><div class="boxMe"><b>The configuration files do not exist, or permission levels are not set.</b><br><br>Please perform the following actions:
<ul class="boxMe"><li>cd <?php echo $dir_fs_www_root . $HTTP_POST_VARS['DIR_WS_CATALOG']; ?>includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul>
<ul class="boxMe"><li>cd <?php echo $dir_fs_www_root . $HTTP_POST_VARS['DIR_WS_ADMIN']; ?>includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul></div></p>

<form name="install" action="install.php?step=4" method="post">

<?php
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if ($key != 'x' && $key != 'y') {
        if (is_array($value)) {
          for ($i=0; $i<sizeof($value); $i++) {
            echo osc_draw_hidden_field($key . '[]', $value[$i]);
          }
        } else {
          echo osc_draw_hidden_field($key, $value);
        }
      }
    }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="image" src="images/button_retry.gif" border="0" alt="Retry"></td>
  </tr>
</table>

</form>

<?php
  } else {
?>

<form name="install" action="install.php?step=5" method="post">

<p>The following configuration values will be written to:<br><br><?php echo $dir_fs_www_root . $HTTP_POST_VARS['DIR_WS_CATALOG']; ?>includes/configure.php<br><?php echo $dir_fs_www_root . $HTTP_POST_VARS['DIR_WS_ADMIN']; ?>includes/configure.php</p>

<p><b>1. Please enter your web server information:</b></p>

<p><b>HTTP Server</b><br><?php echo osc_draw_input_field('HTTP_SERVER', 'http://' . $HTTP_SERVER_VARS['HTTP_HOST']); ?><br>
The web server can be in the form of a hostname, such as <i>http://www.myserver.com</i>, or as an IP address, such as <i>http://192.168.0.1</i>.</p>

<p><b>HTTPS Server</b><br><?php echo osc_draw_input_field('HTTPS_SERVER', 'https://' . $HTTP_SERVER_VARS['HTTP_HOST']); ?><br>
The secure web server can be in the form of a hostname, such as <i>https://www.myserver.com</i>, or as an IP address, such as <i>https://192.168.0.1</i>.</p>

<p><?php echo osc_draw_checkbox_field('ENABLE_SSL', 'true'); ?> <b>Enable SSL Connections</b><br>
Enable Secure Connections With SSL (HTTPS)</p>

<p><b>WWW Catalog Directory</b><br><?php echo osc_draw_input_field('DIR_WS_CATALOG'); ?><br>
The directory where the osCommerce Catalog module resides, usually <i>/catalog/</i>.</p>

<p><b>WWW Administration Tool Directory</b><br><?php echo osc_draw_input_field('DIR_WS_ADMIN'); ?><br>
The directory where the osCommerce Administration Tool resides, usually <i>/admin/</i>.</p>

<p><b>2. Please enter your database server information:</b></p>

<p><b>Database Server</b><br><?php echo osc_draw_input_field('DB_SERVER'); ?><br>
The database server can be in the form of a hostname, such as <i>db1.myserver.com</i>, or as an IP address, such as <i>192.168.0.1</i>.</p>

<p><b>Username</b><br><?php echo osc_draw_input_field('DB_SERVER_USERNAME'); ?><br>
The username is used to connect to the database server. An example username is <i>mysql_10</i>.<br><br>Note: Create and Drop permissions are not needed.</p>

<p><b>Password</b><br><?php echo osc_draw_input_field('DB_SERVER_PASSWORD'); ?><br>
The password is used together with the username, which forms the database user account.</p>

<p><b>Database</b><br><?php echo osc_draw_input_field('DB_DATABASE'); ?><br>
The database used to hold the catalog data. An example database name is <i>catalog</i>.</p>

<p><?php echo osc_draw_checkbox_field('USE_PCONNECT', 'true'); ?> <b>Enable Persistent Connections</b><br>
Enable persistent database connections. Please disable this if you are on a shared server.</p>

<p><?php echo osc_draw_radio_field('STORE_SESSIONS', 'files', true); ?> <b>Store Sessions as Files</b><br>
<?php echo osc_draw_radio_field('STORE_SESSIONS', 'mysql'); ?> <b>Store Sessions in the Database</b><br>
The location to store PHP's sessions files.</p>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel"></a></td>
    <td align="center"><input type="hidden" name="install[]" value="configure"><input type="image" src="images/button_continue.gif" border="0" alt="Continue"></td>
  </tr>
</table>

</form>

<?php
  }
?>