<?php
/*
  $Id: specials.php,v 1.13 2004/10/31 09:43:34 mevans Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Ofertas');

define('TABLE_HEADING_PRODUCTS', 'Productos');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Precio');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_ACTION', 'Acci&oacute;n');

define('TEXT_SPECIALS_PRODUCT', 'Producto:');
define('TEXT_SPECIALS_SPECIAL_PRICE', 'Precio de Oferta:');
define('TEXT_SPECIALS_STATUS', 'Status:');
define('TEXT_SPECIALS_START_DATE', 'Start Date:');
define('TEXT_SPECIALS_EXPIRES_DATE', 'Fecha de Caducidad:');
define('TEXT_SPECIALS_PRICE_TIP', '<b>Notas:</b><ul><li>Puedes introducir un porcentaje de reduccion del precio del producto, por ejemplo: <b>20%</b></li><li>Si por el contrario, introduces un precio de oferta debes de usar el punto como separador decimal \'.\' (punto decimal), por ejemplo: <b>49.99</b></li><li>Deja la fecha de caducidad vacia si no quieres caducidad</li></ul>');

define('TEXT_INFO_DATE_ADDED', 'Fecha Alta:');
define('TEXT_INFO_LAST_MODIFIED', 'Ultima Modificaci&oacute;n:');
define('TEXT_INFO_NEW_PRICE', 'Nuevo Precio:');
define('TEXT_INFO_ORIGINAL_PRICE', 'Precio Original:');
define('TEXT_INFO_PERCENTAGE', 'Porcentaje:');
define('TEXT_INFO_EXPIRES_DATE', 'Fecha de Caducidad:');
define('TEXT_INFO_STATUS_CHANGE', 'Cambio de Estado:');

define('TEXT_INFO_HEADING_DELETE_SPECIALS', 'Eliminar Oferta');
define('TEXT_INFO_DELETE_INTRO', 'Seguro que desea eliminar este precio de oferta?');

define('ERROR_SPECIALS_PRICE', 'Specials price cannot be negative or greater than original price');
define('ERROR_SPECIALS_DATE', 'The expiry date is before the start date');
?>