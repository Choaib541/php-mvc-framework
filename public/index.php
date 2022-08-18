<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="background: #1f1f1c;color:#fff">

<?php

//session_start();
//
//unset($_SESSION["user"]);
//
//exit;

use app\core\Application;

define("ROOT_DIR", dirname(__DIR__));

require_once ROOT_DIR . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$config = [
    "database" => [
        "connection" => $_ENV["DB_CONNECTION"],
        "database" => $_ENV["DB_DATABASE"],
        "host" => $_ENV["DB_HOST"],
        "port" => $_ENV["DB_PORT"],
        "username" => $_ENV["DB_USERNAME"],
        "password" => $_ENV["DB_PASSWORD"],
    ],
    "site" => [
        "SITE_NAME" => "Camado",
        "ROOT_DIR" => dirname(__DIR__)
    ],
];


$app = new Application($config);

$app->router->get("/", [\app\controllers\Controller::class, "index"]);

if ($app->gate->isGuest()) {
    $app->router->get("/login", [\app\controllers\AuthController::class, "login"]);
    $app->router->post("/login", [\app\controllers\AuthController::class, "login"]);
    $app->router->get("/register", [\app\controllers\AuthController::class, "register"]);
    $app->router->post("/register", [\app\controllers\AuthController::class, "register"]);
} else {
    $app->router->get("/logout", [\app\controllers\AuthController::class, "logout"]);
}


$app->run();

?>


</body>
</html>

