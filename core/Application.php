<?php

namespace app\core;

use app\models\User;
use JetBrains\PhpStorm\NoReturn;
use app\core\{Router, Database, Request, Response};

class Application
{

    public Router $router;
    public Database $database;
    public Request $request;
    public Gate $gate;
    public Response $response;
    public static Application $app;
    public static string $ROOT_DIR;
    public static array $info;
    public Session $session;
    public User|null $user = null;

    public function __construct(array $config)
    {
        $this->session = new Session();
        self::$info = $config["site"];
        self::$ROOT_DIR = $config["site"]["ROOT_DIR"];
        $this->database = new Database($config["database"]);
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        self::$app = $this;
        $this->gate = new Gate();
        if ($this->session->get("user")) {
            $this->user = User::find(["id", $this->session->get("user")["id"]]);
        }
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
