<?php

/**
 * @package super-booking-calendar
 * @version 1.0
 */

/**
 * Plugin Name: Super Booking Calendar
 * Plugin URI: http://www.spin-interactive.com
 * Description: Nice calendar to mark important events.
 * Version: 1.0
 * Author: Spin Interactive <dev@spin-interactive.com>
 * Author URI: http://www.spin-interactive.com
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

require_once( plugin_dir_path(__FILE__) . 'Spin.php' );
add_action( 'init', array( 'Spin', 'init' ) );

