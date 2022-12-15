<?php
class App{
    private $controller = "home";
    private $method = "index";
    private $parms = [];
    public function __construct(){
        $url = $this->splitURL();
        if(file_exists("../app/controllers/" . ucfirst($url[0]) . ".php")){
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }
        require "../app/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller;
        if(isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        $this->parms = array_values($url);
        call_user_func_array([$this->controller,$this->method],$this->parms);
    }
    private function splitURL(){
        $url = isset($_GET["url"]) ? trim($_GET["url"],"/") : "home";
        $url = filter_var($url,FILTER_SANITIZE_URL);
        return explode('/',$url);
    }
}