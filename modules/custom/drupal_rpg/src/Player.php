<?php

namespace Drupal\drupal_rpg;


class Player{
  private $_name, // name of the character
          $_company, //name of the company
          $_level, // level of the character
          $_xp, // the current xp of a character
          $_xpForNextLevel, // the xp need for the next level, higher levels need more xp
          $_money; // the amount of money of the player

  public function __construct($name, $company, $level, $xp, $xpForNextLevel, $money) {

    $this->setName($name);
    $this->setCompany($company);
    $this->setLevel($level);
    $this->setXp($xp);
    $this->setXpForNextLevel($xpForNextLevel);
    $this->setMoney($money);
  }

	//GETTERS
	
	public function getName() {
		return $this->_name;
	}

  public function getCompany() {
    return $this->_company;
  }

  public function getLevel() {
    return $this->_level;
  }

  public function getXp() {
    return $this->_xp;
  }

  public function getXpForNextLevel() {
    return $this->_xpForNextLevel;
  }

  public function getMoney() {
    return $this->_money;
  }
	
	//SETTERS

	public function setName($name) {
    if (is_string($name)) {
      $this->_name = $name;
    }
  }

  public function setCompany($company) {
    if (is_string($company)) {
      $this->_company = $company;
    }
  }

  public function setLevel($level) {
    $level = (int) $level;

    if ($level > 0 && $level <= 99) {
      $this->_level = $level;
    }
  }

  public function setXp($xp) {
    $xp = (int) $xp;

    if ($xp > 0)  {
      $this->_xp = $xp;
    }
  }
  public function setXpForNextLevel($xp) {
    $xp = (int) $xp;

    if ($xp > 0 ) {
      $this->_xpForNextLevel = $xp;
    }
  }

  public function setMoney($money) {
    $money = (int) $money;

    if ($money > 0 ) {
      $this->_money = $money;
    }
  }

  public function levelUp() {
    $level = $this->getLevel()+1;
    $this->setLevel($level);
    //plein d'autre choses à faire ici
    //changer xpForNextLevel, changer stats...
  }

  public function gainXp($xp) {
    $xp = (int) $xp;
    $diffXp = $this->getXpForNextLevel() - ($this->getXp() + $xp);
    if($diffXp >0){
      $this->setXp($xp);
    }
    else{
      $this->setXp($xp);
      $this->levelUp();
    }
  }

  public function gainMoney($money) {
    $money = (int) $money;
    $this->setMoney($this->getMoney() + $money);
  }

  public function looseMoney($money) {
    $money = (int) $money;
    $newMoney = $this->getMoney() - $money;
    if($newMoney >0) {
      $this->setMoney($newMoney);
    }
    else {
      //fonction de banqueroute
    }

  }

}