<!DOCTYPE html> 
<html lang="fr">

        <meta chaset="UTF-8">

    <title>PORTFOLIO AROL</title>
    <link rel="Stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <head>


    </head>
    <body>
        <header>
            
            <?php require_once(__DIR__ . '/header.php'); ?>

            <div id="acceuil">
            <div id="texteAccueil">
                <h1>Bienvenue sur mon portfolio</h1>
                <h3>Developpeur web et mobile</h3>
            </div>
            <div id="log">
                <img id="logo" src="image1.png" alt="logo datalab-tech">
            </div>
            </div>
        </header>

        <section>
            <div id="presentation">
                <h2>A propos de moi</h2>
                <p>Je m'appelle Arol , je suis developpeur web et mobile junior. J'ai suivi une formation intensive de 6 mois au sein de la DATALAB-TECH afin d'acquerir les competences necessaires pour integrer le monde professionnel. Je suis passionné par la technologie et la programmation depuis mon plus jeune age, ce qui m'a naturellement conduit à choisir cette voie professionnelle. Mon objectif est de contribuer au développement de solutions innovantes et efficaces, tout en continuant à apprendre et à évoluer dans ce domaine en constante évolution.</p>
            </div>
            <div id="competences">
                <h2>Mes competences</h2>
                <ul>
                    <li>HTML5 & CSS3😎</li>
                    <li>JavaScript</li>
                    <li>Python</li>
                    <li>PHP</li>
                    <li>JavaScript</li>
                    <li>c</li>
                    <li>Java</li>
                </ul>
            </div>
        </section>
        

        <?php require_once(__DIR__ . '/info.php'); ?>
        
        
        <?php require_once(__DIR__ . '/footer.php'); ?>

    </body>

</html>