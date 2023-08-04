<?php

class Ticketz_Tracker_Activator {

    public static function activate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Criando a tabela para armazenar os dados do usuário
        $table_name_user_data = $wpdb->prefix . 'ticketz_tracker_user_data';
        $sql_user_data = "CREATE TABLE $table_name_user_data (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            sessionID VARCHAR(50) NOT NULL,
            fbclid VARCHAR(255) DEFAULT '' NOT NULL,
            gclid VARCHAR(255) DEFAULT '' NOT NULL,
            utm_id VARCHAR(255) DEFAULT '' NOT NULL,
            utm_source VARCHAR(255) DEFAULT '' NOT NULL,
            utm_medium VARCHAR(255) DEFAULT '' NOT NULL,
            utm_campaign VARCHAR(255) DEFAULT '' NOT NULL,
            utm_term VARCHAR(255) DEFAULT '' NOT NULL,
            utm_content VARCHAR(255) DEFAULT '' NOT NULL,
            link_id VARCHAR(255) DEFAULT '' NOT NULL,
            src VARCHAR(255) DEFAULT '' NOT NULL,
            companies_id mediumint(9) NOT NULL,
            campaign_id mediumint(9) NOT NULL,
            funnel_id mediumint(9) NOT NULL,
            channel_id mediumint(9) NOT NULL,
            ip_address VARCHAR(50) DEFAULT '' NOT NULL,
            user_agent VARCHAR(255) DEFAULT '' NOT NULL,
            referrer VARCHAR(255) DEFAULT '' NOT NULL,
            date_time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        $table_name_urls = $wpdb->prefix . 'ticketz_tracker_urls';
        $sql_urls = "CREATE TABLE $table_name_urls (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            url varchar(255) NOT NULL,
            click_count mediumint(9) DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_user_data );
        dbDelta( $sql_urls );
    }
}
