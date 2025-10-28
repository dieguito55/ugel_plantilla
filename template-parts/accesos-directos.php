<?php
/**
 * Bloque: Accesos de preferencia (premium + carrusel móvil)
 * - Desktop: grid en columna
 * - Móvil (<=1024px): carrusel con autoplay cada 2s y flechas
 * Diseño corporativo premium para UGEL
 */
$accesos = ugel_get_accesos(8);
?>

<aside class="hub-aside ax-aside" aria-labelledby="ax-heading">
  <header class="ax-header">
    <div class="ax-header__inner">
      <svg class="ax-header__icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path fill="currentColor" d="M12,2C6.48,2,2,6.48,2,12s4.48,10,10,10s10-4.48,10-10S17.52,2,12,2z M16.36,14.3c-0.43,1.22-1.43,2.2-2.65,2.63 c-1.22,0.43-2.54,0.27-3.63-0.43c-0.26-0.17-0.31-0.53-0.13-0.79c0.17-0.26,0.53-0.31,0.79-0.13c0.85,0.56,1.87,0.69,2.83,0.36 c0.96-0.34,1.71-1.09,2.05-2.05c0.34-0.96,0.21-1.98-0.36-2.83c-0.17-0.26-0.12-0.62,0.13-0.79c0.26-0.17,0.62-0.12,0.79,0.13 C16.63,11.76,16.79,13.08,16.36,14.3z"/>
      </svg>
      <div class="ax-header__text">
        <p class="ax-kicker">Servicios UGEL El Collao</p>
        <h2 id="ax-heading" class="ax-heading">Accesos de Preferencia</h2>
        <p class="ax-header__desc">Acceso rápido a los servicios más utilizados</p>
      </div>
    </div>
  </header>

  <div class="ax-carousel" data-interval="2000" role="region" aria-roledescription="Carrusel" aria-labelledby="ax-heading" tabindex="0">
    <button class="ax-nav prev" type="button" aria-label="Anterior">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>

    <div class="ax-rail">
      <?php if ($accesos): foreach ($accesos as $ax):
        $url    = get_post_meta($ax->ID, '_ax_url', true) ?: get_permalink($ax);
        $target = get_post_meta($ax->ID, '_ax_target', true) ?: '_self';
        $badge  = get_post_meta($ax->ID, '_ax_badge', true);
        $color  = get_post_meta($ax->ID, '_ax_color', true) ?: '#09a19e';
        $ttl    = get_the_title($ax);
        $sub    = has_excerpt($ax) ? get_the_excerpt($ax) : '';
        $logo   = get_the_post_thumbnail_url($ax->ID, 'acceso_logo') ?: get_the_post_thumbnail_url($ax->ID, 'large');
        $style  = '--ax:' . esc_attr($color) . ';';
      ?>
        <a class="ax-card" href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>"
           aria-label="<?php echo esc_attr($ttl); ?>" style="<?php echo esc_attr($style); ?>">
          <div class="ax-card__inner">
            <div class="ax-head">
              <?php if ($badge): ?><span class="ax-badge"><?php echo esc_html($badge); ?></span><?php endif; ?>
              <h3 class="ax-title"><?php echo esc_html($ttl); ?></h3>
              <?php if ($sub): ?><p class="ax-sub"><?php echo esc_html($sub); ?></p><?php endif; ?>
            </div>
            <div class="ax-media" aria-hidden="true">
              <?php if ($logo): ?>
                <img src="<?php echo esc_url($logo); ?>" alt="" loading="lazy" decoding="async">
              <?php else: ?>
                <div class="ax-placeholder">
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 3.5C14.8 3.4 14.6 3.3 14.3 3.3C14 3.3 13.8 3.4 13.6 3.5L7 7V9C7 9.6 7.4 10 8 10H9V16C9 16.6 9.4 17 10 17H14V10H16V17H20C20.6 17 21 16.6 21 16V10H22C22.6 10 23 9.6 23 9Z" fill="currentColor"/>
                  </svg>
                </div>
              <?php endif; ?>
            </div>
            <div class="ax-card__hover">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>
        </a>
      <?php endforeach; else: ?>
        <?php
        $fallback = array(
          array('t' => 'Campus Virtual',   's' => 'Aulas, tareas y asistencia', 'img' => get_template_directory_uri().'/assets/images/logos/campus.svg',  'url'=>home_url('/campus'),   'badge'=>'Oficial', 'c'=>'#5A4EA6'),
          array('t' => 'Trámites en línea','s' => 'Mesa de Partes y consultas', 'img' => get_template_directory_uri().'/assets/images/logos/tramites.svg','url'=>home_url('/tramites'), 'badge'=>'Atención', 'c'=>'#09a19e'),
          array('t' => 'SIDAGE',           's' => 'Gestión administrativa',     'img' => get_template_directory_uri().'/assets/images/logos/sidage.svg',  'url'=>home_url('/sidage'),   'badge'=>'Sistema', 'c'=>'#FA863D'),
          array('t' => 'Mi Boleta',        's' => 'Consulta de haberes',        'img' => get_template_directory_uri().'/assets/images/logos/boleta.svg',  'url'=>home_url('/boleta'),   'badge'=>'Nuevo',   'c'=>'#0BA7A4'),
        );
        foreach($fallback as $f): ?>
          <a class="ax-card" href="<?php echo esc_url($f['url']); ?>" style="--ax:<?php echo esc_attr($f['c']); ?>">
            <div class="ax-card__inner">
              <div class="ax-head">
                <span class="ax-badge"><?php echo esc_html($f['badge']); ?></span>
                <h3 class="ax-title"><?php echo esc_html($f['t']); ?></h3>
                <p class="ax-sub"><?php echo esc_html($f['s']); ?></p>
              </div>
              <div class="ax-media" aria-hidden="true">
                <img src="<?php echo esc_url($f['img']); ?>" alt="" decoding="async">
              </div>
              <div class="ax-card__hover">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                  <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <button class="ax-nav next" type="button" aria-label="Siguiente">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    
    <div class="ax-pagination" aria-hidden="true"></div>
  </div>
</aside>

<style>
/* Línea vertical gruesa a la derecha del aside */
.ax-aside {
  position: sticky; 
  top: 96px;
  padding-right: 20px; 
  margin-right: 16px;
  background: transparent; 
  max-width:100%;
  z-index: 10;
  
  /* Línea vertical gruesa y visible */
  border-right: 4px solid;
  border-image: linear-gradient(180deg, 
    var(--celeste-light) 0%, 
    var(--accent) 25%, 
    var(--navy-dark) 50%, 
    var(--accent) 75%, 
    var(--celeste-light) 100%) 1;
  
  /* Sombra para destacar la línea */
  box-shadow: 8px 0 20px -8px rgba(0, 12, 151, 0.15);
}

/* Efecto de brillo en la línea */
.ax-aside::after {
  content: '';
  position: absolute;
  top: 0;
  right: -4px;
  width: 4px;
  height: 100%;
  background: linear-gradient(180deg,
    rgba(178, 255, 255, 0.8) 0%,
    rgba(130, 151, 254, 0.6) 25%,
    rgba(0, 12, 151, 0.4) 50%,
    rgba(130, 151, 254, 0.6) 75%,
    rgba(178, 255, 255, 0.8) 100%);
  z-index: 2;
  pointer-events: none;
}

/* En móvil quitamos la línea */
@media (max-width:1024px){
  .ax-aside{ 
    position: static; 
    border-right: none; 
    padding-right: 0; 
    margin-right: 0; 
    overflow-x: hidden; 
    padding-bottom: var(--sp-3);
    box-shadow: none;
  }
  
  .ax-aside::after {
    display: none;
  }
}

/* Ajustes responsivos para la línea */
@media (min-width: 1025px) and (max-width: 1280px){
  .ax-aside{ 
    padding-right: 16px; 
    margin-right: 12px; 
    border-right-width: 3px;
  }
  
  .ax-aside::after {
    right: -3px;
    width: 3px;
  }
}

@media (min-width: 1680px){
  .ax-aside{
    max-width: 380px;
    border-right-width: 5px;
  }
  
  .ax-aside::after {
    right: -5px;
    width: 5px;
  }
}

/* El resto del CSS se mantiene igual */
.ax-aside, .ax-aside * { box-sizing: border-box; }
.ax-aside { 
  font-family: var(--font-sans); 
  color: var(--g-800); 
  font-kerning: normal;
  text-rendering: optimizeLegibility;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.ax-aside img, .ax-aside svg { display:block; max-width:100%; height:auto; }
.ax-aside a { color: inherit; text-decoration: none; }
.ax-aside :focus-visible{ 
  outline: 2px solid var(--accent); 
  outline-offset: 3px; 
  box-shadow: var(--ring); 
  border-radius: var(--r-sm);
}
@media (prefers-reduced-motion: reduce){ .ax-aside *{ transition:none !important; animation: none !important; } }

.ax-header{
  margin: 0 0 var(--sp-4);
  padding: 0 0 var(--sp-3);
  border-bottom: 1px solid var(--border-light);
  background: linear-gradient(180deg, transparent 0%, color-mix(in srgb, var(--accent) 6%, #fff) 100%);
  position: relative;
}
.ax-header::after{
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 60px;
  height: 2px;
  background: var(--gradient-accent);
  border-radius: var(--r-pill);
}
.ax-header__inner{ 
  display: flex; 
  align-items: flex-start; 
  gap: var(--sp-3); 
}
.ax-header__icon{ 
  color: var(--navy-dark); 
  flex: 0 0 24px; 
  filter: drop-shadow(0 2px 4px rgba(2, 31, 89, 0.1));
}
.ax-header__text{
  flex: 1;
}
.ax-kicker{
  margin:0 0 var(--sp-1); 
  font-size: var(--fs--1); 
  line-height:1.2; 
  color: var(--navy-dark);
  letter-spacing:.08em; 
  text-transform: uppercase; 
  font-weight: 700;
  background: var(--gradient-accent);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.ax-heading{
  margin:0 0 var(--sp-1); 
  color: var(--g-900); 
  font-weight: 800;
  letter-spacing:-0.02em; 
  line-height:1.1;
  font-size: clamp(1.24rem, 1vw + 1rem, 1.6rem);
  background: linear-gradient(135deg, var(--g-900) 0%, var(--navy-dark) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.ax-header__desc{
  margin: 0;
  font-size: var(--fs--1);
  color: var(--g-600);
  line-height: 1.4;
  font-weight: 500;
}

.hub-layout{ --aside: 360px; }

.ax-carousel{ 
  position:relative; 
  inline-size:100%; 
  contain: layout style paint;
}
.ax-rail{ 
  display:grid; 
  grid-template-columns:1fr; 
  gap: var(--sp-3); 
  inline-size:100%; 
  min-width:0; 
}

@media (max-width:1024px){
  .ax-rail{
    display:flex; 
    overflow-x:auto; 
    gap:0; 
    padding-inline: 16px;
    scroll-snap-type:x mandatory; 
    scroll-padding-inline:16px;
    -webkit-overflow-scrolling:touch; 
    overscroll-behavior-inline:contain;
    scrollbar-width: none;
  }
  .ax-rail::-webkit-scrollbar{ display:none; }
  .ax-card{ 
    flex:0 0 calc(100% - 32px); 
    max-width: calc(100% - 32px);
    scroll-snap-align:start; 
    margin-right: var(--sp-3);
  }
  .ax-card:last-child{ margin-right: 16px; }
}

@media (max-width:1024px){
  .ax-nav{
    position:absolute; 
    top:50%; 
    transform:translateY(-50%);
    inline-size:44px; 
    block-size:44px; 
    border-radius: var(--r-md);
    border: 1px solid rgba(255,255,255,0.2);
    background: var(--gradient-primary);
    color: var(--white); 
    font-size:20px; 
    font-weight:600;
    display:grid; 
    place-items:center; 
    z-index:20;
    box-shadow: var(--sh-lg), inset 0 1px 0 rgba(255,255,255,0.3);
    cursor:pointer;
    transition: all var(--t-2) var(--ease);
    backdrop-filter: blur(12px);
    opacity: 0.95;
  }
  .ax-nav:hover{ 
    transform: translateY(-50%) scale(1.08); 
    box-shadow: var(--sh-lg), inset 0 1px 0 rgba(255,255,255,0.4);
    opacity: 1;
  }
  .ax-nav:active{ transform: translateY(-50%) scale(0.96); }
  .ax-nav.prev{ inset-inline-start:8px; }
  .ax-nav.next{ inset-inline-end:8px; }
  
  .ax-nav svg {
    stroke: currentColor;
    filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
  }
}
@media (min-width:1025px){ .ax-nav{ display:none; } }

.ax-pagination{
  display: none;
  justify-content: center;
  gap: var(--sp-2);
  margin-top: var(--sp-4);
  padding: var(--sp-2);
}
@media (max-width:1024px){
  .ax-pagination{
    display: flex;
  }
}
.ax-dot{
  width: 8px;
  height: 8px;
  border-radius: var(--r-pill);
  background: var(--g-300);
  cursor: pointer;
  transition: all var(--t-1) var(--ease);
}
.ax-dot:hover{
  background: var(--g-400);
  transform: scale(1.2);
}
.ax-dot.active{
  background: var(--navy-dark);
  transform: scale(1.3);
}

.ax-card{
  --ax: var(--accent);
  --bd: color-mix(in srgb, var(--ax) 15%, var(--border));
  --bg: var(--gradient-card);

  display: block;
  color: var(--g-800);
  background: var(--bg);
  border: 1px solid var(--bd);
  border-radius: var(--r-md);
  box-shadow: var(--sh-sm);
  transition: all var(--t-2) var(--ease);
  will-change: transform;
  position: relative; 
  overflow: hidden;
  contain: layout style paint;
}

.ax-card__inner{
  display: grid; 
  grid-template-rows: auto 1fr;
  gap: var(--sp-3);
  padding: var(--sp-4);
  position: relative;
  z-index: 2;
}

.ax-card::before{
  content:""; 
  position:absolute; 
  inset:0 0 auto 0; 
  height:4px;
  background: var(--gradient-accent);
  opacity: 0.9;
  z-index: 3;
}

.ax-card::after{
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, 
    transparent, 
    rgba(255,255,255,0.4), 
    transparent);
  transition: left var(--t-3) var(--ease);
  z-index: 1;
}

.ax-card:hover{
  transform: translateY(-4px);
  border-color: color-mix(in srgb, var(--ax) 30%, var(--border));
  box-shadow: var(--sh-md), var(--sh-inner);
  background: var(--gradient-hover);
}

.ax-card:hover::after{
  left: 100%;
}

.ax-card:active{ 
  transform: translateY(-2px) scale(0.998); 
}

.ax-card:focus-visible{
  outline: 2px solid var(--accent);
  outline-offset: 3px;
  box-shadow: var(--ring), var(--sh-sm);
  border-radius: calc(var(--r-md) + 1px);
}

.ax-card__hover{
  position: absolute;
  top: var(--sp-3);
  right: var(--sp-3);
  width: 32px;
  height: 32px;
  border-radius: var(--r-pill);
  background: var(--gradient-primary);
  color: var(--white);
  display: flex;
  align-items: center;
  justify-content: center;
  transform: translateX(8px) scale(0.9);
  opacity: 0;
  transition: all var(--t-2) var(--ease-bounce);
  box-shadow: var(--sh-sm);
  z-index: 4;
}

.ax-card:hover .ax-card__hover{
  transform: translateX(0) scale(1);
  opacity: 1;
}

.ax-card__hover svg{
  width: 16px;
  height: 16px;
  stroke: currentColor;
}

.ax-head{ 
  display:grid; 
  gap: var(--sp-2); 
  align-content:start; 
  min-width:0; 
  z-index: 2;
}
.ax-badge{
  display:inline-block; 
  width:max-content;
  background: var(--gradient-accent);
  color: var(--navy-dark);
  font: 700 10px/1 var(--font-sans);
  letter-spacing:.05em; 
  text-transform: uppercase;
  padding: 4px 10px; 
  border-radius: var(--r-pill);
  border: 1px solid rgba(255,255,255,0.6);
  box-shadow: 0 2px 8px rgba(2, 31, 89, 0.12), var(--sh-inner);
  backdrop-filter: blur(4px);
}

.ax-title{
  margin:0; 
  color: var(--g-900);
  font-weight: 700; 
  line-height: 1.2; 
  letter-spacing:-0.01em;
  font-size: clamp(15px, 1.25vw, 17px);
  display: -webkit-box; 
  -webkit-box-orient: vertical; 
  -webkit-line-clamp: 2; 
  overflow: hidden;
  text-wrap: balance; 
  hyphens:auto;
  transition: color var(--t-1) var(--ease);
}
.ax-card:hover .ax-title{ 
  color: var(--navy-dark);
  background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-deep) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.ax-sub{
  margin:0; 
  font-size: 12px; 
  font-weight: 500;
  color: color-mix(in srgb, var(--g-900) 64%, var(--g-600));
  line-height: 1.4;
  display: -webkit-box; 
  -webkit-box-orient: vertical; 
  -webkit-line-clamp: 2; 
  overflow: hidden;
  transition: color var(--t-1) var(--ease);
}
.ax-card:hover .ax-sub{ 
  color: color-mix(in srgb, var(--g-900) 78%, var(--g-600)); 
}

.ax-media{
  block-size: clamp(60px, 12vw, 84px);
  padding: 0; /* Eliminamos el padding */
  border-radius: var(--r-sm);
  display:flex; 
  align-items: center; /* Mantenemos center vertical */
  justify-content: center;
  background: linear-gradient(135deg, var(--celeste-50), var(--celeste-100));
  border: 1px solid color-mix(in srgb, var(--ax) 20%, var(--g-200));
  box-shadow: var(--sh-inner), 0 2px 8px rgba(2, 31, 89, 0.06);
  overflow:hidden; 
  position:relative;
  transition: all var(--t-2) var(--ease);
  z-index: 2;
}

.ax-media::before{
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--gradient-accent);
  opacity: 0;
  transition: opacity var(--t-2) var(--ease);
  border-radius: inherit;
}

.ax-media img{
  width: auto; /* Ancho automático para mantener proporciones */
  height: 100%; /* Ocupa toda la ALTURA del contenedor */
  max-width: none; /* Eliminamos restricción de ancho máximo */
  max-height: 100%; /* Mantenemos restricción de altura */
  object-fit: contain; /* Mantenemos la imagen completa sin recortar */
  object-position: center;
  image-rendering: auto; 
  transform: translateZ(0);
  transition: all var(--t-2) var(--ease);
  position: relative;
  z-index: 2;
  filter: brightness(1) saturate(1) contrast(1);
}


.ax-card:hover .ax-media img{ 
  filter: brightness(1.05) saturate(1.1) contrast(1.02);
  transform: scale(1.05); /* Aumentamos ligeramente el scale */
}

.ax-card:hover .ax-media::before{
  opacity: 0.03;
}

.ax-card:hover .ax-media img{ 
  filter: brightness(1.05) saturate(1.1) contrast(1.02);
  transform: scale(1.03);
}

.ax-placeholder{
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%; /* Ocupa toda la altura */
  font-weight: 700; 
  font-size: 11px; 
  letter-spacing: 1.2px;
  color: color-mix(in srgb, var(--accent) 50%, var(--g-500));
  position: relative;
  z-index: 2;
}

.ax-placeholder svg{
  width: auto; /* Ancho automático */
  height: 80%; /* 80% de la altura del contenedor */
  max-height: 100%;
  color: color-mix(in srgb, var(--accent) 40%, var(--g-400));
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.ax-card {
  animation: fadeInUp 0.6s var(--ease) both;
}

.ax-card:nth-child(1) { animation-delay: 0.1s; }
.ax-card:nth-child(2) { animation-delay: 0.2s; }
.ax-card:nth-child(3) { animation-delay: 0.3s; }
.ax-card:nth-child(4) { animation-delay: 0.4s; }
.ax-card:nth-child(5) { animation-delay: 0.5s; }
.ax-card:nth-child(6) { animation-delay: 0.6s; }
.ax-card:nth-child(7) { animation-delay: 0.7s; }
.ax-card:nth-child(8) { animation-delay: 0.8s; }

@media (max-width: 480px){
  .ax-card__inner{
    padding: var(--sp-3);
    gap: var(--sp-2);
  }
  
  .ax-media{
    block-size: 56px;
    padding: 10px;
  }
  
  .ax-header__inner{
    gap: var(--sp-2);
  }
}

@media (min-width: 1025px) and (max-width: 1280px){
  .ax-aside{ 
    padding-right: 16px; 
    margin-right: 12px; 
  }
}

@media (min-width: 1680px){
  .ax-aside{
    max-width: 380px;
  }
}
</style>

<script>
(function(){
  const carousel = document.querySelector('.ax-carousel');
  if(!carousel) return;

  const rail  = carousel.querySelector('.ax-rail');
  const prev  = carousel.querySelector('.ax-nav.prev');
  const next  = carousel.querySelector('.ax-nav.next');
  const pagination = carousel.querySelector('.ax-pagination');
  const cards = Array.from(rail.querySelectorAll('.ax-card'));
  const imgs  = Array.from(rail.querySelectorAll('.ax-media img'));

  if (!rail || cards.length === 0) return;

  const interval = parseInt(carousel.dataset.interval || '2000', 10);
  const isMobile = () => window.matchMedia('(max-width:1024px)').matches;

  let positions = [];
  let timer = null;
  let currentIdx = 0;

  function computePositions(){
    positions = cards.map(el => Math.round(el.offsetLeft - rail.offsetLeft));
    snapTo(currentIndex(), false);
    updatePagination();
  }

  function currentIndex(){
    const x = rail.scrollLeft;
    let best = 0, bestDelta = Infinity;
    positions.forEach((p,i)=>{ const d = Math.abs(p - x); if(d < bestDelta){ bestDelta = d; best = i; } });
    currentIdx = best;
    return best;
  }

  function snapTo(i, smooth=true){
    if (!positions.length) return;
    const clamped = ( (i % positions.length) + positions.length ) % positions.length;
    rail.scrollTo({ left: positions[clamped], behavior: smooth ? 'smooth' : 'auto' });
    currentIdx = clamped;
    updatePagination();
  }

  function updatePagination(){
    if (!pagination || !isMobile()) return;
    pagination.innerHTML = '';
    cards.forEach((_, i) => {
      const dot = document.createElement('span');
      dot.className = `ax-dot ${i === currentIdx ? 'active' : ''}`;
      dot.addEventListener('click', () => snapTo(i));
      pagination.appendChild(dot);
    });
  }

  function nextSlide(){ if(!isMobile()) return; snapTo(currentIdx + 1); }
  function prevSlide(){ if(!isMobile()) return; snapTo(currentIdx - 1); }

  function start(){ if(timer) return; timer = setInterval(()=>{ if(isMobile()) nextSlide(); }, interval); }
  function stop(){ if(timer){ clearInterval(timer); timer = null; } }

  prev?.addEventListener('click', ()=>{ stop(); prevSlide(); start(); });
  next?.addEventListener('click', ()=>{ stop(); nextSlide(); start(); });

  carousel.addEventListener('keydown', (e)=>{
    if(!isMobile()) return;
    if(e.key === 'ArrowLeft'){ e.preventDefault(); stop(); prevSlide(); start(); }
    if(e.key === 'ArrowRight'){ e.preventDefault(); stop(); nextSlide(); start(); }
  });

  rail.addEventListener('touchstart', stop, {passive:true});
  rail.addEventListener('touchend',   ()=> setTimeout(start, 400), {passive:true});
  rail.addEventListener('mouseenter', stop);
  rail.addEventListener('mouseleave', start);

  window.addEventListener('resize', computePositions);
  window.addEventListener('load', computePositions);
  document.addEventListener('DOMContentLoaded', computePositions);
  imgs.forEach(img=>{ img.complete ? computePositions() : img.addEventListener('load', computePositions, {once:true}); });

  start();
})();
</script>