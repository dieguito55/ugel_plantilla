<?php
/**
 * Archivo de archivo (Convocatorias / Comunicados / Categorías / Etiquetas)
 * Diseño: cards optimizadas con título de 4 líneas
 */
get_header();

$paged = max(1, get_query_var('paged', 1));

if (!function_exists('ugel_front_snippet')) {
  function ugel_front_snippet($post_input, $max_words = 28){
    $post = is_object($post_input) ? $post_input : get_post($post_input);
    if (!$post) return '';
    $text = has_excerpt($post) ? $post->post_excerpt : $post->post_content;
    $text = strip_shortcodes($text);
    $text = preg_replace('/\{\{PDF\d+\}\}/i', '', $text);
    $text = wp_strip_all_tags($text);
    $text = preg_replace('/\s+/', ' ', $text);
    $words = preg_split('/\s+/', trim($text));
    if (count($words) > $max_words) $text = implode(' ', array_slice($words, 0, $max_words)) . '…';
    return $text;
  }
}

/* ====== TÍTULO CONTEXTO ====== */
$page_title = '';
if (is_category()) {
  $page_title = single_cat_title('', false);
} elseif (is_tag()) {
  $page_title = single_tag_title('', false);
} elseif (is_tax()) {
  $page_title = single_term_title('', false);
} elseif (is_post_type_archive()) {
  $page_title = post_type_archive_title('', false);
} elseif (is_author() || is_date() || is_search()) {
  $page_title = get_the_archive_title();
} else {
  $pt = get_post_type();
  $pto = $pt ? get_post_type_object($pt) : null;
  $page_title = $pto ? $pto->labels->name : __('Archivo', 'ugel-theme');
}

/* ====== LOOP ====== */
global $wp_query;
$loop   = null;
$totalp = 1;

$should_force_10 = ( is_category() || is_tag() || is_tax() || is_post_type_archive('post')
                  || is_post_type_archive('convocatorias') || is_post_type_archive('comunicados') );

if ($should_force_10) {
  $args   = array_merge($wp_query->query_vars, ['posts_per_page'=>10, 'paged'=>$paged]);
  $loop   = new WP_Query($args);
  $totalp = max(1, (int)$loop->max_num_pages);
} else {
  $loop   = $wp_query;
  $totalp = max(1, (int)$wp_query->max_num_pages);
}

?>
<section class="hub archive-page" aria-label="Archivo: <?php echo esc_attr($page_title); ?>">
  <div class="wrap">
    <div class="hub-layout">

      <aside class="hub-sidebar">
        <?php get_template_part('template-parts/accesos-directos'); ?>
      </aside>

      <div class="hub-main">
        <header class="archive-head">
          <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a>
            <span class="separator">›</span>
            <span class="current"><?php echo esc_html($page_title); ?></span>
          </nav>
          

          
          <?php if (is_archive() && get_the_archive_description()) : ?>
            <p class="archive-desc"><?php echo wp_kses_post(get_the_archive_description()); ?></p>
          <?php endif; ?>
        </header>

        <?php if ($loop->have_posts()): ?>
          <!-- CONTENEDOR HUB-SEC AGREGADO -->
          <section class="hub-sec" aria-labelledby="hub-archivo">
            <header class="hub-head">
              <h2 id="hub-archivo" class="title-ltra"><?php echo esc_html($page_title); ?></h2>
            </header>

            <div class="arch-board">
              <ul class="comm-list">
                <?php while ($loop->have_posts()): $loop->the_post();
                  $ttl   = get_the_title();
                  $url   = get_permalink();
                  $img   = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                  $sum   = ugel_front_snippet(get_post(), 28);
                  $noimg = empty($img);

                  // Etiqueta por post (PT o Categoría)
                  $ptype = get_post_type();
                  if ($ptype === 'convocatorias')      { $label = 'Convocatoria'; }
                  elseif ($ptype === 'comunicados')    { $label = 'Comunicado'; }
                  else {
                    $cats = get_the_category();
                    $sub  = $cats ? array_values(array_filter($cats, fn($c) => (int)$c->parent !== 0)) : [];
                    $pick = $sub ? $sub[0] : ($cats ? $cats[0] : null);
                    $label = $pick ? $pick->name : 'Artículo';
                  }
                ?>
                <li class="comm-item <?php echo $noimg ? 'noimg' : ''; ?>" itemscope itemtype="https://schema.org/Article">
                  <?php if (!$noimg): ?>
                    <div class="comm-thumb">
                      <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($ttl); ?>" loading="lazy" />
                    </div>
                  <?php endif; ?>

                  <div class="comm-body">
                    <div class="comm-meta">
                      <span class="comm-category"><?php echo esc_html($label); ?></span>
                      <time class="comm-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                        <?php echo esc_html(get_the_date('d M Y')); ?>
                      </time>
                    </div>
                    
                    <h2 class="comm-title" itemprop="headline">
                      <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($ttl); ?></a>
                    </h2>
                    
                    <?php if ($sum): ?>
                      <p class="comm-excerpt" itemprop="description"><?php echo esc_html($sum); ?></p>
                    <?php endif; ?>
                  </div>
                  
                  <div class="comm-actions">
                    <a class="hub-btn" href="<?php echo esc_url($url); ?>" aria-label="Ver más sobre <?php echo esc_attr($ttl); ?>">
                      <span>Ver más</span>
                    </a>
                  </div>
                </li>
                <?php endwhile; wp_reset_postdata(); ?>
              </ul>

              <?php
              $links = paginate_links([
                'type'      => 'array',
                'mid_size'  => 2,
                'prev_text' => '‹ Anterior',
                'next_text' => 'Siguiente ›',
                'current'   => $paged,
                'total'     => $totalp,
              ]);
              if ($links): ?>
              <nav class="pager" aria-label="Paginación">
                <ul class="pager-rail">
                  <?php foreach ($links as $l): ?><li class="pager-item"><?php echo $l; ?></li><?php endforeach; ?>
                </ul>
              </nav>
              <?php endif; ?>
            </div>
          </section>
          <!-- FIN CONTENEDOR HUB-SEC -->

        <?php else: ?>
          <div class="arch-board">
            <div class="no-results">
              <h2>No hay contenido disponible</h2>
              <p>No se encontraron publicaciones en esta sección.</p>
              <a class="hub-btn" href="<?php echo esc_url(home_url('/')); ?>">
                <span>Volver al inicio</span>
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  // Card clickeable completa
  function handleCardClick(e){
    if (e.target.closest('a, button, input, textarea, select, [role="button"]')) return;
    const link = e.currentTarget.querySelector('.comm-title a');
    if (link && link.href) {
      window.location.href = link.href;
    }
  }

  document.querySelectorAll('.comm-item').forEach(card => {
    card.addEventListener('click', handleCardClick);
  });

  // Efectos hover mejorados
  document.querySelectorAll('.comm-item').forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.zIndex = '10';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.zIndex = '1';
    });
  });

  // Accesibilidad paginación
  document.querySelectorAll('.pager-rail .current').forEach(current => {
    current.setAttribute('aria-current', 'page');
  });

  // Smooth scroll para paginación
  document.querySelectorAll('.pager-item a').forEach(link => {
    link.addEventListener('click', function(e) {
      const target = document.querySelector('.arch-board');
      if (target) {
        e.preventDefault();
        const href = this.href;
        target.scrollIntoView({ behavior: 'smooth' });
        setTimeout(() => { window.location.href = href; }, 400);
      }
    });
  });
})();
</script>

<?php get_footer();