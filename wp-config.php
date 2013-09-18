<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define( 'WPCACHEHOME', '/sites/newlocalmobi/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'localm_2');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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

define('AUTH_KEY',         '-G#vGJ^L95yE-/1J<$+$S/,BOzVvTpbJdyIs65/Go1m|-9h[TRL:Zfj4~G$ytsh3');
define('SECURE_AUTH_KEY',  'x~j-0sGnL~8q;PY7-)-2Pu37BIBMh@)2==Ma(NHp/FomyOk*/QDI+_Bt0+P^v}[b');
define('LOGGED_IN_KEY',    'P4F7G12+]1uUR~an0!%[EX2!|j^{/<Oeq;b+>mK%.n<s&<@qU[s5r}hdC$!hMaC!');
define('NONCE_KEY',        'Aqc{/!IG&Ghkr1H{K|jIoP!Mb|@X>M--}S>+-Q 9q|KVX=$JcZGyWA;-(I^yu|h4');
define('AUTH_SALT',        '@VQ7s[uRGKa-{3P&1=?oW 4,~GPginG^R+m-OLf=*6M$[fdXRQ);%6519)I7D6/U');
define('SECURE_AUTH_SALT', '*[v.RARf(/k2c#5+R1-BY5~1]R#g^taOV_<tb2U)6Kbe`0R RC=,l-sIUMX*~T5R');
define('LOGGED_IN_SALT',   'uov&/5QCB7)7yGDWrmx?JlKJ01-6h>N.wj;yX#6)6r}Og)HwP@+*E.UC>8A(G?xZ');
define('NONCE_SALT',       'f.(>tL`2xQSJ4?$t;zxU|A`WqJ>EZbv|-mL)5zVTmiz)-7oZHE/&8$HJ]E<q@+[*');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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

