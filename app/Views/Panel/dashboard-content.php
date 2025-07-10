<!-- Cards resumen -->
<div class="row g-4 mb-4">
  <div class="col-12 col-md-4">
    <div class="card text-center py-4">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-cpu"></i> Máquinas</h5>
        <p class="display-5 mb-0"><?= count($maquinas) ?></p>
        <a href="#" class="btn btn-red mt-3">Agregar Máquinas</a>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card text-center py-4">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-calendar-event"></i> Clases</h5>
        <p class="display-5 mb-0">6</p>
        <a href="#" class="btn btn-red mt-3">Ver Clases</a>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card text-center py-4">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-qr-code"></i> QRs Generados</h5>
        <p class="display-5 mb-0"><?= count($maquinas) ?></p>
        <a href="#" onclick="imprimirTodosQRs()" class="btn btn-red mt-3">Imprimir todos los QR</a>
      </div>
    </div>
  </div>
</div>

<!-- Tabla de máquinas -->
<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Máquinas registradas</h5>
    <div class="table-responsive">
      <table class="table table-dark table-striped align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($maquinas as $index => $maq): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($maq['nombre']) ?></td>
              <td>
                <?php if ($maq['estado'] === 'activa'): ?>
                  <span class="badge bg-success">Activa</span>
                <?php else: ?>
                  <span class="badge bg-danger">Inactiva</span>
                <?php endif; ?>
              </td>
              <td width="12%">
                <a href="/maquinas/editar/<?= $maq['id'] ?>" class="btn btn-sm btn-red me-1">Editar</a>
                <button 
                class="btn btn-sm btn-outline-light" 
                data-bs-toggle="modal" 
                data-bs-target="#qrModal"
                onclick="showQR('<?= htmlspecialchars($maq['nombre']) ?>', '<?= $maq['id'] ?>')">
                Ver QR
              </button>


            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</div>

<div class="modal fade" id="qrModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="nombreQR"><i class="bi bi-qr-code"></i> QR de la Máquina</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="qrImage" src="" alt="QR Code" style="width: 200px; height: 200px;">
        <p id="qrUrl" class="mt-3 text-muted small"></p>
        <button class="btn btn-outline-light mt-3" onclick="imprimirQR()">
          <i class="bi bi-printer"></i> Imprimir QR
        </button>

      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Asegúrate de que maquinasData esté definida aquí
  const maquinasData = <?= json_encode($maquinas) ?>;

  function showQR(nombre, id) {
    const url = `http://qr.kiyozumi.cl/maquinas/ver/${id}`;
    const qrImg = document.getElementById('qrImage');
    const label = document.getElementById('nombreQR');
    const urlText = document.getElementById('qrUrl');

    if (!qrImg || !label || !urlText) {
      console.error('❌ Algún elemento no se encontró en el DOM.');
      return;
    }

    const qrApi = `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(url)}`;

    qrImg.src = qrApi;
    label.innerHTML = `<i class="bi bi-qr-code"></i> ${nombre}`;
    urlText.innerHTML = `<a href="${url}" target="_blank" class="text-info"><i class="bi bi-box-arrow-up-right"></i> Abrir enlace QR</a>`;
  }

  function imprimirQR() {
    const qrImg = document.getElementById('qrImage').src;
    const nombre = document.getElementById('nombreQR').innerText;
    const url = document.getElementById('qrUrl').innerText;

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

  function imprimirTodosQRs() {
    const ventana = window.open('', '_blank');
    ventana.document.write(`
      <html>
        <head>
          <title>Imprimir todos los QR</title>
          <style>
            body { text-align: center; font-family: Arial; margin: 20px; }
            .qr-item { margin-bottom: 15px; padding: 10px; border: 1px solid #eee; display: inline-block; text-align: center; page-break-inside: avoid; }
            img { width: 200px; height: 200px; margin-top: 10px; border: 1px solid #ccc; }
            h2 { color: #333; margin-bottom: 5px; }
            p { font-size: 0.9em; color: #666; }
            @media print {
                body { -webkit-print-color-adjust: exact; }
                .qr-item { page-break-after: always; }
            }
          </style>
        </head>
        <body>
    `);

    maquinasData.forEach(function(maquina) {
      const url = `https://qr.kiyozumi.cl/maquinas/ver/${maquina.id}`;
      const qrApi = `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(url)}`;
      ventana.document.write(`
        <div class="qr-item">
          <h6>${maquina.nombre}</h6>
          <img src="${qrApi}" alt="QR Code para ${maquina.nombre}">
        </div>
      `);
    });

    ventana.document.write(`
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


