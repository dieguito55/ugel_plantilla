<?php
/**
 * Plantilla de convocatorias con directorio unificado y accesos a documentos.
 */

if (!defined('ABSPATH')) {
  exit;
}

get_header();

$convocatoria_page   = get_page_by_path('convocatoria');
$directory_base_url  = $convocatoria_page ? get_permalink($convocatoria_page) : get_post_type_archive_link('convocatorias');
if (!$directory_base_url) {
  $directory_base_url = home_url('/convocatoria/');
}

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
  'resultado_preliminar_pdf' => __('Resultado Preliminar', 'ugel-theme'),
  'resultado_final_curr_pdf' => __('Resultado Final', 'ugel-theme'),
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
    );
  }
  wp_reset_postdata();
}
?>

<section class="convocatoria-detail">
  <div class="wrap">
    <div class="convocatoria-shell">
      
      <!-- HEADER CONTAINER -->
      <div class="header-container">
        <div class="header-content">
          <div class="header-text">
            <span class="header-badge"><?php esc_html_e('Procesos institucionales', 'ugel-theme'); ?></span>
            <h1 class="header-title"><?php echo esc_html($directory_title); ?></h1>
            <p class="header-description"><?php echo esc_html($directory_summary); ?></p>
          </div>
          <div class="header-stats">
            <div class="stats-card">
              <span class="stats-number"><?php echo esc_html(number_format_i18n(count($convocatorias))); ?></span>
              <span class="stats-label"><?php esc_html_e('Convocatorias', 'ugel-theme'); ?></span>
            </div>
          </div>
        </div>
      </div>

      <?php if (!empty($convocatorias)) : ?>

      <!-- CONTROLES CONTAINER -->
      <div class="controls-container">
        <div class="control-group">
          <label for="show-entries">Mostrar:</label>
          <select id="show-entries" class="entries-select">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="-1">Todos</option>
          </select>
          <span class="control-label">entradas</span>
        </div>
        <div class="control-group search-group">
          <label for="table-search">Buscar:</label>
          <div class="search-box">
            <input type="text" id="table-search" class="search-input" placeholder="Buscar convocatorias...">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none">
              <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- TABLA CONTAINER CON SCROLL -->
      <div class="table-container">
        <div class="table-scroll-wrapper">
          <table id="convocatoria-table" class="convocatoria-table">
            <thead>
              <tr>
                <th class="col-index">Índice</th>
                <th class="col-title">Convocatoria</th>
                <th class="col-type">Tipo</th>
                <th class="col-dates">Periodo</th>
                <th class="col-status">Estado</th>
                <th class="col-doc">Bases</th>
                <th class="col-doc">Preliminar</th>
                <th class="col-doc">Final</th>
                <th class="col-doc">Resultados</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($convocatorias as $conv) : ?>
              <tr class="table-row">
                <td class="cell-index">
                  <span class="index-badge"><?php echo $conv['indice'] !== '' ? '#' . esc_html($conv['indice']) : '—'; ?></span>
                </td>
                <td class="cell-title">
                  <strong class="conv-title"><?php echo esc_html($conv['title']); ?></strong>
                </td>
                <td class="cell-type">
                  <span class="conv-type"><?php echo !empty($conv['tipo']) ? esc_html($conv['tipo']) : '—'; ?></span>
                </td>
                <td class="cell-dates">
                  <time datetime="<?php echo esc_attr($conv['date_order']); ?>" class="conv-dates"><?php echo esc_html($conv['range']); ?></time>
                </td>
                <td class="cell-status">
                  <span class="status-badge status-<?php echo esc_attr($conv['state_slug']); ?>">
                    <?php echo esc_html($conv['state_label']); ?>
                  </span>
                </td>
                <?php foreach ($docs_labels as $key => $label) :
                  $doc_url = $conv['docs'][$key] ?? '';
                  $has_doc = !empty($doc_url);
                ?>
                <td class="cell-doc">
                  <?php if ($has_doc) : ?>
                    <a class="doc-button doc-available" href="<?php echo esc_url($doc_url); ?>" target="_blank" rel="noopener noreferrer" title="Descargar <?php echo esc_attr($label); ?>">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M14 2H6C4.895 2 4 2.895 4 4V20C4 21.105 4.895 22 6 22H18C19.105 22 20 21.105 20 20V8L14 2Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M16 13H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M16 17H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M10 9H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                      </svg>
                    </a>
                  <?php else : ?>
                    <span class="doc-button doc-unavailable" title="<?php echo esc_attr($label); ?> no disponible">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M14 2H6C4.895 2 4 2.895 4 4V20C4 21.105 4.895 22 6 22H18C19.105 22 20 21.105 20 20V8L14 2Z" stroke="currentColor" stroke-width="1.5" stroke-opacity="0.4"/>
                        <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5" stroke-opacity="0.4"/>
                        <path d="M16 13H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-opacity="0.4"/>
                        <path d="M16 17H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-opacity="0.4"/>
                        <path d="M10 9H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-opacity="0.4"/>
                      </svg>
                    </span>
                  <?php endif; ?>
                </td>
                <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- FOOTER PAGINATION CONTAINER -->
      <div class="footer-container">
        <div class="table-info">
          Mostrando <span class="info-from">1</span> a <span class="info-to">10</span> de <span class="info-total"><?php echo count($convocatorias); ?></span> convocatorias
        </div>
        <div class="table-pagination">
          <button class="pagination-btn pagination-prev" disabled>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
              <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div class="pagination-pages"></div>
          <button class="pagination-btn pagination-next">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
              <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
      </div>

      <?php else : ?>
      <div class="empty-state">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
          <path d="M14 2H6C4.895 2 4 2.895 4 4V20C4 21.105 4.895 22 6 22H18C19.105 22 20 21.105 20 20V8L14 2Z" stroke="currentColor" stroke-width="1.5"/>
          <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5"/>
          <path d="M16 13H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          <path d="M16 17H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          <path d="M10 9H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <h3>No hay convocatorias disponibles</h3>
        <p>No se encontraron convocatorias activas en este momento.</p>
      </div>
      <?php endif; ?>

      <?php if (!empty($additional_content)) : ?>
      <section class="additional-content">
        <div class="content-wrapper">
          <?php echo $additional_content; ?>
        </div>
      </section>
      <?php endif; ?>

    </div>
  </div>
</section>

<style>
  :root {
    --primary: #000C97;
    --primary-dark: #021F59;
    --primary-light: #8297FE;
    --white: #FFFFFF;
    --gray-50: #F8FAFC;
    --gray-100: #F1F5F9;
    --gray-200: #E2E8F0;
    --gray-300: #CBD5E1;
    --gray-400: #94A3B8;
    --gray-500: #64748B;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1E293B;
    --success: #10B981;
    --warning: #F59E0B;
    --warning-light: #FEF3C7;
    --warning-dark: #D97706;
    --error: #EF4444;
    --error-light: #FEE2E2;
    --error-dark: #DC2626;
    --border-radius: 12px;
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s ease-in-out;
  }

  * {
    box-sizing: border-box;
  }

  .convocatoria-detail {
    padding: 2rem 0;
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
    min-height: 100vh;
    width: 100%;
  }

  .wrap {
    width: 100%;
  }

  .convocatoria-shell {
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    padding: 0 1rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    box-sizing: border-box;
  }

  /* HEADER CONTAINER */
  .header-container {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    width: 100%;
    box-sizing: border-box;
  }

  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
  }

  .header-text {
    flex: 1;
  }

  .header-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
  }

  .header-title {
    font-size: 2rem;
    font-weight: 900;
    color: var(--primary-dark);
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
  }

  .header-description {
    color: var(--gray-600);
    font-size: 1rem;
    line-height: 1.5;
    margin: 0;
  }

  .header-stats {
    flex-shrink: 0;
  }

  .stats-card {
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    color: var(--white);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    text-align: center;
    min-width: 140px;
    box-shadow: var(--shadow-md);
  }

  .stats-number {
    display: block;
    font-size: 2rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 0.5rem;
  }

  .stats-label {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  /* CONTROLES CONTAINER */
  .controls-container {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    width: 100%;
    box-sizing: border-box;
  }

  .control-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .control-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    white-space: nowrap;
  }

  .entries-select,
  .search-input {
    padding: 0.625rem 0.875rem;
    border: 1.5px solid var(--gray-300);
    border-radius: 8px;
    font-size: 0.875rem;
    background: var(--white);
    color: var(--gray-800);
    transition: var(--transition);
    font-weight: 500;
  }

  .entries-select:focus,
  .search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 12, 151, 0.1);
  }

  .control-label {
    font-size: 0.875rem;
    color: var(--gray-600);
    white-space: nowrap;
  }

  .search-box {
    position: relative;
    display: flex;
    align-items: center;
  }

  .search-input {
    padding-left: 2.5rem;
    width: 250px;
  }

  .search-icon {
    position: absolute;
    left: 0.75rem;
    color: var(--gray-400);
  }

  /* TABLA CONTAINER */
  .table-container {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
  }

  .table-scroll-wrapper {
    overflow-x: auto;
    overflow-y: auto;
    max-height: 600px;
    width: 100%;
    box-sizing: border-box;
  }

  .table-scroll-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  .table-scroll-wrapper::-webkit-scrollbar-track {
    background: var(--gray-100);
  }

  .table-scroll-wrapper::-webkit-scrollbar-thumb {
    background: var(--gray-400);
    border-radius: 4px;
  }

  .table-scroll-wrapper::-webkit-scrollbar-thumb:hover {
    background: var(--gray-500);
  }

  .convocatoria-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    min-width: 1200px;
  }

  .convocatoria-table thead {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .convocatoria-table th {
    padding: 1rem 0.875rem;
    text-align: left;
    font-weight: 700;
    color: var(--white);
    border: none;
    white-space: nowrap;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .convocatoria-table th.col-index {
    width: 80px;
    text-align: center;
  }

  .convocatoria-table th.col-title {
    min-width: 250px;
  }

  .convocatoria-table th.col-type {
    width: 120px;
  }

  .convocatoria-table th.col-dates {
    width: 150px;
  }

  .convocatoria-table th.col-status {
    width: 110px;
    text-align: center;
  }

  .convocatoria-table th.col-doc {
    width: 90px;
    text-align: center;
  }

  .convocatoria-table td {
    padding: 0.875rem 0.875rem;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
  }

  .table-row:hover {
    background: var(--gray-50);
  }

  .cell-index {
    text-align: center;
  }

  .index-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
    padding: 0.375rem 0.625rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    min-width: 40px;
  }

  .conv-title {
    font-weight: 600;
    color: var(--gray-800);
  }

  .conv-type {
    color: var(--gray-600);
    font-size: 0.875rem;
    font-weight: 500;
  }

  .conv-dates {
    color: var(--gray-600);
    font-size: 0.875rem;
  }

  .cell-status {
    text-align: center;
  }

  .status-badge {
    display: inline-block;
    padding: 0.5rem 0.875rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
  }

  .status-programado {
    background: var(--warning-light);
    color: var(--warning-dark);
    border: 1.5px solid var(--warning);
  }

  .status-en_proceso {
    background: linear-gradient(135deg, var(--success), #047857);
    color: var(--white);
  }

  .status-culminado {
    background: var(--error-light);
    color: var(--error-dark);
    border: 1.5px solid var(--error);
  }

  .cell-doc {
    text-align: center;
  }

  .doc-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    transition: var(--transition);
    border: 1.5px solid transparent;
  }

  .doc-available {
    background: rgba(0, 12, 151, 0.1);
    color: var(--primary);
    border-color: rgba(0, 12, 151, 0.2);
  }

  .doc-available:hover {
    background: rgba(0, 12, 151, 0.18);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
  }

  .doc-unavailable {
    background: var(--gray-100);
    color: var(--gray-400);
    border-color: var(--gray-300);
    cursor: not-allowed;
  }

  /* FOOTER CONTAINER */
  .footer-container {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    width: 100%;
    box-sizing: border-box;
  }

  .table-info {
    font-size: 0.875rem;
    color: var(--gray-600);
    font-weight: 500;
  }

  .info-from,
  .info-to,
  .info-total {
    font-weight: 700;
    color: var(--primary-dark);
  }

  .table-pagination {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .pagination-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border: 1.5px solid var(--gray-300);
    background: var(--white);
    border-radius: 8px;
    color: var(--gray-600);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
  }

  .pagination-btn:hover:not(:disabled) {
    border-color: var(--primary);
    color: var(--primary);
    background: rgba(0, 12, 151, 0.05);
  }

  .pagination-btn:disabled {
    background: var(--gray-100);
    color: var(--gray-400);
    cursor: not-allowed;
    border-color: var(--gray-200);
  }

  .pagination-pages {
    display: flex;
    gap: 0.25rem;
  }

  .page-btn {
    width: 38px;
    height: 38px;
    border: 1.5px solid var(--gray-300);
    background: var(--white);
    border-radius: 8px;
    color: var(--gray-700);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
  }

  .page-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: rgba(0, 12, 151, 0.05);
  }

  .page-btn.active {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-color: var(--primary);
    color: var(--white);
    box-shadow: var(--shadow-md);
  }

  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
  }

  .empty-state svg {
    color: var(--gray-400);
    margin-bottom: 1rem;
  }

  .empty-state h3 {
    color: var(--gray-700);
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 700;
  }

  .empty-state p {
    color: var(--gray-600);
    margin: 0;
  }

  .additional-content {
    margin-top: 0;
  }

  .content-wrapper {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
  }

  /* RESPONSIVE - TABLET */
  @media (max-width: 1024px) {
    .convocatoria-table th.col-title {
      min-width: 200px;
    }
  }

  @media (max-width: 768px) {
    .convocatoria-shell {
      padding: 0 0.75rem;
      gap: 1rem;
    }

    .header-container {
      padding: 1.5rem;
    }

    .header-content {
      flex-direction: column;
      gap: 1.5rem;
    }

    .header-title {
      font-size: 1.75rem;
    }

    .header-description {
      font-size: 0.95rem;
    }

    .header-stats {
      width: 100%;
    }

    .stats-card {
      width: 100%;
      min-width: auto;
    }

    .controls-container {
      flex-direction: row;
      align-items: center;
      padding: 1rem;
      gap: 1rem;
    }

    .control-group {
      width: auto;
      flex-direction: row;
    }

    .control-group label {
      display: inline;
      margin-bottom: 0;
    }

    .search-group {
      flex-direction: row;
    }

    .search-box {
      width: auto;
    }

    .search-input {
      width: 200px;
    }

    .entries-select,
    .search-input {
      padding: 0.625rem 0.75rem;
      font-size: 0.875rem;
    }

    .table-scroll-wrapper {
      max-height: 500px;
    }

    .convocatoria-table {
      font-size: 0.875rem;
      min-width: 1200px;
    }

    .convocatoria-table th {
      padding: 0.875rem 0.75rem;
      font-size: 0.875rem;
    }

    .convocatoria-table td {
      padding: 0.75rem 0.75rem;
    }

    .footer-container {
      flex-direction: column;
      padding: 1rem;
      text-align: center;
    }

    .table-info {
      order: 2;
      width: 100%;
    }

    .table-pagination {
      order: 1;
      width: 100%;
      justify-content: center;
      margin-bottom: 1rem;
    }
  }

  /* RESPONSIVE - MÓVIL */
  @media (max-width: 480px) {
    .convocatoria-shell {
      padding: 0 0.5rem;
      gap: 0.5rem;
    }

    .header-container {
      padding: 1rem 0.875rem;
    }

    .header-content {
      flex-direction: column;
      gap: 1rem;
    }

    .header-badge {
      font-size: 0.65rem;
      padding: 0.3rem 0.65rem;
      margin-bottom: 0.5rem;
    }

    .header-title {
      font-size: 1.35rem;
      margin-bottom: 0.35rem;
      font-weight: 800;
    }

    .header-description {
      font-size: 0.85rem;
      line-height: 1.4;
    }

    .header-stats {
      width: 100%;
    }

    .stats-card {
      padding: 1rem;
      width: 100%;
    }

    .stats-number {
      font-size: 1.5rem;
      margin-bottom: 0.25rem;
    }

    .stats-label {
      font-size: 0.75rem;
    }

    .controls-container {
      padding: 0.5rem;
      flex-direction: column;
      gap: 0.5rem;
    }

    .control-group {
      width: 100%;
      flex-direction: column;
    }

    .control-group label {
      font-size: 0.75rem;
      margin-bottom: 0.3rem;
    }

    .entries-select,
    .search-input {
      font-size: 0.875rem;
      padding: 0.5rem 0.625rem;
    }

    .search-input {
      padding-left: 2.25rem;
      width: 100%;
    }

    .search-icon {
      width: 14px;
      height: 14px;
      left: 0.625rem;
    }

    .table-scroll-wrapper {
      max-height: 400px;
    }

    .convocatoria-table {
      font-size: 0.875rem;
      min-width: 1200px;
    }

    .convocatoria-table th {
      padding: 0.875rem 0.75rem;
      font-size: 0.875rem;
    }

    .convocatoria-table td {
      padding: 0.75rem 0.75rem;
    }

    .footer-container {
      padding: 0.5rem;
      flex-direction: column;
      gap: 0.5rem;
    }

    .table-info {
      font-size: 0.875rem;
      width: 100%;
      order: 2;
    }

    .table-pagination {
      order: 1;
      width: 100%;
      justify-content: center;
      margin-bottom: 0;
    }

    .pagination-pages {
      display: none;
    }

    .empty-state {
      padding: 2rem 1rem;
    }

    .empty-state svg {
      width: 48px;
      height: 48px;
    }

    .empty-state h3 {
      font-size: 1rem;
    }

    .empty-state p {
      font-size: 0.85rem;
    }

    .content-wrapper {
      padding: 1rem;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const table = document.getElementById('convocatoria-table');
  const rows = table.querySelectorAll('tbody tr');
  let itemsPerPage = 10;
  let currentPage = 1;
  
  function updatePagination() {
    const totalPages = Math.ceil(rows.length / itemsPerPage);
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(currentPage * itemsPerPage, rows.length);
    
    document.querySelector('.info-from').textContent = start;
    document.querySelector('.info-to').textContent = end;
    document.querySelector('.info-total').textContent = rows.length;
    
    rows.forEach((row, index) => {
      row.style.display = (index >= start - 1 && index < end) ? '' : 'none';
    });
    
    document.querySelector('.pagination-prev').disabled = currentPage === 1;
    document.querySelector('.pagination-next').disabled = currentPage === totalPages;
    
    updatePageButtons(totalPages);
  }
  
  function updatePageButtons(totalPages) {
    const paginationPages = document.querySelector('.pagination-pages');
    paginationPages.innerHTML = '';
    
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    
    if (endPage - startPage + 1 < maxButtons) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
      const btn = document.createElement('button');
      btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
      btn.textContent = i;
      btn.addEventListener('click', () => {
        currentPage = i;
        updatePagination();
      });
      paginationPages.appendChild(btn);
    }
  }
  
  document.querySelector('.pagination-prev').addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      updatePagination();
    }
  });
  
  document.querySelector('.pagination-next').addEventListener('click', () => {
    const totalPages = Math.ceil(rows.length / itemsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      updatePagination();
    }
  });
  
  updatePagination();
  
  const searchInput = document.getElementById('table-search');
  searchInput.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    let visibleCount = 0;
    
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const isVisible = text.includes(searchTerm);
      row.style.display = isVisible ? '' : 'none';
      if (isVisible) visibleCount++;
    });
    
    currentPage = 1;
    updatePagination();
  });
  
  const entriesSelect = document.getElementById('show-entries');
  entriesSelect.addEventListener('change', function(e) {
    itemsPerPage = e.target.value === '-1' ? rows.length : parseInt(e.target.value);
    currentPage = 1;
    updatePagination();
  });
});
</script>

<?php get_footer();