<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * UGEL Theme — funciones principales
 * - Limpio de controles de color global y CSS dinámico
 * - Mantiene toda funcionalidad existente
 * - Añade mejoras SEO on-page (OG/Twitter/JSON-LD/Canonical)
 */
class UGELTheme {

    public function __construct() {
        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies')); // ahora no registra extras para convocatorias
        add_action('widgets_init', array($this, 'register_sidebars'));
        add_action('customize_register', array($this, 'customize_register'));
        add_filter('body_class', array($this, 'custom_body_classes'));
    }

    public function theme_setup() {
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('title-tag');
        add_theme_support('menus');
        add_theme_support('widgets');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
        add_theme_support('automatic-feed-links');

        add_image_size('hero-slide', 1920, 800, true);
        add_image_size('featured-large', 800, 400, true);
        add_image_size('featured-medium', 400, 300, true);
        add_image_size('featured-small', 300, 200, true);
        add_image_size('quick-access', 100, 100, true);
        add_image_size('cv-visual', 1200, 900, false);

        register_nav_menus(array(
            'primary' => __('Menú Principal', 'ugel-theme'),
            'social'  => __('Redes Sociales', 'ugel-theme'),
            'footer'  => __('Menú Footer', 'ugel-theme'),
        ));
    }

    public function enqueue_scripts() {
        wp_enqueue_style('ugel-main-style', get_template_directory_uri() . '/assets/css/main-styles.css', array(), '1.0.0');
        wp_enqueue_script('ugel-main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);

        wp_localize_script('ugel-main-script', 'ugel_ajax', array(
            'ajax_url'   => admin_url('admin-ajax.php'),
            'nonce'      => wp_create_nonce('ugel_nonce'),
            'theme_url'  => get_template_directory_uri(),
            'site_views' => function_exists('ugel_get_site_views') ? ugel_get_site_views() : 0,
        ));

        // Tipografías (con display=swap ya incluido en URL)
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@500;700;800;900&display=swap', array(), null);

        if (is_singular('convocatorias')) {
            wp_enqueue_style('datatables-core', 'https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css', array(), '1.13.6');
            wp_enqueue_style('datatables-responsive', 'https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css', array('datatables-core'), '2.5.0');
            wp_enqueue_script('datatables-core', 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', array('jquery'), '1.13.6', true);
            wp_enqueue_script('datatables-responsive', 'https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js', array('datatables-core'), '2.5.0', true);
        }
    }

    public function register_post_types() {
        register_post_type('convocatorias', array(
            'labels' => array(
                'name'               => __('Convocatorias', 'ugel-theme'),
                'singular_name'      => __('Convocatoria', 'ugel-theme'),
                'add_new'            => __('Agregar Nueva', 'ugel-theme'),
                'add_new_item'       => __('Agregar Nueva Convocatoria', 'ugel-theme'),
                'edit_item'          => __('Editar Convocatoria', 'ugel-theme'),
                'new_item'           => __('Nueva Convocatoria', 'ugel-theme'),
                'view_item'          => __('Ver Convocatoria', 'ugel-theme'),
                'search_items'       => __('Buscar Convocatorias', 'ugel-theme'),
                'not_found'          => __('No se encontraron convocatorias', 'ugel-theme'),
                'not_found_in_trash' => __('No hay convocatorias en la papelera', 'ugel-theme')
            ),
            'public'       => true,
            'has_archive'  => true,
            'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon'    => 'dashicons-megaphone',
            'show_in_rest' => true,
            'taxonomies'   => array('category'),
        ));

        register_post_type('comunicados', array(
            'labels' => array(
                'name'               => __('Comunicados', 'ugel-theme'),
                'singular_name'      => __('Comunicado', 'ugel-theme'),
                'add_new'            => __('Agregar Nuevo', 'ugel-theme'),
                'add_new_item'       => __('Agregar Nuevo Comunicado', 'ugel-theme'),
                'edit_item'          => __('Editar Comunicado', 'ugel-theme'),
                'new_item'           => __('Nuevo Comunicado', 'ugel-theme'),
                'view_item'          => __('Ver Comunicado', 'ugel-theme'),
                'search_items'       => __('Buscar Comunicado', 'ugel-theme'),
                'not_found'          => __('No se encontraron comunicados', 'ugel-theme'),
                'not_found_in_trash' => __('No hay comunicados en la papelera', 'ugel-theme')
            ),
            'public'       => true,
            'has_archive'  => true,
            'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon'    => 'dashicons-admin-comments',
            'show_in_rest' => true,
            'taxonomies'   => array('category'),
        ));

        register_post_type('slider', array(
            'labels' => array(
                'name'               => __('Slides', 'ugel-theme'),
                'singular_name'      => __('Slide', 'ugel-theme'),
                'add_new'            => __('Agregar Nuevo', 'ugel-theme'),
                'add_new_item'       => __('Agregar Nuevo Slide', 'ugel-theme'),
                'edit_item'          => __('Editar Slide', 'ugel-theme'),
                'new_item'           => __('Nuevo Slide', 'ugel-theme'),
                'view_item'          => __('Ver Slide', 'ugel-theme'),
                'search_items'       => __('Buscar Slides', 'ugel-theme'),
                'not_found'          => __('No se encontraron slides', 'ugel-theme'),
                'not_found_in_trash' => __('No hay slides en la papelera', 'ugel-theme')
            ),
            'public'       => true,
            'supports'     => array('title', 'editor', 'thumbnail', 'page-attributes'),
            'menu_icon'    => 'dashicons-images-alt2',
            'show_in_rest' => true
        ));

        register_post_type('enlaces', array(
            'labels' => array(
                'name'               => __('Enlaces de Interés', 'ugel-theme'),
                'singular_name'      => __('Enlace', 'ugel-theme'),
                'add_new'            => __('Agregar Nuevo', 'ugel-theme'),
                'add_new_item'       => __('Agregar Nuevo Enlace', 'ugel-theme'),
                'edit_item'          => __('Editar Enlace', 'ugel-theme'),
                'new_item'           => __('Nuevo Enlace', 'ugel-theme'),
                'view_item'          => __('Ver Enlace', 'ugel-theme'),
                'search_items'       => __('Buscar Enlaces', 'ugel-theme'),
                'not_found'          => __('No se encontraron enlaces', 'ugel-theme'),
                'not_found_in_trash' => __('No hay enlaces en la papelera', 'ugel-theme')
            ),
            'public'       => true,
            'supports'     => array('title', 'thumbnail', 'custom-fields', 'page-attributes'),
            'menu_icon'    => 'dashicons-admin-links',
            'show_in_rest' => true
        ));

        register_post_type('tarjetas', array(
            'labels' => array(
                'name'               => __('Tarjetas de Portada', 'ugel-theme'),
                'singular_name'      => __('Tarjeta', 'ugel-theme'),
                'add_new'            => __('Agregar Nueva', 'ugel-theme'),
                'add_new_item'       => __('Agregar Nueva Tarjeta', 'ugel-theme'),
                'edit_item'          => __('Editar Tarjeta', 'ugel-theme'),
                'new_item'           => __('Nueva Tarjeta', 'ugel-theme'),
                'view_item'          => __('Ver Tarjeta', 'ugel-theme'),
                'search_items'       => __('Buscar Tarjetas', 'ugel-theme'),
                'not_found'          => __('No se encontraron tarjetas', 'ugel-theme'),
                'not_found_in_trash' => __('No hay tarjetas en la papelera', 'ugel-theme'),
            ),
            'public'       => true,
            'has_archive'  => false,
            'supports'     => array('title','thumbnail','excerpt','page-attributes'),
            'menu_icon'    => 'dashicons-screenoptions',
            'show_in_rest' => true
        ));

        register_post_type('anuncios_portada', array(
            'labels' => array(
                'name'               => __('Anuncios de Inicio', 'ugel-theme'),
                'singular_name'      => __('Anuncio', 'ugel-theme'),
                'add_new'            => __('Agregar Nuevo', 'ugel-theme'),
                'add_new_item'       => __('Agregar Nuevo Anuncio', 'ugel-theme'),
                'edit_item'          => __('Editar Anuncio', 'ugel-theme'),
                'new_item'           => __('Nuevo Anuncio', 'ugel-theme'),
                'view_item'          => __('Ver Anuncio', 'ugel-theme'),
                'search_items'       => __('Buscar Anuncios', 'ugel-theme'),
                'not_found'          => __('No se encontraron anuncios', 'ugel-theme'),
                'not_found_in_trash' => __('No hay anuncios en la papelera', 'ugel-theme'),
            ),
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => true,
            'has_archive'  => false,
            'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'),
            'menu_icon'    => 'dashicons-megaphone',
            'show_in_rest' => true,
        ));
        /* ===========================================================
 * Metabox: URL de redirección para 'anuncios_portada'
 * =========================================================== */
add_action('add_meta_boxes', function () {
  add_meta_box(
    'anuncio_meta',
    __('Propiedades del Anuncio', 'ugel-theme'),
    'ugel_anuncio_meta_callback',
    'anuncios_portada',
    'normal',
    'high'
  );
});

function ugel_anuncio_meta_callback($post) {
  wp_nonce_field('ugel_anuncio_save_meta', 'ugel_anuncio_nonce');

  $url    = get_post_meta($post->ID, '_anuncio_url', true);
  $target = get_post_meta($post->ID, '_anuncio_target', true) ?: '_self';
  ?>
  <style>
    .anuncio-grid{display:grid;grid-template-columns:1fr 220px;gap:14px}
    .anuncio-field{margin:0 0 10px}
    .anuncio-field label{display:block;font-weight:700;margin:0 0 6px}
    .anuncio-field input[type="text"], .anuncio-field select{width:100%}
    .anuncio-hint{font-size:12px;color:#65737e;margin-top:4px}
  </style>
  <div class="anuncio-grid">
    <div class="anuncio-field">
      <label for="anuncio_url"><?php echo esc_html__('URL de destino', 'ugel-theme'); ?></label>
      <input type="text" id="anuncio_url" name="anuncio_url" value="<?php echo esc_attr($url); ?>" placeholder="https://...">
      <div class="anuncio-hint"><?php echo esc_html__('A dónde debe ir el clic del anuncio.', 'ugel-theme'); ?></div>
    </div>
    <div class="anuncio-field">
      <label for="anuncio_target"><?php echo esc_html__('Abrir enlace', 'ugel-theme'); ?></label>
      <select id="anuncio_target" name="anuncio_target">
        <option value="_self"  <?php selected($target, '_self');  ?>><?php echo esc_html__('Misma pestaña','ugel-theme'); ?></option>
        <option value="_blank" <?php selected($target, '_blank'); ?>><?php echo esc_html__('Nueva pestaña','ugel-theme'); ?></option>
      </select>
    </div>
  </div>
  <?php
}

/* Guardado seguro del metabox */
add_action('save_post_anuncios_portada', function ($post_id) {
  if (!isset($_POST['ugel_anuncio_nonce']) ||
      !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ugel_anuncio_nonce'])), 'ugel_anuncio_save_meta')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_post', $post_id)) return;

  $url    = isset($_POST['anuncio_url'])    ? esc_url_raw(trim(wp_unslash($_POST['anuncio_url']))) : '';
  $target = isset($_POST['anuncio_target']) ? (function_exists('ugel_sanitize_target') ? ugel_sanitize_target(wp_unslash($_POST['anuncio_target'])) : (in_array($_POST['anuncio_target'], array('_self','_blank'), true) ? $_POST['anuncio_target'] : '_self')) : '_self';

  update_post_meta($post_id, '_anuncio_url', $url);
  update_post_meta($post_id, '_anuncio_target', $target);
});

    }

    // SIN taxonomías extras para convocatorias (se deja el método vacío por compatibilidad)
    public function register_taxonomies() {}

    public function register_sidebars() {
        register_sidebar(array(
            'name'          => __('Sidebar Principal', 'ugel-theme'),
            'id'            => 'main-sidebar',
            'description'   => __('Área de widgets para la barra lateral principal', 'ugel-theme'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));

        foreach (array(1,2,3,4) as $n) {
            register_sidebar(array(
                'name'          => sprintf(__('Footer %d', 'ugel-theme'), $n),
                'id'            => 'footer-' . $n,
                'description'   => sprintf(__('Columna %d del footer', 'ugel-theme'), $n),
                'before_widget' => '<div class="foot">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4>',
                'after_title'   => '</h4>',
            ));
        }
    }

    public function customize_register($wp_customize) {
        /*** SE MANTIENEN ajustes útiles (sin colores globales) ***/

        // Información de Contacto
        $wp_customize->add_section('ugel_contact', array(
            'title'    => __('Información de Contacto', 'ugel-theme'),
            'priority' => 40,
        ));
        $wp_customize->add_setting('ugel_phone', array(
            'default'           => '994 687 446',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('ugel_phone', array(
            'label'   => __('Teléfono', 'ugel-theme'),
            'section' => 'ugel_contact',
            'type'    => 'text',
        ));
        $wp_customize->add_setting('ugel_address', array(
            'default'           => 'Calle Honduras Nro 100 – 2do Piso – Oficina 5, Arequipa',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control('ugel_address', array(
            'label'   => __('Dirección', 'ugel-theme'),
            'section' => 'ugel_contact',
            'type'    => 'textarea',
        ));
        $wp_customize->add_setting('ugel_email', array(
            'default'           => 'info@ugelelcollao.edu.pe',
            'sanitize_callback' => 'sanitize_email',
        ));
        $wp_customize->add_control('ugel_email', array(
            'label'   => __('Email', 'ugel-theme'),
            'section' => 'ugel_contact',
            'type'    => 'email',
        ));
        $wp_customize->add_setting('ugel_whatsapp', array(
            'default'           => '51994687446',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('ugel_whatsapp', array(
            'label'   => __('WhatsApp (con código de país)', 'ugel-theme'),
            'section' => 'ugel_contact',
            'type'    => 'text',
        ));

        // Redes
        $wp_customize->add_section('ugel_social', array(
            'title'    => __('Redes Sociales', 'ugel-theme'),
            'priority' => 50,
        ));
        foreach (array(
            'ugel_facebook'  => __('Facebook URL', 'ugel-theme'),
            'ugel_twitter'   => __('Twitter/X URL', 'ugel-theme'),
            'ugel_instagram' => __('Instagram URL', 'ugel-theme'),
        ) as $setting => $label) {
            $wp_customize->add_setting($setting, array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            ));
            $wp_customize->add_control($setting, array(
                'label'   => $label,
                'section' => 'ugel_social',
                'type'    => 'url',
            ));
        }

        // Encabezado y Portal
        $wp_customize->add_section('ugel_header', array(
          'title'    => __('Encabezado y Portal', 'ugel-theme'),
          'priority' => 25,
        ));
        $wp_customize->add_setting('custom_logo_minedu', array(
          'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'custom_logo_minedu', array(
          'label'     => __('Logo Ministerio (imagen)', 'ugel-theme'),
          'section'   => 'ugel_header',
          'mime_type' => 'image',
        )));
        $wp_customize->add_setting('portal_label', array(
          'default'           => 'Portal de Transparencia',
          'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('portal_label', array(
          'label'   => __('Texto del botón Portal', 'ugel-theme'),
          'section' => 'ugel_header',
          'type'    => 'text',
        ));
        $wp_customize->add_setting('portal_url', array(
          'default'           => home_url('/transparencia'),
          'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('portal_url', array(
          'label'   => __('URL del Portal', 'ugel-theme'),
          'section' => 'ugel_header',
          'type'    => 'url',
        ));
        $wp_customize->add_setting('portal_target', array(
          'default'           => '_self',
          'sanitize_callback' => 'ugel_sanitize_target',
        ));
        $wp_customize->add_control('portal_target', array(
          'label'   => __('Abrir Portal en', 'ugel-theme'),
          'section' => 'ugel_header',
          'type'    => 'select',
          'choices' => array(
            '_self'  => __('Misma ventana', 'ugel-theme'),
            '_blank' => __('Nueva pestaña', 'ugel-theme'),
          ),
        ));
        $wp_customize->add_setting('portal_icon_svg', array(
          'default'           => '',
          'sanitize_callback' => 'ugel_sanitize_svg',
        ));
        $wp_customize->add_control('portal_icon_svg', array(
          'label'       => __('Icono SVG del Portal (opcional)', 'ugel-theme'),
          'description' => __('Pega el código SVG inline. Si lo dejas vacío, se usará el ícono por defecto.', 'ugel-theme'),
          'section'     => 'ugel_header',
          'type'        => 'textarea',
        ));

        // Bloque Contenido Visual (sin control de color de fondo)
        $wp_customize->add_section('cv_block', array(
          'title'       => __('Contenido Visual (Bloque grande)', 'ugel-theme'),
          'priority'    => 28,
          'description' => __('Imagen a la izquierda y contenido a la derecha con animación al hacer scroll.', 'ugel-theme'),
        ));
        $wp_customize->add_setting('cv_enable', array(
          'default'           => 1,
          'sanitize_callback' => 'ugel_sanitize_bool',
        ));
        $wp_customize->add_control('cv_enable', array(
          'label'   => __('Mostrar bloque', 'ugel-theme'),
          'section' => 'cv_block',
          'type'    => 'checkbox',
        ));
        $wp_customize->add_setting('cv_title', array(
          'default'           => 'Potencia tu gestión educativa con soluciones simples',
          'sanitize_callback' => 'wp_kses_post',
        ));
        $wp_customize->add_control('cv_title', array(
          'label'   => __('Título', 'ugel-theme'),
          'section' => 'cv_block',
          'type'    => 'textarea',
        ));
        $wp_customize->add_setting('cv_subtitle', array(
          'default'           => 'Herramientas y recursos que te ayudan a comunicar, organizar y ejecutar más rápido.',
          'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control('cv_subtitle', array(
          'label'   => __('Subtítulo', 'ugel-theme'),
          'section' => 'cv_block',
          'type'    => 'textarea',
        ));
        $wp_customize->add_setting('cv_btn_text', array(
          'default'           => 'Conoce más',
          'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('cv_btn_text', array(
          'label'   => __('Texto del botón', 'ugel-theme'),
          'section' => 'cv_block',
          'type'    => 'text',
        ));
        $wp_customize->add_setting('cv_btn_url', array(
          'default'           => home_url('/'),
          'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('cv_btn_url', array(
          'label'   => __('URL del botón', 'ugel-theme'),
          'section' => 'cv_block',
          'type'    => 'url',
        ));
        $wp_customize->add_setting('cv_btn_target', array(
          'default'           => '_self',
          'sanitize_callback' => 'ugel_sanitize_target',
        ));
        $wp_customize->add_control('cv_btn_target', array(
          'label'   => __('Abrir botón en', 'ugel-theme'),
          'section' => 'cv_block',
          'type'    => 'select',
          'choices' => array(
            '_self'  => __('Misma pestaña', 'ugel-theme'),
            '_blank' => __('Nueva pestaña', 'ugel-theme'),
          ),
        ));
        $wp_customize->add_setting('cv_image_id', array(
          'default'           => 0,
          'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'cv_image_id', array(
          'label'     => __('Imagen (izquierda)', 'ugel-theme'),
          'section'   => 'cv_block',
          'mime_type' => 'image',
        )));
        // (Eliminado: cv_bg color)
    }

    public function custom_body_classes($classes) {
        if (is_front_page()) {
            $classes[] = 'home-page';
        }
        if (is_singular('convocatorias')) {
            $classes[] = 'single-convocatoria';
        }
        if (is_singular('comunicados')) {
            $classes[] = 'single-comunicado';
        }
        return $classes;
    }
}

new UGELTheme();

/* =========================
 * Contador de visitas del sitio (global)
 * - Cuenta visitas para todo el sitio, no por página individual
 * - Usa cookie de enfriamiento para evitar múltiples incrementos en minutos seguidos
 * ========================= */
function ugel_count_site_view() {
    if (is_user_logged_in() && current_user_can('manage_options')) {
        // Evitar contaminar el contador con navegaciones de administradores
        return;
    }

    $cookie_name = 'ugel_sv';
    $cooldown    = 30 * MINUTE_IN_SECONDS; // 30 minutos
    $now         = time();

    $last = isset($_COOKIE[$cookie_name]) ? intval($_COOKIE[$cookie_name]) : 0;
    if (($now - $last) < $cooldown) {
        return; // ya contado recientemente
    }

    // Incrementar contador atómico en options
    $total = get_option('ugel_site_views_total', 0);
    $total = is_numeric($total) ? intval($total) : 0;
    $total++;
    update_option('ugel_site_views_total', $total, false);

    // Refrescar cookie de enfriamiento
    setcookie($cookie_name, (string)$now, $now + $cooldown, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), true);
}
add_action('template_redirect', 'ugel_count_site_view');

function ugel_get_site_views() {
    $total = get_option('ugel_site_views_total', 0);
    return max(0, intval($total));
}

function ugel_the_site_views($label = true) {
    $n = number_format_i18n(ugel_get_site_views());
    if ($label) {
        echo '<span class="site-views" aria-label="Visitas al sitio">Visitas: ' . esc_html($n) . '</span>';
    } else {
        echo esc_html($n);
    }
}

/* =========================
 * Habilitar categoría en CPT de forma explícita
 * ========================= */
add_action('init', function () {
    foreach (array('convocatorias','comunicados') as $pt) {
        register_taxonomy_for_object_type('category', $pt);
    }
});

/* =========================
 * Helpers de consultas
 * ========================= */
function get_hero_slides() {
    return get_posts(array(
        'post_type'      => 'slider',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish'
    ));
}
function get_convocatorias($limit = 3, $estado = null) {
    return get_posts(array(
        'post_type'      => 'convocatorias',
        'posts_per_page' => intval($limit),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish'
    ));
}

function ugel_get_convocatoria_meta($post_id) {
    $meta = array(
        'indice'                    => get_post_meta($post_id, '_conv_indice', true),
        'tipo'                      => get_post_meta($post_id, '_conv_tipo', true),
        'descripcion'               => get_post_meta($post_id, '_conv_descripcion', true),
        'fecha_inicio'              => get_post_meta($post_id, '_conv_fecha_inicio', true),
        'fecha_fin'                 => get_post_meta($post_id, '_conv_fecha_fin', true),
        'bases_pdf'                 => get_post_meta($post_id, '_conv_bases_pdf', true),
        'resultado_preliminar_pdf'  => get_post_meta($post_id, '_conv_resultado_preliminar_pdf', true),
        'resultado_final_curr_pdf'  => get_post_meta($post_id, '_conv_resultado_final_curricular_pdf', true),
        'resultados_finales_pdf'    => get_post_meta($post_id, '_conv_resultados_finales_pdf', true),
    );

    foreach ($meta as $key => $value) {
        $meta[$key] = is_string($value) ? trim($value) : $value;
    }

    return $meta;
}

function ugel_get_convocatoria_status_details($fecha_inicio, $fecha_fin) {
    $today       = current_time('Y-m-d');
    $inicio_raw  = $fecha_inicio ? substr($fecha_inicio, 0, 10) : '';
    $fin_raw     = $fecha_fin ? substr($fecha_fin, 0, 10) : '';
    $status      = 'en_proceso';
    $label       = __('En proceso', 'ugel-theme');

    if ($inicio_raw && $today < $inicio_raw) {
        $status = 'programado';
        $label  = __('Por iniciar', 'ugel-theme');
    }

    if ($fin_raw && $today > $fin_raw) {
        $status = 'culminado';
        $label  = __('Culminado', 'ugel-theme');
    } elseif ($inicio_raw && (!$fin_raw || $today >= $inicio_raw)) {
        $status = 'en_proceso';
        $label  = __('En proceso', 'ugel-theme');
        if ($fin_raw && $today > $fin_raw) {
            $status = 'culminado';
            $label  = __('Culminado', 'ugel-theme');
        }
    }

    if (!$inicio_raw && $fin_raw && $today > $fin_raw) {
        $status = 'culminado';
        $label  = __('Culminado', 'ugel-theme');
    }

    return array(
        'slug'  => $status,
        'label' => $label,
    );
}

function ugel_format_convocatoria_date($date_string) {
    if (empty($date_string)) {
        return '';
    }

    $timestamp = strtotime($date_string);
    if (!$timestamp) {
        return sanitize_text_field($date_string);
    }

    return esc_html( date_i18n('d/m/Y', $timestamp) );
}
function get_comunicados($limit = 3) {
    return get_posts(array(
        'post_type'      => 'comunicados',
        'posts_per_page' => intval($limit),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish'
    ));
}
function get_destacados($limit = 4) {
    return get_posts(array(
        'post_type'      => array('post', 'convocatorias', 'comunicados'),
        'posts_per_page' => intval($limit),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
        'category_name'  => 'destacados'
    ));
}
function get_enlaces_interes() {
    return get_posts(array(
        'post_type'      => 'enlaces',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish'
    ));
}
function get_feature_cards($limit = 5) {
  return get_posts(array(
    'post_type'      => 'tarjetas',
    'posts_per_page' => intval($limit),
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'post_status'    => 'publish'
  ));
}

/* =========================
 * (ELIMINADO) Colores dinámicos globales
 *  - ugel_custom_css()
 *  - adjustBrightness()
 *  - inyección <style> en wp_head
 * ========================= */

/* ===========================================================
 * Meta boxes (slider, enlaces, tarjetas)
 * =========================================================== */
add_action('add_meta_boxes', function() {
    add_meta_box(
        'convocatoria_detalles',
        __('Detalles de la convocatoria', 'ugel-theme'),
        'ugel_convocatoria_meta_box',
        'convocatorias',
        'normal',
        'high'
    );

    // Slider
    add_meta_box(
        'slide_meta',
        __('Configuración del Slide', 'ugel-theme'),
        'slide_meta_callback',
        'slider',
        'normal',
        'high'
    );

    // Enlaces
    add_meta_box(
        'enlace_meta',
        __('Configuración del Enlace', 'ugel-theme'),
        'enlace_meta_callback',
        'enlaces',
        'normal',
        'high'
    );

    // Tarjetas
    add_meta_box(
        'tarjeta_meta',
        __('Datos de la Tarjeta de Portada', 'ugel-theme'),
        'tarjeta_meta_callback',
        'tarjetas',
        'normal',
        'high'
    );
});

function ugel_convocatoria_meta_box($post) {
    wp_nonce_field('convocatoria_meta_nonce', 'convocatoria_meta_nonce');
    wp_enqueue_media();

    $meta = ugel_get_convocatoria_meta($post->ID);

    $fields = array(
        'bases_pdf'                => __('PDF de Bases', 'ugel-theme'),
        'resultado_preliminar_pdf' => __('Resultado Preliminar Curricular (PDF)', 'ugel-theme'),
        'resultado_final_curr_pdf' => __('Resultado Final Curricular (PDF)', 'ugel-theme'),
        'resultados_finales_pdf'   => __('Resultados Finales (PDF)', 'ugel-theme'),
    );
    ?>
    <style>
      .conv-meta-grid {display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-bottom:20px}
      .conv-meta-field label {font-weight:600;color:#021F59;display:block;margin-bottom:6px}
      .conv-meta-field input[type="text"],
      .conv-meta-field input[type="number"],
      .conv-meta-field input[type="date"],
      .conv-meta-field textarea {width:100%;border:1px solid #d5dbe7;border-radius:8px;padding:10px;font-size:14px;box-sizing:border-box;background:#fff}
      .conv-meta-field textarea{min-height:120px;resize:vertical}
      .conv-meta-pdf {display:flex;gap:10px;align-items:center;margin-bottom:12px}
      .conv-meta-pdf input[type="text"]{flex:1;border:1px solid #d5dbe7;border-radius:8px;padding:8px;font-size:14px;background:#fff}
      .conv-meta-actions{margin-top:24px;border-top:1px solid #e2e8f0;padding-top:16px}
      .conv-meta-grid .conv-meta-field small{display:block;margin-top:4px;color:#64748b;font-size:12px}
    </style>
    <div class="conv-meta-grid">
      <div class="conv-meta-field">
        <label for="conv_indice"><?php esc_html_e('Índice', 'ugel-theme'); ?></label>
        <input type="text" id="conv_indice" name="conv_indice" value="<?php echo esc_attr($meta['indice']); ?>" placeholder="001-2024">
        <small><?php esc_html_e('Identificador corto para la convocatoria.', 'ugel-theme'); ?></small>
      </div>
      <div class="conv-meta-field">
        <label for="conv_tipo"><?php esc_html_e('Tipo de convocatoria', 'ugel-theme'); ?></label>
        <input type="text" id="conv_tipo" name="conv_tipo" value="<?php echo esc_attr($meta['tipo']); ?>" placeholder="CAS, Nombramiento, Interna...">
        <small><?php esc_html_e('Ejemplo: Concurso CAS, Capacitaciones, etc.', 'ugel-theme'); ?></small>
      </div>
      <div class="conv-meta-field">
        <label for="conv_fecha_inicio"><?php esc_html_e('Fecha de inicio', 'ugel-theme'); ?></label>
        <input type="date" id="conv_fecha_inicio" name="conv_fecha_inicio" value="<?php echo esc_attr($meta['fecha_inicio']); ?>">
      </div>
      <div class="conv-meta-field">
        <label for="conv_fecha_fin"><?php esc_html_e('Fecha de fin', 'ugel-theme'); ?></label>
        <input type="date" id="conv_fecha_fin" name="conv_fecha_fin" value="<?php echo esc_attr($meta['fecha_fin']); ?>">
      </div>
    </div>

    <div class="conv-meta-field">
      <label for="conv_descripcion"><?php esc_html_e('Descripción breve', 'ugel-theme'); ?></label>
      <textarea id="conv_descripcion" name="conv_descripcion" placeholder="<?php esc_attr_e('Describe brevemente el proceso.', 'ugel-theme'); ?>"><?php echo esc_textarea($meta['descripcion']); ?></textarea>
    </div>

    <div class="conv-meta-actions">
      <?php foreach ($fields as $key => $label):
        $field_id = 'conv_' . $key;
        $meta_key = $key;
        $value    = isset($meta[$meta_key]) ? $meta[$meta_key] : '';
      ?>
        <div class="conv-meta-pdf">
          <label for="<?php echo esc_attr($field_id); ?>" style="min-width:260px;font-weight:600;color:#021F59;">
            <?php echo esc_html($label); ?>
          </label>
          <input type="text" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_id); ?>" value="<?php echo esc_url($value); ?>" placeholder="https://... .pdf">
          <button type="button" class="button button-secondary" data-target="<?php echo esc_attr($field_id); ?>"><?php esc_html_e('Elegir', 'ugel-theme'); ?></button>
        </div>
      <?php endforeach; ?>
    </div>

    <script>
    (function(){
      const buttons = document.querySelectorAll('#convocatoria_detalles [data-target]');
      buttons.forEach(btn => {
        btn.addEventListener('click', function(){
          const targetId = this.getAttribute('data-target');
          const input    = document.getElementById(targetId);
          if (!input) return;

          const frame = wp.media({
            title: '<?php echo esc_js(__('Selecciona un PDF', 'ugel-theme')); ?>',
            library: { type: 'application/pdf' },
            button: { text: '<?php echo esc_js(__('Usar este PDF', 'ugel-theme')); ?>' },
            multiple: false
          });

          frame.on('select', function(){
            const file = frame.state().get('selection').first().toJSON();
            input.value = file.url || '';
          });

          frame.open();
        });
      });
    })();
    </script>
    <?php
}

function slide_meta_callback($post) {
    wp_nonce_field('slide_meta_nonce', 'slide_meta_nonce');
    $alineacion              = get_post_meta($post->ID, '_alineacion', true);
    $boton_texto             = get_post_meta($post->ID, '_boton_texto', true);
    $boton_url               = get_post_meta($post->ID, '_boton_url', true);
    $boton_secundario_texto  = get_post_meta($post->ID, '_boton_secundario_texto', true);
    $boton_secundario_url    = get_post_meta($post->ID, '_boton_secundario_url', true);
    $ribbon_texto            = get_post_meta($post->ID, '_ribbon_texto', true);

    echo '<table class="form-table">';
    echo '<tr><th><label for="alineacion">'.esc_html__('Alineación del texto:', 'ugel-theme').'</label></th>';
    echo '<td><select id="alineacion" name="alineacion">';
    echo '<option value="left"' . selected($alineacion, 'left', false) . '>'.esc_html__('Izquierda','ugel-theme').'</option>';
    echo '<option value="center"' . selected($alineacion, 'center', false) . '>'.esc_html__('Centro','ugel-theme').'</option>';
    echo '<option value="right"' . selected($alineacion, 'right', false) . '>'.esc_html__('Derecha','ugel-theme').'</option>';
    echo '</select></td></tr>';

    echo '<tr><th><label for="ribbon_texto">'.esc_html__('Texto del Ribbon:', 'ugel-theme').'</label></th>';
    echo '<td><input type="text" id="ribbon_texto" name="ribbon_texto" value="' . esc_attr($ribbon_texto) . '" size="50" /></td></tr>';

    echo '<tr><th><label for="boton_texto">'.esc_html__('Texto Botón Principal:', 'ugel-theme').'</label></th>';
    echo '<td><input type="text" id="boton_texto" name="boton_texto" value="' . esc_attr($boton_texto) . '" size="30" /></td></tr>';

    echo '<tr><th><label for="boton_url">'.esc_html__('URL Botón Principal:', 'ugel-theme').'</label></th>';
    echo '<td><input type="url" id="boton_url" name="boton_url" value="' . esc_attr($boton_url) . '" size="50" /></td></tr>';

    echo '<tr><th><label for="boton_secundario_texto">'.esc_html__('Texto Botón Secundario:', 'ugel-theme').'</label></th>';
    echo '<td><input type="text" id="boton_secundario_texto" name="boton_secundario_texto" value="' . esc_attr($boton_secundario_texto) . '" size="30" /></td></tr>';

    echo '<tr><th><label for="boton_secundario_url">'.esc_html__('URL Botón Secundario:', 'ugel-theme').'</label></th>';
    echo '<td><input type="url" id="boton_secundario_url" name="boton_secundario_url" value="' . esc_attr($boton_secundario_url) . '" size="50" /></td></tr>';
    echo '</table>';
}

function enlace_meta_callback($post) {
    wp_nonce_field('enlace_meta_nonce', 'enlace_meta_nonce');
    $url    = get_post_meta($post->ID, '_enlace_url', true);
    $target = get_post_meta($post->ID, '_enlace_target', true);
    echo '<table class="form-table">';
    echo '<tr><th><label for="enlace_url">'.esc_html__('URL del Enlace:', 'ugel-theme').'</label></th>';
    echo '<td><input type="url" id="enlace_url" name="enlace_url" value="' . esc_attr($url) . '" size="50" /></td></tr>';
    echo '<tr><th><label for="enlace_target">'.esc_html__('Abrir en:', 'ugel-theme').'</label></th>';
    echo '<td><select id="enlace_target" name="enlace_target">';
    echo '<option value="_self"' . selected($target, '_self', false) . '>'.esc_html__('Misma ventana','ugel-theme').'</option>';
    echo '<option value="_blank"' . selected($target, '_blank', false) . '>'.esc_html__('Nueva ventana','ugel-theme').'</option>';
    echo '</select></td></tr>';
    echo '</table>';
}

function tarjeta_meta_callback($post) {
  wp_nonce_field('tarjeta_meta_nonce', 'tarjeta_meta_nonce');
  $enlace_url    = get_post_meta($post->ID, '_enlace_url', true);
  $enlace_target = get_post_meta($post->ID, '_enlace_target', true) ?: '_self';
  $icono_svg     = get_post_meta($post->ID, '_icono_svg', true);

  echo '<table class="form-table">';
  echo '<tr><th><label for="enlace_url">'.esc_html__('URL de destino:', 'ugel-theme').'</label></th>';
  echo '<td><input type="url" id="enlace_url" name="enlace_url" value="'.esc_attr($enlace_url).'" size="60" placeholder="https://..."/></td></tr>';
  echo '<tr><th><label for="enlace_target">'.esc_html__('Abrir en:', 'ugel-theme').'</label></th>';
  echo '<td><select id="enlace_target" name="enlace_target">';
  echo '<option value="_self" '.selected($enlace_target,'_self',false).'>'.esc_html__('Misma ventana','ugel-theme').'</option>';
  echo '<option value="_blank" '.selected($enlace_target,'_blank',false).'>'.esc_html__('Nueva pestaña','ugel-theme').'</option>';
  echo '</select></td></tr>';
  echo '<tr><th><label for="icono_svg">'.esc_html__('Icono SVG (opcional):', 'ugel-theme').'</label></th>';
  echo '<td><textarea id="icono_svg" name="icono_svg" rows="6" cols="60" placeholder="<svg ...>...">'.esc_textarea($icono_svg ?: '').'</textarea><p class="description">'.esc_html__('Pega aquí un SVG (inline). Si lo dejas vacío, se usará un icono por defecto según la posición.', 'ugel-theme').'</p></td></tr>';
  echo '</table>';
}

/* ===========================================================
 * Sanitizadores SVG/target/bool
 * =========================================================== */
function ugel_svg_allowed_tags() {
    return array(
        'svg'  => array(
            'xmlns' => true, 'viewBox' => true, 'fill' => true, 'width' => true, 'height' => true,
            'aria-hidden' => true, 'role' => true, 'focusable' => true
        ),
        'g'    => array('fill'=>true,'stroke'=>true,'clip-path'=>true,'transform'=>true),
        'path' => array('d'=>true,'fill'=>true,'stroke'=>true,'stroke-width'=>true,'fill-rule'=>true,'clip-rule'=>true,'transform'=>true),
        'circle'=>array('cx'=>true,'cy'=>true,'r'=>true,'fill'=>true,'stroke'=>true,'stroke-width'=>true),
        'rect' => array('x'=>true,'y'=>true,'width'=>true,'height'=>true,'rx'=>true,'ry'=>true,'fill'=>true,'stroke'=>true,'stroke-width'=>true,'transform'=>true),
        'polygon'=>array('points'=>true,'fill'=>true,'stroke'=>true,'stroke-width'=>true),
        'polyline'=>array('points'=>true,'fill'=>true,'stroke'=>true,'stroke-width'=>true),
        'line' => array('x1'=>true,'y1'=>true,'x2'=>true,'y2'=>true,'stroke'=>true,'stroke-width'=>true),
        'title'=> array(), 'desc'=>array()
    );
}
function ugel_sanitize_svg($svg_raw) { return wp_kses($svg_raw, ugel_svg_allowed_tags()); }
function ugel_sanitize_target($val){ return in_array($val, array('_self','_blank'), true) ? $val : '_self'; }
function ugel_sanitize_bool($v){ return $v ? 1 : 0; }

/* ===========================================================
 * Guardado de metaboxes
 * =========================================================== */
add_action('save_post', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Slide
    if (isset($_POST['slide_meta_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['slide_meta_nonce'])), 'slide_meta_nonce')) {
        $fields = ['alineacion', 'boton_texto', 'boton_url', 'boton_secundario_texto', 'boton_secundario_url', 'ribbon_texto'];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $raw = wp_unslash($_POST[$field]);
                $value = ($field === 'boton_url' || $field === 'boton_secundario_url')
                    ? esc_url_raw($raw)
                    : sanitize_text_field($raw);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }

    // Enlaces
    if (isset($_POST['enlace_meta_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['enlace_meta_nonce'])), 'enlace_meta_nonce')) {
        if (isset($_POST['enlace_url']))    update_post_meta($post_id, '_enlace_url', esc_url_raw(wp_unslash($_POST['enlace_url'])));
        if (isset($_POST['enlace_target'])) update_post_meta($post_id, '_enlace_target', sanitize_text_field(wp_unslash($_POST['enlace_target'])));
    }

    // Tarjetas
    if (isset($_POST['tarjeta_meta_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tarjeta_meta_nonce'])), 'tarjeta_meta_nonce')) {
        if (isset($_POST['enlace_url']))    update_post_meta($post_id, '_enlace_url', esc_url_raw(wp_unslash($_POST['enlace_url'])));
        if (isset($_POST['enlace_target'])) update_post_meta($post_id, '_enlace_target', sanitize_text_field(wp_unslash($_POST['enlace_target'])));
        if (isset($_POST['icono_svg']))     update_post_meta($post_id, '_icono_svg', ugel_sanitize_svg(wp_unslash($_POST['icono_svg'])));
    }

    if (isset($_POST['convocatoria_meta_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['convocatoria_meta_nonce'])), 'convocatoria_meta_nonce')) {
        $map = array(
            'conv_indice'                     => array('_conv_indice', 'sanitize_text_field'),
            'conv_tipo'                       => array('_conv_tipo', 'sanitize_text_field'),
            'conv_descripcion'                => array('_conv_descripcion', 'wp_kses_post'),
            'conv_fecha_inicio'               => array('_conv_fecha_inicio', 'sanitize_text_field'),
            'conv_fecha_fin'                  => array('_conv_fecha_fin', 'sanitize_text_field'),
            'conv_bases_pdf'                  => array('_conv_bases_pdf', 'esc_url_raw'),
            'conv_resultado_preliminar_pdf'   => array('_conv_resultado_preliminar_pdf', 'esc_url_raw'),
            'conv_resultado_final_curr_pdf'   => array('_conv_resultado_final_curricular_pdf', 'esc_url_raw'),
            'conv_resultados_finales_pdf'     => array('_conv_resultados_finales_pdf', 'esc_url_raw'),
        );

        foreach ($map as $field => $settings) {
            list($meta_key, $sanitize_cb) = $settings;
            $value = isset($_POST[$field]) ? wp_unslash($_POST[$field]) : '';

            if ($sanitize_cb === 'esc_url_raw') {
                $clean = $value ? esc_url_raw($value) : '';
            } elseif ($sanitize_cb === 'wp_kses_post') {
                $clean = $value ? wp_kses_post($value) : '';
            } else {
                $clean = $value ? call_user_func($sanitize_cb, $value) : '';
            }

            if (!empty($clean)) {
                update_post_meta($post_id, $meta_key, $clean);
            } else {
                delete_post_meta($post_id, $meta_key);
            }
        }
    }
});

/* ===========================================================
 * BUSCADOR AJAX
 * =========================================================== */
add_action('wp_ajax_ugel_live_search', 'ugel_live_search');
add_action('wp_ajax_nopriv_ugel_live_search', 'ugel_live_search');

function ugel_live_search() {
    check_ajax_referer('ugel_nonce', 'nonce');

    $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
    if (strlen($search) < 3) {
        wp_die( esc_html__('Mínimo 3 caracteres', 'ugel-theme') );
    }

    $results = new WP_Query(array(
        's'              => $search,
        'post_type'      => array('post', 'convocatorias', 'comunicados'),
        'posts_per_page' => 10,
        'post_status'    => 'publish'
    ));

    if ($results->have_posts()) {
        $output = '<ul class="search-results">';
        while ($results->have_posts()) {
            $results->the_post();
            $output .= '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
        }
        $output .= '</ul>';
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    } else {
        echo '<p>'.esc_html__('No se encontraron resultados', 'ugel-theme').'</p>';
    }
    wp_reset_postdata();
    wp_die();
}

add_filter('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', array('post', 'convocatorias', 'comunicados'));
    }
});

/* ===========================================================
 * REST API FIELDS
 * =========================================================== */
add_action('rest_api_init', function() {
    register_rest_field('slider', 'meta_fields', array(
        'get_callback' => function($post) {
            return array(
                'alineacion'             => get_post_meta($post['id'], '_alineacion', true),
                'boton_texto'            => get_post_meta($post['id'], '_boton_texto', true),
                'boton_url'              => get_post_meta($post['id'], '_boton_url', true),
                'boton_secundario_texto' => get_post_meta($post['id'], '_boton_secundario_texto', true),
                'boton_secundario_url'   => get_post_meta($post['id'], '_boton_secundario_url', true),
                'ribbon_texto'           => get_post_meta($post['id'], '_ribbon_texto', true),
            );
        }
    ));
    register_rest_field('enlaces', 'meta_fields', array(
        'get_callback' => function($post) {
            return array(
                'enlace_url'   => get_post_meta($post['id'], '_enlace_url', true),
                'enlace_target'=> get_post_meta($post['id'], '_enlace_target', true),
            );
        }
    ));
    register_rest_field('tarjetas', 'meta_fields', array(
        'get_callback' => function($post) {
            return array(
                'enlace_url'    => get_post_meta($post['id'], '_enlace_url', true),
                'enlace_target' => get_post_meta($post['id'], '_enlace_target', true),
                'icono_svg'     => get_post_meta($post['id'], '_icono_svg', true),
            );
        }
    ));
});

/* ===========================================================
 * PDFs ADJUNTOS (global para convocatorias y comunicados y post)
 * =========================================================== */
function ugel_get_pdf_attachments($post_id) {
  $items = get_post_meta($post_id, '_adjuntos_pdf', true);
  if (!is_array($items)) return array();
  $clean = array();
  foreach ($items as $it) {
    $titulo = isset($it['titulo']) ? sanitize_text_field($it['titulo']) : '';
    $url    = isset($it['url']) ? esc_url_raw($it['url']) : '';
    if (!empty($url)) {
      $clean[] = array(
        'titulo' => $titulo ?: 'Ver PDF',
        'url'    => $url,
      );
    }
  }
  return $clean;
}

add_action('add_meta_boxes', function() {
  foreach (array('post','comunicados') as $ptype) {
    add_meta_box(
      'ugel_adjuntos_pdf',
      __('PDFs adjuntos', 'ugel-theme'),
      'ugel_pdf_render_metabox',
      $ptype,
      'normal',
      'high'
    );
  }
});

function ugel_pdf_render_metabox($post) {
  wp_nonce_field('ugel_guardar_adjuntos_pdf', 'ugel_adjuntos_pdf_nonce');
  $items = get_post_meta($post->ID, '_adjuntos_pdf', true);
  $items = is_array($items) ? $items : array();
  wp_enqueue_media();
  ?>
  <style>
    .ugel-pdf-table{width:100%;border-collapse:separate;border-spacing:0 8px}
    .ugel-pdf-row{background:#fff;border:1px solid #e3e6ea;border-radius:8px;padding:12px;margin-bottom:8px}
    .ugel-pdf-row .col{display:flex;gap:8px;align-items:center;margin:6px 0}
    .ugel-pdf-row input[type="text"]{width:100%}
    .ugel-pdf-actions{display:flex;gap:10px;margin-top:10px}
    .button-secondary.small{padding:2px 8px;line-height:24px;height:28px}
    .ugel-code-hint{font-size:12px;color:#65737e;margin-top:6px}
  </style>
  <div id="ugel-pdf-repeater">
    <?php if(empty($items)): ?>
      <div class="ugel-pdf-row" data-row>
        <div class="col">
          <label style="min-width:110px;"><?php echo esc_html__('Título del PDF', 'ugel-theme'); ?></label>
          <input type="text" name="adjuntos_pdf[0][titulo]" placeholder="<?php echo esc_attr__('Ej. Bases del proceso','ugel-theme'); ?>">
        </div>
        <div class="col">
          <label style="min-width:110px;"><?php echo esc_html__('Archivo (URL)', 'ugel-theme'); ?></label>
          <input type="text" name="adjuntos_pdf[0][url]" placeholder="https://... .pdf">
          <button class="button button-secondary small" data-choose><?php echo esc_html__('Elegir','ugel-theme'); ?></button>
        </div>
        <div class="ugel-code-hint">
          <?php echo wp_kses_post(__('Código para insertar: <code>[ugel_pdf n="1"]</code> o <code>{{PDF1}}</code>', 'ugel-theme')); ?>
        </div>
        <div class="ugel-pdf-actions">
          <button class="button button-link-delete" data-remove><?php echo esc_html__('Eliminar','ugel-theme'); ?></button>
        </div>
      </div>
    <?php else: ?>
      <?php foreach($items as $i => $it): ?>
        <div class="ugel-pdf-row" data-row>
          <div class="col">
            <label style="min-width:110px;"><?php echo esc_html__('Título del PDF', 'ugel-theme'); ?></label>
            <input type="text" name="adjuntos_pdf[<?php echo esc_attr($i); ?>][titulo]" value="<?php echo esc_attr($it['titulo'] ?? ''); ?>">
          </div>
          <div class="col">
            <label style="min-width:110px;"><?php echo esc_html__('Archivo (URL)', 'ugel-theme'); ?></label>
            <input type="text" name="adjuntos_pdf[<?php echo esc_attr($i); ?>][url]" value="<?php echo esc_url($it['url'] ?? ''); ?>">
            <button class="button button-secondary small" data-choose><?php echo esc_html__('Elegir','ugel-theme'); ?></button>
          </div>
          <div class="ugel-code-hint">
            <?php
              printf(
                wp_kses_post(__('Código para insertar: <code>[ugel_pdf n="%1$d"]</code> o <code>{{PDF%1$d}}</code>', 'ugel-theme')),
                $i+1
              );
            ?>
          </div>
          <div class="ugel-pdf-actions">
            <button class="button button-link-delete" data-remove><?php echo esc_html__('Eliminar','ugel-theme'); ?></button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <p><button class="button button-primary" id="ugel-add-pdf">+ <?php echo esc_html__('Agregar PDF','ugel-theme'); ?></button></p>
  <script>
  (function(){
    const container = document.getElementById('ugel-pdf-repeater');
    const addBtn = document.getElementById('ugel-add-pdf');

    function openMediaLibrary(onSelect){
      const frame = wp.media({
        title: '<?php echo esc_js(__('Selecciona un PDF', 'ugel-theme')); ?>',
        library: { type: 'application/pdf' },
        button: { text: '<?php echo esc_js(__('Usar este PDF', 'ugel-theme')); ?>' },
        multiple: false
      });
      frame.on('select', function(){
        const file = frame.state().get('selection').first().toJSON();
        if(onSelect) onSelect(file.url || '');
      });
      frame.open();
    }

    container.addEventListener('click', function(e){
      if(e.target && e.target.matches('[data-remove]')){
        e.preventDefault();
        const row = e.target.closest('[data-row]');
        if(row && container.querySelectorAll('[data-row]').length > 1){
          row.remove();
        }else if(row){
          row.querySelectorAll('input').forEach(i => i.value = '');
        }
      }
      if(e.target && e.target.matches('[data-choose]')){
        e.preventDefault();
        const input = e.target.parentElement.querySelector('input[type="text"]');
        openMediaLibrary(function(url){
          if(url) input.value = url;
        });
      }
    });

    addBtn.addEventListener('click', function(e){
      e.preventDefault();
      const idx = container.querySelectorAll('[data-row]').length;
      const n   = idx + 1;
      const html = `
      <div class="ugel-pdf-row" data-row>
        <div class="col">
          <label style="min-width:110px;"><?php echo esc_js(__('Título del PDF', 'ugel-theme')); ?></label>
          <input type="text" name="adjuntos_pdf[${idx}][titulo]" placeholder="<?php echo esc_js(__('Ej. Bases del proceso','ugel-theme')); ?>">
        </div>
        <div class="col">
          <label style="min-width:110px;"><?php echo esc_js(__('Archivo (URL)', 'ugel-theme')); ?></label>
          <input type="text" name="adjuntos_pdf[${idx}][url]" placeholder="https://... .pdf">
          <button class="button button-secondary small" data-choose><?php echo esc_js(__('Elegir','ugel-theme')); ?></button>
        </div>
        <div class="ugel-code-hint">
          <?php echo esc_js(__('Código para insertar:', 'ugel-theme')); ?> <code>[ugel_pdf n="${n}"]</code> <?php echo esc_js(__('o', 'ugel-theme')); ?> <code>{{PDF${n}}}</code>
        </div>
        <div class="ugel-pdf-actions">
          <button class="button button-link-delete" data-remove><?php echo esc_js(__('Eliminar','ugel-theme')); ?></button>
        </div>
      </div>`;
      container.insertAdjacentHTML('beforeend', html);
    });
  })();
  </script>
  <?php
}

function ugel_pdf_save_post($post_id){
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (wp_is_post_revision($post_id)) return;
  if (!current_user_can('edit_post', $post_id)) return;
  if (!isset($_POST['ugel_adjuntos_pdf_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ugel_adjuntos_pdf_nonce'])), 'ugel_guardar_adjuntos_pdf')) return;

  $raw = isset($_POST['adjuntos_pdf']) && is_array($_POST['adjuntos_pdf']) ? wp_unslash($_POST['adjuntos_pdf']) : array();
  $clean = array();
  foreach ($raw as $row){
    $titulo = isset($row['titulo']) ? sanitize_text_field($row['titulo']) : '';
    $url    = isset($row['url'])    ? esc_url_raw(trim($row['url']))     : '';
    if ($titulo || $url){
      $clean[] = array(
        'titulo' => $titulo ?: 'Ver PDF',
        'url'    => $url,
      );
    }
  }
  update_post_meta($post_id, '_adjuntos_pdf', $clean);
}
add_action('save_post', 'ugel_pdf_save_post');

// Shortcode por PDF
add_shortcode('ugel_pdf', function($atts){
  $atts = shortcode_atts(array(
    'n'      => 1,
    'titulo' => '',
    'estilo' => 'boton',   // 'boton' o 'link'
    'class'  => '',
  ), $atts);

  $post_id = get_the_ID();
  if (!$post_id) return '';
  $items = ugel_get_pdf_attachments($post_id);
  $idx = max(1, intval($atts['n'])) - 1;
  if (empty($items) || !isset($items[$idx])) return '';

  $it = $items[$idx];
  $title = $atts['titulo'] !== '' ? $atts['titulo'] : $it['titulo'];
  $class = $atts['class'] ? ' '.esc_attr($atts['class']) : '';

  if ($atts['estilo'] === 'link') {
    return '<a class="hub-link'.$class.'" href="'.esc_url($it['url']).'" target="_blank" rel="noopener">'.esc_html($title).'</a>';
  }
  return '<a class="hub-btn'.$class.'" href="'.esc_url($it['url']).'" target="_blank" rel="noopener">
    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" style="vertical-align:-3px;margin-right:6px;">
      <path fill="currentColor" d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm8 6h4l-4-4v4Z"/>
    </svg>'.esc_html($title).'</a>';
});

// Shortcode para todos los PDFs
add_shortcode('ugel_pdfs', function($atts){
  $atts = shortcode_atts(array(
    'titulo' => '',
    'estilo' => 'botones', // botones | lista
    'class'  => '',
  ), $atts);

  $post_id = get_the_ID();
  if (!$post_id) return '';
  $items = ugel_get_pdf_attachments($post_id);
  if (empty($items)) return '';

  ob_start();
  $class = $atts['class'] ? ' '.esc_attr($atts['class']) : '';
  echo '<div class="ugel-pdfs'.$class.'">';
  if ($atts['titulo'] !== '') {
    echo '<h4 class="ugel-pdfs-title">'.esc_html($atts['titulo']).'</h4>';
  }

  if ($atts['estilo'] === 'lista') {
    echo '<ul class="hub-pdf-list">';
    foreach($items as $it){
      echo '<li><a class="hub-link" href="'.esc_url($it['url']).'" target="_blank" rel="noopener">'.esc_html($it['titulo']).'</a></li>';
    }
    echo '</ul>';
  } else {
    echo '<div class="hub-pdf-buttons" style="display:flex;flex-wrap:wrap;gap:10px">';
    foreach($items as $it){
      echo '<a class="hub-btn" href="'.esc_url($it['url']).'" target="_blank" rel="noopener">'.esc_html($it['titulo']).'</a>';
    }
    echo '</div>';
  }
  echo '</div>';
  return ob_get_clean();
});

// Auto-inyección / Tokens
add_filter('the_content', function($content){
  if (is_singular(array('post','comunicados')) && in_the_loop() && is_main_query()){
    $items = ugel_get_pdf_attachments(get_the_ID());

    if (!empty($items)) {
      $content = preg_replace_callback('/\{\{PDF(\d+)\}\}/i', function($m) use ($items){
        $idx = max(1, (int)$m[1]) - 1;
        if (!isset($items[$idx])) return '';
        $it = $items[$idx];
        return '<a class="hub-btn" href="'.esc_url($it['url']).'" target="_blank" rel="noopener">'.esc_html($it['titulo']).'</a>';
      }, $content);
    }

    if (!empty($items)
        && strpos($content, '[ugel_pdfs') === false
        && strpos($content, '[ugel_pdf') === false
        && !preg_match('/\{\{PDF\d+\}\}/i', $content)) {
      $content .= "\n\n" . do_shortcode('[ugel_pdfs]');
    }
  }
  return $content;
}, 20);

// REST API: exponer adjuntos_pdf
add_action('rest_api_init', function(){
  foreach (array('post','comunicados') as $ptype) {
    register_rest_field($ptype, 'adjuntos_pdf', array(
      'get_callback' => function($post){
        return ugel_get_pdf_attachments($post['id']);
      },
      'schema' => array('description'=>__('Lista de PDFs adjuntos (titulo, url)','ugel-theme'),'type'=>'array')
    ));
  }
});


/* ===========================================================
 * Migas de pan
 * =========================================================== */
function ugel_breadcrumbs() {
    if (is_front_page()) return;
    echo '<nav class="breadcrumbs" aria-label="'.esc_attr__('Navegación de migas de pan','ugel-theme').'">';
    echo '<a href="' . esc_url(home_url()) . '">'.esc_html__('Inicio','ugel-theme').'</a>';
    if (is_category() || is_single()) {
        if (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                echo ' <span class="separator">&gt;</span> <a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            }
            echo ' <span class="separator">&gt;</span> <span class="current">' . esc_html(get_the_title()) . '</span>';
        } else {
            echo ' <span class="separator">&gt;</span> <span class="current">' . esc_html(single_cat_title('', false)) . '</span>';
        }
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        $ancestors = array_reverse($ancestors);
        foreach ($ancestors as $ancestor) {
            echo ' <span class="separator">&gt;</span> <a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a>';
        }
        echo ' <span class="separator">&gt;</span> <span class="current">' . esc_html(get_the_title()) . '</span>';
    } elseif (is_search()) {
        echo ' <span class="separator">&gt;</span> <span class="current">' . sprintf(esc_html__('Resultados de búsqueda para "%s"','ugel-theme'), esc_html(get_search_query())) . '</span>';
    } elseif (is_404()) {
        echo ' <span class="separator">&gt;</span> <span class="current">' . esc_html__('Página no encontrada', 'ugel-theme') . '</span>';
    }
    echo '</nav>';
}

add_action('switch_theme', function() {
    wp_clear_scheduled_hook('ugel_update_convocatorias_status');
});

/* ===========================================================
 * Validación UI categorías (post: máx 3; todos: solo subcategorías)
 * =========================================================== */
add_action('admin_enqueue_scripts', function($hook){
  if (!in_array($hook, array('post-new.php','post.php'), true)) return;
  $screen = get_current_screen();
  if (!$screen || $screen->base !== 'post' || !in_array($screen->post_type, array('post','convocatorias','comunicados'), true)) return;

  wp_enqueue_script('jquery');

  $pt = esc_js($screen->post_type);
  $js = <<<JS
  (function(){
    const wrap = document.getElementById('categorychecklist') || document.querySelector('.categorydiv #categorychecklist');
    if(!wrap) return;
    var POST_TYPE = '{$pt}';
    var MAX_POST_CATS = 3;

    function isLeaf(li){
      return !li.querySelector(':scope > ul.children input[type="checkbox"]');
    }
    function countCheckedLeaves(){
      let n = 0;
      wrap.querySelectorAll('input[type="checkbox"]:checked').forEach(cb=>{
        const li = cb.closest('li');
        if(li && isLeaf(li)) n++;
      });
      return n;
    }

    wrap.addEventListener('change', function(e){
      const cb = e.target;
      if(!(cb && cb.matches('input[type="checkbox"]'))) return;
      const li = cb.closest('li');
      if(!li) return;

      const childUl = li.querySelector(':scope > ul.children');
      if(childUl){
        const hasChildren = !!childUl.querySelector('input[type="checkbox"]');
        if(hasChildren && cb.checked){
          cb.checked = false;
          alert('Esta categoría tiene subcategorías. Selecciona una subcategoría (componente → subcomponente).');
          return;
        }
      }

      const parentLi = li.closest('ul.children') ? li.parentElement.closest('li') : null;
      if(parentLi){
        const pCb = parentLi.querySelector(':scope > label > input[type="checkbox"]');
        if(pCb && cb.checked) pCb.checked = false;
      }

      if(POST_TYPE === 'post' && cb.checked){
        const leaves = countCheckedLeaves();
        if(leaves > MAX_POST_CATS){
          cb.checked = false;
          alert('Solo puedes seleccionar hasta '+MAX_POST_CATS+' subcategorías para la entrada.');
        }
      }
    });

    const form = document.getElementById('post');
    if(form){
      form.addEventListener('submit', function(ev){
        const leaves = countCheckedLeaves();
        if(leaves === 0){
          ev.preventDefault();
          alert('Elige al menos una subcategoría (no el padre).');
        }
      });
    }
  })();
  JS;
  wp_add_inline_script('jquery', $js, 'after');
});

/* ===========================================================
 * Normalización en guardado de categorías
 * =========================================================== */
add_action('save_post', function($post_id, $post){
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (wp_is_post_revision($post_id)) return;
  if (!in_array($post->post_type, array('post','convocatorias','comunicados'), true)) return;
  if (!current_user_can('edit_post', $post_id)) return;

  $terms = wp_get_post_terms($post_id, 'category', array('fields'=>'ids'));
  if (is_wp_error($terms)) return;

  $clean = array();
  foreach ($terms as $tid){
    $children = get_term_children($tid, 'category');
    if (empty($children)) $clean[] = (int)$tid;
  }

  if ($post->post_type === 'post' && count($clean) > 3){
    $clean = array_slice($clean, 0, 3);
  }

  if (empty($clean) && $post->post_type === 'post'){
    $clean = array( (int) get_option('default_category') );
  }

  wp_set_post_terms($post_id, $clean, 'category', false);
}, 30, 2);

/* ===========================================================
 * Shortcodes de listado
 * =========================================================== */
add_shortcode('ugel_convocatorias', function($atts) {
    $atts = shortcode_atts(array(
        'limite' => 3,
        'estado' => ''
    ), $atts);
    $convocatorias = get_convocatorias($atts['limite']);
    if (!$convocatorias) return '';
    ob_start(); ?>
    <div class="hub-grid-cards">
        <?php foreach ($convocatorias as $conv): ?>
            <article class="hub-card job">
                <div class="meta">
                    <time datetime="<?php echo esc_attr(get_the_date('Y-m-d', $conv)); ?>">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2Zm13 8H4v10h16V10Z"/></svg>
                        <?php echo esc_html(get_the_date('j M Y', $conv)); ?>
                    </time>
                </div>
                <h3 class="ttl"><?php echo esc_html(get_the_title($conv)); ?></h3>
                <p class="sum"><?php echo esc_html(get_the_excerpt($conv)); ?></p>
                <div class="act">
                    <a class="hub-btn" href="<?php echo esc_url(get_permalink($conv)); ?>"><?php echo esc_html__('Ver detalle','ugel-theme'); ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('ugel_comunicados', function($atts) {
    $atts = shortcode_atts(array(
        'limite' => 3
    ), $atts);
    $comunicados = get_comunicados($atts['limite']);
    if (!$comunicados) return '';
    ob_start(); ?>
    <ol class="hub-feed">
        <?php foreach ($comunicados as $com): ?>
            <li>
                <time datetime="<?php echo esc_attr(get_the_date('Y-m-d', $com)); ?>">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2Zm13 8H4v10h16V10Z"/></svg>
                    <?php echo esc_html(get_the_date('j M Y', $com)); ?>
                </time>
                <a href="<?php echo esc_url(get_permalink($com)); ?>"><?php echo esc_html(get_the_title($com)); ?></a>
                <a class="go" href="<?php echo esc_url(get_permalink($com)); ?>"><?php echo esc_html__('Leer →','ugel-theme'); ?></a>
            </li>
        <?php endforeach; ?>
    </ol>
    <?php
    return ob_get_clean();
});

/* ===========================================================
 * Utilidades de páginas/menús iniciales
 * =========================================================== */
if (!function_exists('ugel_ensure_page')) {
  function ugel_ensure_page($title, $slug, $parent_id = 0) {
    $path = $parent_id ? get_page_uri($parent_id) . '/' . $slug : $slug;
    $page = get_page_by_path($path, OBJECT, 'page');
    if ($page) {
      if ($parent_id && (int)$page->post_parent !== (int)$parent_id) {
        wp_update_post(array('ID' => $page->ID, 'post_parent' => (int)$parent_id));
      }
      return (int)$page->ID;
    }
    $args = array(
      'post_title'   => $title,
      'post_name'    => $slug,
      'post_status'  => 'publish',
      'post_type'    => 'page',
      'post_parent'  => (int)$parent_id,
      'post_content' => '<!-- Contenido inicial. Edítalo en Páginas. -->'
    );
    return (int) wp_insert_post($args);
  }
}

if (!function_exists('ugel_add_menu_item')) {
  function ugel_add_menu_item($menu_id, $page_id, $parent_item_id = 0) {
    return (int) wp_update_nav_menu_item($menu_id, 0, array(
      'menu-item-object-id'   => $page_id,
      'menu-item-object'      => 'page',
      'menu-item-type'        => 'post_type',
      'menu-item-status'      => 'publish',
      'menu-item-parent-id'   => (int)$parent_item_id,
    ));
  }
}

add_action('after_switch_theme', function() {
  $id_direccion          = ugel_ensure_page('Dirección', 'direccion');
  $id_gestion_pedagogica = ugel_ensure_page('Gestión Pedagógica', 'gestion-pedagogica');
  $id_gestion            = ugel_ensure_page('Gestión', 'gestion');
  $id_organo_control     = ugel_ensure_page('Órgano de Control', 'organo-de-control');

  $id_mision_vision   = ugel_ensure_page('Misión y Visión', 'mision-y-vision', $id_direccion);
  $id_imagen_inst     = ugel_ensure_page('Imagen Institucional', 'imagen-institucional', $id_direccion);
  $id_asesoria_legal  = ugel_ensure_page('Asesoría Legal', 'asesoria-legal', $id_direccion);

  $id_inicial         = ugel_ensure_page('Educación Inicial', 'educacion-inicial', $id_gestion_pedagogica);
  $id_primaria        = ugel_ensure_page('Educación Primaria', 'educacion-primaria', $id_gestion_pedagogica);
  $id_secundaria      = ugel_ensure_page('Educación Secundaria', 'educacion-secundaria', $id_gestion_pedagogica);
  $id_tutoria         = ugel_ensure_page('Tutoría y Convivencia Escolar', 'tutoria-y-convivencia-escolar', $id_gestion_pedagogica);
  $id_etp             = ugel_ensure_page('Educación Técnico Productiva', 'educacion-tecnico-productiva', $id_gestion_pedagogica);
  $id_taller_docentes = ugel_ensure_page('Taller Docentes', 'taller-docentes', $id_gestion_pedagogica);

  $id_gestion_inst = ugel_ensure_page('Gestión Institucional', 'gestion-institucional', $id_gestion);
  $id_gestion_adm  = ugel_ensure_page('Gestión Administrativa', 'gestion-administrativa', $id_gestion);

  $locations = get_theme_mod('nav_menu_locations');
  $primary_menu_id = isset($locations['primary']) ? (int)$locations['primary'] : 0;

  if (!$primary_menu_id) {
    $menu_name = 'Menú Principal UGEL';
    $menu_obj  = wp_get_nav_menu_object($menu_name);
    if (!$menu_obj) {
      $primary_menu_id = (int) wp_create_nav_menu($menu_name);
    } else {
      $primary_menu_id = (int) $menu_obj->term_id;
    }

    wp_update_nav_menu_item($primary_menu_id, 0, array(
      'menu-item-title'  => 'INICIO',
      'menu-item-url'    => home_url('/'),
      'menu-item-status' => 'publish',
      'menu-item-type'   => 'custom',
    ));

    $item_direccion = ugel_add_menu_item($primary_menu_id, $id_direccion);
    ugel_add_menu_item($primary_menu_id, $id_mision_vision,   $item_direccion);
    ugel_add_menu_item($primary_menu_id, $id_imagen_inst,     $item_direccion);
    ugel_add_menu_item($primary_menu_id, $id_asesoria_legal,  $item_direccion);

    $item_gp = ugel_add_menu_item($primary_menu_id, $id_gestion_pedagogica);
    ugel_add_menu_item($primary_menu_id, $id_inicial,         $item_gp);
    ugel_add_menu_item($primary_menu_id, $id_primaria,        $item_gp);
    ugel_add_menu_item($primary_menu_id, $id_secundaria,      $item_gp);
    ugel_add_menu_item($primary_menu_id, $id_tutoria,         $item_gp);
    ugel_add_menu_item($primary_menu_id, $id_etp,             $item_gp);
    ugel_add_menu_item($primary_menu_id, $id_taller_docentes, $item_gp);

    $item_gestion = ugel_add_menu_item($primary_menu_id, $id_gestion);
    ugel_add_menu_item($primary_menu_id, $id_gestion_inst, $item_gestion);
    ugel_add_menu_item($primary_menu_id, $id_gestion_adm,  $item_gestion);

    ugel_add_menu_item($primary_menu_id, $id_organo_control);

    $locations = (array) $locations;
    $locations['primary'] = $primary_menu_id;
    set_theme_mod('nav_menu_locations', $locations);
  }
});

if (!function_exists('ugel_ensure_category')) {
  function ugel_ensure_category($name, $slug, $parent = 0){
    $term = get_term_by('slug', $slug, 'category');
    if ($term) {
      if ($parent && (int)$term->parent !== (int)$parent) {
        wp_update_term($term->term_id, 'category', array('parent' => (int)$parent));
      }
      return (int)$term->term_id;
    }
    $r = wp_insert_term($name, 'category', array('slug'=>$slug, 'parent'=>(int)$parent));
    return is_wp_error($r) ? 0 : (int)$r['term_id'];
  }
}

add_action('after_switch_theme', function(){
  // Padres
  $cat_direccion   = ugel_ensure_category('Dirección','direccion');
  $cat_gp          = ugel_ensure_category('Gestión Pedagógica','gestion-pedagogica');
  $cat_gestion     = ugel_ensure_category('Gestión','gestion');
  $cat_oc          = ugel_ensure_category('Órgano de Control','organo-de-control');

  // Hijas de Dirección
  ugel_ensure_category('Misión y Visión', 'mision-y-vision', $cat_direccion);
  ugel_ensure_category('Imagen Institucional','imagen-institucional', $cat_direccion);
  ugel_ensure_category('Asesoría Legal','asesoria-legal', $cat_direccion);

  // Hijas de GP
  ugel_ensure_category('Educación Inicial','educacion-inicial', $cat_gp);
  ugel_ensure_category('Educación Primaria','educacion-primaria', $cat_gp);
  ugel_ensure_category('Educación Secundaria','educacion-secundaria', $cat_gp);
  ugel_ensure_category('Tutoría y Convivencia Escolar','tutoria-y-convivencia-escolar', $cat_gp);
  ugel_ensure_category('Educación Técnico Productiva','educacion-tecnico-productiva', $cat_gp);
  ugel_ensure_category('Taller Docentes','taller-docentes', $cat_gp);

  // Hijas de Gestión
  ugel_ensure_category('Gestión Institucional','gestion-institucional', $cat_gestion);
  ugel_ensure_category('Gestión Administrativa','gestion-administrativa', $cat_gestion);

  // Generales
  ugel_ensure_category('Convocatorias','convocatorias', 0);
  ugel_ensure_category('Comunicados','comunicados', 0);
  ugel_ensure_category('Destacados','destacados', 0);
});

/* ===========================================================
 * CPT Acceso directo
 * =========================================================== */
add_action('init', function () {
  register_post_type('acceso', array(
    'labels' => array(
      'name'               => __('Accesos directos', 'ugel-theme'),
      'singular_name'      => __('Acceso directo', 'ugel-theme'),
      'add_new'            => __('Añadir acceso', 'ugel-theme'),
      'add_new_item'       => __('Añadir acceso directo', 'ugel-theme'),
      'edit_item'          => __('Editar acceso directo', 'ugel-theme'),
      'new_item'           => __('Nuevo acceso', 'ugel-theme'),
      'view_item'          => __('Ver acceso', 'ugel-theme'),
      'search_items'       => __('Buscar accesos', 'ugel-theme'),
      'not_found'          => __('No hay accesos', 'ugel-theme'),
      'not_found_in_trash' => __('No hay accesos en la papelera', 'ugel-theme'),
      'menu_name'          => __('Accesos directos', 'ugel-theme')
    ),
    'public'       => true,
    'show_in_rest' => true,
    'menu_icon'    => 'dashicons-admin-links',
    'supports'     => array('title', 'thumbnail', 'excerpt', 'page-attributes'),
    'has_archive'  => false,
    'rewrite'      => false,
  ));
  add_image_size('acceso_logo', 800, 400, false);
});

add_action('add_meta_boxes', function () {
  add_meta_box('ax_meta', __('Propiedades del acceso', 'ugel-theme'), 'ugel_ax_render_meta', 'acceso', 'normal', 'high');
});

function ugel_ax_render_meta($post) {
  wp_nonce_field('ugel_ax_save_meta', 'ugel_ax_nonce');
  $url    = get_post_meta($post->ID, '_ax_url', true);
  $target = get_post_meta($post->ID, '_ax_target', true) ?: '_self';
  $badge  = get_post_meta($post->ID, '_ax_badge', true);
  $color  = get_post_meta($post->ID, '_ax_color', true) ?: '#09a19e';
  ?>
  <style>
    .ax-grid{display:grid;grid-template-columns:1fr 160px;gap:14px}
    .ax-field{margin:0 0 10px}
    .ax-field label{display:block;font-weight:700;margin:0 0 6px}
    .ax-field input[type="text"], .ax-field select{width:100%}
    .ax-hint{font-size:12px;color:#65737e;margin-top:4px}
  </style>
  <div class="ax-grid">
    <div>
      <div class="ax-field">
        <label for="ax_url"><?php echo esc_html__('Enlace (URL)', 'ugel-theme'); ?></label>
        <input type="text" id="ax_url" name="ax_url" value="<?php echo esc_attr($url); ?>" placeholder="https://...">
        <div class="ax-hint"><?php echo esc_html__('Destino al hacer clic en la card.', 'ugel-theme'); ?></div>
      </div>
      <div class="ax-field">
        <label for="ax_badge"><?php echo esc_html__('Etiqueta superior (opcional)', 'ugel-theme'); ?></label>
        <input type="text" id="ax_badge" name="ax_badge" value="<?php echo esc_attr($badge); ?>" placeholder="<?php echo esc_attr__('Oficial • Nuevo • Importante','ugel-theme'); ?>">
        <div class="ax-hint"><?php echo esc_html__('Pequeña cinta arriba (ej: “Oficial”).', 'ugel-theme'); ?></div>
      </div>
      <div class="ax-field">
        <label for="ax_target"><?php echo esc_html__('Abrir enlace', 'ugel-theme'); ?></label>
        <select id="ax_target" name="ax_target">
          <option value="_self" <?php selected($target, '_self'); ?>><?php echo esc_html__('En la misma pestaña','ugel-theme'); ?></option>
          <option value="_blank" <?php selected($target, '_blank'); ?>><?php echo esc_html__('En nueva pestaña','ugel-theme'); ?></option>
        </select>
      </div>
      <div class="ax-field">
        <label><?php echo esc_html__('Subtítulo (usa el Extracto)', 'ugel-theme'); ?></label>
        <div class="ax-hint"><?php echo wp_kses_post(__('En el editor, usa el campo <b>Extracto</b> como subtítulo.', 'ugel-theme')); ?></div>
      </div>
    </div>
    <div>
      <div class="ax-field">
        <label for="ax_color"><?php echo esc_html__('Color de acento', 'ugel-theme'); ?></label>
        <input type="color" id="ax_color" name="ax_color" value="<?php echo esc_attr($color); ?>" style="width:100%;height:42px;padding:0;border:1px solid #ccd0d4;border-radius:6px;">
        <div class="ax-hint"><?php echo esc_html__('Se usa para bordes, gradientes y foco (solo en este tipo de contenido).', 'ugel-theme'); ?></div>
      </div>
      <div class="ax-field">
        <label><?php echo esc_html__('Logo/Isotipo', 'ugel-theme'); ?></label>
        <div class="ax-hint"><?php echo wp_kses_post(__('Asigna la <b>Imagen destacada</b> (preferible PNG/SVG con fondo transparente).', 'ugel-theme')); ?></div>
      </div>
    </div>
  </div>
  <?php
}

add_action('save_post_acceso', function ($post_id) {
  if (!isset($_POST['ugel_ax_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ugel_ax_nonce'])), 'ugel_ax_save_meta')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_post', $post_id)) return;

  $url    = isset($_POST['ax_url'])    ? esc_url_raw(trim(wp_unslash($_POST['ax_url']))) : '';
  $target = isset($_POST['ax_target']) ? (wp_unslash($_POST['ax_target']) === '_blank' ? '_blank' : '_self') : '_self';
  $badge  = isset($_POST['ax_badge'])  ? sanitize_text_field(wp_unslash($_POST['ax_badge'])) : '';
  $color  = isset($_POST['ax_color'])  ? sanitize_hex_color(wp_unslash($_POST['ax_color'])) : '#09a19e';

  update_post_meta($post_id, '_ax_url', $url);
  update_post_meta($post_id, '_ax_target', $target);
  update_post_meta($post_id, '_ax_badge', $badge);
  update_post_meta($post_id, '_ax_color', $color);
});

function ugel_get_accesos($limite = 6){
  return get_posts(array(
    'post_type'      => 'acceso',
    'posts_per_page' => intval($limite),
    'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'),
    'post_status'    => 'publish',
  ));
}

/* ===========================================================
 * Conversor Inteligente de Menú
 * =========================================================== */
if (!function_exists('ugel_count_posts_by_slug')) {
  function ugel_count_posts_by_slug($slug) {
      $query = new WP_Query(array(
          'post_type'      => array('post', 'convocatorias', 'comunicados'),
          'category_name'  => $slug,
          'posts_per_page' => 1,
          'post_status'    => 'publish',
          'fields'         => 'ids',
      ));
      return (int) $query->found_posts;
  }
}

if (!function_exists('ugel_find_category_by_page')) {
  function ugel_find_category_by_page($page_id) {
      $page = get_post($page_id);
      if (!$page) return null;

      $page_slug = $page->post_name;

      // Intentar categoría con mismo slug
      $category = get_term_by('slug', $page_slug, 'category');
      if ($category) return $category;

      // Si es hija, probar combinaciones
      if ($page->post_parent) {
          $parent = get_post($page->post_parent);
          if ($parent) {
              $combined_slug = $parent->post_name . '-' . $page_slug;
              $category = get_term_by('slug', $combined_slug, 'category');
              if ($category) return $category;

              $category = get_term_by('slug', $page_slug, 'category');
              if ($category) return $category;
          }
      }
      return null;
  }
}

function ugel_convert_existing_menu() {
    $locations = get_theme_mod('nav_menu_locations');
    $primary_menu_id = isset($locations['primary']) ? (int)$locations['primary'] : 0;
    if (!$primary_menu_id) return "No hay menú principal configurado.";

    $menu_items = wp_get_nav_menu_items($primary_menu_id);
    if (!$menu_items) return "El menú está vacío.";

    $conversions = array();
    foreach ($menu_items as $item) {
        if ($item->title === 'INICIO' || $item->title === 'Inicio') continue;

        $should_convert  = false;
        $target_category = null;
        $post_count      = 0;

        if ($item->object === 'page') {
            $page_id = (int) $item->object_id;
            $target_category = ugel_find_category_by_page($page_id);
            if ($target_category) {
                $post_count = ugel_count_posts_by_slug($target_category->slug);
                if ($post_count > 0) $should_convert = true;
            }
        }

        if ($should_convert && $target_category) {
            wp_update_nav_menu_item($primary_menu_id, $item->ID, array(
                'menu-item-object-id'   => $target_category->term_id,
                'menu-item-object'      => 'category',
                'menu-item-type'        => 'taxonomy',
                'menu-item-status'      => 'publish',
                'menu-item-parent-id'   => (int)$item->menu_item_parent,
                'menu-item-title'       => $item->title,
                'menu-item-position'    => $item->menu_order,
            ));

            $conversions[] = array(
                'title'    => $item->title,
                'from'     => 'Página',
                'to'       => 'Categoría',
                'posts'    => $post_count,
                'category' => $target_category->name
            );
        } else {
            $conversions[] = array(
                'title'  => $item->title,
                'from'   => ucfirst($item->object),
                'to'     => 'Sin cambios',
                'posts'  => $post_count,
                'reason' => $target_category ? 'Sin posts' : 'Sin categoría equivalente'
            );
        }
    }
    return $conversions;
}

function ugel_analyze_current_menu() {
    $locations = get_theme_mod('nav_menu_locations');
    $primary_menu_id = isset($locations['primary']) ? (int)$locations['primary'] : 0;
    if (!$primary_menu_id) {
        echo "<p><strong>".esc_html__('No hay menú principal configurado.', 'ugel-theme')."</strong></p>";
        return;
    }

    $menu_items = wp_get_nav_menu_items($primary_menu_id);
    if (!$menu_items) {
        echo "<p><strong>".esc_html__('El menú está vacío.', 'ugel-theme')."</strong></p>";
        return;
    }

    echo "<h3>".esc_html__('Análisis del menú actual:', 'ugel-theme')."</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f1f1f1;'>";
    echo "<th>".esc_html__('Elemento del menú', 'ugel-theme')."</th>";
    echo "<th>".esc_html__('Tipo actual', 'ugel-theme')."</th>";
    echo "<th>".esc_html__('Posts encontrados', 'ugel-theme')."</th>";
    echo "<th>".esc_html__('Acción recomendada', 'ugel-theme')."</th>";
    echo "</tr>";

    foreach ($menu_items as $item) {
        if ($item->title === 'INICIO' || $item->title === 'Inicio') continue;

        $post_count = 0;
        $action = __('Mantener como página', 'ugel-theme');
        $color = '#f8d7da';

        if ($item->object === 'page') {
            $page_id = (int) $item->object_id;
            $page = get_post($page_id);
            if ($page) {
                $page_slug = $page->post_name;
                $category = get_term_by('slug', $page_slug, 'category');
                if ($category) {
                    $query = new WP_Query(array(
                        'post_type'      => array('post', 'convocatorias', 'comunicados'),
                        'category_name'  => $page_slug,
                        'posts_per_page' => 1,
                        'post_status'    => 'publish'
                    ));
                    $post_count = (int) $query->found_posts;
                    if ($post_count > 0) { $action = __('Convertir a categoría','ugel-theme'); $color = '#d4edda'; }
                }
            }
        } elseif ($item->object === 'category') {
            $action = __('Ya es categoría','ugel-theme');
            $color = '#d1ecf1';
            $category = get_term((int)$item->object_id, 'category');
            if ($category) {
                $query = new WP_Query(array(
                    'post_type'      => array('post', 'convocatorias', 'comunicados'),
                    'category_name'  => $category->slug,
                    'posts_per_page' => 1,
                    'post_status'    => 'publish'
                ));
                $post_count = (int) $query->found_posts;
            }
        }

        echo "<tr style='background: $color;'>";
        echo "<td>" . esc_html($item->title) . "</td>";
        echo "<td>" . esc_html(ucfirst($item->object)) . "</td>";
        echo "<td>" . esc_html($post_count) . "</td>";
        echo "<td>" . esc_html($action) . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<div style='margin-top: 15px;'>";
    echo "<p><strong>".esc_html__('Leyenda:', 'ugel-theme')."</strong></p>";
    echo "<ul>";
    echo "<li><span style='background: #d4edda; padding: 2px 8px;'>".esc_html__('Verde:', 'ugel-theme')."</span> ".esc_html__('Se convertirá de página a categoría (tiene posts)', 'ugel-theme')."</li>";
    echo "<li><span style='background: #d1ecf1; padding: 2px 8px;'>".esc_html__('Azul:', 'ugel-theme')."</span> ".esc_html__('Ya es categoría', 'ugel-theme')."</li>";
    echo "<li><span style='background: #f8d7da; padding: 2px 8px;'>".esc_html__('Rojo:', 'ugel-theme')."</span> ".esc_html__('Se mantendrá como página (sin posts o sin categoría equivalente)', 'ugel-theme')."</li>";
    echo "</ul>";
    echo "</div>";
}

add_action('admin_menu', function() {
    add_submenu_page(
        'themes.php',
        __('Actualizar Menú con Categorías', 'ugel-theme'),
        __('Actualizar Menú', 'ugel-theme'),
        'manage_options',
        'ugel-update-menu',
        'ugel_admin_update_menu_page'
    );
});

function ugel_admin_update_menu_page() {
    if (isset($_POST['update_menu']) && isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'ugel_update_menu')) {
        $results = ugel_convert_existing_menu();

        if (is_string($results)) {
            echo '<div class="notice notice-error"><p>' . esc_html($results) . '</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>' . esc_html__('Menú procesado correctamente. Revisa los cambios abajo.', 'ugel-theme') . '</p></div>';

            echo '<div style="background: #fff; padding: 15px; border: 1px solid #ddd; margin: 15px 0;">';
            echo '<h3>'.esc_html__('Resultados de la conversión:', 'ugel-theme').'</h3>';
            echo '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 100%;">';
            echo '<tr style="background: #f1f1f1;"><th>'.esc_html__('Elemento','ugel-theme').'</th><th>'.esc_html__('Cambio','ugel-theme').'</th><th>'.esc_html__('Posts','ugel-theme').'</th><th>'.esc_html__('Estado','ugel-theme').'</th></tr>';

            foreach ($results as $result) {
                $color = '#f8d7da';
                if ($result['to'] === 'Categoría') {
                    $color = '#d4edda';
                } elseif ($result['to'] === 'Sin cambios' && (int)$result['posts'] === 0) {
                    $color = '#fff3cd';
                }

                echo "<tr style='background: $color;'>";
                echo '<td>' . esc_html($result['title']) . '</td>';
                echo '<td>' . esc_html($result['from'] . ' → ' . $result['to']) . '</td>';
                echo '<td>' . esc_html($result['posts']) . '</td>';
                echo '<td>' . esc_html(isset($result['reason']) ? $result['reason'] : 'Convertido exitosamente') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
        }
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Convertidor Inteligente de Menú', 'ugel-theme'); ?></h1>
        <p><?php echo esc_html__('Este sistema analiza tu menú actual y convierte automáticamente:', 'ugel-theme'); ?></p>
        <ul>
            <li><strong><?php echo esc_html__('Páginas con posts:', 'ugel-theme'); ?></strong> <?php echo esc_html__('Se convierten a categorías para usar', 'ugel-theme'); ?> <code>archive.php</code></li>
            <li><strong><?php echo esc_html__('Páginas sin posts:', 'ugel-theme'); ?></strong> <?php echo esc_html__('Se mantienen como páginas para usar', 'ugel-theme'); ?> <code>single.php</code></li>
        </ul>

        <?php ugel_analyze_current_menu(); ?>

        <form method="post" style="margin-top: 20px;">
            <?php wp_nonce_field('ugel_update_menu'); ?>
            <input type="submit" name="update_menu" class="button button-primary"
                   value="<?php echo esc_attr__('Aplicar Conversión Inteligente', 'ugel-theme'); ?>"
                   onclick="return confirm('<?php echo esc_js(__('¿Proceder con la conversión? Solo se cambiarán los elementos marcados en verde.', 'ugel-theme')); ?>')">
        </form>

        <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-left: 4px solid #2196F3;">
            <h3><?php echo esc_html__('¿Cómo funciona?', 'ugel-theme'); ?></h3>
            <ol>
                <li><strong><?php echo esc_html__('Analiza tu menú actual:', 'ugel-theme'); ?></strong> <?php echo esc_html__('Lee todos los elementos existentes sin modificar nada', 'ugel-theme'); ?></li>
                <li><strong><?php echo esc_html__('Busca categorías equivalentes:', 'ugel-theme'); ?></strong> <?php echo esc_html__('Para cada página, busca una categoría con el mismo nombre/slug', 'ugel-theme'); ?></li>
                <li><strong><?php echo esc_html__('Cuenta posts:', 'ugel-theme'); ?></strong> <?php echo esc_html__('Verifica si esa categoría tiene contenido (posts, convocatorias, comunicados)', 'ugel-theme'); ?></li>
                <li><strong><?php echo esc_html__('Convierte inteligentemente:', 'ugel-theme'); ?></strong> <?php echo esc_html__('Solo cambia páginas a categorías cuando tienen contenido', 'ugel-theme'); ?></li>
                <li><strong><?php echo esc_html__('Preserva estructura:', 'ugel-theme'); ?></strong> <?php echo esc_html__('Mantiene el orden, jerarquía y títulos originales', 'ugel-theme'); ?></li>
            </ol>
        </div>
    </div>
    <?php
}

require_once get_template_directory() . '/chatbot-admin.php';

function ugel_enqueue_chatbot_scripts() {
    if (is_admin()) return;

    $child_js_path  = get_stylesheet_directory() . '/chatbot.js';
    $parent_js_path = get_template_directory()   . '/chatbot.js';

    if (file_exists($child_js_path)) {
        $js_uri = get_stylesheet_directory_uri() . '/chatbot.js';
        $js_ver = filemtime($child_js_path);
    } elseif (file_exists($parent_js_path)) {
        $js_uri = get_template_directory_uri() . '/chatbot.js';
        $js_ver = filemtime($parent_js_path);
    } else {
        error_log('❌ UGEL Chatbot: No se encontró chatbot.js');
        return;
    }

    wp_enqueue_script('ugel-chatbot', $js_uri, array(), $js_ver, true);

    $child_json = get_stylesheet_directory() . '/chatbot-responses.json';
    $parent_json = get_template_directory() . '/chatbot-responses.json';
    $json_file = file_exists($child_json) ? $child_json : $parent_json;

    $chatbot_data = array('responses' => array(), 'settings' => array());

    if ($json_file && file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        $decoded_data = json_decode($json_content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {
            $chatbot_data = $decoded_data;
            error_log('✅ UGEL Chatbot: JSON cargado con ' . count($chatbot_data['responses'] ?? array()) . ' respuestas');
        } else {
            error_log('❌ UGEL Chatbot: Error en JSON - ' . json_last_error_msg());
        }
    } else {
        error_log('⚠️ UGEL Chatbot: No se encontró chatbot-responses.json');
    }

    wp_localize_script('ugel-chatbot', 'ugel_chatbot_data', $chatbot_data);
}
add_action('wp_enqueue_scripts', 'ugel_enqueue_chatbot_scripts');

function ugel_create_default_chatbot_json() {
    $json_file = get_stylesheet_directory() . '/chatbot-responses.json';

    if (!file_exists($json_file)) {
        $default_data = array(
            'responses' => array(
                array(
                    'id' => 1,
                    'category' => 'Saludo',
                    'keywords' => array('hola', 'buenos días', 'buenas tardes', 'ayuda', 'asistencia'),
                    'response' => '👋 <strong>¡Hola! Bienvenido/a a la UGEL El Collao</strong><br><br>Soy tu asistente virtual y puedo ayudarte con:<br>• 📋 Información sobre trámites<br>• 📢 Convocatorias vigentes<br>• 🕒 Horarios y contacto<br>• 💻 Servicios online<br>• 📞 Datos de contacto<br><br>¿En qué tema específico necesitas ayuda?',
                    'active' => true
                ),
                array(
                    'id' => 2,
                    'category' => 'Contacto',
                    'keywords' => array('contacto', 'teléfono', 'telefono', 'llamar', 'número', 'numero', 'celular'),
                    'response' => '📞 <strong>Contactos Oficiales:</strong><br>• <strong>Teléfono:</strong> <a href="tel:974202598">974 202 598</a><br>• <strong>Fijo:</strong> <a href="tel:051552506">051 552 506</a><br>• <strong>Email:</strong> <a href="mailto:info@ugelelcollao.edu.pe">info@ugelelcollao.edu.pe</a><br>• <strong>Web:</strong> <a href="https://ugelelcollao.edu.pe" target="_blank">ugelelcollao.edu.pe</a>',
                    'active' => true
                ),
                array(
                    'id' => 3,
                    'category' => 'Ubicación',
                    'keywords' => array('dónde', 'donde', 'ubicación', 'dirección', 'direccion', 'encuentran', 'queda', 'sede'),
                    'response' => 'Nos encontramos en:<br><strong>Jr. Sucre N° 215, Barrio Santa Bárbara</strong><br>Ilave, El Collao, Puno<br><strong>Referencia:</strong> A una cuadra de la plaza de armas<br><br>📍 <a href="https://maps.google.com/?q=Jr.+Sucre+215+Ilave+Puno" target="_blank">Ver en Google Maps</a>',
                    'active' => true
                ),
                array(
                    'id' => 4,
                    'category' => 'Horarios',
                    'keywords' => array('horario', 'horarios', 'atención', 'atencion', 'atienden', 'abren', 'hora', 'cuándo', 'cuando'),
                    'response' => '🕒 <strong>Horarios de Atención:</strong><br>• <strong>Lunes a Viernes:</strong> 8:30 AM - 4:30 PM<br>• <strong>Sábados y Domingos:</strong> Cerrado<br>• <strong>Feriados:</strong> Cerrado<br><br>💡 <em>Te recomendamos llamar antes de tu visita para confirmar disponibilidad.</em>',
                    'active' => true
                )
            ),
            'settings' => array(
                'greeting' => '👋 ¡Hola! Soy el asistente virtual de la UGEL El Collao.<br><br>Puedo ayudarte con:<br>• 📋 Información sobre trámites<br>• 📢 Convocatorias y comunicados<br>• 🕒 Horarios de atención<br>• 📞 Datos de contacto<br>• 💻 Servicios online<br><br>¿En qué puedo ayudarte hoy?',
                'fallback' => '🤔 <strong>No encontré información específica sobre tu consulta.</strong><br><br>Para una atención personalizada:<br>• 📞 <strong>Llámanos:</strong> 974 202 598<br>• 📧 <strong>Escríbenos:</strong> info@ugelelcollao.edu.pe<br>• 🏢 <strong>Visítanos:</strong> Jr. Sucre N° 215, Ilave<br>• 🕒 <strong>Horario:</strong> Lun-Vie 8:30AM-4:30PM',
                'typing_delay' => 1500,
                'match_threshold' => 1
            )
        );

        $json_content = json_encode($default_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $result = file_put_contents($json_file, $json_content);

        if ($result !== false) {
            @chmod($json_file, 0666);
            error_log('✅ UGEL Chatbot: Archivo JSON creado exitosamente');
        } else {
            error_log('❌ UGEL Chatbot: Error al crear archivo JSON');
        }
    }
}
add_action('after_setup_theme', 'ugel_create_default_chatbot_json');

function ugel_chatbot_get_response() {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce'] ?? ''), 'ugel_chatbot_nonce')) {
        wp_send_json_error(array('error' => 'Nonce inválido'));
    }

    $user_message = sanitize_text_field($_POST['message'] ?? '');
    if (empty($user_message)) {
        wp_send_json_error(array('error' => 'Mensaje vacío'));
    }

    $child_json  = get_stylesheet_directory() . '/chatbot-responses.json';
    $parent_json = get_template_directory()   . '/chatbot-responses.json';
    $json_file   = file_exists($child_json) ? $child_json : $parent_json;

    $responses = array();
    $settings  = array();

    if ($json_file && file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        $data = json_decode($json_content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $responses = $data['responses'] ?? array();
            $settings  = $data['settings']  ?? array();
        }
    }

    $response_text = ugel_find_chatbot_response($user_message, $responses, $settings);

    wp_send_json_success(array(
        'response'  => $response_text,
        'timestamp' => current_time('mysql')
    ));
}
add_action('wp_ajax_ugel_chatbot_response', 'ugel_chatbot_get_response');
add_action('wp_ajax_nopriv_ugel_chatbot_response', 'ugel_chatbot_get_response');

function ugel_find_chatbot_response($user_message, $responses, $settings) {
    $message = strtolower(trim($user_message));
    $message = remove_accents($message);

    $best_match  = null;
    $max_matches = 0;
    $threshold   = intval($settings['match_threshold'] ?? 1);

    foreach ($responses as $response) {
        if (empty($response['active']) || empty($response['keywords'])) continue;

        $matches = 0;
        foreach ($response['keywords'] as $keyword) {
            $keyword = remove_accents(strtolower(trim($keyword)));
            if (strpos($message, $keyword) !== false) $matches++;
        }

        if ($matches >= $threshold && $matches > $max_matches) {
            $max_matches = $matches;
            $best_match  = $response;
        }
    }

    return $best_match
        ? $best_match['response']
        : ($settings['fallback'] ?? 'Lo siento, no tengo información específica sobre tu consulta. Para más información, contacta al 974 202 598');
}

function ugel_chatbot_shortcode($atts) {
    return '<div id="ugel-chatbot-shortcode"><!-- El chatbot se carga automáticamente desde el footer --></div>';
}
add_shortcode('ugel_chatbot', 'ugel_chatbot_shortcode');

function ugel_chatbot_debug() {
    if (!current_user_can('manage_options')) {
        return 'No tienes permisos para ver esta información.';
    }

    $child_json  = get_stylesheet_directory() . '/chatbot-responses.json';
    $parent_json = get_template_directory()   . '/chatbot-responses.json';
    $json_file   = file_exists($child_json) ? $child_json : $parent_json;

    ob_start();
    ?>
    <div style="background: #f0f0f0; padding: 15px; margin: 10px; border-radius: 5px; font-family: monospace;">
        <h4>🔧 Debug del Chatbot UGEL El Collao</h4>

        <p><strong>Archivo JSON activo:</strong><br>
        <code><?php echo esc_html($json_file); ?></code></p>

        <p><strong>Estado del archivo:</strong><br>
        <?php if ($json_file && file_exists($json_file)): ?>
            ✅ <strong>Existe</strong><br>
            <?php if (is_readable($json_file)): ?>
                ✅ <strong>Legible</strong><br>
            <?php else: ?>
                ❌ <strong>No legible</strong><br>
            <?php endif; ?>

            <?php if (is_writable($json_file)): ?>
                ✅ <strong>Escribible</strong><br>
            <?php else: ?>
                ⚠️ <strong>No escribible</strong><br>
            <?php endif; ?>

            <strong>Tamaño:</strong> <?php echo size_format(filesize($json_file)); ?><br>
            <strong>Última modificación:</strong> <?php echo date('Y-m-d H:i:s', filemtime($json_file)); ?>
        <?php else: ?>
            ❌ <strong>No existe</strong>
        <?php endif; ?>
        </p>

        <?php if ($json_file && file_exists($json_file)): ?>
            <?php
            $content = file_get_contents($json_file);
            $data    = json_decode($content, true);
            ?>
            <p><strong>Contenido JSON:</strong><br>
            <?php if ($data && json_last_error() === JSON_ERROR_NONE): ?>
                ✅ <strong>JSON válido</strong><br>
                <strong>Total respuestas:</strong> <?php echo count($data['responses'] ?? array()); ?><br>
                <strong>Respuestas activas:</strong> <?php echo count(array_filter($data['responses'] ?? array(), function($r) { return $r['active'] ?? true; })); ?><br>
                <strong>Configuraciones:</strong> <?php echo count($data['settings'] ?? array()); ?>
            <?php else: ?>
                ❌ <strong>JSON inválido:</strong> <?php echo json_last_error_msg(); ?>
            <?php endif; ?>
            </p>
        <?php endif; ?>

        <p><strong>Child theme:</strong><br>
        <code><?php echo esc_html(get_stylesheet_directory()); ?></code></p>

        <p><strong>Parent theme:</strong><br>
        <code><?php echo esc_html(get_template_directory()); ?></code></p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ugel_chatbot_debug', 'ugel_chatbot_debug');

add_shortcode('ugel_destacados', function($atts) {
    $atts = shortcode_atts(array(
        'limite' => 4
    ), $atts);
    $destacados = get_destacados($atts['limite']);
    if (!$destacados) return '';
    ob_start(); ?>
    <ol class="comm-list">
        <?php foreach ($destacados as $dest):
            $ttl = get_the_title($dest);
            $url = get_permalink($dest);
            $img = get_the_post_thumbnail_url($dest->ID, 'featured-small');
            $sum = has_excerpt($dest) ? get_the_excerpt($dest) : '';
            $has_img = !empty($img);
        ?>
            <li class="comm-item <?php echo $has_img ? '' : 'noimg'; ?>">
                <?php if ($has_img): ?>
                    <figure class="comm-thumb">
                        <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($ttl); ?>" loading="lazy" decoding="async">
                    </figure>
                <?php endif; ?>
                <div class="comm-body">
                    <h3 class="comm-title">
                        <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($ttl); ?></a>
                    </h3>
                    <?php if ($sum): ?>
                        <p class="comm-excerpt"><?php echo esc_html($sum); ?></p>
                    <?php endif; ?>
                </div>
                <div class="comm-actions">
                    <a class="hub-btn" href="<?php echo esc_url($url); ?>"><?php echo esc_html__('Ver más','ugel-theme'); ?></a>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
    <?php
    return ob_get_clean();
});

function ugel_chatbot_admin_debug_info() {
    if (!current_user_can('manage_options')) return;

    echo '<div class="notice notice-info"><p>';
    echo '<strong>🔧 Debug Chatbot:</strong> ';
    echo 'Scripts encolados: ' . (wp_script_is('ugel-chatbot', 'enqueued') ? '✅' : '❌') . ' | ';
    echo 'Data disponible: ' . (wp_scripts()->get_data('ugel-chatbot', 'data') ? '✅' : '❌');
    echo '</p></div>';
}
add_action('admin_notices', 'ugel_chatbot_admin_debug_info');


/* ===========================================================
 * SEO On-Page (OG/Twitter/JSON-LD/Canonical)
 * =========================================================== */

// Evitar duplicados si hay plugin SEO
function ugel_has_seo_plugin() {
    return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('SEOPRESS_VERSION');
}

// Canonical
add_action('wp_head', function(){
    if (is_admin() || ugel_has_seo_plugin()) return;
    echo '<link rel="canonical" href="'.esc_url((is_singular() ? get_permalink() : home_url(add_query_arg(array(), $GLOBALS['wp']->request)))) . '/">'. "\n";
}, 5);

// Open Graph & Twitter Cards
add_action('wp_head', function(){
    if (is_admin() || ugel_has_seo_plugin()) return;

    $title = wp_get_document_title();
    $desc  = '';
    $url   = home_url('/');
    $img   = '';

    if (is_singular()) {
        global $post;
        $url  = get_permalink($post);
        $desc = has_excerpt($post) ? wp_strip_all_tags(get_the_excerpt($post)) : wp_trim_words(wp_strip_all_tags($post->post_content), 30);
        if (has_post_thumbnail($post)) {
            $img = get_the_post_thumbnail_url($post, 'large');
        }
    } else {
        $desc = get_bloginfo('description');
        $img  = get_site_icon_url(512);
    }

    echo "\n<!-- UGEL SEO -->\n";
    echo '<meta property="og:type" content="'.(is_singular() ? 'article' : 'website').'">'."\n";
    echo '<meta property="og:title" content="'.esc_attr($title).'">'."\n";
    if ($desc) echo '<meta property="og:description" content="'.esc_attr($desc).'">'."\n";
    echo '<meta property="og:url" content="'.esc_url($url).'">'."\n";
    if ($img) echo '<meta property="og:image" content="'.esc_url($img).'">'."\n";

    echo '<meta name="twitter:card" content="'.($img ? 'summary_large_image' : 'summary').'">'."\n";
    echo '<meta name="twitter:title" content="'.esc_attr($title).'">'."\n";
    if ($desc) echo '<meta name="twitter:description" content="'.esc_attr($desc).'">'."\n";
    if ($img) echo '<meta name="twitter:image" content="'.esc_url($img).'">'."\n";
}, 6);

// JSON-LD Organization + BreadcrumbList
add_action('wp_head', function(){
    if (is_admin() || ugel_has_seo_plugin()) return;

    $org = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => get_bloginfo('name'),
        'url'      => home_url('/'),
        'logo'     => get_site_icon_url(512) ?: (get_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : ''),
        'email'    => get_theme_mod('ugel_email', ''),
        'telephone'=> get_theme_mod('ugel_phone', ''),
        'address'  => array(
            '@type' => 'PostalAddress',
            'streetAddress' => get_theme_mod('ugel_address', '')
        ),
        'sameAs'   => array_filter(array(
            get_theme_mod('ugel_facebook', ''),
            get_theme_mod('ugel_twitter', ''),
            get_theme_mod('ugel_instagram', '')
        ))
    );

    $schemas = array($org);

    if (!is_front_page()) {
        $items = array(
            array('@type'=>'ListItem','position'=>1,'name'=>__('Inicio','ugel-theme'),'item'=>home_url('/'))
        );
        if (is_singular()) {
            $items[] = array('@type'=>'ListItem','position'=>2,'name'=>get_the_title(),'item'=>get_permalink());
        } elseif (is_category()) {
            $items[] = array('@type'=>'ListItem','position'=>2,'name'=>single_cat_title('',false),'item'=>get_category_link(get_queried_object_id()));
        } elseif (is_search()) {
            $items[] = array('@type'=>'ListItem','position'=>2,'name'=>sprintf(__('Búsqueda: %s','ugel-theme'), get_search_query()));
        }
        $schemas[] = array(
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => $items
        );
    }

    echo '<script type="application/ld+json">'. wp_json_encode($schemas) .'</script>' . "\n";
}, 7);
/* ================== Vistas por entrada — UGEL ================== */
if (!function_exists('ugel_is_bot')) {
  function ugel_is_bot() {
    if (php_sapi_name() === 'cli') return true;
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
    if ($ua === '') return true;
    $bots = ['bot','crawl','slurp','spider','mediapartners','facebookexternalhit','preview','http','monitor'];
    foreach ($bots as $b) { if (strpos($ua, $b) !== false) return true; }
    return false;
  }
}

if (!function_exists('ugel_get_post_views')) {
  function ugel_get_post_views($post_id = null) {
    $post_id = $post_id ? intval($post_id) : get_the_ID();
    return (int) get_post_meta($post_id, '_ugel_views', true);
  }
}

if (!function_exists('ugel_set_post_views')) {
  function ugel_set_post_views($post_id) {
    $current = ugel_get_post_views($post_id);
    update_post_meta($post_id, '_ugel_views', $current + 1);
  }
}

if (!function_exists('ugel_maybe_count_view')) {
  function ugel_maybe_count_view() {
    // Ajusta los tipos donde quieres contar
    if (!is_singular(['post','page','convocatorias','comunicados'])) return;
    if (is_user_logged_in() && current_user_can('manage_options')) return; // opcional: no contar admin
    if (is_preview() || is_feed() || ugel_is_bot()) return;

    $post_id = get_queried_object_id();
    if (!$post_id) return;

    // Evitar múltiples conteos por usuario (cookie 6h)
    $cookie_name = 'ugel_view_' . $post_id;
    if (!isset($_COOKIE[$cookie_name])) {
      ugel_set_post_views($post_id);
      // Cookie segura
      setcookie(
        $cookie_name, '1',
        time() + 6 * HOUR_IN_SECONDS,
        COOKIEPATH ?: '/',
        COOKIE_DOMAIN,
        is_ssl(),
        true
      );
      // Para que esté disponible inmediatamente en el request actual
      $_COOKIE[$cookie_name] = '1';
    }
  }
  add_action('template_redirect', 'ugel_maybe_count_view');
}

if (!function_exists('ugel_the_views_badge')) {
  function ugel_the_views_badge($post_id = null, $label = 'vistas') {
    $post_id = $post_id ? intval($post_id) : get_the_ID();
    if (!$post_id) return;

    $views = ugel_get_post_views($post_id);
    $views_fmt = number_format_i18n($views);

    // Icono inline (coincide con tu .foot-views)
    $svg = '<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" style="margin-right:6px">
              <path fill="currentColor" d="M12 5c5.5 0 9.5 4.5 10.7 6-.8 1-5 6-10.7 6S2.5 12 1.3 11C2.5 9.5 6.5 5 12 5Zm0 2C7.7 7 4.4 10.3 3.3 11 4.5 11.8 7.8 15 12 15s7.5-3.2 8.7-4C19.5 10.3 16.3 7 12 7Zm0 2.5A3.5 3.5 0 1 1 8.5 13 3.5 3.5 0 0 1 12 9.5Zm0 2a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 12 11.5Z"/>
            </svg>';

    echo '<span class="foot-views" title="' . esc_attr($views_fmt . ' ' . $label) . '">' . $svg . esc_html($views_fmt . ' ' . $label) . '</span>';
  }
}

/* Shortcode útil para probar en cualquier parte: [ugel_views] */
add_shortcode('ugel_views', function($atts){
  $atts = shortcode_atts(['label'=>'vistas','id'=>0], $atts, 'ugel_views');
  $id = $atts['id'] ? intval($atts['id']) : get_the_ID();
  ob_start();
  ugel_the_views_badge($id, $atts['label']);
  return ob_get_clean();
});


?>
