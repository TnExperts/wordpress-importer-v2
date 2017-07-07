<?php
/*
Plugin Name: WordPress Importer v2
Plugin URI: https://github.com/pbiron/WordPress-Importer
Description: Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.
Author: wordpressdotorg, rmccue, pbiron
Author URI: http://wordpress.org/
Version: 2.0.2
Text Domain: wordpress-importer
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/pbiron/WordPress-Importer
*/

if ( ! class_exists( 'WP_Importer' ) ) {
	defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
	require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
}

require dirname( __FILE__ ) . '/class-logger.php';
require dirname( __FILE__ ) . '/class-logger-cli.php';
require dirname( __FILE__ ) . '/class-logger-html.php';
require dirname( __FILE__ ) . '/class-logger-serversentevents.php';
require dirname( __FILE__ ) . '/class-wxr-importer.php';
require dirname( __FILE__ ) . '/class-wxr-import-info.php';
require dirname( __FILE__ ) . '/class-wxr-import-ui.php';

if ( defined( 'WP_CLI' ) ) {
	require __DIR__ . '/class-command.php';

	WP_CLI::add_command( 'wxr-importer', 'WXR_Import_Command' );
}

function wpimportv2_init() {
	/**
	 * WordPress Importer object for registering the import callback
	 * @global WP_Import $wp_import
	 */
	$GLOBALS['wxr_importer'] = new WXR_Import_UI();
	register_importer(
		'wordpress-v2',
		'WordPress (v2)',
		__( 'Import <strong>posts, pages, comments, custom fields, categories, and tags</strong> from a WordPress export (WXR) file.', 'wordpress-importer' ),
		array( $GLOBALS['wxr_importer'], 'dispatch' )
	);

	add_action( 'load-importer-wordpress-v2', array( $GLOBALS['wxr_importer'], 'on_load' ) );
	add_action( 'wp_ajax_wxr-import', array( $GLOBALS['wxr_importer'], 'stream_import' ) );
}
add_action( 'admin_init', 'wpimportv2_init' );
