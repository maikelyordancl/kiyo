<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Sliders Web | Kiyozumi CF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    <?php require_once '../public/css/dashboard.css'; ?>
    /* Estilos específicos para las imágenes de sliders */
    .slider-img-preview {
      width: 80px;
      height: 45px; /* Aspecto 16:9 */
      object-fit: cover;
      border-radius: 0.25rem;
      border: 1px solid #c30010;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-dark d-lg-none" style="background: #1a1a1a;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="brand ms-2">Kiyozumi CF</span>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row flex-nowrap">

      <nav class="col-lg-2 sidebar d-none d-lg-flex flex-column p-3">
        <img src="https://kiyozumi.cl/logo.png" class="img-fluid mb-4">
        <ul class="nav nav-pills flex-column mb-auto w-100">
          <li class="nav-item">
            <a href="/panel" class="nav-link mb-2"><i class="bi bi-speedometer2"></i> Dashboard</a>
          </li>
          <li><a href="/maquinas" class="nav-link mb-2"><i class="bi bi-cpu"></i> Máquinas</a></li>
          <li><a href="/clases" class="nav-link mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
          <li class="nav-item">
            <a href="/website" class="nav-link mb-2"><i class="bi bi-globe"></i> Administración Web</a>
          </li>
          <li class="nav-item">
            <a href="/website/sliders" class="nav-link active mb-2 ps-4"><i class="bi bi-image"></i> Sliders</a>
          </li>
          <li class="nav-item">
            <a href="/website/settings" class="nav-link mb-2 ps-4"><i class="bi bi-gear"></i> Configuración</a>
          </li>
          <li><a href="/auth/logout" class="nav-link mb-2"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
      </nav>

      <div class="offcanvas offcanvas-start d-lg-none" id="offcanvasSidebar" style="background: #1a1a1a;">
          <div class="offcanvas-header">
              <h5 class="offcanvas-title brand">Kiyozumi CF</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
          </div>
          <div class="offcanvas-body p-0">
              <ul class="nav nav-pills flex-column mb-auto w-100">
                  <li><a href="/panel" class="nav-link mb-2"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                  <li><a href="/maquinas" class="nav-link mb-2"><i class="bi bi-cpu"></i> Máquinas</a></li>
                  <li><a href="/clases" class="nav-link mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
                  <li><a href="/website" class="nav-link active mb-2"><i class="bi bi-globe"></i> Administración Web</a></li>
                  <li><a href="/auth/logout" class="nav-link mb-2"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
              </ul>
          </div>
      </div>

      <main class="col-lg px-2 px-md-4 py-4">
        <h2 class="mb-4"><i class="bi bi-images"></i> Sliders Principales</h2>

        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">Agregar Nuevo Slider</h5>
            <form method="POST" action="/website/createSlider" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label text-white" for="heading_top">Encabezado Superior</label>
                <input type="text" id="heading_top" name="heading_top" class="form-control" placeholder="Ej: Donde el entrenamiento" required>
              </div>
              <div class="mb-3">
                <label class="form-label text-white" for="heading_bottom">Encabezado Inferior</label>
                <input type="text" id="heading_bottom" name="heading_bottom" class="form-control" placeholder="Ej: se convierte en Pasión" required>
              </div>
              <div class="mb-3">
                <label class="form-label text-white" for="paragraph_1">Párrafo 1 (Mensaje Pequeño)</label>
                <input type="text" id="paragraph_1" name="paragraph_1" class="form-control" placeholder="Ej: Tu mejor VERSIÓN">
              </div>
              <div class="mb-3">
                <label class="form-label text-white" for="paragraph_2">Párrafo 2 (Mensaje Grande)</label>
                <input type="text" id="paragraph_2" name="paragraph_2" class="form-control" placeholder="Ej: TE ESPERA únete a nuestra comunidad">
              </div>
              <div class="mb-3">
                <label class="form-label text-white" for="background_image">Imagen de Fondo</label>
                <input type="file" id="background_image" name="background_image" class="form-control" accept="image/*">
                <div class="form-text text-white-50">Se recomienda una imagen de alta resolución que ocupe todo el ancho.</div>
              </div>
              <div class="mb-3">
                <label class="form-label text-white" for="foreground_image">Imagen Frontal (Persona/Objeto)</label>
                <input type="file" id="foreground_image" name="foreground_image" class="form-control" accept="image/*">
                <div class="form-text text-white-50">Imagen de una persona o elemento principal del slider.</div>
              </div>
              <div class="mb-3">
                <label class="form-label text-white" for="order_num">Orden</label>
                <input type="number" id="order_num" name="order_num" class="form-control" value="0">
                <div class="form-text text-white-50">Define el orden en que aparecerán los sliders.</div>
              </div>
              <div class="mb-3 form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                <label class="form-check-label text-white" for="is_active">Activo</label>
              </div>
              <button type="submit" class="btn btn-red w-100">
                <i class="bi bi-plus-circle"></i> Agregar Slider
              </button>
            </form>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">Sliders Registrados</h5>
            <div class="table-responsive">
              <table class="table table-dark table-striped align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Encabezado</th>
                    <th>Fondo</th>
                    <th>Frente</th>
                    <th>Orden</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($sliders)): ?>
                    <tr>
                      <td colspan="7" class="text-center">No hay sliders registrados.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($sliders as $index => $slider): ?>
                      <tr>
                        <td><?= htmlspecialchars($index + 1) ?></td>
                        <td>
                            <strong><?= htmlspecialchars(strip_tags($slider['heading_top'])) ?></strong><br>
                            <?= htmlspecialchars(strip_tags($slider['heading_bottom'])) ?>
                        </td>
                        <td>
                            <?php if (!empty($slider['background_image'])): ?>
                                <img src="<?= htmlspecialchars($slider['background_image']) ?>" class="slider-img-preview" alt="Fondo">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($slider['foreground_image'])): ?>
                                <img src="<?= htmlspecialchars($slider['foreground_image']) ?>" class="slider-img-preview" alt="Frente">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($slider['order_num']) ?></td>
                        <td>
                          <?php if ($slider['is_active']): ?>
                            <span class="badge bg-success">Activo</span>
                          <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                          <?php endif; ?>
                        </td>
                        <td width="18%">
                          <center>
                            <a href="/website/editSlider/<?= htmlspecialchars($slider['id']) ?>" class="btn btn-sm btn-red me-1">Editar</a>
                            <form action="/website/deleteSlider/<?= htmlspecialchars($slider['id']) ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este slider?');">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                          </center>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>