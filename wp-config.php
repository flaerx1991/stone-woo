<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'teplin' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '6baq05o/*S!qZ2e`F9z )0UCW@k5/6aRRe$rp}4jPJ:_u]`%KLF:E3RO%]4UHd$?' );
define( 'SECURE_AUTH_KEY',  'YD:Jw6=`D6V2Ib?0InllVeDH<kn{2gl2<w3hVP}$~<I(/|3Xq{w,9<E/[3hn(Lw,' );
define( 'LOGGED_IN_KEY',    '3kP-Da_6eVHyRY9bk1k2Hf_=Rp+dM~Nd2$LK8p+I%0o!dK1UVICxSic6uH&A-^GY' );
define( 'NONCE_KEY',        'J0Sw8X.<<1HaJMg&YrvpDxG48NvC]HA QSZZ6-YU!,so3+7AS5=RSiY<E},N,=Gq' );
define( 'AUTH_SALT',        '&<lZ&=JDP@ej/8{<Z_};T||TL{o2?)c$zYQy:.zG.;qO/MYW7%$+xAYE{`dJDT|M' );
define( 'SECURE_AUTH_SALT', '0Y7tQ7$I6eq}y.;[/#59.qvt}kayq_4MMcv@s4x<u+wB)n.U&{*-{M_+nBR]H#xr' );
define( 'LOGGED_IN_SALT',   'WJW<|Jz,@<B{-3=LQ=ALmaSmN?cr{u@|3vyz`bvR~Ip`BJKR>M;]dmTE%fBajAXW' );
define( 'NONCE_SALT',       'R+a&QS%ZJ>V<f3^F)e>4m|H`0`Ov4tV:>7a?<#qav.4<eJBx1/M)umbh8CE/q^K@' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
