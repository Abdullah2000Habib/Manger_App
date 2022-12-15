<?php
$root = $_SERVER["REQUEST_SCHEME"] . "://" .$_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"];
define("ROOT",str_replace("index.php","",$root));
define("ASSETS",ROOT . "assets/");
//database config
define("DB_TYPE","mysql");
define("DB_NAME","mem_cache");
define("DB_HOST","memcache.cdidsyfz8fdn.us-east-1.rds.amazonaws.com");
define("DB_PASS","Mah8790035$$");
define("DB_USER","root");

//ec2 Variables
define("EC2_KEY","AKIATBIKKENR63HWFQGW");
define("EC2_SECRET_KEY","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");
define("EC2_region","us-east-1");
define("EC2_VERSION","latest");
define("MAIN_EC2_INSTANCE","i-020a910f0771e2df4");
define("IAMGE_ID","ami-0f29876ab8a5085c7");


//time
date_default_timezone_set('Asia/Gaza');


//s3 variables
define("BUCKET_NAME",'mem-cache-bucket');
define("S3_KEY",'AKIATBIKKENR6VTRW6A2');
define("S3_SECRET","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");
define("IAM_KEY",'AKIATBIKKENR63HWFQGW');
define("IAM_SECRET","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");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
