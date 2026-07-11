<?php
// 1. Conexión a la base de datos
$conn = new mysqli("localhost", "u166935491_admin", "cS)Au4-kA2dF", "u166935491_palominoprime");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 2. Traer TODAS las propiedades (ordenadas de la más nueva a la más antigua)
$sql_todas = "SELECT * FROM propiedades ORDER BY id DESC";
$resultado_todas = $conn->query($sql_todas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas las Propiedades | Palomino Prime</title>
    <link rel="stylesheet" href="style.css">
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
                    <li><a href="index.php#nosotros">NOSOTROS</a></li>
                    <!-- El enlace ahora apunta correctamente a sí mismo -->
                    <li><a href="propiedades.php">PROPIEDADES</a></li>
                    <li><a href="index.php#palomino">PALOMINO</a></li>
                    <li><a href="index.php#contacto">CONTACTO</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="properties-section" style="padding-top: 120px;"> <!-- Padding extra para que el menú no tape el título -->
            <div class="container">
                <h1 style="text-align: center; margin-bottom: 40px; color: #008080;">NUESTRO CATÁLOGO DE PROPIEDADES</h1>
                
                <div class="featured-properties-grid">
                    <?php
                    // Recorremos todas las propiedades encontradas en la tabla
                    if ($resultado_todas->num_rows > 0) {
                        while($prop = $resultado_todas->fetch_assoc()) {
                            
                            // Lógica para mostrar USD o COP
                            $precio_mostrar = "";
                            if (!empty($prop['precio_usd']) && $prop['precio_usd'] > 0) {
                                $precio_mostrar = "$" . number_format($prop['precio_usd'], 0, ',', '.') . " USD";
                            } elseif (!empty($prop['precio_cop']) && $prop['precio_cop'] > 0) {
                                $precio_mostrar = "$" . number_format($prop['precio_cop'], 0, ',', '.') . " COP";
                            }
                            ?>
                            
                            <!-- Tarjeta de la propiedad -->
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
                                
                                <!-- Botón que envía al "molde" individual con el ID correcto -->
                                <a href="propiedad.php?id=<?= $prop['id'] ?>" class="btn btn-secondary">MÁS DETALLES</a>
                            </div>
                            
                            <?php
                        }
                    } else {
                        echo "<p style='text-align:center; width:100%;'>No hay propiedades disponibles en este momento.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="logo">Palomino Prime</div>
            <div class="footer-links">
                <a href="index.php">Inicio</a>
                <a href="#">Nosotros</a>
                <a href="propiedades.php">Propiedades</a>
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

<?php $conn->close(); ?>