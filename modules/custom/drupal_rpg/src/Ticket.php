<?php

namespace Drupal\drupal_rpg;

use Drupal\drupal_rpg\Character;

class Ticket {
  private $_id, // unique id of the ticket
          $_name, // name of the ticket
          $_category, //category of the ticket (general,backend,frontend,design...)
          $_difficulty, // the difficulty level of the ticket from 1 to 10
          $_character, // the character the ticket is attached to
          $_progression; // the progression of the resolution 0 to 100

  public function __construct($id, $name, $category, $difficulty, $character, $progression)
  {
    $this->setId($id);
    $this->setName($name);
    $this->setCategory($category);
    $this->setDifficulty($difficulty);
    $this->setCharacter($character);
    $this->setProgression($progression);

  }

  //GETTERS
	public function getId() {
    return $this->_id;
  }

  public function getName() {
    return $this->_name;
  }

  public function getCategory() {
    return $this->_category;
  }

  public function getDifficulty() {
    return $this->_difficulty;
  }

  public function getCharacter() {
    return $this->_character;
  }

  public function getProgression() {
    return $this->_progression;
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

  public function setCategory($category) {
    if (is_string($category)) {
      $this->_category = $category;
    }
  }

  public function setDifficulty($difficulty) {
    $difficulty = (int) $difficulty;

    if ($difficulty > 0 && $difficulty <= 10) {
      $this->_difficulty = $difficulty;
    }
  }

  public function setCharacter($character) {
      $this->_character = $character;
  }

  public function setProgression($progression) {
    $progression = (int) $progression;

    if ($progression >= 0 && $progression <= 100) {
      $this->_progression = $progression;
    }
  }

  public function doDammage(){
	  if($this->getProgression() < 100) {
	    $character = $this->getCharacter();
      $dammage = $this->getDifficulty();
	    if($character == null){
	      //if tickets are not attached by project manager by the end of turn, they dammage every character
        $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
        $rpg_container = $tempstore->get('rpg_container');
        foreach ($rpg_container->getCharacters() as $character){
          $character->setHealth($character->getHealth() - $dammage);
          $rpg_container->addCharacter($character);
        }
      }
      else{
        $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
        $rpg_container = $tempstore->get('rpg_container');
        $character = $rpg_container->characters()[$this->getCharacter()];
        $health = $character->getHealth();
        $character->setHealth($health - $dammage);
        $rpg_container->addCharacter($character);
      }

    }
  }
}