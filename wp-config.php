<?php
define( 'DISALLOW_FILE_MODS', false );
define( 'WP_CACHE', true ); // Added by WP Rocket


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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u114084584_QMhd6' );

/** Database username */
define( 'DB_USER', 'u114084584_MsHaA' );

/** Database password */
define( 'DB_PASSWORD', 'whBaN0Rjpq' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '/n=BDv^|)]^?~>z3Xr44:$<]Pvp=rI/c?iY]q5ord,I#t,^L8=eV=+?^Cv>},-Nn' );
define( 'SECURE_AUTH_KEY',   '2[c,Q%|xK,(8D+drvd]1P>*`f(7-PGgt!;g(Vg!Ms]f0sFg:&Td>)q^{Dc0}G?8`' );
define( 'LOGGED_IN_KEY',     '1,]tbJ>mW99l=^H_nXv(YNw`!39!@171=/.eI},*0.4`F[L3%O7UJL6Hs/P%9`2C' );
define( 'NONCE_KEY',         '~~9[09wd#IZ5sLY9%uz7I?(1xA]1HfdCX8`4rtE4Hj{Ncl0Wmt1&?&V3J+TnCXoR' );
define( 'AUTH_SALT',         'aHS(c8[ 9r$4rX3&dYIP,:I4@0L~z~ao|veq2]SH>D5zIge,!6]7`@AU%sqJkccg' );
define( 'SECURE_AUTH_SALT',  '|4l-p!~z[fD9sZ;S./fa$,Cg(a@gH|TNHcSQ,^ LYjLzRZ7[LK2I}PoLnhYy!:`#' );
define( 'LOGGED_IN_SALT',    'C1nBy<*DeBS/!)Jf8)lcGr7>mT|U-Lr=[}*&hoq?Tk@JOM#r-^gj1TPU` 1~p`g)' );
define( 'NONCE_SALT',        'BDj$=o|#/,5,Rj%/Q8ZOQi)*!zF(WqZ_9L./M6UT3eC~#_4V}}HdJs Ii{o-s,:|' );
define( 'WP_CACHE_KEY_SALT', 'Y`A6SqaB$ae$ob5>4ggAgq8IU~mxtiPkQ*poYfKMOW{%-I3k%qs]WTg*<+}&B1q*' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';