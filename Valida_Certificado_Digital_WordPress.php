// Função para validar o certificado - Inserir em Funcions PHP e utilizar shortcode [cv_certificate_form]
function cv_validate_certificate($certificate_content) {
    $cert_info = openssl_x509_parse($certificate_content);

    if ($cert_info) {
        $valid_to = date('Y-m-d H:i:s', $cert_info['validTo_time_t']);
        $current_date = date('Y-m-d H:i:s');

        if ($current_date < $valid_to) {
            return 'O certificado é válido até ' . $valid_to;
        } else {
            return 'O certificado expirou em ' . $valid_to;
        }
    } else {
        return 'O certificado não é válido.';
    }
}

// Manipulador AJAX para validar o certificado
function cv_handle_certificate_upload() {
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] == UPLOAD_ERR_OK) {
        $certificate_content = file_get_contents($_FILES['certificate']['tmp_name']);
        $result = cv_validate_certificate($certificate_content);
        wp_send_json_success($result);
    } else {
        wp_send_json_error('Erro no upload do certificado.');
    }
}

add_action('wp_ajax_cv_validate_certificate', 'cv_handle_certificate_upload');
add_action('wp_ajax_nopriv_cv_validate_certificate', 'cv_handle_certificate_upload');

// Shortcode para exibir o formulário de verificação
function cv_certificate_form() {
    ob_start();
    ?>
    <div id="certificate-validation-popup" style="display:none;">
        <div id="certificate-validation-result"></div>
        <button id="close-popup">Fechar</button>
        <a href="/link-para-compra-de-certificados">Comprar Certificados</a>
    </div>
    <form id="certificate-upload-form" enctype="multipart/form-data">
        <label for="certificate">Upload do Certificado (em formato PEM):</label>
        <input type="file" id="certificate" name="certificate" accept=".pem">
        <input type="submit" value="Verificar">
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#certificate-upload-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData();
                formData.append('certificate', $('#certificate')[0].files[0]);
                formData.append('action', 'cv_validate_certificate');

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#certificate-validation-result').html(response.data);
                        } else {
                            $('#certificate-validation-result').html(response.data);
                        }
                        $('#certificate-validation-popup').show();
                    },
                    error: function() {
                        $('#certificate-validation-result').html('Erro ao validar o certificado.');
                        $('#certificate-validation-popup').show();
                    }
                });
            });

            $('#close-popup').on('click', function() {
                $('#certificate-validation-popup').hide();
            });
        });
    </script>
    <style>
        #certificate-validation-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            z-index: 1000;
        }
        #certificate-validation-popup #close-popup {
            margin-top: 10px;
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('cv_certificate_form', 'cv_certificate_form');
