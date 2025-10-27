<?php
/**
 * Index (fallback) — archivos, búsqueda, etc. (NO la portada)
 * Diseño corporativo premium basado en comunicados UGEL
 */
get_header(); ?>

<div class="hub archive-page">
  <div class="wrap">
    <div class="hub-layout">
      
      <!-- Sidebar con accesos directos -->
      <aside class="hub-sidebar">
        <?php get_template_part('template-parts/accesos-directos'); ?>
      </aside>

      <!-- Contenido principal -->
      <main class="hub-main" role="main">
        <header class="archive-head">
          <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a>
            <span class="separator">›</span>
            <span class="current">
              <?php
              if (is_search()) {
                echo 'Búsqueda: ' . esc_html(get_search_query());
              } else {
                echo esc_html(get_the_archive_title() ?: 'Contenido');
              }
              ?>
            </span>
          </nav>
          
          <h1 class="page-title-ltra">
            <?php
            if (is_search()) {
              printf('Resultados para: "%s"', esc_html(get_search_query()));
            } else {
              echo esc_html(get_the_archive_title() ?: 'Contenido');
            }
            ?>
          </h1>
          
          <?php if (is_archive() && get_the_archive_description()) : ?>
            <p class="archive-desc"><?php echo wp_kses_post(get_the_archive_description()); ?></p>
          <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
          <div class="arch-board">
            <ul class="comm-list">
              <?php while (have_posts()) : the_post(); 
                $post_title = get_the_title();
                $post_excerpt = get_the_excerpt();
                $post_thumbnail = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                $post_date = get_the_date('d M Y');
                
                // Determinar categoría
                $categories = get_the_category();
                $category_name = !empty($categories) ? $categories[0]->name : 'General';
              ?>
                <li class="comm-item <?php echo !$post_thumbnail ? 'noimg' : ''; ?>" itemscope itemtype="https://schema.org/Article">
                  <?php if ($post_thumbnail) : ?>
                    <div class="comm-thumb">
                      <img src="<?php echo esc_url($post_thumbnail); ?>" alt="<?php echo esc_attr($post_title); ?>" loading="lazy" />
                    </div>
                  <?php endif; ?>

                  <div class="comm-body">
                    <div class="comm-meta">
                      <time class="comm-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                        <?php echo esc_html($post_date); ?>
                      </time>
                      <span class="comm-category"><?php echo esc_html($category_name); ?></span>
                    </div>
                    
                    <h2 class="comm-title" itemprop="headline">
                      <a href="<?php the_permalink(); ?>"><?php echo esc_html($post_title); ?></a>
                    </h2>
                    
                    <?php if ($post_excerpt) : ?>
                      <p class="comm-excerpt" itemprop="description"><?php echo esc_html(wp_trim_words($post_excerpt, 20)); ?></p>
                    <?php endif; ?>
                  </div>
                  
                  <div class="comm-actions">
                    <a class="hub-btn" href="<?php the_permalink(); ?>" aria-label="Leer más sobre <?php echo esc_attr($post_title); ?>">
                      <span>Ver más</span>
                    </a>
                  </div>
                </li>
              <?php endwhile; ?>
            </ul>

            <!-- Paginación -->
            <nav class="pager" aria-label="Paginación de contenido">
              <?php
              $prev_link = get_previous_posts_link('‹ Anterior');
              $next_link = get_next_posts_link('Siguiente ›');
              ?>
              <div class="pager-nav">
                <?php if ($prev_link) : ?>
                  <div class="pager-prev"><?php echo $prev_link; ?></div>
                <?php endif; ?>
                
                <?php if ($next_link) : ?>
                  <div class="pager-next"><?php echo $next_link; ?></div>
                <?php endif; ?>
              </div>
            </nav>
          </div>
        <?php else : ?>
          <div class="arch-board">
            <div class="no-results">
              <h2>No se encontró contenido</h2>
              <?php if (is_search()) : ?>
                <p>No hay resultados para su búsqueda. Intente con otros términos.</p>
                <div class="search-again">
                  <?php get_search_form(); ?>
                </div>
              <?php else : ?>
                <p>No hay contenido disponible en esta sección.</p>
                <a class="hub-btn" href="<?php echo esc_url(home_url('/')); ?>">
                  <span>Volver al inicio</span>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </main>
    </div>
  </div>
</div>

<style>
/* ===== ESTILOS PRINCIPALES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.hub.archive-page {
    background: linear-gradient(135deg, #FAFBFF 0%, #F4F7FF 100%);
    font-family: 'Inter', system-ui, sans-serif;
    padding: 40px 20px;
    min-height: 100vh;
}

.wrap {
    max-width: 1200px;
    margin: 0 auto;
}

/* ===== LAYOUT GRID ===== */
.hub-layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 32px;
    align-items: start;
}

.hub-sidebar {
    position: sticky;
    top: 100px;
}

.hub-main {
    min-width: 0;
}

/* ===== HEADER Y BREADCRUMBS ===== */
.archive-head {
    margin: 0 0 50px;
    text-align: center;
}

.breadcrumbs {
    font-size: 14px;
    color: #64748B;
    margin: 0 0 20px;
    padding: 0 0 20px;
    border-bottom: 1px solid rgba(2, 31, 89, 0.1);
    font-family: "Inter", system-ui, sans-serif;
    font-weight: 500;
}

.breadcrumbs a {
    color: #000C97;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.breadcrumbs a:hover {
    color: #021F59;
    text-decoration: underline;
}

.breadcrumbs .separator {
    margin: 0 10px;
    color: #CBD5E1;
}

.breadcrumbs .current {
    font-weight: 600;
    color: #0F172A;
}

.page-title-ltra {
    font-family: 'Inter', serif;
    font-size: clamp(32px, 5vw, 48px);
    color: #021F59;
    margin-bottom: 12px;
    font-weight: 800;
    letter-spacing: -1px;
    line-height: 1.2;
}

.archive-desc {
    color: #8297FE;
    font-size: 16px;
    font-weight: 500;
    letter-spacing: 0.5px;
    margin: 0;
}

/* ===== CONTENEDOR PRINCIPAL ===== */
.arch-board {
    background: #FFFFFF;
    border-radius: 24px;
    padding: 32px;
    box-shadow: 
        0 12px 40px rgba(2, 31, 89, 0.08),
        0 6px 20px rgba(2, 31, 89, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.95),
        0 0 0 1px rgba(255, 255, 255, 0.6);
    border: 1.5px solid rgba(130, 151, 254, 0.15);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(12px);
}

/* ===== LISTA COMUNICADOS ===== */
.comm-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: clamp(20px, 3vw, 28px);
    position: relative;
    z-index: 1;
}

/* ===== ITEM COMUNICADO MEJORADO ===== */
.comm-item {
    position: relative;
    display: grid;
    grid-template-columns: 220px 1fr auto;
    gap: 28px;
    align-items: center;
    
    background: linear-gradient(135deg, 
        #FFFFFF 0%,
        #FAFBFF 45%,
        #F4F7FF 100%
    );
    border: 1.5px solid rgba(130, 151, 254, 0.15);
    border-radius: 24px;
    padding: 28px 32px;
    
    box-shadow: 
        0 12px 40px rgba(2, 31, 89, 0.08),
        0 6px 20px rgba(2, 31, 89, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.95),
        0 0 0 1px rgba(255, 255, 255, 0.6);
    
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
    overflow: hidden;
    min-height: 180px;
    backdrop-filter: blur(12px);
}

/* Acento lateral izquierdo MEJORADO */
.comm-item::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 5px;
    background: linear-gradient(180deg, 
        #B2FFFF 0%,
        #8297FE 25%,
        #000C97 50%,
        #021F59 75%,
        #000C97 100%
    );
    opacity: 0.4;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
    border-radius: 2px 0 0 2px;
    box-shadow: 0 0 20px rgba(178, 255, 255, 0.3);
}

/* Efecto de brillo premium */
.comm-item::after {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg,
        transparent,
        rgba(178, 255, 255, 0.15),
        rgba(130, 151, 254, 0.08),
        transparent
    );
    transform: rotate(45deg);
    transition: all 0.8s cubic-bezier(0.23, 1, 0.320, 1);
    opacity: 0;
}

.comm-item:hover {
    transform: translateY(-6px);
    border-color: rgba(130, 151, 254, 0.4);
    
    box-shadow: 
        0 24px 60px rgba(2, 31, 89, 0.15),
        0 12px 30px rgba(130, 151, 254, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.98),
        0 0 0 2px rgba(178, 255, 255, 0.2);
    
    background: linear-gradient(135deg, 
        #FFFFFF 0%,
        #FCFDFF 40%,
        #F8FAFF 100%
    );
}

.comm-item:hover::before {
    opacity: 1;
    width: 6px;
    box-shadow: 0 0 30px rgba(178, 255, 255, 0.5);
}

.comm-item:hover::after {
    opacity: 1;
    transform: rotate(45deg) translate(30%, 30%);
}

/* ===== MINIATURA PREMIUM ===== */
.comm-thumb {
    width: 100%;
    height: 160px;
    overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, 
        #E8EDFF 0%,
        #DDE8FF 100%
    );
    flex-shrink: 0;
    position: relative;
    border: 1.5px solid rgba(130, 151, 254, 0.2);
    box-shadow: 
        0 12px 32px rgba(2, 31, 89, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
}

.comm-thumb::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        rgba(0, 12, 151, 0.04),
        rgba(130, 151, 254, 0.02),
        rgba(178, 255, 255, 0.01)
    );
    z-index: 1;
    transition: all 0.5s ease;
    border-radius: inherit;
}

.comm-thumb::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        transparent,
        rgba(255, 255, 255, 0.5),
        transparent
    );
    opacity: 0;
    transition: opacity 0.5s ease;
    border-radius: inherit;
}

.comm-item:hover .comm-thumb {
    transform: scale(1.05);
    box-shadow: 
        0 16px 48px rgba(2, 31, 89, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.95);
    border-color: rgba(130, 151, 254, 0.5);
}

.comm-item:hover .comm-thumb::before {
    background: linear-gradient(135deg,
        rgba(0, 12, 151, 0.08),
        rgba(130, 151, 254, 0.04),
        rgba(178, 255, 255, 0.02)
    );
}

.comm-item:hover .comm-thumb::after {
    opacity: 1;
}

.comm-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: all 0.7s cubic-bezier(0.23, 1, 0.320, 1);
    filter: saturate(1.08) contrast(1.08) brightness(1.02);
}

.comm-item:hover .comm-thumb img {
    transform: scale(1.12);
    filter: saturate(1.15) contrast(1.12) brightness(1.08);
}

.comm-item.noimg {
    grid-template-columns: 1fr auto;
    padding-left: 36px;
}

/* ===== CUERPO COMUNICADO ===== */
.comm-body {
    min-width: 0;
    display: grid;
    gap: 14px;
    align-content: center;
    position: relative;
    z-index: 2;
}

/* Meta información */
.comm-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 6px;
    flex-wrap: wrap;
}

.comm-date {
    font-size: 12px;
    font-weight: 700;
    color: #000C97;
    background: linear-gradient(135deg, 
        rgba(178, 255, 255, 0.15),
        rgba(130, 151, 254, 0.1)
    );
    padding: 6px 14px;
    border-radius: 14px;
    border: 1px solid rgba(130, 151, 254, 0.3);
    backdrop-filter: blur(8px);
    letter-spacing: 0.3px;
    box-shadow: 0 2px 8px rgba(2, 31, 89, 0.06);
    transition: all 0.3s ease;
}

.comm-date:hover {
    background: linear-gradient(135deg, 
        rgba(178, 255, 255, 0.25),
        rgba(130, 151, 254, 0.15)
    );
    border-color: rgba(130, 151, 254, 0.5);
}

.comm-category {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #021F59;
    background: linear-gradient(135deg, #B2FFFF 0%, #8297FE 100%);
    padding: 6px 14px;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: 
        0 4px 12px rgba(2, 31, 89, 0.12),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    transition: all 0.3s ease;
}

.comm-category:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 6px 16px rgba(2, 31, 89, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
}

.comm-title {
    margin: 0;
    font-size: clamp(19px, 2.4vw, 24px);
    font-weight: 800;
    line-height: 1.35;
    color: #021F59;
    letter-spacing: -0.03em;
    font-family: 'Inter', system-ui, sans-serif;
    
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    overflow: hidden;
    max-height: calc(1.35em * 2);
    transition: all 0.3s ease;
}

.comm-title a {
    color: inherit;
    text-decoration: none;
    background: linear-gradient(135deg, #021F59 0%, #000C97 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
    position: relative;
}

.comm-title a:hover {
    background: linear-gradient(135deg, #000C97 0%, #8297FE 50%, #B2FFFF 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 2px 8px rgba(130, 151, 254, 0.2);
}

.comm-excerpt {
    margin: 0;
    font-size: 15px;
    line-height: 1.65;
    color: #475569;
    font-weight: 500;
    letter-spacing: 0.2px;
    
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    overflow: hidden;
    max-height: calc(1.65em * 2);
    transition: color 0.3s ease;
}

.comm-item:hover .comm-excerpt {
    color: #334155;
}

/* ===== ACCIONES PREMIUM ===== */
.comm-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    justify-self: end;
    position: relative;
    z-index: 2;
}

.comm-actions .hub-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 13px 28px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    color: #FFFFFF;
    background: linear-gradient(135deg, 
        #000C97 0%,
        #021F59 50%,
        #000C97 100%
    );
    border: 1.5px solid rgba(130, 151, 254, 0.4);
    border-radius: 14px;
    box-shadow: 
        0 8px 24px rgba(2, 31, 89, .18),
        0 4px 12px rgba(2, 31, 89, .12),
        inset 0 1px 0 rgba(255, 255, 255, 0.25);
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    font-family: 'Inter', system-ui, sans-serif;
    letter-spacing: 0.3px;
}

.comm-actions .hub-btn::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
        transparent,
        rgba(255, 255, 255, 0.25),
        transparent
    );
    transition: left 0.7s cubic-bezier(0.23, 1, 0.320, 1);
}

.comm-actions .hub-btn span {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.comm-actions .hub-btn:hover {
    transform: translateY(-3px);
    box-shadow: 
        0 16px 40px rgba(2, 31, 89, .28),
        0 8px 24px rgba(130, 151, 254, .15),
        inset 0 1px 0 rgba(255, 255, 255, 0.35);
    border-color: rgba(178, 255, 255, 0.6);
    background: linear-gradient(135deg, 
        #021F59 0%,
        #000C97 50%,
        #021F59 100%
    );
}

.comm-actions .hub-btn:hover::before {
    left: 100%;
}

.comm-actions .hub-btn:active {
    transform: translateY(-1px);
    box-shadow: 
        0 6px 16px rgba(2, 31, 89, .2),
        inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* ===== PAGINACIÓN ===== */
.pager {
    margin: 32px 0 0;
    padding-top: 24px;
    border-top: 1px solid rgba(2, 31, 89, 0.1);
}

.pager-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.pager-prev a,
.pager-next a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 10px;
    background: #FFFFFF;
    color: #475569;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    border: 1px solid rgba(2, 31, 89, 0.15);
    box-shadow: 0 2px 8px rgba(2, 31, 89, 0.06);
    transition: all 0.2s ease;
    font-family: "Inter", system-ui, sans-serif;
}

.pager-prev a:hover,
.pager-next a:hover {
    border-color: #8297FE;
    color: #000C97;
    box-shadow: 0 4px 12px rgba(2, 31, 89, 0.1);
    transform: translateY(-1px);
}

/* ===== SIN RESULTADOS ===== */
.no-results {
    text-align: center;
    padding: 60px 20px;
    color: #64748B;
}

.no-results h2 {
    font-size: 24px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 16px;
    font-family: "Inter", "Segoe UI", system-ui, sans-serif;
}

.no-results p {
    font-size: 16px;
    line-height: 1.6;
    margin: 0 0 24px;
    font-weight: 500;
}

.search-again {
    max-width: 400px;
    margin: 0 auto;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .hub-layout {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .hub-sidebar {
        position: static;
        order: 2;
    }
    
    .hub-main {
        order: 1;
    }
    
    .comm-item {
        grid-template-columns: 180px 1fr auto;
        gap: 22px;
        padding: 24px 28px;
        min-height: 160px;
    }
    
    .comm-thumb {
        height: 140px;
    }
}

@media (max-width: 768px) {
    .hub.archive-page {
        padding: 16px 0 32px;
    }
    
    .arch-board {
        padding: 24px;
    }
    
    .comm-item {
        grid-template-columns: 1fr;
        gap: 18px;
        text-align: center;
        padding: 24px;
    }
    
    .comm-thumb {
        height: 160px;
        justify-self: center;
        max-width: 280px;
        width: 100%;
    }
    
    .comm-meta {
        justify-content: center;
    }
    
    .comm-actions {
        justify-self: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .comm-item.noimg {
        grid-template-columns: 1fr;
        padding: 24px;
    }

    .page-title-ltra {
        font-size: clamp(24px, 6vw, 36px);
    }
    
    .pager-nav {
        flex-direction: column;
        gap: 12px;
    }
    
    .pager-prev a,
    .pager-next a {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .arch-board {
        padding: 20px 16px;
    }
    
    .comm-item {
        padding: 18px;
        border-radius: 18px;
        min-height: auto;
    }
    
    .comm-thumb {
        height: 140px;
    }
    
    .comm-actions .hub-btn {
        padding: 11px 22px;
        font-size: 12px;
    }

    .comm-title {
        font-size: clamp(16px, 4vw, 20px);
    }
}



/* ===== ANIMACIONES ENTRADA ===== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
        filter: blur(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
        filter: blur(0);
    }
}

.comm-item {
    animation: fadeInUp 0.7s cubic-bezier(0.23, 1, 0.320, 1) both;
}

.comm-item:nth-child(1) { animation-delay: 0.1s; }
.comm-item:nth-child(2) { animation-delay: 0.2s; }
.comm-item:nth-child(3) { animation-delay: 0.3s; }
.comm-item:nth-child(4) { animation-delay: 0.4s; }
.comm-item:nth-child(5) { animation-delay: 0.5s; }
</style>

<?php get_footer();