<?php
/*
  $Id: manufacturers.php,v 1.10 2002/01/13 11:00:10 jan0815 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Hersteller');
define('HEADING_TITLE', 'Hersteller');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_MANUFACTURERS', 'Hersteller');
define('TABLE_HEADING_IMAGE', 'Bild');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_MANUFACTURERS', 'Hersteller:');
define('TEXT_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_LAST_MODIFIED', 'letzte &Auml;nderung am:');
define('TEXT_PRODUCTS', 'Artikel:');
define('TEXT_IMAGE_NONEXISTENT', 'BILD NICHT VORHANDEN');

define('TEXT_NEW_INTRO', 'Bitte f&uuml;llen Sie die folgenden Felder f&uuml;r den neuen Hersteller aus');
define('TEXT_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch');
define('TEXT_EDIT_MANUFACTURERS_ID', 'Hersteller ID:');
define('TEXT_EDIT_MANUFACTURERS_NAME', 'Herstellername:');
define('TEXT_EDIT_MANUFACTURERS_IMAGE', 'Herstellerbild:');
define('TEXT_EDIT_MANUFACTURERS_URL', 'Hersteller URL:');

define('TEXT_DELETE_INTRO', 'Sind Sie sicher, dass Sie diesen Hersteller l&ouml;schen m&ouml;chten?');
define('TEXT_DELETE_PRODUCTS', 'Alle Artikel von diesem Hersteller l&ouml;schen? (inkl. Bewertungen, Angebote und Neuerscheinungen)');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNUNG:</b> Es existieren noch %s Artikel, die immer noch mit diesem Hersteller verbunden sind!');

define('ERROR_ACTION', 'EIN FEHLER IST AUFGETRETEN! LETZTE AKTION : ' . $HTTP_GET_VARS['error']);
?>