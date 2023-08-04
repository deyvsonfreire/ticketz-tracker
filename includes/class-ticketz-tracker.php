<?php

class Ticketz_Tracker {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'ticketz-tracker';
        $this->version = TICKETZ_TRACKER_VERSION;
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();

        // Adicionar a ação AJAX para salvar os dados da visita do usuário
        add_action('wp_ajax_save_user_visit_data', array($this, 'save_user_visit_data'));
        add_action('wp_ajax_nopriv_save_user_visit_data', array($this, 'save_user_visit_data'));
    }

    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ticketz-tracker-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ticketz-tracker-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ticketz-tracker-public.php';

        $this->loader = new Ticketz_Tracker_Loader();
    }

    private function define_admin_hooks() {
        $plugin_admin = new Ticketz_Tracker_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

        // Outras ações e filtros relacionados ao painel administrativo.
    }

    private function define_public_hooks() {
        $plugin_public = new Ticketz_Tracker_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // Adição do rastreamento da visita do usuário no rodapé
        $this->loader->add_action( 'wp_footer', $this, 'user_visit_tracking' );

        // Outras ações e filtros relacionados à parte pública do site.
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }

    function generateSessionID() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479), mt_rand(32768, 49151),
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    // Função para codificar o sessionID
    function encodeSessionID($num) {
        $NON_PRINTABLES = ['\u200a', '\u200b', '\u200c', '\u200d', '\u200e'];
        $base = count($NON_PRINTABLES);
        $output = "";

        while($num > 0) {
            $output = $NON_PRINTABLES[$num % $base] + $output; // MSB -> LSB
            $num = floor($num / $base);
        }

        return $output;
    }

    public function user_visit_tracking() {
        ?>
        <script type="text/javascript">
        // Função para obter um parâmetro de URL
        function getURLParameter(name) {
            return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[, ""])[1].replace(/\+/g, '%20'))||null;
        }

        // Geração do SessionID
        var sessionID = "<?php echo $this->generateSessionID(); ?>";

        // Coleta as informações da URL
        var fbclid = getURLParameter('fbclid');
        var gclid = getURLParameter('gclid');
        var utm_id = getURLParameter('utm_id');
        var utm_source = getURLParameter('utm_source');
        var utm_medium = getURLParameter('utm_medium');
        var utm_campaign = getURLParameter('utm_campaign');
        var utm_term = getURLParameter('utm_term');
        var utm_content = getURLParameter('utm_content');
        var campaign_id = getURLParameter('campaign_id');
        var link_id = getURLParameter('link_id');
        var funnel_id = getURLParameter('funnel_id');
        var channel_id = getURLParameter('channel_id');
        var src = getURLParameter('src');

        // Coleta informações da visita do usuário
        var visitData = {
            ip_address: "<?php echo $_SERVER['REMOTE_ADDR']; ?>",
            user_agent: "<?php echo $_SERVER['HTTP_USER_AGENT']; ?>",
            referrer: document.referrer,
            date_time: new Date().toISOString().slice(0, 19).replace('T', ' '),
            fbclid: fbclid,
            gclid: gclid,
            utm_id: utm_id,
            utm_source: utm_source,
            utm_medium: utm_medium,
            utm_campaign: utm_campaign,
            utm_term: utm_term,
            utm_content: utm_content,
            campaign_id: campaign_id,
            link_id: link_id,
            funnel_id: funnel_id,
            channel_id: channel_id,
            src: src
        };

        console.log(visitData); // Log para depuração, você pode removê-lo depois

        // Enviar dados para o servidor
        jQuery.ajax({
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            type: 'POST',
            data: {
                action: 'save_user_visit_data',
                visitData: visitData,
                sessionID: sessionID
        },
        success: function(response) {
            console.log(response); // Log para depuração
        },
        error: function(error) {
            console.error(error); // Log para depuração
        }
    });

        </script>
        <?php
    }

    public function save_user_visit_data() {
        global $wpdb;

        $visitData = $_POST['visitData'];
        $sessionID = $_POST['sessionID'];

        // Nome da tabela
        $table_name_user_data = $wpdb->prefix . 'ticketz_tracker_user_data';

        // Inserir dados na tabela
        $wpdb->insert(
            $table_name_user_data,
            array(
                'sessionID' => $sessionID,
                'fbclid' => $visitData['fbclid'],
                'gclid' => $visitData['gclid'],
                'utm_id' => $visitData['utm_id'],
                'utm_source' => $visitData['utm_source'],
                'utm_medium' => $visitData['utm_medium'],
                'utm_campaign' => $visitData['utm_campaign'],
                'utm_term' => $visitData['utm_term'],
                'utm_content' => $visitData['utm_content'],
                'link_id' => $visitData['link_id'],
                'src' => $visitData['src'],
                'companies_id' => $visitData['companies_id'],
                'campaign_id' => $visitData['campaign_id'], // Agora inserido dinamicamente
                'funnel_id' => $visitData['funnel_id'], // Agora inserido dinamicamente
                'channel_id' => $visitData['channel_id'], // Agora inserido dinamicamente
                'ip_address' => $visitData['ip_address'],
                'user_agent' => $visitData['user_agent'],
                'referrer' => $visitData['referrer'],
                'date_time' => $visitData['date_time']
            )
        );

        // Enviar uma resposta de sucesso
        wp_send_json_success('Dados da visita salvos com sucesso.');
    }

}

?>
