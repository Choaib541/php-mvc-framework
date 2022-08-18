<?php

namespace app\core;

class Session
{

    public function __construct()
    {
        session_start();
    }

    public function create(array $data): void
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }


    public function get(string $key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function check_flash_messages(): void
    {
        if (!isset($_SESSION["flash_messages"])) {
            $this->create(["flash_messages" => []]);
        }
    }

    public function create_flash_message(bool $state, string $message): void
    {
        $this->check_flash_messages();
        $_SESSION["flash_messages"][] = [
            "state" => $state,
            "message" => $message
        ];
    }


}