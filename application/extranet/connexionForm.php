<div class="mb-3">
    <label for="email" class="form-label">Email *</label>
    <input type="email" class="form-control" id="email" name="email" required>
</div>
<div class="mb-3">
    <label for="password" class="form-label">Mot de passe *</label>
    <input type="password" class="form-control" id="password" name="password" required>
    <div id="viewPassword" class="form-text">

        <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('password')">
        <label class="form-label">Voir le mot de passe</label>

    </div>

    <a href="<?= ADDRESS_SITE ?>resetPassword">Mot de passe oublié ?</a>

</div>

<div id="error"></div>

<button type="submit" class="btn btn-primary" onclick="connexion($('#email').val(), $('#password').val())">Se connecter</button>