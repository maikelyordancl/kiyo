<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Slider | Kiyozumi CF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    <?php require_once '../public/css/maquinas.css'; ?>
    .image-preview {
      width: 150px;
      height: 84px; /* Aspecto 16:9 */
      object-fit: cover;
      border-radius: 0.5rem;
      border: 3px solid #c30010;
      display: block;
      margin-bottom: 8px;
    }
    .image-preview.wide {
        width: 100%;
        max-width: 300px; /* Para imágenes de fondo más grandes */
        height: auto;
        max-height: 150px;
    }
    .image-container {
        position: relative;
        display: inline-block;
        margin-right: 15px;
    }
    .remove-img-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #c30010;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
    }
    .remove-img-btn:hover {
        background: #a5000e;
    }
  </style>
</head>

<body>
<div class="container-edit">
  <h2 class="mb-3" style="color:#c30010;"><i class="bi bi-images"></i> Editar Slider</h2>

  <form method="POST" action="/website/updateSlider/<?= htmlspecialchars($slider['id']) ?>" enctype="multipart/form-data">

   <div class="mb-3">
      <label class="form-label text-white" for="heading_top">Encabezado Superior</label>
      <input type="text" class="form-control" id="heading_top" name="heading_top"
             value="<?= htmlspecialchars(strip_tags($slider['heading_top'])) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="heading_bottom">Encabezado Inferior</label>
      <input type="text" class="form-control" id="heading_bottom" name="heading_bottom"
             value="<?= htmlspecialchars(strip_tags($slider['heading_bottom'])) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="paragraph_1">Párrafo 1 (Mensaje Pequeño)</label>
      <input type="text" class="form-control" id="paragraph_1" name="paragraph_1"
             value="<?= htmlspecialchars($slider['paragraph_1']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="paragraph_2">Párrafo 2 (Mensaje Grande)</label>
      <input type="text" class="form-control" id="paragraph_2" name="paragraph_2"
             value="<?= htmlspecialchars($slider['paragraph_2']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label text-white">Imagen de Fondo</label>
        <div class="image-container" id="bgImageContainer">
            <?php if (!empty($slider['background_image'])): ?>
                <?php
                    // Comprueba si la ruta ya es una URL completa o si es una ruta relativa
                    $bg_image_url = (strpos($slider['background_image'], 'http') === 0)
                        ? $slider['background_image']
                        : rtrim($baseUrl, '/') . htmlspecialchars($slider['background_image']);
                ?>
                <img src="<?= $bg_image_url ?>" class="image-preview wide" id="preview_background_image">
                <button type="button" class="remove-img-btn" onclick="removeImage('background_image')"><i class="bi bi-x"></i></button>
                <input type="hidden" name="remove_background_image" id="remove_background_image_flag" value="0">
            <?php else: ?>
                <div class="text-muted" id="preview_background_image_placeholder">No hay imagen de fondo</div>
            <?php endif; ?>
        </div>
        <input type="file" class="form-control mt-2" name="background_image" accept="image/*" onchange="previewImage(this, 'preview_background_image', 'wide')">
    </div>

    <div class="mb-3">
        <label class="form-label text-white">Imagen Frontal (Persona/Objeto)</label>
        <div class="image-container" id="fgImageContainer">
            <?php if (!empty($slider['foreground_image'])): ?>
                 <?php
                    // Comprueba si la ruta ya es una URL completa o si es una ruta relativa
                    $fg_image_url = (strpos($slider['foreground_image'], 'http') === 0)
                        ? $slider['foreground_image']
                        : rtrim($baseUrl, '/') . htmlspecialchars($slider['foreground_image']);
                ?>
                <img src="<?= $fg_image_url ?>" class="image-preview" id="preview_foreground_image">
                <button type="button" class="remove-img-btn" onclick="removeImage('foreground_image')"><i class="bi bi-x"></i></button>
                <input type="hidden" name="remove_foreground_image" id="remove_foreground_image_flag" value="0">
            <?php else: ?>
                <div class="text-muted" id="preview_foreground_image_placeholder">No hay imagen frontal</div>
            <?php endif; ?>
        </div>
        <input type="file" class="form-control mt-2" name="foreground_image" accept="image/*" onchange="previewImage(this, 'preview_foreground_image')">
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="order_num">Orden</label>
      <input type="number" class="form-control" id="order_num" name="order_num"
             value="<?= htmlspecialchars($slider['order_num']) ?>">
      <div class="form-text text-white-50">Define el orden en que aparecerán los sliders.</div>
    </div>

    <div class="mb-3">
      <label class="form-label me-3 text-white">Estado del Slider:</label>
      <div class="form-check form-switch d-inline-block align-middle">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $slider['is_active'] ? 'checked' : '' ?>>
        <label class="form-check-label" id="sliderStatusLabel"></label>
      </div>
    </div>

    <div class="mt-4 text-end">
      <a href="/website/sliders" class="btn btn-secondary me-2">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
      <button type="submit" class="btn btn-red px-4">
        <i class="bi bi-save"></i> Guardar Cambios
      </button>
    </div>

  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Función para actualizar el label de estado
  const sliderStatusSwitch = document.getElementById("is_active");
  const sliderStatusLabel = document.getElementById("sliderStatusLabel");
  function updateSliderStatusLabel() {
    sliderStatusLabel.innerText = sliderStatusSwitch.checked ? "Activo" : "Inactivo";
    sliderStatusLabel.style.color = sliderStatusSwitch.checked ? "#27c600" : "#c30010";
  }
  sliderStatusSwitch.addEventListener("change", updateSliderStatusLabel);
  updateSliderStatusLabel(); // Llama al cargar la página para establecer el estado inicial

  // Función para previsualizar imágenes
  function previewImage(input, previewId, extraClass = '') {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(previewId + '_placeholder');
    const container = input.previousElementSibling; // The .image-container div
    let removeButton = container.querySelector('.remove-img-btn');
    let removeFlagInput = document.getElementById(previewId + '_flag');

    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        if (preview) {
          preview.src = e.target.result;
          preview.className = `image-preview ${extraClass}`; // Reset class
          preview.style.display = 'block';
        } else { // Create img element if it doesn't exist (e.g., from placeholder)
            const newImg = document.createElement('img');
            newImg.src = e.target.result;
            newImg.id = previewId;
            newImg.className = `image-preview ${extraClass}`;
            container.prepend(newImg);
            // Hide placeholder if present
            if (placeholder) placeholder.style.display = 'none';
        }
        // Add remove button if it doesn't exist
        if (!removeButton) {
            removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'remove-img-btn';
            removeButton.innerHTML = '<i class="bi bi-x"></i>';
            removeButton.onclick = () => removeImage(previewId.replace('preview_', ''));
            container.appendChild(removeButton);
        }
        // Reset remove flag
        if (removeFlagInput) removeFlagInput.value = '0';
      };
      reader.readAsDataURL(file);
    } else if (!preview || preview.src === '') { // If no file and no current image
        if (preview) preview.style.display = 'none';
        if (placeholder) placeholder.style.display = 'block';
        if (removeButton) removeButton.remove();
        if (removeFlagInput) removeFlagInput.value = '0'; // Ensure it's off if no image
    }
  }

  // Función para eliminar imagen (visual y marcar para el backend)
  function removeImage(imageType) { // imageType will be 'background_image' or 'foreground_image'
    const previewId = `preview_${imageType}`;
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(previewId + '_placeholder');
    const container = document.getElementById(`${imageType.replace('_image', 'Image')}Container`); // Get the correct container
    const removeButton = container.querySelector('.remove-img-btn');
    const removeFlagInput = document.getElementById(`${imageType}_flag`);
    const fileInput = container.nextElementSibling; // The actual file input

    if (preview) preview.remove(); // Remove the image element
    if (placeholder) placeholder.style.display = 'block'; // Show placeholder
    if (removeButton) removeButton.remove(); // Remove the button
    if (removeFlagInput) removeFlagInput.value = '1'; // Set flag for backend
    if (fileInput) fileInput.value = ''; // Clear the file input
  }

  // Inicializar previews al cargar la página
  window.onload = function() {
    updateSliderStatusLabel();
  };
</script>

</body>
</html>