<?php
/**
 * Index (fallback) — archivos, búsqueda, etc. (NO la portada)
 * Diseño corporativo premium basado en comunicados UGEL 2025
 * Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
 */
get_header(); ?>

<div class="hub archive-page">
  <div class="wrap">
    <div class="hub-layout">
      
      <!-- Sidebar con accesos directos (CSS propio) -->
      <?php get_template_part('template-parts/accesos-directos'); ?>

      <!-- Contenido principal -->
      <main class="hub-main" role="main">
        
        <!-- ========== HEADER ARCHIVE ========== -->
        <header class="archive-head">
          <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">
              <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                <path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
              </svg>
              <span>Inicio</span>
            </a>
            <span class="separator">/</span>
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
          
          <div class="archive-title-section">
            <h1 class="page-title-ltra">
              <?php
              if (is_search()) {
                printf(
                  '<span class="search-icon">🔍</span> Resultados para: <strong>"%s"</strong>',
                  esc_html(get_search_query())
                );
              } else {
                echo esc_html(get_the_archive_title() ?: 'Contenido');
              }
              ?>
            </h1>
            
            <?php if (is_archive() && get_the_archive_description()) : ?>
              <p class="archive-desc"><?php echo wp_kses_post(get_the_archive_description()); ?></p>
            <?php endif; ?>
          </div>
        </header>

        <!-- ========== CONTENIDO PRINCIPAL ========== -->
        <?php if (have_posts()) : ?>
          <div class="arch-board">
            
            <!-- Lista de artículos -->
            <ul class="comm-list">
              <?php while (have_posts()) : the_post(); 
                $post_title = get_the_title();
                $post_excerpt = get_the_excerpt();
                $post_thumbnail = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                $post_date = get_the_date('d M Y');
                $post_url = get_permalink();
                
                // Determinar categoría
                $categories = get_the_category();
                $category_name = !empty($categories) ? $categories[0]->name : 'General';
                $category_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '#';
              ?>
                <li class="comm-item <?php echo !$post_thumbnail ? 'noimg' : ''; ?>" itemscope itemtype="https://schema.org/Article" data-anim="fade-up">
                  
                  <!-- Miniatura mejorada -->
                  <?php if ($post_thumbnail) : ?>
                    <figure class="comm-thumb">
                      <a href="<?php echo esc_url($post_url); ?>" class="thumb-link" aria-label="Ver artículo: <?php echo esc_attr($post_title); ?>">
                        <img 
                          src="<?php echo esc_url($post_thumbnail); ?>" 
                          alt="<?php echo esc_attr($post_title); ?>" 
                          loading="lazy"
                          decoding="async"
                        />
                        <span class="thumb-overlay"></span>
                        <span class="play-badge">
                          <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                            <path fill="currentColor" d="M8 5v14l11-7z"/>
                          </svg>
                        </span>
                      </a>
                    </figure>
                  <?php endif; ?>

                  <!-- Contenido del artículo -->
                  <div class="comm-body">
                    
                    <!-- Meta información mejorada -->
                    <div class="comm-meta">
                      <time class="comm-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                        <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">
                          <path fill="currentColor" d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v12z"/>
                        </svg>
                        <?php echo esc_html($post_date); ?>
                      </time>
                      
                      <a href="<?php echo esc_url($category_link); ?>" class="comm-category" title="Ver categoría: <?php echo esc_attr($category_name); ?>">
                        <svg viewBox="0 0 24 24" width="12" height="12" aria-hidden="true">
                          <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <?php echo esc_html($category_name); ?>
                      </a>
                    </div>
                    
                    <!-- Título -->
                    <h2 class="comm-title" itemprop="headline">
                      <a href="<?php echo esc_url($post_url); ?>" class="title-link">
                        <?php echo esc_html($post_title); ?>
                      </a>
                    </h2>
                    
                    <!-- Extracto -->
                    <?php if ($post_excerpt) : ?>
                      <p class="comm-excerpt" itemprop="description">
                        <?php echo esc_html(wp_trim_words($post_excerpt, 28)); ?>
                      </p>
                    <?php endif; ?>

                    <!-- Tags (si existen) -->
                    <?php 
                    $tags = get_the_tags();
                    if ($tags) : ?>
                      <div class="comm-tags">
                        <?php foreach(array_slice($tags, 0, 3) as $tag) : ?>
                          <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-badge">
                            #<?php echo esc_html($tag->name); ?>
                          </a>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                  
                  <!-- Acciones -->
                  <div class="comm-actions">
                    <a class="hub-btn" href="<?php echo esc_url($post_url); ?>" aria-label="Leer: <?php echo esc_attr($post_title); ?>">
                      <span class="btn-text">Leer artículo</span>
                      <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                        <path fill="currentColor" d="M5 12h11.17l-4.58-4.59L13 6l7 7-7 7-1.41-1.41L16.17 13H5z"/>
                      </svg>
                    </a>
                  </div>
                </li>
              <?php endwhile; ?>
            </ul>

            <!-- ========== PAGINACIÓN MEJORADA ========== -->
            <nav class="pager" aria-label="Paginación de contenido">
              <?php
              $prev_link = get_previous_posts_link('<span class="nav-icon">←</span> <span>Artículos anteriores</span>');
              $next_link = get_next_posts_link('<span>Siguientes artículos</span> <span class="nav-icon">→</span>');
              $paged = max(1, get_query_var('paged', 1));
              $max_pages = $GLOBALS['wp_query']->max_num_pages;
              ?>
              <div class="pager-nav">
                <div class="pager-prev">
                  <?php if ($prev_link) : ?>
                    <div class="nav-btn-prev"><?php echo $prev_link; ?></div>
                  <?php endif; ?>
                </div>
                
                <div class="pager-info">
                  <span class="page-numbers" aria-live="polite">
                    Página <strong><?php echo esc_html($paged); ?></strong> de <strong><?php echo esc_html($max_pages); ?></strong>
                  </span>
                </div>
                
                <div class="pager-next">
                  <?php if ($next_link) : ?>
                    <div class="nav-btn-next"><?php echo $next_link; ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </nav>
          </div>

        <?php else : ?>

          <!-- ========== SIN RESULTADOS ========== -->
          <div class="arch-board">
            <div class="no-results">
              <div class="no-results-icon">
                <?php if (is_search()) : ?>
                  <svg viewBox="0 0 24 24" width="80" height="80" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2" d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h8v14z"/>
                  </svg>
                <?php else : ?>
                  <svg viewBox="0 0 24 24" width="80" height="80" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                  </svg>
                <?php endif; ?>
              </div>

              <h2 class="no-results-title">
                <?php 
                if (is_search()) {
                  echo 'No se encontraron resultados';
                } else {
                  echo 'No hay contenido disponible';
                }
                ?>
              </h2>

              <p class="no-results-text">
                <?php 
                if (is_search()) {
                  printf(
                    'No encontramos resultados para <strong>"%s"</strong>. Intenta con otros términos de búsqueda.',
                    esc_html(get_search_query())
                  );
                } else {
                  echo 'No hay contenido disponible en esta sección en este momento.';
                }
                ?>
              </p>

              <?php if (is_search()) : ?>
                <div class="no-results-search">
                  <p class="search-suggestion">Intenta con:</p>
                  <ul class="suggestions">
                    <li>Palabras más generales</li>
                    <li>Menos palabras en la búsqueda</li>
                    <li>Verificar la ortografía</li>
                  </ul>
                  
                  <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                  </div>
                </div>
              <?php else : ?>
                <a class="hub-btn hub-btn-primary" href="<?php echo esc_url(home_url('/')); ?>">
                  <span class="btn-text">Volver al inicio</span>
                  <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                    <path fill="currentColor" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                  </svg>
                </a>
              <?php endif; ?>
            </div>
          </div>

        <?php endif; ?>
      </main>
    </div>
  </div>
</div>
<?php get_footer(); ?>

<style>
/* ================================
   INDEX ARCHIVE - ELITE CORPORATIVO UGEL 2025
   Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
   ================================ */

:root {
  --color-primary: #000C97;
  --color-primary-dark: #021F59;
  --color-accent: #B2FFFF;
  --color-accent-2: #8297FE;
  --color-white: #FFFFFF;
  --color-text: #1a1a1a;
  --color-text-light: #475569;
  --color-text-lighter: #64748B;
  --color-border: rgba(130, 151, 254, 0.15);
  --shadow-sm: 0 2px 8px rgba(2, 31, 89, 0.06);
  --shadow-md: 0 8px 24px rgba(2, 31, 89, 0.10);
  --shadow-lg: 0 16px 40px rgba(2, 31, 89, 0.15);
  --transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ========== BASE ========== */
.archive-page * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.hub.archive-page {
  background: linear-gradient(135deg, #FAFBFF 0%, #F4F7FF 100%);
  font-family: 'Inter', system-ui, sans-serif;
  padding: 50px 20px;
  min-height: 100vh;
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.hub.archive-page > .wrap {
  max-width: 1280px;
  margin: 0 auto;
}

/* ========== LAYOUT GRID ========== */
.hub.archive-page .hub-layout {
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 40px;
  align-items: start;
}

.hub.archive-page .ax-aside {
  position: sticky;
  top: 120px;
  z-index: 20;
}

.hub.archive-page .hub-main {
  min-width: 0;
}

/* ========== BREADCRUMBS MEJORADOS ========== */
.archive-page .breadcrumbs {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 13px;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1.5px solid var(--color-border);
  flex-wrap: wrap;
}

.archive-page .breadcrumb-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--color-primary);
  text-decoration: none;
  font-weight: 600;
  transition: var(--transition);
  padding: 4px 8px;
  border-radius: 6px;
}

.archive-page .breadcrumb-link:hover {
  color: var(--color-primary-dark);
  background: rgba(130, 151, 254, 0.08);
}

.archive-page .breadcrumb-link svg {
  opacity: 0.8;
}

.archive-page .breadcrumbs .separator {
  color: var(--color-border);
  opacity: 0.6;
}

.archive-page .breadcrumbs .current {
  font-weight: 600;
  color: var(--color-text);
}

/* ========== HEADER ARCHIVE ========== */
.archive-page .archive-head {
  margin-bottom: 40px;
  animation: slideDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.archive-page .archive-title-section {
  text-align: left;
}

.archive-page .page-title-ltra {
  font-size: clamp(32px, 5vw, 48px);
  font-weight: 900;
  color: var(--color-primary-dark);
  margin-bottom: 12px;
  letter-spacing: -1px;
  line-height: 1.15;
  display: flex;
  align-items: center;
  gap: 12px;
}

.archive-page .search-icon {
  font-size: 1.1em;
  animation: bounce 1.5s ease-in-out infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-4px); }
}

.archive-page .page-title-ltra strong {
  background: linear-gradient(135deg, var(--color-primary), var(--color-accent-2));
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.archive-page .archive-desc {
  color: var(--color-text-light);
  font-size: 15px;
  font-weight: 500;
  line-height: 1.6;
  max-width: 80ch;
  margin: 0;
}

/* ========== CONTENEDOR BOARD ========== */
.archive-page .arch-board {
  background: var(--color-white);
  border-radius: 16px;
  padding: 32px;
  box-shadow: var(--shadow-md);
  border: 1px solid var(--color-border);
  animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.96) translateY(20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* ========== LISTA COMUNICADOS ========== */
.archive-page .comm-list {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

/* ========== ITEM COMUNICADO ========== */
.archive-page .comm-item {
  position: relative;
  display: grid;
  grid-template-columns: 220px 1fr auto;
  gap: 28px;
  align-items: start;
  background: linear-gradient(135deg, var(--color-white) 0%, #FAFBFF 100%);
  border: 1.5px solid var(--color-border);
  border-radius: 14px;
  padding: 28px 32px;
  box-shadow: var(--shadow-sm);
  transition: var(--transition);
  overflow: hidden;
  animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

.archive-page .comm-item:nth-child(1) { animation-delay: 0.05s; }
.archive-page .comm-item:nth-child(2) { animation-delay: 0.10s; }
.archive-page .comm-item:nth-child(3) { animation-delay: 0.15s; }
.archive-page .comm-item:nth-child(4) { animation-delay: 0.20s; }
.archive-page .comm-item:nth-child(n+5) { animation-delay: 0.25s; }

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.archive-page .comm-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  width: 4px;
  background: linear-gradient(180deg, var(--color-accent), var(--color-accent-2), var(--color-primary), var(--color-primary-dark));
  opacity: 0;
  transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
  border-radius: 2px 0 0 2px;
}

.archive-page .comm-item:hover {
  transform: translateY(-6px);
  border-color: var(--color-accent-2);
  box-shadow: var(--shadow-lg);
}

.archive-page .comm-item:hover::before {
  opacity: 1;
  width: 5px;
}

.archive-page .comm-item.noimg {
  grid-template-columns: 1fr auto;
  padding-left: 36px;
}

/* ========== MINIATURA PREMIUM ========== */
.archive-page .comm-thumb {
  margin: 0;
  width: 100%;
  height: 160px;
  overflow: hidden;
  border-radius: 12px;
  background: linear-gradient(135deg, #E8EDFF 0%, #DDE8FF 100%);
  flex-shrink: 0;
  position: relative;
  border: 1px solid rgba(130, 151, 254, 0.2);
  transition: var(--transition);
}

.archive-page .thumb-link {
  display: block;
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
  text-decoration: none;
}

.archive-page .comm-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
  filter: saturate(1.05) contrast(1.05);
}

.archive-page .thumb-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg,
    rgba(0, 12, 151, 0.08),
    rgba(130, 151, 254, 0.04)
  );
  opacity: 0;
  transition: opacity 0.4s ease;
}

.archive-page .play-badge {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 12, 151, 0.8);
  color: var(--color-white);
  border-radius: 50%;
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  backdrop-filter: blur(4px);
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.archive-page .comm-item:hover .comm-thumb img {
  transform: scale(1.12);
  filter: saturate(1.12) contrast(1.10);
}

.archive-page .comm-item:hover .thumb-overlay {
  opacity: 1;
}

.archive-page .comm-item:hover .play-badge {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
}

/* ========== CONTENIDO ITEM ========== */
.archive-page .comm-body {
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.archive-page .comm-meta {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.archive-page .comm-date {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 700;
  color: var(--color-primary);
  background: rgba(178, 255, 255, 0.1);
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid rgba(178, 255, 255, 0.3);
  transition: var(--transition);
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.archive-page .comm-date:hover {
  background: rgba(178, 255, 255, 0.2);
  border-color: var(--color-accent);
}

.archive-page .comm-date svg {
  opacity: 0.8;
}

.archive-page .comm-category {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--color-white);
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid rgba(130, 151, 254, 0.4);
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.12);
  transition: var(--transition);
}

.archive-page .comm-category:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.2);
  border-color: rgba(178, 255, 255, 0.5);
}

.archive-page .comm-category svg {
  opacity: 0.9;
}

.archive-page .comm-title {
  margin: 0;
  font-size: clamp(18px, 2.2vw, 24px);
  font-weight: 800;
  line-height: 1.35;
  color: var(--color-primary-dark);
  letter-spacing: -0.3px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.archive-page .comm-title a {
  color: inherit;
  text-decoration: none;
  transition: var(--transition);
}

.archive-page .comm-title a:hover {
  background: linear-gradient(135deg, var(--color-primary-dark), var(--color-accent-2));
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.archive-page .comm-excerpt {
  margin: 0;
  font-size: 14px;
  line-height: 1.6;
  color: var(--color-text-light);
  font-weight: 500;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.archive-page .comm-tags {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 4px;
}

.archive-page .tag-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  font-size: 11px;
  font-weight: 700;
  color: var(--color-primary);
  background: rgba(0, 12, 151, 0.06);
  border: 1px solid rgba(0, 12, 151, 0.15);
  border-radius: 6px;
  text-decoration: none;
  transition: var(--transition);
  text-transform: capitalize;
}

.archive-page .tag-badge:hover {
  background: var(--color-primary);
  color: var(--color-white);
  transform: translateY(-2px);
}

/* ========== ACCIONES ========== */
.archive-page .comm-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  justify-self: end;
}

.archive-page .hub-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 11px 24px;
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  text-decoration: none;
  color: var(--color-white);
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  border: 1.2px solid rgba(130, 151, 254, 0.4);
  border-radius: 10px;
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.15);
  transition: var(--transition);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.archive-page .hub-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
  transition: left 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.archive-page .btn-text {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  gap: 6px;
}

.archive-page .hub-btn svg {
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  z-index: 1;
}

.archive-page .hub-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 28px rgba(2, 31, 89, 0.25);
  border-color: rgba(178, 255, 255, 0.6);
  background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
}

.archive-page .hub-btn:hover::before {
  left: 100%;
}

.archive-page .hub-btn:hover svg {
  transform: translateX(4px);
}

.archive-page .hub-btn:active {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.15);
}

.archive-page .hub-btn-primary {
  padding: 12px 28px;
  font-size: 13px;
  min-width: 200px;
}

/* ========== PAGINACIÓN ========== */
.archive-page .pager {
  margin: 40px 0 0;
  padding-top: 32px;
  border-top: 2px solid var(--color-border);
}

.archive-page .pager-nav {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: 20px;
  align-items: center;
  justify-items: center;
}

.archive-page .pager-prev,
.archive-page .pager-next {
  width: 100%;
}

.archive-page .nav-btn-prev,
.archive-page .nav-btn-next {
  display: contents;
}

.archive-page .pager-prev a,
.archive-page .pager-next a {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  font-size: 12px;
  font-weight: 800;
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  color: var(--color-primary);
  background: linear-gradient(135deg, var(--color-white) 0%, #FAFBFF 100%);
  border: 1.2px solid var(--color-border);
  border-radius: 10px;
  box-shadow: var(--shadow-sm);
  transition: var(--transition);
  cursor: pointer;
  position: relative;
  overflow: hidden;
  white-space: nowrap;
}

.archive-page .nav-icon {
  font-size: 14px;
  opacity: 0.8;
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
}

.archive-page .pager-prev a:hover,
.archive-page .pager-next a:hover {
  transform: translateY(-3px);
  border-color: var(--color-accent-2);
  color: var(--color-white);
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  box-shadow: var(--shadow-lg);
}

.archive-page .pager-prev a:hover .nav-icon {
  opacity: 1;
  transform: translateX(-2px);
}

.archive-page .pager-next a:hover .nav-icon {
  opacity: 1;
  transform: translateX(2px);
}

.archive-page .pager-info {
  display: flex;
  align-items: center;
  justify-content: center;
}

.archive-page .page-numbers {
  font-size: 12px;
  font-weight: 700;
  color: var(--color-primary);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  padding: 8px 16px;
  background: rgba(130, 151, 254, 0.06);
  border: 1px solid var(--color-border);
  border-radius: 8px;
}

.archive-page .page-numbers strong {
  color: var(--color-primary-dark);
  font-weight: 800;
}

/* ========== SIN RESULTADOS ========== */
.archive-page .no-results {
  text-align: center;
  padding: 80px 40px;
  position: relative;
  overflow: hidden;
}

.archive-page .no-results::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 50%, rgba(178, 255, 255, 0.04) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(130, 151, 254, 0.04) 0%, transparent 50%);
  pointer-events: none;
}

.archive-page .no-results-icon {
  margin-bottom: 24px;
  color: rgba(0, 12, 151, 0.1);
  animation: floatIcon 3s ease-in-out infinite;
  position: relative;
  z-index: 1;
}

@keyframes floatIcon {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-12px); }
}

.archive-page .no-results-title {
  margin: 0 0 16px;
  font-size: clamp(24px, 5vw, 36px);
  font-weight: 900;
  color: var(--color-primary-dark);
  letter-spacing: -0.5px;
  position: relative;
  z-index: 1;
}

.archive-page .no-results-text {
  margin: 0 0 32px;
  font-size: 15px;
  color: var(--color-text-light);
  line-height: 1.7;
  max-width: 70ch;
  margin-left: auto;
  margin-right: auto;
  position: relative;
  z-index: 1;
}

.archive-page .no-results-text strong {
  color: var(--color-primary);
  font-weight: 700;
}

.archive-page .no-results-search {
  margin-top: 32px;
  position: relative;
  z-index: 1;
}

.archive-page .search-suggestion {
  font-size: 13px;
  font-weight: 700;
  color: var(--color-primary);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  margin-bottom: 12px;
}

.archive-page .suggestions {
  list-style: none;
  padding: 0;
  margin: 0 0 24px;
  display: flex;
  justify-content: center;
  gap: 12px;
  flex-wrap: wrap;
}

.archive-page .suggestions li {
  padding: 6px 14px;
  background: rgba(130, 151, 254, 0.06);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  font-size: 13px;
  color: var(--color-text-light);
  font-weight: 600;
}

.archive-page .search-form-wrapper {
  max-width: 500px;
  margin: 0 auto;
}

.archive-page .search-form-wrapper form {
  display: flex;
  gap: 8px;
}

.archive-page .search-form-wrapper input[type="search"] {
  flex: 1;
  padding: 11px 16px;
  font-size: 14px;
  border: 1.5px solid var(--color-border);
  border-radius: 10px;
  font-family: 'Inter', system-ui, sans-serif;
  transition: var(--transition);
}

.archive-page .search-form-wrapper input[type="search"]:focus {
  outline: none;
  border-color: var(--color-accent-2);
  box-shadow: 0 0 0 3px rgba(130, 151, 254, 0.1);
}

.archive-page .search-form-wrapper input[type="submit"] {
  padding: 11px 24px;
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  color: var(--color-white);
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  border: 1.2px solid rgba(130, 151, 254, 0.4);
  border-radius: 10px;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.15);
}

.archive-page .search-form-wrapper input[type="submit"]:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(2, 31, 89, 0.2);
}

/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  .hub.archive-page .hub-layout {
    grid-template-columns: 1fr;
    gap: 24px;
  }

  .hub.archive-page .ax-aside {
    position: static;
    order: 2;
  }

  .hub.archive-page .hub-main {
    order: 1;
  }

  .archive-page .comm-item {
    grid-template-columns: 180px 1fr auto;
    gap: 22px;
  }

  .archive-page .comm-thumb {
    height: 140px;
  }

  .archive-page .pager-nav {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .archive-page .pager-info {
    order: -1;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 768px) {
  .hub.archive-page {
    padding: 30px 12px;
  }

  .archive-page .arch-board {
    padding: 20px 16px;
    border-radius: 12px;
  }

  .archive-page .comm-item {
    grid-template-columns: 1fr;
    gap: 16px;
    padding: 20px;
    text-align: center;
  }

  .archive-page .comm-thumb {
    height: 160px;
    justify-self: center;
    max-width: 100%;
  }

  .archive-page .comm-body {
    text-align: left;
  }

  .archive-page .comm-meta {
    justify-content: center;
  }

  .archive-page .comm-actions {
    justify-self: center;
    width: 100%;
  }

  .archive-page .hub-btn {
    width: 100%;
  }

  .archive-page .comm-item.noimg {
    grid-template-columns: 1fr;
    padding: 20px;
  }

  .archive-page .page-title-ltra {
    font-size: clamp(24px, 5vw, 36px);
  }

  .archive-page .archive-desc {
    font-size: 14px;
  }

  .archive-page .breadcrumbs {
    font-size: 12px;
    gap: 8px;
  }

  .archive-page .no-results {
    padding: 50px 20px;
  }

  .archive-page .no-results-icon svg {
    width: 60px;
    height: 60px;
  }

  .archive-page .no-results-title {
    font-size: 24px;
  }

  .archive-page .no-results-text {
    font-size: 14px;
  }
}

/* ========== RESPONSIVE EXTRA SMALL ========== */
@media (max-width: 480px) {
  .hub.archive-page {
    padding: 20px 8px;
  }

  .archive-page .arch-board {
    padding: 16px 12px;
    border-radius: 10px;
  }

  .archive-page .comm-list {
    gap: 16px;
  }

  .archive-page .comm-item {
    padding: 16px;
    min-height: auto;
  }

  .archive-page .comm-item {
    grid-template-columns: 1fr;
  }

  .archive-page .comm-thumb {
    height: 140px;
    width: 100%;
  }

  .archive-page .comm-title {
    font-size: clamp(16px, 4vw, 20px);
  }

  .archive-page .comm-excerpt {
    font-size: 13px;
  }

  .archive-page .comm-actions .hub-btn {
    padding: 10px 16px;
    font-size: 11px;
  }

  .archive-page .page-title-ltra {
    font-size: 22px;
  }

  .archive-page .breadcrumbs {
    font-size: 11px;
  }

  .archive-page .no-results {
    padding: 40px 16px;
  }

  .archive-page .search-form-wrapper form {
    flex-direction: column;
  }

  .archive-page .search-form-wrapper input[type="submit"] {
    width: 100%;
  }
}

/* ========== REDUCE MOTION ========== */
@media (prefers-reduced-motion: reduce) {
  * {
    animation: none !important;
    transition: none !important;
  }

  .archive-page .comm-item:hover,
  .archive-page .hub-btn:hover,
  .archive-page .pager-prev a:hover,
  .archive-page .pager-next a:hover,
  .archive-page .comm-date:hover,
  .archive-page .comm-category:hover,
  .archive-page .tag-badge:hover {
    transform: none !important;
  }
}
</style>

<script>
(function() {
  'use strict';

  // ========== LAZY LOAD IMÁGENES ==========
  if ('IntersectionObserver' in window) {
    const images = document.querySelectorAll('.comm-thumb img');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          img.classList.add('loaded');
          observer.unobserve(img);
        }
      });
    }, {
      rootMargin: '50px'
    });

    images.forEach(img => imageObserver.observe(img));
  }

  // ========== ANIMACIÓN ITEMS AL SCROLL ==========
  if ('IntersectionObserver' in window) {
    const items = document.querySelectorAll('[data-anim="fade-up"]');
    
    const itemObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          itemObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.05,
      rootMargin: '0px 0px -100px 0px'
    });

    items.forEach(item => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(20px)';
      itemObserver.observe(item);
    });
  }

  // ========== EFECTO RIPPLE EN BOTONES ==========
  document.querySelectorAll('.hub-btn, .pager-prev a, .pager-next a').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (e.button !== 0) return;
      
      const rect = this.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const ripple = document.createElement('span');
      ripple.style.position = 'absolute';
      ripple.style.left = x + 'px';
      ripple.style.top = y + 'px';
      ripple.style.width = '0';
      ripple.style.height = '0';
      ripple.style.borderRadius = '50%';
      ripple.style.background = 'rgba(255,255,255,0.4)';
      ripple.style.pointerEvents = 'none';
      ripple.style.zIndex = '0';
      
      this.style.position = 'relative';
      this.appendChild(ripple);
      
      const startTime = Date.now();
      const duration = 600;
      
      const animate = () => {
        const elapsed = Date.now() - startTime;
        const progress = elapsed / duration;
        
        if (progress < 1) {
          ripple.style.width = (progress * 300) + 'px';
          ripple.style.height = (progress * 300) + 'px';
          ripple.style.opacity = 1 - progress;
          requestAnimationFrame(animate);
        } else {
          ripple.remove();
        }
      };
      
      animate();
    });
  });

  // ========== SMOOTH SCROLL ANCHOR ==========
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href !== '#' && document.querySelector(href)) {
        e.preventDefault();
        document.querySelector(href).scrollIntoView({ 
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

  // ========== SCROLL TOP AL PAGINAR ==========
  document.querySelectorAll('.pager-prev a, .pager-next a').forEach(link => {
    link.addEventListener('click', function(e) {
      const board = document.querySelector('.arch-board');
      if (board) {
        setTimeout(() => {
          board.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
      }
    });
  });
})();
</script>
