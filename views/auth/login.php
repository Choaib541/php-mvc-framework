<form method="post" class=" p-3 bg-white text-black rounded-1" style="width: clamp(200px, 100% , 650px)">
    <h1 class="fw-bold text-primary">LOGIN</h1>

    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control <?php echo isset($error_email) ? 'is-invalid' : '' ?>" value="<?php echo $_POST['email'] ?? '' ?>" id="email" name="email" aria-describedby="emailHelp">
        <div class="invalid-feedback">
            <?php echo $error_email ?? "" ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control <?php echo isset($error_password) ? 'is-invalid' : '' ?>" name="password" value="<?php echo $_POST['password'] ?? '' ?>" id="password">
        <div class="invalid-feedback">
            <?php echo $error_password ?? "" ?>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mb-3">Log In</button>
    <div class="form-text">You don't have an account ? <a href="/register">register</a></div>
</form>