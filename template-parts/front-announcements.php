<?php
if (!defined('ABSPATH')) {
    exit;
}

$anuncios = new WP_Query(array(
    'post_type'      => 'anuncios_portada',
    'post_status'    => 'publish',
    'posts_per_page' => 5,
    'orderby'        => array(
        'menu_order' => 'ASC',
        'date'       => 'DESC',
    ),
));

if ($anuncios->have_posts()):
?>
<section class="front-announcements" aria-label="Anuncios destacados">
  <style>
    .front-announcements {
      position: relative;
      z-index: 5;
      display: flex;
      justify-content: center;
      padding: 32px 16px 0;
    }

    .front-announcements__card {
      position: relative;
      width: min(960px, 100%);
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 22px 45px rgba(15, 75, 86, 0.18);
      color: var(--ink);
      isolation: isolate;
    }

    .front-announcements__track {
      position: relative;
      width: 100%;
      height: auto;
      overflow: hidden;
      transition: height 280ms ease;
    }

    .front-announcements__slide {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      opacity: 0;
      visibility: hidden;
      transform: scale(0.985);
      transition: opacity 320ms ease, visibility 320ms ease, transform 320ms ease;
      background: #ffffff;
    }

    .front-announcements__slide.is-active {
      opacity: 1;
      visibility: visible;
      transform: scale(1);
    }

    .front-announcements__media {
      position: relative;
      width: 100%;
      aspect-ratio: 16 / 9;
      background: #d6e9ef;
      overflow: hidden;
    }

    .front-announcements__media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .front-announcements__media--noimage {
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
      background: linear-gradient(135deg, rgba(0, 183, 177, 0.12), rgba(15, 75, 86, 0.22));
    }

    .front-announcements__title {
      position: absolute;
      top: 20px;
      left: 20px;
      right: 20px;
      padding: 12px 20px;
      border-radius: 14px;
      background: rgba(255, 255, 255, 0.9);
      font-size: clamp(20px, 3vw, 28px);
      font-weight: 800;
      line-height: 1.2;
      color: var(--teal);
      text-transform: uppercase;
      letter-spacing: 0.6px;
      box-shadow: 0 16px 35px rgba(5, 71, 70, 0.18);
    }

    .front-announcements__media--noimage .front-announcements__title {
      position: static;
      margin: 24px;
    }

    .front-announcements__body {
      padding: 24px clamp(20px, 6vw, 48px) 36px;
      font-size: 18px;
      line-height: 1.6;
      max-height: 320px;
      overflow-y: auto;
    }

    .front-announcements__body p {
      margin: 0 0 16px;
    }

    .front-announcements__body p:last-child {
      margin-bottom: 0;
    }

    .front-announcements__arrows {
      position: absolute;
      inset-block: 0;
      inset-inline: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      pointer-events: none;
    }

    .front-announcements__arrow {
      pointer-events: all;
      border: none;
      background: rgba(255, 255, 255, 0.9);
      color: var(--teal);
      width: 54px;
      height: 54px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      font-size: 32px;
      font-weight: 700;
      cursor: pointer;
      transition: transform 180ms ease, box-shadow 180ms ease;
      box-shadow: 0 16px 40px rgba(7, 71, 70, 0.18);
    }

    .front-announcements__arrow:focus-visible,
    .front-announcements__arrow:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 22px 60px rgba(7, 71, 70, 0.22);
      outline: none;
    }

    .front-announcements__arrow[hidden] {
      display: none;
    }

    @media (max-width: 768px) {
      .front-announcements {
        padding-inline: 12px;
      }

      .front-announcements__card {
        border-radius: 16px;
      }

      .front-announcements__title {
        top: 16px;
        left: 16px;
        right: 16px;
        padding: 10px 16px;
        border-radius: 12px;
      }

      .front-announcements__body {
        padding: 20px 20px 28px;
        font-size: 16px;
      }

      .front-announcements__arrow {
        width: 46px;
        height: 46px;
        font-size: 26px;
      }

      .front-announcements__media--noimage .front-announcements__title {
        margin: 20px;
      }
    }
  </style>

  <div class="front-announcements__card" data-announcement-rotator>
    <div class="front-announcements__track">
      <?php
      $index = 0;
      while ($anuncios->have_posts()):
          $anuncios->the_post();
          $index++;
          $is_active = $index === 1 ? ' is-active' : '';
          $imagen = get_the_post_thumbnail_url(get_the_ID(), 'large');
      ?>
      <article class="front-announcements__slide<?php echo esc_attr($is_active); ?>" data-index="<?php echo esc_attr($index - 1); ?>">
        <?php if ($imagen): ?>
        <figure class="front-announcements__media">
          <img src="<?php echo esc_url($imagen); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
          <figcaption class="front-announcements__title"><?php echo esc_html(get_the_title()); ?></figcaption>
        </figure>
        <?php else: ?>
        <figure class="front-announcements__media front-announcements__media--noimage">
          <figcaption class="front-announcements__title"><?php echo esc_html(get_the_title()); ?></figcaption>
        </figure>
        <?php endif; ?>
        <div class="front-announcements__body">
          <?php echo wp_kses_post(wpautop(get_the_content())); ?>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <div class="front-announcements__arrows" aria-hidden="true">
      <button class="front-announcements__arrow front-announcements__arrow--prev" type="button" aria-label="Anterior">‹</button>
      <button class="front-announcements__arrow front-announcements__arrow--next" type="button" aria-label="Siguiente">›</button>
    </div>
  </div>

  <script>
    (function() {
      const root = document.querySelector('[data-announcement-rotator]');
      if (!root) {
        return;
      }

      const track = root.querySelector('.front-announcements__track');
      const slides = root.querySelectorAll('.front-announcements__slide');
      const prevBtn = root.querySelector('.front-announcements__arrow--prev');
      const nextBtn = root.querySelector('.front-announcements__arrow--next');
      const AUTO_DELAY = 5000;
      let active = 0;
      let autoTimer = null;

      if (!slides.length) {
        prevBtn?.setAttribute('hidden', 'hidden');
        nextBtn?.setAttribute('hidden', 'hidden');
        return;
      }

      function syncHeight() {
        if (!track) {
          return;
        }
        const activeSlide = slides[active];
        if (activeSlide) {
          track.style.height = activeSlide.scrollHeight + 'px';
        }
      }

      function setActive(index) {
        slides.forEach((slide, idx) => {
          slide.classList.toggle('is-active', idx === index);
        });
        active = index;
        syncHeight();
      }

      function next() {
        const nextIndex = (active + 1) % slides.length;
        setActive(nextIndex);
      }

      function prev() {
        const prevIndex = (active - 1 + slides.length) % slides.length;
        setActive(prevIndex);
      }

      function startAuto() {
        if (slides.length < 2) {
          return;
        }
        stopAuto();
        autoTimer = setInterval(next, AUTO_DELAY);
      }

      function stopAuto() {
        if (autoTimer) {
          clearInterval(autoTimer);
          autoTimer = null;
        }
      }

      prevBtn?.addEventListener('click', () => {
        prev();
        startAuto();
      });

      nextBtn?.addEventListener('click', () => {
        next();
        startAuto();
      });

      root.addEventListener('mouseenter', stopAuto);
      root.addEventListener('mouseleave', startAuto);

      setActive(0);
      syncHeight();

      window.addEventListener('resize', syncHeight);

      if (slides.length < 2) {
        prevBtn?.setAttribute('hidden', 'hidden');
        nextBtn?.setAttribute('hidden', 'hidden');
        window.removeEventListener('resize', syncHeight);
      } else {
        startAuto();
      }
    })();
  </script>
</section>
<?php
endif;
?>
