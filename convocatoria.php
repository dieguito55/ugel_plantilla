<?php
/**
 * Plantilla de convocatoria individual con tabla y adjuntos.
 */

if (!defined('ABSPATH')) {
  exit;
}

get_header();
?>

<section class="convocatoria-detail" aria-label="Detalle de la convocatoria">
  <div class="wrap">
    <div class="convocatoria-shell">
      <?php if (have_posts()) : while (have_posts()) : the_post();
        $post_id   = get_the_ID();
        $meta      = ugel_get_convocatoria_meta($post_id);
        $estado    = ugel_get_convocatoria_status_details($meta['fecha_inicio'] ?? '', $meta['fecha_fin'] ?? '');
        $slug      = isset($estado['slug']) ? sanitize_html_class($estado['slug']) : 'en_proceso';
        $label     = $estado['label'] ?? __('En proceso', 'ugel-theme');
        $indice    = $meta['indice'] ?? '';
        $tipo      = $meta['tipo'] ?? '';
        $fi        = $meta['fecha_inicio'] ?? '';
        $ff        = $meta['fecha_fin'] ?? '';
        $fi_fmt    = ugel_format_convocatoria_date($fi);
        $ff_fmt    = ugel_format_convocatoria_date($ff);
        $rango     = '';
        if ($fi_fmt && $ff_fmt) {
          $rango = sprintf(__('Del %1$s al %2$s', 'ugel-theme'), $fi_fmt, $ff_fmt);
        } elseif ($fi_fmt) {
          $rango = sprintf(__('Desde el %s', 'ugel-theme'), $fi_fmt);
        } elseif ($ff_fmt) {
          $rango = sprintf(__('Hasta el %s', 'ugel-theme'), $ff_fmt);
        }

        $pdfs = array(
          'bases_pdf' => array(
            'label' => __('Bases', 'ugel-theme'),
            'meta'  => $meta['bases_pdf'] ?? '',
          ),
          'resultado_preliminar_pdf' => array(
            'label' => __('Resultado Preliminar Curricular', 'ugel-theme'),
            'meta'  => $meta['resultado_preliminar_pdf'] ?? '',
          ),
          'resultado_final_curr_pdf' => array(
            'label' => __('Resultado Final Curricular', 'ugel-theme'),
            'meta'  => $meta['resultado_final_curr_pdf'] ?? '',
          ),
          'resultados_finales_pdf' => array(
            'label' => __('Resultados Finales', 'ugel-theme'),
            'meta'  => $meta['resultados_finales_pdf'] ?? '',
          ),
        );
      ?>
      <header class="convocatoria-head">
        <nav class="convocatoria-breadcrumbs" aria-label="Ruta de navegación">
          <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Inicio', 'ugel-theme'); ?></a>
          <span class="separator">›</span>
          <a href="<?php echo esc_url(get_post_type_archive_link('convocatorias')); ?>"><?php esc_html_e('Convocatorias', 'ugel-theme'); ?></a>
          <span class="separator">›</span>
          <span class="current"><?php the_title(); ?></span>
        </nav>

        <div class="convocatoria-head__content">
          <?php if (!empty($tipo)) : ?>
            <span class="convocatoria-head__tag"><?php echo esc_html($tipo); ?></span>
          <?php endif; ?>
          <h1 class="convocatoria-head__title"><?php the_title(); ?></h1>
          <?php if (!empty($meta['descripcion'])) : ?>
            <div class="convocatoria-head__summary"><?php echo wp_kses_post(wpautop($meta['descripcion'])); ?></div>
          <?php elseif (has_excerpt()) : ?>
            <div class="convocatoria-head__summary"><?php echo wp_kses_post(wpautop(get_the_excerpt())); ?></div>
          <?php endif; ?>
        </div>
      </header>

      <div class="convocatoria-table__wrapper">
        <table id="convocatoria-table" class="convocatoria-table display nowrap" style="width:100%">
          <thead>
            <tr>
              <th><?php esc_html_e('Índice', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Convocatoria', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Tipo', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Fecha', 'ugel-theme'); ?></th>
              <th><?php esc_html_e('Estado', 'ugel-theme'); ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $indice ? esc_html($indice) : '—'; ?></td>
              <td><?php echo esc_html(get_the_title()); ?></td>
              <td><?php echo $tipo ? esc_html($tipo) : '—'; ?></td>
              <td>
                <?php if ($rango) : ?>
                  <span><?php echo esc_html($rango); ?></span>
                <?php else : ?>
                  <span><?php esc_html_e('No especificada', 'ugel-theme'); ?></span>
                <?php endif; ?>
              </td>
              <td>
                <span class="convocatoria-chip convocatoria-chip--<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <section class="convocatoria-pdfs" aria-label="Documentos de la convocatoria">
        <h2 class="convocatoria-pdfs__title"><?php esc_html_e('Documentos oficiales', 'ugel-theme'); ?></h2>
        <div class="convocatoria-pdfs__grid">
          <?php foreach ($pdfs as $key => $info) :
            $url        = $info['meta'];
            $label_pdf  = $info['label'];
            $has_pdf    = !empty($url);
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

      <section class="convocatoria-body" aria-label="Contenido adicional de la convocatoria">
        <?php if (get_the_content()) : ?>
          <div class="convocatoria-body__content">
            <?php the_content(); ?>
          </div>
        <?php endif; ?>
      </section>

      <?php endwhile; endif; ?>
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
    gap: clamp(32px, 5vw, 48px);
  }
  .convocatoria-head {
    display: flex;
    flex-direction: column;
    gap: 18px;
    background: #FFFFFF;
    border: 1px solid rgba(130, 151, 254, 0.18);
    border-radius: 20px;
    padding: clamp(24px, 4vw, 36px);
    box-shadow:
      0 12px 38px rgba(2, 31, 89, 0.08),
      0 6px 20px rgba(2, 31, 89, 0.05),
      inset 0 1px 0 rgba(255, 255, 255, 0.9);
  }
  .convocatoria-breadcrumbs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    color: #475569;
  }
  .convocatoria-breadcrumbs a {
    color: #021F59;
    text-decoration: none;
  }
  .convocatoria-breadcrumbs a:hover {
    color: #000C97;
    text-decoration: underline;
  }
  .convocatoria-breadcrumbs .separator {
    color: rgba(2, 31, 89, 0.4);
  }
  .convocatoria-head__tag {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 999px;
    background: rgba(0, 12, 151, 0.12);
    color: #000C97;
    font-weight: 700;
    font-size: 0.85rem;
    width: max-content;
  }
  .convocatoria-head__title {
    margin: 0;
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 900;
    color: #021F59;
    line-height: 1.2;
  }
  .convocatoria-head__summary {
    margin: 0;
    color: #475569;
    font-size: 1rem;
    max-width: 760px;
  }
  .convocatoria-head__summary p {
    margin: 0 0 0.75em 0;
  }
  .convocatoria-head__summary p:last-child {
    margin-bottom: 0;
  }
  .convocatoria-table__wrapper {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid rgba(130, 151, 254, 0.18);
    padding: 24px;
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
    gap: 6px;
    font-weight: 700;
    color: #000C97;
    text-decoration: none;
  }
  .convocatoria-pdf__link::after {
    content: '↗';
    font-size: 0.85rem;
  }
  .convocatoria-pdf__link:hover {
    text-decoration: underline;
  }
  .convocatoria-pdf__missing {
    font-size: 0.9rem;
    color: #94A3B8;
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
  @media (max-width: 768px) {
    .convocatoria-table__wrapper {
      padding: 18px;
    }
    .convocatoria-pdfs__grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<script>
  jQuery(function($) {
    var $table = $('#convocatoria-table');
    if ($table.length && $.fn.DataTable) {
      $table.DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?php echo esc_js(__('Todos', 'ugel-theme')); ?>']],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: []
      });
    }
  });
</script>

<?php get_footer(); ?>
