<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Session;
use app\models\User;

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

                $user = User::find(["email", $validating_results["body"]["email"]]);

                if ($user->password == $validating_results["body"]["password"]) {
                    Application::$app->session->create([
                        "user" => [
                            "id" => $user->id,
                        ]
                    ]);
                    $this->response->redirect("/", [
                        "state" => TRUE,
                        "message" => "Logged in successfully"
                    ]);
                } else {
                    return $this->view("auth/login", "auth", params: ["error_password" => "Password is not correct"]);
                }


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
                $user = User::create($validating_results["body"]);

                Application::$app->session->create([
                    "user" => json_encode([
                        "id" => $user->id,
                    ])
                ]);

                $this->response->redirect("/", [
                    "state" => TRUE,
                    "message" => "Logged in successfully"
                ]);
            } else {
                return $this->view("auth/register", "auth", params: $validating_results["body"]);
            }
        }

        return $this->view("auth/register", "auth");
    }

    public function logout()
    {
        unset($_SESSION["user"]);
        $this->response->redirect("/", [
            "state" => TRUE,
            "message" => "Logged out successfully"
        ]);
    }

}
