<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wizoapps_wor2');

/** MySQL database username */
define('DB_USER', 'wizoapps_wor2');

/** MySQL database password */
define('DB_PASSWORD', '7i10t9SI');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '35dB1G>yIGv9e+rhgcPv=g*%vAj):@oF4)z^CcPI%1s(j9;Ix[%-VBfOEj~AjFcx');
define('SECURE_AUTH_KEY',  'QFvC+<2;/m+J}B,hVbRhlzvv3Zu:pD$TCjh83Tk 4YjB]@s`L0QCcc9O^q`mjj#M');
define('LOGGED_IN_KEY',    '1$eS~.9_+oa$d/g!3rc~vTa&GGY8{(F9Xk: %5JSE_57C-tPPb!yP+Uh9r+@7yx3');
define('NONCE_KEY',        'eE{ W2xHZ]uzz]eq,V+6/0h;5fm=}G?%F8PQ+4`R;i@`YGk*vS1gSdQ_aB8PCfR(');
define('AUTH_SALT',        '4S9|H(!Uf%q{F[B^/ZauR9)gP!OG2`oH9D9:Ch2Q5NE`|egn0sus>8rNE+Mh?E6)');
define('SECURE_AUTH_SALT', '?]Xg!{nlJr4dcqw3S|eIIQFR|r.I(ldHP>y$+9w3-a6n-Un>{,JS]8@gMXq-_2*!');
define('LOGGED_IN_SALT',   'TC_Li+-y(BR)ByAp`otnq-0sI;/y[88Je-tJgp<;&nJ)`t{R*!gU]qY@Y!-(PJX%');
define('NONCE_SALT',       'gS$%Q HygLa1V|<,51kmzI Pu%3 XC=cn6uh3|MW]V]+CVX:Y&c=/Gj;w[]fPV;:');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'odu_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
//define('WPLANG', 'fo_FO');
define('WPLANG', 'fr_FR');
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
