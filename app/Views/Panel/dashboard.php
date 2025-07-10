<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | <?= htmlspecialchars($usuario['nombre']) ?> | Kiyozumi CF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        <?php require_once '../public/css/dashboard.css'; ?>
    </style>
</head>
<body>
    <!-- Navbar superior SOLO para móvil -->
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
            <!-- Sidebar -->
            <nav class="col-lg-2 sidebar d-none d-lg-flex flex-column p-3">
                <img src="https://kiyozumi.cl/logo.png"/>
                <ul class="nav nav-pills flex-column mb-auto w-100">
                    <li class="nav-item">
                        <a href="/panel" class="nav-link active mb-2"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item"><a href="/sucursales" class="nav-link mb-2"><i class="bi bi-shop"></i> Sucursales</a></li>
                    <li><a href="/maquinas" class="nav-link mb-2"><i class="bi bi-cpu"></i> Máquinas</a></li>
                    <li><a href="/clases" class="nav-link mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
                    <li class="nav-item"><a href="/website" class="nav-link mb-2"><i class="bi bi-globe"></i> Administración Web</a></li>
                    <li><a href="/auth/logout" class="nav-link mb-2"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
                </ul>
            </nav>

            <!-- Sidebar móvil -->
            <div class="offcanvas offcanvas-start d-lg-none" id="offcanvasSidebar" style="background: #1a1a1a;">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title brand">Kiyozumi CF</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <ul class="nav nav-pills flex-column mb-auto w-100">
                        <li><a href="/panel" class="nav-link active mb-2"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                        <li><a href="/maquinas" class="nav-link mb-2"><i class="bi bi-cpu"></i> Máquinas</a></li>
                        <li><a href="#" class="nav-link mb-2"><i class="bi bi-calendar-event"></i> Clases</a></li>
                        <li><a href="/auth/logout" class="nav-link mb-2"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-lg px-2 px-md-4 py-4">
                <?php require_once __DIR__ . '/../Partials/header.php'; ?>
                <!-- Aquí insertamos el HTML que me diste -->
                <!-- Copia aquí la sección completa del dashboard que me enviaste -->
                <?= require __DIR__ . '/dashboard-content.php'; ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
