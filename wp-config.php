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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Historia-Et-Trivia_db' );

/** Database username */
define( 'DB_USER', 'ThanhDanhLe' );

/** Database password */
define( 'DB_PASSWORD', 'talavua#123@' );

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
define( 'AUTH_KEY',         'h4s(8U6C+a@~.X%PWC=L, h~u[X{cpA_{T9No}f?s,bsrpRAW.absw5Z-87@%%D~' );
define( 'SECURE_AUTH_KEY',  '05X/zz>nsu+_VD$B6[-KK[X{MhMEExH<qCl1?NiwPp!7b$1G8FGFNyURlvcl^:q^' );
define( 'LOGGED_IN_KEY',    'YZhZjW;gSUd=q-fLgW)@  ,&m([cD1)kha:@+7tvClz}C[S&);i v=|JeMe+>JrV' );
define( 'NONCE_KEY',        '0V04F|;J&_6*^xx-MRkyz()11aE6S>mKkQWrXUU5o,2v1,vNlq}Nu)V`ee$Hj:b8' );
define( 'AUTH_SALT',        'zov*Me/QI]Ahh/@Y5iw:p-8!)M1B&}~cAXbAETEdspY;_#GAZzpx5DX4Cw$^<?V7' );
define( 'SECURE_AUTH_SALT', 'X=|-*T4^M-KC-cE$[Ag,a?5EKXH8S#(GhQK=7jqT1_hW?$Z&IH}l.S7j&%tljvy#' );
define( 'LOGGED_IN_SALT',   'fo3}8z #kcNoaBnB.UAP$;Nec?U4=|OzLriSKb+TOc(OLGq[;rn=) :O)`#~Ej4_' );
define( 'NONCE_SALT',       '^60z$COulZ9QJ`t%f0,ldcx.z[]^n;9 3_rC8cO60e4utSOCk77Ob}p#v|[9YJ} ' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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

