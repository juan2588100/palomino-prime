<?php
// 1. Conexión a tu base de datos de Hostinger
$conn = new mysqli("localhost", "u166935491_admin", "cS)Au4-kA2dF", "u166935491_palominoprime");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 2. Traer SOLO las 4 propiedades destacadas para la cuadrícula
$sql_destacadas = "SELECT * FROM propiedades WHERE is_destacada = 1 LIMIT 4";
$resultado_destacadas = $conn->query($sql_destacadas);

// 3. Traer TODAS las propiedades que tengan coordenadas para el mapa interactivo
$sql_mapa = "SELECT id, titulo, precio_usd, precio_cop, imagen_principal, latitud, longitud FROM propiedades WHERE latitud IS NOT NULL AND longitud IS NOT NULL";
$resultado_mapa = $conn->query($sql_mapa);
$propiedades_mapa = [];
if ($resultado_mapa && $resultado_mapa->num_rows > 0) {
    while($row = $resultado_mapa->fetch_assoc()) {
        $propiedades_mapa[] = $row;
    }
}
// Convertimos los datos a JSON para que JavaScript pueda dibujar los pines
$json_mapa = json_encode($propiedades_mapa);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palomino Prime - Mar y Río | Tu Inmobiliaria en Palomino</title>
    
    <!-- Fuentes tipográficas de Google Fonts para el Banner Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@300;400&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    
    <!-- Librería CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
</head>
<body>
<?php include 'header.php'; ?>

    <main>
        <!-- HERO Y BUSCADOR ÉPICO -->
        <section class="hero" style="background-image: url('img/banner.png') !important; background-size: cover !important; background-position: center !important; background-repeat: no-repeat !important; min-height: 65vh; position: relative; margin-bottom: 80px; display: flex; align-items: center;">
            
            <div class="hero-text-container">
                <span class="hero-script">Vive tu sueño en</span>
                <h1 class="hero-title">PALOMINO</h1>
                <div class="hero-divider"></div>
                <p class="hero-subtitle">Donde el mar Caribe se encuentra<br>con la Sierra Nevada.</p>
                
            </div>

            <div class="epic-search-wrapper">
                <form action="propiedades.php" method="GET" class="epic-search-bar">
                    <div class="search-group">
                        <label><img src="https://img.icons8.com/ios/50/000000/marker--v1.png" alt="Ubicación"> UBICACIÓN</label>
                        <select name="ubicacion">
                            <option value="">Palomino, La Guajira</option>
                            <option value="sierra">Sierra Nevada</option>
                            <option value="playa">Frente al Mar</option>
                        </select>
                    </div>
                    <div class="search-divider"></div>
                    <div class="search-group">
                        <label><img src="https://img.icons8.com/ios/50/000000/home--v1.png" alt="Tipo"> TIPO DE PROPIEDAD</label>
                        <select name="tipo">
                            <option value="">Todos</option>
                            <option value="lote">Lote</option>
                            <option value="cabana">Cabaña</option>
                            <option value="villa">Villa</option>
                        </select>
                    </div>
                    <div class="search-divider"></div>
                    <div class="search-group">
                        <label><img src="https://img.icons8.com/ios/50/000000/us-dollar-circled--v1.png" alt="Precio"> RANGO DE PRECIO</label>
                        <select name="precio">
                            <option value="">Todos</option>
                            <option value="bajo">Hasta $50,000 USD</option>
                            <option value="medio">$50k - $100k USD</option>
                            <option value="alto">Más de $100k USD</option>
                        </select>
                    </div>
                    <div class="search-button-group">
                        <button type="submit">BUSCAR PROPIEDADES</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- SECCIÓN DE DESTACADAS -->
        <section class="properties-section" id="propiedades">
            <div class="container">
                <h3>DESTACADAS</h3>
                <!-- Se agregó el id="carrusel-destacadas" para el script de JS -->
                <div class="featured-properties-grid" id="carrusel-destacadas">
                    <?php
                    if ($resultado_destacadas->num_rows > 0) {
                        while($prop = $resultado_destacadas->fetch_assoc()) {
                            ?>
                            <div class="property-card">
                                <img src="<?= htmlspecialchars($prop['imagen_principal']) ?>" alt="<?= htmlspecialchars($prop['titulo']) ?>" style="width: 100%; height: 250px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; margin-bottom: 15px;">
                                <h4 style="text-transform: uppercase;"><?= htmlspecialchars($prop['titulo']) ?></h4>
                                
                                <!-- PRECIOS DOBLES COP Y USD -->
                                <div style="margin-bottom: 12px;">
                                    <?php if (!empty($prop['precio_cop']) && $prop['precio_cop'] > 0): ?>
                                        <p class="price" style="margin: 0; font-size: 18px; color: #008080; font-weight: bold;">$<?= number_format($prop['precio_cop'], 0, ',', '.') ?> COP</p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($prop['precio_usd']) && $prop['precio_usd'] > 0): ?>
                                        <p style="margin: 0; font-size: 14px; color: #666; font-weight: bold;">$<?= number_format($prop['precio_usd'], 2, '.', ',') ?> USD</p>
                                    <?php endif; ?>
                                </div>

                                <p class="location">Ubicación: <?= htmlspecialchars($prop['ubicacion_texto']) ?></p>
                                <div class="medidas-propiedad" style="font-size: 13px; color: #444; margin-bottom: 15px;">
                                    <?php 
                                    if (!empty($prop['area_m2'])) {
                                        echo "<p style='margin: 3px 0;'><strong>Área:</strong> " . htmlspecialchars($prop['area_m2']) . " m²</p>";
                                    }
                                    if (!empty($prop['dimensiones'])) {
                                        echo "<p style='margin: 3px 0;'><strong>Dimensiones:</strong> " . htmlspecialchars($prop['dimensiones']) . "</p>";
                                    }
                                    ?>
                                </div>
                                <a href="propiedad.php?id=<?= $prop['id'] ?>" class="btn btn-secondary">MÁS DETALLES</a>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No hay propiedades destacadas por el momento.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- MÁS QUE INMOBILIARIA -->
        <section class="about-section-custom" id="nosotros">
            <div class="container">
                <h3>PALOMINO MAR & RÍO:<br><span>MÁS QUE INMOBILIARIA</span></h3>
                <p class="intro-text">Nuestra agencia inmobiliaria se especializa en ofrecer propiedades únicas y exclusivas en el hermoso Palomino, donde la majestuosa Sierra Nevada se encuentra con el mar Caribe. Estamos comprometidos en ayudarte a encontrar tu hogar ideal en este entorno natural excepcional.</p>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <img src="https://img.icons8.com/ios/100/00635d/palm-tree.png" alt="Expertos Locales">
                        <h4>Expertos Locales</h4>
                        <p>Conocemos cada rincón de Palomino y sus mejores oportunidades.</p>
                    </div>
                    <div class="feature-item">
                        <img src="https://img.icons8.com/ios/100/00635d/cottage--v1.png" alt="Propiedades Exclusivas">
                        <h4>Propiedades<br>Exclusivas</h4>
                        <p>Seleccionamos propiedades únicas con alto potencial.</p>
                    </div>
                    <div class="feature-item">
                        <img src="https://img.icons8.com/ios/100/00635d/positive-dynamic.png" alt="Alta Valorización">
                        <h4>Alta<br>Valorización</h4>
                        <p>Invierte hoy en una zona de constante crecimiento turístico y comercial.</p>
                    </div>
                    <div class="feature-item">
                        <img src="https://img.icons8.com/ios/100/00635d/marker--v1.png" alt="Ubicación Privilegiada">
                        <h4>Ubicación<br>Privilegiada</h4>
                        <p>Entre el mar Caribe y la Sierra Nevada de Santa Marta.</p>
                    </div>
                    <div class="feature-item">
                        <img src="https://img.icons8.com/ios/100/00635d/handshake.png" alt="Servicio Personalizado">
                        <h4>Servicio<br>Personalizado</h4>
                        <p>Te guiamos desde la búsqueda hasta la escritura.</p>
                    </div>
                    <div class="feature-item">
                        <img src="https://img.icons8.com/ios/100/00635d/like--v1.png" alt="Estilo de Vida Único">
                        <h4>Estilo de Vida<br>Único</h4>
                        <p>Vive rodeado de naturaleza, playas vírgenes y la magia de Palomino.</p>
                    </div>
                </div>

                <div class="stats-bar-verde">
                    <div class="stat-item">
                        <img src="https://img.icons8.com/ios/100/ffffff/home--v1.png" alt="Propiedades">
                        <div class="stat-text">
                            <div class="number">120+</div>
                            <div class="label">PROPIEDADES<br>DISPONIBLES</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <img src="https://img.icons8.com/ios/100/ffffff/user-group-man-man.png" alt="Clientes">
                        <div class="stat-text">
                            <div class="number">98%</div>
                            <div class="label">CLIENTES<br>SATISFECHOS</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <img src="https://img.icons8.com/ios/100/ffffff/star--v1.png" alt="Años">
                        <div class="stat-text">
                            <div class="number">10+</div>
                            <div class="label">AÑOS DE EXPERIENCIA<br>EN PALOMINO</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <img src="https://img.icons8.com/ios/100/ffffff/shield.png" alt="Seguridad">
                        <div class="stat-text">
                            <div class="number">100%</div>
                            <div class="label">ACOMPAÑAMIENTO<br>LEGAL Y SEGURO</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAPA -->
        <section class="map-section" id="palomino">
            <div class="container">
                <h3>EXPLORA NUESTRO MAPA DE PROPIEDADES</h3>
                <div id="mapa-interactivo" style="width: 100%; height: 500px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 2px solid #008080;"></div>
            </div>
        </section>
    </main>

    <!-- INCLUSIÓN DEL FOOTER MODULAR -->
    <?php include 'footer.php'; ?>

    <!-- Librería JS de Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        // Inicializar el mapa centrado en Palomino
        var map = L.map('mapa-interactivo').setView([11.2475, -73.5658], 14);

       // Capa de mapa estilo Google Maps a color
       L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '© Google Maps'
        }).addTo(map);

        // Datos desde PHP
        var propiedades = <?= $json_mapa ?>;

        propiedades.forEach(function(prop) {
            var lat = parseFloat(prop.latitud);
            var lng = parseFloat(prop.longitud);

            if (!isNaN(lat) && !isNaN(lng)) {
                
                // PRECIOS DOBLES PARA EL MAPA
                var preciosBloque = '<div style="margin-bottom: 12px;">';
                if(prop.precio_cop > 0) {
                    preciosBloque += '<p style="margin: 0; font-size: 16px; color: #008080; font-weight: bold;">$' + Number(prop.precio_cop).toLocaleString('es-CO') + ' COP</p>';
                }
                if(prop.precio_usd > 0) {
                    preciosBloque += '<p style="margin: 0; font-size: 13px; color: #666; font-weight: bold;">$' + Number(prop.precio_usd).toLocaleString('en-US', {minimumFractionDigits: 2}) + ' USD</p>';
                }
                preciosBloque += '</div>';

                var popupContenido = `
                    <div style="text-align:center; width: 220px; font-family: Arial, sans-serif;">
                        <img src="${prop.imagen_principal}" alt="${prop.titulo}" style="width:100%; height:130px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                        <h4 style="margin: 0 0 5px 0; font-size: 14px; text-transform: uppercase; color: #333;">${prop.titulo}</h4>
                        ${preciosBloque}
                        <a href="propiedad.php?id=${prop.id}" style="display:block; background-color:#008080; color:white; padding:8px; text-decoration:none; border-radius:5px; font-weight:bold; font-size:12px;">VER PROPIEDAD</a>
                    </div>
                `;

                L.marker([lat, lng]).addTo(map).bindPopup(popupContenido);
            }
        });
    </script>

    <!-- Script para Auto-Scroll del Carrusel Móvil -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const carrusel = document.getElementById('carrusel-destacadas');
            let autoScrollTimer;

            function startScroll() {
                // Solo activamos el auto-scroll en pantallas móviles (<= 768px)
                if (window.innerWidth <= 768 && carrusel) {
                    autoScrollTimer = setInterval(() => {
                        const maxScroll = carrusel.scrollWidth - carrusel.clientWidth;
                        
                        // Si ya llegó al final (con un pequeño margen de 5px), vuelve al inicio
                        if (carrusel.scrollLeft >= maxScroll - 5) {
                            carrusel.scrollTo({ left: 0, behavior: 'smooth' });
                        } else {
                            // Toma el ancho de la primera tarjeta + el gap (15px) para avanzar
                            const tarjeta = carrusel.querySelector('.property-card');
                            if(tarjeta) {
                                const avance = tarjeta.clientWidth + 15;
                                carrusel.scrollBy({ left: avance, behavior: 'smooth' });
                            }
                        }
                    }, 3500); // Tiempo de espera en milisegundos (3.5 segundos por tarjeta)
                }
            }

            // Iniciar el bucle
            startScroll();

            // Experiencia de Usuario: Detener el auto-scroll si el usuario toca la pantalla
            if(carrusel) {
                carrusel.addEventListener('touchstart', () => {
                    clearInterval(autoScrollTimer);
                }, { passive: true });
                
                // Reanudar el auto-scroll 4 segundos después de que el usuario deje de tocar
                carrusel.addEventListener('touchend', () => {
                    clearInterval(autoScrollTimer); // Limpiar cualquier contador previo
                    setTimeout(startScroll, 4000);
                }, { passive: true });
            }
        });
    </script>
</body>
</html>