<?php

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
$app->router->get("/login", [\app\controllers\AuthController::class, "login"]);
$app->router->post("/login", [\app\controllers\AuthController::class, "login"]);
$app->router->get("/register", [\app\controllers\AuthController::class, "register"]);
$app->router->post("/register", [\app\controllers\AuthController::class, "register"]);

$app->run();
