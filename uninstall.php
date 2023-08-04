<?php
// Se o arquivo uninstall.php for chamado pelo WordPress, saia
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Código para remover a tabela relacionada ao plugin
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ticketz_tracker_data");
