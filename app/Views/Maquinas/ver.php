<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Máquina: <?= htmlspecialchars($maquina['nombre']) ?> | Kiyozumi CF</title>
  <meta property="og:title" content="<?= htmlspecialchars($maquina['nombre']) ?> | Kiyozumi CF">
  <meta property="og:description" content="Información, fotos y videos de la máquina <?= htmlspecialchars($maquina['nombre']) ?> en Kiyozumi CF.">
  <meta property="og:url" content="https://qr.kiyozumi.cl/maquinas/ver/<?= $maquina['id'] ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?= htmlspecialchars($maquina['imagen_portada']) ?>">
  <link rel="icon" type="image/png" href="https://kiyozumi.cl/logo.png">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
        <?php require_once '../public/css/ver-maquina.css'; ?>
        /* Estilos específicos para la sección de clases dentro de ver-maquina.php */
        .classes-section {
            background: #212127;
            border-radius: 1rem;
            padding: 1.5rem 1rem;
            margin-top: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.16);
        }
        .classes-section h2 {
            color: #c30010;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        .class-category-title {
            color: #fff; /* O un color que resalte, como #007bff o el rojo de la marca */
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            border-bottom: 2px solid #c30010;
            padding-bottom: 0.5rem;
        }
        .class-item {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            color: #eee;
        }
        .class-item h3 {
            margin-top: 0;
            color: #c30010; /* Color principal de tu marca */
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        .class-item p {
            margin: 0.2rem 0;
            font-size: 0.95rem;
        }
        .class-item .highlight {
            font-weight: bold;
            color: #ddd; /* Un color que contraste pero que sea suave */
        }
        .no-classes-msg {
            text-align: center;
            color: #aaa;
            font-style: italic;
            padding: 1rem;
        }
    </style>
</head>

<body>
  <div class="container" style="max-width:640px; margin:0 auto 2rem auto;">
    
    <div class="machine-header">
        <img src="https://kiyozumi.cl/logo.png" width="10%">
      <div class="machine-title">
        <i class="bi bi-cpu"></i> <?= htmlspecialchars($maquina['nombre']) ?>
      </div>

      <?php if (!empty($maquina['imagen_portada'])): ?>
        <img src="<?= htmlspecialchars($maquina['imagen_portada']) ?>" class="machine-img-ident" alt="Imagen portada de <?= htmlspecialchars($maquina['nombre']) ?>">
      <?php endif; ?>

      <div class="machine-status <?= $maquina['estado'] === 'activa' ? 'active' : 'inactive' ?>">
        <i class="bi <?= $maquina['estado'] === 'activa' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?>"></i>
        <?= $maquina['estado'] === 'activa' ? 'Activa' : 'Inactiva' ?>
      </div>
    </div>

    <div class="desc-box collapsed" id="descripcionMaquina">
  <?= $maquina['descripcion'] ?>
  <div class="desc-fade"></div>
</div>
<button class="btn-vermas" onclick="toggleDescripcion()" id="btnVerMas">
  Ver descripción completa <i class="bi bi-chevron-down"></i>
</button>


    <?php if (!empty($imagenes)): ?>
    <h5 class="text-center fw-bold mb-3" style="color: #c30010;"><i class="bi bi-images"></i> Galería de Imágenes</h5>
    <div class="swiper mySwiper mb-5">
      <div class="swiper-wrapper">
        <?php foreach ($imagenes as $img): ?>
        <div class="swiper-slide">
          <img src="<?= htmlspecialchars($img['url_imagen']) ?>" class="img-fluid" alt="Imagen de <?= htmlspecialchars($maquina['nombre']) ?>">
        </div>
        <?php endforeach; ?>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($videos)): ?>
    <h5 class="text-center fw-bold mb-3" style="color: #c30010;"><i class="bi bi-play-btn-fill"></i> Videos Demostrativos</h5>
    <div class="videos-carousel mb-5">
      <div class="swiper videosSwiper">
        <div class="swiper-wrapper">
          <?php foreach ($videos as $vid): ?>
            <?php
              $url = $vid['url_video'];
              $videoId = '';
              if (strpos($url, 'youtube.com/watch?v=') !== false) {
                $videoId = explode('v=', $url)[1];
                $videoId = explode('&', $videoId)[0];
              } elseif (strpos($url, 'youtu.be/') !== false) {
                $videoId = explode('youtu.be/', $url)[1];
                $videoId = explode('?', $videoId)[0];
              }
            ?>
            <?php if ($videoId): ?>
            <div class="swiper-slide">
              <div class="embed-responsive">
                <iframe 
                  class="video-thumb" 
                  src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>" 
                  frameborder="0" 
                  allowfullscreen>
                </iframe>
              </div>
              <div class="position-absolute top-0 end-0 m-2">
              <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($videoId) ?>" 
                 target="_blank" 
                 class="btn btn-sm btn-danger">
                <i class="bi bi-box-arrow-up-right"> VER EN YOUTUBE</i>
              </a>
            </div>

            </div>

            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($hayClases): ?>
        <div class="classes-section">
            <h2 class="text-center fw-bold mb-4"><i class="bi bi-calendar-event"></i> ¡Nuestras Clases!</h2>

            <?php if (!empty($clasesMensualidad)): ?>
                <h3 class="class-category-title"><i class="bi bi-award-fill"></i> Clases Incluidas en la Mensualidad</h3>
                <p class="text-white-50 mb-3">Estas clases están disponibles para todos nuestros miembros con mensualidad activa:</p>
                <?php foreach ($clasesMensualidad as $clase): ?>
                    <div class="class-item">
                        <h3><?= htmlspecialchars($clase['nombre']) ?></h3>
                        <p><span class="highlight">Descripción:</span> <?= htmlspecialchars($clase['descripcion']) ?></p>
                        <p><span class="highlight">Días:</span> <?= htmlspecialchars($clase['dias']) ?></p>
                        <p><span class="highlight">Horario:</span> <?= htmlspecialchars($clase['horario']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($clasesAdicionales)): ?>
                <h3 class="class-category-title mt-4"><i class="bi bi-star-fill"></i> Clases Adicionales</h3>
                <p class="text-white-50 mb-3">Estas clases tienen un costo adicional, pero te invitamos a conocerlas y potenciar tu entrenamiento:</p>
                <?php foreach ($clasesAdicionales as $clase): ?>
                    <div class="class-item">
                        <h3><?= htmlspecialchars($clase['nombre']) ?></h3>
                        <p><span class="highlight">Descripción:</span> <?= htmlspecialchars($clase['descripcion']) ?></p>
                        <p><span class="highlight">Días:</span> <?= htmlspecialchars($clase['dias']) ?></p>
                        <p><span class="highlight">Horario:</span> <?= htmlspecialchars($clase['horario']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (empty($clasesMensualidad) && empty($clasesAdicionales)): ?>
                <p class="no-classes-msg">Actualmente no hay clases activas para mostrar. ¡Vuelve pronto!</p>
            <?php endif; ?>

        </div>
    <?php else: ?>
        <div class="classes-section">
            <p class="no-classes-msg">Actualmente no hay clases disponibles. ¡Estamos trabajando para añadir nuevas opciones!</p>
        </div>
    <?php endif; ?>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script>
    // Swiper imágenes
    const swiper = new Swiper(".mySwiper", {
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      centeredSlides: true,
      spaceBetween: 16
    });

    // Swiper videos
    const swiperVideos = new Swiper(".videosSwiper", {
      loop: true,
      pagination: {
        el: ".videosSwiper .swiper-pagination",
        dynamicBullets: true,
      },
      navigation: {
        nextEl: ".videosSwiper .swiper-button-next",
        prevEl: ".videosSwiper .swiper-button-prev",
      },
      centeredSlides: true,
      spaceBetween: 16,
      grabCursor: true,
      touchStartPreventDefault: false,
      simulateTouch: true,
      allowTouchMove: true,
      nested: true, // ✅ Este parámetro es CRUCIAL para iframes
    });

  function toggleDescripcion() {
    const box = document.getElementById('descripcionMaquina');
    const btn = document.getElementById('btnVerMas');
    box.classList.toggle('collapsed');
    const isCollapsed = box.classList.contains('collapsed');
    btn.innerHTML = isCollapsed 
      ? 'Ver descripción completa <i class="bi bi-chevron-down"></i>' 
      : 'Ver menos <i class="bi bi-chevron-up"></i>';
  }

  </script>
</body>
</html>