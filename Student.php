<?php
  class Student{

    public $firstname;
    public $lastname;
    public $grades;

    function __construct($firstname,$lastname){
      $this->firstname = $firstname;
      $this->lastname = $lastname;
      $grades = array();
    }

    public function displayname(){
      echo ($this->firstname." ".
        $this->lastname);
    }



  }

