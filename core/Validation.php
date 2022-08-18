<?php

namespace app\core;

use DateTime;

abstract class Validation
{

    abstract public function get_body(): array;

    public function required(string $field): array
    {
        $body = $this->get_body();

        if (isset($body[$field]) && $body[$field] !== "") {
            $result = ["state" => TRUE];
        } else {
            $result = ["state" => FALSE, "message" => "This field field is required"];
        }

        return $result;
    }

    public function min(string $field, array $value): array
    {
        $body = $this->get_body();

        $field_value = $body[$field];

        return strlen($field_value) > $value[0] ? [
            "state" => TRUE,
        ] : [
            "state" => FALSE,
            "message" => "This field must be longer than $value[0] characters"
        ];
    }


    public function max(string $field, array $value): array
    {
        $body = $this->get_body();

        $field_value = $body[$field];

        return strlen($field_value) < $value[0] ? [
            "state" => TRUE,
        ] : [
            "state" => FALSE,
            "message" => "This field must be less than $value[0] characters"
        ];
    }

    public function check_date(string $field): array
    {
        $body = $this->get_body();

        $date = $body[$field];

        $format = 'Y-m-d';

        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date ? [
            "state" => TRUE,
        ] : [
            "state" => FALSE,
            "message" => "This is not a valid date"
        ];
    }

    public function email(string $field): array
    {
        $body = $this->get_body();

        $email = $body[$field];

        $regx = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";


        return preg_match($regx, $email) ? [
            "state" => TRUE,
        ] : [
            "state" => FALSE,
            "message" => "This is not a valid email"
        ];
    }

    public function confirmed(string $field): array
    {
        $body = $this->get_body();

        $password = $body[$field];

        $password_confirmation = $body["password_confirmation"] ?? false;

        if (!$password) {
            return [
                "state" => FALSE,
                "message" => "Password_confirmation is required"
            ];
        }

        return $password === $password_confirmation ? [
            "state" => TRUE
        ] : [
            "state" => FALSE,
            "message" => "the password and password_confirmation does not match"
        ];
    }

    public function exists(string $name, array $data): array
    {

        $table = $data[0];
        $column = $data[1];
        $result = Application::$app->database->select($table, ["id"])->where($column, $this->get_body()[$name])->get();

        return !empty($result) ? [
            "state" => TRUE
        ] : [
            "state" => FALSE,
            "message" => "the $column that you entered does not exist"
        ];
    }

    public function unique(string $name, array $data): array
    {

        $table = $data[0];
        $column = $data[1];
        $result = Application::$app->database->select($table, ["id"])->where($column, $this->get_body()[$name])->get();

        return empty($result) ? [
            "state" => TRUE
        ] : [
            "state" => FALSE,
            "message" => "This $column has already been taken"
        ];
    }

}