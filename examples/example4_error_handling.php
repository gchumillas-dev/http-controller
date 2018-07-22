<?php
/**
 * Sometimes errors happens.
 *
 * This example shows how to handle errors.
 */
header("Content-Type: text/plain; charset=utf-8");
require_once "../vendor/autoload.php";
use gchumillas\http\HttpController;

class MyController extends HttpController
{
    private $_username;

    /**
     * Creates an instance.
     */
    public function __construct()
    {
        $this->onOpen([$this, "open"]);
    }

    /**
     * The document to be printed.
     *
     * @return string
     */
    public function getDocument()
    {
        return "Welcome {$this->_username}. You are in!";
    }

    /**
     * Processes OPEN requests.
     *
     * @return void
     */
    public function open()
    {
        // Both, username and password, are required. And the system throws
        // an InvalidArgumentException if any of them is missing.
        $this->_username = $this->getParam("username", ["required" => true]);
        $password = $this->getParam("password", ["required" => true]);

        if ($this->_username != "lorem" || $password != "ipsum") {
            throw new Exception("Invalid credentials");
        }
    }

    /**
     * Overrides the processRequest method to show a custom error message.
     *
     * @return void
     */
    public function processRequest()
    {
        try {
            parent::processRequest();
        } catch (Exception $e) {
            echo "Error: {$e->getMessage()}";

            // It's important to re-throw the exception, so the system can
            // take timely measures.
            throw $e;
        }
    }
}

// Disables 'display_errors' to prevent
// the user from seeing confusing messages.
ini_set("display_errors", false);

// Processes the current HTTP request and prints a document.
$c = new MyController();
$c->processRequest();
echo $c->getDocument();
