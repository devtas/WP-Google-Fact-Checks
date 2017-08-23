<?php
/*
Plugin Name: WP Google Fact Checks
Plugin URI:  https://github.com/devtas/WP-Google-Fact-Checks
Description: Add the Fact Checks by Google on your Wordpress. See more: https://goo.gl/o7vyK7
Version:     0.1
Author:      Thiago Andrade
Author URI:  http://devtas.tk/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

// Verifying add_action function
if ( !function_exists( 'add_action' ) ) {
	echo 'I\'m just a plugin and cannot called directly;';
	exit;
}

// CONSTANTS
define( 'WPGFC__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( WPGFC__PLUGIN_DIR . 'class.wpgfc.php' );

add_action( 'init', array( 'wpgfc', 'init' ) );