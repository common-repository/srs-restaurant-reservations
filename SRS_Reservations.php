<?php
/**
  Plugin Name: SRS Restaurant Reservations
  Plugin URI: http://sandyrig.com
  Description: Restaurant Reservations Management System
  Author: Atif N
  Version: 0.1
  Author URI: http://sandyrig.com
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


final class srs_reservations{
    public static $selectedDate;
    public static $_instance;
    public static $action_message;
    public static $error_message;
    public static $reservations;
    public static $reservation_graph;
    public static $settingsData;


    public function  __construct(){
        // Initialize admin init scripts and stuff
        $this->admin_inits();

        // Initialize init scripts and stuff
        $this->front_inits();

        // Gets the current date
        $this->setSelectedDate_init();

        // Initialize error messages array
        self::$error_message = array();

        // Define Constants
        $this->defineConstants();

        // Include required files
        $this->includeFiles();

        // Add admin menus
        $this->registerMenus();

        // Register JS and CSS scripts
        $this->registerScripts();

        // Load settings
        $this->load_settings();

        // Remove admin sidebar menu on the right
//        $this->remove_admin_menu_inits();
    }

    /**
     * Define all the constants
     */
    private function defineConstants(){
        global $wpdb;

        define( 'SRS_PLUGIN_URL', plugins_url() . '/srs-restaurant-reservations' );
        define( 'SRS_PLUGIN_DIR', plugin_dir_path(__file__) );

        define( 'SRS_CUSTOMERS_TABLE', $wpdb->prefix . 'srs_customers' );
        define( 'SRS_RESERVATIONS_TABLE', $wpdb->prefix . 'srs_reservations' );
    }

    /**
     * Include required files
     */
    private function includeFiles(){
        // Classes
        include_once ( 'includes/install.php' );
        include_once ( 'includes/admin/classes/class_reservations_ops.php' );
        include_once ( 'includes/calendar/class_calendar.php' );
        include_once ( 'includes/graph/class_graph.php' );
        // Classes- Frontend
        include_once ( 'includes/srs/class_front.php');

        // Templates
        include_once ( 'includes/admin/templates/header.php' );
        include_once ( 'includes/admin/templates/menu.php');
        include_once ( 'includes/admin/templates/admin.php');
        include_once ( 'includes/admin/templates/settings.php');
        include_once ( 'includes/admin/templates/make-reservations.php');
        include_once ( 'includes/admin/templates/list-reservations.php');
        // Templates Frontend
        include_once ( 'includes/srs/template.php');

        register_activation_hook(__FILE__, 'installNewTables');
    }

    /**
     * Register and load Admin menus
     */
    private function registerMenus(){
        add_action('admin_menu', array( $this, 'add_menu_function_call' ));
    }
    public function add_menu_function_call(){
        $page           = add_menu_page('SRS Reservations', 'Reservations', 'administrator', 'srs-reservations', array( $this, 'admin_settings_page_home' ));
        $settings_page  = add_submenu_page('srs-reservations', 'SRS Reservations Settings', 'Settings', 'administrator', 'srs-reservations-settings', array( $this, 'admin_settings_page_settings' ));
        add_action( 'admin_print_styles-' . $page, array( $this, 'srs_enque_admin_scripts') );
        add_action( 'admin_print_styles-' . $settings_page, array( $this, 'srs_enque_admin_scripts') );
    }
    public function admin_settings_page_home(){
        call_admin_homepage();
    }

    public function admin_settings_page_settings(){
        call_admin_settings();
    }

    /**
     * Register and load JS and CSS scripts
     */
    private function registerScripts(){
        add_action('admin_init', array( $this, 'srs_register_admin_scripts' ) );
        add_action('wp_enqueue_scripts', array( $this, 'srs_register_scripts' ) );
    }

    // Register Admin Scripts
    public function srs_register_admin_scripts(){
        // Admin style
        wp_register_style( 'srs-admin-stylesheet', SRS_PLUGIN_URL . '/assets/css/admin-style.css' );
        // style
        wp_register_style( 'srs-admin-stylesheet', SRS_PLUGIN_URL . '/assets/css/style.css' );
        // Date picker style
        wp_register_style( 'srs-datepicker-stylesheet', SRS_PLUGIN_URL . '/assets/js/datetimepicker/jquery.datetimepicker.css' );
        // jQuery UI style
        wp_register_style( 'srs-jquery-ui-stylesheet', SRS_PLUGIN_URL . '/assets/css/jquery-ui/flick/jquery-ui.min.css' );
        // Date picker script
        wp_register_script( 'srs-datepicker-script', SRS_PLUGIN_URL . '/assets/js/datetimepicker/jquery.datetimepicker.js' );
        // Admin script
        wp_register_script( 'srs-admin-script', SRS_PLUGIN_URL . '/assets/js/admin.js' );
        // Highcharts
        wp_register_script( 'srs-highcharts-script', 'http://code.highcharts.com/highcharts.js' );

    }

    // Enqueue Admin Scripts
    public function srs_enque_admin_scripts(){
        wp_enqueue_style( 'srs-admin-stylesheet' );
        wp_enqueue_style( 'srs-datepicker-stylesheet' );
        wp_enqueue_style( 'srs-jquery-ui-stylesheet' );
        wp_enqueue_script( 'srs-datepicker-script' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'srs-admin-script' );
        wp_enqueue_script( 'srs-highcharts-script' );
    }

    // Register and Enqueue scripts - frontend
    public function srs_register_scripts(){
        // style
        wp_register_style( 'srs-stylesheet', SRS_PLUGIN_URL . '/assets/css/style.css' );
        wp_enqueue_style( 'srs-stylesheet' );

        // Javascript
        wp_register_script  ( 'srs-frontend-js', SRS_PLUGIN_URL . '/assets/js/reservation_form.js' );
        wp_enqueue_script   ( 'srs-frontend-js' );
    }

    /**
     * @return srs_reservations
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function admin_inits(){
        add_action('admin_init', array( $this, 'admin_inits_call' ), 1);
    }

    public static function admin_inits_call(){
        self::start_session();
    }

    public static function start_session(){
        if(!session_id()){
            session_start();
        }
    }

    public function front_inits(){
        add_action('init', array( $this, 'front_inits_call' ), 1);
    }

    public static function front_inits_call(){
        self::start_session();
    }

    public function load_settings(){
        self::$settingsData = reservations_ops::srs_get_settings();
    }

    /**
     * Sets the selected date for both front and backend
     */
    public function setSelectedDate_init(){
        add_action('admin_init', array( $this, 'setSelectedDate' ), 15 );
        add_action('init', array( $this, 'setSelectedDate' ), 15 );
    }

    public static function setSelectedDate(){
        if(isset($_GET['selected_date'])){
            self::$selectedDate = $_GET['selected_date'];
            $_SESSION['srs-selected-date'] = $_GET['selected_date'];
            wp_redirect(remove_query_arg(array('selected_date')));
            exit;
        }elseif($_SESSION['srs-selected-date']){
            self::$selectedDate = $_SESSION['srs-selected-date'];
        }else{
            self::$selectedDate = date('Y-m-d');
        }
        srs_reservations::srs_debug(self::$selectedDate, 'Selected Date');
    }

    /**
     * Gets selected date
     */
    public function selectedDate(){
        return self::$selectedDate;
    }

    public static function todaysDate(){
        return date('Y-m-d');
    }

    public static function add_calendar(){
        ?>
        <script type="text/javascript">
            <?php date_default_timezone_set('UTC'); ?>
            jQuery('#srs-reservation-date').datetimepicker({
                format:'Y-m-d',
                inline:true,
                timepicker: false
            });
        </script>
    <?php
    }

    private function remove_admin_menu_inits()
    {
        if ($_GET['page'] == 'srs-reservations') {?>
        <style type="text/css">
            #adminmenuwrap, #adminmenuback, #wpfooter, #wpadminbar {
                display: none!important;
            }
            #wpcontent, #footer { margin-left: 0px!important; }
        </style>
        <?php
        }
    }

    public static function srs_debug($error, $event='not provided', $type='v'){
        array_push(self::$error_message, array('message'=>$error, 'type'=>$type, 'event'=>$event));
//        if($type='a'){
//            print_r($error);
//        }else{
//            echo $error;
//        }
    }

    public static function save_action_message($message){
        self::$action_message = $message;
    }



}

function SRS_INSTANCE(){
    return srs_reservations::instance();
}

$GLOBALS['srs_reservations'] = SRS_INSTANCE();








?>