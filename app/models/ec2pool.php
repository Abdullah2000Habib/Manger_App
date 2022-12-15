<?php
use Aws\Ec2\Ec2Client;
class ec2pool{
    public $ec2 = null;
    public function __construct(){
        $this->getec2Instance();
    }
    public function getec2Instance(){
        $this->ec2 = Ec2Client::factory(array(
            'credentials'=>[
                'key'    => EC2_KEY,
                'secret' => EC2_SECRET_KEY,
            ],
            'region' => EC2_region,
            'version'=>EC2_VERSION
        ));
        return $this->ec2;
    }
    public function get_number_of_instances(){
        $result = $this->getInstancesInfo();
        $count = 0;
        foreach($result['Reservations'] as $instances)
        {
            foreach($instances['Instances'] as $instance)
            {
                if($instance["State"]["Code"] == 16 || $instance["State"]["Code"] == 0){
                    $count++;
                }
            }
        }
        return $count;
    }

    public function getInstancesInfo()
    {
        return $this->ec2->describeInstances();
    }
    public function create_instance_from_Image(){
        $number_of_instances = $this->get_number_of_instances();
        if($number_of_instances < 8 ){
            $instance_name = "mem-cache-copy" . $number_of_instances;
            $result = $this->ec2->runInstances([
                'ImageId' => IAMGE_ID,
                'InstanceType' => 't2.micro',
                'KeyName' => "key",
                'SecurityGroupIds' => ['sg-08011b749b762b03c'],
                'MinCount' => 1,
                'MaxCount' => 1,
                'TagSpecifications '=>[
                    'ResourceType'  => 'instance',
                    'Tags'          => [
                        'Key' => 'Name',
                        'Value'=> $instance_name
                    ],
                ],
            ]);
        }
    }
    public function stop_instance(){
        $instance_id = $this->selectInstance();
        if($instance_id != false){
            $result = $this->ec2->stopInstances(array(
                'InstanceIds' => [$instance_id],
            ));
        }
    }
    public function terminate_instance(){
        $instance_id = $this->selectInstance();
        if($instance_id != false){
            $result = $this->ec2->terminateInstances(array(
                'InstanceIds' => array($instance_id),
            ));
            return $result;
        }
        return false;
    }
    public function get_instance_by_name($name){
        $result = $result = $this->ec2->describeInstances(array(
            "Filters" => array(
                array(
                    "Name" => "tag:environment",
                    "Values" => array(
                        $name
                    )
                )
            )
        ));
        return $result;
    }

    public function selectInstance()
    {
        $count = $this->get_number_of_instances();
        if($count > 1){
            $result = $this->getInstancesInfo();
            foreach($result['Reservations'] as $instances) {
                foreach ($instances['Instances'] as $instance) {
                    if($instance["InstanceId"] == MAIN_EC2_INSTANCE){
                        continue;
                    }
                    if($instance["State"]["Code"] == 16 || $instance["State"]["Code"] == 0) {
                        return $instance["InstanceId"];
                    }
                }
            }
        }
        return false;
    }
}
