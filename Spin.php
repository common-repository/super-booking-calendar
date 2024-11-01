<?php

/**
 * Class Spin
 * @package super-booking-calendar
 * @author Spin Interactive <dev@spin-interactive.com>
 * @version 1.0
 */

class Spin {

    private static $initiated = false;

    public static function init() {
        if ( ! self::$initiated ) {
            self::init_hooks();
        }
    }

    private static function init_hooks() {
        self::$initiated = true;
        if(is_admin()) {
            /* Backoffice */
            //load_plugin_textdomain( 'super-booking-calendar' );
            add_action( 'admin_menu', array( 'Spin', 'admin_menu' ) );
            add_action( 'admin_notices', array( 'Spin', 'admin_notices' ) );
            wp_enqueue_style("super-booking-calendar_css", plugin_dir_url(__FILE__) . "inc/jquery-ui.min.css", null, "1.0");
            wp_enqueue_script("jquery-ui-datepicker");
        }
        else {
            /* Frontoffice */
            wp_enqueue_style("super-booking-calendar_css", plugin_dir_url(__FILE__) . "inc/fullcalendar.min.css", null, "1.0");
            wp_enqueue_script("super-booking-calendar_script1", plugin_dir_url(__FILE__) . "inc/moment.min.js", null, "1.0", true);
            wp_enqueue_script("super-booking-calendar_script2", plugin_dir_url(__FILE__) . "inc/fullcalendar.min.js", null, "1.0", true);
            wp_enqueue_script("super-booking-calendar_script3", plugin_dir_url(__FILE__) . "inc/script.js", null, "1.0", true);
        }
        add_shortcode( 'super-booking-calendar', array( 'Spin', 'super_booking_calendar_shortcode' )  );
    }

    //[super-booking-calendar]
    function super_booking_calendar_shortcode( $atts ){
        global $wpdb;
        $data = "";
        $events = $wpdb->get_results("SELECT event_name, event_date FROM booking ORDER BY id ASC");
        foreach($events as $event) $data .= $event->event_name.",;".$event->event_date.";+";
        return '<div id="super-booking-calendar" data-json="'.addslashes($data).'"></div>';
    }

    public static function admin_menu_options() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
    }

    public static function admin_help() {
        $screen = get_current_screen();
        $screen->add_help_tab( array(
            'id' => 1,            //unique id for the tab
            'title' => "Test1",      //unique visible title for the tab
            'content' => "Hello world 1",  //actual help text
            'callback' => array( 'Spin', 'admin_menu_options' ) //optional function to callback
        ) );
        $screen->add_help_tab( array(
            'id' => 2,            //unique id for the tab
            'title' => "Test2",      //unique visible title for the tab
            'content' => "Hello world 2",  //actual help text
            'callback' => array( 'Spin', 'admin_menu_options' ) //optional function to callback
        ) );
        // Help Sidebar
        $screen->set_help_sidebar(
            "Réservation"
        );
    }

    public static function admin_notices() {
        global $hook_suffix;
        if($hook_suffix == "settings_page_super-booking-calendar") {
            if(isset($_GET["view"])) {
                switch($_GET["view"]) {
                    case "form":
                        Spin::view("form");
                        break;
                    default:
                        Spin::view("default");
                }
            }
            else {
                Spin::view("default");
            }
        }
    }

    public static function admin_menu()
    {
        $hook = add_options_page('Super Booking Calendar Options', 'Super Booking Calendar', 'manage_options', 'super-booking-calendar', array('Spin', 'admin_menu_options'));
        if (version_compare($GLOBALS['wp_version'], '3.3', '>=')) {
            //add_action( "load-$hook", array( 'Spin', 'admin_help' ) );
        }
    }

    public static function view( $name, array $args = array() ) {
        $args = apply_filters( 'super-booking-calendar_view_arguments', $args, $name );
        foreach ( $args AS $key => $val ) {
            $$key = $val;
        }
        $file = plugin_dir_path(__FILE__) . 'views/'. $name . '.php';
        include( $file );
    }

}