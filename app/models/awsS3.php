<?php
use Aws\S3\S3Client;
use Aws\S3;
use Aws\S3\Exception\S3Exception;
class awsS3{
    public $s3 = null;
    public function __construct(){
        if($this->s3 === null){
            $this->gets3Client();
        }
    }
    public function gets3Client()
    {
        try {
            $this->s3 = S3Client::factory(
                array(
                    'credentials' => array(
                        'key' => S3_KEY,
                        'secret' => S3_SECRET
                    ),
                    'version' => 'latest',
                    'region' => 'us-east-1'
                )
            );
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    public function deleteAll($dir){
        $db = new database();
        $data = $db->query("SELECT image_path FROM cache_data");
        if(is_array($data)){
            foreach($data as $row){
                $this->s3->deleteObject(["Bucket"=>BUCKET_NAME,'Key'=>$row->image_path]);
            }
        }
        $db->query("TRUNCATE TABLE cache_data");
        $db->query("TRUNCATE TABLE statistics");
    }
}