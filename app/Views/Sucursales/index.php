<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sucursales | Kiyozumi CF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        <?php require_once '../public/css/dashboard.css'; ?>
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <nav class="col-lg-2 sidebar d-none d-lg-flex flex-column p-3">
                <img src="https://kiyozumi.cl/logo.png" class="img-fluid mb-4">
                <ul class="nav nav-pills flex-column mb-auto w-100">
                    <li class="nav-item"><a href="/panel" class="nav-link mb-2"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                    <li class="nav-item"><a href="/sucursales" class="nav-link active mb-2"><i class="bi bi-shop"></i> Sucursales</a></li>
                    <li><a href="/maquinas" class="nav-link mb-2"><i class="bi bi-cpu"></i> Máquinas</a></li>
                    <li><a href="/clases" class="nav-link mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
                    <li class="nav-item"><a href="/website" class="nav-link mb-2"><i class="bi bi-globe"></i> Administración Web</a></li>
                    <li><a href="/auth/logout" class="nav-link mb-2"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
                </ul>
            </nav>

            <main class="col-lg px-2 px-md-4 py-4">
                <?php require_once __DIR__ . '/../Partials/header.php'; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Agregar Nueva Sucursal</h5>
                        <form method="POST" action="/sucursales/crear">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombre de la sucursal" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="direccion" class="form-control" placeholder="Dirección (opcional)">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="telefono" class="form-control" placeholder="Teléfono (opcional)">
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-red">
                                    <i class="bi bi-plus-circle"></i> Agregar Sucursal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Sucursales Registradas</h5>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($sucursales)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No hay sucursales registradas.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($sucursales as $sucursal): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($sucursal['nombre']) ?></td>
                                                <td><?= htmlspecialchars($sucursal['direccion'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($sucursal['telefono'] ?? 'N/A') ?></td>
                                                <td>
                                                    <a href="/sucursales/editar/<?= $sucursal['id'] ?>" class="btn btn-sm btn-red me-1">
                                                        <i class="bi bi-pencil-fill"></i> Editar
                                                    </a>
                                                    <a href="/sucursales/eliminar/<?= $sucursal['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar esta sucursal? Se desasignarán todas sus máquinas.');">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </a>
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