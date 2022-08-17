<?php

namespace app\core;

class Response
{

    public function view(string $view, string $layout = "main", array $params = []): string
    {

        $actual_layout = $this->render_layout($layout);
        $actual_view = $this->render_view($view, $params);

        return str_replace("{{content}}", $actual_view, $actual_layout);

    }

    private function render_view(string $view, array $params): string
    {

        ob_start();
        extract(Application::$info);
        extract($params);
        require_once Application::$ROOT_DIR . "/views/$view.php";
        $view = ob_get_contents();
        ob_end_clean();
        return $view;

    }

    private function render_layout(string $layout): string
    {

        ob_start();
        extract(Application::$info);
        require_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        $layout = ob_get_contents();
        ob_end_clean();
        return $layout;

    }

}