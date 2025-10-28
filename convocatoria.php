<?php
/**
 * Template Name: Convocatorias
 * Description: Listado general de convocatorias con tabla interactiva y descargas.
 */

if (!defined('ABSPATH')) {
  exit;
}

get_header();

$page_object   = get_queried_object();
$page_title    = $page_object ? get_the_title($page_object) : __('Convocatorias', 'ugel-theme');
$page_excerpt  = '';
$page_content  = '';
$page_url      = $page_object ? get_permalink($page_object) : home_url('/convocatoria/');

if ($page_object instanceof WP_Post) {
  if (!empty($page_object->post_excerpt)) {
    $page_excerpt = wpautop($page_object->post_excerpt);
  } elseif (!empty($page_object->post_content)) {
    $page_content = apply_filters('the_content', $page_object->post_content);
  }
}

$highlight_id  = isset($_GET['convocatoria']) ? absint($_GET['convocatoria']) : 0;
$conv_query    = get_posts(array(
  'post_type'      => 'convocatorias',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
  'orderby'        => 'date',
  'order'          => 'DESC',
));

$documents_map = array(
  'bases_pdf'                => __('Bases', 'ugel-theme'),
  'resultado_preliminar_pdf' => __('Resultado Preliminar Curricular', 'ugel-theme'),
  'resultado_final_curr_pdf' => __('Resultado Final Curricular', 'ugel-theme'),
  'resultados_finales_pdf'   => __('Resultados Finales', 'ugel-theme'),
);
?>

<section class="convocatorias-archive" aria-label="Convocatorias disponibles">
  <div class="wrap">
    <div class="convocatorias-panel">
      <header class="convocatorias-archive__header">
        <span class="convocatorias-archive__badge"><?php esc_html_e('Procesos vigentes y culminados', 'ugel-theme'); ?></span>
        <h1 class="convocatorias-archive__title"><?php echo esc_html($page_title); ?></h1>
        <?php if ($page_excerpt): ?>
          <div class="convocatorias-archive__summary"><?php echo wp_kses_post($page_excerpt); ?></div>
        <?php elseif ($page_content): ?>
          <div class="convocatorias-archive__summary"><?php echo wp_kses_post($page_content); ?></div>
        <?php endif; ?>
      </header>

      <div class="convocatorias-table__wrap" id="convocatoriasTable" data-highlight="<?php echo esc_attr($highlight_id); ?>">
        <?php if (!empty($conv_query)): ?>
          <table id="tablaConvocatorias" class="convocatorias-table display nowrap" style="width:100%">
            <thead>
              <tr>
                <th><?php esc_html_e('Índice', 'ugel-theme'); ?></th>
                <th><?php esc_html_e('Convocatoria', 'ugel-theme'); ?></th>
                <th><?php esc_html_e('Tipo', 'ugel-theme'); ?></th>
                <th><?php esc_html_e('Fecha de inicio', 'ugel-theme'); ?></th>
                <th><?php esc_html_e('Estado', 'ugel-theme'); ?></th>
                <?php foreach ($documents_map as $label): ?>
                  <th><?php echo esc_html($label); ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($conv_query as $conv_post):
                $meta         = ugel_get_convocatoria_meta($conv_post->ID);
                $indice       = $meta['indice'] ?? '';
                $tipo         = $meta['tipo'] ?? '';
                $fi_raw       = $meta['fecha_inicio'] ?? '';
                $ff_raw       = $meta['fecha_fin'] ?? '';
                $fi           = ugel_format_convocatoria_date($fi_raw);
                $ff           = ugel_format_convocatoria_date($ff_raw);
                $estado       = ugel_get_convocatoria_status_details($fi_raw, $ff_raw);
                $estado_slug  = isset($estado['slug']) ? sanitize_html_class($estado['slug']) : 'en_proceso';
                $estado_label = $estado['label'] ?? __('En proceso', 'ugel-theme');
                $order_date   = $fi_raw ? strtotime($fi_raw) : false;
                if (!$order_date && $ff_raw) {
                  $order_date = strtotime($ff_raw);
                }
                if (!$order_date) {
                  $order_date = get_post_time('U', true, $conv_post);
                }
                $order_date = $order_date ?: time();

                if ($fi && $ff) {
                  $fecha_texto = sprintf(__('Del %1$s al %2$s', 'ugel-theme'), $fi, $ff);
                } elseif ($fi) {
                  $fecha_texto = sprintf(__('Desde el %s', 'ugel-theme'), $fi);
                } elseif ($ff) {
                  $fecha_texto = sprintf(__('Hasta el %s', 'ugel-theme'), $ff);
                } else {
                  $fecha_texto = __('Fecha por definir', 'ugel-theme');
                }

                $row_class = $highlight_id === $conv_post->ID ? ' is-highlight' : '';
                $row_url   = add_query_arg('convocatoria', $conv_post->ID, $page_url);
              ?>
              <tr class="convocatorias-table__row<?php echo esc_attr($row_class); ?>" data-convocatoria="<?php echo esc_attr($conv_post->ID); ?>">
                <td data-title="<?php esc_attr_e('Índice', 'ugel-theme'); ?>">
                  <?php echo $indice ? esc_html($indice) : '—'; ?>
                </td>
                <td data-title="<?php esc_attr_e('Convocatoria', 'ugel-theme'); ?>">
                  <a href="<?php echo esc_url($row_url); ?>" class="convocatorias-table__link"><?php echo esc_html(get_the_title($conv_post)); ?></a>
                </td>
                <td data-title="<?php esc_attr_e('Tipo', 'ugel-theme'); ?>">
                  <?php echo $tipo ? esc_html($tipo) : '—'; ?>
                </td>
                <td data-title="<?php esc_attr_e('Fecha de inicio', 'ugel-theme'); ?>" data-order="<?php echo esc_attr($order_date); ?>">
                  <span class="convocatorias-table__date"><?php echo esc_html($fecha_texto); ?></span>
                  <?php if ($fi && $ff): ?>
                    <small class="convocatorias-table__date-range"><?php echo esc_html($fi . ' – ' . $ff); ?></small>
                  <?php endif; ?>
                </td>
                <td data-title="<?php esc_attr_e('Estado', 'ugel-theme'); ?>">
                  <span class="convocatoria-chip convocatoria-chip--<?php echo esc_attr($estado_slug); ?>"><?php echo esc_html($estado_label); ?></span>
                </td>
                <?php foreach ($documents_map as $meta_key => $label):
                  $doc_url = isset($meta[$meta_key]) ? $meta[$meta_key] : '';
                ?>
                <td data-title="<?php echo esc_attr($label); ?>" class="convocatorias-table__doc">
                  <?php if (!empty($doc_url)): ?>
                    <a class="convocatorias-table__doc-link" href="<?php echo esc_url($doc_url); ?>" target="_blank" rel="noopener">
                      <span class="screen-reader-text"><?php printf(esc_html__('Descargar %s', 'ugel-theme'), esc_html($label)); ?></span>
                      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm8 2v4h4"/><path d="M8 13h8v2H8zm0 4h8v2H8zm0-8h4v2H8z"/></svg>
                    </a>
                  <?php else: ?>
                    <span class="convocatorias-table__doc-empty" aria-hidden="true">—</span>
                  <?php endif; ?>
                </td>
                <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="convocatorias-empty">
            <svg viewBox="0 0 64 64" aria-hidden="true" focusable="false"><path d="M12 8h28l12 12v28a8 8 0 0 1-8 8H12a8 8 0 0 1-8-8V16a8 8 0 0 1 8-8Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><path d="M40 8v12h12" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><path d="M19 32h26M19 42h26M19 22h10" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
            <p><?php esc_html_e('Aún no hay convocatorias registradas.', 'ugel-theme'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($conv_query)): ?>
  <script>
    jQuery(function($){
      var $table = $('#tablaConvocatorias');
      if(!$table.length) return;

      var dataTable = $table.DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?php echo esc_js(__('Todos', 'ugel-theme')); ?>']],
        order: [[3, 'desc']],
        language: {
          decimal: '',
          emptyTable: '<?php echo esc_js(__('No hay registros disponibles', 'ugel-theme')); ?>',
          info: '<?php echo esc_js(__('Mostrando _START_ a _END_ de _TOTAL_ convocatorias', 'ugel-theme')); ?>',
          infoEmpty: '<?php echo esc_js(__('Mostrando 0 a 0 de 0 convocatorias', 'ugel-theme')); ?>',
          infoFiltered: '<?php echo esc_js(__('(filtrado de _MAX_ convocatorias en total)', 'ugel-theme')); ?>',
          lengthMenu: '<?php echo esc_js(__('Mostrar _MENU_ filas', 'ugel-theme')); ?>',
          loadingRecords: '<?php echo esc_js(__('Cargando…', 'ugel-theme')); ?>',
          processing: '<?php echo esc_js(__('Procesando…', 'ugel-theme')); ?>',
          search: '<?php echo esc_js(__('Buscar:', 'ugel-theme')); ?>',
          zeroRecords: '<?php echo esc_js(__('No se encontraron coincidencias', 'ugel-theme')); ?>',
          paginate: {
            first: '<?php echo esc_js(__('Primero', 'ugel-theme')); ?>',
            last: '<?php echo esc_js(__('Último', 'ugel-theme')); ?>',
            next: '<?php echo esc_js(__('Siguiente', 'ugel-theme')); ?>',
            previous: '<?php echo esc_js(__('Anterior', 'ugel-theme')); ?>'
          }
        }
      });

      var highlightId = parseInt($('#convocatoriasTable').data('highlight'), 10) || 0;
      if (highlightId) {
        var $row = $table.find('tbody tr[data-convocatoria="' + highlightId + '"]');
        if ($row.length) {
          var rowIndex = dataTable.row($row).index();
          if (typeof rowIndex === 'number') {
            var info = dataTable.page.info();
            var rowsPerPage = info ? info.length : 10;
            if (!rowsPerPage || rowsPerPage === -1) {
              rowsPerPage = dataTable.rows().count();
            }
            var targetPage = rowsPerPage ? Math.floor(rowIndex / rowsPerPage) : 0;
            dataTable.page(targetPage).draw(false);
            setTimeout(function(){
              var $targetRow = $table.find('tbody tr[data-convocatoria="' + highlightId + '"]');
              $targetRow.addClass('is-highlighted');
              $targetRow[0].scrollIntoView({behavior: 'smooth', block: 'center'});
            }, 120);
          }
        }
      }
    });
  </script>
<?php endif; ?>

<?php
get_footer();
