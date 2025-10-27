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