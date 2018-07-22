# HttpController

This library contains the `HttpController` class, which processes HTTP requests. It's especially suitable for the development of REST applications.

## Installation

Use the following command to install the library:
```bash
composer require gchumillas/http-controller
```

Then load the `HttpController` class from the script:
```php
require_once "vendor/autoload.php";
use gchumillas\http\HttpController;

$c = new HttpController();
$c->on("GET", function () {
  echo "Processing GET requests...\n";
});
$c->processRequest();
```

## Basic example

For more examples, see the [examples](/examples) folder.

```php
header("Content-Type: text/plain; charset=utf-8");
require_once "vendor/autoload.php";
use gchumillas\http\HttpController;

class MyController extends HttpController
{
    private $_user;

    public function __construct()
    {
        // Adds an 'event listener' to 'GET' requests.
        $this->onGet([$this, "get"]);
    }

    // This is the document to be printed.
    public function getDocument()
    {
        return "Welcome {$this->_user}!";
    }

    // This listener processes 'GET' requests.
    public function get()
    {
        $this->_user = $this->getParam("user");
    }
}

// Processes the HTTP request and prints a document.
$c = new MyController();
$c->processRequest();
echo $c->getDocument();
```
