<?php
function criar_tipo_postagem_slider() {
    $args = array(
        'public' => true,
        'label'  => 'Sliders',
        'supports' => array('title', )
    );
    register_post_type('slider', $args);
}
add_action('init', 'criar_tipo_postagem_slider');

// Enqueue SwiperJS
function enqueue_slider_scripts() {
    wp_enqueue_style('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_slider_scripts');

// Adicionar meta box para slides
function criar_meta_box_slider() {
    add_meta_box(
        'meta_box_id',       // ID único para o meta box
        'Configurações do Slide',   // Título do meta box
        'exibir_meta_box_slider',  // Callback para renderizar o conteúdo do meta box
        'slider',              // Post type onde o meta box será exibido
        'normal',            // Contexto
    );
}
add_action('add_meta_boxes', 'criar_meta_box_slider');

// Salvar dados do meta box
function exibir_meta_box_slider($post) {
    wp_nonce_field(basename(__FILE__), 'slider_nonce');
    $stored_meta = get_post_meta($post->ID, 'slides', true);
    $stored_meta = is_array($stored_meta) ? $stored_meta : array();  // Verifica se $stored_meta é um array
    $num_slides = count($stored_meta);  // corrigido
    ?>

    <div id="meta_inner">
        <style>
            .remove-slide {
                color: #a00;
                cursor: pointer;
                margin-top: 10px;
                display: inline-block;
                height: fit-content;
            }
            .slide {
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                display: flex;
                gap: 10px;
            }
            .item-form {
                display: flex;
                gap: 10px;
                flex-direction: column;
                width: 90%;
            }
            .slide label, .label-slider {
                font-weight: 600;
            }

            .label-slider {
                margin-bottom: 10px;
            }

            .cm-s-default {
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                margin-top: 10px;
            }
            .configuracoes-slider {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            #add-slide {
    padding: 6px 16px;
    background: #4eaa4e;
    border-radius: 16px;
    color: white;
    margin-top: 32px;
}
        </style>
    <?php

    for ($i = 0; $i < $num_slides; $i++) {
        ?>
        <div class="slide">
            <div class="item-form">
                <label for="titulo-slider">
                    Título
                </label>
                <input type="text" name="slides[<?php echo $i; ?>][titulo]" value="<?php if(isset($stored_meta[$i]['titulo'])) echo $stored_meta[$i]['titulo']; ?>" placeholder="Título">
            </div>
            <div class="item-form">
                <label for="descricao-slider">
                    Descrição
                </label>
                <textarea name="slides[<?php echo $i; ?>][descricao]" placeholder="Descrição"><?php if(isset($stored_meta[$i]['descricao'])) echo $stored_meta[$i]['descricao']; ?></textarea>
            </div>
            <div class="item-form">
                <label for="texto-botao-slider">
                    Texto e link do Botão
                </label>
                <input type="text" name="slides[<?php echo $i; ?>][texto_botao]" value="<?php if(isset($stored_meta[$i]['texto_botao'])) echo $stored_meta[$i]['texto_botao']; ?>" placeholder="Texto do Botão">
                <input type="text" name="slides[<?php echo $i; ?>][link_botao]" value="<?php if(isset($stored_meta[$i]['link_botao'])) echo $stored_meta[$i]['link_botao']; ?>" placeholder="Link do Botão">
            </div>
            <div class="item-form">
                <label for="">
                    Imagem desktop
                </label>
                <input type="text" name="slides[<?php echo $i; ?>][imagem]" id="slides[<?php echo $i; ?>][imagem]" value="<?php if(isset($stored_meta[$i]['imagem'])) echo $stored_meta[$i]['imagem']; ?>" />
                <button type="button" class="upload-custom-img button"><?php _e('Carregar/Selecionar Imagem'); ?></button>
                <label for="">
                    Imagem mobile
                </label>
                <input type="text" name="slides[<?php echo $i; ?>][imagem_mobile]" id="slides[<?php echo $i; ?>][imagem_mobile]" value="<?php if(isset($stored_meta[$i]['imagem_mobile'])) echo $stored_meta[$i]['imagem_mobile']; ?>" />
                <button type="button" class="upload-custom-img button"><?php _e('Carregar/Selecionar Imagem'); ?></button>
            </div>
            
            <span class="remove-slide"><?php esc_html_e('Remover', 'text-domain'); ?></span>
        </div>
        <?php
    }

    ?>
    <span id="add-slide"><?php esc_html_e('Adicionar Slide', 'text-domain'); ?></span>
    </div>

    <script type="text/javascript" nowprocket>
    jQuery(document).ready(function($) {
        var count = <?php echo $num_slides; ?>;
        $('#add-slide').click(function() {
            count++;
            $('#meta_inner').append(
                '<div class="slide">' +
                    '<div class="item-form">' +
                        '<label for="titulo-slider">Título</label>' +
                        '<input type="text" name="slides[' + count + '][titulo]" value="" placeholder="Título">' +
                    '</div>' +
                    '<div class="item-form">' +
                        '<label for="descricao-slider">Descrição</label>' +
                        '<textarea name="slides[' + count + '][descricao]" placeholder="Descrição"></textarea>' +
                    '</div>' +
                    '<div class="item-form">' +
                        '<label for="texto-botao-slider">Texto e link do Botão</label>' +
                        '<input type="text" name="slides[' + count + '][texto_botao]" value="" placeholder="Texto do Botão">' +
                        '<input type="text" name="slides[' + count + '][link_botao]" value="" placeholder="Link do Botão">' +
                    '</div>' +
                    '<div class="item-form">' +
                        '<label>Imagem desktop</label>' +
                        '<input type="text" name="slides[' + count + '][imagem]" id="slides[' + count + '][imagem]" value="" />' +
                        '<button type="button" class="upload-custom-img button">Carregar/Selecionar Imagem</button>' +
                        '<label>Imagem mobile</label>' +
                        '<input type="text" name="slides[' + count + '][imagem_mobile]" id="slides[' + count + '][imagem_mobile]" value="" />' +
                        '<button type="button" class="upload-custom-img button">Carregar/Selecionar Imagem</button>' +
                    '</div>' +
                    '<span class="remove-slide">Remover</span>' +
                '</div>'
            );
        });
        $('#meta_inner').on('click', '.remove-slide', function() {
            $(this).parent().remove();
        });
    });
</script>


<script type="text/javascript">
jQuery(document).ready(function($) {
    var frame,
        metaBox = $('#meta_inner'), // Your meta box id here
        addImgLink = metaBox.find('.upload-custom-img');

    // ADD IMAGE LINK
    metaBox.on('click', '.upload-custom-img', function(event) {

        event.preventDefault();

        // Obter o campo de imagem correspondente
        var imgField = $(this).prev();

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: 'Selecionar ou Carregar Imagem',
            button: {
                text: 'Usar esta imagem'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        frame.on('select', (function(imgField) {
            return function() {
                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();
                // Send the attachment URL to our custom image input field.
                imgField.val(attachment.url);
            };
        })(imgField));  // Passando imgField como argumento

        // Finally, open the modal on click
        frame.open();
    });
});
</script>


    <?php
}

// Salvar dados do meta box
function salvar_meta_box_slider($post_id) {
    // Verificar nonce
    if (!isset($_POST['slider_nonce']) || !wp_verify_nonce($_POST['slider_nonce'], basename(__FILE__))) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Salvar os dados
    $slides_data = isset($_POST['slides']) ? $_POST['slides'] : array();
    
    // Sanitizar os dados
    foreach ($slides_data as $index => $slide) {
        $slides_data[$index]['titulo'] = sanitize_text_field($slide['titulo']);
        $slides_data[$index]['descricao'] = sanitize_textarea_field($slide['descricao']);
        $slides_data[$index]['texto_botao'] = sanitize_text_field($slide['texto_botao']);
        $slides_data[$index]['link_botao'] = esc_url_raw($slide['link_botao']);
        $slides_data[$index]['image'] = esc_url_raw($slide['image']);
        $slides_data[$index]['image_mobile'] = esc_url_raw($slide['image_mobile']);

    }
    
    update_post_meta($post_id, 'slides', $slides_data);
}
add_action('save_post', 'salvar_meta_box_slider');



// Função para criar o meta box de CSS personalizado
function criar_meta_box_css() {
    add_meta_box(
        'css_meta_box_id',       // ID único para o meta box
        'CSS Personalizado',     // Título do meta box
        'exibir_meta_box_css',   // Callback para renderizar o conteúdo do meta box
        'slider',                // Post type onde o meta box será exibido
        'normal',                // Contexto
        'high'                   // Prioridade
    );
}
add_action('add_meta_boxes', 'criar_meta_box_css');

function enqueue_codemirror_scripts() {
    wp_enqueue_style('codemirror-css', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1/codemirror.min.css');
    wp_enqueue_script('codemirror-js', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1/codemirror.min.js', array(), null, true);
    wp_enqueue_script('codemirror-css-js', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1/mode/css/css.min.js', array('codemirror-js'), null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_codemirror_scripts');


// Função callback para exibir o conteúdo do meta box
function exibir_meta_box_css($post) {
    wp_nonce_field(basename(__FILE__), 'css_nonce');
    $stored_meta = get_post_meta($post->ID);
    ?>

<p>Titulo: h2-slide-swiperjs | Descrição: p-slide-swiperjs | Imagem: img-slide-swiperjs img-mb/img-pc | Botão: btn-slider</p>
    <!-- Campos para CSS Desktop e Mobile -->
    <label class="label-slider" for="css_desktop">CSS Desktop</label>
    <textarea name="css_desktop" id="css_desktop" class="codemirror-textarea" rows="5" style="width:100%;"><?php if (isset($stored_meta['css_desktop'])) echo $stored_meta['css_desktop'][0]; ?></textarea>
    
    <label class="label-slider" for="css_mobile">CSS Mobile</label>
    <textarea name="css_mobile" id="css_mobile" class="codemirror-textarea" rows="5" style="width:100%;"><?php if (isset($stored_meta['css_mobile'])) echo $stored_meta['css_mobile'][0]; ?></textarea>

    <script type="text/javascript" nowprocket>
        jQuery(document).ready(function($) {
            var editorSettings = {
                lineNumbers: true,
                mode: 'css',
            };

            var desktopEditor = CodeMirror.fromTextArea(document.getElementById('css_desktop'), editorSettings);
            var mobileEditor = CodeMirror.fromTextArea(document.getElementById('css_mobile'), editorSettings);
        });
    </script>


    <?php
}

// Função para salvar os dados do meta box
function salvar_meta_box_css($post_id) {
    // Verificar nonce
    if (!isset($_POST['css_nonce']) || !wp_verify_nonce($_POST['css_nonce'], basename(__FILE__))) {
        return;
    }

    // Verificar se é um autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verificar permissão do usuário
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Salvar os dados
    if (isset($_POST['css_desktop'])) {
        update_post_meta($post_id, 'css_desktop', $_POST['css_desktop']);
    }
    if (isset($_POST['css_mobile'])) {
        update_post_meta($post_id, 'css_mobile', $_POST['css_mobile']);
    }
}
add_action('save_post', 'salvar_meta_box_css');


// Função para exibir o meta box de configurações do slider
function criar_meta_box_configuracoes_slider() {
    add_meta_box(
        'configuracoes_slider_meta_box_id',       // ID único para o meta box
        'Configurações do Slider',                // Título do meta box
        'exibir_meta_box_configuracoes_slider',   // Callback para renderizar o conteúdo do meta box
        'slider',                                 // Post type onde o meta box será exibido
        'normal',                                 // Contexto
        'high'                                    // Prioridade
    );
}
add_action('add_meta_boxes', 'criar_meta_box_configuracoes_slider');

// Função callback para exibir o conteúdo do meta box
function exibir_meta_box_configuracoes_slider($post) {
    wp_nonce_field(basename(__FILE__), 'configuracoes_slider_nonce');
    $stored_meta = get_post_meta($post->ID);
    ?>

<div class="configuracoes-slider">
    <label>
        <input type="checkbox" name="autoplay" value="1" <?php checked(isset($stored_meta['autoplay'][0]) ? $stored_meta['autoplay'][0] : '0', '1'); ?>>
        Autoplay
    </label>
    <label>
        Delay:
        <input type="text" name="delay" value="<?php echo isset($stored_meta['delay'][0]) ? $stored_meta['delay'][0] : ''; ?>" placeholder="5000">
    </label>
    <label>
        Efeito de Transição:
        <select name="efeito_transicao">
            <option value="slide" <?php selected(isset($stored_meta['efeito_transicao'][0]) ? $stored_meta['efeito_transicao'][0] : '', 'slide'); ?>>Slide</option>
            <option value="fade" <?php selected(isset($stored_meta['efeito_transicao'][0]) ? $stored_meta['efeito_transicao'][0] : '', 'fade'); ?>>Fade</option>
            <option value="cube" <?php selected(isset($stored_meta['efeito_transicao'][0]) ? $stored_meta['efeito_transicao'][0] : '', 'cube'); ?>>Cube</option>
            <option value="coverflow" <?php selected(isset($stored_meta['efeito_transicao'][0]) ? $stored_meta['efeito_transicao'][0] : '', 'coverflow'); ?>>Coverflow</option>
            <option value="flip" <?php selected(isset($stored_meta['efeito_transicao'][0]) ? $stored_meta['efeito_transicao'][0] : '', 'flip'); ?>>Flip</option>
        </select>
    </label>
    <label>
        <input type="checkbox" name="loop" value="1" <?php checked(isset($stored_meta['loop'][0]) ? $stored_meta['loop'][0] : '0', '1'); ?>>
        Loop
    </label>
    <label>
        <input type="checkbox" name="paginacao" value="1" <?php checked(isset($stored_meta['paginacao'][0]) ? $stored_meta['paginacao'][0] : '0', '1'); ?>>
        Paginação
    </label>
    <label>
        <input type="checkbox" name="navegacao" value="1" <?php checked(isset($stored_meta['navegacao'][0]) ? $stored_meta['navegacao'][0] : '0', '1'); ?>>
        Navegação
    </label>
    <label>
    Altura desktop:
    <input type="text" name="altura_desktop" value="<?php echo isset($stored_meta['altura_desktop'][0]) ? $stored_meta['altura_desktop'][0] : ''; ?>" placeholder="500px">
</label>
<label>
    Altura mobile:
    <input type="text" name="altura_mobile" value="<?php echo isset($stored_meta['altura_mobile'][0]) ? $stored_meta['altura_mobile'][0] : ''; ?>" placeholder="500px">
</label>
</div>


    <?php
}

// Função para salvar os dados do meta box
function salvar_meta_box_configuracoes_slider($post_id) {
    // Verificar nonce
    if (!isset($_POST['configuracoes_slider_nonce']) || !wp_verify_nonce($_POST['configuracoes_slider_nonce'], basename(__FILE__))) {
        return;
    }

    // Verificar se é um autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verificar permissão do usuário
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Salvar as configurações do slider
    update_post_meta($post_id, 'autoplay', isset($_POST['autoplay']) ? '1' : '0');
    update_post_meta($post_id, 'delay', isset($_POST['delay']) ? $_POST['delay'] : '5000');
    update_post_meta($post_id, 'efeito_transicao', isset($_POST['efeito_transicao']) ? $_POST['efeito_transicao'] : 'slide');
    update_post_meta($post_id, 'loop', isset($_POST['loop']) ? '1' : '0');
    update_post_meta($post_id, 'paginacao', isset($_POST['paginacao']) ? '1' : '0');
    update_post_meta($post_id, 'navegacao', isset($_POST['navegacao']) ? '1' : '0');
    update_post_meta($post_id, 'altura_desktop', isset($_POST['altura_desktop']) ? $_POST['altura_desktop'] : '500px');
    update_post_meta($post_id, 'altura_mobile', isset($_POST['altura_mobile']) ? $_POST['altura_mobile'] : '500px');

}

add_action('save_post', 'salvar_meta_box_configuracoes_slider');


// Registrar shortcode para exibir slider
function exibir_slider_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => ''
        ),
        $atts,
        'slider_swiperjs'
    );
    
    $post_id = intval($atts['id']);
    if (!$post_id) return '';
    
    $slides = get_post_meta($post_id, 'slides', true);
    if (!$slides) return '';
    
    // Obter o CSS personalizado
    $css_desktop = get_post_meta($post_id, 'css_desktop', true);
    $css_mobile = get_post_meta($post_id, 'css_mobile', true);

    // Obter configurações do slider
    $autoplay = get_post_meta($post_id, 'autoplay', true) === '1' ? 'true' : 'false';
    $delay = get_post_meta($post_id, 'delay', true) ?: '5000';
    $effect = get_post_meta($post_id, 'efeito_transicao', true) ?: 'slide';
    $loop = get_post_meta($post_id, 'loop', true) === '1' ? 'true' : 'false';
    $pagination = get_post_meta($post_id, 'paginacao', true) === '1' ? 'true' : 'false';
    $navegacao = get_post_meta($post_id, 'navegacao', true) === '1' ? 'true' : 'false';

    $altura_desktop = get_post_meta($post_id, 'altura_desktop', true);
    $altura_mobile = get_post_meta($post_id, 'altura_mobile', true);

    $altura_desktop = $altura_desktop ? $altura_desktop : '500px';  // Se a altura não estiver definida, use '500px' como padrão
    $altura_mobile = $altura_mobile ? $altura_mobile : '500px';  // Se a altura não estiver definida, use '500px' como padrão


    ob_start();
    ?>
    <style>
        .swiper-container {
            height: <?php echo esc_attr($altura_desktop); ?>;
        }

        @media (max-width: 768px) {
            .swiper-container {
                height: <?php echo esc_attr($altura_mobile); ?> !important;
            }
            .image-slide img {
                height: <?php echo esc_attr($altura_mobile); ?> !important;
            }
            .img-mb {
                display: block !important;
            }

            .img-pc {
                display: none !important;
            }
        }
        .image-slide img{
            height: <?php echo esc_attr($altura_desktop); ?>;
        }

        .img-pc {
            display: block;
        }

        .img-mb {
            display: none;
        }
    </style>
    <?php

    echo '<style type="text/css">';
    echo $css_desktop ;  // CSS para Desktop
    echo '@media (max-width: 767px) { ' . $css_mobile . ' }';   // CSS para Mobile
    echo '</style>';
    echo '<div class="swiper-container" style="overflow-x:hidden; position:relative">';
    echo '<div class="swiper-wrapper">';
    foreach ($slides as $slide) {
        echo '<div class="swiper-slide">';
        echo '<div class="image-slide">';
        echo '<img class="img-slide-swiperjs img-pc" src="' . esc_url($slide['imagem']) . '" alt="' . esc_html($slide['titulo']) . '">';
        echo '<img class="img-slide-swiperjs img-mb" src="' . esc_url($slide['imagem_mobile']) . '" alt="' . esc_html($slide['titulo']) . '">';
        echo '</div>';
        echo    '<div class="info-slide">';
        echo        '<h2 class="h2-slide-swiperjs">' . esc_html($slide['titulo']) . '</h2>';
        echo        '<p class="p-slide-swiperjs">' . esc_html($slide['descricao']) . '</p>';
        if ($slide['texto_botao'] && $slide['link_botao']) {
            echo    '<a href="' . esc_url($slide['link_botao']) . '" class="btn-slider">' . esc_html($slide['texto_botao']) . '</a>';
        }
        echo    '</div>';
        echo '</div>';
    }
    
    
    echo '</div>';
    if ($navegacao === 'true') {
        echo '<div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>';
    }

    if ($pagination === 'true') {
        echo '<div class="swiper-pagination"></div>';
    }
    
    echo '</div>';
    ?>
    <script nowprocket>

        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.swiper-container', {
                effect: '<?php echo $effect; ?>',
                autoplay: <?php echo $autoplay; ?> ? {
                    delay: <?php echo $delay; ?>
                } : false,
                loop: <?php echo $loop; ?>,
                
                navigation: <?php echo $navegacao; ?> ? {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                } : false,

                pagination: <?php echo $pagination; ?> ? {
                    el: '.swiper-pagination',
                    clickable: true,
                } : false,
                slidesPerView: 1,
                slidesPerGroup: 1
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('slider_swiperjs', 'exibir_slider_shortcode');
