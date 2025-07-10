<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Máquinas | Kiyozumi CF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        <?php require_once '../public/css/dashboard.css'; ?>
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.5rem;
            max-height: 200px;
            overflow-y: auto;
            background-color: #2c2e33;
            padding: 1rem;
            border-radius: 0.5rem;
        }
    </style>
     <style>
    body {
      background: #191b1f;
      color: #fff;
      font-family: 'Segoe UI', Arial, sans-serif;
    }
    .sidebar {
      background: #1a1a1a;
      min-height: 100vh;
      border-right: 1px solid #2c2c2c;
    }
    .sidebar .nav-link {
      color: #fff;
      font-weight: 500;
      transition: background 0.2s, color 0.2s;
    }
    .sidebar .nav-link.active, .sidebar .nav-link:hover {
      background: #c30010;
      color: #fff;
    }
    .brand {
      font-size: 1.5rem;
      font-weight: bold;
      color: #c30010;
      margin-bottom: 1rem;
      letter-spacing: 2px;
    }
    .card {
      background: #22242a;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 2px 10px rgba(20,20,20,0.2);
    }
    .card-title, .table th {
      color: #c30010;
      letter-spacing: 1px;
      font-weight: bold;
    }
    .btn-red {
      background: #c30010;
      color: #fff;
      border: none;
    }
    .btn-red:hover, .btn-red:focus {
      background: #a5000e;
      color: #fff;
    }
    .table-dark {
      --bs-table-bg: #212227;
      --bs-table-striped-bg: #24252a;
      --bs-table-hover-bg: #292b30;
    }
    .badge.bg-success {
      background-color: #27c600 !important;
    }
    .badge.bg-danger {
      background-color: #c30010 !important;
    }
    .modal-content {
      background: #191b1f;
      color: #fff;
      border-radius: 1rem;
      border: 1px solid #c30010;
    }
    .modal-header {
      border-bottom: 1px solid #c30010;
    }
  </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <nav class="col-lg-2 sidebar d-none d-lg-flex flex-column p-3">
            <img src="https://kiyozumi.cl/logo.png" class="img-fluid mb-4">
            <ul class="nav nav-pills flex-column mb-auto w-100">
                <li class="nav-item"><a href="/panel" class="nav-link mb-2"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="/sucursales" class="nav-link mb-2"><i class="bi bi-shop"></i> Sucursales</a></li>
                <li><a href="/maquinas" class="nav-link active mb-2"><i class="bi bi-cpu"></i> Máquinas</a></li>
                <li><a href="/clases" class="nav-link mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
                <li class="nav-item"><a href="/website" class="nav-link mb-2"><i class="bi bi-globe"></i> Administración Web</a></li>
                <li><a href="/auth/logout" class="nav-link mb-2"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
            </ul>
        </nav>

        <main class="col-lg px-2 px-md-4 py-4">
            
            <?php require_once __DIR__ . '/../Partials/header.php'; ?>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3"><i class="bi bi-plus-circle"></i> Crear y Asignar Nueva Máquina</h5>
                            <p class="text-white-50 small">Crea una máquina desde cero y se asignará a <strong><?= htmlspecialchars($_SESSION['sucursal_seleccionada']['nombre']) ?></strong>.</p>
                            <form method="POST" action="/maquinas/crear" class="mt-auto">
                                <div class="input-group">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombre de la nueva máquina" required>
                                    <button type="submit" class="btn btn-red">Crear y Asignar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3"><i class="bi bi-link-45deg"></i> Asignar Máquinas Existentes</h5>
                            <p class="text-white-50 small">Selecciona una o varias máquinas (con CTRL o CMD) para añadirlas a esta sucursal.</p>
                            <?php if (empty($maquinas_no_asignadas)): ?>
                                <p class="text-success fst-italic mt-auto">¡Todas las máquinas del sistema ya están en esta sucursal!</p>
                            <?php else: ?>
                                <form method="POST" action="/maquinas/asignarExistente" class="mt-auto">
                                    <select name="id_maquinas[]" class="form-select select-multiple mb-3" multiple required>
                                        <?php foreach ($maquinas_no_asignadas as $maq_existente): ?>
                                            <option value="<?= $maq_existente['id'] ?>"><?= htmlspecialchars($maq_existente['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-outline-light w-100">Asignar Seleccionadas</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Máquinas en esta sucursal (<?= htmlspecialchars($_SESSION['sucursal_seleccionada']['nombre']) ?>)</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($maquinas)): ?>
                                <tr>
                                    <td colspan="3" class="text-center fst-italic">No hay máquinas asignadas a esta sucursal.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($maquinas as $maq): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($maq['nombre']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $maq['estado'] === 'activa' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($maq['estado']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="/maquinas/editar/<?= $maq['id'] ?>" class="btn btn-sm btn-red" title="Editar detalles de la máquina"><i class="bi bi-pencil-fill"></i> Editar</a>
                                            <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#qrModal" onclick="showQR('<?= htmlspecialchars($maq['nombre']) ?>', '<?= $maq['id'] ?>')" title="Ver QR"><i class="bi bi-qr-code"></i></button>
                                            <a href="/maquinas/desasignar/<?= $maq['id'] ?>" class="btn btn-sm btn-outline-warning" onclick="return confirm('¿Quitar esta máquina de la sucursal actual? No se eliminará del sistema.')" title="Quitar de esta sucursal"><i class="bi bi-x-circle"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-danger">
                 <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-globe"></i> Administración Global de Máquinas</h5>
                </div>
                <div class="card-body">
                    <p class="text-white-50 small">Esta tabla muestra **todas** las máquinas del sistema. Desde aquí puedes eliminarlas permanentemente.</p>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nombre de Máquina (Global)</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($maquinas_todas as $maq_global): ?>
                                <tr>
                                    <td><?= htmlspecialchars($maq_global['nombre']) ?></td>
                                    <td class="text-center">
                                        <a href="/maquinas/eliminar/<?= $maq_global['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¡ATENCIÓN! Esto eliminará la máquina de TODAS las sucursales y del sistema de forma PERMANENTE. ¿Estás seguro?')">
                                            <i class="bi bi-trash-fill"></i> Eliminar del Sistema
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function showQR(nombre, id) {
    const url = `https://qr.kiyozumi.cl/maquinas/ver/${id}`;
    const qrImg = document.getElementById('qrImage');
    const label = document.getElementById('nombreQR');
    const urlText = document.getElementById('qrUrl');

    qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(url)}`;
    label.innerHTML = `<i class="bi bi-qr-code"></i> ${nombre}`;
    urlText.innerHTML = `<a href="${url}" target="_blank" class="text-info"><i class="bi bi-box-arrow-up-right"></i> Abrir enlace QR</a>`;
  }

  function imprimirQR() {
    const qrImg = document.getElementById('qrImage').src;
    const nombre = document.getElementById('nombreQR').innerText;
    const ventana = window.open('', '_blank');
    ventana.document.write(`
      <html>
        <head>
          <title>Imprimir QR</title>
          <style>
            body { text-align: center; font-family: Arial; }
            img { width: 220px; height: 220px; margin: 20px 0; }
            h2 { color: #333; }
            p { color: #555; }
          </style>
        </head>
        <body>
          <h2>${nombre}</h2>
          <img src="${qrImg}" alt="QR">
          <script>
            window.onload = function() {
              window.print();
              window.onafterprint = function() { window.close(); };
            }
          <\/script>
        </body>
      </html>
    `);
    ventana.document.close();
  }
</script>
</body>
</html>