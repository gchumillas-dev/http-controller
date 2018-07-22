<?php
namespace gchumillas\http;
use \Exception;
use \InvalidArgumentException;

/**
 * Processes HTTP requests and performs actions according
 * to the request method.
 *
 * Example:
 *
 *     $c = new HttpController();
 *     $c->onOpen(function () {
 *         echo "This listener is called in first place\n";
 *     });
 *     $c->on("GET", function () {
 *         echo "This method processes GET requests\n";
 *     });
 *     $c->on("POST", function () {
 *         echo "This method processes POST requests\n";
 *     });
 *     $c->processRequest();
 *
 */
class HttpController
{
    /**
     * List of 'OPEN' listeners.
     *
     * @var callback[]
     */
    private $_openListeners = [];

    /**
    * List of listeners.
    *
    * @var {method: string[], callback[]}[]
    */
    private $_listeners = [];

    /**
    * Gets a parameter.
    *
    * Examples:
    *
    *    // Gets a cookie with a default value
    *    $page = $this->get("page", ["default" => "0"]);
    *
    *    // The parameter `username` is required.
    *    $username = $this->get("username", ["required" => "true"]);
    *
    *    // Do not 'trim' the parameter (defualt is 'true')
    *    $title = $this->get("title", ["trim" => false]);
    *
    * @param string $name    Parameter name
    * @param array  $options Options (not required)
    *
    * @return mixed
    */
    public function getParam($name, $options = [])
    {
        $default = isset($options["default"]) ? $options["default"] : null;
        $required = isset($options["required"]) ? $options["required"] : false;
        $trim = isset($options["trim"]) ? $options["trim"] : true;
        $param = isset($_REQUEST[$name]) ? $_REQUEST[$name]: $default;

        if ($trim) {
            $param = trim($param);
        }

        if ($required && strlen($param) == 0) {
            throw new InvalidArgumentException(
                "The parameter `$name` is required"
            );
        }

        return $param;
    }

    /**
    * Adds a request listener.
    *
    * Example:
    *
    *    $c = new HttpController();
    *    $c->on("PUT", function () {
    *        echo "Processing PUT request\n";
    *    });
    *    $c->processRequest();
    *
    * @param string   $method   Method name (GET, POST, PUT, etc...)
    * @param callable $listener Request listener
    *
    * @return void
    */
    public function on($method, $listener)
    {
        if (!array_key_exists($method, $this->_listeners)) {
            $this->_listeners[$method] = [];
        }

        array_push($this->_listeners[$method], $listener);
    }

    /**
    * Adds an 'OPEN' request listener.
    *
    * 'OPEN' request listeners are called at first place, before any other
    * request listeners.
    *
    * @param callable $listener Request listener
    *
    * @return void
    */
    public function onOpen($listener)
    {
        array_push($this->_openListeners, $listener);
    }

    /**
    * Processes the request.
    *
    * @return void
    */
    public function processRequest()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $listeners = $this->_openListeners;
        if (array_key_exists($requestMethod, $this->_listeners)) {
            $listeners = array_merge(
                $listeners,
                $this->_listeners[$requestMethod]
            );
        }

        try {
            foreach ($listeners as $listener) {
                call_user_func($listener);
            }
        } catch (Exception $e) {
            $message = substr(
                preg_replace('/\s+/', ' ', $e->getMessage()), 0, 150
            );
            header("HTTP/1.0 400 $message");
            throw $e;
        }
    }
}
