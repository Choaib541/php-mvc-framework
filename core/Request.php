<?php

namespace app\core;

class Request extends Validation
{

    private array $rules;

    public function __construct()
    {

        $this->rules = [
            "required" => [$this, "required"],
            "min" => [$this, "min"],
            "max" => [$this, "max"],
            "date" => [$this, "check_date"],
            "email" => [$this, "email"],
            "confirmed" => [$this, "confirmed"],
            "exists" => [$this, "exists"],
            "unique" => [$this, "unique"],
        ];

    }

    /**
     * @return string
     */
    public function get_request_method(): string
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * @return bool
     */
    public function is_post(): bool
    {
        return $this->get_request_method() === "post";
    }

    /**
     * @return bool
     */
    public function is_get(): bool
    {
        return $this->get_request_method() === "get";
    }


    /**
     * @return array
     */
    public function get_body(): array
    {
        $body = [];

        if ($this->is_post()) {

            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

        } elseif ($this->is_get()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    public function get_url(): string
    {

        $uri = $_SERVER["REQUEST_URI"];

        $qm_position = strpos("$uri", "?");

        if ($qm_position) {
            $current_url = substr($uri, 0, $qm_position);
        } else {
            $current_url = $uri;
        }

        return $current_url;
    }

    public function validate($request_rules): array
    {
        $result = [
            "state" => TRUE,
            "body" => []
        ];

        foreach ($request_rules as $rule_name => $request_name_rules) {

            foreach ($request_name_rules as $rule) {

                if (is_string($rule)) {

                    $rule_callback = $this->rules[$rule];

                    $res = call_user_func($rule_callback, $rule_name);

                    if (!$res["state"]) {
                        $result["state"] = $res["state"];
                        $result["body"]["error_$rule_name"] = $res["message"];
                        break;
                    }

                    continue;
                }

                $rule_callback = $this->rules[$rule[0]];

                $rule_parameters = $rule[1];

                $res = call_user_func($rule_callback, $rule_name, $rule_parameters);


                if (!$res["state"]) {
                    $result["body"]["error_$rule_name"] = $res["message"];
                    $result["state"] = $res["state"];
                    break;
                }

            }

        }

        if ($result["state"]) {
            $result["body"] = $this->get_body();
            if (isset($result["body"]["password_confirmation"])) {
                unset($result["body"]["password_confirmation"]);
            }
        }

        return $result;
    }


}