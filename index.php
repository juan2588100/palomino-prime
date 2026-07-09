<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palomino Prime - Mar y Río | Tu Inmobiliaria en Palomino</title>
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
                    <li><a href="#">NOSOTROS</a></li>
                    <li><a href="propiedad.php">PROPIEDADES</a></li>
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
                    
                    <div class="property-card">
                        <img src="img/propiedades/destacadas/cabaña-mar.webp" alt="Cabaña frente al mar" style="width: 100%; height: 250px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; margin-bottom: 15px;">
                        <h4>CABAÑA FRENTE AL MAR</h4>
                        <p class="price">$180,000 USD</p>
                        <p class="location">Ubicación: Palomino Prime</p>
                        <a href="propiedad.php?id=1" class="btn btn-secondary">MÁS DETALLES</a>
                    </div>
                    
                    <div class="property-card">
                        <img src="img/propiedades/destacadas/cabaña-mar1.avif" alt="Villa Sierra del Mar" style="width: 100%; height: 250px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; margin-bottom: 15px;">
                        <h4>VILLA SIERRA DEL MAR</h4>
                        <p class="price">$750M COP</p>
                        <p class="location">Ubicación: Palomino Prime</p>
                        <a href="propiedad.php?id=2" class="btn btn-secondary">MÁS DETALLES</a>
                    </div>
                    
                    <div class="property-card">
                        <img src="img/propiedades/destacadas/cabaña-mar2.avif" alt="Terreno con acceso al río" style="width: 100%; height: 250px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; margin-bottom: 15px;">
                        <h4>LOTE CON ACCESO AL RÍO</h4>
                        <p class="price">$100M COP</p>
                        <p class="location">Ubicación: Palomino Prime</p>
                        <a href="propiedad.php?id=3" class="btn btn-secondary">MÁS DETALLES</a>
                    </div>
                    
                    <div class="property-card">
                        <img src="img/propiedades/destacadas/cabaña-mar3.avif" alt="Refugio en la selva" style="width: 100%; height: 250px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; margin-bottom: 15px;">
                        <h4>REFUGIO EN LA SELVA</h4>
                        <p class="price">$250M COP</p>
                        <p class="location">Ubicación: Palomino Prime</p>
                        <a href="propiedad.php?id=4" class="btn btn-secondary">MÁS DETALLES</a>
                    </div>

                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <h3>PALOMINO PRIME MAR Y RÍO:<br>MÁS QUE INMOBILIARIA</h3>
                <p>Nuestra agencia inmobiliaria se especializa en ofrecer propiedades únicas y exclusivas en el hermoso Palomino, donde la majestuosa Sierra Nevada se encuentra con el mar Caribe. Estamos comprometidos en ayudarte a encontrar tu hogar ideal en este entorno natural excepcional.</p>
                <div class="icons-grid">
                    <div class="icon-item">
                        <img src="https://via.placeholder.com/64" alt="Expertos Locales" width="64" height="64">
                        <h4>Expertos Locales</h4>
                    </div>
                    <div class="icon-item">
                        <img src="https://via.placeholder.com/64" alt="Propiedades Exclusivas" width="64" height="64">
                        <h4>Propiedades Exclusivas</h4>
                    </div>
                    <div class="icon-item">
                        <img src="https://via.placeholder.com/64" alt="Comprometidos con Palomino" width="64" height="64">
                        <h4>Comprometidos con Palomino</h4>
                    </div>
                    <div class="icon-item">
                        <img src="https://via.placeholder.com/64" alt="Servicio Personalizado" width="64" height="64">
                        <h4>Servicio Personalizado</h4>
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