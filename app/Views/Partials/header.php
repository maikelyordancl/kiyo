<?php
// Empezamos la sesión si no está iniciada para acceder a las variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtenemos todas las sucursales para el desplegable
$sucursalModel = new \Mindshot\Kiyozumicf\Models\Sucursal();
$listaSucursales = $sucursalModel->obtenerTodas();

$sucursalSeleccionada = $_SESSION['sucursal_seleccionada'] ?? null;
?>
<header class="d-flex justify-content-between align-items-center p-3 mb-4" style="background-color: #1a1a1a; border-bottom: 2px solid #c30010;">
    <div>
        <h2 class="mb-0"><i class="bi bi-speedometer2"></i> Panel de Administración</h2>
    </div>
    <div class="d-flex align-items-center">
        <div class="dropdown me-3">
            <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownSucursal" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-shop"></i>
                <?php if ($sucursalSeleccionada): ?>
                    Sucursal: <strong><?= htmlspecialchars($sucursalSeleccionada['nombre']) ?></strong>
                <?php else: ?>
                    Seleccionar Sucursal
                <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownSucursal">
                <?php if (empty($listaSucursales)): ?>
                     <li><a class="dropdown-item" href="/sucursales">Añadir sucursal</a></li>
                <?php else: ?>
                    <?php foreach ($listaSucursales as $sucursal): ?>
                        <li>
                            <a class="dropdown-item <?= ($sucursalSeleccionada && $sucursalSeleccionada['id'] == $sucursal['id']) ? 'active' : '' ?>"
                               href="/sucursales/seleccionar/<?= $sucursal['id'] ?>">
                                <?= htmlspecialchars($sucursal['nombre']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                 <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/sucursales"><i class="bi bi-pencil-fill"></i> Gestionar Sucursales</a></li>
            </ul>
        </div>

        <div class="text-white">
            <i class="bi bi-person-circle"></i>
            <span>Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></strong></span>
        </div>
    </div>
</header>