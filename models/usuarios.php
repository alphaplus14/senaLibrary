<?php

class usuarios{
    private $identidad;
    private $id;

    public function __construct(){}

    public function setNombre($identidad){
        $this->identidad=$identidad;
    }
    public function getNombre(){
        return $this->identidad;
    }
    
    public function setId($id){
        $this->id=$id;
    }
    public function getId(){
        return $this->id;
    }

}
?>