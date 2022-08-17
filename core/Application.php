<?php

namespace app\core;

use JetBrains\PhpStorm\NoReturn;
use app\core\{Router, Database, Request, Response};

class Application
{

    public Router $router;
    public Database $database;
    public Request $request;
    public Response $response;
    public static Application $app;
    public static string $ROOT_DIR;
    public static array $info;
    private Middleware $middleware;

    public function __construct(array $config)
    {
        self::$info = $config["site"];
        self::$ROOT_DIR = $config["site"]["ROOT_DIR"];
        $this->middleware = new Middleware();
        $this->database = new Database($config["database"]);
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        self::$app = $this;
    }

    public function migrate(): void
    {
        $this->database->migrate();
    }


    public function run(): void
    {
        $this->router->resolve();
    }

    public function handle_error(string $message, string|bool $sql = FALSE): never
    {

        $params = ["message" => $message];

        if ($sql) {
            $params["sql"] = $sql;
        }

        $body = $this->response->view("errors/display_errors", layout: "auth", params: $params);

        echo $body;

        exit;
    }

}
