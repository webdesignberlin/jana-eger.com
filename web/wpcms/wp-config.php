<?php
/**
 * In dieser Datei werden die Grundeinstellungen für WordPress vorgenommen.
 *
 * Zu diesen Einstellungen gehören: MySQL-Zugangsdaten, Tabellenpräfix,
 * Secret-Keys, Sprache und ABSPATH. Mehr Informationen zur wp-config.php gibt es auf der {@link http://codex.wordpress.org/Editing_wp-config.php
 * wp-config.php editieren} Seite im Codex. Die Informationen für die MySQL-Datenbank bekommst du von deinem Webhoster.
 *
 * Diese Datei wird von der wp-config.php-Erzeugungsroutine verwendet. Sie wird ausgeführt, wenn noch keine wp-config.php (aber eine wp-config-sample.php) vorhanden ist,
 * und die Installationsroutine (/wp-admin/install.php) aufgerufen wird.
 * Man kann aber auch direkt in dieser Datei alle Eingaben vornehmen und sie von wp-config-sample.php in wp-config.php umbenennen und die Installation starten.
 *
 * @package WordPress
 */

/**  MySQL Einstellungen - diese Angaben bekommst du von deinem Webhoster. */
/**  Ersetze database_name_here mit dem Namen der Datenbank, die du verwenden möchtest. */
define('DB_NAME', 'db291238_1');

/** Ersetze username_here mit deinem MySQL-Datenbank-Benutzernamen */
define('DB_USER', 'db291238_1');

/** Ersetze password_here mit deinem MySQL-Passwort */
define('DB_PASSWORD', '.dgSDuG54_z!-.efr3');

/** Ersetze localhost mit der MySQL-Serveradresse */
define('DB_HOST', 'mysql5.jana-eger.com');

/** Der Datenbankzeichensatz der beim Erstellen der Datenbanktabellen verwendet werden soll */
define('DB_CHARSET', 'utf8');

/** Der collate type sollte nicht geändert werden */
define('DB_COLLATE', '');

/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden KEY in eine beliebige, möglichst einzigartige Phrase. 
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service} kannst du dir alle KEYS generieren lassen.
 * Bitte trage für jeden KEY eine eigene Phrase ein. Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten Benutzer müssen sich danach erneut anmelden.
 *
 * @seit 2.6.0
 */
define('AUTH_KEY',         'x&dsTU%o^}xk;QhJ+,~)8.hc{~ntX3wNTPxmAY+Ua7`-vUdcAo?@lp%8kBp@|Ht7');
define('SECURE_AUTH_KEY',  'F5r~YQmGw4lW*|[-%w^yC8~X4u*%*+)! 2 W|U2*{~ffw|Gp6?SXnG6wL(~t%RAh');
define('LOGGED_IN_KEY',    'cr5.2_~9H5u,PSkEFLCemlh?9*dD}J`n%H]Jr+vL}QT/+}b/hX17+G1=R|Z}*Wg0');
define('NONCE_KEY',        '|@#mbZldLxc]6/VV+[a>0@)aD&Y7@R<.Vv7*x6Fw>=0{mhTHGH%zE+Yw%Etb4tSB');
define('AUTH_SALT',        ',4e(1i1.B_`0ndV&,{bhPp:2D]bg`+r]}7qZaH#41YWQ<PeB1Q&NjIvG?A`JG:+^');
define('SECURE_AUTH_SALT', ':4`IWoA%lrq~M<+*npzOYtxKn}Y5m.ZZ6t^bC!:9d51W!<Fw68dV2+sT9o[Bpa*i');
define('LOGGED_IN_SALT',   '%Rtw~fk.$Fi8w65iE]+(atR!.`u1[>fQ2/*z=[(W_YGB++8|R?kl2w1)g9glw`/&');
define('NONCE_SALT',       '4l-zQv?iIo3HjNAYw_{K>Z-1@<t|9lsuj%17C~R2O6g[&Ni{x%)/LZ2F.![e+N9y');

/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix
 *
 *  Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 *  verschiedene WordPress-Installationen betreiben. Nur Zahlen, Buchstaben und Unterstriche bitte!
 */
$table_prefix  = 'wpcms_';

/**
 * WordPress Sprachdatei
 *
 * Hier kannst du einstellen, welche Sprachdatei benutzt werden soll. Die entsprechende
 * Sprachdatei muss im Ordner wp-content/languages vorhanden sein, beispielsweise de_DE.mo
 * Wenn du nichts einträgst, wird Englisch genommen.
 */
define('WPLANG', 'de_DE');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');