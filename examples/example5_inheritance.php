<?php
/**
 * Sometimes errors happens.
 *
 * This example shows how to handle errors.
 */
header("Content-Type: text/plain; charset=utf-8");
require_once "../vendor/autoload.php";
use gchumillas\http\HttpController;

/**
 * BaseController provides a database connection.
 */
class BaseController extends HttpController
{
    protected $conn;

    /**
     * Creates an instance.
     */
    public function __construct()
    {
        $this->onOpen(
            function () {
                $this->conn = new mysqli(
                    "localhost", "root", "chum11145", "test"
                );

                if ($this->conn->connect_errno > 0) {
                    throw new Exception($this->conn->connect_error);
                }
            }
        );
    }
}

class MyController extends BaseController
{
    private $_rows = [];

    /**
     * Creates an instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->onOpen([$this, "open"]);
    }

    /**
     * The document to be printed.
     *
     * @return string
     */
    public function getDocument()
    {
        return json_encode($this->_rows);
    }

    /**
     * Processes OPEN requests.
     *
     * @return void
     */
    public function open()
    {
        $result = $this->conn->query("select id, title from item");
        while ($row = $result->fetch_assoc()) {
            array_push(
                $this->_rows,
                ["id" => $row["id"], "title" => $row["title"]]
            );
        }
        $result->close();
    }
}

// Processes the current HTTP requests and prints a JSON document.
$c = new MyController();
$c->processRequest();
echo $c->getDocument();
