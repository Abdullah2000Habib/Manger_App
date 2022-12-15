<?php
class comunicate{
    public function getMode(){
        return $_SESSION["mode"];
    }
    public function getMaxMissRate(){
        return $_SESSION["maxMissRate"];
    }
    public function getMinMissRate(){
        return $_SESSION["minMissRate"];
    }
}