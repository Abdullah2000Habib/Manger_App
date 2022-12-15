<?php
use Aws\Ec2\Ec2Client;
use Aws\CloudWatch\CloudWatchClient;
use Aws\Exception\AwsException;
class Home extends Controller{
    public $pool = null;
    public $data = array();
    public function __construct(){
        $this->pool = new ec2pool();
    }
    public function index(){
        $this->getdata();
        $this->view("index",$this->data);
    }
    public function manualConfig(){
        $this->pool = new ec2pool();
        if(isset($_POST["grow"])){
            $this->pool->create_instance_from_Image();
        }
        elseif (isset($_POST["shrink"])){
            $this->pool->terminate_instance();
        }
        $this->getdata();
        $this->view("index",$this->data);

    }

    private function getdata()
    {
        if(!isset($_SESSION["mode"])){
            $_SESSION["mode"] = "manual";
        }
        $cloudWatch = new cloudWatch();
        $config = new config();
        if(isset($_SESSION["minMissRate"])){
            $this->data["minMissRate"] = $_SESSION["minMissRate"];
            $this->data["maxMissRate"] = $_SESSION["maxMissRate"];
        }
        else{
            $this->data["minMissRate"] = 0;
            $this->data["maxMissRate"] = 0;
            $_SESSION["minMissRate"] = 0;
            $_SESSION["maxMissRate"] = 0;
            $cloudWatch->saveAutoConfig($this->data["minMissRate"],$this->data["maxMissRate"]);
        }
        $this->data["statistics"] = $cloudWatch->getCloudWatchStatistics();
        $this->saveData($this->data["statistics"]);
        $this->data["mode"] = $_SESSION["mode"] ?? "manual";
        $this->data["numberOfInstances"] = $this->pool->get_number_of_instances();
        $cloudWatch->saveNumberOfWorkers($this->data["numberOfInstances"]);
        $this->data["cacheConfig"] = $config->getCacheConfig();
    }
    public function AllNodesOps(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST["delete"])){
                $this->deleteAll();
            }
            elseif(isset($_POST["clear"])){
                $this->clearAll();
            }
        }
    }
    public function autoConfig(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $_SESSION["maxMissRate"] = $_POST["maxMissRate"];
            $_SESSION["minMissRate"] = $_POST["minMissRate"];
            if($_SESSION["minMissRate"] >= $_SESSION["maxMissRate"]){
                $_SESSION["minMissRate"] = $_SESSION["maxMissRate"] - 1;
            }
            $cloudWatch = new cloudWatch();
            $cloudWatch->saveAutoConfig($_SESSION["maxMissRate"],$_SESSION["minMissRate"]);
        }
        $this->getdata();
        $this->view("index",$this->data);
    }

    private function deleteAll()
    {
        $s3 = new awsS3();
        $s3->deleteAll("/data/");
        $this->getdata();
        $this->view("index",$this->data);
    }

    private function clearAll()
    {
        //todo
    }
    public function config(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST["policy"]) && isset($_POST["capacity"])){
                $capacity = $_POST["capacity"];
                $policy = $_POST["policy"];
                $config = new config();
                $isvalid = $config->validateCapacityPolicy($capacity,$policy);
                if($isvalid){
                    $config->applyTheNewConfig($capacity,$policy);
                }
            }
        }
        $this->getdata();
        $this->view("index",$this->data);
    }
    public function mode(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $_SESSION["mode"] = $_POST["mode"];
            $cloudWatch = new cloudWatch();
            $cloudWatch->putMode($_SESSION["mode"]);
        }
        $this->getdata();
        $this->view("index",$this->data);

    }

    private function saveData($data)
    {
        $path = "data.json";
        $fp = fopen($path, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }
}