<?php

namespace app\core;

use app\controllers\Site_Controller;

class Router
{

    private array $requests = [];
    private Request $request;
    private Response $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(
        Request $request, Response $response
    )
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param string $url
     * @param $func
     * @return void
     */
    public function get(string $url, $func): void
    {
        $this->requests["get"][$url] = $func;
    }

    /**
     * @param string $url
     * @param $func
     * @return void
     */
    public function post(string $url, $func): void
    {
        $this->requests["post"][$url] = $func;
    }

    public function resolve(): void
    {

        $requests = $this->request->is_post() ? $this->requests["post"] : $this->requests["get"];
        $current_url = $this->request->get_url();

        $callback = $requests[$current_url] ?? [Site_Controller::class, "error_404"];

        if (is_array($callback)) {
            $callback[0] = new $callback[0]($this->response);
        }

        echo call_user_func($callback, $this->request);

    }


}