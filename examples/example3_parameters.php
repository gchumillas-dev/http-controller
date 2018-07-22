<?php
/**
 * This example shows the use of the 'getParam' method.
 */
header("Content-Type: text/plain; charset=utf-8");
require_once "../vendor/autoload.php";
use gchumillas\http\HttpController;

class MyController extends HttpController
{
    private $_firstName = "";
    private $_lastName = "";
    private $_status = "";
    private $_bio = "";

    /**
     * Creates an instance.
     */
    public function __construct()
    {
        $this->on("GET", [$this, "get"]);
    }

    /**
     * The document to be printed.
     *
     * @return string
     */
    public function getDocument()
    {
        $fullname = implode(
            " ",
            array_filter([$this->_firstName, $this->_lastName])
        );

        return "Hello $fullname! Your status is '{$this->_status}' " .
        "and this your bio:\n{$this->_bio}\n";
    }

    /**
     * This listener processes 'GET' requests.
     *
     * @return void
     */
    public function get()
    {
        // The parameter 'firstname' is required and the system will throw
        // an InvalidArgumentException when it is missing.
        $this->_firstName = $this->getParam("firstname", ["required" => true]);

        // The parameter 'lastname' is optional (default is NULL)
        $this->_lastName = $this->getParam("lastname");

        // The parameter 'status' is optional (defaul is 'single')
        $this->_status = $this->getParam("status", ["default" => "single"]);

        // By default, the method removes spaces around the string. But You
        // can change this default behaviour.
        $this->_bio = $this->getParam("bio", ["trim" => false]);
    }
}

// Processes the current HTTP request and prints a document.
$c = new MyController();
$c->processRequest();
echo $c->getDocument();
