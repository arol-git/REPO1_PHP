<?php
// Initialisation des variables
$success = false;
$error = '';
$name = '';
$email = '';
$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    if (empty($name)) {
        $error = 'Veuillez entrer votre nom.';
    } elseif (empty($email)) {
        $error = 'Veuillez entrer votre email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez entrer un email valide.';
    } elseif (empty($message)) {
        $error = 'Veuillez entrer votre message.';
    } else {
        // Ici vous pouvez :
        // 1. Envoyer un email (décommentez la partie email)
        // 2. Sauvegarder dans un fichier
        // 3. Afficher simplement un message de succès
        
        // Option 1 : Sauvegarder dans un fichier texte
        $data = "[" . date('Y-m-d H:i:s') . "] " . $name . " | " . $email . " | " . str_replace(["\n", "\r"], ' ', $message) . "\n";
        file_put_contents('messages.txt', $data, FILE_APPEND | LOCK_EX);
        
        // Option 2 : Envoyer un email (décommentez et configurez)
        /*
        $to = 'votre@email.com';
        $subject = 'Nouveau message de ' . $name;
        $body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email";
        mail($to, $subject, $body, $headers);
        */
        
        $success = true;
        
        // Réinitialiser les champs
        $name = '';
        $email = '';
        $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - DATALAB-TECH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #0f0f1a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .contact-container {
            max-width: 600px;
            width: 100%;
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            border: 1px solid rgba(0, 212, 255, 0.3);
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .contact-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .contact-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.3);
        }

        .contact-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .contact-header p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: white;
            font-family: inherit;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .input-group textarea {
            padding-top: 0.9rem;
            resize: vertical;
            min-height: 120px;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: #00d4ff;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }

        .input-group input:focus + i,
        .input-group textarea:focus + i {
            color: #00d4ff;
        }

        button {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.4);
        }

        .error-message {
            background: rgba(255, 68, 68, 0.15);
            border: 1px solid rgba(255, 68, 68, 0.3);
            padding: 0.8rem;
            border-radius: 12px;
            color: #ff6666;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.85rem;
        }

        .success-message {
            background: rgba(0, 255, 136, 0.15);
            border: 1px solid rgba(0, 255, 136, 0.3);
            padding: 0.8rem;
            border-radius: 12px;
            color: #00ff88;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.85rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .contact-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.7rem;
        }

        @media (max-width: 480px) {
            .contact-container {
                padding: 1.5rem;
            }
            
            .contact-header h1 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <div class="contact-header">
            <div class="contact-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h1>Nous contacter</h1>
            <p>Envoyez-nous un message, nous vous répondrons rapidement</p>
        </div>

        <?php if ($success): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> 
                Message envoyé avec succès ! Nous vous répondrons sous 24h.
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> 
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nom complet</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Jean Dupont" required>
                </div>
            </div>

            <div class="form-group">
                <label>Adresse email</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="jean@exemple.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Message</label>
                <div class="input-group">
                    <i class="fas fa-comment"></i>
                    <textarea name="message" placeholder="Votre message..." required><?php echo htmlspecialchars($message); ?></textarea>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-paper-plane"></i> Envoyer le message
            </button>
        </form>

        <div class="contact-footer">
            <i class="fas fa-shield-alt"></i> Vos informations sont confidentielles
        </div>
    </div>
</body>
</html>