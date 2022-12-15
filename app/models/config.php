<?php
class config{
    public database $db;
    public function __construct(){
        $this->db = new database();
    }

    public function validateCapacityPolicy($capacity,$policy)
    {
        $isVaildCapacity = $this->validCapacity($capacity);
        $isValidPolicy = $this->validatePolicy($policy);
        return $isVaildCapacity && $isValidPolicy;
    }

    private function validCapacity($capacity)
    {
        if($capacity >= 1 && $capacity <= 4 ){
            return true;
        }
        return false;

    }

    private function validatePolicy($policy)
    {
        if(is_array($this->getPolicyid($policy))){
            return true;
        }
        return false;
    }

    public function applyTheNewConfig($capacity,$policy)
    {
        $data = $this->getPolicyid($policy);
        $query = "UPDATE configuration set capacity = :capacity,policy_id = :policy ";
        $this->db->query($query,[
            "capacity"=>$capacity,
            "policy"=>$data[0]->id
        ]);
    }
    public function getCacheConfig(){
        $query = "SELECT c.capacity as capacity, p.policy as policy FROM configuration c inner join  polices p on(c.policy_id = p.id) LIMIT 1";
        $data = $this->db->query($query);
        return $data;
    }

    private function getPolicyid($policy)
    {
        $query = "SELECT id FROM polices WHERE policy = :policy";
        $db = new database();
        return $db->query($query,["policy"=>$policy]);
    }
}