<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ecommerce' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'x>wCw$X2wCE{2;z%BUY(!-RUH^.X$~[>)k@ FwWKD};5gC;s~M>r_jE{?HZ{ap.w' );
define( 'SECURE_AUTH_KEY',  '<I/J IaLURzlHP@&a;b|`-P)rz:S}U?gL.YuvcQj,;&*!SWH%{W^rzvcL-MdjB=;' );
define( 'LOGGED_IN_KEY',    'X0w{`*LYuJ`R!/icKdV7o*B2VkBmZTmK{AMfGMi@U]Z6HY8nIckYb@H`bn8KG4%v' );
define( 'NONCE_KEY',        'xwJi~)7 4zBOhVpnyrAAtlwrb?nvy9g{,BZL!JQ;7;$XDB=PBM;+SBC^C}FE$bLH' );
define( 'AUTH_SALT',        'x*PBe>%u=GZV99 k%CHdbe8zm~!je)xQq~TD#XjD<?#Bti-YkTQ$}Evp].[ufW38' );
define( 'SECURE_AUTH_SALT', 'i{|%x#`sNxErLU=2;;M3}ao|h43%&EM<j,WVJ+/}tP>=>NE):gfhqBEt1?u)d12O' );
define( 'LOGGED_IN_SALT',   'C7<4Ju#{Q~c]WDj/e$6`*^U^7&gx[C&hFM#Q74Oe/q|:Wd.QBlU6BzCHNrWnu7&^' );
define( 'NONCE_SALT',       '{K/yxX#D7iVaH v;*tcIw9KAt[FZimUzj/;2&>:x !(@!3oLG=.vL/Tt}E2E?Xto' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
