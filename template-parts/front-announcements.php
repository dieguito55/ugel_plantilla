<?php
if (!defined('ABSPATH')) exit;

$anuncios = new WP_Query([
  'post_type'      => 'anuncios_portada',
  'post_status'    => 'publish',
  'posts_per_page' => 5,
  'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
]);

if ($anuncios->have_posts()):
?>
<section class="ugel-announcements" aria-label="Anuncios destacados UGEL" role="region">
  <style>
    :root{
      /* Paleta UGEL */
      --primary:#000C97;
      --secondary:#021F59;
      --accent:#8297FE;
      --cyan:#B2FFFF;
      --white:#FFFFFF;
      --ink:#0F172A;

      /* Altura uniforme y responsive (clamp) */
      /* Se ajusta a la pantalla: mínimo 200–220px, ideal en vh, tope 420px */
      --img-h: clamp(200px, 50vh, 420px);

      --shadow-lg:0 18px 50px rgba(2,31,89,.16);
      --overlay-grad: linear-gradient(180deg, rgba(0,0,0,.00) 0%, rgba(0,0,0,.08) 52%, rgba(0,0,0,.14) 100%);
      --overlay-glow: radial-gradient(520px 320px at 18% 22%, rgba(178,255,255,.04), transparent 62%);
    }

    *{box-sizing:border-box}

    /* Fondo aún más transparente y con blur moderado */
    .ugel-announcements{
      position:fixed; inset:0; z-index:9999;
      display:grid; place-items:center;
      padding:8px;
      background:linear-gradient(135deg, rgba(2,31,89,.10) 0%, rgba(0,12,151,.08) 100%);
      backdrop-filter:blur(4px);
      animation:fadeBg .22s ease-out;
    }
    @keyframes fadeBg{from{opacity:0;backdrop-filter:blur(0)}to{opacity:1;backdrop-filter:blur(4px)}}
    .ugel-announcements[hidden]{display:none!important}

    /* Contenedor se “encoge” a la imagen, pero no excede el viewport */
    .ugel-announcements__container{
      position:relative;
      display:inline-block;       /* shrink-to-fit → se ajusta al ancho real de la imagen */
      max-width:96vw;             /* nunca más ancho que el viewport */
      background:var(--white);
      border-radius:10px;
      overflow:hidden;
      box-shadow:var(--shadow-lg);
      line-height:0;              /* elimina espacios colapsados alrededor de la img */
    }

    /* Botón cerrar (pequeño) */
    .ugel-announcements__close-fab{
      position:absolute; top:6px; right:6px; z-index:6;
      width:24px; height:24px; border:none; border-radius:6px;
      display:grid; place-items:center;
      background:rgba(255,255,255,.70);
      color:var(--secondary); font-size:14px; cursor:pointer;
      backdrop-filter:blur(2px); transition:.16s ease;
    }
    .ugel-announcements__close-fab:hover,
    .ugel-announcements__close-fab:focus-visible{
      background:#fff; color:var(--primary);
      outline:2px solid #e0e7ff; outline-offset:2px;
      transform:scale(1.05);
    }

    /* Pista y slides */
    .ugel-announcements__track{ position:relative; }
    .ugel-announcements__slide{
      position:absolute; inset:0;
      opacity:0; visibility:hidden; transform:translateY(10px);
      transition:opacity .26s cubic-bezier(.34,1.56,.64,1), transform .26s cubic-bezier(.34,1.56,.64,1), visibility .26s;
      white-space:nowrap; /* evita saltos que agreguen espacio */
    }
    .ugel-announcements__slide.is-active{ position:static; opacity:1; visibility:visible; transform:none; }

    /* Media: ALTURA IGUAL para todas, ancho por proporción; no desborda el viewport */
    .ugel-announcements__media{
      position:relative; display:inline-flex; justify-content:center; align-items:center;
      text-decoration:none;
    }
    .ugel-announcements__media img{
      display:block;
      height:var(--img-h);   /* ← altura uniforme */
      width:auto;            /* ← ancho según relación de aspecto */
      max-width:96vw;        /* ← tope: nunca más ancho que el viewport */
    }

    /* Overlay sutil (más transparente) */
    .ugel-announcements__media::before{
      content:""; position:absolute; inset:0; z-index:2; pointer-events:none;
      background: var(--overlay-grad), var(--overlay-glow);
    }

    /* Texto sobre imagen (compacto responsive) */
    .ugel-announcements__media-caption{
      position:absolute; inset:0; z-index:3;
      display:flex; flex-direction:column; justify-content:flex-end; align-items:center; gap:4px;
      padding:10px 10px; color:#fff; text-align:center;
    }
    .ugel-announcements__label{
      display:inline-block; padding:3px 7px; border-radius:999px;
      background:rgba(130,151,254,.10); border:1px solid rgba(130,151,254,.22);
      color:#E6F0FF; font:800 9px/1 'Inter',system-ui,sans-serif; text-transform:uppercase; letter-spacing:.7px;
      backdrop-filter:blur(1px);
    }
    .ugel-announcements__headline{
      margin:0; max-width:min(70ch, 92%);
      font:800 clamp(12px,2.6vw,18px)/1.25 'Inter', system-ui, sans-serif; letter-spacing:-.1px;
      text-shadow:0 2px 10px rgba(0,0,0,.28);
    }
    .ugel-announcements__excerpt{
      margin:0; max-width:min(80ch, 94%);
      font:500 clamp(11px,2.2vw,14px)/1.5 'Inter', system-ui, sans-serif; letter-spacing:.12px; opacity:.94;
    }

    /* Flechas (pequeñas, se posicionan dentro del ancho real de la imagen) */
    .ugel-announcements__navigation{
      position:absolute; inset:0; z-index:5;
      display:flex; align-items:center; justify-content:space-between;
      padding:0 6px; pointer-events:none;
    }
    .ugel-announcements__arrow{
      pointer-events:auto; width:24px; height:24px; border-radius:6px;
      background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.35);
      color:#fff; font-size:14px; display:grid; place-items:center; cursor:pointer;
      transition:.16s ease; backdrop-filter:blur(2px);
    }
    .ugel-announcements__arrow:hover,
    .ugel-announcements__arrow:focus-visible{ background:rgba(255,255,255,.30); border-color:#fff; transform:scale(1.06); }
    .ugel-announcements__arrow[hidden]{display:none!important}

    /* Dots centrados bajo la imagen */
    .ugel-announcements__controls{
      position:absolute; left:50%; bottom:6px; transform:translateX(-50%);
      z-index:5; display:flex; gap:5px;
    }
    .ugel-announcements__dot{
      width:6px; height:6px; border-radius:50%;
      background:rgba(130,151,254,.26); border:none; cursor:pointer; transition:.16s ease;
      box-shadow:0 2px 6px rgba(2,31,89,.16);
    }
    .ugel-announcements__dot.is-active{
      background:var(--accent); transform:scale(1.18);
      box-shadow:0 6px 12px rgba(130,151,254,.28);
    }

    /* Responsividad fina por tamaño/orientación */
    @media (max-width:920px){ :root { --img-h: clamp(200px, 48vh, 380px); } }
    @media (max-width:640px){ :root { --img-h: clamp(180px, 44vh, 320px); } }
    @media (max-width:420px){ :root { --img-h: clamp(160px, 42vh, 280px); } }

    @media (orientation:landscape) and (max-height:480px){
      :root { --img-h: clamp(150px, 70vh, 260px); }
    }
  </style>

  <div class="ugel-announcements__container" data-announcement-rotator>
    <button class="ugel-announcements__close-fab" type="button" aria-label="Cerrar">✕</button>

    <div class="ugel-announcements__track">
      <?php
      $index = 0;
      while($anuncios->have_posts()):
        $anuncios->the_post();
        $index++;
        $is_active   = $index === 1 ? ' is-active' : '';
        $imagen      = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $meta_url    = get_post_meta(get_the_ID(), '_anuncio_url', true);
        $meta_target = get_post_meta(get_the_ID(), '_anuncio_target', true) ?: '_self';
        $href        = $meta_url ? $meta_url : get_permalink();
        $rel_attr    = ($meta_target === '_blank') ? 'noopener noreferrer' : '';
        $excerpt     = wp_strip_all_tags( wp_trim_words(get_the_content(), 20, '…') );
      ?>
      <article class="ugel-announcements__slide<?php echo esc_attr($is_active); ?>" data-index="<?php echo esc_attr($index - 1); ?>">
        <a class="ugel-announcements__media"
           href="<?php echo esc_url($href); ?>"
           target="<?php echo esc_attr($meta_target); ?>"
           rel="<?php echo esc_attr($rel_attr); ?>"
           title="<?php echo esc_attr(get_the_title()); ?>">
          <?php if ($imagen): ?>
            <img src="<?php echo esc_url($imagen); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
          <?php endif; ?>
          <div class="ugel-announcements__media-caption" aria-hidden="false">
            <span class="ugel-announcements__label">Comunicado</span>
            <h3 class="ugel-announcements__headline"><?php echo esc_html(get_the_title()); ?></h3>
            <p class="ugel-announcements__excerpt"><?php echo esc_html($excerpt); ?></p>
          </div>
        </a>

        <div class="ugel-announcements__navigation" aria-hidden="false">
          <button class="ugel-announcements__arrow ugel-announcements__arrow--prev" type="button" aria-label="Anterior">‹</button>
          <button class="ugel-announcements__arrow ugel-announcements__arrow--next" type="button" aria-label="Siguiente">›</button>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>

      <div class="ugel-announcements__controls" role="tablist" aria-label="Paginación de comunicados">
        <?php for ($i = 0; $i < $index; $i++): ?>
          <button class="ugel-announcements__dot <?php echo $i === 0 ? 'is-active' : ''; ?>"
                  data-dot="<?php echo $i; ?>"
                  role="tab"
                  aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                  aria-label="Ir al comunicado <?php echo $i + 1; ?>">
          </button>
        <?php endfor; ?>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const root   = document.querySelector('[data-announcement-rotator]');
      if(!root) return;

      const section   = root.closest('.ugel-announcements');
      const slides    = Array.from(root.querySelectorAll('.ugel-announcements__slide'));
      const dots      = Array.from(root.querySelectorAll('.ugel-announcements__dot'));
      const closeFab  = root.querySelector('.ugel-announcements__close-fab');

      const AUTO_DELAY = 6000;
      const ROTATE_MIN = 2;
      let active = 0, autoTimer = null;

      function setActive(index){
        slides.forEach((s,i)=> s.classList.toggle('is-active', i===index));
        dots.forEach((d,i)=>{
          d.classList.toggle('is-active', i===index);
          d.setAttribute('aria-selected', i===index ? 'true' : 'false');
        });
        active = index;
      }
      const next = ()=> setActive((active + 1) % slides.length);
      const prev = ()=> setActive((active - 1 + slides.length) % slides.length);

      function startAuto(){ if(slides.length >= ROTATE_MIN){ stopAuto(); autoTimer = setInterval(next, AUTO_DELAY); } }
      function stopAuto(){ if(autoTimer){ clearInterval(autoTimer); autoTimer=null; } }

      root.addEventListener('click', (e)=>{
        if(e.target.classList.contains('ugel-announcements__arrow--prev')){ prev(); startAuto(); }
        if(e.target.classList.contains('ugel-announcements__arrow--next')){ next(); startAuto(); }
        if(e.target.classList.contains('ugel-announcements__dot')){
          const i = Number(e.target.getAttribute('data-dot')); setActive(i); startAuto();
        }
      });

      root.addEventListener('mouseenter', stopAuto);
      root.addEventListener('mouseleave', startAuto);

      function closeAll(){ section?.setAttribute('hidden','hidden'); stopAuto(); }
      closeFab?.addEventListener('click', closeAll);
      section?.addEventListener('click', e=>{ if(e.target===section) closeAll(); });

      root.addEventListener('keydown', e=>{
        if(e.key==='ArrowRight'){ e.preventDefault(); next(); startAuto(); }
        if(e.key==='ArrowLeft'){  e.preventDefault(); prev(); startAuto(); }
        if(e.key==='Escape'){     e.preventDefault(); closeAll(); }
      });

      setActive(0);
      startAuto();
    })();
  </script>
</section>
<?php endif; ?>
