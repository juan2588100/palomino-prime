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
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    
    <!-- Librería CSS del Mapa Interactivo (Leaflet) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php" style="display: flex !important; align-items: center !important; gap: 15px !important; text-decoration: none; float: left; margin-top: 5px;">
                <picture>
                    <source media="(min-width: 768px)" srcset="img/palomino-LOGO-MAR-Y-RIO.png">
                    <img src="img/palomino.png" alt="Palomino Mar y Río" style="max-height: 100px !important; width: auto !important; object-fit: contain !important; display: block !important;">
                </picture>
            </a>
            <nav>
                <ul>
                    <li><a href="index.php">INICIO</a></li>
                    <li><a href="#">NOSOTROS</a></li>
                    <li><a href="propiedades.php">PROPIEDADES</a></li>
                    <li><a href="#">PALOMINO</a></li>
                    <li><a href="#">CONTACTO</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('img/banner.png') !important; background-size: cover !important; background-position: center !important; background-repeat: no-repeat !important; min-height: 60vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: #fff; padding: 0 20px;">
            <h1 style="font-size: 48px; font-weight: bold; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.6);">TU PARAÍSO ENTRE EL MAR Y EL RÍO</h1>
            <p style="font-size: 20px; margin-bottom: 30px; text-shadow: 1px 1px 2px rgba(0,0,0,0.6);">Invierte en el paraíso entre el mar y la Sierra Nevada.</p>
            <a href="#propiedades" class="btn" style="background-color: #008080; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background 0.3s;">VER PROPIEDADES DESTACADAS</a>
        </section>

        <section class="properties-section" id="propiedades">
            <div class="container">
                <h3>DESTACADAS</h3>
                <div class="featured-properties-grid">
                    <?php
                    // Si hay propiedades destacadas en la base de datos, las muestra
                    if ($resultado_destacadas->num_rows > 0) {
                        while($prop = $resultado_destacadas->fetch_assoc()) {
                            
                            // Formatear el precio para que diferencie USD o COP
                            $precio_mostrar = "";
                            if (!empty($prop['precio_usd']) && $prop['precio_usd'] > 0) {
                                $precio_mostrar = "$" . number_format($prop['precio_usd'], 2, '.', ',') . " USD";
                            } elseif (!empty($prop['precio_cop']) && $prop['precio_cop'] > 0) {
                                $precio_mostrar = "$" . number_format($prop['precio_cop'], 0, ',', '.') . " COP";
                            }
                            ?>
                            
                            <div class="property-card">
                                <img src="<?= htmlspecialchars($prop['imagen_principal']) ?>" alt="<?= htmlspecialchars($prop['titulo']) ?>" style="width: 100%; height: 250px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; margin-bottom: 15px;">
                                
                                <h4 style="text-transform: uppercase;"><?= htmlspecialchars($prop['titulo']) ?></h4>
                                
                                <p class="price"><?= $precio_mostrar ?></p>
                                <p class="location">Ubicación: <?= htmlspecialchars($prop['ubicacion_texto']) ?></p>
                                
                                <!-- INICIO DEL CÓDIGO INTELIGENTE DE MEDIDAS -->
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
                                <!-- FIN DEL CÓDIGO INTELIGENTE DE MEDIDAS -->

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

        <!-- SECCIÓN: MÁS QUE INMOBILIARIA -->
        <section class="about-section-custom">
            <div class="container">
                <h3>PALOMINO PRIME MAR Y RÍO:<br><span>MÁS QUE INMOBILIARIA</span></h3>
                <p class="intro-text">Nuestra agencia inmobiliaria se especializa en ofrecer propiedades únicas y exclusivas en el hermoso Palomino, donde la majestuosa Sierra Nevada se encuentra con el mar Caribe. Estamos comprometidos en ayudarte a encontrar tu hogar ideal en este entorno natural excepcional.</p>
                
                <!-- Grid de Iconos -->
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

                <!-- Barra Píldora de Estadísticas -->
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

        <section class="map-section">
            <div class="container">
                <h3>EXPLORA NUESTRO MAPA DE PROPIEDADES</h3>
                
                <!-- AQUI VA EL NUEVO MAPA INTERACTIVO -->
                <div id="mapa-interactivo" style="width: 100%; height: 500px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 2px solid #008080;"></div>
                
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="logo">Palomino Prime</div>
            <div class="footer-links">
                <a href="#">Inicio</a>
                <a href="#">Nosotros</a>
                <a href="#">Propiedades</a>
                <a href="#">Contacto</a>
            </div>
            <div class="footer-social">
                <a href="#">FB</a>
                <a href="#">TW</a>
                <a href="#">IG</a>
                <a href="#">IN</a>
            </div>
        </div>
        <div class="container copyright">
            <p>© 2024 Palomino Prime Mar y Río. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Librería JS del Mapa Interactivo (Leaflet) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Script para dibujar el mapa y los pines -->
    <script>
        // 1. Inicializar el mapa centrado en Palomino
        var map = L.map('mapa-interactivo').setView([11.2475, -73.5658], 14);

        // 2. Cargar la capa base gratuita de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // 3. Recibir los datos de las propiedades desde PHP
        var propiedades = <?= $json_mapa ?>;

        // 4. Dibujar los pines en el mapa
        propiedades.forEach(function(prop) {
            // Asegurarnos de que los datos de latitud y longitud sean números
            var lat = parseFloat(prop.latitud);
            var lng = parseFloat(prop.longitud);

            if (!isNaN(lat) && !isNaN(lng)) {
                // Formatear el precio
                var precioText = "";
                if(prop.precio_usd > 0) {
                    precioText = "$" + Number(prop.precio_usd).toLocaleString('en-US', {minimumFractionDigits: 2}) + " USD";
                } else if(prop.precio_cop > 0) {
                    precioText = "$" + Number(prop.precio_cop).toLocaleString('es-CO') + " COP";
                }

                // Crear el contenido de la tarjetita al hacer clic (Popup)
                var popupContenido = `
                    <div style="text-align:center; width: 220px;">
                        <img src="${prop.imagen_principal}" alt="${prop.titulo}" style="width:100%; height:130px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                        <h4 style="margin: 0 0 5px 0; font-size: 14px; text-transform: uppercase; color: #333;">${prop.titulo}</h4>
                        <p style="margin: 0 0 12px 0; font-size: 15px; font-weight: bold; color: #008080;">${precioText}</p>
                        <a href="propiedad.php?id=${prop.id}" style="display:block; background-color:#008080; color:white; padding:8px; text-decoration:none; border-radius:5px; font-weight:bold; font-size:12px;">VER PROPIEDAD</a>
                    </div>
                `;

                // Agregar el pin al mapa con su tarjetita
                L.marker([lat, lng]).addTo(map).bindPopup(popupContenido);
            }
        });
    </script>
</body>
</html>