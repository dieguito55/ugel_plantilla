<?php
/**
 * Single — Post individual con aside de Accesos Directos
 * Diseño Elite Corporativo UGEL - PREMIUM EDITION
 * Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
 * Optimizado: Animaciones, Sombras, Tipografía Premium
 */

get_header(); ?>

<section class="hub" aria-label="Contenido principal">
  <div class="wrap">
    <div class="hub-layout">

      <?php get_template_part('template-parts/accesos-directos'); ?>

      <div class="hub-main">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
          
          $pt    = get_post_type();
          $pto   = $pt ? get_post_type_object($pt) : null;
          $label = $pto ? $pto->labels->singular_name : get_post_type();
          $words = str_word_count( wp_strip_all_tags( get_the_content() ) );
          $mins  = max(1, ceil($words / 200));

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

          <header class="single-crumbs">
            <nav class="breadcrumbs" aria-label="Ruta de navegación">
              <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-home">
                <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                Inicio
              </a>
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

          <main class="single-board" role="main">
            
            <div class="readbar" aria-hidden="true">
              <span class="readbar-fill"></span>
            </div>

            <div class="single-badges">
              <span class="badge badge-kind" data-tooltip="Tipo de contenido"><?php echo esc_html($label ?: 'Publicación'); ?></span>
              <time class="badge badge-date" datetime="<?php echo esc_attr( get_the_date('c') ); ?>" data-tooltip="Fecha de publicación">
                <?php echo esc_html( get_the_date('d M Y') ); ?>
              </time>
              <span class="badge badge-time" data-tooltip="Tiempo de lectura">
                <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/></path></svg>
                <?php echo esc_html($mins); ?> min
              </span>
            </div>

            <header class="single-header">
              <div class="title-wrapper">
                <h1 class="single-title">
                  <span class="title-text"><?php the_title(); ?></span>
                </h1>
                <div class="title-accent"></div>
              </div>

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

            <?php if ( has_excerpt() ): ?>
              <div class="single-excerpt">
                <?php echo wp_kses_post( get_the_excerpt() ); ?>
              </div>
            <?php endif; ?>

            <article class="single-content">
              <?php the_content(); ?>
            </article>

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
   SINGLE POST - ELITE CORPORATIVO UGEL PREMIUM
   Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
   ================================ */

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
  --color-primary: #000C97;
  --color-secondary: #8297FE;
  --color-accent: #B2FFFF;
  --color-dark: #021F59;
  --color-light: #FFFFFF;
  --color-text: #0F4A7F;
  --color-bg: #FAFBFF;
  --radius-sm: 10px;
  --radius-md: 14px;
  --radius-lg: 16px;
  --shadow-sm: 0 4px 12px rgba(2, 31, 89, 0.08);
  --shadow-md: 0 10px 32px rgba(2, 31, 89, 0.10);
  --shadow-lg: 0 14px 40px rgba(2, 31, 89, 0.12);
}

/* ========== BREADCRUMBS ========== */
.single-crumbs {
  margin: 0 0 20px;
  padding: 0 0 16px;
  animation: fadeInDown 0.6s ease-out;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.breadcrumbs {
  font-size: 12px;
  color: var(--color-text);
  font-weight: 600;
  letter-spacing: 0.3px;
  margin: 0;
  padding: 0;
  border-bottom: 1.5px solid rgba(130, 151, 254, 0.15);
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 4px;
}

.breadcrumb-home {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.breadcrumb-home svg {
  opacity: 0.8;
  transition: all 0.3s ease;
}

.breadcrumbs a {
  color: var(--color-dark);
  text-decoration: none;
  font-weight: 700;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  position: relative;
}

.breadcrumbs a::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
  transition: width 0.3s ease;
}

.breadcrumbs a:hover {
  color: var(--color-primary);
}

.breadcrumbs a:hover::after {
  width: 100%;
}

.breadcrumbs .separator {
  margin: 0 6px;
  color: #94A3B8;
  opacity: 0.6;
  font-weight: 300;
}

.breadcrumbs .current {
  color: #64748B;
  font-weight: 700;
  text-transform: capitalize;
}

/* ========== SINGLE BOARD ========== */
.single-board {
  --r: 18px;
  --pad-v: 32px;
  --pad-h: 32px;
  
  position: relative;
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 40%,
    #F4F7FF 100%
  );
  
  border: 1.5px solid rgba(130, 151, 254, 0.15);
  border-radius: var(--r);
  padding: var(--pad-v) var(--pad-h);
  padding-bottom: 0;
  
  box-shadow: 
    0 12px 40px rgba(2, 31, 89, 0.08),
    0 4px 16px rgba(2, 31, 89, 0.04),
    inset 0 1px 0 rgba(255, 255, 255, 0.98),
    inset 0 -1px 0 rgba(2, 31, 89, 0.02);
  
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  animation: slideInUp 0.7s ease-out 0.1s both;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(24px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.single-board:hover {
  box-shadow: 
    0 16px 48px rgba(2, 31, 89, 0.12),
    0 6px 20px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.99),
    inset 0 -1px 0 rgba(2, 31, 89, 0.03);
  transform: translateY(-4px);
  border-color: rgba(130, 151, 254, 0.25);
}

/* Acento vertical derecha - MEJORADO */
.single-board::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 6px;
  height: 100%;
  background: linear-gradient(180deg, 
    #B2FFFF 0%,
    #8297FE 20%,
    #000C97 50%,
    #021F59 75%,
    #000C97 95%,
    #8297FE 100%
  );
  border-radius: 0 var(--r) var(--r) 0;
  opacity: 0.3;
  transition: all 0.4s ease;
  box-shadow: -3px 0 12px rgba(0, 12, 151, 0.15);
}

.single-board:hover::after {
  opacity: 0.7;
  box-shadow: -4px 0 16px rgba(0, 12, 151, 0.25);
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
  background: linear-gradient(90deg, 
    #000C97 0%,
    #8297FE 50%,
    #B2FFFF 100%
  );
  border-radius: 2px;
  transition: width 0.15s ease-out;
  box-shadow: 
    0 2px 8px rgba(0, 12, 151, 0.30),
    0 0 16px rgba(178, 255, 255, 0.20);
}

/* ========== BADGES FLOTANTES ========== */
.single-badges {
  position: absolute;
  top: 32px;
  right: 32px;
  z-index: 4;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  justify-content: flex-end;
  animation: fadeIn 0.8s ease-out 0.3s both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 14px;
  font-weight: 800;
  font-size: 11px;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  font-family: 'Inter', sans-serif;
  
  background: rgba(255, 255, 255, 0.92);
  border: 1.2px solid rgba(130, 151, 254, 0.18);
  color: var(--color-dark);
  
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.12),
    inset 0 1px 2px rgba(255, 255, 255, 0.9);
  
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: default;
  position: relative;
}

.badge::before {
  content: attr(data-tooltip);
  position: absolute;
  bottom: -32px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--color-dark);
  color: white;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 10px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.2s ease;
  font-weight: 600;
}

.badge:hover {
  background: rgba(255, 255, 255, 0.97);
  border-color: rgba(130, 151, 254, 0.35);
  transform: translateY(-3px) scale(1.05);
  box-shadow: 
    0 10px 24px rgba(2, 31, 89, 0.18),
    inset 0 1px 2px rgba(255, 255, 255, 0.95);
}

.badge-kind {
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.10),
    rgba(130, 151, 254, 0.06)
  );
  border-color: rgba(0, 12, 151, 0.20);
}

.badge-date {
  background: linear-gradient(135deg, 
    rgba(178, 255, 255, 0.10),
    rgba(130, 151, 254, 0.06)
  );
  border-color: rgba(178, 255, 255, 0.20);
}

.badge-time {
  background: linear-gradient(135deg, 
    rgba(130, 151, 254, 0.10),
    rgba(178, 255, 255, 0.06)
  );
  border-color: rgba(130, 151, 254, 0.20);
}

.badge svg {
  opacity: 0.90;
  flex-shrink: 0;
  transition: transform 0.3s ease;
}

.badge:hover svg {
  transform: scale(1.15) rotate(10deg);
}

/* ========== HEADER CONTENIDO ========== */
.single-header {
  margin: 36px 0 28px;
  padding-top: 18px;
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.title-wrapper {
  position: relative;
  margin-bottom: 20px;
}

.single-title {
  position: relative;
  margin: 0;
  padding: 0;
}

.title-text {
  display: inline-block;
  font-size: clamp(26px, 5vw, 44px);
  font-weight: 900;
  line-height: 1.15;
  color: var(--color-dark);
  letter-spacing: -0.02em;
  word-break: break-word;
  word-wrap: break-word;
  hyphens: auto;
  position: relative;
  z-index: 2;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, var(--color-dark) 0%, var(--color-primary) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: none;
}

.title-accent {
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 0;
  height: 12px;
  background: linear-gradient(90deg, 
    rgba(0, 12, 151, 0.20),
    rgba(130, 151, 254, 0.20),
    rgba(178, 255, 255, 0.15)
  );
  border-radius: 8px;
  z-index: 1;
  box-shadow: 0 3px 12px rgba(0, 12, 151, 0.12);
  animation: expandWidth 0.8s ease-out 0.4s both;
}

@keyframes expandWidth {
  from {
    width: 0;
  }
  to {
    width: 65%;
  }
}

/* ========== META INFORMACIÓN ========== */
.single-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 14px;
  margin-top: 16px;
  font-size: 13px;
  color: var(--color-text);
  font-weight: 600;
  letter-spacing: 0.25px;
  animation: fadeIn 0.8s ease-out 0.5s both;
}

.meta-item {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  padding: 8px 14px;
  background: rgba(255, 255, 255, 0.6);
  border: 1.2px solid rgba(130, 151, 254, 0.15);
  border-radius: 12px;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  font-family: 'Inter', sans-serif;
}

.meta-item:hover {
  background: rgba(255, 255, 255, 0.85);
  border-color: rgba(130, 151, 254, 0.30);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.10);
}

.meta-item svg {
  opacity: 0.85;
  color: var(--color-primary);
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.meta-item:hover svg {
  transform: scale(1.2) rotate(-5deg);
  opacity: 1;
}

/* ========== EXCERPT ========== */
.single-excerpt {
  margin: 22px 0 28px;
  padding: 20px 22px;
  font-size: 15px;
  line-height: 1.72;
  color: var(--color-text);
  font-weight: 600;
  letter-spacing: 0.25px;
  font-family: 'Inter', sans-serif;
  
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.05),
    rgba(130, 151, 254, 0.04),
    rgba(178, 255, 255, 0.02)
  );
  
  border-left: 4px solid var(--color-primary);
  border-radius: 0 12px 12px 0;
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  
  transition: all 0.3s ease;
  animation: slideInLeft 0.7s ease-out 0.3s both;
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-16px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.single-excerpt:hover {
  border-left-color: var(--color-secondary);
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.08),
    rgba(130, 151, 254, 0.06),
    rgba(178, 255, 255, 0.03)
  );
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

/* ========== CONTENIDO PRINCIPAL ========== */
.single-content {
  margin-top: 28px;
  padding-bottom: 32px;
  font-size: 15px;
  line-height: 1.82;
  color: #475569;
  font-family: 'Inter', sans-serif;
  letter-spacing: 0.2px;
}

.single-content p {
  margin: 0 0 20px;
  transition: color 0.3s ease;
}

.single-content h2 {
  margin: 36px 0 16px;
  padding-bottom: 12px;
  font-size: 24px;
  font-weight: 900;
  line-height: 1.25;
  color: var(--color-dark);
  letter-spacing: -0.015em;
  font-family: 'Poppins', sans-serif;
  border-bottom: 2px solid rgba(130, 151, 254, 0.12);
  transition: all 0.3s ease;
}

.single-content h2:hover {
  border-bottom-color: var(--color-secondary);
  color: var(--color-primary);
}

.single-content h3 {
  margin: 28px 0 14px;
  font-size: 20px;
  font-weight: 800;
  line-height: 1.32;
  color: var(--color-primary);
  letter-spacing: -0.01em;
  font-family: 'Poppins', sans-serif;
  position: relative;
  padding-left: 14px;
}

.single-content h3::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 4px;
  height: 20px;
  background: linear-gradient(180deg, var(--color-primary), var(--color-secondary));
  border-radius: 2px;
  transition: all 0.3s ease;
}

.single-content h3:hover::before {
  width: 6px;
  box-shadow: 0 0 12px rgba(0, 12, 151, 0.25);
}

.single-content h4 {
  margin: 20px 0 12px;
  font-size: 16px;
  font-weight: 800;
  color: var(--color-text);
  font-family: 'Poppins', sans-serif;
}

.single-content a {
  color: var(--color-primary);
  text-decoration: none;
  font-weight: 700;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  border-bottom: 2px solid rgba(0, 12, 151, 0.25);
  padding-bottom: 2px;
  position: relative;
}

.single-content a::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
  transition: width 0.3s ease;
}

.single-content a:hover {
  color: var(--color-secondary);
  border-bottom-color: var(--color-secondary);
}

.single-content a:hover::after {
  width: 100%;
}

.single-content img {
  max-width: 100%;
  height: auto;
  display: block;
  margin: 28px auto;
  border-radius: 14px;
  border: 1.5px solid rgba(130, 151, 254, 0.18);
  box-shadow: 
    0 10px 28px rgba(2, 31, 89, 0.10),
    0 2px 8px rgba(2, 31, 89, 0.05);
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: pointer;
}

.single-content img:hover {
  box-shadow: 
    0 16px 40px rgba(2, 31, 89, 0.15),
    0 4px 12px rgba(130, 151, 254, 0.15);
  transform: translateY(-4px) scale(1.02);
  border-color: rgba(130, 151, 254, 0.30);
}

.single-content figure {
  margin: 28px 0;
  padding: 0;
}

.single-content figcaption {
  font-size: 13px;
  color: #64748B;
  text-align: center;
  margin-top: 10px;
  font-weight: 600;
  font-style: italic;
  letter-spacing: 0.15px;
}

.single-content blockquote {
  margin: 28px 0;
  padding: 20px 22px;
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.05),
    rgba(130, 151, 254, 0.04),
    rgba(178, 255, 255, 0.02)
  );
  border-left: 5px solid var(--color-secondary);
  border-radius: 0 10px 10px 0;
  color: var(--color-text);
  font-style: italic;
  font-weight: 600;
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  transition: all 0.3s ease;
  position: relative;
  padding-left: 24px;
}

.single-content blockquote::before {
  content: '"';
  position: absolute;
  left: 8px;
  top: 0;
  font-size: 48px;
  color: rgba(130, 151, 254, 0.15);
  font-family: 'Poppins', serif;
  font-style: normal;
  font-weight: 900;
}

.single-content blockquote:hover {
  border-left-color: var(--color-accent);
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.08),
    rgba(130, 151, 254, 0.06),
    rgba(178, 255, 255, 0.03)
  );
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.10),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
  transform: translateX(4px);
}

.single-content blockquote p {
  margin: 0;
}

.single-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 28px 0;
  border: 1.5px solid rgba(130, 151, 254, 0.18);
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.06);
  transition: all 0.3s ease;
}

.single-content table:hover {
  box-shadow: 0 8px 24px rgba(2, 31, 89, 0.10);
  border-color: rgba(130, 151, 254, 0.30);
}

.single-content th,
.single-content td {
  border: 1px solid rgba(130, 151, 254, 0.12);
  padding: 14px 16px;
  text-align: left;
  font-weight: 500;
}

.single-content thead th {
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.10),
    rgba(130, 151, 254, 0.07),
    rgba(178, 255, 255, 0.04)
  );
  font-weight: 800;
  color: var(--color-dark);
  font-family: 'Poppins', sans-serif;
  letter-spacing: 0.3px;
}

.single-content tbody tr {
  transition: all 0.2s ease;
}

.single-content tbody tr:hover {
  background: rgba(178, 255, 255, 0.05);
  box-shadow: inset 1px 0 0 rgba(130, 151, 254, 0.12);
}

.single-content tbody tr:nth-child(even) {
  background: rgba(130, 151, 254, 0.02);
}

/* ========== NAVEGACIÓN POST ========== */
.post-navigation {
  display: flex;
  align-items: stretch;
  gap: 16px;
  margin-top: 36px;
  margin-bottom: 0;
  padding-top: 24px;
  border-top: 2px solid rgba(130, 151, 254, 0.15);
  animation: fadeIn 0.8s ease-out 0.6s both;
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
  gap: 11px;
  text-decoration: none;
  color: var(--color-dark);
  font-weight: 800;
  font-size: 13px;
  letter-spacing: 0.4px;
  font-family: 'Poppins', sans-serif;
  text-transform: uppercase;
  
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 50%,
    #F4F7FF 100%
  );
  
  border: 1.5px solid rgba(130, 151, 254, 0.22);
  border-radius: 14px;
  padding: 13px 20px;
  
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.08),
    0 2px 8px rgba(130, 151, 254, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
  
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  min-height: 48px;
  position: relative;
  overflow: hidden;
}

.nav-post a::before {
  content: '';
  position: absolute;
  top: 50%;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, 
    transparent,
    rgba(178, 255, 255, 0.15),
    transparent
  );
  transform: translateY(-50%);
  transition: left 0.6s ease;
}

.nav-post a:hover::before {
  left: 100%;
}

.nav-arrow {
  font-size: 16px;
  opacity: 0.75;
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  font-weight: 900;
}

.nav-text {
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
}

.nav-post a:hover {
  transform: translateY(-4px);
  border-color: rgba(0, 12, 151, 0.50);
  background: linear-gradient(135deg, 
    #FCFDFF 0%,
    #F9FBFF 50%,
    #F5F8FF 100%
  );
  color: var(--color-primary);
  box-shadow: 
    0 12px 32px rgba(2, 31, 89, 0.18),
    0 6px 16px rgba(130, 151, 254, 0.12),
    inset 0 1px 0 rgba(255, 255, 255, 0.95);
}

.nav-post a:hover .nav-arrow {
  opacity: 1;
  transform: translateX(3px);
  color: var(--color-secondary);
}

.nav-prev a:hover .nav-arrow {
  transform: translateX(-3px);
}

.nav-post a:active {
  transform: translateY(-2px);
}

/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  :root {
    --pad-v: 24px;
    --pad-h: 24px;
  }

  .single-board {
    --pad-v: 24px;
    --pad-h: 24px;
  }

  .single-badges {
    top: 24px;
    right: 24px;
    gap: 10px;
  }

  .badge {
    font-size: 10px;
    padding: 7px 13px;
  }

  .single-header {
    margin: 24px 0 18px;
  }

  .single-content {
    font-size: 14.5px;
  }

  .single-content h2 {
    font-size: 22px;
    margin: 32px 0 14px;
  }

  .single-content h3 {
    font-size: 19px;
    margin: 24px 0 12px;
  }

  .post-navigation {
    gap: 12px;
  }

  .nav-post a {
    padding: 11px 16px;
    font-size: 12px;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 640px) {
  :root {
    --radius-lg: 14px;
  }

  .single-crumbs {
    margin: 0 0 12px;
  }

  .breadcrumbs {
    font-size: 11px;
    padding: 0 0 10px;
    gap: 2px;
  }

  .breadcrumb-home svg {
    width: 12px;
    height: 12px;
  }

  .single-board {
    --pad-v: 20px;
    --pad-h: 16px;
    border-radius: 14px;
    box-shadow: 
      0 8px 24px rgba(2, 31, 89, 0.08),
      0 2px 8px rgba(2, 31, 89, 0.04),
      inset 0 1px 0 rgba(255, 255, 255, 0.95);
  }

  .single-board::after {
    width: 4px;
  }

  .single-badges {
    top: 20px;
    right: 16px;
    gap: 8px;
    width: calc(100% - 32px);
    justify-content: flex-end;
  }

  .badge {
    font-size: 9px;
    padding: 6px 11px;
    gap: 5px;
  }

  .badge svg {
    width: 12px;
    height: 12px;
  }

  .single-header {
    margin: 18px 0 14px;
    padding-top: 6px;
  }

  .title-text {
    font-size: clamp(20px, 6vw, 28px);
    line-height: 1.15;
  }

  .title-accent {
    width: 55% !important;
    bottom: 6px;
  }

  .single-meta {
    gap: 9px;
    font-size: 11px;
  }

  .meta-item {
    padding: 6px 10px;
    gap: 6px;
    border-radius: 10px;
  }

  .meta-item svg {
    width: 13px;
    height: 13px;
  }

  .single-excerpt {
    margin: 14px 0 18px;
    padding: 14px 16px;
    font-size: 14px;
    line-height: 1.62;
    border-radius: 0 10px 10px 0;
  }

  .single-content {
    font-size: 14px;
    line-height: 1.72;
    padding-bottom: 20px;
    margin-top: 18px;
  }

  .single-content p {
    margin: 0 0 14px;
  }

  .single-content h2 {
    margin: 22px 0 11px;
    font-size: 19px;
    padding-bottom: 9px;
  }

  .single-content h3 {
    margin: 18px 0 10px;
    font-size: 16px;
    padding-left: 12px;
  }

  .single-content h3::before {
    width: 3px;
    height: 16px;
  }

  .single-content h4 {
    font-size: 15px;
    margin: 16px 0 9px;
  }

  .single-content img {
    margin: 18px auto;
    border-radius: 10px;
  }

  .single-content blockquote {
    margin: 18px 0;
    padding: 14px 14px 14px 20px;
    font-size: 14px;
  }

  .single-content blockquote::before {
    font-size: 36px;
    left: 4px;
  }

  .single-content table {
    font-size: 12px;
    margin: 18px 0;
  }

  .single-content th,
  .single-content td {
    padding: 10px 12px;
  }

  .post-navigation {
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
    padding-top: 16px;
  }

  .nav-prev,
  .nav-next {
    margin: 0 !important;
  }

  .nav-post a {
    width: 100%;
    padding: 12px 14px;
    font-size: 11px;
    justify-content: center;
    min-height: 42px;
    gap: 8px;
  }

  .nav-post a:hover {
    transform: translateY(-3px);
  }
}

/* ========== EXTRA SMALL ========== */
@media (max-width: 480px) {
  .single-crumbs {
    margin: 0 0 10px;
  }

  .breadcrumbs {
    font-size: 10px;
  }

  .single-board {
    --pad-v: 16px;
    --pad-h: 14px;
    border-radius: 12px;
  }

  .single-badges {
    top: 16px;
    right: 14px;
  }

  .badge {
    font-size: 8px;
    padding: 5px 9px;
  }

  .title-text {
    font-size: clamp(18px, 6.5vw, 24px);
  }

  .single-content {
    font-size: 13.5px;
    line-height: 1.65;
  }
}

/* ========== REDUCE MOTION ========== */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }

  .single-board,
  .badge,
  .meta-item,
  .single-content a,
  .single-content img,
  .nav-post a {
    transition: none !important;
  }

  .single-board:hover::after {
    opacity: 0.3 !important;
  }

  .nav-post a:hover {
    transform: none !important;
  }
}

/* ========== DARK MODE (OPCIONAL) ========== */
@media (prefers-color-scheme: dark) {
  .single-board {
    background: linear-gradient(135deg, 
      #1a1f3a 0%,
      #16213e 40%,
      #0f1a2e 100%
    );
  }

  .title-text {
    background: linear-gradient(135deg, #B2FFFF 0%, #8297FE 100%);
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .single-meta,
  .badge,
  .meta-item {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(178, 255, 255, 0.15);
    color: #B2FFFF;
  }

  .single-content {
    color: #cbd5e1;
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

  // ========== ANIMACIÓN ENTRADA CASCADA ==========
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, idx) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.style.animation = `fadeInUp 0.7s ease-out forwards`;
        }, idx * 100);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.single-content h2, .single-content h3').forEach(el => {
    observer.observe(el);
  });

  // ========== PÁRRAFOS CON EFECTO AL SCROLL ==========
  const pObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('.single-content p').forEach(p => {
    p.style.opacity = '0.6';
    p.style.transform = 'translateY(8px)';
    p.style.transition = 'all 0.6s ease-out';
    pObserver.observe(p);
  });
})();
</script>

<?php get_footer(); ?>