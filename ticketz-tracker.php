<?php
/**
 * Plugin Name: Ticketz Tracker
 * Description: Combinação das funcionalidades dos plugins "url-rotator-manager" e "whatsapp_conversion_tracking".
 * Version: 1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'TICKETZ_TRACKER_VERSION', '1.0.0' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-ticketz-tracker.php';

function activate_ticketz_tracker() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ticketz-tracker-activator.php';
    Ticketz_Tracker_Activator::activate();
}

function deactivate_ticketz_tracker() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ticketz-tracker-deactivator.php';
    Ticketz_Tracker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ticketz_tracker' );
register_deactivation_hook( __FILE__, 'deactivate_ticketz_tracker' );

function run_ticketz_tracker() {
    $plugin = new Ticketz_Tracker();
    $plugin->run();
}

run_ticketz_tracker();
?>
