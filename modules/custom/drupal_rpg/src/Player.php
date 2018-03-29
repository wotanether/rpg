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
	
	public function name() {
		return $this->_name;
	}

  public function company() {
    return $this->_company;
  }

  public function level() {
    return $this->_level;
  }

  public function xp() {
    return $this->_xp;
  }

  public function xpForNextLevel() {
    return $this->_xpForNextLevel;
  }

  public function money() {
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
      $this->_name = $company;
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
    $level = $this->level()+1;
    $this->setLevel($level);
    //plein d'autre choses à faire ici
    //changer xpForNextLevel, changer stats...
  }

  public function gainXp($xp) {
    $xp = (int) $xp;
    $diffXp = $this->xpForNextLevel() - ($this->xp() + $xp);
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
    $this->setMoney($this->money() + $money);
  }

}