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
                    <source media="(min-width: 768px)" srcset="img/palomino-LOGO-MAR-Y-RIO.png">
                    <!-- Pantallas pequeñas (Celulares: se carga por defecto) -->
                    <img src="img/palomino.png" alt="Palomino Mar y Río" style="max-height: 100px !important; width: auto !important; object-fit: contain !important; display: block !important;">
                </picture>
            </a>
            
            <nav>
                <ul>
                    <li><a href="index.php">INICIO</a></li>
                    <li><a href="index.php#nosotros">NOSOTROS</a></li>
                    <li><a href="propiedades.php">PROPIEDADES</a></li>
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

                <!-- INICIO DEL CÓDIGO INTELIGENTE DE CARACTERÍSTICAS -->
                <div class="features-grid">
                    <?php if (!empty($propiedad['habitaciones']) && $propiedad['habitaciones'] > 0): ?>
                        <div class="feature-card">🛏️ <?= htmlspecialchars($propiedad['habitaciones']) ?> Habitaciones</div>
                    <?php endif; ?>
                    
                    <?php if (!empty($propiedad['banos']) && $propiedad['banos'] > 0): ?>
                        <div class="feature-card">🚿 <?= htmlspecialchars($propiedad['banos']) ?> Baños</div>
                    <?php endif; ?>

                    <?php if (!empty($propiedad['area_m2'])): ?>
                        <div class="feature-card">📐 <?= htmlspecialchars($propiedad['area_m2']) ?> m² Área</div>
                    <?php endif; ?>

                    <?php if (!empty($propiedad['dimensiones'])): ?>
                        <div class="feature-card">📏 <?= htmlspecialchars($propiedad['dimensiones']) ?> Dimensiones</div>
                    <?php endif; ?>
                </div>
                <!-- FIN DEL CÓDIGO INTELIGENTE DE CARACTERÍSTICAS -->

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


    <footer class="footer-premium">
        <div class="footer-container">
            <!-- Columna 1: Marca y descripción -->
            <div class="footer-col brand-col">
                <!-- Usamos tu logo blanco o dejamos el texto si prefieres -->
                <h3 class="footer-logo">PALOMINO <span>PRIME</span></h3>
                <p>Tu agencia inmobiliaria de confianza. Te guiamos en cada paso para asegurar tu inversión en el paraíso entre el mar Caribe y la majestuosa Sierra Nevada.</p>
            </div>

            <!-- Columna 2: Enlaces rápidos -->
            <div class="footer-col">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="index.php#propiedades">Propiedades Destacadas</a></li>
                    <li><a href="propiedades.php">Catálogo Completo</a></li>
                    <li><a href="#">Sobre Nosotros</a></li>
                </ul>
            </div>

            <!-- Columna 3: Contacto (Aquí integramos la ubicación física) -->
            <div class="footer-col contacto-col">
                <h4>Contacto</h4>
                <p><img src="https://img.icons8.com/ios-filled/50/ffffff/marker.png" alt="Ubicación"> Palomino, La Guajira, Colombia</p>
                <p><img src="https://img.icons8.com/ios-filled/50/ffffff/phone.png" alt="Teléfono"> +57 300 000 0000</p>
                <p><img src="https://img.icons8.com/ios-filled/50/ffffff/new-post.png" alt="Email"> contacto@palominoprime.com</p>
            </div>

            <!-- Columna 4: Redes Sociales -->
            <div class="footer-col social-col">
                <h4>Síguenos</h4>
                <div class="social-icons">
                    <a href="#"><img src="https://img.icons8.com/ios-filled/50/ffffff/facebook-new.png" alt="Facebook"></a>
                    <a href="#"><img src="https://img.icons8.com/ios-filled/50/ffffff/instagram-new.png" alt="Instagram"></a>
                    <a href="#"><img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp--v1.png" alt="WhatsApp"></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y') ?> Palomino Prime Mar y Río. Todos los derechos reservados.</p>
        </div>
    </footer>

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