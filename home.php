<?php
/**
 * Home (blog index) — Lista posts cuando hay una Página de Entradas asignada
 * Diseño Elite Corporativo UGEL
 * Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
 */

get_header(); ?>

<main class="site-main wrap" role="main">

  <!-- ========== BLOG HEADER ========== -->
  <header class="blog-header">
    <div class="blog-header-content">
      <div class="blog-header-decoration">
        <div class="decoration-line"></div>
        <div class="decoration-dots">
          <span class="dot dot-1"></span>
          <span class="dot dot-2"></span>
          <span class="dot dot-3"></span>
        </div>
      </div>

      <h1 class="blog-title">
        <?php 
        $blog_title = get_the_title( get_option('page_for_posts') ) ?: 'Blog';
        echo esc_html( $blog_title ); 
        ?>
      </h1>

      <p class="blog-subtitle">
        Descubre las últimas noticias, actualizaciones y contenido importante de la UGEL
      </p>

      <?php 
      $blog_desc = get_the_content( null, false, get_option('page_for_posts') );
      if ( $blog_desc ) : 
      ?>
        <div class="blog-description">
          <?php echo wp_kses_post( $blog_desc ); ?>
        </div>
      <?php endif; ?>

      <div class="blog-stats">
        <span class="stat-item">
          <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
          </svg>
          <span><?php echo esc_html( wp_count_posts()->publish ); ?> artículos</span>
        </span>
        <span class="stat-item">
          <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
            <path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5-3.5H6c.69 2.04 2.67 3.5 5 3.5z"/>
          </svg>
          <span>Actualizados regularmente</span>
        </span>
      </div>
    </div>
  </header>

  <!-- ========== CONTENIDO ========== -->
  <?php if ( have_posts() ) : ?>

    <!-- Grid de tarjetas -->
    <div class="hub-grid-cards">
      <?php while ( have_posts() ) : the_post(); 
        $post_count = get_query_var( 'paged' ) > 1 ? 
          ( ( get_query_var( 'paged' ) - 1 ) * get_query_var( 'posts_per_page' ) ) + get_the_ID() : 
          get_the_ID();
      ?>
        
        <article class="hub-card" data-anim="fade-up">
          
          <!-- Miniatura -->
          <?php if ( has_post_thumbnail() ) : ?>
            <figure class="card-thumb">
              <a href="<?php the_permalink(); ?>" class="thumb-link">
                <?php the_post_thumbnail( 'featured-large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
                <span class="thumb-overlay"></span>
              </a>
            </figure>
          <?php else : ?>
            <figure class="card-thumb card-thumb-empty">
              <a href="<?php the_permalink(); ?>" class="thumb-link">
                <div class="empty-placeholder">
                  <svg viewBox="0 0 24 24" width="48" height="48" aria-hidden="true">
                    <path fill="currentColor" opacity="0.4" d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                  </svg>
                </div>
              </a>
            </figure>
          <?php endif; ?>

          <!-- Badge categoría -->
          <?php
          $categories = get_the_category();
          $primary_cat = !empty($categories) ? $categories[0] : null;
          $primary_label = $primary_cat ? $primary_cat->name : 'Blog';
          $primary_link  = $primary_cat ? get_category_link($primary_cat) : '';
          ?>
          <div class="card-badge">
            <?php if ($primary_link): ?>
              <a href="<?php echo esc_url( $primary_link ); ?>" class="card-badge__link"><?php echo esc_html( $primary_label ); ?></a>
            <?php else: ?>
              <span><?php echo esc_html( $primary_label ); ?></span>
            <?php endif; ?>
          </div>

          <!-- Contenido -->
          <div class="card-body">
            <h3 class="card-title">
              <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </h3>

            <div class="card-meta">
              <time class="card-pill" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
                <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M16 2H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 15H8v-2h6v2zm0-4H8v-2h6v2zm0-4H8V7h6v2z"/></svg>
                <span><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
              </time>
              <span class="card-pill card-pill--author">
                <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span><?php echo esc_html( get_the_author() ); ?></span>
              </span>
            </div>

            <?php if ( ! empty( $categories ) ) : ?>
            <div class="card-tags" role="list">
              <?php foreach ( array_slice( $categories, 0, 3 ) as $cat ) : ?>
                <a class="card-tag" role="listitem" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Extracto -->
            <p class="card-excerpt">
              <?php 
              $excerpt = get_the_excerpt();
              if ( ! $excerpt ) {
                $excerpt = wp_trim_words( get_the_content(), 20, '...' );
              }
              echo esc_html( $excerpt );
              ?>
            </p>

            <!-- Botón -->
            <div class="card-actions">
              <a class="card-btn" href="<?php the_permalink(); ?>">
                <span>Leer artículo</span>
                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                  <path fill="currentColor" d="M5 12h11.17l-4.58-4.59L13 6l7 7-7 7-1.41-1.41L16.17 13H5z"/>
                </svg>
              </a>
            </div>
          </div>
        </article>

      <?php endwhile; ?>
    </div>

    <!-- ========== PAGINACIÓN ========== -->
    <nav class="pagination-nav" aria-label="Navegación de páginas">
      <div class="pagination-wrapper">
        <div class="nav-prev">
          <?php previous_posts_link( '<span class="nav-btn nav-btn-prev"><span class="nav-arrow">←</span> <span class="nav-text">Anteriores</span></span>' ); ?>
        </div>
        <div class="nav-next">
          <?php next_posts_link( '<span class="nav-btn nav-btn-next"><span class="nav-text">Siguientes</span> <span class="nav-arrow">→</span></span>' ); ?>
        </div>
      </div>
    </nav>

  <?php else : ?>

    <!-- ========== SIN POSTS ========== -->
    <div class="no-posts">
      <div class="no-posts-icon">
        <svg viewBox="0 0 24 24" width="64" height="64" aria-hidden="true">
          <path fill="currentColor" opacity="0.2" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
        </svg>
      </div>
      <h2 class="no-posts-title">No hay publicaciones aún</h2>
      <p class="no-posts-text">Estamos preparando contenido interesante. Vuelve pronto.</p>
    </div>

  <?php endif; ?>

</main>

<?php get_footer(); ?>

<style>
/* ================================
   HOME BLOG - ELITE CORPORATIVO UGEL
   Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
   ================================ */

/* ========== BLOG HEADER ========== */
.blog-header {
  text-align: center;
  margin-bottom: 40px;
  padding: 48px 24px;
  
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 45%,
    #F4F7FF 100%
  );
  
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: 18px;
  
  box-shadow: 
    0 10px 32px rgba(2, 31, 89, 0.07),
    0 4px 16px rgba(2, 31, 89, 0.03),
    inset 0 1px 0 rgba(255, 255, 255, 0.95);
  
  position: relative;
  overflow: hidden;
}

.blog-header::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5px;
  background: linear-gradient(90deg, #B2FFFF, #8297FE, #000C97, #8297FE, #B2FFFF);
  opacity: 0.8;
}

.blog-header-content {
  position: relative;
  z-index: 2;
}

.blog-header-decoration {
  margin-bottom: 16px;
}

.decoration-line {
  width: 70px;
  height: 3px;
  background: linear-gradient(90deg, #000C97, #8297FE, #B2FFFF);
  margin: 0 auto 12px;
  border-radius: 999px;
  box-shadow: 0 2px 8px rgba(0, 12, 151, 0.15);
}

.decoration-dots {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.dot-1 { background: #B2FFFF; }
.dot-2 { background: #8297FE; }
.dot-3 { background: #000C97; }

.blog-header:hover .dot {
  transform: scale(1.2);
}

.blog-title {
  margin: 0 0 8px;
  font-size: clamp(32px, 6vw, 48px);
  font-weight: 900;
  line-height: 1.15;
  color: #021F59;
  letter-spacing: -0.02em;
  word-break: break-word;
}

.blog-subtitle {
  margin: 0 0 16px;
  font-size: 16px;
  line-height: 1.60;
  color: #0F4A7F;
  font-weight: 600;
  letter-spacing: 0.3px;
  max-width: 70ch;
  margin-left: auto;
  margin-right: auto;
}

.blog-description {
  margin: 16px 0 20px;
  font-size: 15px;
  line-height: 1.70;
  color: #475569;
  max-width: 75ch;
  margin-left: auto;
  margin-right: auto;
}

/* ========== BLOG STATS ========== */
.blog-stats {
  display: flex;
  justify-content: center;
  gap: 24px;
  flex-wrap: wrap;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid rgba(130, 151, 254, 0.12);
}

.stat-item {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 700;
  color: #021F59;
  letter-spacing: 0.3px;
  padding: 6px 12px;
  background: rgba(255, 255, 255, 0.5);
  border: 1px solid rgba(130, 151, 254, 0.15);
  border-radius: 10px;
  transition: all 0.25s ease;
}

.stat-item:hover {
  background: rgba(255, 255, 255, 0.8);
  border-color: rgba(130, 151, 254, 0.30);
}

.stat-item svg {
  color: #000C97;
  opacity: 0.85;
  flex-shrink: 0;
}

/* ========== GRID DE TARJETAS ========== */
.hub-grid-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
  animation: fadeIn 0.6s ease both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ========== CARD ========== */
.hub-card {
  position: relative;
  display: grid;
  grid-template-rows: auto auto 1fr auto;
  gap: 0;
  
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 45%,
    #F4F7FF 100%
  );
  
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: 14px;
  overflow: hidden;
  
  box-shadow: 
    0 8px 24px rgba(2, 31, 89, 0.06),
    0 2px 8px rgba(2, 31, 89, 0.02),
    inset 0 1px 0 rgba(255, 255, 255, 0.95);
  
  transition: all 0.45s cubic-bezier(0.23, 1, 0.320, 1);
  animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.320, 1) both;
}

.hub-card:nth-child(1) { animation-delay: 0.05s; }
.hub-card:nth-child(2) { animation-delay: 0.10s; }
.hub-card:nth-child(3) { animation-delay: 0.15s; }
.hub-card:nth-child(4) { animation-delay: 0.20s; }
.hub-card:nth-child(5) { animation-delay: 0.25s; }
.hub-card:nth-child(6) { animation-delay: 0.30s; }

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
    filter: blur(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
    filter: blur(0);
  }
}

.hub-card:hover {
  transform: translateY(-6px);
  box-shadow: 
    0 16px 40px rgba(2, 31, 89, 0.12),
    0 6px 20px rgba(130, 151, 254, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.98);
  border-color: rgba(130, 151, 254, 0.25);
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FCFDFF 40%,
    #F8FAFF 100%
  );
}

/* ========== MINIATURA ========== */
.card-thumb {
  margin: 0;
  overflow: hidden;
  aspect-ratio: 16/9;
  background: linear-gradient(135deg, #E8EDFF, #DDE8FF);
  border-radius: 0;
  position: relative;
}

.card-thumb-empty {
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.04),
    rgba(130, 151, 254, 0.03)
  );
}

.card-thumb-empty svg {
  color: rgba(0, 12, 151, 0.15);
  opacity: 0.6;
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
  transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
  filter: saturate(1.05) contrast(1.05) brightness(1.01);
}

.thumb-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg,
    rgba(0, 12, 151, 0.08),
    rgba(130, 151, 254, 0.04),
    rgba(178, 255, 255, 0.02)
  );
  opacity: 0;
  transition: opacity 0.45s ease;
}

.hub-card:hover .thumb-link img {
  transform: scale(1.10);
  filter: saturate(1.12) contrast(1.10) brightness(1.05);
}

.hub-card:hover .thumb-overlay {
  opacity: 1;
}

/* ========== BADGE ========== */
.card-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  z-index: 3;
  display: inline-flex;
  align-items: center;
  padding: 6px 14px;
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #FFFFFF;
  background: linear-gradient(135deg, #000C97, #021F59);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  box-shadow:
    0 4px 12px rgba(2, 31, 89, 0.20),
    inset 0 1px 0 rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(8px);
  transition: all 0.3s ease;
}

.card-badge__link,
.card-badge span {
  color: inherit;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}

.card-badge__link:hover,
.card-badge__link:focus-visible {
  text-decoration: none;
  outline: none;
}

.hub-card:hover .card-badge {
  transform: translateY(-2px);
  box-shadow:
    0 6px 16px rgba(2, 31, 89, 0.25),
    inset 0 1px 0 rgba(255, 255, 255, 0.35);
}

/* ========== CONTENIDO CARD ========== */
.card-body {
  padding: 18px;
  display: flex;
  flex-direction: column;
  gap: 14px;
  align-content: flex-start;
}

.card-title {
  margin: 0;
  font-size: 16px;
  font-weight: 800;
  line-height: 1.30;
  color: #021F59;
  letter-spacing: -0.01em;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 5;
  line-clamp: 5;
  overflow: hidden;
  max-height: calc(1.30em * 5);
}

.card-title a {
  color: inherit;
  text-decoration: none;
  transition: all 0.3s ease;
}

.card-title a:hover {
  background: linear-gradient(135deg, #021F59, #000C97, #8297FE);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ========== META INFORMACIÓN ========== */
.card-meta {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  align-items: center;
}

.card-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 999px;
  background: rgba(178, 255, 255, 0.16);
  border: 1px solid rgba(130, 151, 254, 0.2);
  font-size: 12px;
  font-weight: 700;
  color: #021F59;
  letter-spacing: 0.2px;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.card-pill svg {
  opacity: 0.85;
  flex-shrink: 0;
  color: inherit;
}

.card-pill:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(2, 31, 89, 0.15);
}

.card-pill--author {
  background: rgba(130, 151, 254, 0.18);
}

.card-tags {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}

.card-tag {
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 700;
  color: #021F59;
  text-decoration: none;
  background: linear-gradient(135deg, rgba(130, 151, 254, 0.16), rgba(178, 255, 255, 0.18));
  border: 1px solid rgba(130, 151, 254, 0.28);
  transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
}

.card-tag:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.16);
  background: linear-gradient(135deg, rgba(130, 151, 254, 0.26), rgba(178, 255, 255, 0.24));
}

/* ========== EXTRACTO ========== */
.card-excerpt {
  margin: 0;
  font-size: 13px;
  line-height: 1.60;
  color: #475569;
  font-weight: 500;
  letter-spacing: 0.15px;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  overflow: hidden;
  max-height: calc(1.60em * 2);
}

/* ========== ACCIONES CARD ========== */
.card-actions {
  padding: 12px 18px 18px;
  border-top: 1px solid rgba(130, 151, 254, 0.12);
  background: linear-gradient(180deg,
    transparent 0%,
    rgba(130, 151, 254, 0.02) 100%
  );
}

.card-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 20px;
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  text-decoration: none;
  color: #FFFFFF;
  
  background: linear-gradient(135deg, 
    #000C97 0%,
    #021F59 50%,
    #000C97 100%
  );
  
  border: 1.2px solid rgba(130, 151, 254, 0.35);
  border-radius: 10px;
  
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.14),
    0 2px 8px rgba(2, 31, 89, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.card-btn::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg,
    transparent,
    rgba(255, 255, 255, 0.22),
    transparent
  );
  transition: left 0.6s cubic-bezier(0.23, 1, 0.320, 1);
}

.card-btn span {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  gap: 6px;
}

.card-btn svg {
  transition: transform 0.35s cubic-bezier(0.23, 1, 0.320, 1);
}

.card-btn:hover {
  transform: translateY(-3px);
  border-color: rgba(178, 255, 255, 0.5);
  box-shadow: 
    0 10px 28px rgba(2, 31, 89, 0.22),
    0 4px 12px rgba(130, 151, 254, 0.12),
    inset 0 1px 0 rgba(255, 255, 255, 0.3);
  background: linear-gradient(135deg, 
    #021F59 0%,
    #000C97 50%,
    #021F59 100%
  );
}

.card-btn:hover::before {
  left: 100%;
}

.card-btn:hover svg {
  transform: translateX(3px);
}

.card-btn:active {
  transform: translateY(-1px);
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.15),
    inset 0 1px 3px rgba(0, 0, 0, 0.08);
}

/* ========== PAGINACIÓN ========== */
.pagination-nav {
  margin-top: 40px;
  padding-top: 24px;
  border-top: 1px solid rgba(130, 151, 254, 0.12);
}

.pagination-wrapper {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.nav-prev,
.nav-next {
  flex: 1;
  min-width: 140px;
  max-width: 200px;
}

.nav-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 12px 20px;
  font-size: 13px;
  font-weight: 800;
  text-decoration: none;
  letter-spacing: 0.3px;
  color: #021F59;
  
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 100%
  );
  
  border: 1.2px solid rgba(130, 151, 254, 0.20);
  border-radius: 12px;
  
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: pointer;
}

.nav-arrow {
  font-size: 16px;
  opacity: 0.75;
  transition: all 0.3s ease;
}

.nav-btn:hover {
  transform: translateY(-3px);
  border-color: rgba(0, 12, 151, 0.40);
  color: #000C97;
  background: linear-gradient(135deg, 
    #FCFDFF 0%,
    #F8FAFF 100%
  );
  box-shadow: 
    0 10px 28px rgba(2, 31, 89, 0.15),
    0 4px 12px rgba(130, 151, 254, 0.10),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.nav-btn:hover .nav-arrow {
  opacity: 1;
  transform: translateX(2px);
}

.nav-btn-prev:hover .nav-arrow {
  transform: translateX(-2px);
}

/* ========== SIN POSTS ========== */
.no-posts {
  text-align: center;
  padding: 60px 24px;
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 45%,
    #F4F7FF 100%
  );
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: 16px;
  box-shadow: 
    0 10px 32px rgba(2, 31, 89, 0.07),
    0 4px 16px rgba(2, 31, 89, 0.03);
}

.no-posts-icon {
  margin-bottom: 20px;
  color: rgba(0, 12, 151, 0.15);
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}

.no-posts-title {
  margin: 0 0 12px;
  font-size: clamp(24px, 4vw, 32px);
  font-weight: 900;
  color: #021F59;
  letter-spacing: -0.01em;
}

.no-posts-text {
  margin: 0;
  font-size: 15px;
  color: #475569;
  line-height: 1.65;
  max-width: 60ch;
  margin-left: auto;
  margin-right: auto;
}



/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  .blog-header {
    padding: 36px 20px;
    margin-bottom: 32px;
  }

  .blog-title {
    font-size: clamp(28px, 5vw, 40px);
  }

  .hub-grid-cards {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
  }

  .card-body {
    padding: 16px;
  }

  .card-title {
    font-size: 15px;
  }

  .card-excerpt {
    font-size: 12.5px;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 640px) {
  .site-main {
    padding-left: 16px;
    padding-right: 16px;
  }

  .blog-header {
    padding: 24px 16px;
    margin-bottom: 24px;
    border-radius: 14px;
  }

  .blog-header-decoration {
    margin-bottom: 12px;
  }

  .decoration-line {
    width: 60px;
    height: 2px;
  }

  .dot {
    width: 6px;
    height: 6px;
  }

  .blog-title {
    font-size: clamp(22px, 6.5vw, 32px);
    line-height: 1.15;
    margin-bottom: 6px;
  }

  .blog-subtitle {
    font-size: 14px;
    line-height: 1.55;
    margin-bottom: 12px;
  }

  .blog-description {
    margin: 12px 0 16px;
    font-size: 14px;
  }

  .blog-stats {
    gap: 12px;
    margin-top: 14px;
    padding-top: 14px;
  }

  .stat-item {
    font-size: 12px;
    padding: 5px 10px;
  }

  .stat-item svg {
    width: 14px;
    height: 14px;
  }

  .hub-grid-cards {
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 24px;
  }

  .hub-card {
    border-radius: 12px;
  }

  .card-thumb {
    aspect-ratio: 16/10;
  }

  .card-badge {
    font-size: 10px;
    padding: 5px 10px;
  }

  .card-body {
    padding: 14px;
    gap: 8px;
  }

  .card-title {
    font-size: 15px;
    line-height: 1.28;
  }

  .card-meta {
    font-size: 11px;
    gap: 8px;
    padding: 4px 0;
  }

  .meta-date,
  .meta-author {
    padding: 3px 8px;
    font-size: 11px;
  }

  .meta-date svg,
  .meta-author svg {
    width: 12px;
    height: 12px;
  }

  .card-excerpt {
    font-size: 12px;
    line-height: 1.55;
  }

  .card-actions {
    padding: 10px 14px 14px;
  }

  .card-btn {
    width: 100%;
    padding: 10px 16px;
    font-size: 11px;
  }

  .card-btn svg {
    width: 14px;
    height: 14px;
  }

  .pagination-nav {
    margin-top: 24px;
    padding-top: 16px;
  }

  .pagination-wrapper {
    gap: 10px;
    flex-direction: column;
  }

  .nav-prev,
  .nav-next {
    max-width: none;
  }

  .nav-btn {
    padding: 11px 16px;
    font-size: 12px;
  }

  .nav-arrow {
    font-size: 14px;
  }

  .no-posts {
    padding: 40px 16px;
    border-radius: 12px;
  }

  .no-posts-title {
    font-size: clamp(20px, 5.5vw, 26px);
  }

  .no-posts-text {
    font-size: 14px;
  }
}

/* ========== REDUCE MOTION ========== */
@media (prefers-reduced-motion: reduce) {
  .hub-card,
  .card-btn,
  .nav-btn,
  .stat-item,
  .meta-date,
  .meta-author,
  .blog-header:hover .dot,
  .card-thumb,
  .card-title a,
  .no-posts-icon {
    transition: none !important;
    animation: none !important;
    transform: none !important;
  }

  .card-btn::before,
  .thumb-overlay {
    animation: none !important;
  }

  .hub-card:hover {
    transform: none !important;
  }

  .card-btn:hover,
  .nav-btn:hover {
    transform: none !important;
  }

  .card-btn:hover svg {
    transform: none !important;
  }
}
</style>

<script>
(function() {
  'use strict';

  // ========== LAZY LOAD IMÁGENES ==========
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
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    cards.forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      cardObserver.observe(card);
    });
  }

  // ========== SMOOTH SCROLL ANCHOR ==========
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href !== '#' && document.querySelector(href)) {
        e.preventDefault();
        document.querySelector(href).scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
})();
</script>