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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'ZR*z?Q!oyo^0-!l)N5sp!}%IHZ?}F^mGSY,yUpgXG23(:+J;kBY)M7ydWAo>#J5@');
define('SECURE_AUTH_KEY',  'qK7uxfa51.U[5`J[D^ch^Ow+Q*N{h4T,kD3Tr%of&hM=x&:e-x8.r;pf*.NkKcO[');
define('LOGGED_IN_KEY',    '+Q9a0Js3}P?N@c IC:+J$Ar1<kkS&^hk&ZZ|M|.6+7-+-#o%qa8U3BV-1ydgAtu7');
define('NONCE_KEY',        'Bm`[0OyvQn1PMhO!6BR,|}jvIO{~8:J;<V`6)S# aE9)44t;:#V#,q8MGm!x7gtO');
define('AUTH_SALT',        'm^}4~b1A$.@DZ9EzU>]tc99{{)P2}?djJuwvza<MYN{22Q_8>;+(_`RiJXx6beQ|');
define('SECURE_AUTH_SALT', 'Qf%$BPlxbp;~Uk@~{u86D]2BK$)Bv5!3|?NfO[WteSATss|ja5qc$jfsmj8MdKm_');
define('LOGGED_IN_SALT',   ';&0b+e3A~e|+!sem--av2C (H 9Rb(iPPL<[I>p`TZ1Ovsg2;VXp4R3s+mNla*H=');
define('NONCE_SALT',       '>StnO<#e?`XD-tIdQJ_V{PW``V+m3u-M|qDzD#M53LQ3eH~ue#`I?{w/Mi}|jm*+');

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
