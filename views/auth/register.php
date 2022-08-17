<form method="post" class=" p-3 bg-white text-black rounded-1">
    <h1 class="fw-bold text-primary">REGISTER</h1>
    <div class="mb-3 row">
        <div class="col">
            <label for="name" class="form-label">Full Name</label>
            <input name="name" type="text" value="<?php echo $_POST['name'] ?? '' ?>" class="form-control <?php echo isset($error_name) ? 'is-invalid' : '' ?>" id="name">
            <!--is-invalid-->
            <div class="invalid-feedback">
                <?php echo $error_name ?? "" ?>
            </div>
        </div>
        <div class="col">
            <label for="birthday" class="form-label">Birthday Date</label>
            <input name="birthday" type="date" value="<?php echo $_POST['birthday'] ?? '' ?>" class="form-control <?php echo isset($error_birthday) ? 'is-invalid' : '' ?>" id="birthday">
            <div class="invalid-feedback">
                <?php echo $error_birthday ?? "" ?>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input name="email" autocomplete="off" type="email" value="<?php echo $_POST['email'] ?? '' ?>" class="form-control <?php echo isset($error_email) ? 'is-invalid' : '' ?>" id="email">
        <div class="invalid-feedback">
            <?php echo $error_email ?? "" ?>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" class="form-control <?php echo isset($error_password) ? 'is-invalid' : '' ?>" id="password">
            <div class="invalid-feedback">
                <?php echo $error_password ?? "" ?>
            </div>
        </div>
        <div class="col">
            <label for="password_confirmation" class="form-label">Password Confirmation</label>
            <input name="password_confirmation" type="password" class="form-control  <?php echo isset($error_password) ? 'is-invalid' : '' ?>" id="password_confirmation">
        </div>
    </div>

    <button type="submit" class="btn btn-primary mb-3">Register</button>

    <div class="form-text">You have an account ? <a href="/login">login</a></div>

</form>