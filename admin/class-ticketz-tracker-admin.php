<?php

class Ticketz_Tracker_Admin {

    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ticketz-tracker-admin.css', array(), $this->version, 'all' );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ticketz-tracker-admin.js', array( 'jquery' ), $this->version, false );
    }

    public function add_plugin_admin_menu() {
        add_options_page( 'Configuração do Ticketz Tracker', 'Ticketz Tracker', 'manage_options', $this->plugin_name, array($this, 'display_plugin_admin_page') );
    }

    public function display_plugin_admin_page() {
        include_once( 'partials/ticketz-tracker-admin-display.php' );
    }

    public function register_settings() {
        register_setting( 'ticketz-tracker', 'companies_id' );
    }

    function ticketz_tracker_add_url($url) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ticketz_tracker_urls';
        $wpdb->insert(
            $table_name,
            array(
                'url' => $url,
            )
        );
    }

    function ticketz_tracker_list_urls() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ticketz_tracker_urls';
        return $wpdb->get_results("SELECT * FROM $table_name");
    }

    function ticketz_tracker_update_url($id, $url) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ticketz_tracker_urls';
        $wpdb->update(
            $table_name,
            array('url' => $url),
            array('id' => $id)
        );
    }

    function ticketz_tracker_delete_url($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ticketz_tracker_urls';
        $wpdb->delete(
            $table_name,
            array('id' => $id)
        );
    }
    
    
    
    
}
