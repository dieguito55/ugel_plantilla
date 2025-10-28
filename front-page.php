<?php
get_header(); ?>

<section class="hero-carousel" aria-label="Carrusel principal" data-theme="institutional">
  <div class="hero-track" id="heroTrack">
    <?php
    $slides = get_hero_slides();
    $slide_count = 0;

    if ($slides):
      foreach ($slides as $slide):
        $slide_count++;
        $alineacion = get_post_meta($slide->ID, '_alineacion', true) ?: 'left';
        $ribbon_texto = get_post_meta($slide->ID, '_ribbon_texto', true);
        $boton_texto = get_post_meta($slide->ID, '_boton_texto', true);
        $boton_url = get_post_meta($slide->ID, '_boton_url', true);
        $boton_secundario_texto = get_post_meta($slide->ID, '_boton_secundario_texto', true);
        $boton_secundario_url = get_post_meta($slide->ID, '_boton_secundario_url', true);
        $imagen = get_the_post_thumbnail_url($slide->ID, 'hero-slide');
        $active_class = $slide_count === 1 ? 'is-active' : '';
        $aria_hidden  = $slide_count === 1 ? 'false' : 'true';
    ?>
    <article class="hero-slide align-<?php echo esc_attr($alineacion); ?> <?php echo $active_class; ?>" aria-hidden="<?php echo $aria_hidden; ?>">
      <?php if ($imagen): ?>
        <img src="<?php echo esc_url($imagen); ?>" alt="<?php echo esc_attr($slide->post_title); ?>" />
      <?php endif; ?>
      <div class="veil" aria-hidden="true"></div>

      <?php if ($slide->post_content || $ribbon_texto || $boton_texto): ?>
      <div class="caption">
        <div class="cap-box">
          <?php if ($ribbon_texto): ?>
            <span class="ribbon"><?php echo esc_html($ribbon_texto); ?></span>
          <?php endif; ?>

          <?php if ($slide->post_title): ?>
            <h2 class="cap-title"><?php echo wp_kses_post($slide->post_title); ?></h2>
          <?php endif; ?>

          <?php if ($slide->post_content): ?>
            <div class="cap-desc"><?php echo wp_kses_post(wpautop($slide->post_content)); ?></div>
          <?php endif; ?>

          <?php if ($boton_texto || $boton_secundario_texto): ?>
          <div class="cap-actions">
            <?php if ($boton_texto && $boton_url): ?>
              <a class="btn-cta" href="<?php echo esc_url($boton_url); ?>">
                <span><?php echo esc_html($boton_texto); ?></span>
                <svg class="btn-icon" width="14" height="14" viewBox="0 0 16 16" fill="none">
                  <path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            <?php endif; ?>
            <?php if ($boton_secundario_texto && $boton_secundario_url): ?>
              <a class="btn-ghost" href="<?php echo esc_url($boton_secundario_url); ?>">
                <span><?php echo esc_html($boton_secundario_texto); ?></span>
              </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </article>
    <?php
      endforeach;
    endif; ?>
  </div>

  <div class="hero-arrows" aria-hidden="true">
    <button class="hero-arrow prev" type="button" id="heroPrev" aria-label="Anterior">‹</button>
    <button class="hero-arrow next" type="button" id="heroNext" aria-label="Siguiente">›</button>
  </div>

  <div class="hero-controls" role="tablist" aria-label="Paginación carrusel" id="heroDots"></div>
</section>



<section class="features-section" aria-label="Accesos rápidos">
  <div class="container">
    <div class="features-grid">
      <?php
      $tarjetas = get_feature_cards(4);
      if ($tarjetas && count($tarjetas) > 0):
        foreach ($tarjetas as $t):
          $img = get_the_post_thumbnail_url($t->ID, 'featured-large');
          $ttl = get_the_title($t);
          $sub = has_excerpt($t) ? get_the_excerpt($t) : '';
          $url = get_post_meta($t->ID, '_enlace_url', true) ?: get_permalink($t);
          $tgt = get_post_meta($t->ID, '_enlace_target', true) ?: '_self';
          $svg = get_post_meta($t->ID, '_icono_svg', true);
          $bgStyle = $img ? " style=\"background-image: url('" . esc_url($img) . "');\"" : '';
      ?>
        <article class="feature-card">
          <div class="feature-card__bg"<?php echo $bgStyle; ?>></div>
          
          <div class="feature-card__wave"></div>
          
          <div class="feature-card__content">
            <?php if ($svg): ?>
              <div class="feature-card__icon" aria-hidden="true">
                <?php echo $svg; ?>
              </div>
            <?php endif; ?>
            
            <h3 class="feature-card__title">
              <?php echo esc_html($ttl); ?>
            </h3>
            
            <?php if ($sub): ?>
              <p class="feature-card__description">
                <?php echo esc_html($sub); ?>
              </p>
            <?php endif; ?>
            
            <a 
              class="feature-card__cta" 
              href="<?php echo esc_url($url); ?>" 
              target="<?php echo esc_attr($tgt); ?>"
              aria-label="Acceder a <?php echo esc_attr($ttl); ?>"
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
        </article>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<section class="hub" aria-label="Contenido principal UGEL">
  <div class="wrap">
    <div class="hub-layout">
        <?php get_template_part('template-parts/accesos-directos'); ?>

      <div class="hub-main">
         
        <?php
        // Helper para snippet: limpia {{PDFX}} y shortcodes
        if (!function_exists('ugel_front_snippet')) {
          function ugel_front_snippet($post_input, $max_words = 28){
            $post = is_object($post_input) ? $post_input : get_post($post_input);
            if (!$post) return '';
            $text = has_excerpt($post) ? $post->post_excerpt : $post->post_content;
            $text = strip_shortcodes($text);                 // [shortcodes]
            $text = preg_replace('/\{\{PDF\d+\}\}/i', '', $text); // {{PDF1}}, {{PDF2}}, etc.
            $text = wp_strip_all_tags($text);
            $text = preg_replace('/\s+/', ' ', $text);
            $words = preg_split('/\s+/', trim($text));
            if (count($words) > $max_words) {
              $text = implode(' ', array_slice($words, 0, $max_words)) . '…';
            }
            return $text;
          }
        }
        ?>
         <!-- =================== -->
        <!-- DESTACADOS (1 x 6) -->
        <!-- =================== -->
        <?php
        $q_destacados = new WP_Query([
          'post_type'      => array('post', 'convocatorias', 'comunicados'),
          'posts_per_page' => 6,
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
          'category_name'  => 'destacados'
        ]);
        if ($q_destacados->have_posts()): ?>
        <section class="hub-sec" aria-labelledby="hub-destacados">
          <header class="hub-head">
            <h2 id="hub-destacados" class="title-ltra">Destacados</h2>
            <a class="hub-more" href="<?php echo esc_url( get_category_link( get_category_by_slug('destacados')->term_id ) ); ?>">Ver todos</a>
          </header>

          <ol class="comm-list">
            <?php while ($q_destacados->have_posts()): $q_destacados->the_post();
              $ttl = get_the_title();
              $url = get_permalink();
              $img = get_the_post_thumbnail_url(get_the_ID(), 'featured-small');
              $sum = function_exists('ugel_front_snippet') ? ugel_front_snippet(get_post(), 28) : '';
              $has_img = !empty($img);
            ?>
            <li class="comm-item <?php echo $has_img ? '' : 'noimg'; ?>" itemscope itemtype="https://schema.org/Article">

              <?php if ($has_img): ?>
                <figure class="comm-thumb" aria-hidden="false">
                  <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($ttl); ?>" loading="lazy" itemprop="image">
                </figure>
              <?php endif; ?>

              <div class="comm-body">
                <h3 class="comm-title" itemprop="headline">
                  <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($ttl); ?></a>
                </h3>
                <?php if ($sum): ?>
                  <p class="comm-excerpt"><?php echo esc_html($sum); ?></p>
                <?php endif; ?>
              </div>

              <div class="comm-actions">
                <a class="hub-btn" href="<?php echo esc_url($url); ?>" aria-label="Ver más sobre <?php echo esc_attr($ttl); ?>">Ver más</a>
              </div>
            </li>
            <?php endwhile; wp_reset_postdata(); ?>
          </ol>
        </section>
        <?php endif; ?>
        <!-- =================== -->
        <!-- COMUNICADOS (1 x 6) -->
        <!-- =================== -->
        <section class="hub-sec" aria-labelledby="hub-comunicados">
          <header class="hub-head">
            <h2 id="hub-comunicados" class="title-ltra">Comunicados</h2>
            <a class="hub-more" href="<?php echo esc_url( get_post_type_archive_link('comunicados') ); ?>">Ver todos</a>
          </header>

          <?php
    $q_com = new WP_Query([
      'post_type'      => 'comunicados',
      'posts_per_page' => 6,
      'post_status'    => 'publish',
      'orderby'        => 'date',
      'order'          => 'DESC'
    ]);
    if ($q_com->have_posts()): ?>
      <ol class="comm-list">
        <?php while ($q_com->have_posts()): $q_com->the_post();
          $ttl = get_the_title();
          $url = get_permalink();
          $img = get_the_post_thumbnail_url(get_the_ID(), 'featured-small');
          $sum = function_exists('ugel_front_snippet') ? ugel_front_snippet(get_post(), 28) : '';
          $has_img = !empty($img);
        ?>
        <li class="comm-item <?php echo $has_img ? '' : 'noimg'; ?>" itemscope itemtype="https://schema.org/Article">

          <?php if ($has_img): ?>
            <figure class="comm-thumb" aria-hidden="false">
              <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($ttl); ?>" loading="lazy" itemprop="image">
            </figure>
          <?php endif; ?>

          <div class="comm-body">
            <h3 class="comm-title" itemprop="headline">
              <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($ttl); ?></a>
            </h3>
            <?php if ($sum): ?>
              <p class="comm-excerpt"><?php echo esc_html($sum); ?></p>
            <?php endif; ?>
          </div>

          <div class="comm-actions">
            <a class="hub-btn" href="<?php echo esc_url($url); ?>" aria-label="Ver más sobre <?php echo esc_attr($ttl); ?>">Ver más</a>
          </div>
        </li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ol>
    <?php else: ?>
      <p>No hay comunicados disponibles por ahora.</p>
    <?php endif; ?>

        </section>
        <!-- ===================== -->
        <!-- CONVOCATORIAS (3 x 2) -->
        <!-- ===================== -->
        <section class="hub-sec" aria-labelledby="hub-convocatorias">
          <header class="hub-head">
            <h2 id="hub-convocatorias" class="title-ltra">Convocatorias</h2>
            <a class="hub-more" href="<?php echo esc_url( get_post_type_archive_link('convocatorias') ); ?>">Ver todas</a>
          </header>

          <?php
          $q_conv = new WP_Query([
            'post_type'      => 'convocatorias',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
          ]);
          if ($q_conv->have_posts()): ?>
            <div class="conv-preview" role="list" aria-label="Listado rápido de convocatorias">
              <?php while ($q_conv->have_posts()): $q_conv->the_post();
                $ttl   = get_the_title();
                $url   = get_permalink();
                $meta  = ugel_get_convocatoria_meta(get_the_ID());
                $state = ugel_get_convocatoria_status_details($meta['fecha_inicio'] ?? '', $meta['fecha_fin'] ?? '');
                $slug  = isset($state['slug']) ? sanitize_html_class($state['slug']) : 'en_proceso';
                $label = $state['label'] ?? __('En proceso', 'ugel-theme');
                $indice = $meta['indice'] ?? '';
                $tipo   = $meta['tipo'] ?? '';
                $fi_raw = $meta['fecha_inicio'] ?? '';
                $ff_raw = $meta['fecha_fin'] ?? '';
                $fi     = ugel_format_convocatoria_date($fi_raw);
                $ff     = ugel_format_convocatoria_date($ff_raw);
                if ($fi && $ff) {
                  $fecha_texto = sprintf(__('Del %1$s al %2$s', 'ugel-theme'), $fi, $ff);
                } elseif ($fi) {
                  $fecha_texto = sprintf(__('Desde el %s', 'ugel-theme'), $fi);
                } elseif ($ff) {
                  $fecha_texto = sprintf(__('Hasta el %s', 'ugel-theme'), $ff);
                } else {
                  $fecha_texto = __('Fecha por confirmar', 'ugel-theme');
                }
                $date_attr = $fi_raw ?: ($ff_raw ?: current_time('Y-m-d'));
              ?>
              <article class="conv-preview__item" itemscope itemtype="https://schema.org/Event" role="listitem">
                <div class="conv-preview__main">
                  <?php if (!empty($indice)): ?>
                    <span class="conv-preview__index">#<?php echo esc_html($indice); ?></span>
                  <?php endif; ?>
                  <h3 class="conv-preview__title" itemprop="name">
                    <a href="<?php echo esc_url($url); ?>" itemprop="url"><?php echo esc_html($ttl); ?></a>
                  </h3>
                  <?php if (!empty($tipo)): ?>
                    <p class="conv-preview__type"><?php echo esc_html($tipo); ?></p>
                  <?php endif; ?>
                </div>
                <div class="conv-preview__meta">
                  <span class="conv-preview__status conv-preview__status--<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></span>
                  <time class="conv-preview__date" datetime="<?php echo esc_attr($date_attr); ?>" itemprop="startDate">
                    <?php echo esc_html($fecha_texto); ?>
                  </time>
                </div>
                <div class="conv-preview__actions">
                  <a class="conv-preview__link" href="<?php echo esc_url($url); ?>" aria-label="Ver convocatoria <?php echo esc_attr($ttl); ?>">
                    <?php esc_html_e('Ver', 'ugel-theme'); ?>
                  </a>
                </div>
              </article>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          <?php else: ?>
            <p>No hay convocatorias disponibles por ahora.</p>
          <?php endif; ?>
        </section>

      </div>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/contenidovisual'); ?>

<section class="interest-links" aria-label="Enlaces de interés">
  <div class="wrap">
    <div class="interest-links__header">
      <h2 class="interest-links__title">Enlaces de Interés</h2>
    </div>
  </div>
</section>

<section class="logo-ribbon" aria-label="Carrusel de accesos rápidos">
  <div class="logo-ribbon__viewport">
    <div class="logo-ribbon__track">
      <?php
      $enlaces = get_enlaces_interes();
      if ($enlaces):
        for ($i = 0; $i < 2; $i++):
          foreach ($enlaces as $enlace):
            $url    = get_post_meta($enlace->ID, '_enlace_url', true);
            $target = get_post_meta($enlace->ID, '_enlace_target', true) ?: '_self';
            $imagen = get_the_post_thumbnail_url($enlace->ID, 'quick-access');
            if ($imagen && $url): ?>
              <a class="logo-ribbon__link" 
                 href="<?php echo esc_url($url); ?>" 
                 target="<?php echo esc_attr($target); ?>"
                 rel="<?php echo $target === '_blank' ? 'noopener noreferrer' : ''; ?>"
                 aria-label="<?php echo esc_attr($enlace->post_title); ?>">
                <img src="<?php echo esc_url($imagen); ?>" 
                     alt="<?php echo esc_attr($enlace->post_title); ?>"
                     loading="lazy">
              </a>
            <?php
            endif;
          endforeach;
        endfor;
      endif;
      ?>
    </div>
  </div>
</section>

<section class="visit-section" aria-label="Información de contacto y ubicación UGEL El Collao">
  <div class="visit-container">
    <div class="visit-grid">
      
      <!-- PANEL IZQUIERDO -->
      <aside class="visit-info-panel">
        <header class="visit-header" aria-labelledby="visit-title">
          <p class="visit-kicker">Información de contacto y ubicación</p>
          <h2 id="visit-title" class="visit-title">Visítanos en UGEL El Collao</h2>
          <p class="visit-subtitle">Estamos aquí para atenderte</p>
        </header>

        <!-- HORARIOS -->
        <article class="visit-card" aria-labelledby="card-horario">
          <div class="card-header">
            <div class="card-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2V7h2v10z"/>
              </svg>
            </div>
            <div>
              <h3 class="card-title" id="card-horario">Horario de atención</h3>
              <p class="card-desc">Nuestros horarios de servicio</p>
            </div>
          </div>
          <div class="schedule-grid" role="list">
            <div class="schedule-item" role="listitem">
              <span class="day">Lunes a Viernes</span>
              <span class="dots" aria-hidden="true"></span>
              <span class="time">8:30 AM – 4:30 PM</span>
            </div>
            <div class="schedule-item" role="listitem">
              <span class="day">Sábados</span>
              <span class="dots" aria-hidden="true"></span>
              <span class="time closed">Cerrado</span>
            </div>
            <div class="schedule-item" role="listitem">
              <span class="day">Domingos</span>
              <span class="dots" aria-hidden="true"></span>
              <span class="time closed">Cerrado</span>
            </div>
            <div class="schedule-item" role="listitem">
              <span class="day">Feriados</span>
              <span class="dots" aria-hidden="true"></span>
              <span class="time closed">Cerrado</span>
            </div>
          </div>
        </article>

        <!-- DIRECCIÓN -->
        <article class="visit-card" aria-labelledby="card-direccion">
          <div class="card-header">
            <div class="card-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7Zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z"/>
              </svg>
            </div>
            <div>
              <h3 class="card-title" id="card-direccion">Visítanos</h3>
              <p class="card-desc">Nuestra ubicación</p>
            </div>
          </div>
          <div class="address-info">
            <p class="address-line">Jr. Sucre N° 215, Barrio Santa Bárbara</p>
            <p class="address-line">Ilave, El Collao, Puno</p>
            <p class="address-note">(A una cuadra de la plaza de armas)</p>
          </div>
        </article>

        <!-- NOTA -->
        <p class="visit-note" role="note">
          <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">
            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <span>Horarios sujetos a feriados nacionales y disposiciones del sector educación.</span>
        </p>
      </aside>

      <!-- MAPA — COLUMNA DERECHA A TODA ALTURA -->
      <div class="visit-map-container">
        <div class="map-wrapper">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3808.5!2d-69.6369!3d-16.0986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915e72e8b8b8b8b8%3A0x1234567890!2sJr.%20Sucre%20215%2C%20Ilave!5e0!3m2!1ses!2spe!4v1234567890" 
            loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            aria-label="Mapa interactivo de la ubicación de UGEL El Collao"></iframe>

          <!-- Marcador decorativo -->
          <div class="map-pin" aria-hidden="true">
            <div class="pin-icon"></div>
          </div>
          <div class="map-pulse" aria-hidden="true"></div>

          <!-- WhatsApp -->
          <a class="whatsapp-fab" href="https://wa.me/51974202598" target="_blank" rel="noopener noreferrer" aria-label="Escríbenos por WhatsApp">
            <div class="wa-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 1 1 4.432 4.433L.057 24Zm6.597-3.807a9.804 9.804 0 1 0-3.3-3.3l-.98 3.58 3.58-.98Z"/></svg>
            </div>
            <span>WhatsApp</span>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


<?php get_footer();
