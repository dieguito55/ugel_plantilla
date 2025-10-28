<?php
/**
 * Page — Páginas estáticas con control de acceso
 * Diseño Elite Corporativo UGEL
 * Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
 */

// ========== CONTROL DE ACCESO ==========
// Verificar si la página tiene acceso restringido
$page_access = get_post_meta(get_the_ID(), '_page_access_restricted', true);

if ($page_access && !current_user_can('read_private_posts')) {
    // Bloquear acceso no autorizado
    status_header(403);
    get_template_part('template-parts/403');
    exit;
}

get_header(); ?>

<section class="hub" aria-label="Contenido principal">
  <div class="wrap">
    <div class="hub-layout">

      <?php
      // ASIDE: Accesos directos (carrusel responsive)
      get_template_part('template-parts/accesos-directos');
      ?>

      <!-- MAIN -->
      <div class="hub-main">
        <?php if (have_posts()) : while (have_posts()) : the_post();
          $page_words = str_word_count( wp_strip_all_tags( get_the_content() ) );
          $page_minutes = max(1, ceil($page_words / 200));
        ?>

          <main class="single-post-content" role="main">
            
            <!-- Header Mejorado -->
            <header class="single-header enhanced-header">

              <!-- BREADCRUMBS: Centrados arriba del header -->
              <div class="header-breadcrumbs">
                <?php ugel_breadcrumbs(); ?>
              </div>

              <!-- Decoración superior -->
              <div class="header-decoration">
                <div class="decoration-line"></div>
                <div class="decoration-dots">
                  <span class="dot dot-1"></span>
                  <span class="dot dot-2"></span>
                  <span class="dot dot-3"></span>
                </div>
              </div>

              <!-- Título mejorado -->
              <h1 class="single-title enhanced-title">
                <span class="title-text"><?php the_title(); ?></span>
                <span class="title-underline"></span>
              </h1>

              <!-- Meta información -->
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
                <div class="meta-item tertiary-meta">
                  <div class="meta-icon">
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                      <path fill="currentColor" d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                  </div>
                  <span class="meta-text"><?php echo esc_html( $page_minutes ); ?> min de lectura</span>
                </div>
              </div>
            </header>

            <!-- Contenido Principal -->
            <article class="single-content enhanced-content">
              <div class="content-wrapper">
                <div class="content-inner">
                  <?php
                  // Procesamiento del contenido
                  $content = get_the_content();
                  $content = apply_filters('the_content', $content);

                  // Destacar primer párrafo
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
                  // Paginación mejorada
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

                <!-- Pie de página -->
                <footer class="page-meta-footer">
                  <time class="last-updated" datetime="<?php echo get_the_modified_date('c'); ?>">
                    Última actualización: <?php echo get_the_modified_date('j \d\e F, Y'); ?>
                  </time>
                </footer>
              </div>
            </article>

          </main>

        <?php endwhile; else: ?>
          <div class="no-content-error">
            <p>No se encontró contenido.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<style>
/* ================================
   PAGE TEMPLATE - ELITE CORPORATIVO UGEL
   Paleta: #B2FFFF, #000C97, #8297FE, #FFFFFF, #000000, #021F59
   ================================ */

/* ========== HEADER MEJORADO ========== */
.enhanced-header {
  position: relative;
  text-align: center;
  margin-bottom: 32px;
  padding: 48px 24px 28px;
  
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 45%,
    #F4F7FF 100%
  );
  
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 
    0 10px 32px rgba(2, 31, 89, 0.07),
    0 4px 16px rgba(2, 31, 89, 0.03),
    inset 0 1px 0 rgba(255, 255, 255, 0.95);
}

/* Borde superior decorativo */
.enhanced-header::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5px;
  background: linear-gradient(90deg, #B2FFFF, #8297FE, #000C97, #8297FE, #B2FFFF);
  opacity: 0.8;
}

/* Borde derecho decorativo sutil */
.enhanced-header::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 4px;
  background: linear-gradient(180deg, 
    #B2FFFF 0%,
    #8297FE 25%,
    #000C97 50%,
    #021F59 75%,
    #000C97 100%
  );
  opacity: 0.25;
}

/* ========== BREADCRUMBS ========== */
.header-breadcrumbs {
  position: absolute;
  top: 12px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 5;
  pointer-events: auto;
}

.header-breadcrumbs .breadcrumbs {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  font-size: 12px;
  line-height: 1;
  
  background: rgba(255, 255, 255, 0.90);
  border: 1px solid rgba(130, 151, 254, 0.15);
  border-radius: 12px;
  color: #0F4A7F;
  font-weight: 600;
  
  box-shadow: 
    0 4px 16px rgba(2, 31, 89, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  transition: all 0.3s ease;
}

.header-breadcrumbs .breadcrumbs:hover {
  background: rgba(255, 255, 255, 0.95);
  border-color: rgba(130, 151, 254, 0.25);
  box-shadow: 
    0 6px 20px rgba(2, 31, 89, 0.12),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.header-breadcrumbs .breadcrumbs a {
  color: #021F59;
  font-weight: 800;
  text-decoration: none;
  transition: color 0.25s ease;
}

.header-breadcrumbs .breadcrumbs a:hover {
  color: #000C97;
  text-decoration: underline;
}

.header-breadcrumbs .breadcrumbs .separator {
  color: #94A3B8;
  margin: 0 2px;
  font-weight: 600;
}

.header-breadcrumbs .breadcrumbs .current {
  color: #64748B;
  font-weight: 700;
}

.header-breadcrumbs .breadcrumbs a:first-of-type {
  position: relative;
  padding-left: 16px;
}

.header-breadcrumbs .breadcrumbs a:first-of-type::before {
  content: "";
  position: absolute;
  left: 2px;
  top: 50%;
  transform: translateY(-50%);
  width: 11px;
  height: 11px;
  mask: url("data:image/svg+xml;utf8,<svg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path fill='black' d='M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z'/></svg>") no-repeat center / contain;
  background: currentColor;
  opacity: 0.85;
}

/* ========== DECORACIÓN HEADER ========== */
.header-decoration {
  margin: 24px 0 18px;
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
  display: block;
  transition: all 0.3s ease;
}

.dot-1 { background: #B2FFFF; }
.dot-2 { background: #8297FE; }
.dot-3 { background: #000C97; }

.enhanced-header:hover .dot {
  transform: scale(1.2);
}

/* ========== TÍTULO ========== */
.enhanced-title {
  position: relative;
  margin: 0 0 20px;
  padding: 0;
}

.title-text {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 5;
  line-clamp: 5;
  overflow: hidden;
  font-size: clamp(26px, 5vw, 42px);
  font-weight: 900;
  line-height: 1.18;
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
  bottom: 6px;
  left: 50%;
  transform: translateX(-50%);
  width: 55%;
  height: 12px;
  background: linear-gradient(90deg, 
    rgba(0, 12, 151, 0.12),
    rgba(130, 151, 254, 0.15),
    rgba(178, 255, 255, 0.10)
  );
  border-radius: 6px;
  z-index: 1;
  box-shadow: 0 3px 12px rgba(0, 12, 151, 0.10);
}

/* ========== META INFORMACIÓN ========== */
.enhanced-meta {
  display: flex;
  justify-content: center;
  gap: 16px;
  flex-wrap: wrap;
  font-size: 13px;
}

.meta-item {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 16px;
  border-radius: 999px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  backdrop-filter: blur(8px);
}

.primary-meta {
  background: linear-gradient(135deg, rgba(0, 12, 151, 0.12), rgba(130, 151, 254, 0.08));
  border: 1px solid rgba(0, 12, 151, 0.18);
  font-weight: 700;
  color: #021F59;
}

.primary-meta:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.20);
}

.secondary-meta {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.72), rgba(178, 255, 255, 0.22));
  border: 1px solid rgba(130, 151, 254, 0.18);
  color: #0F4A7F;
  font-weight: 600;
}

.secondary-meta:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.16);
}

.tertiary-meta {
  background: linear-gradient(135deg, rgba(178, 255, 255, 0.16), rgba(130, 151, 254, 0.10));
  border: 1px solid rgba(130, 151, 254, 0.20);
  color: #0F4A7F;
  font-weight: 600;
}

.tertiary-meta:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(2, 31, 89, 0.16);
}

.meta-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: linear-gradient(135deg, 
    rgba(178, 255, 255, 0.15),
    rgba(130, 151, 254, 0.10)
  );
  color: #000C97;
  flex-shrink: 0;
}

/* ========== CONTENIDO PRINCIPAL ========== */
.enhanced-content {
  background: linear-gradient(135deg, 
    #FFFFFF 0%,
    #FAFBFF 45%,
    #F4F7FF 100%
  );
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: 16px;
  
  box-shadow: 
    0 10px 32px rgba(2, 31, 89, 0.07),
    0 4px 16px rgba(2, 31, 89, 0.03),
    inset 0 1px 0 rgba(255, 255, 255, 0.95);
  
  overflow: hidden;
  transition: all 0.3s ease;
}

.enhanced-content:hover {
  box-shadow: 
    0 14px 40px rgba(2, 31, 89, 0.10),
    0 6px 20px rgba(2, 31, 89, 0.05),
    inset 0 1px 0 rgba(255, 255, 255, 0.98);
}

.content-wrapper {
  padding: 32px;
}

.content-inner {
  font-size: 15px;
  line-height: 1.75;
  color: #475569;
}

.content-inner p {
  margin: 0 0 18px;
}

.content-inner h2,
.content-inner h3,
.content-inner h4 {
  margin: 28px 0 14px;
  font-weight: 800;
  color: #021F59;
  letter-spacing: -0.01em;
}

.content-inner h2 {
  font-size: 24px;
  margin-top: 32px;
}

.content-inner h3 {
  font-size: 20px;
}

.content-inner h4 {
  font-size: 17px;
}

/* ========== PÁRRAFO PRINCIPAL ========== */
.lead-paragraph {
  font-size: 16px !important;
  line-height: 1.68 !important;
  color: #0F4A7F !important;
  font-weight: 600 !important;
  margin-bottom: 24px !important;
  padding: 18px 20px !important;
  
  background: linear-gradient(135deg, 
    rgba(0, 12, 151, 0.04),
    rgba(130, 151, 254, 0.03),
    rgba(178, 255, 255, 0.02)
  ) !important;
  
  border-left: 4px solid #000C97 !important;
  border-radius: 0 10px 10px 0 !important;
  position: relative !important;
  
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
}

.content-divider {
  width: 80px;
  height: 3px;
  background: linear-gradient(90deg, #000C97, #8297FE, #B2FFFF);
  margin: 28px auto !important;
  border-radius: 999px;
  box-shadow: 0 2px 8px rgba(0, 12, 151, 0.15);
}

/* ========== PAGINACIÓN ========== */
.page-navigation {
  margin: 32px 0;
  text-align: center;
}

.page-links {
  display: flex;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
}

.page-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  
  background: #FFFFFF;
  border: 1.5px solid rgba(130, 151, 254, 0.15);
  color: #0F4A7F;
  font-weight: 700;
  text-decoration: none;
  
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: pointer;
}

.page-link:hover {
  transform: translateY(-2px);
  background: linear-gradient(135deg, #000C97, #021F59);
  border-color: rgba(0, 12, 151, 0.5);
  color: #FFFFFF;
  box-shadow: 0 6px 16px rgba(0, 12, 151, 0.20);
}

.page-link.current {
  background: linear-gradient(135deg, #000C97, #021F59);
  border-color: rgba(0, 12, 151, 0.5);
  color: #FFFFFF;
  box-shadow: 0 6px 16px rgba(0, 12, 151, 0.20);
}

/* ========== PIE DE PÁGINA ========== */
.page-meta-footer {
  margin-top: 32px;
  padding-top: 20px;
  text-align: center;
  border-top: 1px solid rgba(130, 151, 254, 0.12);
}

.last-updated {
  font-size: 12px;
  color: #64748B;
  font-weight: 600;
  letter-spacing: 0.3px;
  padding: 8px 16px;
  background: rgba(178, 255, 255, 0.08);
  border: 1px solid rgba(130, 151, 254, 0.15);
  border-radius: 12px;
  display: inline-block;
  transition: all 0.3s ease;
}

.last-updated:hover {
  background: rgba(130, 151, 254, 0.12);
  border-color: rgba(130, 151, 254, 0.25);
  color: #0F4A7F;
}

/* ========== ERROR / NO CONTENIDO ========== */
.no-content-error {
  padding: 48px 24px;
  text-align: center;
  background: linear-gradient(135deg, #FFFFFF, #FAFBFF);
  border: 1px solid rgba(130, 151, 254, 0.12);
  border-radius: 16px;
  color: #475569;
  font-weight: 600;
}


/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  .enhanced-header {
    padding-top: 56px;
    margin-bottom: 24px;
  }

  .header-breadcrumbs .breadcrumbs {
    font-size: 11px;
    padding: 6px 12px;
  }

  .enhanced-meta {
    gap: 12px;
  }

  .content-wrapper {
    padding: 24px;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 640px) {
  .enhanced-header {
    padding: 52px 16px 20px;
    margin-bottom: 18px;
    border-radius: 14px;
  }

  .header-breadcrumbs {
    top: 8px;
  }

  .header-breadcrumbs .breadcrumbs {
    font-size: 10px;
    padding: 5px 10px;
    gap: 4px;
  }

  .header-decoration {
    margin: 18px 0 12px;
  }

  .decoration-line {
    width: 60px;
    height: 2px;
  }

  .dot {
    width: 6px;
    height: 6px;
  }

  .title-text {
    font-size: clamp(22px, 5.5vw, 28px);
    line-height: 1.20;
  }

  .enhanced-meta {
    flex-direction: column;
    gap: 10px;
    font-size: 12px;
  }

  .meta-item {
    padding: 7px 14px;
    gap: 8px;
  }

  .meta-icon {
    width: 24px;
    height: 24px;
  }

  .content-wrapper {
    padding: 18px;
  }

  .lead-paragraph {
    font-size: 14.5px !important;
    padding: 14px 16px !important;
    margin-bottom: 18px !important;
  }

  .content-divider {
    margin: 20px auto !important;
  }

  .page-navigation {
    margin: 24px 0;
  }

  .page-link {
    width: 36px;
    height: 36px;
    font-size: 13px;
  }

  .page-meta-footer {
    margin-top: 24px;
    padding-top: 16px;
  }

  .last-updated {
    font-size: 11px;
    padding: 6px 14px;
  }
}

/* ========== REDUCE MOTION ========== */
@media (prefers-reduced-motion: reduce) {
  .enhanced-header,
  .enhanced-content,
  .meta-item,
  .page-link {
    transition: none !important;
  }

  .enhanced-header:hover .dot {
    transform: none !important;
  }

  .page-link:hover {
    transform: none !important;
  }
}
</style>

<?php get_footer(); ?>