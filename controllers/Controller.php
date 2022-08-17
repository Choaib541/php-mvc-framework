<?php

namespace app\controllers;

class Controller extends \app\core\Controller
{

    public function index(): string
    {
        return $this->view("index", params: ["state" => "true"]);
    }


}