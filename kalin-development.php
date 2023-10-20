<?php
/**
 * Plugin Name: Kalin Development
 * Plugin URI: https://kalin.digital
 * Description: Plugin para desenvolvimento de temas e plugins (não desative ou atualize sem a permissão do seu programador).
 * Version: 1.0.2
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

 define( 'LANDINMAX__VERSION', '1.0.0.0' );

require dirname(__FILE__) . '/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/kalindigital/kalin-development',
    __FILE__,
    'kalin-development'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');
