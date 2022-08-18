<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class AuthController extends Controller
{

    public function login(Request $request): string
    {
        if ($request->is_post()) {

            $validating_results = $request->validate([
                "email" => ["required", "email", ["exists", ["users", "email"]]],
                "password" => ["required", ["min", [8]]],
            ]);

            if ($validating_results["state"]) {
            } else {
                return $this->view("auth/login", "auth", params: $validating_results["body"]);
            }

        }

        return $this->view("auth/login", "auth");
    }

    public function register(Request $request): string
    {

        if ($request->is_post()) {

            $validating_results = $request->validate([
                "name" => ["required", ["min", [3]], ["max", [22]]],
                "birthday" => ["required", "date"],
                "email" => ["required", "email", ["unique", ["users", 'email']]],
                "password" => ["required", "confirmed"],
            ]);


            if ($validating_results["state"]) {
            } else {
                return $this->view("auth/register", "auth", params: $validating_results["body"]);
            }
        }

        return $this->view("auth/register", "auth");
    }
}
