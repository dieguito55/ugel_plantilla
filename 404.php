<?php
/**
 * Página 404 — Diseño Corporativo Premium UGEL
 * - Hero elegante con animaciones sutiles
 * - Búsqueda integrada con sugerencias
 * - Accesos directos pegados a la izquierda
 * - Comunicados en fila debajo de secciones populares
 */
get_header(); ?>

<section class="error-404-page" aria-labelledby="e404-title">
  <div class="wrap">
    <?php if (function_exists('ugel_breadcrumbs')) { ugel_breadcrumbs(); } ?>

    <!-- HERO CORPORATIVO -->
    <div class="e404-hero" id="e404Hero">
      <div class="e404-col visual" data-anim="from-left">
        <div class="e404-number" aria-hidden="true">
          <span>4</span><span>0</span><span>4</span>
        </div>
        <div class="e404-glow" aria-hidden="true"></div>
      </div>

      <div class="e404-col content" data-anim="from-right">
        <div class="e404-badge">Error</div>
        <h1 id="e404-title" class="e404-title">Página no encontrada</h1>
        <p class="e404-desc">
          Lo sentimos, no pudimos encontrar la página que buscas. Puede que haya sido movida, 
          eliminada o que la dirección tenga algún error.
        </p>

        <div class="e404-actions">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="e404-btn primary">
            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
              <path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
            <span>Ir al inicio</span>
          </a>
          <button onclick="history.back()" class="e404-btn secondary">
            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
              <path fill="currentColor" d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            <span>Volver atrás</span>
          </button>
        </div>

        <!-- BÚSQUEDA AVANZADA -->
        <div class="e404-search-section">
          <form class="e404-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="search-input-wrapper">
              <svg class="search-icon" viewBox="0 0 24 24" width="20" height="20">
                <path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM9.5 14A3.5 3.5 0 1 1 13 10.5 3.504 3.504 0 0 1 9.5 14Z"/>
              </svg>
              <input id="e404-search" type="search" name="s" placeholder="Buscar en el sitio UGEL..." autocomplete="on" aria-label="Buscar en el sitio">
              <button type="submit" class="search-submit" aria-label="Ejecutar búsqueda">
                <svg viewBox="0 0 24 24" width="18" height="18">
                  <path fill="currentColor" d="M5 12h11.17l-4.58-4.59L13 6l7 7-7 7-1.41-1.41L16.17 13H5z"/>
                </svg>
              </button>
            </div>
          </form>

          <div class="e404-suggestions" aria-label="Secciones populares">
            <span class="suggestions-label">Secciones populares:</span>
            <div class="suggestions-chips">
              <a href="<?php echo esc_url( get_post_type_archive_link('convocatorias') ); ?>" class="suggestion-chip">
                <svg viewBox="0 0 24 24" width="14" height="14">
                  <path fill="currentColor" d="M12 2l3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/>
                </svg>
                Convocatorias
              </a>
              <a href="<?php echo esc_url( get_post_type_archive_link('comunicados') ); ?>" class="suggestion-chip">
                <svg viewBox="0 0 24 24" width="14" height="14">
                  <path fill="currentColor" d="M20 2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4l4 4 4-4h4a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/>
                </svg>
                Comunicados
              </a>
              <a href="<?php echo esc_url( home_url('/mesa-de-partes') ); ?>" class="suggestion-chip">
                <svg viewBox="0 0 24 24" width="14" height="14">
                  <path fill="currentColor" d="M14 2H6a2 2 0 0 0-2 2v16l4-2 4 2 4-2 4 2V8l-6-6z"/>
                </svg>
                Mesa de Partes
              </a>
              <a href="<?php echo esc_url( home_url('/transparencia') ); ?>" class="suggestion-chip">
                <svg viewBox="0 0 24 24" width="14" height="14">
                  <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                Transparencia
              </a>
            </div>
          </div>
        </div>

        <?php
          $report_email = sanitize_email( get_theme_mod('ugel_email','') );
          if ($report_email):
        ?>
        <div class="e404-report">
          <svg viewBox="0 0 24 24" width="16" height="16">
            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <span>¿Encontraste un enlace roto? <a href="mailto:<?php echo esc_attr($report_email); ?>?subject=Reporte%20404&body=Hola%2C%20encontr%C3%A9%20un%20enlace%20roto:%20<?php echo urlencode( esc_url( home_url( add_query_arg( array(), $wp->request ) ) ) ); ?>">Reportar problema</a></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- CONTENIDO ADICIONAL - LAYOUT ESPECIAL -->
    <div class="e404-layout-special">
      
      <!-- ACCESOS DIRECTOS - PEGADO A LA IZQUIERDA -->
      <div class="e404-accesos-container">
        <div class="e404-section" data-anim="fade-up">
          <div class="e404-section-header">
            <h3 class="e404-section-title">Accesos directos</h3>
            <p class="e404-section-desc">Servicios más utilizados</p>
          </div>
          <?php get_template_part('template-parts/accesos-directos'); ?>
        </div>
      </div>

      <!-- CONTENIDO DERECHO - SECCIONES POPULARES + COMUNICADOS -->
      <div class="e404-content-right">
        
        <!-- ÚLTIMOS COMUNICADOS EN FILA -->
        <div class="e404-section" data-anim="fade-up">
          <div class="e404-section-header">
            <h3 class="e404-section-title">Últimos comunicados</h3>
            <p class="e404-section-desc">Mantente informado con las últimas noticias</p>
          </div>
          <?php
            if (function_exists('get_comunicados')) {
              $recent_comunicados = get_comunicados(4);
              if ($recent_comunicados):
          ?>
          <div class="e404-comunicados-fila">
            <?php foreach ($recent_comunicados as $com): ?>
            <article class="e404-comunicado-fila">
              <div class="comunicado-fila-content">
                <div class="comunicado-fila-meta">
                  <time class="comunicado-fila-date" datetime="<?php echo esc_attr(get_the_date('Y-m-d', $com)); ?>">
                    <?php echo esc_html(get_the_date('j M Y', $com)); ?>
                  </time>
                  <span class="comunicado-fila-badge">Nuevo</span>
                </div>
                <h4 class="comunicado-fila-title">
                  <a href="<?php echo esc_url(get_permalink($com)); ?>">
                    <?php echo esc_html(get_the_title($com)); ?>
                  </a>
                </h4>
                <div class="comunicado-fila-excerpt">
                  <?php 
                    $excerpt = get_the_excerpt($com);
                    echo esc_html(wp_trim_words($excerpt, 12, '...'));
                  ?>
                </div>
              </div>
              <a class="comunicado-fila-link" href="<?php echo esc_url(get_permalink($com)); ?>" aria-label="Leer comunicado completo">
                <svg viewBox="0 0 24 24" width="20" height="20">
                  <path fill="currentColor" d="M5 12h11.17l-4.58-4.59L13 6l7 7-7 7-1.41-1.41L16.17 13H5z"/>
                </svg>
              </a>
            </article>
            <?php endforeach; ?>
          </div>
          <?php endif; } ?>
        </div>

      </div>

    </div>
  </div>
</section>

<style>
/* ===== 404 CORPORATIVO PREMIUM ===== */
.error-404-page {
  --e404-padding: clamp(40px, 8vw, 80px) 0;
  --e404-gap: clamp(24px, 4vw, 48px);
  
  padding: var(--e404-padding);
  background: linear-gradient(135deg, #FAFBFF 0%, #F4F7FF 50%, #EEF3FF 100%);
  position: relative;
  overflow: hidden;
}

.error-404-page::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 20%, rgba(178, 255, 255, 0.15) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(130, 151, 254, 0.1) 0%, transparent 50%);
  pointer-events: none;
}

/* HERO SECTION */
.e404-hero {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: var(--e404-gap);
  align-items: center;
  position: relative;
  z-index: 2;
  margin-bottom: clamp(60px, 8vw, 100px);
}

.e404-col.visual {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 280px;
}

.e404-number {
  font-weight: 800;
  font-size: clamp(100px, 16vw, 240px);
  line-height: 0.8;
  letter-spacing: -0.04em;
  display: flex;
  gap: 0.05em;
  background: linear-gradient(135deg, #000C97 0%, #8297FE 50%, #B2FFFF 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  font-family: "Inter", system-ui, sans-serif;
  position: relative;
  z-index: 2;
}

.e404-number span {
  display: inline-block;
  animation: e404Float 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}

.e404-number span:nth-child(1) { animation-delay: 0.1s; }
.e404-number span:nth-child(2) { animation-delay: 0.2s; }
.e404-number span:nth-child(3) { animation-delay: 0.3s; }

@keyframes e404Float {
  from {
    opacity: 0;
    transform: translateY(30px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.e404-glow {
  position: absolute;
  width: 200px;
  height: 200px;
  background: linear-gradient(135deg, #8297FE 0%, #B2FFFF 100%);
  border-radius: 50%;
  filter: blur(60px);
  opacity: 0.3;
  animation: glowPulse 4s ease-in-out infinite alternate;
}

@keyframes glowPulse {
  from { transform: scale(1); opacity: 0.2; }
  to { transform: scale(1.1); opacity: 0.4; }
}

/* CONTENT SECTION */
.e404-col.content {
  max-width: 580px;
}

.e404-badge {
  display: inline-block;
  padding: 6px 12px;
  background: linear-gradient(135deg, #000C97, #021F59);
  color: #FFFFFF;
  font-size: 12px;
  font-weight: 700;
  border-radius: 6px;
  margin-bottom: 16px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.e404-title {
  margin: 0 0 16px;
  font-size: clamp(32px, 4vw, 48px);
  font-weight: 700;
  line-height: 1.2;
  letter-spacing: -0.02em;
  color: #0F172A;
  font-family: "Inter", "Segoe UI", system-ui, sans-serif;
}

.e404-desc {
  margin: 0 0 32px;
  font-size: clamp(16px, 1.8vw, 18px);
  line-height: 1.6;
  color: #475569;
  font-weight: 500;
}

/* BUTTONS */
.e404-actions {
  display: flex;
  gap: 16px;
  margin-bottom: 40px;
  flex-wrap: wrap;
}

.e404-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 14px 28px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 15px;
  text-decoration: none;
  border: 1.5px solid transparent;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  font-family: "Inter", system-ui, sans-serif;
  cursor: pointer;
}

.e404-btn.primary {
  background: linear-gradient(135deg, #000C97 0%, #021F59 100%);
  color: #FFFFFF;
  box-shadow: 0 8px 24px rgba(2, 31, 89, 0.2);
}

.e404-btn.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 32px rgba(2, 31, 89, 0.3);
}

.e404-btn.secondary {
  background: #FFFFFF;
  color: #000C97;
  border-color: #000C97;
  box-shadow: 0 4px 16px rgba(2, 31, 89, 0.1);
}

.e404-btn.secondary:hover {
  background: #000C97;
  color: #FFFFFF;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(2, 31, 89, 0.2);
}

/* SEARCH SECTION */
.e404-search-section {
  margin-bottom: 24px;
}

.e404-search-form {
  max-width: 480px;
  margin-bottom: 20px;
}

.search-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  background: #FFFFFF;
  border: 1.5px solid #E2E8F0;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(2, 31, 89, 0.08);
  transition: all 0.3s ease;
}

.search-input-wrapper:focus-within {
  border-color: #8297FE;
  box-shadow: 0 6px 20px rgba(130, 151, 254, 0.15);
}

.search-icon {
  position: absolute;
  left: 16px;
  color: #64748B;
}

#e404-search {
  flex: 1;
  border: none;
  outline: none;
  padding: 16px 52px 16px 48px;
  font-size: 16px;
  background: transparent;
  color: #0F172A;
}

#e404-search::placeholder {
  color: #94A3B8;
}

.search-submit {
  background: linear-gradient(135deg, #000C97, #021F59);
  border: none;
  padding: 16px 20px;
  color: #FFFFFF;
  cursor: pointer;
  transition: all 0.3s ease;
}

.search-submit:hover {
  background: linear-gradient(135deg, #021F59, #000C97);
}

/* SUGGESTIONS */
.e404-suggestions {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.suggestions-label {
  font-size: 14px;
  color: #64748B;
  font-weight: 500;
}

.suggestions-chips {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.suggestion-chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 20px;
  color: #000C97;
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(2, 31, 89, 0.05);
}

.suggestion-chip:hover {
  background: #000C97;
  color: #FFFFFF;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(2, 31, 89, 0.15);
}

/* REPORT SECTION */
.e404-report {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: #F8FAFF;
  border: 1px solid #E2E8F0;
  border-radius: 8px;
  font-size: 14px;
  color: #475569;
}

.e404-report a {
  color: #000C97;
  text-decoration: none;
  font-weight: 600;
}

.e404-report a:hover {
  text-decoration: underline;
}

/* LAYOUT ESPECIAL - ACCESOS IZQUIERDA + CONTENIDO DERECHA */
.e404-layout-special {
  display: grid;
  grid-template-columns: 380px 1fr;
  gap: 0;
  align-items: start;
  position: relative;
  z-index: 2;
  max-width: 1400px;
  margin: 0 auto;
}

/* ACCESOS DIRECTOS - PEGADO A LA IZQUIERDA */
.e404-accesos-container {
  position: sticky;
  top: 100px;
  margin-right: 40px;
  margin-left: -20px; /* Pegado a la izquierda */
}

.e404-accesos-container .e404-section {
  background: #FFFFFF;
  border-radius: 0 20px 20px 0;
  padding: clamp(24px, 4vw, 32px);
  box-shadow: 0 8px 32px rgba(2, 31, 89, 0.08);
  border: 1px solid #F1F5F9;
  border-left: none;
  width: calc(100% + 20px);
}

/* CONTENIDO DERECHO */
.e404-content-right {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

/* SECTIONS */
.e404-section {
  background: #FFFFFF;
  border-radius: 20px;
  padding: clamp(24px, 4vw, 32px);
  box-shadow: 0 8px 32px rgba(2, 31, 89, 0.08);
  border: 1px solid #F1F5F9;
}

.e404-section-header {
  margin-bottom: clamp(20px, 3vw, 28px);
}

.e404-section-title {
  margin: 0 0 8px;
  font-size: clamp(20px, 2.5vw, 24px);
  font-weight: 700;
  color: #0F172A;
  font-family: "Inter", system-ui, sans-serif;
  background: linear-gradient(135deg, #0F172A 0%, #021F59 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.e404-section-desc {
  margin: 0;
  font-size: 14px;
  color: #64748B;
  font-weight: 500;
}

/* COMUNICADOS EN FILA */
.e404-comunicados-fila {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.e404-comunicado-fila {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 20px;
  background: #FAFBFF;
  border: 1px solid #F1F5F9;
  border-radius: 12px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.e404-comunicado-fila::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 3px;
  height: 100%;
  background: linear-gradient(180deg, #B2FFFF 0%, #8297FE 50%, #000C97 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.e404-comunicado-fila:hover {
  transform: translateX(4px);
  box-shadow: 0 8px 24px rgba(2, 31, 89, 0.12);
  border-color: #8297FE;
}

.e404-comunicado-fila:hover::before {
  opacity: 1;
}

.comunicado-fila-content {
  flex: 1;
  min-width: 0;
}

.comunicado-fila-meta {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
}

.comunicado-fila-date {
  font-size: 12px;
  color: #64748B;
  font-weight: 600;
}

.comunicado-fila-badge {
  background: linear-gradient(135deg, #000C97, #021F59);
  color: #FFFFFF;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.comunicado-fila-title {
  margin: 0 0 8px;
  font-size: 16px;
  font-weight: 700;
  line-height: 1.4;
  color: #0F172A;
}

.comunicado-fila-title a {
  color: inherit;
  text-decoration: none;
}

.comunicado-fila-title a:hover {
  color: #000C97;
}

.comunicado-fila-excerpt {
  margin: 0;
  font-size: 13px;
  line-height: 1.5;
  color: #64748B;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.comunicado-fila-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 8px;
  color: #64748B;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.e404-comunicado-fila:hover .comunicado-fila-link {
  background: #000C97;
  color: #FFFFFF;
  border-color: #000C97;
  transform: translateX(2px);
}

/* ANIMATIONS */
[data-anim] {
  opacity: 0;
  transform: translate3d(0, 0, 0);
  will-change: transform, opacity;
  transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94),
              opacity 0.6s ease;
}

[data-anim="from-left"] { transform: translateX(-40px); }
[data-anim="from-right"] { transform: translateX(40px); }
[data-anim="fade-up"] { transform: translateY(30px); }

.is-inview [data-anim] {
  opacity: 1;
  transform: none;
}

/* RESPONSIVE */
@media (max-width: 1200px) {
  .e404-layout-special {
    grid-template-columns: 320px 1fr;
    gap: 30px;
  }
  
  .e404-accesos-container {
    margin-right: 30px;
    margin-left: -15px;
  }
}

@media (max-width: 1024px) {
  .e404-hero {
    grid-template-columns: 1fr;
    gap: 40px;
    text-align: center;
  }
  
  .e404-col.visual {
    min-height: 200px;
  }
  
  .e404-actions {
    justify-content: center;
  }
  
  .e404-suggestions {
    justify-content: center;
  }
  
  .e404-layout-special {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  
  .e404-accesos-container {
    position: static;
    margin: 0;
  }
  
  .e404-accesos-container .e404-section {
    border-radius: 20px;
    border-left: 1px solid #F1F5F9;
    width: 100%;
  }
}

@media (max-width: 768px) {
  .error-404-page {
    --e404-padding: clamp(32px, 6vw, 60px) 0;
  }
  
  .e404-actions {
    flex-direction: column;
    align-items: stretch;
  }
  
  .e404-suggestions {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .suggestions-chips {
    justify-content: center;
  }
  
  .e404-section {
    padding: 20px;
  }
  
  .e404-comunicado-fila {
    flex-direction: column;
    gap: 12px;
  }
  
  .comunicado-fila-link {
    align-self: flex-end;
  }
}

@media (prefers-reduced-motion: reduce) {
  .e404-number span,
  .e404-glow,
  [data-anim] {
    animation: none !important;
    transition: none !important;
    transform: none !important;
    opacity: 1 !important;
  }
}
</style>

<script>
(function(){
  const hero = document.getElementById('e404Hero');
  if(!hero) return;

  // Intersection Observer para animaciones
  const io = 'IntersectionObserver' in window ? new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
      if(entry.isIntersecting){
        hero.classList.add('is-inview');
      } else {
        hero.classList.remove('is-inview');
      }
    });
  }, {
    threshold: [0, 0.1, 0.3],
    rootMargin: '0px 0px -10% 0px'
  }) : null;

  if(io) { 
    io.observe(hero); 
  } else { 
    hero.classList.add('is-inview'); 
  }

  // Auto-focus en campo de búsqueda
  const searchInput = document.getElementById('e404-search');
  if(searchInput) {
    setTimeout(() => {
      try {
        searchInput.focus({ preventScroll: true });
      } catch(e) {}
    }, 400);
  }

  // Manejar enlaces externos
  hero.querySelectorAll('a[href^="http"]').forEach(link => {
    try {
      if(new URL(link.href).host !== location.host) {
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
      }
    } catch(e) {}
  });

  // Observar todas las secciones con animaciones
  const sections = document.querySelectorAll('[data-anim]');
  const sectionObserver = 'IntersectionObserver' in window ? new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
      if(entry.isIntersecting){
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'none';
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -5% 0px'
  }) : null;

  if(sectionObserver) {
    sections.forEach(section => sectionObserver.observe(section));
  }
})();
</script>

<?php get_footer(); ?>