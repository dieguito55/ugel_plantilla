(function() {
  'use strict';

  let isMenuOpen = false;
  let heroSlideInterval = null;
  let currentSlide = 0;
  let sugAbort;

  document.addEventListener('DOMContentLoaded', function() {
    initializeTheme();
  });

  /* Theme boot sequence */
  function initializeTheme() {
    setupMenuSplitting();
    setupMobileMenu();
    setupStickySubheader();
    setupHeroCarousel();
    setupForms();
    setupLazyLoading();
    setupSmoothScrolling();
    setupAccessibility();
    setupSearchEnhancements();
  }

  /* Menu label typography adjustments */
  function setupMenuSplitting() {
    function splitMenuLabels(selector) {
      const links = document.querySelectorAll(selector);
      links.forEach(a => {
        if (a.dataset.splitted) return;
        const text = a.textContent.trim().replace(/\s+/g, ' ');
        const words = text.split(' ');
        if (words.length <= 1) return;
        const norm = s => s.normalize('NFD').replace(/\p{Diacritic}/gu, '').toUpperCase();
        let top = '', bottom = '';
        if (norm(words[0]) === 'GESTION' && words.length >= 2) {
          top = words[0];
          bottom = words.slice(1).join(' ');
        } else if (words.length >= 3) {
          top = words.slice(0, words.length - 1).join(' ');
          bottom = words[words.length - 1];
        } else {
          top = words[0];
          bottom = words[1];
        }
        a.innerHTML = `<span class="l1">${top}</span><span class="l2">${bottom}</span>`;
        a.dataset.splitted = '1';
      });
    }
    splitMenuLabels('.menu > a, .menu .menu-item > a');
  }
/* Mobile menu: floating button, drawer, and backdrop */
function setupMobileMenu() {
  const fab = document.getElementById('menuFab');
  const menu = document.getElementById('mobileMenu');
  const backdrop = document.getElementById('mobileBackdrop');
  if (!fab || !menu || !backdrop) return;

  if (fab.dataset.enhanced === '1') return;
  fab.dataset.enhanced = '1';
  menu.dataset.enhanced = '1';
  backdrop.dataset.enhanced = '1';

  // Bloqueo de scroll SIN tocar el <body> (sin overflow/position fixed)
  const ScrollGuard = (() => {
    let enabled = false;
    const cancelableKeys = new Set([' ', 'ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End']);

    const prevent = (e) => {
      if (menu.contains(e.target)) return;
      e.preventDefault();
    };

    const preventKeys = (e) => {
      if (!enabled) return;
      if (cancelableKeys.has(e.key) && !menu.contains(e.target)) {
        e.preventDefault();
      }
    };

    return {
      enable() {
        if (enabled) return;
        enabled = true;
        window.addEventListener('wheel', prevent, { passive: false });
        window.addEventListener('touchmove', prevent, { passive: false });
        document.addEventListener('keydown', preventKeys, { passive: false });
      },
      disable() {
        if (!enabled) return;
        enabled = false;
        window.removeEventListener('wheel', prevent);
        window.removeEventListener('touchmove', prevent);
        document.removeEventListener('keydown', preventKeys);
      }
    };
  })();

  let isMenuOpen = false;

  const open = () => {
    if (isMenuOpen) return;
    isMenuOpen = true;
    menu.classList.add('open');
    backdrop.classList.add('open');
    fab.classList.add('open');
    fab.setAttribute('aria-expanded', 'true');
    document.body.classList.add('mm-open');
    ScrollGuard.enable();
    setTimeout(() => {
      const focusables = menu.querySelectorAll('a, button, input, select, textarea');
      const target = Array.from(focusables).find(el => !(el instanceof HTMLInputElement && el.type === 'search'));
      if (target) {
        target.focus();
      }
    }, 80);
  };

  const close = () => {
    if (!isMenuOpen) return;
    isMenuOpen = false;
    menu.classList.remove('open');
    backdrop.classList.remove('open');
    fab.classList.remove('open');
    fab.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('mm-open');
    ScrollGuard.disable();
    fab.focus();
  };

  const toggle = () => (isMenuOpen ? close() : open());

  isMenuOpen = false;
  menu.classList.remove('open');
  backdrop.classList.remove('open');
  fab.classList.remove('open');
  document.body.classList.remove('mm-open');
  ScrollGuard.disable();

  let clicking = false;
  fab.addEventListener('click', (e) => {
    e.stopPropagation();
    if (clicking) return;
    clicking = true;
    toggle();
    setTimeout(() => (clicking = false), 220);
  });

  backdrop.addEventListener('click', (e) => {
    e.stopPropagation();
    close();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isMenuOpen) close();
  });

  menu.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (link && link.getAttribute('href') !== '#') close();
  });

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      if (window.innerWidth > 1024 && isMenuOpen) close();
    }, 120);
  });

  window.addEventListener('orientationchange', () => { if (isMenuOpen) close(); });
}



  function setupStickySubheader() {
  const BODY_CLASS = 'nav-stick-subheader';
  const BP = 1024;
  const topbar = document.querySelector('.topbar');
  const subheader = document.querySelector('.subheader');
  if (!topbar || !subheader) return;

  let threshold = topbar.offsetHeight || 80;
  let ticking = false;

  const update = () => {
    if (window.innerWidth <= BP) {
      document.body.classList.remove(BODY_CLASS);
    } else {
      const y = window.scrollY || document.documentElement.scrollTop || 0;
      document.body.classList.toggle(BODY_CLASS, y > threshold);
    }
    ticking = false;
  };

  const onScroll = () => {
    if (!ticking) {
      requestAnimationFrame(update);
      ticking = true;
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', () => {
    threshold = topbar.offsetHeight || 80;
    update();
  }, { passive: true });

  update();
}


/* Hero carousel transitions and controls */
(function setupHeroCarousel() {
  const track   = document.getElementById('heroTrack');
  const btnPrev = document.getElementById('heroPrev');
  const btnNext = document.getElementById('heroNext');
  const dotsWrap= document.getElementById('heroDots');
  if (!track || !dotsWrap) return;

  track.dataset.enhanced = '1';

  const allSlides = Array.from(track.querySelectorAll('.hero-slide'));
  if (allSlides.length === 0) return;

  const dynamicSlides = allSlides.filter(slide =>
    !slide.querySelector('.cap-title')?.textContent.includes('La forma más rápida de lograr')
  );
  const slides = dynamicSlides.length ? dynamicSlides : allSlides;

  slides.forEach((slide, i) => {
    slide.id = `hero-slide-${i}`;
    slide.setAttribute('role', 'tabpanel');
    slide.setAttribute('aria-roledescription', 'slide');
  });

  dotsWrap.innerHTML = '';
  dotsWrap.dataset.enhanced = '1';
  if (slides.length > 1) {
    slides.forEach((_, idx) => {
      const dot = document.createElement('button');
      dot.className = 'hero-dot' + (idx === 0 ? ' is-active' : '');
      dot.type = 'button';
      dot.setAttribute('role', 'tab');
      dot.setAttribute('aria-selected', idx === 0 ? 'true' : 'false');
      dot.setAttribute('aria-controls', `hero-slide-${idx}`);
      dot.setAttribute('aria-label', `Ir al slide ${idx + 1}`);
      dot.addEventListener('click', () => goToSlide(idx, true));
      dotsWrap.appendChild(dot);
    });
  }

  let current = Math.max(0, slides.findIndex(s => s.classList.contains('is-active')));
  if (current < 0) current = 0;

  let paused = false;
  let timer  = null;
  const delay = 7500;

  function setActive(n){
    slides[current].classList.remove('is-active');
    slides[current].setAttribute('aria-hidden','true');
    if (dotsWrap.children[current]) {
      dotsWrap.children[current].classList.remove('is-active');
      dotsWrap.children[current].setAttribute('aria-selected','false');
    }
    current = (n + slides.length) % slides.length;
    slides[current].classList.add('is-active');
    slides[current].setAttribute('aria-hidden','false');
    if (dotsWrap.children[current]) {
      dotsWrap.children[current].classList.add('is-active');
      dotsWrap.children[current].setAttribute('aria-selected','true');
    }
  }

  function goToSlide(n, user=false){
    if (slides.length <= 1) return;
    setActive(n);
    if (user) start();
  }
  const next = () => goToSlide(current + 1);
  const prev = () => goToSlide(current - 1);

  function start(){
    stop();
    if (slides.length > 1) timer = setInterval(() => { if (!paused) next(); }, delay);
  }
  function stop(){
    if (timer) { clearInterval(timer); timer = null; }
  }

  btnNext && btnNext.addEventListener('click', () => { next(); start(); });
  btnPrev && btnPrev.addEventListener('click', () => { prev(); start(); });

  const hero = document.querySelector('.hero-carousel');
  if (hero) {
    hero.addEventListener('mouseenter', () => { paused = true; });
    hero.addEventListener('mouseleave', () => { paused = false; });
    hero.addEventListener('touchstart', () => { paused = true; }, { passive: true });
    hero.addEventListener('touchend', () => { paused = false; }, { passive: true });
  }

  document.addEventListener('keydown', (e) => {
    if (!hero || !hero.contains(document.activeElement)) return;
    if (e.key === 'ArrowRight') { e.preventDefault(); next(); start(); }
    if (e.key === 'ArrowLeft')  { e.preventDefault(); prev(); start(); }
  });

  if ('IntersectionObserver' in window && hero) {
    const io = new IntersectionObserver(([entry]) => { 
      paused = !entry.isIntersecting; 
    }, { threshold: 0.2 });
    io.observe(hero);
  }

  let startX = 0, dx = 0, touching = false;
  const threshold = 60;
  
  function onStart(e){
    touching = true; dx = 0;
    startX = ('touches' in e) ? e.touches[0].clientX : e.clientX;
  }
  function onMove(e){
    if (!touching) return;
    const x = ('touches' in e) ? e.touches[0].clientX : e.clientX;
    dx = x - startX;
  }
  function onEnd(){
    if (!touching) return; 
    touching = false;
    if (Math.abs(dx) > threshold) { 
      dx < 0 ? next() : prev(); 
      start(); 
    }
  }
  
  hero?.addEventListener('touchstart', onStart, {passive:true});
  hero?.addEventListener('touchmove',  onMove,  {passive:true});
  hero?.addEventListener('touchend',   onEnd,   {passive:true});
  hero?.addEventListener('pointerdown', onStart);
  hero?.addEventListener('pointermove',  onMove);
  hero?.addEventListener('pointerup',    onEnd);

  slides.forEach((s,i)=> s.setAttribute('aria-hidden', i === current ? 'false' : 'true'));
  if (slides.length > 1) start();
  
  document.addEventListener('visibilitychange', () => { 
    paused = document.hidden; 
  });

  if (slides.length > 1) {
    slides.forEach(slide => {
      const img = slide.querySelector('img');
      if (img && !img.complete) {
        const preload = new Image();
        preload.src = img.src;
      }
    });
  }
})();

  /* Form submissions and search suggestion binding */
  function setupForms() {
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
      newsletterForm.addEventListener('submit', handleNewsletterSubmit);
    }
    const filterForms = document.querySelectorAll('.filter-form');
    filterForms.forEach(form => {
      const selects = form.querySelectorAll('select');
      selects.forEach(select => { select.addEventListener('change', () => { form.submit(); }); });
    });
    const searchInputs = document.querySelectorAll('input[type="search"]');
    searchInputs.forEach(setupSearchSuggestions);
  }

  /* Newsletter subscription flow */
  function handleNewsletterSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Enviando...';
    fetch(ugel_ajax.ajax_url, { method: 'POST', body: formData })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification('¡Gracias por suscribirte!', 'success');
          form.reset();
        } else {
          showNotification('Error al suscribirse. Inténtalo nuevamente.', 'error');
        }
      })
      .catch(() => { showNotification('Error al suscribirse. Inténtalo nuevamente.', 'error'); })
      .finally(() => { submitBtn.disabled = false; submitBtn.textContent = 'Suscribirme'; });
  }

  /* Lazy loading for deferred media */
  function setupLazyLoading() {
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src || img.src;
            img.classList.remove('lazy-load');
            img.classList.add('loaded');
            observer.unobserve(img);
          }
        });
      });
      document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        img.classList.add('lazy-load');
        imageObserver.observe(img);
      });
    }
  }

  /* Smooth scrolling for anchor navigation */
  function setupSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#') return;
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          const offsetTop = target.offsetTop - 100;
          window.scrollTo({ top: offsetTop, behavior: 'smooth' });
        }
      });
    });
  }

  /* Accessibility helpers and screen reader support */
  function setupAccessibility() {
    document.addEventListener('keydown', handleTabTrap);
    document.addEventListener('focusin', e => {
      if (e.target.matches('a, button, input, textarea, select')) e.target.classList.add('has-focus');
    });
    document.addEventListener('focusout', e => { e.target.classList.remove('has-focus'); });
    const liveRegion = createLiveRegion();
    window.announceToScreenReader = function(message) {
      liveRegion.textContent = message;
      setTimeout(() => { liveRegion.textContent = ''; }, 1000);
    };
  }

  /* Live search suggestions in desktop and mobile forms */
  function setupSearchSuggestions(input) {
    let timeout;
    input.addEventListener('input', function() {
      const query = this.value.trim();
      clearTimeout(timeout);
      if (sugAbort) sugAbort.abort();
      if (query.length < 3) { hideSuggestions(); return; }
      timeout = setTimeout(() => { fetchSuggestions(query, input); }, 300);
    });
    input.addEventListener('keydown', function(e) {
      const suggestions = document.querySelector('.search-suggestions');
      if (!suggestions) return;
      const items = suggestions.querySelectorAll('.suggestion-item');
      const current = suggestions.querySelector('.suggestion-item.active');
      let index = Array.from(items).indexOf(current);
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        index = index < items.length - 1 ? index + 1 : 0;
        setActiveSuggestion(items, index);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        index = index > 0 ? index - 1 : items.length - 1;
        setActiveSuggestion(items, index);
      } else if (e.key === 'Enter' && current) {
        e.preventDefault();
        window.location.href = current.href;
      } else if (e.key === 'Escape') {
        hideSuggestions();
      }
    });
  }

  /* Search analytics and validation */
  function setupSearchEnhancements() {
    const searchForm = document.getElementById('buscarForm');
    if (searchForm) {
      searchForm.addEventListener('submit', function(e) {
        const input = this.querySelector('input[name="s"]');
        const query = input.value.trim();
        if (!query) {
          e.preventDefault();
          input.focus();
          input.setAttribute('placeholder', 'Escribe algo para buscar…');
          return;
        }
        if (typeof gtag !== 'undefined') {
          gtag('event', 'search', { search_term: query });
        }
      });
    }
  }

  function trapFocus(element) {
    const focusableElements = element.querySelectorAll('a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select');
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    element.addEventListener('keydown', function(e) {
      if (e.key === 'Tab') {
        if (e.shiftKey) {
          if (document.activeElement === firstElement) { lastElement.focus(); e.preventDefault(); }
        } else {
          if (document.activeElement === lastElement) { firstElement.focus(); e.preventDefault(); }
        }
      }
    });
  }

  function handleTabTrap(e) {
    if (e.key !== 'Tab') return;
    if (isMenuOpen) {
      const menu = document.getElementById('mobileMenu');
      if (menu) trapFocus(menu);
    }
  }

  function createLiveRegion() {
    const region = document.createElement('div');
    region.setAttribute('aria-live', 'polite');
    region.setAttribute('aria-atomic', 'true');
    region.className = 'sr-only';
    document.body.appendChild(region);
    return region;
  }

  function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    Object.assign(notification.style, {
      position: 'fixed',
      top: '20px',
      right: '20px',
      padding: '16px 20px',
      borderRadius: '8px',
      color: '#fff',
      fontWeight: '600',
      zIndex: '9999',
      opacity: '0',
      transform: 'translateY(-10px)',
      transition: 'all 0.3s ease',
      maxWidth: '400px'
    });
    if (type === 'success') {
      notification.style.background = 'linear-gradient(90deg, #10b981, #059669)';
    } else if (type === 'error') {
      notification.style.background = 'linear-gradient(90deg, #ef4444, #dc2626)';
    } else {
      notification.style.background = 'linear-gradient(90deg, #3b82f6, #2563eb)';
    }
    document.body.appendChild(notification);
    setTimeout(() => {
      notification.style.opacity = '1';
      notification.style.transform = 'translateY(0)';
    }, 10);
    setTimeout(() => {
      notification.style.opacity = '0';
      notification.style.transform = 'translateY(-10px)';
      setTimeout(() => { if (notification.parentNode) notification.parentNode.removeChild(notification); }, 300);
    }, 5000);
    if (window.announceToScreenReader) window.announceToScreenReader(message);
  }

  function fetchSuggestions(query, input) {
    if (sugAbort) sugAbort.abort();
    sugAbort = new AbortController();
    const body = new URLSearchParams({
      action: 'ugel_live_search',
      nonce: ugel_ajax.nonce,
      search: query
    });
    fetch(ugel_ajax.ajax_url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
      signal: sugAbort.signal
    })
      .then(response => response.text())
      .then(html => { showSuggestions(html, input); })
      .catch(err => { if (err.name !== 'AbortError') console.error(err); });
  }

  function showSuggestions(html, input) {
    hideSuggestions();
    if (!html.trim()) return;
    const container = document.createElement('div');
    container.className = 'search-suggestions';
    container.innerHTML = html;
    const rect = input.getBoundingClientRect();
    Object.assign(container.style, {
      position: 'absolute',
      top: rect.bottom + window.scrollY + 'px',
      left: rect.left + window.scrollX + 'px',
      width: rect.width + 'px',
      zIndex: '1000',
      background: '#fff',
      border: '1px solid #e9eef0',
      borderRadius: '8px',
      boxShadow: '0 8px 24px rgba(0,0,0,0.15)',
      maxHeight: '300px',
      overflowY: 'auto'
    });
    document.body.appendChild(container);
    container.querySelectorAll('a').forEach(a => a.classList.add('suggestion-item'));
    container.addEventListener('click', e => {
      const link = e.target.closest('.suggestion-item');
      if (link) hideSuggestions();
    });
    document.addEventListener('click', function clickOutside(e) {
      if (!container.contains(e.target) && e.target !== input) {
        hideSuggestions();
        document.removeEventListener('click', clickOutside);
      }
    });
  }

  function hideSuggestions() {
    const existing = document.querySelector('.search-suggestions');
    if (existing) existing.remove();
  }

  function setActiveSuggestion(items, index) {
    items.forEach((item, i) => { item.classList.toggle('active', i === index); });
  }

  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => { clearTimeout(timeout); func(...args); };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  function throttle(func, limit) {
    let inThrottle;
    return function() {
      const args = arguments;
      const context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }

  function isMobileDevice() {
    return window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  }

  if (isMobileDevice()) {
    if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
      document.documentElement.style.setProperty('--animation-duration', '0.1s');
    }
    document.addEventListener('touchstart', function() {}, { passive: true });
  }

  window.addEventListener('orientationchange', debounce(() => {
    if (isMenuOpen) {
      const fab = document.querySelector('#menuFab');
      if (fab) fab.click();
    }
  }, 150));

  window.addEventListener('error', function(e) {
    if (window.location.hostname !== 'localhost' && typeof gtag !== 'undefined') {
      gtag('event', 'exception', { description: e.error ? e.error.toString() : 'JS Error', fatal: false });
    }
  });

 function preloadCriticalResources() {
  const logoImg = document.querySelector('header .site-logo img, .topbar .site-logo img');
  if (!logoImg) return;

  const rect = logoImg.getBoundingClientRect();
  const isAboveTheFold = rect.top < (window.innerHeight * 0.8);
  if (!isAboveTheFold) return;

  const link = document.createElement('link');
  link.rel = 'preload';
  link.as = 'image';
  link.href = logoImg.currentSrc || logoImg.src;
  if (link.href) document.head.appendChild(link);
}


  function optimizeWebVitals() {
    setTimeout(() => {
      const scripts = document.querySelectorAll('script[data-lazy]');
      scripts.forEach(script => {
        const newScript = document.createElement('script');
        newScript.src = script.dataset.src;
        newScript.async = true;
        document.head.appendChild(newScript);
      });
    }, 2000);
  }

  if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
  }

  function trackPerformance() {
    if (typeof gtag !== 'undefined' && window.webVitals) {
      window.webVitals.getLCP(metric => {
        gtag('event', 'web_vital', { name: 'LCP', value: Math.round(metric.value), event_category: 'performance' });
      });
      window.webVitals.getFID(metric => {
        gtag('event', 'web_vital', { name: 'FID', value: Math.round(metric.value), event_category: 'performance' });
      });
      window.webVitals.getCLS(metric => {
        gtag('event', 'web_vital', { name: 'CLS', value: Math.round(metric.value * 1000), event_category: 'performance' });
      });
    }
  }

  window.UGELTheme = {
    showNotification: showNotification,
    announceToScreenReader: window.announceToScreenReader,
    isMobileDevice: isMobileDevice,
    debounce: debounce,
    throttle: throttle
  };

  preloadCriticalResources();
  optimizeWebVitals();
  window.addEventListener('load', () => { setTimeout(trackPerformance, 1000); });

  if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
      var el = this;
      do {
        if (Element.prototype.matches.call(el, s)) return el;
        el = el.parentElement || el.parentNode;
      } while (el !== null && el.nodeType === 1);
      return null;
    };
  }
  if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
  }

  if (!('IntersectionObserver' in window)) {
    const images = document.querySelectorAll('img[loading="lazy"]');
    images.forEach(img => {
      img.src = img.dataset.src || img.src;
      img.classList.add('loaded');
    });
  }
})();

