<?php 
class Router {
    protected $controller = 'POSController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {

        // 1. Retrieve the link and split it
        $url = $this->parseUrl();

        // 2. Searching for the "controller"
        if (isset($url[0]) && file_exists(ROOT_DIR . '/app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]); // remove the controller name from the array, leaving only the function and variables.
        }

        // Recalling the controller file
        require_once ROOT_DIR . '/app/controllers/' . $this->controller . '.php';

        // Creating an object from the controller
        $this->controller = new $this->controller();

        // 3. Searching for the function
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 4. 
        $this->params = $url ? array_values($url) : [];

        // 5. Final implementation: Calling the function inside the controller and passing the variables to it.
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // # Fetch the link and split it into an array
    private function parseUrl() {
        if (isset($_GET['url'])) {
            // Remove the slash from the end of the link
            $url = rtrim($_GET['url'], '/');

            $url = filter_var($url, FILTER_SANITIZE_URL);

            // Splitting the link based on the slash into an array
            $url = explode('/', $url);

            return $url;
        }
        return [];
    }

}