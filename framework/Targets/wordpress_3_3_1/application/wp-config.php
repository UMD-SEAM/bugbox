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
define('DB_NAME', 'wordpress_3_3_1_A');

/** MySQL database username */
define('DB_USER', 'dbroot');

/** MySQL database password */
define('DB_PASSWORD', 'connection452');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         '=@-}y-l.f97A*miiZ!fS%zhF^Qtd7Qf@vZ+y02a!?pU!oBD,KnZyNF0YBsh|esPH');
define('SECURE_AUTH_KEY',  '|9|A<h7N(}fa/+)h!HVrZA~RnpTtmPy-+W6+O) ~*CN;`?#w_7N/^:lG*+xMA?YQ');
define('LOGGED_IN_KEY',    '4:miX1)=I,pWtZ^O]s.,<+0O7m)!q}TL%bjEn+g[c]6ku^?eTK<F u-Ex2VpCtXV');
define('NONCE_KEY',        '`c5}!>{-f nLTW%Hv8*QmJ?fq6{,Ws43*JX+r@~Wo6??R?N++nd5+sT2 Ce,]MoE');
define('AUTH_SALT',        'm:1hXwhnQ!8wi|~sJ,kv_E^xceS:pTIMevej2XwImck : bW<2j@v+_EO_w|lj=w');
define('SECURE_AUTH_SALT', ')~z&q=fY tT$dO_uM 9gm4&VN-U&[Fh}5iQl1sjHbE~2mCm/$N+I=BYIP_{smQfl');
define('LOGGED_IN_SALT',   ':CNQi?S^^AK>PObQbi4!EO)4`!*#8J@?p09m!pCGEI8+%2Q/5|pEMO%=MxT2&i:W');
define('NONCE_SALT',       '(yRsP>F{`C#@dq4YJN{aX[9I@})(+z+vd#kq1U=a -|+y3oKi/YM9#w_=Q@qBOL4');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
