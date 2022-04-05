<?php

/* Accumulate points Plugin.
 *
 * @package   WPPOINTS\Main
 * @copyright Copyright (C) 2022, Manhnv - nguyenmanh0397@gmail.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Accumulate points
 * Version:     1.0.0
 * Plugin URI:  https://manhnv.com
 * Description: Accumulate points.
 * Author:      Manhnv (AKA 0xmanhnv)
 * Author URI:  https://manhnv.com
 * Text Domain: Accumulate-points
 * Domain Path: /languages/
 * License:     GPL v3
 * Requires at least: 5.8
 * Requires PHP: 5.6.20
 *
 * WC requires at least: 3.0
 * WC tested up to: 6.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Define contants
 */
if ( ! defined( 'WPPOINTS_FOLDER_NAME' ) ) {
	define( 'WPPOINTS_FOLDER_NAME', '/accumulate-points' );
}

if ( ! defined( 'WPPOINTS_FILE' ) ) {
	define( 'WPPOINTS_FILE', __FILE__ );
}

if ( ! defined( 'WPPOINTS_PLUGIN_URL' ) ) {
	define( 'WPPOINTS_PLUGIN_URL', plugin_dir_url( WPPOINTS_FILE ) );
}

if ( !defined( 'WPPOINTS_CORE_DIR' ) ) {
	define( 'WPPOINTS_CORE_DIR', WP_PLUGIN_DIR . WPPOINTS_FOLDER_NAME );
}

if ( ! defined( 'WPPOINTS_PATH' ) ) {
	define( 'WPPOINTS_PATH', plugin_dir_path( WPPOINTS_FILE ) );
}

if ( ! defined( 'WPPOINTS_BASENAME' ) ) {
	define( 'WPPOINTS_BASENAME', plugin_basename( WPPOINTS_FILE ) );
}

if ( ! defined( 'WPPOINTS_PLUGIN_BASENAME' ) ) {
    define( 'WPPOINTS_PLUGIN_BASENAME', plugin_basename( WPPOINTS_FILE ) );
}

if ( !defined( 'WPPOINTS_CORE_INC' ) ) {
	define( 'WPPOINTS_CORE_INC', WPPOINTS_CORE_DIR . '/_inc' );
}

if ( !defined( 'WPPOINTS_ADMIN_DIR' ) ) {
	define( 'WPPOINTS_ADMIN_DIR', WPPOINTS_CORE_DIR . '/admin' );
}

// Load the Accumulate POINTS plugin.
require_once WPPOINTS_CORE_DIR . '/wp-points-main.php';

WPPoints_Plugin::init();
