<?php
// 1. CONEXIÓN A LA BASE DE DATOS (Ajusta con tus datos reales de Hostinger)
$host = "localhost"; // O el servidor de Hostinger
$user = "u166935491_admin";
$pass = "cS)Au4-kA2dF";
$db = "u166935491_palominoprime";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 2. OBTENER EL ID DE LA PROPIEDAD (Por defecto usaremos la 1 si no se envía)
$id_propiedad = isset($_GET['id']) ? intval($_GET['id']) : 1;

// 3. OBTENER DATOS DE LA PROPIEDAD
$sql_prop = "SELECT * FROM propiedades WHERE id = $id_propiedad";
$resultado_prop = $conn->query($sql_prop);

if ($resultado_prop->num_rows > 0) {
    $propiedad = $resultado_prop->fetch_assoc();
} else {
    die("Propiedad no encontrada.");
}

// 4. OBTENER LAS FOTOS DE LA GALERÍA
$sql_fotos = "SELECT * FROM galeria_fotos WHERE propiedad_id = $id_propiedad ORDER BY orden ASC";
$resultado_fotos = $conn->query($sql_fotos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($propiedad['titulo']) ?> | Palomino Prime</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="propiedad.css">
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="propiedad.css?v=<?= time(); ?>">

    <style>
        header {
            position: static !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
        <a href="index.php" style="display: flex !important; align-items: center !important; gap: 15px !important; text-decoration: none; float: left; margin-top: 5px;">
    
    <picture>
        <!-- Pantallas medianas y grandes (Tablets y Escritorio: 768px o más) -->
        <source media="(min-width: 768px)" srcset="img/palomino-LOGO-MAR-Y-RIO.jpg">
        
        <!-- Pantallas pequeñas (Celulares: se carga por defecto) -->
        <img src="img/palomino.jpg" alt="Palomino Mar y Río" style="max-height: 100px !important; width: auto !important; object-fit: contain !important; display: block !important;">
    </picture>
    
    <!-- Texto de la marca -->
   

</a>
            
            <nav>
                <ul>
                    <li><a href="index.php">INICIO</a></li>
                    <li><a href="index.php#nosotros">NOSOTROS</a></li>
                    <li><a href="index.php#propiedades">PROPIEDADES</a></li>
                    <li><a href="index.php#palomino">PALOMINO</a></li>
                    <li><a href="index.php#contacto">CONTACTO</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="contenedor-principal">
        
        <div class="propiedad-header">
            <h1><?= htmlspecialchars($propiedad['titulo']) ?></h1>
            <p class="ubicacion">📍 <?= htmlspecialchars($propiedad['ubicacion_texto']) ?></p>
        </div>

        <section class="carrusel-contenedor">
            <div class="swiper miCarrusel">
                <div class="swiper-wrapper">
                    <?php
                    // 1. Siempre mostramos la imagen principal como el primer slide
                    echo '<div class="swiper-slide">';
                    echo '<img src="' . htmlspecialchars($propiedad['imagen_principal']) . '" alt="Vista principal">';
                    echo '</div>';

                    // 2. Luego recorremos y agregamos las fotos adicionales de la galería (si existen)
                    if ($resultado_fotos->num_rows > 0) {
                        while($foto = $resultado_fotos->fetch_assoc()) {
                            echo '<div class="swiper-slide">';
                            echo '<img src="' . htmlspecialchars($foto['url_foto']) . '" alt="Vista propiedad">';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </section>

        <div class="contenido-doble-columna">
            <article class="detalles-propiedad">
                <h2>Acerca de esta propiedad</h2>
                <p><?= nl2br(htmlspecialchars($propiedad['descripcion'])) ?></p>

                <div class="features-grid">
                    <div class="feature-card">🛏️ <?= $propiedad['habitaciones'] ?> Habitaciones</div>
                    <div class="feature-card">🚿 <?= $propiedad['banos'] ?> Baños</div>
                    <div class="feature-card">📐 <?= $propiedad['area_m2'] ?> m² Área</div>
                </div>

                <div id="mapa-container">
                    <h3>Ubicación Exacta</h3>
                    <iframe 
                        src="https://maps.google.com/maps?q=<?= $propiedad['latitud'] ?>,<?= $propiedad['longitud'] ?>&z=15&output=embed" 
                        width="100%" height="350" style="border:0; border-radius:12px;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </article>

            <aside class="sticky-sidebar">
                <div class="tarjeta-reserva">
                    <p class="precio-usd">$<?= number_format($propiedad['precio_usd'], 0, ',', '.') ?> USD</p>
                    <p class="precio-cop">$<?= number_format($propiedad['precio_cop'], 0, ',', '.') ?> COP</p>
                    <hr>
                    <a href="https://wa.me/573000000000?text=Hola,%20me%20interesa%20la%20propiedad:%20<?= urlencode($propiedad['titulo']) ?>" target="_blank" class="btn btn-primary btn-contacto">CONTACTAR POR WHATSAPP</a>
                </div>
            </aside>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.miCarrusel', {
            loop: true,
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            pagination: { el: '.swiper-pagination', clickable: true },
            autoplay: { delay: 4000, disableOnInteraction: false }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>