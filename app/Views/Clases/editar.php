<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Clase: <?= htmlspecialchars($clase['nombre']) ?> | Kiyozumi CF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    <?php require_once '../public/css/maquinas.css'; ?>
    /* Puedes añadir estilos específicos para clases aquí si es necesario */
  </style>
</head>

<body>
<div class="container-edit">
  <h2 class="mb-3" style="color:#c30010;"><i class="bi bi-calendar-event"></i> Editar Clase: <?= htmlspecialchars($clase['nombre']) ?></h2>

  <form method="POST" action="/clases/actualizar/<?= $clase['id'] ?>">

    <div class="mb-3">
      <label class="form-label me-3 text-white">Estado de la clase:</label>
      <input type="hidden" name="estado" value="inactiva">
      <div class="form-check form-switch d-inline-block align-middle">
        <input class="form-check-input" type="checkbox" id="estadoClase" name="estado" value="activa" <?= $clase['estado'] === 'activa' ? 'checked' : '' ?>>
        <label class="form-check-label" id="estadoLabelClase"></label>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="nombreClase">Nombre de la clase</label>
      <input type="text" class="form-control" id="nombreClase" name="nombre" value="<?= htmlspecialchars($clase['nombre']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label text-white" for="descripcionClase">Descripción</label>
      <textarea class="form-control" id="descripcionClase" name="descripcion" rows="4"><?= htmlspecialchars($clase['descripcion']) ?></textarea>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <label class="form-label text-white" for="tipoClase">Tipo</label>
        <select id="tipoClase" name="tipo" class="form-select" required>
          <option value="mensualidad" <?= ($clase['tipo'] === 'mensualidad') ? 'selected' : '' ?>>Mensualidad (Karate, Crossfit)</option>
          <option value="adicional" <?= ($clase['tipo'] === 'adicional') ? 'selected' : '' ?>>Adicional (Boxeo, Kick Boxing)</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label text-white" for="diasClase">Días</label>
        <input type="text" class="form-control" id="diasClase" name="dias" value="<?= htmlspecialchars($clase['dias']) ?>" placeholder="Ej: Lu, Mi, Vi" required>
      </div>
      <div class="col-md-4">
        <label class="form-label text-white" for="horarioClase">Horario</label>
        <input type="text" class="form-control" id="horarioClase" name="horario" value="<?= htmlspecialchars($clase['horario']) ?>" placeholder="Ej: 18:00 - 19:00" required>
      </div>
    </div>

    <div class="mt-4 text-end">
      <a href="/clases" class="btn btn-secondary me-2">
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
  const estadoSwitchClase = document.getElementById("estadoClase");
  const estadoLabelClase = document.getElementById("estadoLabelClase");
  function updateEstadoLabelClase() {
    estadoLabelClase.innerText = estadoSwitchClase.checked ? "Activa" : "Inactiva";
    estadoLabelClase.style.color = estadoSwitchClase.checked ? "#27c600" : "#c30010";
  }
  estadoSwitchClase.addEventListener("change", updateEstadoLabelClase);
  updateEstadoLabelClase();
</script>

</body>
</html>