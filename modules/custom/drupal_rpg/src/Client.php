<?php

namespace Drupal\drupal_rpg;

use Drupal\drupal_rpg\Ticket;

class Client{
  Private
          $_id, // the unique id of the client
          $_name, // the name of the client
          $_difficulty, // from 0 to 20
          $_needs; // same as health but for client when its reachs 0 the team wins


  public function __construct($id, $name, $difficulty, $needs) {
    $this->setId($id);
    $this->setName($name);
    $this->setDifficulty($difficulty);
    $this->setNeeds($needs);

  }

  //GETTERS
  public function id() {
    return $this->_id;
  }

  public function name() {
    return $this->_name;
  }

	public function needs() {
    return $this->_needs;
  }

  public function difficulty() {
    return $this->_difficulty;
  }

  //SETTERS
  public function setId($id) {
    $this->_id = $id;
  }

  public function setName($name) {
    if (is_string($name)) {
      $this->_name = $name;
    }
  }

  public function setNeeds($needs) {
    $needs = (int) $needs;

    if ($needs > 0) {
      $this->_needs = $needs;
    }
  }

  public function setDifficulty($difficulty) {
    $difficulty = (int) $difficulty;

    if ($difficulty > 0) {
      $this->_difficulty = $difficulty;
    }
  }

  public function summonTester(){
    //Try to summon a tester, the chance depends on difficulty
    $id = '';
    $name = '';
    $duration = '';
    $difficulty = '';
    //Look if there is 4 or less tester on the field
    if(count($GLOBALS['container']->tester()) <=4){
      $chance = mt_rand(0,100)*($this->difficulty()/10);
      if($chance >= 70){
        return new Tester($id, $name, $duration, $difficulty);
      }
    }
  }


}