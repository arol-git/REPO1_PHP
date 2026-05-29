<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <a href="Index.php">
                    <span class="logo-icon">⚡</span>
                    <span class="logo-text">DATALAB-TECH</span>
                </a>
            </div>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="Index.php" class="active">Accueil</a></li>
                <li><a href="#about">À propos</a></li>
                <li><a href="#skills">Compétences</a></li>
                <li><a href="#projects">Projets</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="nav-actions">
                <button class="theme-toggle" id="theme-toggle" title="Changer de thème">
                    <span class="theme-icon">🌙</span>
                </button>
            </div>
        </div>
    </nav>
</header>

<script>
    // Gestion du thème
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    const savedTheme = localStorage.getItem('theme') || 'dark';
    
    htmlElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon();
    
    themeToggle.addEventListener('click', () => {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon();
    });
    
    function updateThemeIcon() {
        const theme = htmlElement.getAttribute('data-theme');
        themeToggle.textContent = theme === 'dark' ? '☀️' : '🌙';
    }
    
    // Gestion du menu hamburger
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navLinks.classList.toggle('active');
    });
    
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navLinks.classList.remove('active');
        });
    });
    
    // Gestion de l'active sur les liens
    const sections = document.querySelectorAll('section, .hero');
    const navItems = document.querySelectorAll('.nav-links a');
    
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollY >= sectionTop - 200) {
                current = section.getAttribute('id') || 'home';
            }
        });
        
        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === `#${current}` || (current === 'home' && item.getAttribute('href') === 'Index.php')) {
                item.classList.add('active');
            }
        });
    });
</script>