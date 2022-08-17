<?php

namespace app\core;

class Middleware
{

    public function auth(): bool
    {
        return TRUE;
    }

}