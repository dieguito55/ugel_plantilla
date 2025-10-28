<?php
/**
 * Page — Páginas estáticas con control de acceso
 * Diseño Elite Corporativo UGEL - PREMIUM EDITION
 * Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
 * Optimizado: Animaciones, Sombras Avanzadas, Tipografía Premium
 */

// ========== CONTROL DE ACCESO ==========
$page_access = get_post_meta(get_the_ID(), '_page_access_restricted', true);

if ($page_access && !current_user_can('read_private_posts')) {
    status_header(403);
    get_template_part('template-parts/403');
    exit;
}

get_header(); ?>

<section class="hub" aria-label="Contenido principal">
  <div class="wrap">
    <div class="hub-layout">

      <?php get_template_part('template-parts/accesos-directos'); ?>

      <div class="hub-main">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

          <main class="single-post-content" role="main">
            
            <header class="single-header enhanced-header">

              <div class="header-breadcrumbs">
                <?php ugel_breadcrumbs(); ?>
              </div>

              <div class="header-decoration">
                <div class="decoration-line"></div>
                <div class="decoration-dots">
                  <span class="dot dot-1"></span>
                  <span class="dot dot-2"></span>
                  <span class="dot dot-3"></span>
                </div>
              </div>

              <h1 class="single-title enhanced-title">
                <span class="title-text"><?php the_title(); ?></span>
              </h1>

              <div class="title-accent-line"></div>

              <div class="single-meta enhanced-meta">
                <div class="meta-item primary-meta">
                  <div class="meta-icon">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                      <path fill="currentColor" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z"/>
                      <path fill="currentColor" opacity="0.6" d="M14 2v6h6"/>
                    </svg>
                  </div>
                  <span class="meta-text">Página informativa</span>
                </div>

                <?php if (get_the_modified_date() !== get_the_date()) : ?>
                <div class="meta-item secondary-meta">
                  <div class="meta-icon">
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                      <path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/>
                    </svg>
                  </div>
                  <span class="meta-text">Actualizado: <?php echo get_the_modified_date('j M Y'); ?></span>
                </div>
                <?php endif; ?>
              </div>
            </header>

            <article class="single-content enhanced-content">
              <div class="content-wrapper">
                <div class="content-inner">
                  <?php
                  $content = get_the_content();
                  $content = apply_filters('the_content', $content);

                  if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $content, $matches)) {
                    $first_p = $matches[0];
                    $rest_content = str_replace($first_p, '', $content);
                    $enhanced_first_p = str_replace('<p', '<p class="lead-paragraph"', $first_p);
                    echo $enhanced_first_p;
                    echo '<div class="content-divider"></div>';
                    echo $rest_content;
                  } else {
                    echo $content;
                  }
                  ?>

                  <?php
                  wp_link_pages(array(
                    'before'        => '<nav class="page-navigation" aria-label="Navegación de páginas"><div class="page-links">',
                    'after'         => '</div></nav>',
                    'link_before'   => '<span class="page-link">',
                    'link_after'    => '</span>',
                    'next_or_number'=> 'number',
                    'separator'     => '',
                    'pagelink'      => '<span class="screen-reader-text">Página </span>%',
                  ));
                  ?>
                </div>

                <footer class="page-meta-footer">
                  <time class="last-updated" datetime="<?php echo get_the_modified_date('c'); ?>">
                    <svg viewBox="0 0 24 24" width="13" height="13" aria-hidden="true"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    Última actualización: <?php echo get_the_modified_date('j \d\e F, Y'); ?>
                  </time>
                </footer>
              </div>
            </article>

          </main>

        <?php endwhile; else: ?>
          <div class="no-content-error">
            <svg viewBox="0 0 24 24" width="48" height="48" aria-hidden="true"><path fill="currentColor" opacity="0.3" d="M21 5H3c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 12H3V7h18v10zm-5-9h-8v2h8V8z"/></svg>
            <p>No se encontró contenido.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<style>
/* ================================
   PAGE TEMPLATE - ELITE CORPORATIVO UGEL PREMIUM
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
  --shadow-sm: 0 4px 12px rgba(2, 31, 89, 0.08);
  --shadow-md: 0 10px 32px rgba(2, 31, 89, 0.10);
  --shadow-lg: 0 14px 40px rgba(2, 31, 89, 0.12);
}

/* ========== HEADER MEJORADO ========== */
.enhanced-header {
  position: relative;
  text-align: center;
  margin-bottom: 36px;
  padding: 52px 28px 32px;
  
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 40%,
    #F4F7FF 100%
  );
  
  border: 1.5px solid rgba(130, 151, 254, 0.15);
  border-radius: 18px;
  overflow: hidden;
  
  box-shadow: 
    0 12px 40px rgba(2, 31, 89, 0.08),
    0 4px 16px rgba(2, 31, 89, 0.04),
    inset 0 1px 0 rgba(255, 255, 255, 0.98),
    inset 0 -1px 0 rgba(2, 31, 89, 0.02);
  
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  animation: slideInDown 0.7s ease-out;
}

@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-24px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.enhanced-header:hover {
  transform: translateY(-2px);
  box-shadow: 
    0 16px 48px rgba(2, 31, 89, 0.12),
    0 6px 20px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.99);
  border-color: rgba(130, 151, 254, 0.25);
}

/* Borde superior decorativo */
.enhanced-header::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 6px;
  background: linear-gradient(90deg, 
    #B2FFFF 0%,
    #8297FE 25%,
    #000C97 50%,
    #8297FE 75%,
    #B2FFFF 100%
  );
  opacity: 0.8;
  transition: opacity 0.4s ease;
}

.enhanced-header:hover::before {
  opacity: 1;
}

/* Borde derecho decorativo */
.enhanced-header::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 5px;
  background: linear-gradient(180deg, 
    #B2FFFF 0%,
    #8297FE 25%,
    #000C97 50%,
    #021F59 75%,
    #000C97 100%
  );
  opacity: 0.3;
  transition: all 0.4s ease;
  box-shadow: -3px 0 12px rgba(0, 12, 151, 0.12);
}

.enhanced-header:hover::after {
  opacity: 0.6;
  box-shadow: -4px 0 16px rgba(0, 12, 151, 0.20);
}

/* ========== BREADCRUMBS ========== */
.header-breadcrumbs {
  position: absolute;
  top: 14px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 5;
  animation: fadeIn 0.8s ease-out 0.2s both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.header-breadcrumbs .breadcrumbs {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  font-size: 12px;
  line-height: 1;
  font-family: 'Inter', sans-serif;
  
  background: rgba(255, 255, 255, 0.92);
  border: 1.2px solid rgba(130, 151, 254, 0.18);
  border-radius: 14px;
  color: #0F4A7F;
  font-weight: 700;
  letter-spacing: 0.2px;
  
  box-shadow: 
    0 6px 20px rgba(2, 31, 89, 0.10),
    inset 0 1px 2px rgba(255, 255, 255, 0.9);
  
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
}

.header-breadcrumbs .breadcrumbs:hover {
  background: rgba(255, 255, 255, 0.96);
  border-color: rgba(130, 151, 254, 0.30);
  transform: translateY(-2px);
  box-shadow: 
    0 8px 24px rgba(2, 31, 89, 0.15),
    inset 0 1px 2px rgba(255, 255, 255, 0.95);
}

.header-breadcrumbs .breadcrumbs a {
  color: var(--color-dark);
  font-weight: 800;
  text-decoration: none;
  transition: all 0.3s ease;
  position: relative;
}

.header-breadcrumbs .breadcrumbs a::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
  transition: width 0.3s ease;
}

.header-breadcrumbs .breadcrumbs a:hover {
  color: var(--color-primary);
}

.header-breadcrumbs .breadcrumbs a:hover::after {
  width: 100%;
}

.header-breadcrumbs .breadcrumbs .separator {
  color: #94A3B8;
  margin: 0 3px;
  font-weight: 400;
  opacity: 0.7;
}

.header-breadcrumbs .breadcrumbs .current {
  color: #64748B;
  font-weight: 700;
}

.header-breadcrumbs .breadcrumbs a:first-of-type {
  position: relative;
  padding-left: 18px;
}

.header-breadcrumbs .breadcrumbs a:first-of-type::before {
  content: "";
  position: absolute;
  left: 2px;
  top: 50%;
  transform: translateY(-50%);
  width: 12px;
  height: 12px;
  mask: url("data:image/svg+xml;utf8,<svg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path fill='black' d='M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z'/></svg>") no-repeat center / contain;
  background: currentColor;
  opacity: 0.85;
  transition: all 0.3s ease;
}

.header-breadcrumbs .breadcrumbs a:first-of-type:hover::before {
  transform: translateY(-50%) scale(1.2);
  opacity: 1;
}

/* ========== DECORACIÓN HEADER ========== */
.header-decoration {
  margin: 28px 0 22px;
  animation: fadeInUp 0.8s ease-out 0.3s both;
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

.decoration-line {
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #000C97, #8297FE, #B2FFFF);
  margin: 0 auto 14px;
  border-radius: 999px;
  box-shadow: 0 3px 12px rgba(0, 12, 151, 0.18);
  transition: all 0.4s ease;
}

.enhanced-header:hover .decoration-line {
  width: 120px;
  box-shadow: 0 4px 16px rgba(0, 12, 151, 0.25);
}

.decoration-dots {
  display: flex;
  justify-content: center;
  gap: 12px;
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: block;
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(2, 31, 89, 0.12);
}

.dot-1 {
  background: #B2FFFF;
}

.dot-2 {
  background: #8297FE;
}

.dot-3 {
  background: #000C97;
}

.enhanced-header:hover .dot {
  transform: scale(1.25);
}

.dot:hover {
  transform: scale(1.4) rotate(360deg);
}

/* ========== TÍTULO ========== */
.enhanced-title {
  position: relative;
  margin: 0 0 24px;
  padding: 0;
  animation: fadeInUp 0.8s ease-out 0.4s both;
}

.title-text {
  display: inline-block;
  font-size: clamp(28px, 5.5vw, 48px);
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
}

.title-accent-line {
  width: 0;
  height: 5px;
  background: linear-gradient(90deg, 
    var(--color-primary),
    var(--color-secondary),
    var(--color-accent)
  );
  margin: 20px auto 0;
  border-radius: 999px;
  box-shadow: 0 3px 12px rgba(0, 12, 151, 0.20);
  animation: expandWidth 0.8s ease-out 0.5s both;
}

@keyframes expandWidth {
  from {
    width: 0;
  }
  to {
    width: 70%;
  }
}

/* ========== META INFORMACIÓN ========== */
.enhanced-meta {
  display: flex;
  justify-content: center;
  gap: 16px;
  flex-wrap: wrap;
  font-size: 13px;
  animation: fadeIn 0.8s ease-out 0.6s both;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 10px 18px;
  border-radius: 14px;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  backdrop-filter: blur(8px);
  font-family: 'Inter', sans-serif;
  font-weight: 700;
  letter-spacing: 0.2px;
}

.primary-meta {
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.10),
    rgba(130, 151, 254, 0.06)
  );
  border: 1.2px solid rgba(0, 12, 151, 0.18);
  color: var(--color-dark);
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.08);
}

.primary-meta:hover {
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.15),
    rgba(130, 151, 254, 0.10)
  );
  border-color: rgba(0, 12, 151, 0.30);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(2, 31, 89, 0.12);
}

.secondary-meta {
  background: linear-gradient(135deg, 
    rgba(255, 255, 255, 0.75),
    rgba(255, 255, 255, 0.65)
  );
  border: 1.2px solid rgba(130, 151, 254, 0.15);
  color: var(--color-text);
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.06);
}

.secondary-meta:hover {
  background: linear-gradient(135deg, 
    rgba(255, 255, 255, 0.90),
    rgba(255, 255, 255, 0.80)
  );
  border-color: rgba(130, 151, 254, 0.28);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(2, 31, 89, 0.10);
}

.meta-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, 
    rgba(178, 255, 255, 0.18),
    rgba(130, 151, 254, 0.12)
  );
  color: var(--color-primary);
  flex-shrink: 0;
  transition: all 0.3s ease;
  box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.6);
}

.meta-item:hover .meta-icon {
  transform: scale(1.15) rotate(-10deg);
  background: linear-gradient(135deg, 
    rgba(178, 255, 255, 0.25),
    rgba(130, 151, 254, 0.18)
  );
}

/* ========== CONTENIDO PRINCIPAL ========== */
.enhanced-content {
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 40%,
    #F4F7FF 100%
  );
  border: 1.5px solid rgba(130, 151, 254, 0.15);
  border-radius: 18px;
  
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

.enhanced-content:hover {
  transform: translateY(-3px);
  box-shadow: 
    0 16px 48px rgba(2, 31, 89, 0.12),
    0 6px 20px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.99);
  border-color: rgba(130, 151, 254, 0.25);
}

.content-wrapper {
  padding: 36px;
}

.content-inner {
  font-size: 15px;
  line-height: 1.80;
  color: #475569;
  font-family: 'Inter', sans-serif;
  letter-spacing: 0.2px;
}

.content-inner p {
  margin: 0 0 20px;
  transition: all 0.3s ease;
}

.content-inner h2,
.content-inner h3,
.content-inner h4 {
  margin: 32px 0 16px;
  font-weight: 900;
  color: var(--color-dark);
  letter-spacing: -0.015em;
  font-family: 'Poppins', sans-serif;
  transition: all 0.3s ease;
}

.content-inner h2 {
  font-size: 26px;
  margin-top: 36px;
  padding-bottom: 12px;
  border-bottom: 2px solid rgba(130, 151, 254, 0.15);
}

.content-inner h2:hover {
  border-bottom-color: var(--color-secondary);
  color: var(--color-primary);
}

.content-inner h3 {
  font-size: 21px;
  color: var(--color-primary);
  padding-left: 14px;
  position: relative;
}

.content-inner h3::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 4px;
  height: 22px;
  background: linear-gradient(180deg, var(--color-primary), var(--color-secondary));
  border-radius: 2px;
  transition: all 0.3s ease;
}

.content-inner h3:hover::before {
  width: 6px;
  box-shadow: 0 0 12px rgba(0, 12, 151, 0.25);
}

.content-inner h4 {
  font-size: 17px;
  color: var(--color-text);
}

.content-inner a {
  color: var(--color-primary);
  text-decoration: none;
  font-weight: 700;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  border-bottom: 2px solid rgba(0, 12, 151, 0.25);
  padding-bottom: 2px;
  position: relative;
}

.content-inner a::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
  transition: width 0.3s ease;
}

.content-inner a:hover {
  color: var(--color-secondary);
  border-bottom-color: var(--color-secondary);
}

.content-inner a:hover::after {
  width: 100%;
}

.content-inner img {
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

.content-inner img:hover {
  box-shadow: 
    0 16px 40px rgba(2, 31, 89, 0.15),
    0 4px 12px rgba(130, 151, 254, 0.15);
  transform: translateY(-4px) scale(1.02);
  border-color: rgba(130, 151, 254, 0.30);
}

.content-inner figure {
  margin: 28px 0;
  padding: 0;
}

.content-inner figcaption {
  font-size: 13px;
  color: #64748B;
  text-align: center;
  margin-top: 10px;
  font-weight: 600;
  font-style: italic;
  letter-spacing: 0.15px;
}

.content-inner blockquote {
  margin: 28px 0;
  padding: 20px 22px;
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.05),
    rgba(130, 151, 254, 0.04),
    rgba(178, 255, 255, 0.02)
  );
  border-left: 5px solid var(--color-secondary);
  border-radius: 0 12px 12px 0;
  color: var(--color-text);
  font-style: italic;
  font-weight: 600;
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  transition: all 0.3s ease;
  position: relative;
  padding-left: 28px;
}

.content-inner blockquote::before {
  content: '"';
  position: absolute;
  left: 10px;
  top: 4px;
  font-size: 52px;
  color: rgba(130, 151, 254, 0.12);
  font-family: 'Poppins', serif;
  font-style: normal;
  font-weight: 900;
  line-height: 1;
}

.content-inner blockquote:hover {
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

.content-inner blockquote p {
  margin: 0;
}

.content-inner table {
  width: 100%;
  border-collapse: collapse;
  margin: 28px 0;
  border: 1.5px solid rgba(130, 151, 254, 0.18);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.06);
  transition: all 0.3s ease;
}

.content-inner table:hover {
  box-shadow: 0 8px 24px rgba(2, 31, 89, 0.10);
  border-color: rgba(130, 151, 254, 0.30);
}

.content-inner th,
.content-inner td {
  border: 1px solid rgba(130, 151, 254, 0.12);
  padding: 14px 16px;
  text-align: left;
  font-weight: 500;
  transition: all 0.2s ease;
}

.content-inner thead th {
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.10),
    rgba(130, 151, 254, 0.07),
    rgba(178, 255, 255, 0.04)
  );
  font-weight: 800;
  color: var(--color-dark);
  font-family: 'Poppins', sans-serif;
  letter-spacing: 0.3px;
  text-transform: uppercase;
  font-size: 12px;
}

.content-inner tbody tr {
  transition: all 0.2s ease;
}

.content-inner tbody tr:hover {
  background: rgba(178, 255, 255, 0.05);
  box-shadow: inset 1px 0 0 rgba(130, 151, 254, 0.12);
}

.content-inner tbody tr:nth-child(even) {
  background: rgba(130, 151, 254, 0.02);
}

/* ========== PÁRRAFO PRINCIPAL ========== */
.lead-paragraph {
  font-size: 16px !important;
  line-height: 1.70 !important;
  color: var(--color-text) !important;
  font-weight: 700 !important;
  margin-bottom: 24px !important;
  padding: 20px 22px !important;
  
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.06),
    rgba(130, 151, 254, 0.04),
    rgba(178, 255, 255, 0.02)
  ) !important;
  
  border-left: 5px solid var(--color-primary) !important;
  border-radius: 0 12px 12px 0 !important;
  position: relative !important;
  padding-left: 26px !important;
  
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
  
  transition: all 0.3s ease;
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.lead-paragraph::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 3px;
  height: 30px;
  background: linear-gradient(180deg, var(--color-primary), var(--color-secondary));
  border-radius: 2px;
}

.lead-paragraph:hover {
  border-left-color: var(--color-secondary);
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.10),
    rgba(130, 151, 254, 0.06),
    rgba(178, 255, 255, 0.03)
  );
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.12),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
  transform: translateX(3px);
}

.content-divider {
  width: 90px;
  height: 4px;
  background: linear-gradient(90deg, var(--color-primary), var(--color-secondary), var(--color-accent));
  margin: 32px auto !important;
  border-radius: 999px;
  box-shadow: 0 3px 12px rgba(0, 12, 151, 0.18);
  transition: all 0.3s ease;
}

.content-divider:hover {
  width: 120px;
  box-shadow: 0 4px 16px rgba(0, 12, 151, 0.25);
}

/* ========== PAGINACIÓN ========== */
.page-navigation {
  margin: 36px 0;
  text-align: center;
  animation: fadeIn 0.8s ease-out 0.5s both;
}

.page-links {
  display: flex;
  justify-content: center;
  gap: 10px;
  flex-wrap: wrap;
}

.page-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 12px;
  font-family: 'Poppins', sans-serif;
  
  background: linear-gradient(135deg, #FFFFFF, #FAFBFF);
  border: 1.5px solid rgba(130, 151, 254, 0.18);
  color: var(--color-text);
  font-weight: 800;
  text-decoration: none;
  font-size: 14px;
  letter-spacing: 0.3px;
  
  box-shadow: 
    0 4px 12px rgba(2, 31, 89, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  
  transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.page-link::before {
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

.page-link:hover::before {
  left: 100%;
}

.page-link:hover {
  transform: translateY(-3px);
  background: linear-gradient(135deg, 
    var(--color-primary),
    var(--color-dark)
  );
  border-color: rgba(0, 12, 151, 0.50);
  color: #FFFFFF;
  box-shadow: 
    0 10px 28px rgba(2, 31, 89, 0.18),
    0 4px 12px rgba(130, 151, 254, 0.10),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.page-link.current {
  background: linear-gradient(135deg, 
    var(--color-primary),
    var(--color-dark)
  );
  border-color: rgba(0, 12, 151, 0.50);
  color: #FFFFFF;
  font-weight: 900;
  box-shadow: 
    0 10px 28px rgba(2, 31, 89, 0.18),
    0 4px 12px rgba(130, 151, 254, 0.10);
}

.page-link:active {
  transform: translateY(-1px);
}

/* ========== PIE DE PÁGINA ========== */
.page-meta-footer {
  margin-top: 36px;
  padding-top: 24px;
  text-align: center;
  border-top: 1.5px solid rgba(130, 151, 254, 0.15);
  animation: fadeInUp 0.8s ease-out 0.6s both;
}

.last-updated {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: #64748B;
  font-weight: 700;
  letter-spacing: 0.3px;
  padding: 10px 18px;
  background: linear-gradient(135deg, 
    rgba(178, 255, 255, 0.10),
    rgba(130, 151, 254, 0.06)
  );
  border: 1.2px solid rgba(130, 151, 254, 0.18);
  border-radius: 12px;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  font-family: 'Inter', sans-serif;
}

.last-updated svg {
  opacity: 0.80;
  transition: all 0.3s ease;
}

.last-updated:hover {
  background: linear-gradient(135deg, 
    rgba(130, 151, 254, 0.15),
    rgba(178, 255, 255, 0.08)
  );
  border-color: rgba(130, 151, 254, 0.30);
  color: var(--color-text);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.10);
}

.last-updated:hover svg {
  transform: scale(1.2) rotate(12deg);
  opacity: 1;
}

/* ========== ERROR / NO CONTENIDO ========== */
.no-content-error {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 56px 32px;
  text-align: center;
  background: linear-gradient(135deg, #FFFFFF, #FAFBFF, #F4F7FF);
  border: 1.5px solid rgba(130, 151, 254, 0.15);
  border-radius: 18px;
  gap: 20px;
  
  box-shadow: 
    0 10px 32px rgba(2, 31, 89, 0.08),
    0 4px 16px rgba(2, 31, 89, 0.04),
    inset 0 1px 0 rgba(255, 255, 255, 0.98);
  
  color: #475569;
  font-weight: 600;
  letter-spacing: 0.3px;
  transition: all 0.3s ease;
  animation: slideInUp 0.7s ease-out;
}

.no-content-error svg {
  color: rgba(2, 31, 89, 0.20);
  transition: all 0.4s ease;
}

.no-content-error:hover svg {
  color: rgba(2, 31, 89, 0.30);
  transform: scale(1.1) rotate(-5deg);
}

.no-content-error p {
  margin: 0;
  font-size: 16px;
  color: var(--color-text);
}

/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  .enhanced-header {
    padding: 48px 24px 28px;
    margin-bottom: 28px;
  }

  .header-breadcrumbs .breadcrumbs {
    font-size: 11px;
    padding: 7px 14px;
  }

  .decoration-line {
    width: 70px;
    height: 3px;
    margin: 0 auto 12px;
  }

  .dot {
    width: 9px;
    height: 9px;
    gap: 10px;
  }

  .title-text {
    font-size: clamp(24px, 5vw, 36px);
  }

  .title-accent-line {
    width: 60% !important;
  }

  .enhanced-meta {
    gap: 12px;
  }

  .meta-item {
    padding: 8px 14px;
    gap: 9px;
    font-size: 12px;
  }

  .meta-icon {
    width: 28px;
    height: 28px;
  }

  .content-wrapper {
    padding: 28px;
  }

  .content-inner {
    font-size: 14.5px;
  }

  .content-inner h2 {
    font-size: 22px;
    margin: 28px 0 12px;
  }

  .content-inner h3 {
    font-size: 19px;
  }

  .page-link {
    width: 40px;
    height: 40px;
    font-size: 13px;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 640px) {
  .enhanced-header {
    padding: 48px 18px 24px;
    margin-bottom: 20px;
    border-radius: 14px;
  }

  .enhanced-header::before {
    height: 4px;
  }

  .enhanced-header::after {
    width: 4px;
  }

  .header-breadcrumbs {
    top: 10px;
  }

  .header-breadcrumbs .breadcrumbs {
    font-size: 10px;
    padding: 6px 12px;
    gap: 4px;
  }

  .header-decoration {
    margin: 20px 0 16px;
  }

  .decoration-line {
    width: 65px;
    height: 3px;
    margin: 0 auto 10px;
  }

  .enhanced-header:hover .decoration-line {
    width: 95px;
  }

  .dot {
    width: 8px;
    height: 8px;
    gap: 10px;
  }

  .title-text {
    font-size: clamp(22px, 6vw, 30px);
    line-height: 1.18;
  }

  .title-accent-line {
    width: 50% !important;
    height: 4px;
    margin: 16px auto 0;
  }

  .enhanced-meta {
    flex-direction: column;
    gap: 10px;
    font-size: 12px;
  }

  .meta-item {
    padding: 8px 13px;
    gap: 8px;
    width: 100%;
    justify-content: center;
  }

  .meta-icon {
    width: 26px;
    height: 26px;
  }

  .meta-icon svg {
    width: 14px;
    height: 14px;
  }

  .enhanced-content {
    border-radius: 14px;
  }

  .content-wrapper {
    padding: 20px;
  }

  .content-inner {
    font-size: 14px;
    line-height: 1.72;
  }

  .content-inner p {
    margin: 0 0 14px;
  }

  .lead-paragraph {
    font-size: 14.5px !important;
    padding: 14px 14px 14px 18px !important;
    margin-bottom: 18px !important;
    border-radius: 0 10px 10px 0 !important;
  }

  .content-divider {
    width: 75px;
    height: 3px;
    margin: 20px auto !important;
  }

  .content-inner h2 {
    font-size: 20px;
    margin: 24px 0 11px;
    padding-bottom: 9px;
  }

  .content-inner h3 {
    font-size: 17px;
    margin: 18px 0 10px;
    padding-left: 12px;
  }

  .content-inner h3::before {
    width: 3px;
    height: 18px;
  }

  .content-inner h4 {
    font-size: 15px;
    margin: 16px 0 9px;
  }

  .content-inner img {
    margin: 18px auto;
    border-radius: 10px;
  }

  .content-inner blockquote {
    margin: 18px 0;
    padding: 14px 14px 14px 20px;
    font-size: 14px;
  }

  .content-inner blockquote::before {
    font-size: 40px;
    left: 4px;
  }

  .content-inner table {
    font-size: 12px;
    margin: 18px 0;
  }

  .content-inner th,
  .content-inner td {
    padding: 10px 12px;
  }

  .page-navigation {
    margin: 24px 0;
  }

  .page-links {
    gap: 8px;
  }

  .page-link {
    width: 38px;
    height: 38px;
    font-size: 12px;
  }

  .page-meta-footer {
    margin-top: 24px;
    padding-top: 18px;
  }

  .last-updated {
    font-size: 11px;
    padding: 8px 14px;
    gap: 6px;
  }

  .last-updated svg {
    width: 11px;
    height: 11px;
  }

  .no-content-error {
    padding: 40px 20px;
    gap: 16px;
  }

  .no-content-error svg {
    width: 40px;
    height: 40px;
  }

  .no-content-error p {
    font-size: 14px;
  }
}

/* ========== EXTRA SMALL ========== */
@media (max-width: 480px) {
  .enhanced-header {
    padding: 44px 14px 20px;
  }

  .header-breadcrumbs .breadcrumbs {
    font-size: 9px;
    padding: 5px 10px;
  }

  .title-text {
    font-size: clamp(20px, 6.5vw, 26px);
  }

  .enhanced-meta {
    gap: 8px;
    font-size: 11px;
  }

  .meta-item {
    padding: 7px 11px;
  }

  .content-wrapper {
    padding: 16px;
  }

  .content-inner {
    font-size: 13.5px;
    line-height: 1.65;
  }

  .lead-paragraph {
    font-size: 13.5px !important;
    padding: 12px 12px 12px 16px !important;
  }

  .page-link {
    width: 36px;
    height: 36px;
    font-size: 11px;
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

  .enhanced-header,
  .enhanced-content,
  .meta-item,
  .page-link,
  .last-updated {
    transition: none !important;
  }

  .enhanced-header:hover::after {
    opacity: 0.3 !important;
  }

  .enhanced-header:hover .dot {
    transform: none !important;
  }

  .page-link:hover {
    transform: none !important;
  }
}

/* ========== DARK MODE (OPCIONAL) ========== */
@media (prefers-color-scheme: dark) {
  .enhanced-header {
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

  .enhanced-content {
    background: linear-gradient(135deg, 
      #1a1f3a 0%,
      #16213e 40%,
      #0f1a2e 100%
    );
  }

  .content-inner {
    color: #cbd5e1;
  }

  .meta-item,
  .badge,
  .last-updated {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(178, 255, 255, 0.15);
    color: #B2FFFF;
  }
}
</style>

<script>
(function() {
  'use strict';

  // ========== ANIMACIÓN AL SCROLL ==========
  const observerOptions = {
    threshold: 0.2,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, idx) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.style.animation = `fadeInUp 0.7s ease-out forwards`;
        }, idx * 80);
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.content-inner h2, .content-inner h3, .content-inner blockquote').forEach(el => {
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

  document.querySelectorAll('.content-inner p:not(.lead-paragraph)').forEach(p => {
    p.style.opacity = '0.7';
    p.style.transform = 'translateY(8px)';
    p.style.transition = 'all 0.6s ease-out';
    pObserver.observe(p);
  });

  // ========== ENLACES EXTERNOS ==========
  document.querySelectorAll('.content-inner a[href^="http"]').forEach(a => {
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
        document.querySelector(href).scrollIntoView({ 
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

  // ========== EFECTO HOVER EN IMAGES ==========
  document.querySelectorAll('.content-inner img').forEach(img => {
    img.addEventListener('click', function() {
      if (this.requestFullscreen) {
        this.requestFullscreen();
      }
    });
  });
})();
</script>

<?php get_footer(); ?>