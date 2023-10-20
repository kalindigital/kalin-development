<?php

// Adicionar Metabox
function whatsapp_message_metabox() {
    add_meta_box(
        'whatsapp_message',         // ID do metabox
        'WhatsApp Message',         // Título
        'whatsapp_message_callback',// Função de callback
        'shop_order',               // Post Type
        'side',                     // Contexto
        'default'                   // Prioridade
    );
}
add_action('add_meta_boxes', 'whatsapp_message_metabox');

// Callback para renderizar o conteúdo do Metabox
function whatsapp_message_callback($post) {
    // Verifica nonce por segurança
    wp_nonce_field(basename(__FILE__), 'whatsapp_message_nonce');

    // Obtém o objeto de pedido
    $order = wc_get_order($post->ID);

    // Obtém o status do pedido
    $order_status = $order->get_status();

    // Define as mensagens padrão para cada status de pedido
    $default_messages = array(
        'pending'    => 'Seu pedido está pendente.',
        'processing' => 'Seu pedido está sendo processado.',
        'on-hold'    => 'Seu pedido está em espera.',
        'completed'  => 'Seu pedido foi completado.',
        'cancelled'  => 'Seu pedido foi cancelado.',
        'refunded'   => 'Seu pedido foi reembolsado.',
        'failed'     => 'Seu pedido falhou.',
    );

    // Obtém a mensagem padrão para o status atual do pedido
    $default_message = isset($default_messages[$order_status]) ? $default_messages[$order_status] : '';

    // Obtém o valor do campo se já estiver salvo, caso contrário, usa a mensagem padrão
    $whatsapp_message = get_post_meta($post->ID, '_whatsapp_message', true) ?: $default_message;

    // Obtém o número de telefone do cliente
    $phone = get_post_meta($post->ID, '_billing_phone', true);

    // Exibe o número de telefone do cliente
    echo '<p>Número do cliente: ' . $phone . '</p>';

    // Campo de entrada de texto
    echo '<textarea id="whatsapp_message" name="whatsapp_message" rows="4" style="width:100%">'.$whatsapp_message.'</textarea>';
    echo '<p id="send_whatsapp_btn" class="button">Enviar via WhatsApp</p>';

    // Adicionando JavaScript
    echo '
    <script type="text/javascript" nowprocket>
    document.addEventListener("DOMContentLoaded", function() {

        document.getElementById("send_whatsapp_btn").addEventListener("click", function() {
            var phone = "'.$phone.'";
var message = document.getElementsByName("whatsapp_message")[0].value;
            console.log(message);
            if(message && message !== "undefined") {
                var whatsapp_url = "https://api.whatsapp.com/send?phone=" + phone + "&text=" + encodeURIComponent(message);
                window.open(whatsapp_url, "_blank");
            } else {
                alert("Por favor, insira uma mensagem.");
            }
        });
    });
    </script>';
}

?>