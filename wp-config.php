<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'giraffe3adeVaVu');

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
define('AUTH_KEY',         '@|AuuHb`vn.!3v({* nmg<0LTw|5r>.p/Eg9jt (V?Zldj1!_w+w$iIY$`tI<^~e');
define('SECURE_AUTH_KEY',  '5Gw=+6=YAI=]6iE0^PS^~q399|uSA>U$/b<>fp;Iv+u`]KI#xp=_=Zz!;~|-A+;D');
define('LOGGED_IN_KEY',    'C`?t_qFW>1L<JzMD;?]zdY-FvQ[8OOJneK4KXlH)-n]wOh++{$2sdUf6l|T[^Mf<');
define('NONCE_KEY',        'na8Sw<_B!A78NO%QW@Bq^?v#q^x;zP17Oi^}t-WV}.7RqolJ4Z}5VX+a<ieb-+Um');
define('AUTH_SALT',        'vNu(@RQ-i<D+Hx&mp7I)/WrxN2#`uTpxLc($K(7RffWc.]Ld[&~He%NkFT${{^|F');
define('SECURE_AUTH_SALT', 'bM_=-@Xf-&r`Wju(|_WD}Q>*-Da(P1nA+|)n+dA2,_e+>{+^6_6lD|3Y y;0g7lS');
define('LOGGED_IN_SALT',   '3vkwCnCk}T%b~FA:S)ta`mor;Z%7`?/lu0^y--n0t,PX-Ck+1vI`7XRJC&K-$f.i');
define('NONCE_SALT',       '!5Qz+=N2hStn3lrdF 9d/rILG>h>EE.(y8Lix)6T[{z`eoG._dX{zzWD;fq<U.L8');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'egg_';

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
