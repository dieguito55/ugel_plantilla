<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

<?php wp_head(); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&display=swap" rel="stylesheet">

<style>
/* ==========================================================================
   UGEL – Header/Nav Pro v12
   Mejora solicitada:
   - Subheader (desktop): hover con “card + indicador lateral + flecha deslizante” en subitems,
     y halo sutil en items padre cuando se abre el submenu (no solo pintar).
   - Portal: brillo direccional + leve tilt.
   - Logos: lift + halo secundario (#7E9BF2) al hover.
   - Mantiene buscador compacto con sugerencias (overlay, máx. 5), acordeón móvil y z-index correcto.
   ========================================================================== */

/* =========================
   TOKENS – PALETA / TIPOS / EFECTOS
   ========================= */
:root{
  --color-primario: #03178C;
  --color-secundario: #7E9BF2;
  --color-blanco: #FFFFFF;

  --prim-800: color-mix(in oklch, var(--color-primario) 92%, black);
  --prim-700: color-mix(in oklch, var(--color-primario) 88%, black);
  --prim-600: color-mix(in oklch, var(--color-primario) 80%, black);
  --prim-500: var(--color-primario);
  --prim-300: color-mix(in oklch, var(--color-primario) 58%, white);

  --sec-700: color-mix(in oklch, var(--color-secundario) 86%, black);
  --sec-500: var(--color-secundario);
  --sec-300: color-mix(in oklch, var(--color-secundario) 72%, white);

  --text-strong:#0A1022;
  --text-base:#1b2333;
  --text-muted: color-mix(in oklch, var(--text-base) 60%, white);
  --bg-base:#fff; --bg-soft:#f7f8ff;
  --border: color-mix(in oklch, var(--text-base) 16%, white);
  --border-strong: color-mix(in oklch, var(--text-base) 24%, white);

  --fs-12: 12px; --fs-13: 13px; --fs-14: 14px; --fs-15: 15px;
  --lh-tight: 1.15; --lh-normal: 1.35;

  --shadow-sm: 0 1px 2px rgba(8,15,60,.06), 0 1px 1px rgba(8,15,60,.04);
  --shadow-md: 0 8px 22px rgba(8,15,60,.12);
  --shadow-lg: 0 20px 48px rgba(8,15,60,.16);

  --focus-ring: 3px solid color-mix(in oklch, var(--color-secundario) 55%, white);
  --focus-offset: 2px;

  --ease: cubic-bezier(.2,.65,.2,1);
  --dur-xfast: 120ms; --dur-fast: 160ms; --dur: 220ms;

  --h: clamp(64px, 6.2vw, 78px);
  --badge: clamp(82px, 9vw, 104px);
  --nav-gap-from-logo: 34px; --nav-right-gutter: 0px; --nav-left: clamp(320px, 30vw, 560px);
  --logo2-pad: 5px; --logo2-scale:.92; --logo2-lift: clamp(4px, 1vw, 10px);
  --logo2-border-clip: 50%; --logo2-border-w: 5px;

  --subheader-py: 0px; --menu-item-py: 9px;
  --subheader-overlap: 50px; --cards-overlap: 72px;
}

/* =========================
   ANIMACIONES
   ========================= */
@keyframes logoShine { 0%{transform:translateX(-120%) skewX(-12deg);opacity:0}40%{opacity:.55}100%{transform:translateX(140%) skewX(-12deg);opacity:0} }
@keyframes ringPulse { 0%{box-shadow:0 0 0 0 rgba(0,12,151,0),0 10px 24px rgba(0,0,0,.18)}70%{box-shadow:0 0 0 10px rgba(0,12,151,.12),0 18px 40px rgba(0,0,0,.22)}100%{box-shadow:0 0 0 14px rgba(0,12,151,0),0 10px 24px rgba(0,0,0,.18)} }
@keyframes menuReveal { from{opacity:0;transform:translateY(8px) scale(.98)} to{opacity:1;transform:translateY(0) scale(1)} }

/* =========================
   BASE
   ========================= */
*{box-sizing:border-box}
html,body{margin:0}
body{
  font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
  font-size: var(--fs-15);
  line-height: var(--lh-normal);
  color: var(--text-base);
  background: var(--bg-base);
  -webkit-font-smoothing:antialiased; text-rendering:optimizeLegibility;
  letter-spacing: .1px;
}
.topbar-cap{ height:6px; background: linear-gradient(90deg, var(--prim-500), color-mix(in oklch, var(--prim-500) 60%, var(--sec-500))) }

/* =========================
   TOPBAR (sticky)
   ========================= */
.topbar{
  position:sticky; top:0; z-index:7000;
  background:#fff; height:var(--h);
  border-bottom:1px solid var(--border);
  box-shadow: var(--shadow-sm);
  transition: transform var(--dur) var(--ease), box-shadow var(--dur) var(--ease), backdrop-filter var(--dur) var(--ease);
  will-change: transform;
  backdrop-filter: saturate(120%) blur(0px);
}
body.nav-stick-subheader .topbar{ transform: translateY(calc(-100% - var(--badge) / 2)); overflow:hidden; }
body.is-scrolled .topbar{ box-shadow: var(--shadow-md); backdrop-filter: saturate(120%) blur(6px); }

.wrap{ max-width:1240px; margin:0 auto; height:100%; padding:0 16px; display:flex; align-items:center; gap:14px; flex-wrap:nowrap }

/* =========================
   LOGOS
   ========================= */
.brand{ display:flex; align-items:center; gap:10px; min-width: clamp(280px, 27vw, 360px); position:relative }
.logo1{
  height:calc(var(--h) - 18px); min-width: clamp(132px, 15vw, 178px);
  display:flex; align-items:center; justify-content:center; overflow:hidden;
  border:1px solid var(--border); border-radius:12px; background:#fff;
  box-shadow: var(--shadow-sm); padding:6px 10px;
  transition: transform var(--dur) var(--ease), box-shadow var(--dur) var(--ease), filter var(--dur) var(--ease), border-color var(--dur-fast);
  will-change: transform;
}
.logo1:hover{
  transform: translateY(-2px) scale(1.01);
  box-shadow: 0 16px 38px rgba(0,0,0,.14), 0 8px 18px rgba(0,0,0,.08);
  border-color: var(--sec-500);
  filter:saturate(1.02);
}
.logo1:focus-visible{ outline: var(--focus-ring); outline-offset: var(--focus-offset) }
.logo1 img{ height:100%; width:auto; object-fit:contain; display:block }

.logo2{
  position:relative; width:var(--badge); height:var(--badge); border-radius:16px; background:#fff; overflow:hidden;
  display:grid; place-items:center; box-shadow: var(--shadow-lg);
  margin-left:12px; transform: translateY(calc(var(--badge) / 2 - var(--logo2-lift))); z-index:1;
  transition: transform var(--dur) var(--ease), box-shadow var(--dur) var(--ease), filter var(--dur) var(--ease), border-color var(--dur-fast);
  will-change: transform;
}
.logo2::after{
  content:""; position:absolute; inset:0; border:var(--logo2-border-w) solid var(--prim-500); border-radius:16px;
  clip-path: inset(var(--logo2-border-clip) 0 0 0); pointer-events:none;
  transition: clip-path var(--dur) var(--ease), border-color var(--dur) var(--ease), opacity var(--dur) var(--ease);
}
.logo2::before{ content:""; position:absolute; inset:0; pointer-events:none; opacity:0; background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,.35) 44%, rgba(255,255,255,0) 100%); transform: translateX(-130%) skewX(-12deg) }
.logo2 .frame{ width:100%; height:100%; padding:var(--logo2-pad); display:grid; place-items:center; background:#fff }
.logo2 img{ max-width:100%; max-height:100%; transform: scale(var(--logo2-scale)); transition: transform var(--dur) var(--ease) }
@media (hover:hover){
  .logo2:hover{
    transform: translateY(calc(var(--badge)/2 - var(--logo2-lift) - 2px)) scale(1.018);
    box-shadow: 0 26px 58px rgba(8,15,60,.22), 0 0 0 6px rgba(126,155,242,.18);
  }
  .logo2:hover img{ transform: scale(calc(var(--logo2-scale) * 1.02)) }
  .logo2:hover::before{ animation: logoShine .9s ease-in-out forwards }
  .logo2:hover::after{ border-color: var(--sec-500) }
}
.logo2:focus-visible{ outline: 0; animation: ringPulse 1.15s ease-out 1 }

.spacer{ flex:1 1 auto }

/* =========================
   REDES + PORTAL + BUSCADOR
   ========================= */
.socials{ display:flex; align-items:center; gap:8px }

/* ICONOS SOCIALES – Fondo BLANCO + icono azul; hover → fondo marca + icono blanco */
.sbtn{
  position:relative; width:38px; height:38px; border-radius:999px;
  background:#fff; color: var(--prim-500); border:1.5px solid var(--sec-500);
  display:grid; place-items:center; overflow:hidden;
  box-shadow: var(--shadow-sm), 0 0 0 0 rgba(0,12,151,0);
  transition: transform var(--dur-fast) var(--ease), box-shadow var(--dur-fast) var(--ease), filter var(--dur-fast), background var(--dur-fast), color var(--dur-fast), border-color var(--dur-fast);
}
.sbtn:hover{ transform: translateY(-2px); box-shadow: var(--shadow-md), 0 0 0 6px rgba(0,12,151,.06) }
.sbtn:active{ transform: translateY(0) scale(.98) }
.sbtn:focus-visible{ outline: var(--focus-ring); outline-offset: var(--focus-offset) }
.sbtn svg{ width:18px; height:18px; display:block }
.sbtn svg *{ fill: currentColor !important; stroke: currentColor !important; }
.sbtn[aria-label*="Facebook"]:hover{ background:#1877F2; color:#fff; border-color:#1877F2 }
.sbtn[aria-label*="X"]:hover, .sbtn[title="X"]:hover{ background:#111; color:#fff; border-color:#111 }
.sbtn[aria-label*="Twitter"]:hover{ background:#1DA1F2; color:#fff; border-color:#1DA1F2 }
.sbtn[aria-label*="Instagram"]:hover{
  background: radial-gradient(120px 120px at 30% 20%, #F58529, #DD2A7B 50%, #8134AF 75%, #515BD4 100%);
  color:#fff; border-color:#DD2A7B;
}

/* Contenedor de acciones */
.actions{ display:flex; align-items:center; gap:8px; justify-content:flex-end }

/* =========================
   BUSCADOR – COMPACTO + SUGERENCIAS OVERLAY (z alto)
   ========================= */
form.search{
  --radius: 999px; position:relative; display:flex; align-items:center; gap:6px;
  background:#fff; border:1.25px solid var(--border); border-radius:var(--radius);
  padding:4px 6px 4px 10px; box-shadow: var(--shadow-sm);
  transition: box-shadow var(--dur-fast), transform var(--dur-fast), border-color var(--dur-fast), background var(--dur-fast);
}
form.search:focus-within{ box-shadow: var(--shadow-md); transform:translateY(-1px); border-color: var(--prim-500); background: #fff }
.search input{
  width:180px; max-width:22vw; border:0; outline:0; background:transparent;
  font-size: var(--fs-13); line-height:1.2; color:#1a1f24; padding:6px 0; letter-spacing:.2px;
}
.search input::placeholder{ color: var(--text-muted) }
.search button{
  min-height:36px; border:0; border-radius:999px; padding:6px 12px;
  font-weight:800; font-size: var(--fs-12); letter-spacing:.35px; cursor:pointer;
  background: var(--prim-500); color:#fff;
  box-shadow: 0 10px 24px rgba(0,12,151,.22), inset 0 -2px 0 rgba(255,255,255,.12);
  transition: filter var(--dur-fast), transform var(--dur-fast), box-shadow var(--dur-fast);
}
.search button:hover{ filter:brightness(1.06) }
.search button:active{ transform: translateY(1px) scale(.99); box-shadow: 0 8px 18px rgba(0,12,151,.2) }
.search button:focus-visible{ outline: var(--focus-ring); outline-offset: var(--focus-offset) }

/* Sugerencias overlay — sobrepone subheader */
.search .search-suggest{
  position:absolute; left:0; right:0; top: calc(100% + 6px);
  background:#fff; border:1px solid var(--border); border-radius:12px;
  box-shadow: 0 26px 64px rgba(8,15,60,.22);
  padding:4px; z-index: 7500; display:none;
}
.search .search-suggest.show{ display:block }
.search .search-suggest ul{ list-style:none; padding:4px; margin:0; max-height:300px; overflow:auto }
.search .search-suggest li{ border-radius:10px; border:1px solid transparent; transition: transform var(--dur-xfast) var(--ease) }
.search .search-suggest a{
  display:flex; align-items:center; gap:10px; padding:10px 12px; text-decoration:none; color:var(--text-base);
  font-weight:700; font-size: var(--fs-13); line-height:1.25;
}
.search .search-suggest li:hover{ background: color-mix(in oklch, var(--sec-300) 26%, white); border-color: var(--sec-300); transform: translateX(2px) }
.search .search-suggest [data-role="more"]{ margin-top:4px; border-top:1px dashed var(--border); padding-top:6px }
.search .search-suggest .empty,
.search .search-suggest .suggest-loading{ padding:10px 12px; font-size: var(--fs-13); color:var(--text-muted) }

/* Portal chip – brillo direccional + leve tilt */
.portal{
  position:relative;
  display:flex; align-items:center; gap:8px; text-decoration:none;
  padding:6px 8px; border-radius:12px; background:#fff;
  border:1px dashed var(--sec-300);
  transition: background var(--dur-fast), transform var(--dur-fast), border-color var(--dur-fast), box-shadow var(--dur-fast);
  margin-left:6px; box-shadow: var(--shadow-sm);
  will-change: transform;
}
.portal::after{
  content:""; position:absolute; inset:0; border-radius:inherit; pointer-events:none;
  background: linear-gradient(110deg, transparent 0%, transparent 45%, rgba(255,255,255,.65) 50%, transparent 55%, transparent 100%);
  transform: translateX(-120%); transition: transform .6s var(--ease);
}
.portal:hover{
  background: color-mix(in oklch, var(--sec-300) 26%, white);
  transform: translateY(-1px) rotate(.15deg);
  box-shadow: 0 16px 34px rgba(8,15,60,.16), 0 2px 0 rgba(255,255,255,.6) inset;
}
.portal:hover::after{ transform: translateX(140%) }
.portal:focus-visible{ outline: var(--focus-ring); outline-offset: var(--focus-offset) }
.portal .badge{
  width:38px; height:38px; border-radius:10px; display:grid; place-items:center;
  background: radial-gradient(120px 120px at 35% 25%, var(--sec-300), var(--prim-300) 60%, transparent 100%);
  box-shadow: inset 0 6px 14px rgba(0,0,0,.08), 0 8px 18px rgba(0,0,0,.12);
  transition: transform var(--dur-fast);
}
.portal:hover .badge{ transform: translateY(-1px) }
.portal .badge svg{ width:18px; height:18px }
.portal b{ color: var(--prim-600); font-size: var(--fs-12); white-space:nowrap; letter-spacing:.25px }

/* =========================
   SUBHEADER / MENÚ (DESKTOP) – SIN BULLETS + HOVERS MEJORADOS
   ========================= */
.subheader{ position:relative; z-index:3000; margin-top:0; transition: box-shadow var(--dur) var(--ease) }
.subheader .wrap{ height:auto; padding-top:0; padding-bottom:0 }
body.nav-stick-subheader .subheader{ position:sticky; top:0; z-index:3000; box-shadow: var(--shadow-md) }

.nav-bar{
  margin-left: calc(var(--nav-left) + var(--nav-gap-from-logo)); margin-right: var(--nav-right-gutter);
  position:relative; background: var(--prim-500);
  border-radius: 0 0 26px 26px;
  padding: var(--subheader-py) 18px var(--subheader-py);
  box-shadow: 0 16px 34px rgba(0,0,0,.18);
  overflow:visible;
}
.nav-bar::before{ content:""; position:absolute; left:0; right:0; top:0; height:36%; background: linear-gradient(180deg, rgba(255,255,255,.16), rgba(255,255,255,0)) }

.menu, .menu ul, .menu li{ list-style:none; margin:0; padding:0 }
.menu > ul{ display:flex; align-items:center; gap:22px; white-space:nowrap; overflow:visible }
.menu > ul > li{ position:relative; flex:0 0 auto }

/* Halo/acento cuando el submenu está visible (además del fondo) */
.menu > ul > li.menu-item-has-children:hover > a::before,
.menu > ul > li.menu-item-has-children:focus-within > a::before{
  content:""; position:absolute; inset:-6px; border-radius:18px;
  background: radial-gradient(120px 60px at 50% 120%, rgba(126,155,242,.28), transparent 70%);
  filter: blur(10px); z-index:-1;
  transition: opacity var(--dur);
}

.menu > ul > li > a{
  color:#fff; text-decoration:none; text-transform:uppercase; font-weight:800;
  font-size: 12.75px; letter-spacing:.6px;
  padding: var(--menu-item-py) 12px; border-radius:14px; position:relative; display:inline-grid; justify-items:center; text-align:center; line-height:var(--lh-tight); min-width:116px;
  text-shadow:0 2px 4px rgba(0,0,0,.25);
  transition: transform var(--dur), background var(--dur), box-shadow var(--dur);
}
.menu > ul > li.menu-item-has-children > a{ pointer-events:none; cursor:default }
.menu > ul > li:not(.menu-item-has-children) > a:hover,
.menu > ul > li.menu-item-has-children:hover > a{ transform: translateY(-1px); background: rgba(255,255,255,.14); box-shadow: 0 4px 12px rgba(0,0,0,.15) }
.menu > ul > li > a:focus-visible{ outline:2px solid rgba(255,255,255,.7); outline-offset:3px; border-radius:12px }
.menu > ul > li > a::after{ content:""; position:absolute; left:16%; right:16%; bottom:5px; height:2px; background: rgba(255,255,255,.92); transform:scaleX(0); transform-origin:center; transition: transform var(--dur); border-radius:2px }
.menu > ul > li > a:hover::after{ transform:scaleX(1) }
.menu > ul > li.current-menu-item > a,
.menu > ul > li.current_page_item > a,
.menu > ul > li.current-menu-ancestor > a,
.menu > ul > li.current_page_ancestor > a{ background: rgba(255,255,255,.18); box-shadow: inset 0 0 0 1px rgba(255,255,255,.32), 0 4px 12px rgba(0,0,0,.12); border-radius:14px }

/* Submenu container con borde suave y animación */
.menu .sub-menu{
  position:absolute; left:0; top:calc(100% + 0px);
  min-width:300px; max-width:480px; width:max-content;
  background:
    linear-gradient(180deg, rgba(126,155,242,.12), transparent 18%),
    radial-gradient(140% 120% at 0% 0%, rgba(255,255,255,.9), rgba(255,255,255,.98) 60%, #fff);
  border:1px solid var(--border); border-radius:20px; padding:16px; z-index:4000;
  box-shadow: 0 22px 60px rgba(0,0,0,.18), 0 10px 26px rgba(0,0,0,.08), inset 0 1px 0 rgba(255,255,255,.6);
  display:grid; gap:8px;
  opacity:0; transform: translateY(8px) scale(.98); pointer-events:none;
  transition: opacity var(--dur) var(--ease), transform var(--dur) var(--ease);
  backdrop-filter: blur(10px);
}
.menu .sub-menu::before{ content:""; position:absolute; top:-8px; left:60px; width:0; height:0; border-left:8px solid transparent; border-right:8px solid transparent; border-bottom:8px solid #ffffff; z-index:1 }
.menu .sub-menu::after{ content:""; position:absolute; top:-10px; left:59px; width:0; height:0; border-left:9px solid transparent; border-right:9px solid transparent; border-bottom:9px solid var(--border); filter:blur(.2px) }
.menu > ul > li.menu-item-has-children:hover > .sub-menu,
.menu > ul > li.menu-item-has-children:focus-within > .sub-menu{ opacity:1; transform: translateY(0) scale(1); pointer-events:auto; animation: menuReveal .26s var(--ease) }

/* Items de submenu con “indicador lateral” + “flecha” al hover */
.menu .sub-menu li{ list-style:none }
.menu .sub-menu a{
  position:relative;
  display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:14px; text-decoration:none; color:#1e293b;
  font-weight:700; font-size: var(--fs-14); line-height:1.28; border:1px solid transparent;
  background: linear-gradient(135deg, #f9fbff 0%, #f1f5f9 100%);
  transition: transform var(--dur-fast), background var(--dur-fast), color var(--dur-fast), box-shadow var(--dur-fast), border-color var(--dur-fast);
  overflow:hidden;
}
.menu .sub-menu a::before{ /* indicador lateral */
  content:""; position:absolute; left:8px; top:50%; width:0; height:60%;
  background: linear-gradient(180deg, var(--sec-500), var(--prim-500));
  border-radius: 6px; transform: translateY(-50%); transition: width var(--dur-fast) var(--ease);
}
.menu .sub-menu a::after{ /* flecha deslizante */
  content:"→"; font-weight:900; color: var(--prim-500);
  margin-left:auto; opacity:.0; transform: translateX(-6px);
  transition: transform var(--dur-fast) var(--ease), opacity var(--dur-fast);
}
/* >>> CAMBIO SOLICITADO: hover de sublista a #7E9BF2 <<< */
.menu .sub-menu a:hover{
  color:#fff;
  border-color: var(--sec-500);
  background: var(--sec-500);
  box-shadow: 0 12px 30px rgba(126,155,242,.30), 0 3px 10px rgba(0,0,0,.06);
  transform: translateX(2px);
}
.menu .sub-menu a:hover::before{ width:6px }
.menu .sub-menu a:hover::after{ opacity:1; transform: translateX(0); color:#fff }

/* =========================
   FAB + MENÚ MÓVIL – POPOVER + ACORDEÓN
   ========================= */
.mobile-fab{
  position:fixed; right:12px; bottom:12px; z-index:9000; display:none;
  width:52px; height:52px; border:0; border-radius:16px; cursor:pointer;
  background: var(--prim-500); color:#fff;
  box-shadow: 0 18px 40px rgba(0,0,0,.34);
  transition: transform var(--dur-fast), box-shadow var(--dur-fast);
}
.mobile-fab:hover{ transform: translateY(-1px); box-shadow: 0 22px 46px rgba(0,0,0,.38) }
.mobile-fab:active{ transform: translateY(1px) }
.mobile-fab:focus-visible{ outline: var(--focus-ring); outline-offset: var(--focus-offset) }
.mobile-fab .bars,
.mobile-fab .bars::before,
.mobile-fab .bars::after{
  content:""; display:block; width:24px; height:2px; background:#fff; margin:0 auto; border-radius:2px;
  transition: transform .18s, opacity .18s;
}
.mobile-fab .bars{ position:relative }
.mobile-fab .bars::before{ position:absolute; top:-8px; left:0 }
.mobile-fab .bars::after{ position:absolute; top:8px; left:0 }
.mobile-fab.open .bars{ background:transparent }
.mobile-fab.open .bars::before{ transform:translateY(8px) rotate(45deg) }
.mobile-fab.open .bars::after{ transform:translateY(-8px) rotate(-45deg) }

.mobile-menu{
  position:fixed; right:12px; bottom:70px; z-index:9100;
  width:min(92vw,380px); max-height:min(86vh, 560px);
  border-radius:20px; overflow:hidden; background:#fff;
  box-shadow: 0 26px 64px rgba(0,0,0,.42), 0 1px 0 rgba(255,255,255,.22) inset;
  transform: scale(.94) translateY(10px); opacity:0; pointer-events:none;
  transition: transform var(--dur) var(--ease), opacity var(--dur) var(--ease);
  display:flex; flex-direction:column;
}
.mobile-menu.open{ opacity:1; transform: scale(1) translateY(0); pointer-events:auto }
.mm-head{
  padding:10px 14px; text-align:center; font-weight:900; color:#fff;
  background: linear-gradient(180deg, var(--prim-500), var(--prim-800));
  letter-spacing:.4px; position:sticky; top:0; z-index:2;
  text-shadow:0 1px 3px rgba(0,0,0,.25);
  box-shadow: inset 0 -1px 0 rgba(255,255,255,.18);
}
.mm-body{ padding:10px; display:grid; gap:10px; align-content:start; overflow:auto; background:#fff }
.mm-body ul{ list-style:none; margin:0; padding:0; display:grid; gap:6px }

/* Buscador móvil */
.mm-search{ display:none; position:sticky; top:0; z-index:3; background:#fff; padding:6px; border-radius:14px; box-shadow: var(--shadow-sm) }
.mm-search form.search{ width:100% }
.mm-search .search input{ width:100%; max-width:100% }

/* Ítems top-level */
.mm-body > ul > li{ position:relative }
.mm-body > ul > li > a{
  position:relative; text-decoration:none; color:var(--prim-600);
  background: color-mix(in oklch, var(--sec-300) 26%, white);
  border:1px solid var(--sec-300);
  border-radius:14px; padding:12px 44px 12px 12px;
  font-weight:900; text-transform:uppercase; font-size: var(--fs-13); display:block; line-height:1.14;
  box-shadow: var(--shadow-sm);
  transition: transform var(--dur-fast), box-shadow var(--dur-fast), background var(--dur-fast), border-color var(--dur-fast), color var(--dur-fast);
}
.mm-body > ul > li > a:hover{ transform: translateY(-1px); box-shadow: var(--shadow-md); background: color-mix(in oklch, var(--sec-300) 34%, white) }

/* ACTUAL (color) */
.mm-body > ul > li.is-current > a{
  background: linear-gradient(180deg, var(--prim-500), var(--prim-700));
  color:#fff; border-color: var(--prim-500);
  box-shadow: 0 14px 34px rgba(0,12,151,.26), inset 0 1px 0 rgba(255,255,255,.18);
}
.mm-body > ul > li.is-current > a:hover{ transform: translateY(-1px) }

/* Caret dentro del anchor */
.mm-body > ul > li.has-sub > a::after{
  content:""; position:absolute; right:12px; top:50%; margin-top:-5px;
  width:10px; height:10px; border-right:2px solid var(--prim-600); border-bottom:2px solid var(--prim-600);
  transform: rotate(45deg); transition: transform var(--dur-fast), border-color var(--dur-fast);
}
.mm-body > ul > li.is-open > a::after{ transform: rotate(-135deg) }
.mm-body > ul > li.is-current > a::after{ border-color:#fff }

/* Submenú interno */
.mm-body .sub-menu{
  position:static; display:none; gap:6px; padding:0; margin:8px 0 0 0;
  border:none; background:transparent; box-shadow:none; opacity:1; transform:none; pointer-events:auto;
}
.mm-body li.is-open > .sub-menu{ display:grid }
.mm-body .sub-menu li{ list-style:none }
.mm-body .sub-menu a{
  text-transform:none; font-size: var(--fs-13); background:#f9faff; border:1px dashed var(--border);
  border-radius:12px; padding:10px 12px; text-align:left; font-weight:700; color:var(--text-base);
  transition: background var(--dur-fast), transform var(--dur-fast), border-color var(--dur-fast);
}
.mm-body .sub-menu a:hover{ background:#eef2ff; border-color: var(--sec-500); transform: translateX(3px) }

/* Backdrop móvil */
.mobile-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.35); opacity:0; pointer-events:none; transition:opacity var(--dur) var(--ease); z-index:8900 }
.mobile-backdrop.open{ opacity:1; pointer-events:auto }

/* RELACIONES / FIXES */
.hero-carousel { z-index: 0 !important }
.hero-carousel::before, .hero-slide .veil { pointer-events: none }

/* =========================
   RESPONSIVE – Header compacto + logos más pequeños
   ========================= */
@media (max-width:1160px){
  .subheader .menu > ul{ flex-wrap:wrap; row-gap:8px; column-gap:18px; white-space:normal }
  .menu > ul > li > a{ min-width:auto; padding: var(--menu-item-py) 10px; font-size:12.5px }
  :root{ --nav-left: clamp(300px, 28vw, 520px) }
}
@media (max-width:1024px){
  :root{
    --h: clamp(48px, 7vw, 56px);
    --badge: clamp(46px, 7vw, 58px);
    --logo2-border-clip: 0%;
    --logo2-lift: 0px;
    --nav-left: clamp(220px, 40vw, 320px);
  }
  .topbar{ height:auto; padding:8px 0 }
  .wrap{ flex-wrap:wrap; gap:8px; justify-content:space-between }
  .brand{ order:1; flex:1 1 auto; justify-content:flex-start; min-width:auto }
  .logo1{ height: calc(var(--h) - 12px); min-width: clamp(80px, 36vw, 110px) }
  .logo2{ width:var(--badge); height:var(--badge); margin-left:8px; transform:none; box-shadow: var(--shadow-md) }
  .spacer{ order:2; flex:0 0 0 }

  .socials{ order:3; gap:6px }
  .actions{ order:3; justify-content:flex-end }
  .actions form.search{ display:none }

  .portal{ padding:4px 6px; border-radius:10px }
  .portal .badge{ width:28px; height:28px }
  .portal .badge svg{ width:16px; height:16px }
  .portal b{ font-size:11.75px }

  .subheader{ display:none !important }
  .mobile-fab{ display:grid; place-items:center }
  .mm-search{ display:block }
}
@media (max-width:560px){
  :root{ --h: 44px; --badge: 44px }
  .brand{ flex-wrap:nowrap }
  .logo1{ min-width: clamp(74px, 40vw, 98px) }
  .logo2{ width:var(--badge); height:var(--badge); margin-left:6px }
  .portal b{ font-size:11.5px }
}

/* REDUCED MOTION */
@media (prefers-reduced-motion: reduce){
  .logo2, .logo1, .sbtn, .portal, .search button, form.search, .mobile-menu, .menu .sub-menu{ transition:none !important; animation:none !重要 }
  .logo2::before{ display:none !important }
}

[hidden]{ display:none !important }
</style>
</head>
<body <?php body_class(); ?>>

<div class="topbar-cap"></div>

<header class="topbar">
  <div class="wrap">
    <!-- logos -->
    <div class="brand" id="brandBlock">
      <?php
      // Logo principal (Ministerio)
      $logo_minedu_id  = get_theme_mod('custom_logo_minedu');
      $logo_minedu_url = '';
      if ($logo_minedu_id) {
          $src = wp_get_attachment_image_src($logo_minedu_id, 'full');
          if ($src) { $logo_minedu_url = $src[0]; }
      }
      if (!$logo_minedu_url) {
          $logo_minedu_url = get_template_directory_uri() . '/assets/images/logo_minedu.png';
      }
      ?>
      <a href="<?php echo esc_url(home_url()); ?>" class="logo1" aria-label="Logo Ministerio">
        <img src="<?php echo esc_url($logo_minedu_url); ?>" alt="Ministerio de Educación" />
      </a>
      
      <?php
      // Logo secundario (UGEL)
      $custom_logo_id = get_theme_mod('custom_logo');
      $logo_ugel_url  = $custom_logo_id ? wp_get_attachment_image_src($custom_logo_id, 'full')[0] : get_template_directory_uri() . '/assets/images/logo.png';
      ?>
      <a href="<?php echo esc_url(home_url()); ?>" class="logo2" aria-label="Logo UGEL">
        <div class="frame">
          <img src="<?php echo esc_url($logo_ugel_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" />
        </div>
      </a>
    </div>

    <div class="spacer"></div>

    <!-- redes (derecha) -->
    <nav class="socials" aria-label="Redes sociales">
      <?php
      $facebook_url  = get_theme_mod('ugel_facebook', '#');
      $twitter_url   = get_theme_mod('ugel_twitter', '#');
      $instagram_url = get_theme_mod('ugel_instagram', '#');

      $svg_fb  = get_theme_mod('facebook_icon_svg', '');
      $svg_tw  = get_theme_mod('twitter_icon_svg', '');
      $svg_ig  = get_theme_mod('instagram_icon_svg', '');
      ?>
      <a class="sbtn" href="<?php echo esc_url($facebook_url); ?>" aria-label="Facebook" title="Facebook" target="_blank" rel="noopener">
        <?php echo $svg_fb ? ugel_sanitize_svg($svg_fb) : '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12.06C22 6.49 17.52 2 11.94 2S2 6.49 2 12.06c0 5.01 3.66 9.17 8.44 9.94v-7.03H7.9v-2.91h2.54V9.41c0-2.5 1.49-3.88 3.77-3.88 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.77 8.44-4.93 8.44-9.94z"/></svg>'; ?>
      </a>
      <a class="sbtn" href="<?php echo esc_url($twitter_url); ?>" aria-label="X/Twitter" title="X" target="_blank" rel="noopener">
        <?php echo $svg_tw ? ugel_sanitize_svg($svg_tw) : '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2H21l-6.5 7.43L22 22h-6.85l-4.81-6.26L4.9 22H2l7.03-8.03L2 2h6.93l4.37 5.77L18.244 2Zm-2.4 18h1.85L8.49 4h-1.9l9.25 16Z"/></svg>'; ?>
      </a>
      <a class="sbtn" href="<?php echo esc_url($instagram_url); ?>" aria-label="Instagram" title="Instagram" target="_blank" rel="noopener">
        <?php echo $svg_ig ? ugel_sanitize_svg($svg_ig) : '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3Zm5 3.8A5.2 5.2 0 1 1 6.8 13 5.2 5.2 0 0 1 12 7.8Zm0 2a3.2 3.2 0 1 0 3.2 3.2A3.2 3.2 0 0 0 12 9.8Zm5.55-2.85a1.15 1.15 0 1 1-1.15 1.15 1.15 1.15 0 0 1 1.15-1.15Z"/></svg>'; ?>
      </a>
    </nav>

    <!-- buscador (desktop) + portal (derecha) -->
    <div class="actions">
      <?php
        $portal_url    = get_theme_mod('portal_url', home_url('/transparencia'));
        $portal_label  = get_theme_mod('portal_label', 'Portal de Transparencia');
        $portal_target = get_theme_mod('portal_target', '_self');
        $portal_svg    = get_theme_mod('portal_icon_svg', '');
      ?>
      <a class="portal" href="<?php echo esc_url($portal_url); ?>" target="<?php echo esc_attr($portal_target); ?>" aria-label="<?php echo esc_attr($portal_label); ?>">
        <div class="badge" aria-hidden="true">
          <?php
          echo $portal_svg
            ? ugel_sanitize_svg($portal_svg)
            : '<svg viewBox="0 0 24 24" fill="#084b49" aria-hidden="true"><path d="M14.5 2H7a2 2 0 0 0-2 2v16l4-2 4 2 4-2 4 2V8.5L14.5 2Zm.5 2.5L20.5 10H16a1 1 0 0 1-1-1V4.5ZM9 9h4v2H9V9Zm0 4h6v2H9v-2Z"/></svg>';
          ?>
        </div>
        <b><?php echo esc_html($portal_label); ?></b>
      </a>

      <form class="search" id="buscarForm" role="search" aria-label="Buscar en el sitio" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input id="q" name="s" type="search" placeholder="Buscar…" autocomplete="off"
               aria-autocomplete="list" aria-expanded="false" aria-controls="suggestDesktop" />
        <button type="submit" aria-label="Buscar">BUSCAR</button>
        <!-- Sugerencias (overlay) -->
        <div class="search-suggest" id="suggestDesktop" role="listbox" hidden></div>
      </form>
    </div>
  </div>
</header>

<!-- subheader (desktop a la derecha) -->
<nav class="subheader" aria-label="Menú principal">
  <div class="wrap">
    <div class="nav-bar">
      <div class="menu">
        <?php
        wp_nav_menu(array(
          'theme_location' => 'primary',
          'container'      => false,
          'fallback_cb'    => false,
        ));
        ?>
      </div>
    </div>
  </div>
</nav>

<!-- FAB + panel menú móvil (popover) -->
<button class="mobile-fab" id="menuFab" aria-label="Abrir menú" aria-controls="mobileMenu" aria-expanded="false">
  <span class="bars"></span>
</button>

<div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true" aria-labelledby="mobileMenuTitle">
  <div class="mm-head" id="mobileMenuTitle">Menú</div>

  <!-- Buscador PRIMERO dentro del panel móvil -->
  <div class="mm-search">
    <form class="search" id="buscarFormMobile" role="search" aria-label="Buscar en el sitio (móvil)" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input name="s" type="search" placeholder="Buscar…" autocomplete="off"
             aria-autocomplete="list" aria-expanded="false" aria-controls="suggestMobile" />
      <button type="submit" aria-label="Buscar">BUSCAR</button>
      <div class="search-suggest" id="suggestMobile" role="listbox" hidden></div>
    </form>
  </div>

  <nav class="mm-body">
    <?php
    wp_nav_menu(array(
      'theme_location' => 'primary',
      'container'      => false,
      'fallback_cb'    => false,
    ));
    ?>
  </nav>
</div>
<div class="mobile-backdrop" id="mobileBackdrop"></div>

<script>
/* ===== Config WP REST para sugerencias ===== */
const WP_BASE = <?php echo json_encode( trailingslashit( home_url() ) ); ?>;
const REST_SEARCH = WP_BASE + 'wp-json/wp/v2/search?_fields=title,url,subtype&per_page=5&search=';

/* ===== Util: debounce ===== */
function debounce(fn, wait){ let t; return function(...args){ clearTimeout(t); t=setTimeout(()=>fn.apply(this,args), wait); } }

/* ===== Sugerencias de búsqueda (accesibles, overlay, máx 5) ===== */
function initSearchSuggest(form){
  if(!form) return;
  const input = form.querySelector('input[type="search"]');
  const box   = form.querySelector('.search-suggest');
  if(!input || !box) return;

  let idx = -1;
  let items = [];

  function hide(){
    box.innerHTML = '';
    box.hidden = true;
    box.classList.remove('show');
    input.setAttribute('aria-expanded','false');
    idx = -1; items = [];
  }

  function render(list, q){
    const ul = document.createElement('ul');
    if(!list || !list.length){
      ul.innerHTML = `<li class="empty">Sin resultados para “${q.replace(/</g,'&lt;')}”</li>`;
    }else{
      list.forEach((it)=>{
        const li = document.createElement('li');
        li.setAttribute('role','option');
        li.innerHTML = `<a href="${it.url}" tabindex="-1">
          <span>${it.title}</span>
          <small style="margin-left:auto;color:#667085;font-weight:600">${it.subtype || ''}</small>
        </a>`;
        ul.appendChild(li);
      });
      const more = document.createElement('div');
      more.setAttribute('data-role','more');
      more.innerHTML = `<a href="${WP_BASE}?s=${encodeURIComponent(q)}" style="display:flex;align-items:center;gap:8px;padding:10px 12px;font-weight:800;">Ver todos los resultados</a>`;
      ul.appendChild(more);
    }
    box.innerHTML = '';
    box.appendChild(ul);
    box.hidden = false;
    box.classList.add('show');
    input.setAttribute('aria-expanded','true');

    items = Array.from(box.querySelectorAll('li[role="option"] a'));
    idx = -1;
  }

  async function fetchSuggest(q){
    try{
      box.innerHTML = '<div class="suggest-loading">Buscando…</div>';
      box.hidden = false; box.classList.add('show');
      input.setAttribute('aria-expanded','true');

      const res = await fetch(REST_SEARCH + encodeURIComponent(q), { credentials:'same-origin' });
      if(!res.ok) throw new Error('HTTP '+res.status);
      const data = await res.json();
      render((data||[]).slice(0,5), q);
    }catch(e){
      render([], q);
    }
  }

  const onType = debounce(()=>{
    const q = (input.value || '').trim();
    if(q.length < 2){ hide(); return; }
    fetchSuggest(q);
  }, 150);

  input.addEventListener('input', onType);
  input.addEventListener('focus', ()=>{
    const q = (input.value||'').trim();
    if(q.length >= 2) fetchSuggest(q);
  });
  input.addEventListener('keydown', (e)=>{
    if(box.hidden) return;
    if(e.key === 'ArrowDown'){
      e.preventDefault(); if(!items.length) return;
      idx = (idx + 1) % items.length; items[idx].focus();
    }else if(e.key === 'ArrowUp'){
      e.preventDefault(); if(!items.length) return;
      idx = (idx - 1 + items.length) % items.length; items[idx].focus();
    }else if(e.key === 'Escape'){
      hide();
    }
  });

  document.addEventListener('click', (e)=>{ if(!form.contains(e.target)) hide(); });
  form.addEventListener('submit', hide);
}

/* ===== Ajuste dinámico: ancho real de logos → --nav-left ===== */
(function(){
  const root = document.documentElement;
  const brand = document.getElementById('brandBlock');
  if(!brand) return;
  let rAF;
  function syncNavLeft(){
    const w = Math.ceil(brand.getBoundingClientRect().width);
    root.style.setProperty('--nav-left', w + 'px');
  }
  function onResize(){ if(rAF) cancelAnimationFrame(rAF); rAF = requestAnimationFrame(syncNavLeft); }
  syncNavLeft();
  window.addEventListener('resize', onResize, {passive:true});
})();

/* ===== Body scrolled → elevación topbar ===== */
(function(){
  let ticking = false;
  const onScroll = () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        document.body.classList.toggle('is-scrolled', window.scrollY > 4);
        ticking = false;
      });
      ticking = true;
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

/* ===== Menú móvil – ACORDEÓN (un solo abierto) & estado actual por color ===== */
(function(){
  const fab = document.getElementById('menuFab');
  const panel = document.getElementById('mobileMenu');
  const backdrop = document.getElementById('mobileBackdrop');
  const mmBody = panel ? panel.querySelector('.mm-body') : null;
  const OPEN_CLASS = 'open';
  const LS_KEY = 'UGEL:mmLastRef';

  function openMenu(){
    fab.classList.add(OPEN_CLASS);
    panel.classList.add(OPEN_CLASS);
    backdrop.classList.add(OPEN_CLASS);
    fab.setAttribute('aria-expanded','true');
    document.body.classList.add('mm-open');
  }
  function closeMenu(){
    fab.classList.remove(OPEN_CLASS);
    panel.classList.remove(OPEN_CLASS);
    backdrop.classList.remove(OPEN_CLASS);
    fab.setAttribute('aria-expanded','false');
    document.body.classList.remove('mm-open');
    panel.querySelectorAll('li.is-open').forEach(li=>{
      li.classList.remove('is-open');
      const a = li.querySelector(':scope > a'); if(a) a.setAttribute('aria-expanded','false');
    });
  }

  if(fab){
    fab.addEventListener('click', (e)=>{ e.stopPropagation(); panel.classList.contains(OPEN_CLASS) ? closeMenu() : openMenu(); });
  }
  if(backdrop){ backdrop.addEventListener('click', closeMenu); }

  document.addEventListener('click', (e)=>{
    if(!panel.classList.contains(OPEN_CLASS)) return;
    const isInsidePanel = panel.contains(e.target);
    const isFab = fab.contains(e.target);
    const isHeader = e.target.closest('.topbar') !== null;
    if(!isInsidePanel && !isFab && !isHeader){ closeMenu(); }
  });
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && panel.classList.contains(OPEN_CLASS)) closeMenu(); });
  window.addEventListener('resize', ()=>{ if(window.innerWidth>820 && panel.classList.contains(OPEN_CLASS)) closeMenu(); });

  function setupAccordion(){
    if(!mmBody) return;
    const rootUL = mmBody.querySelector('ul.menu');
    if(!rootUL) return;

    const topLis = Array.from(rootUL.children).filter(el => el.tagName === 'LI');
    const menuId = rootUL.id || 'primary';

    topLis.forEach((li, idx)=>{
      li.dataset.idx = String(idx);
      const a = li.querySelector(':scope > a');
      const sub = li.querySelector(':scope > .sub-menu');
      if(!a) return;

      if(sub){
        li.classList.add('has-sub');
        const sid = (sub.id && sub.id.trim()) ? sub.id : `mm-sub-${menuId}-${idx}`;
        sub.id = sid;

        a.setAttribute('aria-controls', sid);
        a.setAttribute('aria-expanded','false');

        a.addEventListener('click', (e)=>{
          e.preventDefault(); e.stopPropagation();
          toggleItem(li);
        });

        sub.querySelectorAll('a').forEach(link=>{
          link.addEventListener('click', ()=>{
            const label = (link.textContent || '').trim();
            setReference(li, label, {persist:true, menuId});
            closeMenu();
          });
        });
      }
    });

    function toggleItem(li){
      const open = rootUL.querySelector('li.is-open');
      if(open && open !== li) collapse(open);
      if(li.classList.contains('is-open')) collapse(li); else expand(li);
    }
    function expand(li){
      li.classList.add('is-open');
      const a = li.querySelector(':scope > a');
      if(a) a.setAttribute('aria-expanded','true');
      setTimeout(()=>{ li.scrollIntoView({block:'nearest', behavior:'smooth'}); }, 10);
    }
    function collapse(li){
      li.classList.remove('is-open');
      const a = li.querySelector(':scope > a');
      if(a) a.setAttribute('aria-expanded','false');
    }

    function setReference(parentLi, text, opts={}){
      const {persist=false, menuId} = (opts||{});
      topLis.forEach(li=>{ if(li !== parentLi) li.classList.remove('is-current'); });
      parentLi.classList.add('is-current');
      if(persist){
        try{
          const payload = { menuId: menuId || 'primary', parentIdx: Number(parentLi.dataset.idx || 0), label: text };
          localStorage.setItem(LS_KEY, JSON.stringify(payload));
        }catch(e){}
      }
    }

    let current = rootUL.querySelector('li.current-menu-item, li.current_page_item, li.current-menu-ancestor, li.current_page_ancestor, li.current-menu-parent, li.current_page_parent');
    if(current){
      const parentTop = current.closest('ul.sub-menu') ? current.closest('ul.sub-menu').parentElement : current;
      if(parentTop && topLis.includes(parentTop)){
        expand(parentTop);
        const deep = current.querySelector('a') || parentTop.querySelector(':scope > a');
        const label = (deep && deep.textContent.trim()) || '';
        setReference(parentTop, label, {persist:true, menuId});
      }
    }else{
      try{
        const raw = localStorage.getItem(LS_KEY);
        if(raw){
          const data = JSON.parse(raw);
          if(data && String(data.menuId || 'primary') === String(menuId)){
            const li = topLis[data.parentIdx] || null;
            if(li && li.querySelector(':scope > .sub-menu')){
              expand(li);
              setReference(li, data.label || '', {persist:false});
            }
          }
        }
      }catch(e){}
    }
  }

  setupAccordion();
})();

/* ===== Inicializa sugerencias en ambos formularios ===== */
initSearchSuggest(document.getElementById('buscarForm'));
initSearchSuggest(document.getElementById('buscarFormMobile'));
</script>
</body>
</html>
