<div class="mb-3">
    <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
    <input type="email" class="form-control" id="email" name="email" required>
</div>
<div class="mb-3">
    <label for="password" class="form-label"><span class="lang-password"></span> <span style="color: red;">*</span></label>
    <input type="password" class="form-control" id="password" name="password" required>
    <div id="viewPassword" class="form-text">

        <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('password')">
        <label class="form-label lang-viewPassword"></label>

    </div>

    <a href="<?= ADDRESS_SITE ?>profil/resetPassword" class="lang-forgetPassword"></a>

</div>

<div id="error"></div>

<button type="submit" class="btn btn-primary lang-sendLogin" onclick="connexion($('#email').val(), $('#password').val())"></button>