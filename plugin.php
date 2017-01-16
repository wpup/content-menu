<?php

/**
 * Plugin Name: Content menu
 * Plugin URI: https://github.com/frozzare/wp-content-menu
 * Description: Adds a content menu to WordPress admin
 * Author: Fredrik Forsmo
 * Author URI: https://github.com/frozzare
 * Version: 1.0.1
 * Textdomain: wp-content-menu
 */

// Load plugin class.
require_once __DIR__ . '/src/class-table.php';
require_once __DIR__ . '/src/class-menu.php';

/**
 * Boot the plugin.
 */
add_action( 'plugins_loaded', function () {
	return new \Frozzare\Content\Menu;
} );
