<?php

class Controller
{
    public function view($view,$data = array()){
        if(file_exists("../app/views/" . strtolower($view) . ".view.php")){
            extract($data);
            include_once "../app/views/" . strtolower($view) . ".view.php";
        }
        else{
            http_response_code(404);
            echo "Page Not Found";
        }
    }
}