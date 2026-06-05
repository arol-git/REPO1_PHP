<div class="contact-grid">
    <div class="contact-info">
        <div class="contact-card">
            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h4>Localisation</h4>
            <p>Yaoundé, Cameroun</p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
            <h4>Email</h4>
            <p>arolyemeli@gmail.com</p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i class="fas fa-phone"></i></div>
            <h4>Téléphone</h4>
            <p>+237 671 990 780</p>
        </div>
    </div>
    <div class="contact-form">
        <form action="contact.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" name="name" id="name" placeholder="Votre nom" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" placeholder="votre@email.com" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" placeholder="Votre message..." required></textarea>
            </div>
            <div class="form-group">
                <label for="screenshot">Joindre un fichier (optionnel)</label>
                <input type="file" name="screenshot" id="screenshot" accept="image/*">
            </div>
            <button type="submit" name="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Envoyer le message
            </button>
        </form>
    </div>
</div>