<?php
/**
 * Plantilla de convocatorias con directorio unificado y accesos a documentos.
 */

if (!defined('ABSPATH')) {
  exit;
}

get_header();

$highlight_id = isset($_GET['convocatoria']) ? absint($_GET['convocatoria']) : 0;
if (!$highlight_id && isset($_GET['resaltar'])) {
  $highlight_id = absint($_GET['resaltar']);
}
if (!$highlight_id && isset($_GET['conv'])) {
  $highlight_id = absint($_GET['conv']);
}

$highlight_slug = isset($_GET['convocatoria_slug']) ? sanitize_key(wp_unslash($_GET['convocatoria_slug'])) : '';
if (!$highlight_id && $highlight_slug) {
  $highlight_post = get_page_by_path($highlight_slug, OBJECT, 'convocatorias');
  if ($highlight_post) {
    $highlight_id = (int) $highlight_post->ID;
  }
}

if (is_singular('convocatorias')) {
  $highlight_id = $highlight_id ?: get_queried_object_id();
}

$convocatoria_page   = get_page_by_path('convocatoria');
$directory_base_url  = $convocatoria_page ? get_permalink($convocatoria_page) : get_post_type_archive_link('convocatorias');
if (!$directory_base_url) {
  $directory_base_url = home_url('/convocatoria/');
}
$directory_anchor_url = $directory_base_url ? $directory_base_url . '#convocatoria-table' : '';

$queried_object    = get_queried_object();
$directory_title   = __('Convocatorias UGEL El Collao', 'ugel-theme');
$directory_summary = __('Explora todas las convocatorias vigentes y culminadas con acceso directo a sus documentos oficiales.', 'ugel-theme');
$additional_content = '';

if ($queried_object instanceof WP_Post && 'convocatorias' !== $queried_object->post_type) {
  $directory_title = get_the_title($queried_object->ID) ?: $directory_title;

  if (has_excerpt($queried_object)) {
    $directory_summary = wp_trim_words(wp_strip_all_tags($queried_object->post_excerpt), 40, '…');
  } elseif (!empty($queried_object->post_content)) {
    $directory_summary = wp_trim_words(wp_strip_all_tags($queried_object->post_content), 40, '…');
  }

  $content_raw = apply_filters('the_content', $queried_object->post_content);
  if ($content_raw && trim(wp_strip_all_tags($content_raw))) {
    $additional_content = $content_raw;
  }
}

$docs_labels = array(
  'bases_pdf'                => __('Bases', 'ugel-theme'),
  'resultado_preliminar_pdf' => __('Resultado Preliminar Curricular', 'ugel-theme'),
  'resultado_final_curr_pdf' => __('Resultado Final Curricular', 'ugel-theme'),
  'resultados_finales_pdf'   => __('Resultados Finales', 'ugel-theme'),
);

$convocatorias_query = new WP_Query(array(
  'post_type'      => 'convocatorias',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
  'orderby'        => 'date',
  'order'          => 'DESC',
));

$convocatorias = array();

if ($convocatorias_query->have_posts()) {
  while ($convocatorias_query->have_posts()) {
    $convocatorias_query->the_post();

    $post_id = get_the_ID();
    $meta    = ugel_get_convocatoria_meta($post_id);
    $state   = ugel_get_convocatoria_status_details($meta['fecha_inicio'] ?? '', $meta['fecha_fin'] ?? '');

    $indice     = $meta['indice'] ?? '';
    $tipo       = $meta['tipo'] ?? '';
    $inicio_raw = $meta['fecha_inicio'] ?? '';
    $fin_raw    = $meta['fecha_fin'] ?? '';
    $inicio_fmt = ugel_format_convocatoria_date($inicio_raw);
    $fin_fmt    = ugel_format_convocatoria_date($fin_raw);
    $range      = '';

    if ($inicio_fmt && $fin_fmt) {
      $range = sprintf(__('Del %1$s al %2$s', 'ugel-theme'), $inicio_fmt, $fin_fmt);
    } elseif ($inicio_fmt) {
      $range = sprintf(__('Desde el %s', 'ugel-theme'), $inicio_fmt);
    } elseif ($fin_fmt) {
      $range = sprintf(__('Hasta el %s', 'ugel-theme'), $fin_fmt);
    } else {
      $range = __('Fecha por confirmar', 'ugel-theme');
    }

    $convocatorias[] = array(
      'id'          => $post_id,
      'title'       => get_the_title(),
      'slug'        => get_post_field('post_name', $post_id),
      'indice'      => $indice,
      'tipo'        => $tipo,
      'range'       => $range,
      'date_order'  => $inicio_raw ?: ($fin_raw ?: get_the_date('Y-m-d', $post_id)),
      'state_slug'  => $state['slug'] ?? 'en_proceso',
      'state_label' => $state['label'] ?? __('En proceso', 'ugel-theme'),
      'docs'        => array(
        'bases_pdf'                => $meta['bases_pdf'] ?? '',
        'resultado_preliminar_pdf' => $meta['resultado_preliminar_pdf'] ?? '',
        'resultado_final_curr_pdf' => $meta['resultado_final_curr_pdf'] ?? '',
        'resultados_finales_pdf'   => $meta['resultados_finales_pdf'] ?? '',
      ),
      'descripcion' => $meta['descripcion'] ?? '',
    );
  }
  wp_reset_postdata();
}

$highlight_post = $highlight_id ? get_post($highlight_id) : null;
if ($highlight_post instanceof WP_Post && empty($additional_content)) {
  $content_raw = apply_filters('the_content', $highlight_post->post_content);
  if ($content_raw && trim(wp_strip_all_tags($content_raw))) {
    $additional_content = $content_raw;
  }
}

$highlight_meta  = $highlight_post ? ugel_get_convocatoria_meta($highlight_post->ID) : array();
$highlight_state = $highlight_post ? ugel_get_convocatoria_status_details($highlight_meta['fecha_inicio'] ?? '', $highlight_meta['fecha_fin'] ?? '') : array('slug' => '', 'label' => '');
$highlight_index = $highlight_meta['indice'] ?? '';
$highlight_type  = $highlight_meta['tipo'] ?? '';
$highlight_start = $highlight_meta['fecha_inicio'] ?? '';
$highlight_end   = $highlight_meta['fecha_fin'] ?? '';
$highlight_start_fmt = ugel_format_convocatoria_date($highlight_start);
$highlight_end_fmt   = ugel_format_convocatoria_date($highlight_end);
$highlight_range = '';

if ($highlight_start_fmt && $highlight_end_fmt) {
  $highlight_range = sprintf(__('Del %1$s al %2$s', 'ugel-theme'), $highlight_start_fmt, $highlight_end_fmt);
} elseif ($highlight_start_fmt) {
  $highlight_range = sprintf(__('Desde el %s', 'ugel-theme'), $highlight_start_fmt);
} elseif ($highlight_end_fmt) {
  $highlight_range = sprintf(__('Hasta el %s', 'ugel-theme'), $highlight_end_fmt);
} elseif ($highlight_post) {
  $highlight_range = __('Fecha por confirmar', 'ugel-theme');
}

$highlight_summary = '';
if ($highlight_post) {
  if (!empty($highlight_meta['descripcion'])) {
    $highlight_summary = wp_trim_words(wp_strip_all_tags($highlight_meta['descripcion']), 40, '…');
  } elseif (!empty($highlight_post->post_excerpt)) {
    $highlight_summary = wp_trim_words(wp_strip_all_tags($highlight_post->post_excerpt), 40, '…');
  }
}

$highlight_docs = array();
foreach ($docs_labels as $key => $label) {
  $highlight_docs[$key] = array(
    'label' => $label,
    'meta'  => $highlight_meta[$key] ?? '',
  );
}
?>

<section class="convocatoria-detail" aria-label="Directorio de convocatorias">
  <div class="wrap">
    <div class="convocatoria-shell">
      <header class="convocatoria-head">
        <div class="convocatoria-head__content">
          <span class="convocatoria-head__eyebrow"><?php esc_html_e('Procesos institucionales', 'ugel-theme'); ?></span>
          <h1 class="convocatoria-head__title"><?php echo esc_html($directory_title); ?></h1>
          <p class="convocatoria-head__summary"><?php echo esc_html($directory_summary); ?></p>
        </div>
        <div class="convocatoria-head__meta">
          <div class="convocatoria-head__badge">
            <span class="badge-value"><?php echo esc_html(number_format_i18n(count($convocatorias))); ?></span>
            <span class="badge-label"><?php esc_html_e('Convocatorias publicadas', 'ugel-theme'); ?></span>
          </div>
          <?php if ($highlight_post && $directory_anchor_url) : ?>
          <a class="convocatoria-head__reset" href="<?php echo esc_url($directory_anchor_url); ?>">
            <?php esc_html_e('Ver listado completo', 'ugel-theme'); ?>
          </a>
          <?php endif; ?>
        </div>
      </header>

      <?php if ($highlight_post) : ?>
      <section class="convocatoria-focus" id="convocatoria-focus" aria-label="Convocatoria seleccionada">
        <div class="convocatoria-focus__primary">
          <div class="convocatoria-focus__top">
            <?php if (!empty($highlight_index)) : ?>
              <span class="convocatoria-focus__index">#<?php echo esc_html($highlight_index); ?></span>
            <?php endif; ?>
            <?php if (!empty($highlight_type)) : ?>
              <span class="convocatoria-focus__type"><?php echo esc_html($highlight_type); ?></span>
            <?php endif; ?>
          </div>
          <h2 class="convocatoria-focus__title"><?php echo esc_html(get_the_title($highlight_post)); ?></h2>
          <?php if (!empty($highlight_summary)) : ?>
            <p class="convocatoria-focus__summary"><?php echo esc_html($highlight_summary); ?></p>
          <?php endif; ?>
        </div>
        <div class="convocatoria-focus__aside">
          <span class="convocatoria-chip convocatoria-chip--<?php echo esc_attr($highlight_state['slug'] ?? 'en_proceso'); ?>">
            <?php echo esc_html($highlight_state['label'] ?? __('En proceso', 'ugel-theme')); ?>
          </span>
          <?php if (!empty($highlight_range)) : ?>
            <span class="convocatoria-focus__dates"><?php echo esc_html($highlight_range); ?></span>
          <?php endif; ?>
        </div>
      </section>

      <section class="convocatoria-pdfs" aria-label="Documentos de la convocatoria destacada">
        <h2 class="convocatoria-pdfs__title"><?php esc_html_e('Documentos oficiales', 'ugel-theme'); ?></h2>
        <div class="convocatoria-pdfs__grid">
          <?php foreach ($highlight_docs as $key => $info) :
            $url         = $info['meta'];
            $label_pdf   = $info['label'];
            $has_pdf     = !empty($url);
            $gradient_id = 'pdfGradient-' . sanitize_html_class($key);
          ?>
          <article class="convocatoria-pdf <?php echo $has_pdf ? 'convocatoria-pdf--active' : 'convocatoria-pdf--empty'; ?>">
            <div class="convocatoria-pdf__icon" aria-hidden="true">
              <svg viewBox="0 0 48 48" width="48" height="48" role="img" focusable="false">
                <defs>
                  <linearGradient id="<?php echo esc_attr($gradient_id); ?>" x1="0%" x2="100%" y1="0%" y2="100%">
                    <stop offset="0%" stop-color="#000C97" />
                    <stop offset="50%" stop-color="#021F59" />
                    <stop offset="100%" stop-color="#8297FE" />
                  </linearGradient>
                </defs>
                <path fill="url(#<?php echo esc_attr($gradient_id); ?>)" d="M32 4H14a4 4 0 0 0-4 4v32a4 4 0 0 0 4 4h20a4 4 0 0 0 4-4V12L32 4z" opacity="0.12"></path>
                <path fill="url(#<?php echo esc_attr($gradient_id); ?>)" d="M32 4l6 8h-6z" opacity="0.3"></path>
                <path fill="#FFFFFF" d="M18 20h12v2H18zm0 6h12v2H18zm0 6h8v2h-8z"></path>
                <path fill="url(#<?php echo esc_attr($gradient_id); ?>)" d="M18 12h6v6h-6z" opacity="0.35"></path>
              </svg>
            </div>
            <div class="convocatoria-pdf__content">
              <h3 class="convocatoria-pdf__title"><?php echo esc_html($label_pdf); ?></h3>
              <?php if ($has_pdf) : ?>
                <a class="convocatoria-pdf__link" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                  <?php esc_html_e('Descargar PDF', 'ugel-theme'); ?>
                </a>
              <?php else : ?>
                <span class="convocatoria-pdf__missing"><?php esc_html_e('Documento no disponible', 'ugel-theme'); ?></span>
              <?php endif; ?>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <?php if (!empty($convocatorias)) : ?>
      <div class="convocatoria-table__wrapper" id="directorio-convocatorias">
        <table id="convocatoria-table" class="convocatoria-table display nowrap" style="width:100%" data-highlight-id="<?php echo esc_attr($highlight_post ? $highlight_post->ID : 0); ?>">
          <thead>
            <tr>
              <th><?php esc_html_e('Índice', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Convocatoria', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Tipo', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Fechas', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Estado', 'ugel-theme'); ?></th>
              <?php foreach ($docs_labels as $label) : ?>
              <th><?php echo esc_html($label); ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($convocatorias as $conv) :
              $row_classes = array();
              if ($highlight_post && (int) $conv['id'] === (int) $highlight_post->ID) {
                $row_classes[] = 'is-highlighted';
              }
            ?>
            <tr id="convocatoria-<?php echo esc_attr($conv['id']); ?>" class="<?php echo esc_attr(implode(' ', $row_classes)); ?>">
              <td data-label="<?php esc_attr_e('Índice', 'ugel-theme'); ?>" data-order="<?php echo esc_attr($conv['indice'] !== '' ? (int) $conv['indice'] : 0); ?>">
                <?php echo $conv['indice'] !== '' ? '#' . esc_html($conv['indice']) : '—'; ?>
              </td>
              <td data-label="<?php esc_attr_e('Convocatoria', 'ugel-theme'); ?>" class="convocatoria-table__title">
                <strong><?php echo esc_html($conv['title']); ?></strong>
              </td>
              <td data-label="<?php esc_attr_e('Tipo', 'ugel-theme'); ?>">
                <?php echo !empty($conv['tipo']) ? esc_html($conv['tipo']) : '—'; ?>
              </td>
              <td data-label="<?php esc_attr_e('Fechas', 'ugel-theme'); ?>" data-order="<?php echo esc_attr($conv['date_order']); ?>">
                <time datetime="<?php echo esc_attr($conv['date_order']); ?>"><?php echo esc_html($conv['range']); ?></time>
              </td>
              <td data-label="<?php esc_attr_e('Estado', 'ugel-theme'); ?>">
                <span class="convocatoria-chip convocatoria-chip--<?php echo esc_attr($conv['state_slug']); ?>"><?php echo esc_html($conv['state_label']); ?></span>
              </td>
              <?php foreach ($docs_labels as $key => $label) :
                $doc_url = $conv['docs'][$key] ?? '';
                $has_doc = !empty($doc_url);
              ?>
              <td data-label="<?php echo esc_attr($label); ?>" data-order="<?php echo $has_doc ? 1 : 0; ?>">
                <?php if ($has_doc) : ?>
                  <a class="convocatoria-doc convocatoria-doc--active" href="<?php echo esc_url($doc_url); ?>" target="_blank" rel="noopener noreferrer">
                    <span class="convocatoria-doc__icon" aria-hidden="true">📄</span>
                    <span class="convocatoria-doc__label"><?php esc_html_e('Descargar', 'ugel-theme'); ?></span>
                  </a>
                <?php else : ?>
                  <span class="convocatoria-doc convocatoria-doc--empty">
                    <span class="convocatoria-doc__icon" aria-hidden="true">⏳</span>
                    <span class="convocatoria-doc__label"><?php esc_html_e('Pendiente', 'ugel-theme'); ?></span>
                  </span>
                <?php endif; ?>
              </td>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else : ?>
        <p class="convocatoria-empty"><?php esc_html_e('No hay convocatorias disponibles por ahora.', 'ugel-theme'); ?></p>
      <?php endif; ?>

      <?php if (!empty($additional_content)) : ?>
      <section class="convocatoria-body" aria-label="Información adicional">
        <div class="convocatoria-body__content">
          <?php echo $additional_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
      </section>
      <?php endif; ?>
    </div>
  </div>
</section>

<style>
  .convocatoria-detail {
    padding: clamp(48px, 6vw, 72px) 0;
    background: linear-gradient(180deg, #F5F8FF 0%, #FFFFFF 50%, #F8FAFF 100%);
  }
  .convocatoria-shell {
    display: flex;
    flex-direction: column;
    gap: clamp(28px, 4vw, 48px);
  }
  .convocatoria-head {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: clamp(18px, 4vw, 36px);
    background: #FFFFFF;
    border: 1px solid rgba(130, 151, 254, 0.18);
    border-radius: 20px;
    padding: clamp(24px, 4vw, 36px);
    box-shadow:
      0 12px 38px rgba(2, 31, 89, 0.08),
      0 6px 20px rgba(2, 31, 89, 0.05),
      inset 0 1px 0 rgba(255, 255, 255, 0.9);
  }
  .convocatoria-head__content {
    max-width: min(600px, 100%);
  }
  .convocatoria-head__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    background: rgba(0, 12, 151, 0.12);
    color: #000C97;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.6px;
  }
  .convocatoria-head__title {
    margin: 14px 0 12px;
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 900;
    color: #021F59;
    line-height: 1.18;
  }
  .convocatoria-head__summary {
    margin: 0;
    color: #475569;
    font-size: 1rem;
    max-width: 54ch;
  }
  .convocatoria-head__meta {
    display: flex;
    flex-direction: column;
    gap: 16px;
    align-items: flex-end;
  }
  .convocatoria-head__badge {
    display: grid;
    place-items: center;
    gap: 4px;
    padding: 18px 24px;
    border-radius: 16px;
    background: linear-gradient(160deg, rgba(0, 12, 151, 0.08), rgba(130, 151, 254, 0.12));
    border: 1px solid rgba(130, 151, 254, 0.18);
    text-align: center;
    min-width: 200px;
  }
  .badge-value {
    font-size: clamp(1.8rem, 3vw, 2.2rem);
    font-weight: 900;
    color: #021F59;
  }
  .badge-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  .convocatoria-head__reset {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
    color: #000C97;
    text-decoration: none;
  }
  .convocatoria-head__reset::before {
    content: '↺';
    font-size: 1rem;
  }
  .convocatoria-head__reset:hover {
    text-decoration: underline;
  }
  .convocatoria-focus {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 18px;
    background: linear-gradient(135deg, rgba(178, 255, 255, 0.25), rgba(130, 151, 254, 0.15));
    border: 1px solid rgba(0, 12, 151, 0.16);
    border-radius: 20px;
    padding: clamp(20px, 3vw, 28px);
    box-shadow:
      0 18px 42px rgba(2, 31, 89, 0.12),
      inset 0 1px 0 rgba(255, 255, 255, 0.95);
  }
  .convocatoria-focus__primary {
    flex: 1 1 380px;
  }
  .convocatoria-focus__top {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
  }
  .convocatoria-focus__index {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 14px;
    border-radius: 999px;
    background: rgba(0, 12, 151, 0.85);
    color: #FFFFFF;
    font-weight: 800;
    letter-spacing: 0.6px;
  }
  .convocatoria-focus__type {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.8);
    color: #021F59;
    font-weight: 700;
    border: 1px solid rgba(0, 12, 151, 0.16);
  }
  .convocatoria-focus__title {
    margin: 0 0 10px;
    font-size: clamp(1.5rem, 2.4vw, 2rem);
    font-weight: 900;
    color: #021F59;
  }
  .convocatoria-focus__summary {
    margin: 0;
    color: #1E293B;
    max-width: 70ch;
  }
  .convocatoria-focus__aside {
    display: grid;
    align-items: center;
    gap: 12px;
    text-align: right;
  }
  .convocatoria-focus__dates {
    font-weight: 700;
    color: #021F59;
  }
  .convocatoria-table__wrapper {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid rgba(130, 151, 254, 0.18);
    padding: clamp(20px, 3vw, 28px);
    box-shadow:
      0 10px 32px rgba(2, 31, 89, 0.07),
      0 4px 16px rgba(2, 31, 89, 0.04),
      inset 0 1px 0 rgba(255, 255, 255, 0.9);
  }
  table.convocatoria-table {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 0.95rem;
  }
  .convocatoria-table thead th {
    background: linear-gradient(120deg, rgba(0, 12, 151, 0.08), rgba(2, 31, 89, 0.04));
    color: #021F59;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 14px;
    border-bottom: 2px solid rgba(0, 12, 151, 0.12);
  }
  .convocatoria-table tbody td {
    padding: 16px 14px;
    border-bottom: 1px solid rgba(130, 151, 254, 0.12);
    color: #0F172A;
    font-weight: 600;
    vertical-align: middle;
  }
  .convocatoria-table__title strong {
    font-weight: 800;
    color: #021F59;
  }
  .convocatoria-table tbody tr.is-highlighted {
    background: linear-gradient(135deg, rgba(0, 12, 151, 0.06), rgba(130, 151, 254, 0.08));
    box-shadow: inset 0 0 0 2px rgba(0, 12, 151, 0.18);
  }
  .convocatoria-table tbody tr.is-highlighted td:first-child::before {
    content: '★';
    color: #FFD166;
    margin-right: 6px;
  }
  .convocatoria-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 800;
    letter-spacing: 0.6px;
    text-transform: uppercase;
  }
  .convocatoria-chip--programado {
    background: rgba(130, 151, 254, 0.18);
    color: #021F59;
  }
  .convocatoria-chip--en_proceso {
    background: rgba(0, 12, 151, 0.16);
    color: #FFFFFF;
  }
  .convocatoria-chip--culminado {
    background: rgba(148, 163, 184, 0.18);
    color: #334155;
  }
  .convocatoria-doc {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.85rem;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid transparent;
  }
  .convocatoria-doc--active {
    background: rgba(0, 12, 151, 0.12);
    color: #000C97;
    border-color: rgba(0, 12, 151, 0.18);
  }
  .convocatoria-doc--active:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(2, 31, 89, 0.14);
  }
  .convocatoria-doc--empty {
    background: rgba(241, 245, 249, 0.8);
    color: #94A3B8;
    border-color: rgba(148, 163, 184, 0.3);
    cursor: not-allowed;
  }
  .convocatoria-doc__icon {
    font-size: 1rem;
  }
  .convocatoria-doc__label {
    white-space: nowrap;
  }
  .convocatoria-pdfs {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .convocatoria-pdfs__title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 800;
    color: #021F59;
  }
  .convocatoria-pdfs__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
  }
  .convocatoria-pdf {
    background: #FFFFFF;
    border-radius: 18px;
    border: 1px solid rgba(130, 151, 254, 0.16);
    padding: 18px;
    display: flex;
    gap: 14px;
    align-items: center;
    box-shadow:
      0 10px 28px rgba(2, 31, 89, 0.08),
      inset 0 1px 0 rgba(255, 255, 255, 0.95);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }
  .convocatoria-pdf--empty {
    background: linear-gradient(135deg, rgba(130, 151, 254, 0.08), rgba(178, 255, 255, 0.08));
    border-style: dashed;
    opacity: 0.85;
  }
  .convocatoria-pdf--active:hover {
    transform: translateY(-2px);
    box-shadow:
      0 14px 32px rgba(2, 31, 89, 0.1),
      inset 0 1px 0 rgba(255, 255, 255, 0.95);
  }
  .convocatoria-pdf__icon {
    flex-shrink: 0;
  }
  .convocatoria-pdf__title {
    margin: 0 0 6px 0;
    font-size: 1rem;
    font-weight: 800;
    color: #021F59;
  }
  .convocatoria-pdf__link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.85rem;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid transparent;
  }
  .convocatoria-doc--active {
    background: rgba(0, 12, 151, 0.12);
    color: #000C97;
    border-color: rgba(0, 12, 151, 0.18);
  }
  .convocatoria-doc--active:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(2, 31, 89, 0.14);
  }
  .convocatoria-doc--empty {
    background: rgba(241, 245, 249, 0.8);
    color: #94A3B8;
    border-color: rgba(148, 163, 184, 0.3);
    cursor: not-allowed;
  }
  .convocatoria-doc__icon {
    font-size: 1rem;
  }
  .convocatoria-doc__label {
    white-space: nowrap;
  }
  .convocatoria-body__content {
    background: #FFFFFF;
    border-radius: 18px;
    border: 1px solid rgba(130, 151, 254, 0.16);
    padding: clamp(24px, 4vw, 36px);
    box-shadow:
      0 12px 34px rgba(2, 31, 89, 0.08),
      inset 0 1px 0 rgba(255, 255, 255, 0.92);
  }
  .convocatoria-body__content h2,
  .convocatoria-body__content h3,
  .convocatoria-body__content h4 {
    color: #021F59;
    font-weight: 800;
  }
  .convocatoria-body__content a {
    color: #000C97;
    font-weight: 700;
  }
  .convocatoria-empty {
    margin: 0;
    font-weight: 700;
    color: #475569;
  }
  @media (max-width: 1024px) {
    .convocatoria-head {
      flex-direction: column;
      align-items: flex-start;
    }
    .convocatoria-head__meta {
      align-items: flex-start;
    }
    .convocatoria-focus__aside {
      text-align: left;
      justify-items: flex-start;
    }
  }
  @media (max-width: 768px) {
    .convocatoria-table__wrapper {
      padding: 18px;
    }
    .convocatoria-doc {
      width: 100%;
      justify-content: center;
    }
    .convocatoria-doc {
      width: 100%;
      justify-content: center;
    }
  }
</style>

<script>
  jQuery(function($) {
    var $table = $('#convocatoria-table');
    if ($table.length && $.fn.DataTable) {
      var highlightId = parseInt($table.data('highlightId'), 10) || 0;
      var highlightSelector = highlightId ? '#convocatoria-' + highlightId : null;

      var dataTable = $table.DataTable({
        responsive: true,
        pageLength: -1,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, '<?php echo esc_js(__('Todos', 'ugel-theme')); ?>']],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[3, 'desc']],
        columnDefs: [
          { targets: [5, 6, 7, 8], orderable: false, searchable: false },
          { targets: 1, responsivePriority: 1 },
          { targets: 4, responsivePriority: 2 }
        ]
      });

      if (highlightSelector) {
        var $highlightRow = $(highlightSelector);
        if ($highlightRow.length) {
          dataTable.on('draw', function() {
            var $row = $(highlightSelector);
            if ($row.length) {
              $('html, body').stop(true).animate({
                scrollTop: $row.offset().top - 160
              }, 420);
            }
          });

          setTimeout(function() {
            $('html, body').stop(true).animate({
              scrollTop: $highlightRow.offset().top - 160
            }, 420);
          }, 360);
        }
      }
    }
  });
</script>

<?php get_footer(); ?>
