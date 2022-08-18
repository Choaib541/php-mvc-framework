<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>

<div class="d-flex flex-column justify-content-end position-fixed p-2 top-0"
     style="pointer-events: none;height: 100vh;z-index: 99999999;width: 450px;right: 0">

    <?php foreach ($_SESSION["flash_messages"] ?? [] as $flash_message): ?>

        <?php if ($flash_message["state"]): ?>
            <div class="alert shadow-lg alert-success"><?php echo $flash_message["message"] ?></div>
        <?php else: ?>
            <div class="alert shadow-lg alert-danger"><?php echo $flash_message["message"] ?></div>
        <?php endif; ?>

    <?php endforeach; ?>

    <?php unset($_SESSION["flash_messages"]); ?>

</div>

<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">

            <?php echo $SITE_NAME ?? ""; ?>

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (\app\core\Application::$app->gate->isGuest()): ?>>
                    <li class="nav-item">
                        <a class="nav-link active  text-white" aria-current="page" href="/login">
                            <button class=" btn btn-primary">
                                Login
                            </button>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/register">
                            <button class=" btn btn-primary">
                                Register
                            </button>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\app\core\Application::$app->gate->isAuth()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/logout">
                            <button class=" btn btn-primary">
                                Logout
                            </button>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
{{content}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>

<script>

    const alert = document.querySelector(".alert")
    if (alert) {
        setTimeout(() => {
            alert.remove()
        }, [3000])
    }

</script>

</body>
</html>
