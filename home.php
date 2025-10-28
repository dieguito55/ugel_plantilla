<?php
/**
 * Home (blog index) — Lista posts cuando hay una Página de Entradas asignada
 * Diseño Elite Corporativo UGEL 2025
 * Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
 */

get_header(); ?>

<main class="site-main wrap" role="main">

  <!-- ========== BLOG HERO SECTION ========== -->
  <section class="blog-hero">
    <div class="hero-container">
      <div class="hero-content">
        <div class="hero-badge">
          <span class="badge-dot"></span>
          <span>Blog Institucional</span>
        </div>

        <h1 class="hero-title">
          <?php 
          $blog_title = get_the_title( get_option('page_for_posts') ) ?: 'Blog';
          echo esc_html( $blog_title ); 
          ?>
        </h1>

        <p class="hero-subtitle">
          Descubre las últimas noticias, actualizaciones y contenido importante de la UGEL
        </p>

        <?php 
        $blog_desc = get_the_content( null, false, get_option('page_for_posts') );
        if ( $blog_desc ) : 
        ?>
          <div class="hero-description">
            <?php echo wp_kses_post( $blog_desc ); ?>
          </div>
        <?php endif; ?>

        <div class="hero-stats">
          <div class="stat-card">
            <div class="stat-icon">
              <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                <path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.3-1.54c-.2-.24-.58-.27-.77-.05-.19.22-.16.59.09.77l1.96 2.36c.12.15.31.24.51.24.4 0 .77-.35.77-.77 0-.02 0-.05-.01-.07l3.91-5.05c.2-.24.16-.59-.09-.77-.23-.19-.59-.16-.77.09z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number"><?php echo esc_html( wp_count_posts()->publish ); ?></div>
              <div class="stat-label">Artículos</div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                <path fill="currentColor" d="M11.99 5C6.47 5 2 8.13 2 12s4.47 7 9.99 7C17.52 19 22 15.87 22 12s-4.48-7-10.01-7zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 4 15.5 4 14 4.67 14 5.5 14.67 7 15.5 7z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">Vivo</div>
              <div class="stat-label">Actualizado</div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                <path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9.5c0 .83-.67 1.5-1.5 1.5S11 13.33 11 12.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">Info</div>
              <div class="stat-label">Confiable</div>
            </div>
          </div>
        </div>
      </div>

      <div class="hero-decoration">
        <div class="decoration-blob blob-1"></div>
        <div class="decoration-blob blob-2"></div>
        <div class="decoration-blob blob-3"></div>
      </div>
    </div>
  </section>

  <!-- ========== FILTRO Y BÚSQUEDA (Opcional) ========== -->
  <section class="blog-filter-section">
    <div class="filter-container">
      <div class="filter-info">
        <h2 class="filter-title">Últimas Publicaciones</h2>
        <p class="filter-subtitle">Explora nuestros artículos más recientes</p>
      </div>
    </div>
  </section>

  <!-- ========== CONTENIDO ========== -->
  <?php if ( have_posts() ) : ?>

    <!-- Grid de tarjetas mejorado -->
    <section class="blog-posts-section">
      <div class="posts-container">
        <div class="hub-grid-cards">
          <?php while ( have_posts() ) : the_post(); ?>
            
            <article class="hub-card" data-anim="fade-up">
              
              <!-- Miniatura con overlay -->
              <?php if ( has_post_thumbnail() ) : ?>
                <figure class="card-thumb">
                  <a href="<?php the_permalink(); ?>" class="thumb-link">
                    <?php the_post_thumbnail( 'featured-large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
                    <span class="thumb-overlay"></span>
                    <span class="play-icon">
                      <svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true">
                        <path fill="currentColor" d="M8 5v14l11-7z"/>
                      </svg>
                    </span>
                  </a>
                </figure>
              <?php else : ?>
                <figure class="card-thumb card-thumb-empty">
                  <a href="<?php the_permalink(); ?>" class="thumb-link">
                    <div class="empty-placeholder">
                      <svg viewBox="0 0 24 24" width="52" height="52" aria-hidden="true">
                        <path fill="currentColor" opacity="0.3" d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                      </svg>
                    </div>
                  </a>
                </figure>
              <?php endif; ?>

              <!-- Badge categoría con animación -->
              <div class="card-badge">
                <span class="badge-icon">
                  <svg viewBox="0 0 24 24" width="12" height="12" aria-hidden="true">
                    <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                </span>
                <?php 
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                  echo esc_html( $categories[0]->name );
                } else {
                  echo 'Blog';
                }
                ?>
              </div>

              <!-- Contenido mejorado -->
              <div class="card-body">
                <h3 class="card-title">
                  <a href="<?php the_permalink(); ?>" class="title-link">
                    <?php the_title(); ?>
                  </a>
                </h3>

                <!-- Meta información mejorada -->
                <div class="card-meta">
                  <span class="meta-item meta-date">
                    <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">
                      <path fill="currentColor" d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h6.5M17 8.5h2.5M21 15.1l-5.1 5.1M12.5 21h6"/>
                    </svg>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                      <?php echo esc_html(get_the_date('d M Y')); ?>
                    </time>
                  </span>
                  <span class="meta-item meta-author">
                    <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">
                      <path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span><?php echo esc_html( get_the_author() ); ?></span>
                  </span>
                </div>

                <!-- Extracto mejorado -->
                <p class="card-excerpt">
                  <?php 
                  $excerpt = get_the_excerpt();
                  if ( ! $excerpt ) {
                    $excerpt = wp_trim_words( get_the_content(), 22, '...' );
                  }
                  echo esc_html( $excerpt );
                  ?>
                </p>

                <!-- Tags -->
                <?php 
                $tags = get_the_tags();
                if ( $tags ) : ?>
                  <div class="card-tags">
                    <?php foreach( $tags as $tag ) : ?>
                      <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
                        #<?php echo esc_html( $tag->name ); ?>
                      </a>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Botón de acción mejorado -->
              <div class="card-actions">
                <a class="card-btn" href="<?php the_permalink(); ?>" aria-label="Leer: <?php echo esc_attr( get_the_title() ); ?>">
                  <span class="btn-text">Leer más</span>
                  <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                    <path fill="currentColor" d="M5 12h11.17l-4.58-4.59L13 6l7 7-7 7-1.41-1.41L16.17 13H5z"/>
                  </svg>
                </a>
              </div>
            </article>

          <?php endwhile; ?>
        </div>
      </div>
    </section>

    <!-- ========== PAGINACIÓN MEJORADA ========== -->
    <nav class="pagination-nav" aria-label="Navegación de páginas">
      <div class="pagination-wrapper">
        <div class="nav-prev">
          <?php previous_posts_link( '<span class="nav-btn nav-btn-prev"><span class="nav-icon">←</span> <span class="nav-text">Artículos anteriores</span></span>' ); ?>
        </div>
        <div class="nav-center">
          <span class="nav-pages" aria-live="polite">
            Página <?php echo max(1, get_query_var('paged', 1)); ?> de <?php echo esc_html( $GLOBALS['wp_query']->max_num_pages ); ?>
          </span>
        </div>
        <div class="nav-next">
          <?php next_posts_link( '<span class="nav-btn nav-btn-next"><span class="nav-text">Siguientes artículos</span> <span class="nav-icon">→</span></span>' ); ?>
        </div>
      </div>
    </nav>

  <?php else : ?>

    <!-- ========== SIN POSTS ========== -->
    <section class="no-posts-section">
      <div class="no-posts">
        <div class="no-posts-icon">
          <svg viewBox="0 0 24 24" width="80" height="80" aria-hidden="true">
            <path fill="currentColor" opacity="0.2" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
        </div>
        <h2 class="no-posts-title">No hay publicaciones aún</h2>
        <p class="no-posts-text">Estamos preparando contenido interesante y relevante. Vuelve pronto para descubrir las últimas actualizaciones.</p>
        <a href="<?php echo esc_url( home_url() ); ?>" class="no-posts-btn">
          <span>Volver al inicio</span>
          <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
            <path fill="currentColor" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
          </svg>
        </a>
      </div>
    </section>

  <?php endif; ?>

</main>

<?php get_footer(); ?>

<style>
/* ================================
   HOME BLOG - ELITE CORPORATIVO UGEL
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
  --color-border: rgba(130, 151, 254, 0.12);
  --shadow-sm: 0 2px 8px rgba(2, 31, 89, 0.06);
  --shadow-md: 0 8px 24px rgba(2, 31, 89, 0.10);
  --shadow-lg: 0 16px 40px rgba(2, 31, 89, 0.15);
  --transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ========== HERO SECTION ========== */
.blog-hero {
  position: relative;
  margin: -60px -24px 60px;
  padding: 80px 24px;
  background: linear-gradient(135deg, #FFFFFF 0%, #FAFBFF 45%, #F4F7FF 100%);
  border-bottom: 2px solid var(--color-accent);
  overflow: hidden;
}

.blog-hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--color-accent), transparent);
}

.hero-container {
  position: relative;
  display: grid;
  grid-template-columns: 1fr;
  gap: 40px;
  align-items: center;
  z-index: 2;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  width: fit-content;
  padding: 8px 16px;
  background: rgba(130, 151, 254, 0.08);
  border: 1px solid var(--color-accent-2);
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  color: var(--color-primary);
  letter-spacing: 0.5px;
  text-transform: uppercase;
  animation: slideDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.badge-dot {
  width: 6px;
  height: 6px;
  background: #10b981;
  border-radius: 50%;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.hero-title {
  margin: 0;
  font-size: clamp(36px, 6.5vw, 56px);
  font-weight: 900;
  line-height: 1.1;
  color: var(--color-primary-dark);
  letter-spacing: -1px;
  animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
}

.hero-subtitle {
  margin: 0 0 16px;
  font-size: clamp(16px, 2.5vw, 20px);
  font-weight: 600;
  line-height: 1.6;
  color: #0F4A7F;
  letter-spacing: 0.3px;
  max-width: 80ch;
  animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
}

.hero-description {
  margin: 16px 0 0;
  font-size: 15px;
  line-height: 1.7;
  color: var(--color-text-light);
  max-width: 85ch;
  animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s both;
}

.hero-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 16px;
  margin-top: 24px;
  animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: var(--color-white);
  border: 1px solid var(--color-border);
  border-radius: 12px;
  transition: var(--transition);
}

.stat-card:hover {
  transform: translateY(-4px);
  border-color: var(--color-accent-2);
  box-shadow: var(--shadow-md);
}

.stat-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, var(--color-accent-2), var(--color-accent));
  border-radius: 10px;
  color: var(--color-white);
  flex-shrink: 0;
}

.stat-content {
  flex: 1;
}

.stat-number {
  font-size: 18px;
  font-weight: 800;
  color: var(--color-primary-dark);
}

.stat-label {
  font-size: 12px;
  color: var(--color-text-light);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

/* Decoración de blobs */
.decoration-blob {
  position: absolute;
  border-radius: 45% 55% 60% 40% / 55% 45% 55% 60%;
  opacity: 0.05;
  animation: blobFloat 6s ease-in-out infinite;
}

.blob-1 {
  width: 300px;
  height: 300px;
  background: var(--color-primary);
  top: -100px;
  right: -100px;
  animation-delay: 0s;
}

.blob-2 {
  width: 200px;
  height: 200px;
  background: var(--color-accent-2);
  bottom: -50px;
  left: -50px;
  animation-delay: 2s;
}

.blob-3 {
  width: 250px;
  height: 250px;
  background: var(--color-accent);
  top: 50%;
  right: -80px;
  animation-delay: 4s;
}

@keyframes blobFloat {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33% { transform: translate(30px, -30px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
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

/* ========== FILTER SECTION ========== */
.blog-filter-section {
  margin-bottom: 40px;
  animation: fadeIn 0.8s ease-out 0.3s both;
}

.filter-title {
  margin: 0 0 8px;
  font-size: 28px;
  font-weight: 800;
  color: var(--color-primary-dark);
  letter-spacing: -0.5px;
}

.filter-subtitle {
  margin: 0;
  font-size: 15px;
  color: var(--color-text-light);
  font-weight: 500;
}

/* ========== BLOG POSTS SECTION ========== */
.blog-posts-section {
  margin-bottom: 50px;
  animation: fadeIn 0.8s ease-out 0.4s both;
}

.posts-container {
  width: 100%;
}

.hub-grid-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 24px;
  animation: fadeIn 0.8s ease-out 0.5s both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* ========== CARD ========== */
.hub-card {
  position: relative;
  display: flex;
  flex-direction: column;
  background: var(--color-white);
  border: 1px solid var(--color-border);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: var(--transition);
  animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

.hub-card:nth-child(1) { animation-delay: 0.05s; }
.hub-card:nth-child(2) { animation-delay: 0.10s; }
.hub-card:nth-child(3) { animation-delay: 0.15s; }
.hub-card:nth-child(4) { animation-delay: 0.20s; }
.hub-card:nth-child(5) { animation-delay: 0.25s; }
.hub-card:nth-child(6) { animation-delay: 0.30s; }
.hub-card:nth-child(n+7) { animation-delay: 0.35s; }

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.92) translateY(20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.hub-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--color-primary), var(--color-accent-2), var(--color-accent));
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.hub-card:hover {
  transform: translateY(-8px);
  border-color: var(--color-accent-2);
  box-shadow: var(--shadow-lg);
}

.hub-card:hover::before {
  transform: scaleX(1);
}

/* ========== MINIATURA ========== */
.card-thumb {
  margin: 0;
  overflow: hidden;
  aspect-ratio: 16/9;
  background: linear-gradient(135deg, #E8EDFF 0%, #DDE8FF 100%);
  position: relative;
}

.card-thumb-empty {
  display: flex;
  align-items: center;
  justify-content: center;
}

.thumb-link {
  display: block;
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
  text-decoration: none;
}

.thumb-link img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.thumb-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg,
    rgba(0, 12, 151, 0.05),
    rgba(130, 151, 254, 0.03)
  );
  opacity: 0;
  transition: opacity 0.4s ease;
}

.play-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 56px;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 12, 151, 0.8);
  color: var(--color-white);
  border-radius: 50%;
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  backdrop-filter: blur(4px);
}

.hub-card:hover .thumb-link img {
  transform: scale(1.12);
}

.hub-card:hover .thumb-overlay {
  opacity: 1;
}

.hub-card:hover .play-icon {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
}

/* ========== BADGE ========== */
.card-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  z-index: 10;
  padding: 6px 14px;
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--color-white);
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  border: 1.2px solid rgba(255, 255, 255, 0.3);
  border-radius: 10px;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.2);
  backdrop-filter: blur(8px);
  transition: all 0.3s ease;
}

.badge-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 14px;
  height: 14px;
}

.hub-card:hover .card-badge {
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.3);
}

/* ========== CONTENIDO CARD ========== */
.card-body {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex-grow: 1;
}

.card-title {
  margin: 0;
  font-size: 17px;
  font-weight: 800;
  line-height: 1.35;
  color: var(--color-primary-dark);
  letter-spacing: -0.3px;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.title-link {
  color: inherit;
  text-decoration: none;
  transition: var(--transition);
}

.card-title a:hover {
  background: linear-gradient(135deg, var(--color-primary-dark), var(--color-accent-2));
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ========== META INFORMACIÓN ========== */
.card-meta {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  font-size: 12px;
  padding: 8px 0;
}

.meta-item {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: rgba(130, 151, 254, 0.06);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  color: var(--color-text-light);
  font-weight: 600;
  transition: var(--transition);
}

.meta-item:hover {
  background: rgba(130, 151, 254, 0.12);
  border-color: var(--color-accent-2);
  color: var(--color-primary);
}

.meta-item svg {
  color: var(--color-primary);
  opacity: 0.8;
  flex-shrink: 0;
}

/* ========== EXTRACTO ========== */
.card-excerpt {
  margin: 0;
  font-size: 13.5px;
  line-height: 1.65;
  color: var(--color-text-light);
  font-weight: 500;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* ========== TAGS ========== */
.card-tags {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 4px;
}

.tag {
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

.tag:hover {
  background: var(--color-primary);
  color: var(--color-white);
  border-color: var(--color-primary);
  transform: translateY(-2px);
}

/* ========== ACCIONES CARD ========== */
.card-actions {
  padding-top: 12px;
  border-top: 1px solid var(--color-border);
  margin-top: auto;
}

.card-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 11px 18px;
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
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

.card-btn::before {
  content: '';
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
  transition: left 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.btn-text {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  gap: 6px;
}

.card-btn svg {
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  z-index: 1;
}

.card-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 28px rgba(2, 31, 89, 0.25);
  border-color: rgba(178, 255, 255, 0.6);
  background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
}

.card-btn:hover::before {
  left: 100%;
}

.card-btn:hover svg {
  transform: translateX(4px);
}

.card-btn:active {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.15);
}

/* ========== PAGINACIÓN ========== */
.pagination-nav {
  margin-top: 50px;
  padding-top: 32px;
  border-top: 2px solid var(--color-border);
  animation: fadeIn 0.8s ease-out 0.6s both;
}

.pagination-wrapper {
  display: flex;
  gap: 20px;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.nav-prev,
.nav-next {
  flex: 1;
  min-width: 150px;
  max-width: 220px;
}

.nav-prev {
  text-align: left;
}

.nav-next {
  text-align: right;
}

.nav-center {
  flex: 0 1 auto;
}

.nav-pages {
  font-size: 13px;
  font-weight: 700;
  color: var(--color-primary);
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.nav-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  font-size: 12px;
  font-weight: 800;
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  color: var(--color-primary);
  background: linear-gradient(135deg, #FFFFFF 0%, #FAFBFF 100%);
  border: 1.2px solid var(--color-border);
  border-radius: 10px;
  box-shadow: var(--shadow-sm);
  transition: var(--transition);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.nav-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 100%;
  background: linear-gradient(135deg, var(--color-accent-2), var(--color-accent));
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
  z-index: 0;
}

.nav-icon {
  font-size: 14px;
  opacity: 0.8;
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
}

.nav-text {
  position: relative;
  z-index: 1;
}

.nav-btn:hover {
  transform: translateY(-3px);
  border-color: var(--color-accent-2);
  color: var(--color-white);
  box-shadow: var(--shadow-lg);
}

.nav-btn:hover::before {
  transform: scaleX(1);
}

.nav-btn:hover .nav-icon {
  opacity: 1;
  transform: translateX(2px);
}

.nav-btn-prev:hover .nav-icon {
  transform: translateX(-2px);
}

/* ========== SIN POSTS ========== */
.no-posts-section {
  margin-top: 40px;
  animation: fadeIn 0.8s ease-out both;
}

.no-posts {
  text-align: center;
  padding: 80px 30px;
  background: linear-gradient(135deg, #FFFFFF 0%, #FAFBFF 45%, #F4F7FF 100%);
  border: 2px dashed var(--color-border);
  border-radius: 16px;
  position: relative;
  overflow: hidden;
}

.no-posts::before {
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

.no-posts-icon {
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

.no-posts-title {
  margin: 0 0 12px;
  font-size: clamp(24px, 5vw, 32px);
  font-weight: 900;
  color: var(--color-primary-dark);
  letter-spacing: -0.5px;
  position: relative;
  z-index: 1;
}

.no-posts-text {
  margin: 0 0 28px;
  font-size: 15px;
  color: var(--color-text-light);
  line-height: 1.7;
  max-width: 70ch;
  margin-left: auto;
  margin-right: auto;
  position: relative;
  z-index: 1;
}

.no-posts-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 12px 28px;
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  color: var(--color-white);
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  border: 1.2px solid rgba(130, 151, 254, 0.4);
  border-radius: 10px;
  text-decoration: none;
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.15);
  transition: var(--transition);
  position: relative;
  z-index: 1;
  overflow: hidden;
  cursor: pointer;
}

.no-posts-btn::before {
  content: '';
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
  transition: left 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.no-posts-btn svg {
  transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  z-index: 1;
}

.no-posts-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 28px rgba(2, 31, 89, 0.25);
  border-color: rgba(178, 255, 255, 0.6);
  background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
}

.no-posts-btn:hover::before {
  left: 100%;
}

.no-posts-btn:hover svg {
  transform: translateX(3px);
}

/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  .blog-hero {
    padding: 60px 20px;
    margin: -60px -20px 50px;
  }

  .hero-title {
    font-size: clamp(32px, 5vw, 44px);
  }

  .hero-stats {
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
  }

  .hub-grid-cards {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
  }

  .card-title {
    font-size: 16px;
  }

  .card-excerpt {
    font-size: 13px;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 640px) {
  .site-main {
    padding-left: 12px;
    padding-right: 12px;
  }

  .blog-hero {
    padding: 40px 16px;
    margin: -60px -12px 40px;
    border-radius: 0;
  }

  .hero-badge {
    font-size: 11px;
    padding: 6px 12px;
  }

  .hero-title {
    font-size: clamp(26px, 6vw, 36px);
    line-height: 1.2;
  }

  .hero-subtitle {
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 12px;
  }

  .hero-description {
    font-size: 14px;
    margin: 12px 0 0;
  }

  .hero-stats {
    grid-template-columns: 1fr;
    gap: 10px;
    margin-top: 16px;
  }

  .stat-card {
    padding: 12px;
  }

  .stat-icon {
    width: 40px;
    height: 40px;
  }

  .stat-number {
    font-size: 16px;
  }

  .stat-label {
    font-size: 11px;
  }

  .filter-title {
    font-size: 22px;
    margin-bottom: 6px;
  }

  .filter-subtitle {
    font-size: 14px;
  }

  .hub-grid-cards {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .card-thumb {
    aspect-ratio: 16/10;
  }

  .card-badge {
    font-size: 10px;
    padding: 5px 10px;
    top: 10px;
    left: 10px;
  }

  .card-body {
    padding: 16px;
    gap: 10px;
  }

  .card-title {
    font-size: 16px;
    line-height: 1.3;
  }

  .card-meta {
    font-size: 11px;
    gap: 8px;
  }

  .meta-item {
    padding: 4px 8px;
    font-size: 11px;
  }

  .card-excerpt {
    font-size: 13px;
    line-height: 1.6;
  }

  .card-tags {
    gap: 6px;
    margin-top: 2px;
  }

  .tag {
    font-size: 10px;
    padding: 3px 8px;
  }

  .card-actions {
    padding-top: 10px;
  }

  .card-btn {
    padding: 10px 14px;
    font-size: 11px;
    letter-spacing: 0.3px;
  }

  .pagination-nav {
    margin-top: 30px;
    padding-top: 20px;
  }

  .pagination-wrapper {
    gap: 10px;
    flex-direction: column;
  }

  .nav-prev,
  .nav-next,
  .nav-btn {
    width: 100%;
    max-width: none;
  }

  .nav-center {
    width: 100%;
    text-align: center;
  }

  .nav-btn {
    padding: 11px 14px;
    font-size: 11px;
  }

  .no-posts {
    padding: 50px 20px;
    border-radius: 12px;
  }

  .no-posts-icon {
    margin-bottom: 16px;
  }

  .no-posts-icon svg {
    width: 60px;
    height: 60px;
  }

  .no-posts-title {
    font-size: 22px;
  }

  .no-posts-text {
    font-size: 13px;
    margin-bottom: 20px;
  }

  .no-posts-btn {
    padding: 10px 20px;
    font-size: 12px;
  }
}

/* ========== EXTRA SMALL ========== */
@media (max-width: 380px) {
  .blog-hero {
    padding: 30px 12px;
  }

  .hero-title {
    font-size: 22px;
  }

  .hero-subtitle {
    font-size: 13px;
  }

  .stat-card {
    flex-direction: column;
    text-align: center;
    gap: 8px;
  }

  .card-title {
    font-size: 15px;
  }

  .card-btn {
    font-size: 10px;
  }
}

/* ========== REDUCE MOTION ========== */
@media (prefers-reduced-motion: reduce) {
  * {
    animation: none !important;
    transition: none !important;
  }

  .hub-card:hover,
  .card-btn:hover,
  .nav-btn:hover,
  .stat-card:hover,
  .meta-item:hover,
  .tag:hover {
    transform: none !important;
  }
}
</style>

<script>
(function() {
  'use strict';

  // ========== OBSERVADOR LAZY LOAD IMÁGENES ==========
  if ('IntersectionObserver' in window) {
    const images = document.querySelectorAll('.card-thumb img');
    
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

  // ========== ANIMACIÓN CARDS AL SCROLL ==========
  if ('IntersectionObserver' in window) {
    const cards = document.querySelectorAll('[data-anim="fade-up"]');
    
    const cardObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          cardObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.05,
      rootMargin: '0px 0px -80px 0px'
    });

    cards.forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      cardObserver.observe(card);
    });
  }

  // ========== EFECTO RIPPLE EN BOTONES ==========
  document.querySelectorAll('.card-btn, .nav-btn, .no-posts-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
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

  // ========== ANALYTICS EVENTOS ==========
  document.querySelectorAll('.card-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      if (typeof gtag !== 'undefined') {
        gtag('event', 'read_article', {
          'article_title': this.closest('.hub-card').querySelector('.card-title')?.textContent || 'Unknown'
        });
      }
    });
  });
})();
</script>