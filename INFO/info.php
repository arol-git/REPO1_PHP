<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <body>

        <div class="contenaire">

            <h1>Me contacter</h1>
        
            <form action="contact.php" method="GET" enctype="multipart/form-data" class="formulaire">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name"><br>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email"><br>

                <label for="message">Message</label>
                <textarea name="message" id="message" placeholder="Enter your message"></textarea><br>

                <label for="screenshot" class="form-label">Votre capture d'écran</label>
                <input type="file" class="form-control" id="screenshot" name="screenshot" />
                
                <input type="submit" value="Envoyer" name="submit">
            </form>

        </div>
        
    </body>
</html>