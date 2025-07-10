<?php

namespace core;

class View
{

    protected string $layout;
    protected string $content;

    public function __construct(
        protected array $data,
        protected string $view,
    )
    {
        $this->render();
    }

    public function render()
    {
        if (is_array($this->data)) {
            extract($this->data);
        }

        if (isAjax()) {
            exit(json_encode($this->data));
        } else {
            ob_start();
            require_once $this->view;
            $this->content = ob_get_clean();
            require_once VIEWS . "/layouts/index.php";
        }
    }

    public function getPart($file, $data = null)
    {
        if (is_array($data)) {
            extract($data);
        }

        $file = VIEWS . "/{$file}.php";
        if (is_file($file)) {
            require $file;
        } else {
            echo "Файл {$file} не найден";
        }

    }

}