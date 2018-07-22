<?php
/**
 * A very basic example.
 */
header("Content-Type: text/plain; charset=utf-8");
require_once "../vendor/autoload.php";
use gchumillas\http\HttpController;

$c = new HttpController();

// This listener is called at first place, before any other listener.
$c->onOpen(
    function () {
        echo "Opening request.\n";
    }
);

// This listener processes 'GET' requests.
$c->on(
    "GET",
    function () {
        echo "Processing GET request.\n";
    }
);

// This listener processes 'POST' requests.
$c->on(
    "POST",
    function () {
        echo "Processing POST requests.\n";
    }
);

// Processes the current HTTP request.
$c->processRequest();
