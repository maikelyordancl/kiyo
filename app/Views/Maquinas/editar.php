<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Máquina | Kiyozumi CF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill-emoji@0.1.7/dist/quill-emoji.css">

  <style>
    <?php require_once '../public/css/maquinas.css'; ?>
    .portada-img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 0.7rem;
      border: 3px solid #c30010;
      display: block;
      margin-bottom: 8px;
    }
  </style>
</head>

<body>
<div class="container-edit">
  <h2 class="mb-3" style="color:#c30010;"><i class="bi bi-cpu"></i> Editar Máquina</h2>

  <form method="POST" action="/maquinas/actualizar/<?= $maquina['id'] ?>" enctype="multipart/form-data" onsubmit="return guardarMaquina(event)">

    <!-- Estado -->
    <div class="mb-3">
      <label class="form-label me-3">Estado de la máquina:</label>
      <input type="hidden" name="estado" value="inactiva">
      <div class="form-check form-switch d-inline-block align-middle">
        <input class="form-check-input" type="checkbox" id="estadoMaquina" name="estado" value="activa" <?= $maquina['estado'] === 'activa' ? 'checked' : '' ?>>
        <label class="form-check-label" id="estadoLabel"></label>
      </div>
    </div>

    <!-- Nombre -->
    <div class="mb-3">
      <label class="form-label" for="nombreMaquina">Nombre de la máquina</label>
      <input type="text" class="form-control" id="nombreMaquina" name="nombre" value="<?= htmlspecialchars($maquina['nombre']) ?>" required>
    </div>

    <!-- Imagen de Portada -->
    <div class="mb-3">
      <label class="form-label">Imagen de portada</label>
      <div>
        <?php if (!empty($maquina['imagen_portada'])): ?>
          <img src="<?= htmlspecialchars($maquina['imagen_portada']) ?>" class="portada-img" id="previewPortada">
        <?php else: ?>
          <div class="text-muted" id="previewPortada">No hay imagen de portada</div>
        <?php endif; ?>
      </div>
      <input type="file" class="form-control mt-2" name="imagen_portada" accept="image/*" onchange="mostrarPreviewPortada(this)">
    </div>

    <!-- Descripción -->
    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <div id="descMaquina" class="quill-editor" style="height:200px;"></div>
      <input type="hidden" name="descripcion" id="inputDescripcion">
    </div>

    <!-- Álbum de imágenes -->
    <div class="mb-3">
      <label class="form-label">Álbum de imágenes</label>
      <div class="mb-2 d-flex flex-wrap align-items-center" id="imagenesAlbum">
        <?php foreach($imagenes as $img): ?>
          <div class="position-relative me-2 mb-2">
            <img src="<?= htmlspecialchars($img['url_imagen']) ?>" class="thumb-img">
            <button type="button" class="icon-btn position-absolute top-0 end-0" onclick="eliminarImagen(this)">
              <i class="bi bi-x-circle-fill"></i>
            </button>
          </div>
        <?php endforeach; ?>
      </div>
      <input type="file" class="form-control mt-2" id="nuevaImagen" name="nuevas_imagenes[]" multiple accept="image/*">
      <input type="hidden" name="imagenes" id="inputImagenes">
    </div>

    <!-- Videos -->
    <div class="mb-3">
      <label class="form-label">Videos (YouTube)</label>
      <div id="videosLista">
        <?php foreach($videos as $vid): ?>
          <div class="input-group mb-2">
            <span class="input-group-text"><i class="bi bi-youtube"></i></span>
            <input type="url" class="form-control video-input" value="<?= htmlspecialchars($vid['url_video']) ?>" oninput="mostrarPreviewVideos()">
            <button type="button" class="icon-btn" onclick="eliminarVideo(this)"><i class="bi bi-x-circle-fill"></i></button>
          </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn btn-sm btn-outline-light mt-1" onclick="agregarVideo()">
        <i class="bi bi-plus-circle"></i> Agregar video
      </button>

      <div id="carouselVideos" class="carousel slide mt-3" data-bs-ride="carousel">
        <div class="carousel-inner" id="videosPreview"></div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselVideos" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselVideos" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>

      <input type="hidden" name="videos" id="inputVideos">
    </div>

    <!-- Botones -->
    <div class="mt-4 text-end">
      <a href="#" onclick="history.back()" class="btn btn-secondary me-2">
        <i class="bi bi-arrow-left"></i> Volver 
      </a>
      <button type="submit" class="btn btn-red px-4">
        <i class="bi bi-save"></i> Guardar Cambios
      </button>
    </div>

  </form>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-emoji@0.1.7/dist/quill-emoji.min.js"></script>


<script>
  const quill = new Quill('#descMaquina', {
  theme: 'snow',
  modules: {
    toolbar: [
      [{ 'header': [1, 2, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['link', 'image'],
      ['emoji'], // ✅ Agregar botón de emoji
      [{ 'color': [] }, { 'background': [] }],
      ['clean']
    ],
    'emoji-toolbar': true,
    'emoji-textarea': false,
    'emoji-shortname': true
  }
});

  quill.root.innerHTML = <?= json_encode($maquina['descripcion']) ?>;

  const estadoSwitch = document.getElementById("estadoMaquina");
  const estadoLabel = document.getElementById("estadoLabel");
  function updateEstadoLabel() {
    estadoLabel.innerText = estadoSwitch.checked ? "Activa" : "Inactiva";
    estadoLabel.style.color = estadoSwitch.checked ? "#27c600" : "#c30010";
  }
  estadoSwitch.addEventListener("change", updateEstadoLabel);
  updateEstadoLabel();

  function mostrarPreviewPortada(input) {
    const file = input.files[0];
    const preview = document.getElementById('previewPortada');
    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.className = 'portada-img';
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }

  }

  function eliminarImagen(btn) {
    btn.parentElement.remove();
  }

  function agregarVideo() {
    const lista = document.getElementById('videosLista');
    const div = document.createElement('div');
    div.className = "input-group mb-2";
    div.innerHTML = `
      <span class="input-group-text"><i class="bi bi-youtube"></i></span>
      <input type="url" class="form-control video-input" placeholder="Enlace de YouTube" oninput="mostrarPreviewVideos()">
      <button type="button" class="icon-btn" onclick="eliminarVideo(this)"><i class="bi bi-x-circle-fill"></i></button>
    `;
    lista.appendChild(div);
    mostrarPreviewVideos();
  }

  function eliminarVideo(btn) {
    btn.parentElement.remove();
    mostrarPreviewVideos();
  }

  function mostrarPreviewVideos() {
    const contenedor = document.getElementById('videosPreview');
    contenedor.innerHTML = "";
    const videos = Array.from(document.querySelectorAll('.video-input'))
      .map(input => input.value)
      .filter(url => url.trim() !== "");

    videos.forEach((url, index) => {
      let videoId = "";
      if (url.includes('youtube.com/watch?v=')) {
        videoId = url.split('v=')[1]?.split('&')[0] || "";
      } else if (url.includes('youtu.be/')) {
        videoId = url.split('youtu.be/')[1]?.split('?')[0] || "";
      }

      if (videoId) {
        const activeClass = index === 0 ? 'active' : '';
        const item = document.createElement('div');
        item.className = `carousel-item ${activeClass}`;
        item.innerHTML = `
          <div class="text-center">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>
            <p class="mt-2 text-white small">${url}</p>
          </div>
        `;
        contenedor.appendChild(item);
      }
    });
  }
  window.onload = mostrarPreviewVideos;

  function guardarMaquina(event) {
    document.getElementById('inputDescripcion').value = quill.root.innerHTML;
    const imgs = Array.from(document.querySelectorAll('#imagenesAlbum img'))
      .map(img => img.src);
    document.getElementById('inputImagenes').value = JSON.stringify(imgs);
    const vids = Array.from(document.querySelectorAll('.video-input')).map(input => input.value);
    document.getElementById('inputVideos').value = JSON.stringify(vids);
    return true;
  }
</script>

</body>
</html>
