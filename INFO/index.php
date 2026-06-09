<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AROL YEMELI | Développeur Web & Mobile - DATALAB-TECH</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php require_once(__DIR__ . '/header.php'); ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-container">
                <div class="hero-content" data-aos="fade-right">
                    <div class="hero-badge">✨ Bienvenue sur mon portfolio</div>
                    <h1 class="hero-title">
                        Arol <span class="gradient-text">YEMELI</span>
                    </h1>
                    <p class="hero-subtitle">
                        Développeur web et mobile junior, passionné par la création de solutions innovantes
                        et performantes. Formé à DATALAB-TECH.
                    </p>
                    <div class="hero-buttons">
                        <a href="#contact" class="btn-primary"> Me contacter</a>
                        <a href="#projects" class="btn-primary"> Voir mes projets</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">10+</span>
                            <span class="stat-label">Mois de formation</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">15+</span>
                            <span class="stat-label">Projets réalisés</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Passion</span>
                        </div>
                    </div>
                </div>
                <div class="hero-image" data-aos="fade-left">
                    <div class="hero-image-wrapper">
                        <img src="image1.png" alt="Arol YEMELI">
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">À propos</span>
                    <h2 class="section-title">Qui suis-<span class="gradient-text">je ?</span></h2>
                    <p class="section-subtitle">Découvrez mon parcours et ma passion pour la technologie</p>
                </div>
                <div class="about-grid">
                    <div class="about-card">
                        <div class="about-icon">🎓</div>
                        <h3>Ma formation</h3>
                        <p>Formation intensive de 6 mois à DATALAB-TECH, spécialisée en développement web et mobile.</p>
                    </div>
                    <div class="about-card">
                        <div class="about-icon">💡</div>
                        <h3>Ma vision</h3>
                        <p>Créer des solutions innovantes qui allient performance, esthétique et simplicité.</p>
                    </div>
                    <div class="about-card">
                        <div class="about-icon">🚀</div>
                        <h3>Mon objectif</h3>
                        <p>Contribuer au développement de projets ambitieux et continuer à apprendre chaque jour.</p>
                    </div>
                </div>
                <div class="about-content">
                    <div class="about-text">
                        <h3>Mon parcours</h3>
                        <p>Je m'appelle <strong class="gradient-text">Arol YEMELI</strong>, je suis développeur web et mobile junior. Passionné par la technologie depuis mon plus jeune âge, j'ai décidé de transformer cette passion en métier en rejoignant DATALAB-TECH.</p>
                        <p>Au cours de ma formation, j'ai acquis des compétences solides en développement frontend, backend et mobile. Je suis constamment à la recherche de nouveaux défis pour améliorer mes compétences.</p>
                        <div class="about-info">
                            <div class="info-item"><i class="fas fa-map-marker-alt"></i> Yaoundé, Cameroun</div>
                            <div class="info-item"><i class="fas fa-envelope"></i> arolyemeli@gmail.com</div>
                            <div class="info-item"><i class="fas fa-phone"></i> +237 671 990 780</div>
                        </div>
                    </div>
                    <div class="about-stats">
                        <div class="stat-circle">
                            <div class="circle-progress">
                                <svg viewBox="0 0 100 100">
                                    <defs>
                                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#00d4ff" />
                                            <stop offset="100%" stop-color="#7b2ff7" />
                                        </linearGradient>
                                    </defs>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="url(#gradient)" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="85"/>
                                </svg>
                                <div class="circle-label">
                                    <span class="circle-value">70%</span>
                                    <span class="circle-name">Web</span>
                                </div>
                            </div>
                        </div>
                        <div class="stat-circle">
                            <div class="circle-progress">
                                <svg viewBox="0 0 100 100">
                                    <defs>
                                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#00d4ff" />
                                            <stop offset="100%" stop-color="#7b2ff7" />
                                        </linearGradient>
                                    </defs>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="url(#gradient)" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="113"/>
                                </svg>
                                <div class="circle-label">
                                    <span class="circle-value">60%</span>
                                    <span class="circle-name">Mobile</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="skills fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Compétences</span>
                    <h2 class="section-title">Mes <span class="gradient-text">technologies</span></h2>
                    <p class="section-subtitle">Les outils et langages que je maîtrise</p>
                </div>
                <div class="skills-grid">
                    <div class="skill-card">
                        <div class="skill-header">
                            <i class="fas fa-code"></i>
                            <h3>Frontend</h3>
                        </div>
                        <div class="skill-items">
                            <div class="skill-item">
                                <div class="skill-info"><span>HTML5</span><span> 90%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 90%"></div></div>
                            </div>
                            <div class="skill-item">
                                <div class="skill-info"><span>CSS3</span><span> 85%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 85%"></div></div>
                            </div>
                            <div class="skill-item">
                                <div class="skill-info"><span>JavaScript</span><span> 75%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 75%"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="skill-card">
                        <div class="skill-header">
                            <i class="fas fa-server"></i>
                            <h3>Backend</h3>
                        </div>
                        <div class="skill-items">
                            <div class="skill-item">
                                <div class="skill-info"><span>PHP</span><span> 80%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 80%"></div></div>
                            </div>
                            <div class="skill-item">
                                <div class="skill-info"><span>Python</span><span> 70%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 70%"></div></div>
                            </div>
                            <div class="skill-item">
                                <div class="skill-info"><span>Java</span><span> 65%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 65%"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="skill-card">
                        <div class="skill-header">
                            <i class="fas fa-database"></i>
                            <h3>Base de données</h3>
                        </div>
                        <div class="skill-items">
                            <div class="skill-item">
                                <div class="skill-info"><span>MySQL</span><span> 85%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 85%"></div></div>
                            </div>
                            <div class="skill-item">
                                <div class="skill-info"><span>MongoDB</span><span> 60%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 60%"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="skill-card">
                        <div class="skill-header">
                            <i class="fas fa-mobile-alt"></i>
                            <h3>Mobile</h3>
                        </div>
                        <div class="skill-items">
                            <div class="skill-item">
                                <div class="skill-info"><span>Flutter</span><span> 70%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 70%"></div></div>
                            </div>
                            <div class="skill-item">
                                <div class="skill-info"><span>React Native</span><span> 65%</span></div>
                                <div class="skill-bar"><div class="skill-progress" style="width: 65%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="projects fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Réalisations</span>
                    <h2 class="section-title">Mes <span class="gradient-text">projets</span></h2>
                    <p class="section-subtitle">Découvrez quelques-unes de mes réalisations</p>
                </div>
                <div class="projects-grid">
                    <div class="project-card">
                        <div class="project-image">
                            <div class="project-icon"><i class="fas fa-store"></i></div>
                            <div class="project-overlay">
                                <a href="../LABTECH/index.php" class="project-link"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                        <div class="project-info">
                            <h3>E-commerce DATALAB-TECH</h3>
                            <p>Plateforme e-commerce complète avec gestion de panier, dashboard admin et système de paiement.</p>
                            <div class="project-tags">
                                <span>PHP</span><span>MySQL</span><span>JavaScript</span>
                            </div>
                        </div>
                    </div>
                    <div class="project-card">
                        <div class="project-image">
                            <div class="project-icon"><i class="fas fa-mobile-alt"></i></div>
                            <div class="project-overlay">
                                <a href="#" class="project-link"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                        <div class="project-info">
                            <h3>Application Mobile</h3>
                            <p>Application de gestion de tâches développée avec Flutter et Firebase.</p>
                            <div class="project-tags">
                                <span>Flutter</span><span>Firebase</span><span>Dart</span>
                            </div>
                        </div>
                    </div>
                    <div class="project-card">
                        <div class="project-image">
                            <div class="project-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="project-overlay">
                                <a href="#" class="project-link"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                        <div class="project-info">
                            <h3>Dashboard Analytics</h3>
                            <p>Dashboard interactif pour la visualisation de données avec React et Chart.js.</p>
                            <div class="project-tags">
                                <span>React</span><span>Chart.js</span><span>API</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="contact fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Contact</span>
                    <h2 class="section-title">Parlons de votre <span class="gradient-text">projet</span></h2>
                    <p class="section-subtitle">Une idée ? Un projet ? N'hésitez pas à me contacter</p>
                </div>
                <?php require_once(__DIR__ . '/info.php'); ?>
            </div>
        </section>
    </main>

    <?php require_once(__DIR__ . '/footer.php'); ?>

    <script>
        // Animation fade-up
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-up').forEach(el => {
            observer.observe(el);
        });

        // Menu hamburger
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');

        if (hamburger) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });
        }

        // Fermer le menu au clic sur un lien
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    </script>
</body>
</html>