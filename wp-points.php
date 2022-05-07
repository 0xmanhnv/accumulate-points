<?php

/* Accumulate points Plugin.
 *
 * @package   WPPOINTS\Main
 * @copyright Copyright (C) 2022, Manhnv - nguyenmanh0397@gmail.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Accumulate points
 * Version:     1.0.3
 * Plugin URI:  https://github.com/0xmanhnv/accumulate-points
 * Description: Accumulate points.
 * Author:      Manhnv (AKA 0xmanhnv)
 * Author URI:  https://manhnv.com
 * Text Domain: Accumulate-points
 * Domain Path: /languages/
 * License:     GPL v3
 * Requires at least: 5.8
 * Requires PHP: 5.6.20
 * WC requires at least: 3.0
 * WC tested up to: 6.1
 * Update URI: https://github.com/0xmanhnv/accumulate-points
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

// Create the query var so that WP catches the custom /member/username url
add_filter( 'query_vars', 'accumulate_points_rewrite_add_var' );
function accumulate_points_rewrite_add_var( $vars ) {
    $vars[] = 'accumulate-points';
    return $vars;
}

// Create the rewrites
add_action('init','accumulate_points_rewrite_rule');
function accumulate_points_rewrite_rule() {
    add_rewrite_tag( '%accumulate-points%', '([^&]+)' );
    add_rewrite_rule(
        '^accumulate-points/([^/]*)/?',
        'index.php?accumulate-points=$matches[1]',
        'top'
    );
}

// Catch the URL and redirect it to a template file
add_action( 'template_redirect', 'accumulate_points_rewrite_catch' );
function accumulate_points_rewrite_catch($original_template) {
    global $wp_query;
	$plugin_url = plugins_url( 'accumulate-points.php', __FILE__ );
	$plugin_url = substr( $plugin_url, strlen( home_url() ) + 1 );

	// var_dump($wp_query->query_vars);
	// die;

    // if ( array_key_exists( 'points', $wp_query->query_vars ) ) {
    //     include ($plugin_url);
    //     exit();
    // }else{
	// 	return $original_template;
	// }
	if ( isset($wp_query->query_vars['name']) && $wp_query->query_vars['name'] === 'accumulate-points' || array_key_exists( 'accumulate-points', $wp_query->query_vars ) ) {
        include ($plugin_url);
        exit();
    }else{
		return $original_template;
	}
}

// Code needed to finish the member page setup
add_action('init','accumulate_points_rewrite');
function accumulate_points_rewrite() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}
