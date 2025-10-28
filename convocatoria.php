<?php
/**
 * Plantilla personalizada para el archivo de Convocatorias.
 * Presenta un listado tabular con filtros, paginación y enlaces a documentos.
 */

get_header();

$title        = post_type_archive_title('', false);
$description  = get_the_archive_description();
$conv_query   = new WP_Query(array(
  'post_type'      => 'convocatorias',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
));

$records = array();

if ($conv_query->have_posts()) {
  while ($conv_query->have_posts()) {
    $conv_query->the_post();
    $details = function_exists('ugel_get_convocatoria_details') ? ugel_get_convocatoria_details(get_the_ID()) : array();

    $records[] = array(
      'indice'              => $details['indice'] ?? '',
      'titulo'              => $details['titulo'] ?? get_the_title(),
      'permalink'           => $details['permalink'] ?? get_permalink(),
      'tipo'                => $details['tipo'] ?? '',
      'estado'              => $details['estado'] ?? '',
      'estado_slug'         => $details['estado_slug'] ?? '',
      'fecha_inicio'        => $details['fecha_inicio'] ?? '',
      'fecha_inicio_fmt'    => $details['fecha_inicio_fmt'] ?? '',
      'fecha_fin_fmt'       => $details['fecha_fin_fmt'] ?? '',
      'fecha_rango'         => $details['fecha_rango'] ?? '',
      'descripcion'         => $details['descripcion'] ?? '',
      'pdf_bases'           => $details['pdf_bases'] ?? '',
      'pdf_preliminar'      => $details['pdf_preliminar'] ?? '',
      'pdf_final_curricular'=> $details['pdf_final_curricular'] ?? '',
      'pdf_resultados'      => $details['pdf_resultados'] ?? '',
    );
  }
  wp_reset_postdata();
}

usort($records, function($a, $b) {
  $a_has_index = $a['indice'] !== '';
  $b_has_index = $b['indice'] !== '';

  if ($a_has_index && $b_has_index) {
    $cmp = strnatcasecmp($a['indice'], $b['indice']);
    if ($cmp !== 0) {
      return $cmp;
    }
  } elseif ($a_has_index || $b_has_index) {
    return $a_has_index ? -1 : 1;
  }

  $a_time = $a['fecha_inicio'] ? strtotime($a['fecha_inicio']) : 0;
  $b_time = $b['fecha_inicio'] ? strtotime($b['fecha_inicio']) : 0;

  if ($a_time !== $b_time) {
    return ($a_time > $b_time) ? -1 : 1;
  }

  return strnatcasecmp($a['titulo'], $b['titulo']);
});

$total_records = count($records);

foreach ($records as $idx => $record) {
  $display_index = $record['indice'] !== '' ? $record['indice'] : (string) ($idx + 1);
  $records[$idx]['display_index'] = $display_index;

  $search_bits = array(
    $display_index,
    $record['titulo'],
    $record['tipo'],
    $record['estado'],
    $record['fecha_inicio_fmt'],
    $record['fecha_fin_fmt'],
    $record['fecha_rango'],
    $record['descripcion'],
  );

  $search_text = implode(' ', array_filter($search_bits));
  $records[$idx]['search_text'] = function_exists('mb_strtolower')
    ? mb_strtolower($search_text, 'UTF-8')
    : strtolower($search_text);
}

$pdf_icon = '<svg class="pdf-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">'
  .'<path fill="currentColor" d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm9 8H9v2h2v4h2v-4h2v-2Zm-3-6v4h4l-4-4Z"/>'
  .'</svg>';

$doc_labels = array(
  'pdf_bases'            => __('Bases', 'ugel-theme'),
  'pdf_preliminar'       => __('Resultado preliminar curricular', 'ugel-theme'),
  'pdf_final_curricular' => __('Resultado final curricular', 'ugel-theme'),
  'pdf_resultados'       => __('Resultados finales', 'ugel-theme'),
);
?>

<section class="convocatoria-archive" data-convocatoria-table>
  <div class="wrap">
    <header class="convocatoria-archive__header">
      <div class="convocatoria-archive__title-block">
        <?php if (function_exists('ugel_breadcrumbs')): ?>
          <?php ugel_breadcrumbs(); ?>
        <?php endif; ?>
        <h1 class="convocatoria-archive__title"><?php echo esc_html($title ?: __('Convocatorias', 'ugel-theme')); ?></h1>
      </div>
      <?php if ($description): ?>
        <div class="convocatoria-archive__intro"><?php echo wp_kses_post($description); ?></div>
      <?php endif; ?>
    </header>

    <div class="convocatoria-archive__controls">
      <label class="convocatoria-archive__length" for="conv-length">
        <span><?php esc_html_e('Mostrar', 'ugel-theme'); ?></span>
        <select id="conv-length" data-length>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
          <option value="all"><?php esc_html_e('Todos', 'ugel-theme'); ?></option>
        </select>
      </label>
      <label class="convocatoria-archive__search" for="conv-search">
        <span><?php esc_html_e('Buscar', 'ugel-theme'); ?></span>
        <input type="search" id="conv-search" placeholder="<?php esc_attr_e('Buscar convocatoria…', 'ugel-theme'); ?>" data-search />
      </label>
    </div>

    <div class="convocatoria-archive__table">
      <table>
        <thead>
          <tr>
            <th scope="col"><?php esc_html_e('Índice', 'ugel-theme'); ?></th>
            <th scope="col"><?php esc_html_e('Convocatoria', 'ugel-theme'); ?></th>
            <th scope="col"><?php esc_html_e('Tipo de convocatoria', 'ugel-theme'); ?></th>
            <th scope="col"><?php esc_html_e('Fecha de inicio', 'ugel-theme'); ?></th>
            <th scope="col"><?php esc_html_e('Estado', 'ugel-theme'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($records): ?>
            <?php foreach ($records as $record):
              $docs = array();
              foreach ($doc_labels as $key => $label) {
                if (!empty($record[$key])) {
                  $docs[] = array('label' => $label, 'url' => $record[$key]);
                }
              }
            ?>
            <tr data-search="<?php echo esc_attr($record['search_text']); ?>">
              <td data-label="<?php esc_attr_e('Índice', 'ugel-theme'); ?>"><?php echo esc_html($record['display_index']); ?></td>
              <td data-label="<?php esc_attr_e('Convocatoria', 'ugel-theme'); ?>">
                <a class="convocatoria-table__title" href="<?php echo esc_url($record['permalink']); ?>">
                  <?php echo esc_html($record['titulo']); ?>
                </a>
                <?php if (!empty($record['descripcion'])): ?>
                  <p class="convocatoria-table__description"><?php echo esc_html($record['descripcion']); ?></p>
                <?php endif; ?>
                <?php if ($docs): ?>
                  <ul class="convocatoria-table__docs">
                    <?php foreach ($docs as $doc): ?>
                      <li>
                        <a class="convocatoria-table__doc" href="<?php echo esc_url($doc['url']); ?>" target="_blank" rel="noopener">
                          <?php echo $pdf_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                          <span><?php echo esc_html($doc['label']); ?></span>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </td>
              <td data-label="<?php esc_attr_e('Tipo de convocatoria', 'ugel-theme'); ?>">
                <?php echo $record['tipo'] ? esc_html($record['tipo']) : '—'; ?>
              </td>
              <td data-label="<?php esc_attr_e('Fecha de inicio', 'ugel-theme'); ?>">
                <?php echo $record['fecha_inicio_fmt'] ? esc_html($record['fecha_inicio_fmt']) : '—'; ?>
              </td>
              <td data-label="<?php esc_attr_e('Estado', 'ugel-theme'); ?>">
                <?php if ($record['estado']): ?>
                  <span class="status-pill<?php echo $record['estado_slug'] ? ' status-' . esc_attr($record['estado_slug']) : ''; ?>"><?php echo esc_html($record['estado']); ?></span>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="convocatoria-archive__footer">
      <p class="convocatoria-archive__info" data-count><?php printf(esc_html__('Mostrando 0 de %d convocatorias', 'ugel-theme'), $total_records); ?></p>
      <nav class="convocatoria-archive__pager" data-pager aria-label="<?php esc_attr_e('Paginación de convocatorias', 'ugel-theme'); ?>"></nav>
    </div>

    <p class="convocatoria-archive__empty" data-empty <?php echo $records ? 'hidden' : ''; ?>>
      <?php esc_html_e('No se encontraron convocatorias para mostrar.', 'ugel-theme'); ?>
    </p>
  </div>
</section>

<?php
get_footer();
