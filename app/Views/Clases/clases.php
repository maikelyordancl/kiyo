<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clases | Kiyozumi CF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    <?php require_once '../public/css/dashboard.css'; ?>
    /* Puedes añadir estilos específicos para clases aquí si es necesario */
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
          <li><a href="/clases" class="nav-link active mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
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
                  <li><a href="/clases" class="nav-link active mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
                  <li><a href="/auth/logout" class="nav-link mb-2"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
              </ul>
          </div>
      </div>

      <main class="col-lg px-2 px-md-4 py-4">
        <?php require_once __DIR__ . '/../Partials/header.php'; ?>

        <div class="card mb-4" id="formAgregar">
          <div class="card-body">
            <h5 class="card-title mb-3">Agregar Nueva Clase</h5>
            <form method="POST" action="/clases/crear">
              <div class="mb-3">
                <input type="text" id="nombreNuevaClase" name="nombre" class="form-control" placeholder="Nombre de la clase" required>
              </div>
              <div class="mb-3">
                <textarea id="descripcionNuevaClase" name="descripcion" class="form-control" placeholder="Descripción de la clase (opcional)" rows="2"></textarea>
              </div>
              <div class="row g-2 mb-3">
                <div class="col-md-4">
                  <select id="tipoNuevaClase" name="tipo" class="form-select" required>
                    <option value="">Selecciona Tipo</option>
                    <option value="mensualidad">Mensualidad (Karate, Crossfit)</option>
                    <option value="adicional">Adicional (Boxeo, Kick Boxing)</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <input type="text" id="diasNuevaClase" name="dias" class="form-control" placeholder="Días (Ej: Lu, Mi, Vi)" required>
                </div>
                <div class="col-md-4">
                  <input type="text" id="horarioNuevaClase" name="horario" class="form-control" placeholder="Horario (Ej: 18:00 - 19:00)" required>
                </div>
              </div>
              <div class="mb-3 form-check form-switch">
                <input class="form-check-input" type="checkbox" id="estadoNuevaClase" name="estado" value="activa" checked>
                <label class="form-check-label text-white" for="estadoNuevaClase">Activa</label>
              </div>
              <button type="submit" class="btn btn-red w-100">
                <i class="bi bi-plus-circle"></i> Agregar Clase
              </button>
            </form>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">Clases Registradas</h5>
            <div class="table-responsive">
              <table class="table table-dark table-striped align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Días</th>
                    <th>Horario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($clases)): ?>
                    <tr>
                      <td colspan="7" class="text-center">No hay clases registradas.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($clases as $index => $clase): ?>
                      <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($clase['nombre']) ?></td>
                        <td><?= htmlspecialchars($clase['tipo']) ?></td>
                        <td><?= htmlspecialchars($clase['dias']) ?></td>
                        <td><?= htmlspecialchars($clase['horario']) ?></td>
                        <td>
                          <?php if ($clase['estado'] === 'activa'): ?>
                            <span class="badge bg-success">Activa</span>
                          <?php else: ?>
                            <span class="badge bg-danger">Inactiva</span>
                          <?php endif; ?>
                        </td>
                        <td width="18%">
                          <center>
                            <a href="/clases/editar/<?= $clase['id'] ?>" class="btn btn-sm btn-red me-1">Editar</a>
                            <form action="/clases/eliminar/<?= $clase['id'] ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta clase?');">
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