<!-- ===================== FOOTER ===================== -->
<footer class="site-footer" role="contentinfo" aria-label="Pie de página UGEL El Collao" itemscope itemtype="https://schema.org/WPFooter">
  <div class="foot-top"></div>
  <div class="foot-wrap">
    
    <?php if (is_active_sidebar('footer-1')): ?>
      <?php dynamic_sidebar('footer-1'); ?>
    <?php else: ?>
      <div class="foot">
        <h4>UGEL El Collao</h4>
        <p>Comprometidos con la mejora de los aprendizajes, la gestión eficiente y la atención oportuna a nuestra comunidad educativa.</p>
        <p><a href="<?php echo home_url('/transparencia'); ?>">Portal de Transparencia</a> · <a href="<?php echo home_url('/normativa'); ?>">Normativa</a></p>
      </div>
    <?php endif; ?>

    <?php if (is_active_sidebar('footer-2')): ?>
      <?php dynamic_sidebar('footer-2'); ?>
    <?php else: ?>
      <div class="foot">
        <h4>Enlaces útiles</h4>
        <ul>
          <li><a href="<?php echo get_post_type_archive_link('convocatorias'); ?>">Convocatorias</a></li>
          <li><a href="<?php echo get_post_type_archive_link('comunicados'); ?>">Comunicados</a></li>
          <li><a href="<?php echo home_url('/tramites'); ?>">Trámites en línea</a></li>
          <li><a href="<?php echo home_url('/campus'); ?>">Campus Virtual</a></li>
          <li><a href="<?php echo home_url('/mesa-de-partes'); ?>">Mesa de Partes Virtual</a></li>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (is_active_sidebar('footer-3')): ?>
      <?php dynamic_sidebar('footer-3'); ?>
    <?php else: ?>
      <div class="foot">
        <h4>Contacto y Atención</h4>
        <p><strong>Tel.:</strong> <a href="tel:974202598">974 202 598</a></p>
        <p><strong>Fijo:</strong> <a href="tel:051552506">051 552 506</a></p>
        <p><strong>Dirección:</strong> Jr. Sucre N° 215, Barrio Santa Bárbara, Ilave, El Collao, Puno</p>
        <p><strong>Email:</strong> <a href="mailto:info@ugelelcollao.edu.pe">info@ugelelcollao.edu.pe</a></p>
        <p><strong>Horario:</strong> Lun-Vie 8:30AM - 4:30PM</p>
      </div>
    <?php endif; ?>
    
  </div>

  <div class="foot-bottom">
    <div>
      © <span id="y"><?php echo date('Y'); ?></span> UGEL El Collao. Todos los derechos reservados.
      <?php if (function_exists('ugel_the_site_views')) : ?>
        &nbsp;•&nbsp;<?php ugel_the_site_views(__('Visitas totales', 'ugel-theme')); ?>
      <?php endif; ?>
      <?php if (function_exists('ugel_the_views_badge') && is_singular()) : ?>
        &nbsp;•&nbsp;<?php ugel_the_views_badge(get_queried_object_id()); ?>
      <?php endif; ?>
    </div>
    <div class="foot-social" aria-label="Redes sociales">
      <?php
      $facebook_url  = get_theme_mod('ugel_facebook', '#');
      $twitter_url   = get_theme_mod('ugel_twitter', '#');
      $instagram_url = get_theme_mod('ugel_instagram', '#');
      ?>
      <a href="<?php echo esc_url($facebook_url); ?>" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12.06C22 6.49 17.52 2 11.94 2S2 6.49 2 12.06c0 5.01 3.66 9.17 8.44 9.94v-7.03H7.9v-2.91h2.54V9.41c0-2.5 1.49-3.88 3.77-3.88 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.77 8.44-4.93 8.44-9.94z"/></svg>
      </a>
      <a href="<?php echo esc_url($twitter_url); ?>" aria-label="X/Twitter" target="_blank" rel="noopener noreferrer">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2H21l-6.5 7.43L22 22h-6.85l-4.81-6.26L4.9 22H2l7.03-8.03L2 2h6.93l4.37 5.77L18.244 2Zm-2.4 18h1.85L8.49 4h-1.9l9.25 16Z"/></svg>
      </a>
      <a href="<?php echo esc_url($instagram_url); ?>" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3Zm5 3.8A5.2 5.2 0 1 1 6.8 13 5.2 5.2 0 0 1 12 7.8Zm0 2a3.2 3.2 0 1 0 3.2 3.2A3.2 3.2 0 0 0 12 9.8Zm5.55-2.85a1.15 1.15 0 1 1-1.15 1.15 1.15 1.15 0 0 1 1.15-1.15Z"/></svg>
      </a>
    </div>
  </div>
</footer>

<!-- ===== Chatbot UGEL El Collao ===== -->
<div id="ugel-chatbot-container">
  <!-- Botón FAB flotante -->
  <button class="chat-fab" id="chatFab" type="button"
          aria-label="Abrir asistente virtual UGEL El Collao"
          aria-controls="chatPanel" aria-expanded="false">
    <div class="robot-container">
      <!-- Antena superior con luz -->
      <div class="robot-antenna">
        <div class="antenna-light"></div>
      </div>
      
      <!-- Cuerpo principal del robot -->
      <div class="robot-body">
        <!-- Cabeza redonda -->
        <div class="robot-head">
          <div class="robot-eyes">
            <div class="robot-eye left">
              <div class="eye-shine"></div>
            </div>
            <div class="robot-eye right">
              <div class="eye-shine"></div>
            </div>
          </div>
          <div class="robot-mouth"></div>
        </div>
        
        <!-- Torso -->
        <div class="robot-torso">
          <div class="chest-panel">
            <div class="panel-light"></div>
          </div>
          <!-- Brazos -->
          <div class="robot-arm left"></div>
          <div class="robot-arm right"></div>
        </div>
        
        <!-- Piernas -->
        <div class="robot-legs">
          <div class="robot-leg left"></div>
          <div class="robot-leg right"></div>
        </div>
      </div>
      
      <!-- Sombra flotante -->
      <div class="robot-shadow"></div>
    </div>
    <span class="chat-notification" id="chatNotification" aria-hidden="true"></span>
  </button>

  <!-- Panel del chatbot -->
  <div class="chat-panel" id="chatPanel" role="dialog" aria-modal="true" aria-labelledby="chatTitle" aria-hidden="true">
    <div class="chat-head">
      <div class="chat-title-section">
        <strong id="chatTitle">🏛️ Asistente UGEL El Collao</strong>
        <div class="chat-status">🟢 En línea • Respuesta automática</div>
      </div>
      <button id="chatClose" type="button" class="chat-close-btn" aria-label="Cerrar chat">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
          <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
    
    <div class="chat-body" id="chatBody">
      <!-- Los mensajes se cargarán aquí dinámicamente -->
    </div>
    
    <div class="chat-input-container">
      <form class="chat-input" id="chatForm">
        <div class="input-wrapper">
          <input id="chatTxt" 
                 type="text" 
                 placeholder="Pregunta sobre trámites, horarios, contacto..." 
                 aria-label="Escribe tu consulta" 
                 maxlength="500" 
                 autocomplete="off">
          <button type="submit" class="send-btn" aria-label="Enviar mensaje" title="Enviar (Enter)">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
              <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
            </svg>
          </button>
        </div>
        <div class="chat-suggestions" id="chatSuggestions" aria-label="Sugerencias rápidas">
          <button type="button" class="suggestion-btn" data-text="¿Cuáles son sus horarios?">⏰ Horarios</button>
          <button type="button" class="suggestion-btn" data-text="¿Dónde están ubicados?">📍 Ubicación</button>
          <button type="button" class="suggestion-btn" data-text="¿Cómo puedo contactarlos?">📞 Contacto</button>
          <button type="button" class="suggestion-btn" data-text="¿Qué trámites realizan?">📋 Trámites</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Overlay para móvil -->
  <div class="chat-overlay" id="chatOverlay" aria-hidden="true"></div>
</div>

<?php wp_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  /* Menu and hero fallback handlers */
  function splitMenuLabels(selector){
    const links = document.querySelectorAll(selector);
    links.forEach(a=>{
      if (a.dataset.splitted) return;
      const text = a.textContent.trim().replace(/\s+/g,' ');
      const words = text.split(' ');
      if (words.length <= 1) return;
      const norm = s => s.normalize('NFD').replace(/\p{Diacritic}/gu,'').toUpperCase();
      let top='', bottom='';
      if (norm(words[0]) === 'GESTION' && words.length >= 2){
        top = words[0]; bottom = words.slice(1).join(' ');
      } else if (words.length >= 3){
        top = words.slice(0, words.length - 1).join(' ');
        bottom = words[words.length - 1];
      } else { top = words[0]; bottom = words[1]; }
      a.innerHTML = `<span class="l1">${top}</span><span class="l2">${bottom}</span>`;
      a.dataset.splitted = '1';
    });
  }
  splitMenuLabels('.menu > a, .menu .menu-item > a');

  const $ = s => document.querySelector(s);
  const fab = $('#menuFab');
  const menu = $('#mobileMenu');
  const back = $('#mobileBackdrop');
  const chatFab = $('#chatFab');
  const chatPanel = $('#chatPanel');
  const chatOverlay = $('#chatOverlay');
  if (fab) { document.body.classList.add('has-mobile-fab'); }

  const dispatchMenuState = (disabled) => {
    const detail = { disabled: !!disabled };
    if (typeof CustomEvent === 'function') {
      document.dispatchEvent(new CustomEvent('ugel:mobile-menu', { detail }));
    } else if (document.createEvent) {
      const legacyEvent = document.createEvent('CustomEvent');
      legacyEvent.initCustomEvent('ugel:mobile-menu', true, true, detail);
      document.dispatchEvent(legacyEvent);
    }
  };

  const syncChatbot = (disabled) => {
    const isDisabled = !!disabled;
    if (chatFab) {
      chatFab.classList.toggle('is-disabled', isDisabled);
      chatFab.setAttribute('aria-disabled', isDisabled ? 'true' : 'false');
      if (isDisabled) {
        chatFab.setAttribute('tabindex', '-1');
        chatFab.blur();
      } else {
        chatFab.removeAttribute('tabindex');
      }
    }

    if (isDisabled) {
      chatPanel?.classList.remove('open');
      chatPanel?.setAttribute('aria-hidden', 'true');
      chatOverlay?.classList.remove('show');
      if (window.ugelChatbot && typeof window.ugelChatbot.close === 'function') {
        window.ugelChatbot.close();
      }
    }

    dispatchMenuState(isDisabled);
  };

  function toggleMenu(open){
    const willOpen = (open===undefined) ? !menu?.classList.contains('open') : open;
    menu?.classList.toggle('open', willOpen);
    back?.classList.toggle('show', willOpen);
    fab?.classList.toggle('open', willOpen);
    fab?.setAttribute('aria-expanded', String(!!willOpen));
    document.body.style.overflow = willOpen ? 'hidden' : '';
    syncChatbot(willOpen);
  }
  syncChatbot(false);
  if (!fab?.dataset.enhanced) {
    if (fab) {
      fab.addEventListener('click', ()=>toggleMenu());
    }
    if (back) {
      back.addEventListener('click', ()=>toggleMenu(false));
    }
    document.addEventListener('keydown', e=>{ if(e.key==='Escape') toggleMenu(false) });
  }

  /* Sticky subheader fallback */
  (function stickSubheaderOnScroll(){
    const onScroll = () => {
      if (window.scrollY > 10) document.body.classList.add('nav-stick-subheader');
      else document.body.classList.remove('nav-stick-subheader');
    };
    onScroll();
    window.addEventListener('scroll', onScroll, {passive:true});
  })();

  /* Hero carousel fallback */
  (function initHero(){
    const track = document.getElementById('heroTrack');
    if(!track || track.dataset.enhanced) return;
    const slides = Array.from(track.querySelectorAll('.hero-slide'));
    const btnPrev = document.getElementById('heroPrev');
    const btnNext = document.getElementById('heroNext');
    const dotsWrap = document.getElementById('heroDots');

    if (slides.length === 0) return;

    slides.forEach((_, idx)=>{
      const d = document.createElement('button');
      d.className = 'hero-dot' + (idx===0 ? ' is-active' : '');
      d.type = 'button';
      d.setAttribute('role','tab');
      d.setAttribute('aria-selected', idx===0 ? 'true' : 'false');
      d.setAttribute('aria-controls', `hero-slide-${idx}`);
      d.addEventListener('click', ()=>go(idx));
      dotsWrap?.appendChild(d);
    });
    slides.forEach((s, i)=> s.id = `hero-slide-${i}`);

    let i = 0, timer = null, delay = 6000, paused = false;
    function go(n){
      slides[i].classList.remove('is-active');
      slides[i].setAttribute('aria-hidden','true');
      dotsWrap?.children[i]?.classList.remove('is-active');
      dotsWrap?.children[i]?.setAttribute('aria-selected','false');
      i = (n + slides.length) % slides.length;
      slides[i].classList.add('is-active');
      slides[i].setAttribute('aria-hidden','false');
      dotsWrap?.children[i]?.classList.add('is-active');
      dotsWrap?.children[i]?.setAttribute('aria-selected','true');
    }
    function next(){ go(i+1) }
    function prev(){ go(i-1) }
    function play(){ stop(); timer = setInterval(()=>{ if(!paused) next(); }, delay) }
    function stop(){ if(timer) clearInterval(timer); timer = null }
    if (btnNext) btnNext.addEventListener('click', ()=>{ next(); play(); });
    if (btnPrev) btnPrev.addEventListener('click', ()=>{ prev(); play(); });
    const hero = document.querySelector('.hero-carousel');
    if (hero) {
      hero.addEventListener('mouseenter', ()=>{ paused = true; });
      hero.addEventListener('mouseleave', ()=>{ paused = false; });
      hero.addEventListener('touchstart', ()=>{ paused = true; }, {passive:true});
      hero.addEventListener('touchend', ()=>{ paused = false; }, {passive:true});
    }
    window.addEventListener('keydown', (e)=>{ 
      if(e.key === 'ArrowRight'){ next(); play(); } 
      if(e.key === 'ArrowLeft'){ prev(); play(); } 
    });
    if (slides.length > 1) play();
  })();

  /* Chatbot compatibility bridges */
  window.toggleChat = function() {
    if (window.ugelChatbot) {
      window.ugelChatbot.toggle();
    } else {
      console.warn('⚠️ Chatbot aún no cargado');
    }
  };
  window.sendMsg = function() {
    if (window.ugelChatbot) {
      window.ugelChatbot.sendMessage();
    } else {
      console.warn('⚠️ Chatbot aún no cargado');
    }
  };

  console.log('✅ Footer scripts cargados (sin chatbot)');
});
</script>

</body>
</html>
