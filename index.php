<?php
// 1. Conexión a tu base de datos de Hostinger
$conn = new mysqli("localhost", "u166935491_admin", "cS)Au4-kA2dF", "u166935491_palominoprime");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 2. Traer SOLO las 4 propiedades destacadas
$sql_destacadas = "SELECT * FROM propiedades WHERE is_destacada = 1 LIMIT 4";
$resultado_destacadas = $conn->query($sql_destacadas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palomino Prime - Mar y Río | Tu Inmobiliaria en Palomino</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
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
                                $precio_mostrar = "$" . number_format($prop['precio_usd'], 0, ',', '.') . " USD";
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
                                    // Muestra los metros cuadrados si la base de datos tiene el dato
                                    if (!empty($prop['area_m2'])) {
                                        echo "<p style='margin: 3px 0;'><strong>Área:</strong> " . htmlspecialchars($prop['area_m2']) . " m²</p>";
                                    }
                                    
                                    // Muestra el frente x fondo si registraste dimensiones
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
                
                <!-- Grid de Iconos: 6 columnas -->
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
        </section>

        <section class="map-section">
            <div class="container">
                <h3>UBICACIÓN</h3>
                <div id="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15655.483120619865!2d-73.56382098064603!3d11.197042571343714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e83344b5a26685f%3A0xc3f833b3a32f7a0b!2sPalomino%2C%20Dibulla%2C%20La%20Guajira!5e0!3m2!1ses!2sco!4v1716301345678!5m2!1ses!2sco" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="map-filter">
                    <input type="text" placeholder="Ubicación">
                    <input type="text" placeholder="Tipo">
                    <input type="text" placeholder="Rango Precio">
                    <button class="btn btn-primary">BUSCAR</button>
                </div>
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
</body>
</html>