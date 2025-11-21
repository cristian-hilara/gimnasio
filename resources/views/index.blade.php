@push('css')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone - Tu Gimnasio de Confianza</title>
    
    <link href="{{ asset('css/styles.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <i class="fas fa-dumbbell"></i>
                    <span>FitZone</span>
                </div>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#servicios">Servicios</a></li>
                    <li><a href="#planes">Planes</a></li>
                    <li><a href="#horarios">Horarios</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="/login" class="btn-login">Iniciar Sesión</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Transforma Tu Cuerpo, <span>Transforma Tu Vida</span></h1>
                <p class="hero-subtitle">Entrena con los mejores instructores y equipamiento de última generación</p>
                <div class="hero-buttons">
                    <a href="#planes" class="btn btn-primary">Ver Planes</a>
                    <a href="#contacto" class="btn btn-secondary">Contáctanos</a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-users"></i>
                        <h3>500+</h3>
                        <p>Miembros Activos</p>
                    </div>
                    <div class="stat">
                        <i class="fas fa-trophy"></i>
                        <h3>10+</h3>
                        <p>Años de Experiencia</p>
                    </div>
                    <div class="stat">
                        <i class="fas fa-star"></i>
                        <h3>4.9</h3>
                        <p>Calificación</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="servicios">
        <div class="container">
            <div class="section-header">
                <h2>Nuestros Servicios</h2>
                <p>Todo lo que necesitas para alcanzar tus objetivos</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h3>Musculación</h3>
                    <p>Equipamiento de última generación para desarrollar músculo y fuerza</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-running"></i>
                    </div>
                    <h3>Cardio</h3>
                    <p>Zona de cardio equipada con máquinas modernas para quemar calorías</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>Clases Grupales</h3>
                    <p>Yoga, Spinning, Zumba, CrossFit y más actividades dirigidas</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Entrenadores Personales</h3>
                    <p>Profesionales certificados para ayudarte a alcanzar tus metas</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-apple-alt"></i>
                    </div>
                    <h3>Nutrición</h3>
                    <p>Asesoramiento nutricional personalizado para complementar tu entrenamiento</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-spa"></i>
                    </div>
                    <h3>Spa & Sauna</h3>
                    <p>Relájate después de tu entrenamiento en nuestras instalaciones</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="planes">
        <div class="container">
            <div class="section-header">
                <h2>Planes y Membresías</h2>
                <p>Elige el plan que mejor se adapte a tus necesidades</p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Básico</h3>
                        <div class="price">
                            <span class="currency">Bs</span>
                            <span class="amount">150</span>
                            <span class="period">/mes</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Acceso al gimnasio</li>
                        <li><i class="fas fa-check"></i> Zona de cardio</li>
                        <li><i class="fas fa-check"></i> Zona de pesas</li>
                        <li><i class="fas fa-times"></i> Clases grupales</li>
                        <li><i class="fas fa-times"></i> Entrenador personal</li>
                    </ul>
                    <a href="#contacto" class="btn btn-outline">Elegir Plan</a>
                </div>

                <div class="pricing-card featured">
                    <div class="badge">Popular</div>
                    <div class="pricing-header">
                        <h3>Premium</h3>
                        <div class="price">
                            <span class="currency">Bs</span>
                            <span class="amount">250</span>
                            <span class="period">/mes</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Todo lo del plan Básico</li>
                        <li><i class="fas fa-check"></i> Clases grupales ilimitadas</li>
                        <li><i class="fas fa-check"></i> Acceso a Spa y Sauna</li>
                        <li><i class="fas fa-check"></i> 2 sesiones con entrenador</li>
                        <li><i class="fas fa-times"></i> Asesoría nutricional</li>
                    </ul>
                    <a href="#contacto" class="btn btn-primary">Elegir Plan</a>
                </div>

                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Elite</h3>
                        <div class="price">
                            <span class="currency">Bs</span>
                            <span class="amount">400</span>
                            <span class="period">/mes</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Todo lo del plan Premium</li>
                        <li><i class="fas fa-check"></i> Entrenador personal ilimitado</li>
                        <li><i class="fas fa-check"></i> Asesoría nutricional</li>
                        <li><i class="fas fa-check"></i> Plan de entrenamiento personalizado</li>
                        <li><i class="fas fa-check"></i> Acceso 24/7</li>
                    </ul>
                    <a href="#contacto" class="btn btn-outline">Elegir Plan</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Schedule Section -->
    <section class="schedule" id="horarios">
        <div class="container">
            <div class="section-header">
                <h2>Horarios de Clases</h2>
                <p>Encuentra la clase perfecta para ti</p>
            </div>
            <div class="schedule-table">
                <table>
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                            <th>Sábado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="time">07:00</td>
                            <td class="class yoga">Yoga</td>
                            <td class="class cardio">Cardio</td>
                            <td class="class yoga">Yoga</td>
                            <td class="class cardio">Cardio</td>
                            <td class="class yoga">Yoga</td>
                            <td class="class spinning">Spinning</td>
                        </tr>
                        <tr>
                            <td class="time">09:00</td>
                            <td class="class spinning">Spinning</td>
                            <td class="class crossfit">CrossFit</td>
                            <td class="class spinning">Spinning</td>
                            <td class="class crossfit">CrossFit</td>
                            <td class="class spinning">Spinning</td>
                            <td class="class zumba">Zumba</td>
                        </tr>
                        <tr>
                            <td class="time">18:00</td>
                            <td class="class zumba">Zumba</td>
                            <td class="class yoga">Yoga</td>
                            <td class="class zumba">Zumba</td>
                            <td class="class yoga">Yoga</td>
                            <td class="class crossfit">CrossFit</td>
                            <td class="class cardio">Cardio</td>
                        </tr>
                        <tr>
                            <td class="time">19:30</td>
                            <td class="class crossfit">CrossFit</td>
                            <td class="class spinning">Spinning</td>
                            <td class="class crossfit">CrossFit</td>
                            <td class="class spinning">Spinning</td>
                            <td class="class zumba">Zumba</td>
                            <td class="empty">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contacto">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h2>Contáctanos</h2>
                    <p>¿Tienes preguntas? Estamos aquí para ayudarte</p>
                    
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Dirección</h4>
                            <p>Av. Principal #123, La Paz, Bolivia</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Teléfono</h4>
                            <p>+591 7123-4567</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>info@fitzone.bo</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Horario</h4>
                            <p>Lun - Vie: 6:00 AM - 10:00 PM<br>Sáb - Dom: 8:00 AM - 8:00 PM</p>
                        </div>
                    </div>

                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" placeholder="Nombre completo" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" placeholder="Teléfono" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Mensaje" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="logo">
                        <i class="fas fa-dumbbell"></i>
                        <span>FitZone</span>
                    </div>
                    <p>Tu mejor versión te espera. Únete a la familia FitZone y alcanza tus objetivos.</p>
                </div>
                <div class="footer-section">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#planes">Planes</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Servicios</h4>
                    <ul>
                        <li><a href="#">Musculación</a></li>
                        <li><a href="#">Clases Grupales</a></li>
                        <li><a href="#">Entrenamiento Personal</a></li>
                        <li><a href="#">Nutrición</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Newsletter</h4>
                    <p>Suscríbete para recibir noticias y promociones</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Tu email">
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 FitZone. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    
    <script src="scripts.js"></script>
</body>
</html>

@push('js')

<script src="{{asset('js/pagina.js')}}"></script>
    
@endpush