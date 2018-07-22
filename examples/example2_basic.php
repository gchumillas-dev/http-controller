<?php
/**
 * A very basic example.
 *
 * In this case we a class that extends HttpController.
 */
header("Content-Type: text/plain; charset=utf-8");
require_once "../vendor/autoload.php";
use gchumillas\http\HttpController;

class MyController extends HttpController
{
    /**
     * Creates an instance.
     */
    public function __construct()
    {
        // Adds some 'request listeners'
        $this->onOpen([$this, "open"]);
        $this->on("GET", [$this, "get"]);
        $this->on("POST", [$this, "post"]);
    }

    /**
     * This listener is called at first place, before any other listener.
     *
     * @return void
     */
    public function open()
    {
        echo "Opening request.\n";
    }

    /**
     * This listener processes 'GET' requests.
     *
     * @return void
     */
    public function get()
    {
        echo "Processing GET request.\n";
    }

    /**
     * This listener processes 'POST' requests.
     *
     * @return void
     */
    public function post()
    {
        echo "Processing POST requests.\n";
    }
}

// Processes the current HTTP request.
$c = new MyController();
$c->processRequest();
