<?php
// 1. Conexión a la base de datos
$conn = new mysqli("localhost", "u166935491_admin", "cS)Au4-kA2dF", "u166935491_palominoprime");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 2. Capturar lo que el usuario busca y cómo quiere ordenarlo
$busqueda = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$ordenar = isset($_GET['ordenar']) ? $_GET['ordenar'] : 'reciente';

// 3. Armar la consulta SQL de forma inteligente según los filtros
$sql_todas = "SELECT * FROM propiedades";
$condiciones = [];

// Si el usuario escribió algo en la barra clásica
if (!empty($busqueda)) {
    // Busca en el título, ubicación o descripción
    $condiciones[] = "(titulo LIKE '%$busqueda%' OR ubicacion_texto LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%')";
}

// Unir las condiciones si existen
if (count($condiciones) > 0) {
    $sql_todas .= " WHERE " . implode(" AND ", $condiciones);
}

// 4. Aplicar el orden seleccionado
switch ($ordenar) {
    case 'precio_alto':
        $sql_todas .= " ORDER BY precio_cop DESC"; 
        break;
    case 'precio_bajo':
        $sql_todas .= " ORDER BY precio_cop ASC";
        break;
    case 'area_mayor':
        $sql_todas .= " ORDER BY area_m2 DESC";
        break;
    case 'area_menor':
        $sql_todas .= " ORDER BY area_m2 ASC";
        break;
    case 'antiguo':
        $sql_todas .= " ORDER BY id ASC";
        break;
    case 'reciente':
    default:
        $sql_todas .= " ORDER BY id DESC"; // Por defecto, los últimos agregados
        break;
}

$resultado_todas = $conn->query($sql_todas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Propiedades | Palomino Prime</title>
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
                    <li><a href="propiedades.php">PROPIEDADES</a></li>
                    <li><a href="index.php#palomino">PALOMINO</a></li>
                    <li><a href="index.php#contacto">CONTACTO</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="properties-section" style="padding-top: 120px;">
            <div class="container">
                <h1 style="text-align: center; margin-bottom: 20px; color: #008080;">NUESTRO CATÁLOGO DE PROPIEDADES</h1>
                
                <!-- INICIO DEL BUSCADOR Y FILTROS -->
                <div class="search-filter-container" style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <form action="propiedades.php" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
                        
                        <!-- Barra de búsqueda clásica -->
                        <div style="flex: 1; min-width: 250px;">
                            <input type="text" name="q" placeholder="Buscar por palabra clave, título o ubicación..." value="<?= htmlspecialchars($busqueda) ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px;">
                        </div>
                        
                        <!-- Filtro Ordenar Por -->
                        <div style="min-width: 200px;">
                            <select name="ordenar" onchange="this.form.submit()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; cursor: pointer;">
                                <option value="reciente" <?= $ordenar == 'reciente' ? 'selected' : '' ?>>🕒 Más recientes</option>
                                <option value="antiguo" <?= $ordenar == 'antiguo' ? 'selected' : '' ?>>⏳ Menos recientes</option>
                                <option value="precio_alto" <?= $ordenar == 'precio_alto' ? 'selected' : '' ?>>📈 Mayor precio</option>
                                <option value="precio_bajo" <?= $ordenar == 'precio_bajo' ? 'selected' : '' ?>>📉 Menor precio</option>
                                <option value="area_mayor" <?= $ordenar == 'area_mayor' ? 'selected' : '' ?>>📐 Mayor área</option>
                                <option value="area_menor" <?= $ordenar == 'area_menor' ? 'selected' : '' ?>>📏 Menor área</option>
                            </select>
                        </div>
                        
                        <!-- Botón Buscar -->
                        <div>
                            <button type="submit" class="btn" style="background-color: #008080; color: white; padding: 12px 25px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">BUSCAR</button>
                        </div>

                        <!-- Botón Limpiar (Solo aparece si el usuario hizo una búsqueda) -->
                        <?php if(!empty($busqueda) || $ordenar != 'reciente'): ?>
                            <div>
                                <a href="propiedades.php" style="display: inline-block; padding: 12px 20px; color: #555; text-decoration: none; border: 1px solid #ddd; border-radius: 6px; background: white;">Limpiar Filtros</a>
                            </div>
                        <?php endif; ?>

                    </form>
                </div>
                <!-- FIN DEL BUSCADOR Y FILTROS -->

                <div class="featured-properties-grid">
                    <?php
                    if ($resultado_todas->num_rows > 0) {
                        while($prop = $resultado_todas->fetch_assoc()) {
                            
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
                        echo "<div style='text-align:center; width:100%; padding: 40px;'>";
                        echo "<h3 style='color: #666;'>No se encontraron propiedades 😔</h3>";
                        echo "<p>Intenta buscar con otras palabras o limpia los filtros.</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

   
</body>
</html>

<?php $conn->close(); ?>