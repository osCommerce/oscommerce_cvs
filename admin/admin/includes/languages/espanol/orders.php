<?php
/*
  $Id: orders.php,v 1.20 2002/11/01 05:55:36 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Pedidos');
define('HEADING_TITLE_SEARCH', 'Pedido ID:');
define('HEADING_TITLE_STATUS', 'Estado:');

define('TABLE_HEADING_COMMENTS', 'Comentarios');
define('TABLE_HEADING_CUSTOMERS', 'Clientes');
define('TABLE_HEADING_ORDER_TOTAL', 'Total Pedido');
define('TABLE_HEADING_DATE_PURCHASED', 'Fecha de Compra');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_ACTION', 'Accion');
define('TABLE_HEADING_QUANTITY', 'Cantidad');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Productos');
define('TABLE_HEADING_TAX', 'Impuesto');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Precio (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Precio (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');

define('TABLE_HEADING_NEW_VALUE', 'Valor Nuevo');
define('TABLE_HEADING_OLD_VALUE', 'Valor Antiguo');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Cliente Notificado');
define('TABLE_HEADING_DATE_ADDED', 'A�adido el');

define('ENTRY_CUSTOMER', 'Cliente:');
define('ENTRY_SOLD_TO', 'Cliente:');
define('ENTRY_STREET_ADDRESS', 'Direccion:');
define('ENTRY_SUBURB', '');
define('ENTRY_CITY', 'Poblacion:');
define('ENTRY_POST_CODE', 'Codigo Postal:');
define('ENTRY_STATE', 'Provincia:');
define('ENTRY_COUNTRY', 'Pais:');
define('ENTRY_TELEPHONE', 'Telefono:');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_DELIVERY_TO', 'Enviar A:');
define('ENTRY_SHIP_TO', 'Enviar A:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Metodo de Pago:');
define('ENTRY_CREDIT_CARD_TYPE', 'Tipo Tarjeta Credito:');
define('ENTRY_CREDIT_CARD_OWNER', 'Titular Tarjeta Credito:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numero Tarjeta Credito:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Caducidad Tarjeta Credito:');
define('ENTRY_SUB_TOTAL', 'Subtotal:');
define('ENTRY_TAX', 'Impuestos:');
define('ENTRY_SHIPPING', 'Gastos de Envio:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Fecha de Compra:');
define('ENTRY_STATUS', 'Estado:');
define('ENTRY_DATE_LAST_UPDATED', 'Ultima Modificacion:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notificar Cliente:');
define('ENTRY_PRINTABLE', 'Print Invoice');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Eliminar Pedido');
define('TEXT_INFO_DELETE_INTRO', 'Seguro que quiere eliminar este pedido?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'A�ada productos al almacen');
define('TEXT_DATE_ORDER_CREATED', 'A�adido el:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Modificado:');
define('TEXT_INFO_PAYMENT_METHOD', 'Metodo de Pago:');

define('TEXT_ALL_ORDERS', 'Todos');
define('TEXT_NO_ORDER_HISTORY', 'No hay historico');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Actualizacion del Pedido');
define('EMAIL_TEXT_ORDER_NUMBER', 'Numero de Pedido:');
define('EMAIL_TEXT_INVOICE_URL', 'Pedido Detallado:');
define('EMAIL_TEXT_DATE_ORDERED', 'Fecha del Pedido:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Su pedido ha sido actualizado al siguiente estado.' . "\n\n" . 'Nuevo estado: %s' . "\n\n" . 'Por favor responda a este email si tiene alguna pregunta que hacer.' . "\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: No existe pedido.');
define('SUCCESS_ORDER_UPDATED', 'Exito: Pedido actualizado correctamente.');
?>