<?php
// Função para adicionar a página de configurações no menu administrativo
function ticketz_tracker_settings_menu() {
    add_options_page('Configurações do Ticketz Tracker', 'Ticketz Tracker', 'manage_options', 'ticketz_tracker', 'ticketz_tracker_settings_page');
}

// Função para renderizar a página de configurações
function ticketz_tracker_settings_page() {
    ?>
    <div class="wrap">
        <h2>Configurações do Ticketz Tracker</h2>
        <form method="post" action="options.php">
            <?php
                settings_fields('ticketz_tracker_settings_group');
                do_settings_sections('ticketz_tracker');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Função para registrar as configurações
function ticketz_tracker_register_settings() {
    register_setting('ticketz_tracker_settings_group', 'companies_id');
    register_setting('ticketz_tracker_settings_group', 'rotator_url');

    add_settings_section('ticketz_tracker_main_section', 'Configurações Principais', 'ticketz_tracker_main_settings_callback', 'ticketz_tracker');
    add_settings_field('companies_id', 'ID da Companhia', 'ticketz_tracker_companies_id_callback', 'ticketz_tracker', 'ticketz_tracker_main_section');
    add_settings_field('rotator_url', 'URL do Rotator', 'ticketz_tracker_rotator_url_callback', 'ticketz_tracker', 'ticketz_tracker_main_section');
}

// Callback para o campo rotator_url
function ticketz_tracker_rotator_url_callback() {
    $rotator_url = esc_attr(get_option('rotator_url'));
    echo '<input type="text" name="rotator_url" value="' . $rotator_url . '" />';
}


// Callback para o campo companies_id
function ticketz_tracker_companies_id_callback() {
    $companies_id = esc_attr(get_option('companies_id'));
    echo '<input type="text" name="companies_id" value="' . $companies_id . '" />';
}

// Adicionar as ações
add_action('admin_menu', 'ticketz_tracker_settings_menu');
add_action('admin_init', 'ticketz_tracker_register_settings');
