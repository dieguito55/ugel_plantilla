<?php
/**
 * Bloque Contenido Visual (texto izquierda, imagen derecha)
 * Editable en Apariencia > Personalizar > Contenido Visual
 * Premium UGEL: Paleta #B2FFFF, #000C97, #8297FE, #021F59
 * Versión: full-bleed optimizado + animación repetible + altura controlada
 */

$enabled    = (bool) get_theme_mod('cv_enable', 1);
if (!$enabled) return;

$title      = get_theme_mod('cv_title', 'Potencia tu gestión educativa con soluciones simples');
$subtitle   = get_theme_mod('cv_subtitle', 'Herramientas y recursos que te ayudan a comunicar, organizar y ejecutar más rápido.');
$btn_text   = get_theme_mod('cv_btn_text', 'Conoce más');
$btn_url    = get_theme_mod('cv_btn_url', home_url('/'));
$btn_target = get_theme_mod('cv_btn_target', '_self');
$bg_color   = get_theme_mod('cv_bg', '#FAFBFF');

$img_id   = absint( get_theme_mod('cv_image_id', 0) );
$img_url  = $img_id ? wp_get_attachment_image_url($img_id, 'cv-visual') : get_template_directory_uri().'/assets/images/contenidovisual.jpg';
$img_alt  = esc_attr( get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: wp_strip_all_tags($title) );
?>
<section class="cv-banner" aria-labelledby="cv-title" style="--cv-bg: <?php echo esc_attr($bg_color); ?>;">
  <div class="wrap">
    <div class="cv-grid" id="cvBanner">
      <!-- Contenido (izquierda) -->
      <div class="cv-content" data-anim="from-left">
        <header class="cv-head">
          <h2 id="cv-title" class="cv-title" data-split>
            <?php echo wp_kses_post($title); ?>
          </h2>
          <?php if ($subtitle): ?>
            <p class="cv-sub" data-anim-ch><?php echo esc_html($subtitle); ?></p>
          <?php endif; ?>
        </header>

        <?php if ($btn_text && $btn_url): ?>
          <div class="cv-actions" data-anim-ch>
            <a class="cv-btn" href="<?php echo esc_url($btn_url); ?>" target="<?php echo esc_attr($btn_target); ?>">
              <?php echo esc_html($btn_text); ?>
              <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M5 12h11.17l-4.58-4.59L13 6l7 7-7 7-1.41-1.41L16.17 13H5z"/></svg>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Imagen (derecha) -->
      <div class="cv-media" data-anim="from-right">
        <?php if ($img_url): ?>
          <figure class="cv-figure">
            <img class="cv-img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo $img_alt; ?>" loading="lazy" decoding="async">
          </figure>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Decorativos premium -->
  <span class="cv-deco one" aria-hidden="true"></span>
  <span class="cv-deco two" aria-hidden="true"></span>
</section>

<style>
/* ========== CONTENIDO VISUAL PREMIUM ========== */

/* Colores corporativos UGEL */
:root {
  --ugel-cyan: #B2FFFF;
  --ugel-blue-dark: #000C97;
  --ugel-blue: #8297FE;
  --ugel-navy: #021F59;
  --ugel-black: #000000;
  --ugel-white: #FFFFFF;
}

/* ========== SECCIÓN BANNER ========== */
.cv-banner {
  --cv-gap: clamp(28px, 5vw, 48px);
  --cv-pad-block: clamp(32px, 6vh, 56px);
  --cv-radius-img: clamp(14px, 2vw, 20px);
  
  position: relative;
  background: var(--cv-bg, #FAFBFF);
  padding-block: var(--cv-pad-block);
  overflow: hidden;
  transition: background-color 0.3s ease;
}

/* ========== GRID LAYOUT ========== */
.cv-grid {
  display: grid;
  grid-template-columns: 1.05fr 1fr;
  gap: var(--cv-gap);
  align-items: center;
  min-height: 380px;
  max-height: 450px;
}

/* ========== CONTENIDO (IZQUIERDA) ========== */
.cv-content {
  color: var(--ugel-navy);
  z-index: 2;
  position: relative;
}

.cv-head {
  margin: 0 0 clamp(16px, 2.2vw, 24px);
}

/* ===== TÍTULO PREMIUM ===== */
.cv-title {
  margin: 0 0 12px;
  font-weight: 900;
  font-size: clamp(28px, 4.5vw, 42px);
  line-height: 1.15;
  letter-spacing: -0.025em;
  color: var(--ugel-navy);
  text-wrap: balance;
  position: relative;
  transition: all 0.35s ease;
}

/* Subrayado dinámico profesional */
.cv-title::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: -10px;
  height: 5px;
  width: 0%;
  background: linear-gradient(90deg, 
    var(--ugel-navy) 0%,
    var(--ugel-blue-dark) 25%,
    var(--ugel-blue) 50%,
    var(--ugel-blue-dark) 75%,
    var(--ugel-navy) 100%
  );
  border-radius: 3px;
  box-shadow: 0 2px 12px rgba(0, 12, 151, 0.2);
  transition: width 0.8s cubic-bezier(0.23, 1, 0.320, 1) 0.12s;
}

.is-inview .cv-title::after {
  width: 55%;
}

/* ===== SUBTÍTULO ===== */
.cv-sub {
  margin: 0;
  font-size: clamp(14px, 1.8vw, 18px);
  line-height: 1.62;
  color: #0F4A7F;
  font-weight: 500;
  letter-spacing: 0.2px;
  max-width: 58ch;
  transition: color 0.3s ease;
}

.is-inview .cv-sub {
  color: var(--ugel-blue-dark);
}

/* ===== ACCIONES ===== */
.cv-actions {
  margin-top: clamp(18px, 2.4vw, 26px);
}

.cv-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 12px 28px;
  border-radius: 12px;
  background: linear-gradient(135deg, 
    var(--ugel-blue-dark) 0%,
    var(--ugel-navy) 50%,
    var(--ugel-blue-dark) 100%
  );
  color: var(--ugel-white);
  text-decoration: none;
  font-weight: 700;
  font-size: 13px;
  letter-spacing: 0.3px;
  text-transform: uppercase;
  border: 1.2px solid rgba(178, 255, 255, 0.3);
  box-shadow: 
    0 8px 24px rgba(2, 31, 89, 0.15),
    0 4px 12px rgba(2, 31, 89, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  
  transition: all 0.45s cubic-bezier(0.23, 1, 0.320, 1);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.cv-btn::before {
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
  transition: left 0.6s cubic-bezier(0.23, 1, 0.320, 1);
}

.cv-btn svg {
  position: relative;
  z-index: 1;
  transition: transform 0.3s ease;
}

.cv-btn span {
  position: relative;
  z-index: 1;
}

.cv-btn:hover {
  transform: translateY(-3px);
  box-shadow: 
    0 16px 40px rgba(2, 31, 89, 0.22),
    0 8px 24px rgba(178, 255, 255, 0.12),
    inset 0 1px 0 rgba(255, 255, 255, 0.3);
  border-color: rgba(178, 255, 255, 0.6);
  background: linear-gradient(135deg, 
    var(--ugel-navy) 0%,
    var(--ugel-blue-dark) 50%,
    var(--ugel-navy) 100%
  );
}

.cv-btn:hover::before {
  left: 100%;
}

.cv-btn:hover svg {
  transform: translateX(3px);
}

.cv-btn:active {
  transform: translateY(-1px);
  box-shadow: 
    0 6px 16px rgba(2, 31, 89, 0.15),
    inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* ========== IMAGEN (DERECHA) ========== */
.cv-figure {
  margin: 0;
  border-radius: var(--cv-radius-img);
  overflow: hidden;
  background: linear-gradient(135deg, #E8EDFF, #DDE8FF);
  border: 1px solid rgba(130, 151, 254, 0.15);
  box-shadow: 
    0 12px 32px rgba(2, 31, 89, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
  position: relative;
  height: 100%;
}

.cv-img {
  width: 100%;
  height: 100%;
  display: block;
  object-fit: cover;
  transform: translateY(0) scale(1);
  will-change: transform;
  transition: filter 0.6s ease;
  filter: saturate(1.06) contrast(1.06) brightness(1.01);
}

.is-inview .cv-img {
  filter: saturate(1.12) contrast(1.10) brightness(1.05);
}

/* ========== DECORATIVOS PREMIUM ========== */
.cv-deco {
  position: absolute;
  pointer-events: none;
  display: block;
  border-radius: 50%;
  filter: blur(32px);
  opacity: 0.2;
}

.cv-deco.one {
  width: 240px;
  height: 240px;
  background: var(--ugel-cyan);
  top: -80px;
  left: -60px;
  animation: float1 16s ease-in-out infinite alternate;
}

.cv-deco.two {
  width: 280px;
  height: 280px;
  background: var(--ugel-blue);
  right: -80px;
  bottom: -60px;
  animation: float2 18s ease-in-out infinite alternate;
}

@keyframes float1 {
  from { transform: translate(0, 0); }
  to { transform: translate(24px, -12px); }
}

@keyframes float2 {
  from { transform: translate(0, 0); }
  to { transform: translate(-20px, 16px); }
}

/* ========== ANIMACIONES ON-SCROLL ========== */
[data-anim],
[data-anim-ch] {
  opacity: 0;
  filter: blur(6px);
  will-change: transform, opacity, filter;
  transition:
    transform 0.85s cubic-bezier(0.23, 1, 0.320, 1),
    opacity 0.6s ease,
    filter 0.6s ease;
}

[data-anim="from-left"] {
  transform: translateX(-60px) scale(0.98);
}

[data-anim="from-right"] {
  transform: translateX(60px) scale(0.98);
}

[data-anim-ch] {
  transform: translateY(28px) scale(0.98);
}

.is-inview [data-anim],
.is-inview [data-anim-ch] {
  opacity: 1;
  transform: translateX(0) translateY(0) scale(1);
  filter: blur(0);
}

/* Escalonado fino */
.is-inview .cv-sub[data-anim-ch] {
  transition-delay: 0.16s;
}

.is-inview .cv-actions[data-anim-ch] {
  transition-delay: 0.28s;
}

/* ========== RESPONSIVE TABLET ========== */
@media (max-width: 1024px) {
  .cv-grid {
    grid-template-columns: 1fr;
    gap: clamp(20px, 4vw, 32px);
    min-height: unset;
    max-height: unset;
  }

  .cv-content {
    order: 1;
  }

  .cv-media {
    order: 2;
  }

  [data-anim="from-left"],
  [data-anim="from-right"] {
    transform: translateY(30px) scale(0.98);
  }

  .cv-figure {
    max-height: 300px;
  }
}

/* ========== RESPONSIVE MOBILE ========== */
@media (max-width: 640px) {
  .cv-banner {
    --cv-pad-block: clamp(24px, 5vh, 40px);
  }

  .cv-title {
    font-size: clamp(22px, 5.5vw, 32px);
    line-height: 1.2;
  }

  .cv-sub {
    font-size: clamp(13px, 1.6vw, 15px);
    line-height: 1.55;
  }

  .cv-btn {
    padding: 11px 22px;
    font-size: 12px;
  }

  .cv-figure {
    max-height: 280px;
  }

  .cv-deco.one {
    width: 180px;
    height: 180px;
  }

  .cv-deco.two {
    width: 200px;
    height: 200px;
  }
}
/* ========== REDUCE MOTION ========== */
@media (prefers-reduced-motion: reduce) {
  [data-anim],
  [data-anim-ch] {
    transition: none !important;
    transform: none !important;
    opacity: 1 !important;
    filter: none !important;
  }

  .cv-deco.one,
  .cv-deco.two {
    animation: none !important;
  }

  .cv-title::after {
    transition: none !important;
    width: 55% !important;
  }
}
</style>

<script>
(function() {
  const section = document.getElementById('cvBanner')?.closest('.cv-banner');
  if (!section) return;

  // Observador de intersección (repetible)
  const io = 'IntersectionObserver' in window ? new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        section.classList.add('is-inview');
      } else {
        section.classList.remove('is-inview');
      }
    });
  }, {
    threshold: [0, 0.2, 0.6],
    rootMargin: '0px 0px -10% 0px'
  }) : null;

  if (io) {
    io.observe(section);
  } else {
    section.classList.add('is-inview');
  }

  // Parallax suave en imagen
  const img = section.querySelector('.cv-img');
  function parallax() {
    if (!img) return;
    const rect = section.getBoundingClientRect();
    const vh = window.innerHeight || document.documentElement.clientHeight;
    const vis = Math.min(vh, Math.max(0, vh - rect.top));
    const prog = Math.min(1, Math.max(0, vis / (vh + rect.height)));
    const y = (prog * 16) - 8;
    img.style.transform = 'translateY(' + y.toFixed(1) + 'px) scale(1.02)';
  }

  window.addEventListener('scroll', parallax, { passive: true });
  window.addEventListener('resize', parallax, { passive: true });
  parallax();
})();
</script>