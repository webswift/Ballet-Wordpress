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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'i2881854_wp2');

/** MySQL database username */
define('DB_USER', 'i2881854_wp2');

/** MySQL database password */
define('DB_PASSWORD', 'Z^lEx5]INmimh]^kIX.37~^8');

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
define('AUTH_KEY',         '3G9oc8OA20UqklpGP2aXLz1PGwpHcarEM1t654CmsBn0iBfZJDB0yAOC9HS5pFLy');
define('SECURE_AUTH_KEY',  'icozczfEJFLknMQH1zkPkCU37J9tA69jy8WOOTIRe1ZiFzlfH1vP8wC5EKyCqzx4');
define('LOGGED_IN_KEY',    'vPqZwh46OYsBOhS2WiZ8yJPVTv2tj0CE0QSZjNe2QqaFFHkSGMp4kVtpNLCW6lzN');
define('NONCE_KEY',        'SJR7NTKSd4bsOkLkiWfSwhdUke3Vx1UGEu4f4DjOB13vk7IIVozkPVainOgc50wB');
define('AUTH_SALT',        'CA1AkIt0f6nj6OUh1IBXYtJxwbBT90H0vWDSa9crSDBOSpFU422Q4fQ1naDlM7jd');
define('SECURE_AUTH_SALT', 'mPruyWRx0IVMO8Sf2QeO8KT9wVfiRqg8UvF9kustNLNXYdF4yImq3SGLHQkbNR2c');
define('LOGGED_IN_SALT',   'M3Om3QlJDNFv0ROs9g0FmgRUZJOPS3meTJCiUixtnmanVqNTwFezw9RW3zOLuthz');
define('NONCE_SALT',       'ExBZgTBwRTgvn043SsWscDxzPBRU5GbLzwKhq6QcJyu4P3FelLASo2HQzgmFICSK');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_MEMORY_LIMIT', '200M');
define('WP_UPLOAD_MAX_FILESIZE', '32M');