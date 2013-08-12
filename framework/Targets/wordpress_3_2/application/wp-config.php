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
define('DB_NAME', 'wordpress_3_2');

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
define('AUTH_KEY',         'PW8BaS,#+2__|%BR9&E.VQ7$9{L9K?<6R$+b+niXNs||F[%<Sv[5R?$,?5i)gyv@');
define('SECURE_AUTH_KEY',  'YK2f3h&q/agTNDqr__5lZ  )-#[FN|-k sAYlkj@}D)uj0e0k$*|QJ[3+mTM-nYj');
define('LOGGED_IN_KEY',    '=iWV[%3S|;N nN]Z)}Ut!W3ioSg+ X2?TTG::.*c0Bbu3Z+YoW= EztB+_:mWzX:');
define('NONCE_KEY',        'Y&68t$Ucg%>$9*vDD p=ecKi8Xx#F|-vYG)Jv-4Pe} /m:--HR|A|>8S^qp^q-8I');
define('AUTH_SALT',        '[$oSJ(UVg|j]|5.|<4Ob+5ZiHV(}i&2)pa~6sb7YXz]r*]p4|aG?I9,n^v& vR31');
define('SECURE_AUTH_SALT', 'N9a@dP8XQCQ0mA%D? 6:e$s*Ai]fM|Eg_L$6,%YrAqR5st6P4`TNY9p2t+UxKD*K');
define('LOGGED_IN_SALT',   '+=lKlbu-M|.mge&,W_Z-eW<r.oy[|Um;r9Yr?P<:l|u xIIS0`kG+@<kVnA/CsJQ');
define('NONCE_SALT',       '!L_ugjxYR={-&%7E!BVS%U(*_jI1_JjVLf_|WSS 0YI:ZH^Z|bV@JH:_v-W;7V}D');

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
