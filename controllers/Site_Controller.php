<?php

namespace app\controllers;

use app\core\Controller;

class Site_Controller extends Controller
{

    public function error_404(): string
    {
        return "404";
    }


    public function error_403(): string
    {
        return "403";
    }

}