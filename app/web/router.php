<?php

namespace App\Web;

class Router
{
    public function get($path)
    {
        switch ($path) {
            case '/':
                return 'Main Page';
                break;
            case "/hello":
                return 'Hello Page';
                break;
            default:
                $this->error();
        }
    }

    public function post()
    {
        return json_encode(["success" => true]);
    }

    public function error()
    {
        return header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
    }


    public function dispatch()
    {
        $path = parse_url($_SERVER['REQUEST_URI'])['path'];
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                echo $this->get($path);
                break;
            case 'POST':
                echo $this->post();
                break;
            default:
                $this->get('/');
        }

    }
}
