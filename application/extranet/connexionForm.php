<form action="<?= PATH_FORM ?>connexion.php" method="post">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
        <div id="viewPassword" class="form-text">

            <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('password')">
            <label class="form-label">Show password</label>

        </div>

    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>