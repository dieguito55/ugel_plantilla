<?php
/**
 * Single — Post individual con aside de Accesos Directos
 * Diseño Elite Corporativo UGEL
 * Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
 */

get_header(); ?>

<section class="hub" aria-label="Contenido principal">
  <div class="wrap">
    <div class="hub-layout">

      <?php get_template_part('template-parts/accesos-directos'); ?>

      <div class="hub-main">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
          
          // ========== DATOS AUXILIARES ==========
          $pt    = get_post_type();
          $pto   = $pt ? get_post_type_object($pt) : null;
          $label = $pto ? $pto->labels->singular_name : get_post_type();
          $words = str_word_count( wp_strip_all_tags( get_the_content() ) );
          $mins  = max(1, ceil($words / 200));

          // ========== BREADCRUMBS ==========
          $crumb_url   = '';
          $crumb_label = '';

          if ($pt === 'post') {
            $cats = get_the_category();
            $sub  = $cats ? array_values(array_filter($cats, function($c){ return (int)$c->parent !== 0; })) : array();
            $pick = $sub ? $sub[0] : ($cats ? $cats[0] : null);
            if ($pick) {
              $crumb_url   = get_category_link($pick->term_id);
              $crumb_label = $pick->name;
            } else {
              $blog_id = (int) get_option('page_for_posts');
              if ($blog_id) {
                $crumb_url   = get_permalink($blog_id);
                $crumb_label = get_the_title($blog_id);
              } else {
                $crumb_url   = home_url('/');
                $crumb_label = __('Blog','ugel-theme');
              }
            }
          } else {
            $archive_url = $pto && !empty($pto->has_archive) ? get_post_type_archive_link($pt) : '';
            if ($archive_url) {
              $crumb_url   = $archive_url;
              $crumb_label = $pto ? $pto->labels->name : ucfirst($pt);
            } else {
              $crumb_url   = '';
              $crumb_label = $pto ? $pto->labels->name : ucfirst($pt);
            }
          }
        ?>

          <!-- ========== BREADCRUMBS ========== -->
          <header class="single-crumbs">
            <nav class="breadcrumbs" aria-label="Ruta de navegación">
              <a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a>
              <span class="separator">›</span>
              <?php if ($crumb_url): ?>
                <a href="<?php echo esc_url($crumb_url); ?>"><?php echo esc_html($crumb_label); ?></a>
              <?php else: ?>
                <span class="current"><?php echo esc_html($crumb_label); ?></span>
              <?php endif; ?>
              <span class="separator">›</span>
              <span class="current"><?php the_title(); ?></span>
            </nav>
          </header>

          <!-- ========== MAIN BOARD ========== -->
          <main class="single-board" role="main">
            
            <!-- Barra de lectura -->
            <div class="readbar" aria-hidden="true">
              <span class="readbar-fill"></span>
            </div>

            <!-- Etiquetas flotantes -->
            <div class="single-badges">
              <span class="badge badge-kind"><?php echo esc_html($label ?: 'Publicación'); ?></span>
              <time class="badge badge-date" datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
                <?php echo esc_html( get_the_date('d M Y') ); ?>
              </time>
              <span class="badge badge-time">
                <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/></path></svg>
                <?php echo esc_html($mins); ?> min
              </span>
            </div>

            <!-- ========== HEADER CONTENIDO ========== -->
            <header class="single-header">
              <h1 class="single-title">
                <span class="title-text"><?php the_title(); ?></span>
                <span class="title-underline"></span>
              </h1>

              <div class="single-meta">
                <span class="meta-item meta-date">
                  <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                    <path fill="currentColor" d="M16 2H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 15H8v-2h6v2zm0-4H8v-2h6v2zm0-4H8V7h6v2z"/>
                  </svg>
                  <time><?php echo esc_html( get_the_date('d \d\e F, Y') ); ?></time>
                </span>

                <?php if ($pt === 'post'): ?>
                <span class="meta-item meta-category">
                  <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                    <path fill="currentColor" d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                  </svg>
                  <span><?php the_category(', '); ?></span>
                </span>
                <?php endif; ?>

                <span class="meta-item meta-read">
                  <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                    <path fill="currentColor" d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                  </svg>
                  <span><?php echo esc_html($mins); ?> min de lectura</span>
                </span>
              </div>
            </header>

            <!-- ========== EXCERPT ========== -->
            <?php if ( has_excerpt() ): ?>
              <div class="single-excerpt">
                <?php echo wp_kses_post( get_the_excerpt() ); ?>
              </div>
            <?php endif; ?>

            <!-- ========== CONTENIDO PRINCIPAL ========== -->
            <article class="single-content">
              <?php the_content(); ?>
            </article>

            <!-- ========== NAVEGACIÓN POST ========== -->
            <nav class="post-navigation" aria-label="Navegación entre artículos">
              <div class="nav-post nav-prev">
                <?php previous_post_link('<span class="nav-link">%link</span>', '<span class="nav-arrow">←</span><span class="nav-text">Anterior</span>'); ?>
              </div>
              <div class="nav-post nav-next">
                <?php next_post_link('<span class="nav-link">%link</span>', '<span class="nav-text">Siguiente</span><span class="nav-arrow">→</span>'); ?>
              </div>
            </nav>

          </main>

        <?php endwhile; endif; ?>
      </div>

    </div>
  </div>
</section>

<style>
/* ================================
   SINGLE POST - ELITE CORPORATIVO UGEL
   Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
   ================================ */

/* ========== BREADCRUMBS ========== */
.single-crumbs {
  margin: 0 0 16px;
  padding: 0 0 12px;
}

.breadcrumbs {
  font-size: 12px;
  color: #0F4A7F;
  font-weight: 600;
  letter-spacing: 0.2px;
  margin: 0;
  padding: 0;
  border-bottom: 1px solid rgba(130, 151, 254, 0.12);
}

.breadcrumbs a {
  color: #021F59;
  text-decoration: none;
  font-weight: 800;
  transition: color 0.25s ease;
}

.breadcrumbs a:hover {
  color: #000C97;
  text-decoration: underline;
}

.breadcrumbs .separator {
  margin: 0 8px;
  color: #94A3B8;
  opacity: 0.7;
}

.breadcrumbs .current {
  color: #64748B;
  font-weight: 700;
}

/* ========== SINGLE BOARD ========== */
.single-board {
  --r: 16px;
  --pad-v: 28px;
  --pad-h: 28px;
  
  position: relative;
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 45%,
    #F4F7FF 100%
  );
  
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: var(--r);
  padding: var(--pad-v) var(--pad-h);
  padding-bottom: 0;
  
  box-shadow: 
    0 10px 32px rgba(2, 31, 89, 0.07),
    0 4px 16px rgba(2, 31, 89, 0.03),
    inset 0 1px 0 rgba(255, 255, 255, 0.95);
  
  overflow: hidden;
  transition: all 0.3s ease;
}

.single-board:hover {
  box-shadow: 
    0 14px 40px rgba(2, 31, 89, 0.10),
    0 6px 20px rgba(2, 31, 89, 0.05),
    inset 0 1px 0 rgba(255, 255, 255, 0.98);
}

/* Acento vertical derecha */
.single-board::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 5px;
  height: 100%;
  background: linear-gradient(180deg, 
    #B2FFFF 0%,
    #8297FE 25%,
    #000C97 50%,
    #021F59 75%,
    #000C97 100%
  );
  border-radius: 0 var(--r) var(--r) 0;
  opacity: 0.4;
  transition: opacity 0.3s ease;
}

.single-board:hover::after {
  opacity: 0.6;
}

/* ========== BARRA DE LECTURA ========== */
.readbar {
  position: sticky;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: transparent;
  z-index: 10;
  border-radius: 2px;
}

.readbar .readbar-fill {
  display: block;
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #000C97, #8297FE, #B2FFFF);
  border-radius: 2px;
  transition: width 0.1s ease-out;
  box-shadow: 0 2px 8px rgba(0, 12, 151, 0.25);
}

/* ========== BADGES FLOTANTES ========== */
.single-badges {
  position: absolute;
  top: 28px;
  right: 28px;
  z-index: 4;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: flex-end;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  border-radius: 12px;
  font-weight: 800;
  font-size: 11px;
  letter-spacing: 0.4px;
  text-transform: uppercase;
  
  background: rgba(255, 255, 255, 0.90);
  border: 1px solid rgba(130, 151, 254, 0.15);
  color: #021F59;
  
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.10),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  transition: all 0.3s ease;
}

.badge:hover {
  background: rgba(255, 255, 255, 0.95);
  border-color: rgba(130, 151, 254, 0.25);
  transform: translateY(-2px);
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.15),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.badge-kind {
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.08),
    rgba(130, 151, 254, 0.05)
  );
  border-color: rgba(0, 12, 151, 0.15);
}

.badge-date {
  background: linear-gradient(135deg, 
    rgba(178, 255, 255, 0.08),
    rgba(130, 151, 254, 0.05)
  );
  border-color: rgba(178, 255, 255, 0.15);
}

.badge-time {
  background: linear-gradient(135deg, 
    rgba(130, 151, 254, 0.08),
    rgba(178, 255, 255, 0.05)
  );
  border-color: rgba(130, 151, 254, 0.15);
}

.badge svg {
  opacity: 0.85;
  flex-shrink: 0;
}

/* ========== HEADER CONTENIDO ========== */
.single-header {
  margin: 32px 0 24px;
  padding-top: 16px;
}

.single-title {
  position: relative;
  margin: 0 0 18px;
  padding: 0;
}

.title-text {
  display: inline-block;
  font-size: clamp(24px, 4.5vw, 40px);
  font-weight: 900;
  line-height: 1.20;
  color: #021F59;
  letter-spacing: -0.015em;
  word-break: break-word;
  word-wrap: break-word;
  hyphens: auto;
  position: relative;
  z-index: 2;
}

.title-underline {
  position: absolute;
  bottom: 4px;
  left: 0;
  width: 60%;
  height: 10px;
  background: linear-gradient(90deg, 
    rgba(0, 12, 151, 0.12),
    rgba(130, 151, 254, 0.15),
    rgba(178, 255, 255, 0.10)
  );
  border-radius: 6px;
  z-index: 1;
  box-shadow: 0 2px 8px rgba(0, 12, 151, 0.10);
}

/* ========== META INFORMACIÓN ========== */
.single-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  margin-top: 14px;
  font-size: 13px;
  color: #0F4A7F;
  font-weight: 600;
  letter-spacing: 0.2px;
}

.meta-item {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  background: rgba(255, 255, 255, 0.5);
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: 10px;
  transition: all 0.25s ease;
}

.meta-item:hover {
  background: rgba(255, 255, 255, 0.8);
  border-color: rgba(130, 151, 254, 0.25);
}

.meta-item svg {
  opacity: 0.80;
  color: #000C97;
  flex-shrink: 0;
}

/* ========== EXCERPT ========== */
.single-excerpt {
  margin: 18px 0 24px;
  padding: 18px 20px;
  font-size: 15px;
  line-height: 1.68;
  color: #0F4A7F;
  font-weight: 600;
  letter-spacing: 0.2px;
  
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.04),
    rgba(130, 151, 254, 0.03),
    rgba(178, 255, 255, 0.02)
  );
  
  border-left: 4px solid #000C97;
  border-radius: 0 10px 10px 0;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
}

/* ========== CONTENIDO PRINCIPAL ========== */
.single-content {
  margin-top: 24px;
  padding-bottom: 28px;
  font-size: 15px;
  line-height: 1.78;
  color: #475569;
}

.single-content p {
  margin: 0 0 18px;
}

.single-content h2 {
  margin: 32px 0 14px;
  font-size: 22px;
  font-weight: 800;
  line-height: 1.28;
  color: #021F59;
  letter-spacing: -0.01em;
}

.single-content h3 {
  margin: 24px 0 12px;
  font-size: 19px;
  font-weight: 800;
  line-height: 1.32;
  color: #000C97;
  letter-spacing: -0.005em;
}

.single-content h4 {
  margin: 18px 0 10px;
  font-size: 16px;
  font-weight: 800;
  color: #0F4A7F;
}

.single-content a {
  color: #000C97;
  text-decoration: none;
  font-weight: 700;
  transition: all 0.25s ease;
  border-bottom: 2px solid rgba(0, 12, 151, 0.20);
}

.single-content a:hover {
  color: #8297FE;
  border-bottom-color: #8297FE;
}

.single-content img {
  max-width: 100%;
  height: auto;
  display: block;
  margin: 24px auto;
  border-radius: 12px;
  border: 1px solid rgba(130, 151, 254, 0.15);
  box-shadow: 0 8px 24px rgba(2, 31, 89, 0.08);
  transition: all 0.3s ease;
}

.single-content img:hover {
  box-shadow: 0 12px 36px rgba(2, 31, 89, 0.12);
  transform: translateY(-2px);
}

.single-content figure {
  margin: 24px 0;
  padding: 0;
}

.single-content figcaption {
  font-size: 13px;
  color: #64748B;
  text-align: center;
  margin-top: 8px;
  font-weight: 500;
}

.single-content blockquote {
  margin: 24px 0;
  padding: 16px 18px;
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.04),
    rgba(130, 151, 254, 0.03)
  );
  border-left: 4px solid #8297FE;
  border-radius: 0 8px 8px 0;
  color: #0F4A7F;
  font-style: italic;
  font-weight: 600;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
}

.single-content blockquote p {
  margin: 0;
}

.single-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 24px 0;
  border: 1px solid rgba(130, 151, 254, 0.15);
  border-radius: 8px;
  overflow: hidden;
}

.single-content th,
.single-content td {
  border: 1px solid rgba(130, 151, 254, 0.12);
  padding: 12px 14px;
  text-align: left;
}

.single-content thead th {
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.08),
    rgba(130, 151, 254, 0.05)
  );
  font-weight: 800;
  color: #021F59;
}

.single-content tbody tr:hover {
  background: rgba(178, 255, 255, 0.03);
}

/* ========== NAVEGACIÓN POST ========== */
.post-navigation {
  display: flex;
  align-items: stretch;
  gap: 14px;
  margin-top: 32px;
  margin-bottom: 0;
  padding-top: 20px;
  border-top: 1px solid rgba(130, 151, 254, 0.12);
}

.nav-prev {
  margin-right: auto;
}

.nav-next {
  margin-left: auto;
}

.nav-link {
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
}

.nav-post a {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  text-decoration: none;
  color: #021F59;
  font-weight: 800;
  font-size: 13px;
  letter-spacing: 0.3px;
  
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 100%
  );
  
  border: 1.2px solid rgba(130, 151, 254, 0.20);
  border-radius: 12px;
  padding: 12px 18px;
  
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  
  transition: all 0.35s cubic-bezier(0.23, 1, 0.320, 1);
  min-height: 44px;
}

.nav-arrow {
  font-size: 16px;
  opacity: 0.75;
  transition: all 0.3s ease;
}

.nav-text {
  transition: all 0.3s ease;
}

.nav-post a:hover {
  transform: translateY(-3px);
  border-color: rgba(0, 12, 151, 0.40);
  background: linear-gradient(135deg, 
    #FCFDFF 0%,
    #F8FAFF 100%
  );
  color: #000C97;
  box-shadow: 
    0 10px 28px rgba(2, 31, 89, 0.15),
    0 4px 12px rgba(130, 151, 254, 0.10),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.nav-post a:hover .nav-arrow {
  opacity: 1;
  transform: translateX(2px);
}

.nav-prev a:hover .nav-arrow {
  transform: translateX(-2px);
}

.nav-post a:active {
  transform: translateY(-1px);
}



/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  .single-board {
    --pad-v: 24px;
    --pad-h: 22px;
  }

  .single-badges {
    top: 24px;
    right: 22px;
    gap: 8px;
  }

  .badge {
    font-size: 10px;
    padding: 6px 12px;
  }

  .single-header {
    margin: 24px 0 18px;
  }

  .single-content {
    font-size: 14.5px;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 640px) {
  .single-crumbs {
    margin: 0 0 12px;
  }

  .breadcrumbs {
    font-size: 11px;
    padding: 0 0 8px;
  }

  .single-board {
    --pad-v: 18px;
    --pad-h: 16px;
    border-radius: 14px;
  }

  .single-badges {
    top: 18px;
    right: 16px;
    gap: 6px;
    width: calc(100% - 32px);
    justify-content: flex-end;
  }

  .badge {
    font-size: 9px;
    padding: 5px 10px;
  }

  .single-header {
    margin: 20px 0 16px;
    padding-top: 8px;
  }

  .title-text {
    font-size: clamp(20px, 5.5vw, 26px);
    line-height: 1.18;
  }

  .single-meta {
    gap: 10px;
    font-size: 12px;
  }

  .meta-item {
    padding: 5px 10px;
    gap: 6px;
  }

  .meta-item svg {
    width: 14px;
    height: 14px;
  }

  .single-excerpt {
    margin: 14px 0 18px;
    padding: 14px 16px;
    font-size: 14px;
    line-height: 1.60;
  }

  .single-content {
    font-size: 14px;
    line-height: 1.70;
    padding-bottom: 18px;
  }

  .single-content p {
    margin: 0 0 14px;
  }

  .single-content h2 {
    margin: 24px 0 12px;
    font-size: 20px;
  }

  .single-content h3 {
    margin: 18px 0 10px;
    font-size: 17px;
  }

  .single-content img {
    margin: 18px auto;
    border-radius: 10px;
  }

  .post-navigation {
    flex-direction: column;
    gap: 10px;
    margin-top: 24px;
    padding-top: 16px;
  }

  .nav-prev,
  .nav-next {
    margin: 0 !important;
  }

  .nav-post a {
    width: 100%;
    padding: 11px 16px;
    font-size: 12px;
    justify-content: center;
    min-height: 40px;
  }

  .nav-post a:hover {
    transform: translateY(-2px);
  }
}

/* ========== REDUCE MOTION ========== */
@media (prefers-reduced-motion: reduce) {
  .single-board,
  .badge,
  .meta-item,
  .single-content a,
  .single-content img,
  .nav-post a,
  .readbar-fill {
    transition: none !important;
    animation: none !important;
  }

  .single-board:hover::after {
    opacity: 0.4 !important;
  }

  .nav-post a:hover {
    transform: none !important;
  }

  .nav-post a:hover .nav-arrow {
    transform: none !important;
  }
}
</style>

<script>
(function() {
  'use strict';

  // ========== BARRA DE LECTURA ==========
  const board = document.querySelector('.single-board');
  const fill  = board?.querySelector('.readbar-fill');
  const art   = board?.querySelector('.single-content');

  function updateReadbar() {
    if (!board || !fill || !art) return;
    
    const rect = art.getBoundingClientRect();
    const h = art.offsetHeight;
    const winH = window.innerHeight || document.documentElement.clientHeight;
    const top = Math.max(0, Math.min(h, -rect.top));
    const denom = Math.max(1, h - winH);
    const p = Math.max(0, Math.min(1, top / denom));
    
    fill.style.width = (p * 100).toFixed(2) + '%';
  }

  window.addEventListener('scroll', updateReadbar, { passive: true });
  window.addEventListener('resize', updateReadbar, { passive: true });
  updateReadbar();

  // ========== ENLACES EXTERNOS ==========
  document.querySelectorAll('.single-content a[href^="http"]').forEach(a => {
    try {
      if (new URL(a.href).host !== location.host) {
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
      }
    } catch (_) {}
  });

  // ========== SMOOTH SCROLL PARA ANCLAS ==========
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

<?php get_footer(); ?>