<?php
class Kalin_Development_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page() {
        add_options_page(
            'Kalin Development', // page_title
            'Kalin Development', // menu_title
            'manage_options',    // capability
            'kalin-development', // menu_slug
            array($this, 'create_admin_page') // function
        );
    }

    public function create_admin_page() {
        // Verifica o usuário atual tem permissão para isso
        if (!current_user_can('manage_options')) {
            wp_die(__('Você não tem permissões suficientes para acessar esta página.'));
        }
        ?>
        <div class="wrap">
            <h1>Kalin Development</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields('kalin_option_group');
                do_settings_sections('kalin-development-admin');
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {
        register_setting(
            'kalin_option_group', // option_group
            'kalin_development_options', // option_name
            array($this, 'sanitize') // sanitize_callback
        );

        add_settings_section(
            'setting_section_id', // id
            'Configurações do Plugin', // title
            array($this, 'print_section_info'), // callback
            'kalin-development-admin' // page
        );

        add_settings_field(
            'slider_swiperjs', // id
            'Ativar Slider SwiperJS', // title
            array($this, 'slider_swiperjs_callback'), // callback
            'kalin-development-admin', // page
            'setting_section_id' // section
        );

        add_settings_field(
            'whatsapp_order_message', 
            'Ativar WhatsApp Order Message', 
            array($this, 'whatsapp_order_message_callback'), 
            'kalin-development-admin', 
            'setting_section_id'
        );
    }

    public function sanitize($input) {
        $new_input = array();
        $new_input['slider_swiperjs'] = sanitize_text_field($input['slider_swiperjs']);
        $new_input['whatsapp_order_message'] = sanitize_text_field($input['whatsapp_order_message']);
        return $new_input;
    }

    public function print_section_info() {
        print 'Selecione os recursos que deseja ativar:';
    }

    public function slider_swiperjs_callback() {
        $options = get_option('kalin_development_options');
        $checked = $options['slider_swiperjs'] == '1' ? 'checked' : '';
        echo '<input type="checkbox" id="slider_swiperjs" name="kalin_development_options[slider_swiperjs]" value="1" '.$checked.' />';
    }

    public function whatsapp_order_message_callback() {
        $options = get_option('kalin_development_options');
        $checked = $options['whatsapp_order_message'] == '1' ? 'checked' : '';
        echo '<input type="checkbox" id="whatsapp_order_message" name="kalin_development_options[whatsapp_order_message]" value="1" '.$checked.' />';
    }
}

if (is_admin())
    $kalin_development_admin = new Kalin_Development_Admin();
?>
