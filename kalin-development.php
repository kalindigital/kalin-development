<?php
/**
 * Plugin Name: Kalin Development
 * Plugin URI: https://kalin.digital
 * Description: Plugin para desenvolvimento de temas e plugins (não desative ou atualize sem a permissão do seu programador).
 * Version: 1.0.0
 * Author: Kalin Digital
 * Author URI: https://kalin.digital
 * License: GPL2
 */

if (!defined('WPINC')) {
    die;
}

/**
 * Inclui os arquivos de funcionalidades do plugin.
 */
function kalin_development_include_files() {
    $kalin_options = get_option('kalin_development_options');

    if (is_array($kalin_options)) {
        if (isset($kalin_options['slider_swiperjs']) && $kalin_options['slider_swiperjs'] == '1') {
            include plugin_dir_path(__FILE__) . 'includes/slider-swiperjs.php';
        }

        if (isset($kalin_options['whatsapp_order_message']) && $kalin_options['whatsapp_order_message'] == '1') {
            include plugin_dir_path(__FILE__) . 'includes/whatsapp-order-message.php';
        }
    }
}
add_action('plugins_loaded', 'kalin_development_include_files');


/**
 * Carrega o arquivo com as configurações do plugin.
 */
function kalin_development_admin_file() {
    if (is_admin()) {
        include plugin_dir_path(__FILE__) . 'admin/class-kalin-development-admin.php';
    }
}
add_action('plugins_loaded', 'kalin_development_admin_file');


/**
 * Adiciona o Plugin Update Checker.
 */
require 'caminho/para/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/seu-usuario/seu-repositorio',
    __FILE__,
    'nome-unico-do-seu-plugin'
);

//Opcional: Se você estiver usando um repositório privado, especifique o token de acesso como este:
$myUpdateChecker->setAuthentication('seu-token-de-acesso');

//Opcional: Defina o caminho do arquivo de metadados do plugin. O caminho padrão é 'readme.txt', mas você também pode usar um arquivo JSON. Exemplo:
$myUpdateChecker->setMetadataUrl('https://github.com/seu-usuario/seu-repositorio/raw/master/plugin.json');


?>