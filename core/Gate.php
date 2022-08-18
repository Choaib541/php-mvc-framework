<?php

namespace app\core;

class Gate
{

    public function isAuth(): bool
    {
        return (bool)Application::$app->user;
    }


    public function isGuest(): bool
    {
        return !(bool)Application::$app->user;
    }

}