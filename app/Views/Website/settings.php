<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración Web | Kiyozumi CF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    <?php require_once '../public/css/maquinas.css'; ?>
    /* Puedes añadir estilos específicos para esta vista si es necesario */
  </style>
</head>

<body>
<div class="container-edit">
  <h2 class="mb-3" style="color:#c30010;"><i class="bi bi-gear-fill"></i> Configuración General del Sitio Web</h2>

  <form method="POST" action="/website/updateSettings">

    <p class="text-white-50 mb-4">Edita los valores de la configuración global de tu sitio web.</p>

    <div class="mb-3">
      <label class="form-label text-white" for="setting_site_title">Título del Sitio Web (Meta Title)</label>
      <input type="text" class="form-control" id="setting_site_title" name="setting_site_title" 
             value="<?= htmlspecialchars($settings['site_title'] ?? 'Kiyozumi CF') ?>" required>
      <div class="form-text text-white-50">Título que aparece en la pestaña del navegador y en los resultados de búsqueda.</div>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="setting_meta_description">Meta Descripción</label>
      <textarea class="form-control" id="setting_meta_description" name="setting_meta_description" rows="3"><?= htmlspecialchars($settings['meta_description'] ?? 'El Gimnasio Kiyozumi, ubicado en Coronel, región del Biobío, ofrece más de 800m² de instalaciones modernas y equipadas, brindando entrenamiento en musculación, CrossFit, karate y más.') ?></textarea>
      <div class="form-text text-white-50">Breve resumen del contenido de la página para motores de búsqueda.</div>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="setting_meta_keywords">Meta Keywords</label>
      <input type="text" class="form-control" id="setting_meta_keywords" name="setting_meta_keywords" 
             value="<?= htmlspecialchars($settings['meta_keywords'] ?? 'gimnasio, fitness, CrossFit, karate, kickboxing, entrenamiento, preparación física, musculación, deportes, salud, bienestar, acondicionamiento físico, Biobío, Coronel, artes marciales, personal trainer, entrenamiento personalizado, deporte en Coronel, gimnasio en Coronel, gimnasio en Biobío, gimnasio moderno, equipamiento deportivo') ?>">
      <div class="form-text text-white-50">Palabras clave separadas por comas.</div>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="setting_og_image">URL Imagen Open Graph (Compartir en Redes)</label>
      <input type="url" class="form-control" id="setting_og_image" name="setting_og_image" 
             value="<?= htmlspecialchars($settings['og_image'] ?? 'https://kiyozumi.cl/3home.jpg') ?>">
      <div class="form-text text-white-50">URL de la imagen que se muestra al compartir el sitio en redes sociales.</div>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="setting_whatsapp_number">Número de WhatsApp</label>
      <input type="text" class="form-control" id="setting_whatsapp_number" name="setting_whatsapp_number" 
             value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '+56966102032') ?>">
      <div class="form-text text-white-50">Número de teléfono para el botón de WhatsApp (Ej: +56912345678).</div>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="setting_opening_hours_weekdays">Horario Lunes - Viernes</label>
      <input type="text" class="form-control" id="setting_opening_hours_weekdays" name="setting_opening_hours_weekdays" 
             value="<?= htmlspecialchars($settings['opening_hours_weekdays'] ?? '08:00 a 23:00') ?>">
      <div class="form-text text-white-50">Horario de apertura para días de semana.</div>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="setting_opening_hours_weekend">Horario Sábado y Domingo</label>
      <input type="text" class="form-control" id="setting_opening_hours_weekend" name="setting_opening_hours_weekend" 
             value="<?= htmlspecialchars($settings['opening_hours_weekend'] ?? '09:00 a 16:30') ?>">
      <div class="form-text text-white-50">Horario de apertura para fines de semana.</div>
    </div>
    
    <div class="mt-4 text-end">
      <a href="/website" class="btn btn-secondary me-2">
        <i class="bi bi-arrow-left"></i> Volver 
      </a>
      <button type="submit" class="btn btn-red px-4">
        <i class="bi bi-save"></i> Guardar Cambios
      </button>
    </div>

  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>