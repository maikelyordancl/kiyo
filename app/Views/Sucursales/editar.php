<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sucursal | Kiyozumi CF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        <?php require_once '../public/css/maquinas.css'; ?>
    </style>
</head>
<body>
<div class="container-edit">
    <h2 class="mb-3" style="color:#c30010;"><i class="bi bi-pencil-fill"></i> Editar Sucursal</h2>

    <form method="POST" action="/sucursales/actualizar/<?= $sucursal['id'] ?>">
        <div class="mb-3">
            <label class="form-label text-white" for="nombre">Nombre de la Sucursal</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($sucursal['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-white" for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($sucursal['direccion'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label text-white" for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($sucursal['telefono'] ?? '') ?>">
        </div>

        <div class="mt-4 text-end">
            <a href="/sucursales" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver al listado
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