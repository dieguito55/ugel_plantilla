<?php
/**
 * Panel de Administración del Chatbot UGEL El Collao - CORREGIDO
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

class UgelChatbotAdmin {
    
    private $json_file;
    
    public function __construct() {
    $child_json = get_stylesheet_directory() . '/chatbot-responses.json';
    $parent_json = get_template_directory() . '/chatbot-responses.json';
    $this->json_file = file_exists($child_json) ? $child_json : $parent_json;

    add_action('admin_menu', array($this, 'add_admin_menu'));
    add_action('admin_post_save_chatbot_response', array($this, 'save_response'));
    add_action('admin_post_delete_chatbot_response', array($this, 'delete_response'));
    add_action('admin_post_update_chatbot_settings', array($this, 'update_settings'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
}

    
    public function add_admin_menu() {
        add_menu_page(
            'Chatbot UGEL',
            'Chatbot',
            'manage_options',
            'ugel-chatbot',
            array($this, 'admin_page'),
            'dashicons-format-chat',
            30
        );
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_ugel-chatbot') {
            return;
        }
        
        wp_enqueue_style('ugel-chatbot-admin', get_stylesheet_directory_uri() . '/assets/chatbot-admin.css', array(), '1.0.0');
        wp_enqueue_script('ugel-chatbot-admin', get_stylesheet_directory_uri() . '/assets/chatbot-admin.js', array('jquery'), '1.0.0', true);
    }
    
    public function get_responses() {
        if (!file_exists($this->json_file)) {
            // Crear archivo por defecto si no existe
            $this->create_default_json();
        }
        
        $content = file_get_contents($this->json_file);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Error JSON en chatbot: ' . json_last_error_msg());
            return array('responses' => array(), 'settings' => array());
        }
        
        return $data;
    }
    
    private function create_default_json() {
        $default_data = array(
            'responses' => array(
                array(
                    'id' => 1,
                    'category' => 'Saludo',
                    'keywords' => array('hola', 'buenos días', 'buenas tardes', 'ayuda'),
                    'response' => '¡Hola! Soy el asistente virtual de la UGEL El Collao. ¿En qué puedo ayudarte?',
                    'active' => true
                )
            ),
            'settings' => array(
                'greeting' => '👋 ¡Hola! Soy el asistente virtual de la UGEL El Collao.<br><br>¿En qué puedo ayudarte hoy?',
                'fallback' => 'Para más información, contacta con nosotros al 974 202 598',
                'typing_delay' => 1500,
                'match_threshold' => 1
            )
        );
        
        $this->save_responses($default_data);
    }
    
    public function save_responses($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $result = file_put_contents($this->json_file, $json);
        
        if ($result !== false) {
            // Asegurar permisos correctos
            chmod($this->json_file, 0666);
            
            // Limpiar caché si existe
            if (function_exists('wp_cache_flush')) {
                wp_cache_flush();
            }
        }
        
        return $result !== false;
    }
    
    public function save_response() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['_wpnonce'], 'save_chatbot_response')) {
            wp_die('No tienes permisos para realizar esta acción.');
        }
        
        $data = $this->get_responses();
        $responses = $data['responses'] ?? array();
        
        $response_data = array(
            'id' => intval($_POST['response_id']),
            'category' => sanitize_text_field($_POST['category']),
            'keywords' => array_map('trim', array_filter(explode(',', sanitize_text_field($_POST['keywords'])))),
            'response' => wp_kses_post($_POST['response']),
            'active' => isset($_POST['active'])
        );
        
        if ($response_data['id'] === 0) {
            // Nuevo registro
            $response_data['id'] = $this->get_next_id($responses);
            $responses[] = $response_data;
        } else {
            // Actualizar existente
            foreach ($responses as &$resp) {
                if ($resp['id'] === $response_data['id']) {
                    $resp = $response_data;
                    break;
                }
            }
        }
        
        $data['responses'] = $responses;
        
        if ($this->save_responses($data)) {
            wp_redirect(admin_url('admin.php?page=ugel-chatbot&message=saved'));
        } else {
            wp_redirect(admin_url('admin.php?page=ugel-chatbot&message=error'));
        }
        exit;
    }
    
    public function delete_response() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_GET['_wpnonce'], 'delete_response_' . $_GET['id'])) {
            wp_die('No tienes permisos para realizar esta acción.');
        }
        
        $data = $this->get_responses();
        $responses = $data['responses'] ?? array();
        $id = intval($_GET['id']);
        
        $responses = array_filter($responses, function($resp) use ($id) {
            return $resp['id'] !== $id;
        });
        
        $data['responses'] = array_values($responses);
        
        if ($this->save_responses($data)) {
            wp_redirect(admin_url('admin.php?page=ugel-chatbot&message=deleted'));
        } else {
            wp_redirect(admin_url('admin.php?page=ugel-chatbot&message=error'));
        }
        exit;
    }
    
    public function update_settings() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['_wpnonce'], 'update_chatbot_settings')) {
            wp_die('No tienes permisos para realizar esta acción.');
        }
        
        $data = $this->get_responses();
        
        $data['settings'] = array(
            'greeting' => wp_kses_post($_POST['greeting']),
            'fallback' => wp_kses_post($_POST['fallback']),
            'typing_delay' => intval($_POST['typing_delay']),
            'match_threshold' => intval($_POST['match_threshold'])
        );
        
        if ($this->save_responses($data)) {
            wp_redirect(admin_url('admin.php?page=ugel-chatbot&tab=settings&message=saved'));
        } else {
            wp_redirect(admin_url('admin.php?page=ugel-chatbot&tab=settings&message=error'));
        }
        exit;
    }
    
    private function get_next_id($responses) {
        $max_id = 0;
        foreach ($responses as $resp) {
            if ($resp['id'] > $max_id) {
                $max_id = $resp['id'];
            }
        }
        return $max_id + 1;
    }
    
    public function admin_page() {
        $data = $this->get_responses();
        $responses = $data['responses'] ?? array();
        $settings = $data['settings'] ?? array();
        $current_tab = $_GET['tab'] ?? 'responses';
        $message = $_GET['message'] ?? '';
        
        ?>
        <div class="wrap">
            <h1>🤖 Gestión del Chatbot UGEL El Collao</h1>
            
            <?php if ($message): ?>
                <div class="notice <?php echo $message === 'error' ? 'notice-error' : 'notice-success'; ?> is-dismissible">
                    <p>
                        <?php 
                        switch($message) {
                            case 'saved': echo '✅ Guardado correctamente.'; break;
                            case 'deleted': echo '🗑️ Eliminado correctamente.'; break;
                            case 'error': echo '❌ Error al guardar. Verifica los permisos del archivo JSON.'; break;
                        }
                        ?>
                    </p>
                </div>
            <?php endif; ?>
            
            <nav class="nav-tab-wrapper">
                <a href="?page=ugel-chatbot&tab=responses" class="nav-tab <?php echo $current_tab === 'responses' ? 'nav-tab-active' : ''; ?>">
                    📝 Respuestas (<?php echo count($responses); ?>)
                </a>
                <a href="?page=ugel-chatbot&tab=settings" class="nav-tab <?php echo $current_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                    ⚙️ Configuración
                </a>
                <a href="?page=ugel-chatbot&tab=test" class="nav-tab <?php echo $current_tab === 'test' ? 'nav-tab-active' : ''; ?>">
                    🧪 Probar
                </a>
                <a href="?page=ugel-chatbot&tab=help" class="nav-tab <?php echo $current_tab === 'help' ? 'nav-tab-active' : ''; ?>">
                    ❓ Ayuda
                </a>
            </nav>
            
            <?php if ($current_tab === 'responses'): ?>
                <?php $this->render_responses_tab($responses); ?>
            <?php elseif ($current_tab === 'settings'): ?>
                <?php $this->render_settings_tab($settings); ?>
            <?php elseif ($current_tab === 'test'): ?>
                <?php $this->render_test_tab(); ?>
            <?php else: ?>
                <?php $this->render_help_tab(); ?>
            <?php endif; ?>
        </div>
        
        <style>
            .ugel-responses-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            .ugel-responses-table th, .ugel-responses-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
            .ugel-responses-table th { background: #f1f1f1; font-weight: bold; }
            .ugel-responses-table tr:nth-child(even) { background: #f9f9f9; }
            .ugel-keywords { font-size: 12px; color: #666; }
            .ugel-status { font-weight: bold; }
            .ugel-status.active { color: #46b450; }
            .ugel-status.inactive { color: #dc3232; }
            .ugel-form-row { margin: 20px 0; }
            .ugel-form-row label { display: block; font-weight: bold; margin-bottom: 5px; }
            .ugel-form-row input, .ugel-form-row textarea, .ugel-form-row select { width: 100%; max-width: 600px; }
            .ugel-form-row textarea { height: 120px; }
            .ugel-help-box { background: #f1f1f1; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .button-primary { margin-right: 10px; }
            .chatbot-test-area { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin: 20px 0; }
        </style>
        <?php
    }
    
    private function render_responses_tab($responses) {
        $edit_id = $_GET['edit'] ?? 0;
        $edit_response = null;
        
        if ($edit_id > 0) {
            foreach ($responses as $resp) {
                if ($resp['id'] === intval($edit_id)) {
                    $edit_response = $resp;
                    break;
                }
            }
        }
        ?>
        
        <div style="display: flex; gap: 20px; margin-top: 20px;">
            
            <!-- Formulario -->
            <div style="flex: 1; max-width: 500px;">
                <div class="postbox">
                    <div class="postbox-header">
                        <h2><?php echo $edit_response ? '✏️ Editar Respuesta' : '➕ Nueva Respuesta'; ?></h2>
                    </div>
                    <div class="inside">
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <input type="hidden" name="action" value="save_chatbot_response">
                            <input type="hidden" name="response_id" value="<?php echo $edit_response['id'] ?? 0; ?>">
                            <?php wp_nonce_field('save_chatbot_response'); ?>
                            
                            <div class="ugel-form-row">
                                <label>Categoría:</label>
                                <input type="text" name="category" value="<?php echo esc_attr($edit_response['category'] ?? ''); ?>" placeholder="Ej: Información Básica" required>
                            </div>
                            
                            <div class="ugel-form-row">
                                <label>Palabras Clave (separadas por comas):</label>
                                <input type="text" name="keywords" value="<?php echo esc_attr(implode(', ', $edit_response['keywords'] ?? array())); ?>" placeholder="hola, saludo, buenos días" required>
                                <small>Si el mensaje contiene alguna de estas palabras, se activará esta respuesta.</small>
                            </div>
                            
                            <div class="ugel-form-row">
                                <label>Respuesta:</label>
                                <textarea name="response" placeholder="Escribe la respuesta del chatbot aquí..." required><?php echo esc_textarea($edit_response['response'] ?? ''); ?></textarea>
                                <small>Puedes usar HTML básico como &lt;br&gt;, &lt;strong&gt;, &lt;a&gt;, etc.</small>
                            </div>
                            
                            <div class="ugel-form-row">
                                <label>
                                    <input type="checkbox" name="active" <?php checked($edit_response['active'] ?? true); ?>>
                                    Activo
                                </label>
                            </div>
                            
                            <button type="submit" class="button-primary">
                                <?php echo $edit_response ? '💾 Actualizar' : '➕ Crear'; ?>
                            </button>
                            
                            <?php if ($edit_response): ?>
                                <a href="?page=ugel-chatbot" class="button">❌ Cancelar</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Lista -->
            <div style="flex: 2;">
                <table class="ugel-responses-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoría</th>
                            <th>Palabras Clave</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($responses)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #666;">
                                    No hay respuestas configuradas. ¡Crea la primera!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($responses as $resp): ?>
                                <tr <?php echo $edit_id === $resp['id'] ? 'style="background: #fff2cc;"' : ''; ?>>
                                    <td><?php echo $resp['id']; ?></td>
                                    <td><strong><?php echo esc_html($resp['category']); ?></strong></td>
                                    <td class="ugel-keywords">
                                        <?php echo esc_html(implode(', ', array_slice($resp['keywords'], 0, 3))); ?>
                                        <?php if (count($resp['keywords']) > 3): ?>
                                            <span style="color: #999;">... (+<?php echo count($resp['keywords']) - 3; ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="ugel-status <?php echo $resp['active'] ? 'active' : 'inactive'; ?>">
                                            <?php echo $resp['active'] ? '✅ Activo' : '❌ Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?page=ugel-chatbot&edit=<?php echo $resp['id']; ?>" class="button button-small">✏️ Editar</a>
                                        <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=delete_chatbot_response&id=' . $resp['id']), 'delete_response_' . $resp['id']); ?>" 
                                           class="button button-small" 
                                           onclick="return confirm('¿Estás seguro de eliminar esta respuesta?');">🗑️ Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
        <?php
    }
    
    private function render_settings_tab($settings) {
        $defaults = array(
            'greeting' => '¡Hola! Soy el asistente virtual de la UGEL El Collao.',
            'fallback' => 'No tengo información específica sobre tu consulta. Por favor, contacta con nosotros.',
            'typing_delay' => 1500,
            'match_threshold' => 1
        );
        
        $settings = array_merge($defaults, $settings);
        ?>
        
        <div style="max-width: 800px; margin-top: 20px;">
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="update_chatbot_settings">
                <?php wp_nonce_field('update_chatbot_settings'); ?>
                
                <div class="postbox">
                    <div class="postbox-header">
                        <h2>⚙️ Configuración General</h2>
                    </div>
                    <div class="inside">
                        
                        <div class="ugel-form-row">
                            <label>Mensaje de Bienvenida:</label>
                            <textarea name="greeting" rows="4"><?php echo esc_textarea($settings['greeting']); ?></textarea>
                            <small>Este mensaje se muestra cuando el usuario abre el chat por primera vez.</small>
                        </div>
                        
                        <div class="ugel-form-row">
                            <label>Mensaje por Defecto (cuando no encuentra respuesta):</label>
                            <textarea name="fallback" rows="4"><?php echo esc_textarea($settings['fallback']); ?></textarea>
                            <small>Se muestra cuando ninguna palabra clave coincide con el mensaje del usuario.</small>
                        </div>
                        
                        <div class="ugel-form-row">
                            <label>Retraso de Escritura (milisegundos):</label>
                            <input type="number" name="typing_delay" value="<?php echo intval($settings['typing_delay']); ?>" min="500" max="5000">
                            <small>Tiempo que el bot "piensa" antes de responder (1000 = 1 segundo).</small>
                        </div>
                        
                        <div class="ugel-form-row">
                            <label>Número Mínimo de Coincidencias:</label>
                            <input type="number" name="match_threshold" value="<?php echo intval($settings['match_threshold']); ?>" min="1" max="3">
                            <small>Cuántas palabras clave deben coincidir para activar una respuesta.</small>
                        </div>
                        
                        <button type="submit" class="button-primary">💾 Guardar Configuración</button>
                        
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    
    private function render_test_tab() {
        ?>
        <div class="chatbot-test-area">
            <h3>🧪 Probar Chatbot</h3>
            <p>Escribe un mensaje para probar cómo responde el chatbot:</p>
            
            <div style="margin: 20px 0;">
                <input type="text" id="testMessage" placeholder="Escribe tu mensaje aquí..." style="width: 70%; padding: 10px;">
                <button id="testSend" class="button-primary">Probar</button>
            </div>
            
            <div id="testResult" style="background: #f9f9f9; padding: 15px; border-radius: 5px; min-height: 50px; margin-top: 20px;">
                <em>La respuesta aparecerá aquí...</em>
            </div>
            
            <script>
            jQuery(document).ready(function($) {
                $('#testSend').click(function() {
                    var message = $('#testMessage').val().trim();
                    if (!message) {
                        alert('Escribe un mensaje para probar');
                        return;
                    }
                    
                    $('#testResult').html('<em>Procesando...</em>');
                    
                    $.post(ajaxurl, {
                        action: 'ugel_chatbot_response',
                        message: message,
                        nonce: '<?php echo wp_create_nonce('ugel_chatbot_nonce'); ?>'
                    }, function(response) {
                        try {
                            var data = JSON.parse(response);
                            $('#testResult').html('<strong>Respuesta:</strong><br>' + data.response);
                        } catch (e) {
                            $('#testResult').html('<span style="color: red;">Error: ' + response + '</span>');
                        }
                    });
                });
                
                $('#testMessage').keypress(function(e) {
                    if (e.which == 13) {
                        $('#testSend').click();
                    }
                });
            });
            </script>
        </div>
        <?php
    }
    
    private function render_help_tab() {
        ?>
        <div style="max-width: 800px; margin-top: 20px;">
            
            <div class="ugel-help-box">
                <h3>🚀 Cómo Funciona el Chatbot</h3>
                <p>El chatbot busca <strong>palabras clave</strong> en los mensajes de los usuarios y responde con las respuestas que hayas configurado.</p>
                
                <h4>📝 Creando Respuestas Efectivas:</h4>
                <ul>
                    <li><strong>Palabras Clave:</strong> Usa sinónimos y variaciones (ej: "horario, horarios, atención, atienden")</li>
                    <li><strong>Categorías:</strong> Agrupa respuestas similares (ej: "Contacto", "Trámites", "Horarios")</li>
                    <li><strong>HTML:</strong> Puedes usar etiquetas como &lt;br&gt;, &lt;strong&gt;, &lt;a href=""&gt;, etc.</li>
                </ul>
            </div>
            
            <div class="ugel-help-box">
                <h3>🔧 Información Técnica</h3>
                <p><strong>Archivo de Datos:</strong> <code><?php echo $this->json_file; ?></code></p>
                <p><strong>Estado del Archivo:</strong> 
                    <?php if (file_exists($this->json_file)): ?>
                        <span style="color: green;">✅ Existe</span>
                        <?php if (is_writable($this->json_file)): ?>
                            <span style="color: green;">✅ Escribible</span>
                        <?php else: ?>
                            <span style="color: red;">❌ No escribible</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span style="color: red;">❌ No existe</span>
                    <?php endif; ?>
                </p>
                <p><strong>Total de Respuestas:</strong> <?php echo count($this->get_responses()['responses'] ?? array()); ?></p>
            </div>
            
        </div>
        <?php
    }
}

// Inicializar el admin del chatbot
new UgelChatbotAdmin();
?>