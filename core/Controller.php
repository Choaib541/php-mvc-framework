<?php

namespace app\core;

class Controller
{

    public Response $response;
    public Database $db;

    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->db = Application::$app->database;
    }

    function view(string $views, string $layout = "main", array $params = []): string
    {
        return $this->response->view($views, $layout, $params);
    }

}