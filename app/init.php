<?php
session_start();
include_once "../app/core/app.php";
include_once "../app/core/Controller.php";
include_once "../app/core/config.php";
include_once "../app/core/database.php";
include_once "../app/core/helper.php";
include_once "../app/core/helper.php";
include_once "../app/core/comunicate.php";

spl_autoload_register(function ($className){
    include_once "../app/models/" . $className . ".php";
});