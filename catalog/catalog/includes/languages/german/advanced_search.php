<?php
/*
  $Id: advanced_search.php,v 1.16 2002/05/27 14:09:41 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Erweiterte Suche');
define('HEADING_TITLE', 'Erweiterte Suche');

define('HEADING_SEARCH_CRITERIA', 'Erweiterte Suche');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Auch in Beschreibungen suchen');
define('ENTRY_CATEGORIES', 'Kategorien:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Unterkategorien mit einbeziehen');
define('ENTRY_MANUFACTURERS', 'Hersteller:');
define('ENTRY_PRICE_FROM', 'Preis ab:');
define('ENTRY_PRICE_TO', 'Preis bis:');
define('ENTRY_DATE_FROM', 'hinzugef&uuml;gt von:');
define('ENTRY_DATE_TO', 'hinzugef&uuml;gt bis:');

define('TEXT_SEARCH_HELP_LINK', '<u>Search Help</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Alle Kategorien');
define('TEXT_ALL_MANUFACTURERS', 'Alle Hersteller');

define('HEADING_SEARCH_HELP', 'Hilfe zur erweiterten Suche');
define('TEXT_SEARCH_HELP', 'Die Suchmaschine erm&ouml;glicht Ihnen die Suche in den Produktnamen, Produktbeschreibungen, Herstellern und Modellen.<br><br>Sie haben die M&ouml;glichkeit logische Operatoren wie "AND" (Und) und "OR" (oder) zu verwenden.<br><br>Als Beispiel k&ouml;nnten Sie also angeben: <u>Microsoft AND Maus</u>.<br><br>Desweiteren k&ouml;nnen Sie Klammern verwenden um die Suche zu verschachteln, also z.B.:<br><br><u>Microsoft AND (Maus OR Tastatur OR "Visual Basic")</u>.<br><br>Mit Anf&uuml;hrungszeichen k�nnen Sie mehrere Worte zu einem Suchbegriff zusammenfassen.');
define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

define('JS_AT_LEAST_ONE_INPUT', '* Eines der folgenden Felder mu� ausgef�llt werden:\n    Stichworte\n    Datum hinzugef�gt von\n    Datum hinzugef�gt bis\n    Preis ab\n    Preis bis\n');
define('JS_INVALID_FROM_DATE', '* Unzul�ssiges von Datum\n');
define('JS_INVALID_TO_DATE', '* Unzul�ssiges bis jetzt\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* Das Datum von muss gr�sser oder gleich bis jetzt sein\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* Preis ab, mu� eine Zahl sein\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* Preis bis, mu� eine Zahl sein\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Preis bis mu� gr��er oder gleich Preis ab sein.\n');
define('JS_INVALID_KEYWORDS', '* Suchbegriff unzul�ssig\n');
?>