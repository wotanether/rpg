<?php

namespace Drupal\drupal_rpg;


class Character{
  private $_id, // unique id of the character
          $_name, // name of the character
          $_speciality, //speciality of the character(front,back,rh...)
          $_level, // level of the character
          $_salary, // the amount of money you have to pay at the end of the year
          $_status, // active or burnout burnout characters can't do anything before their health
          $_xp, // the current xp of a character
          $_xpForNextLevel, // the xp need for the next level, higher levels need more xp

          $_health, // health of the character,it slowly drops if the character has too much tickets, if it drops to 0 the character is "burnout"
          $_speed, // character speed influence every action (time to resolve a ticket, develop a module...) basic-time x speed
          $_skill, // skill represent the strengh of a character the more skill he has, the more "need of the client" he fills with each action
          $_luck, // luck influence the outcome of events(client changing his mind, technical issue on the computer, electrical failure...) and the result of actions like "find a solution on stackoverflow" or "search for example"

          $_tickets = []; // an array of tickets assigned to a character

  public function __construct($id, $name, $speciality, $level, $salary, $status, $xp, $xpForNextLevel, $health, $speed, $skill, $luck) {

    $this->setId($id);
    $this->setName($name);
    $this->setSpeciality($speciality);
    $this->setLevel($level);
    $this->setSalary($salary);
    $this->setStatus($status);
    $this->setXp($xp);
    $this->setXpForNextLevel($xpForNextLevel);
    $this->setHealth($health);
    $this->setSpeed($speed);
    $this->setSkill($skill);
    $this->setLuck($luck);

  }

	//GETTERS
	public function getId() {
		return $this->_id;
	}
	
	public function getName() {
		return $this->_name;
	}

  public function getLevel() {
    return $this->_level;
  }

  public function getSalary() {
    return $this->_salary;
  }

  public function getSpeciality() {
    return $this->_speciality;
  }

  public function getStatus() {
    return $this->_status;
  }

  public function getXp() {
    return $this->_xp;
  }

  public function getXpForNextLevel() {
    return $this->_xpForNextLevel;
  }

	public function getHealth() {
		return $this->_health;
	}
	
	public function getSpeed() {
		return $this->_speed;
	}
	
	public function getSkill() {
		return $this->_skill;
	}
	
	public function getLuck() {
		return $this->_luck;
	}

	public function getTickets() {
    return $this->_tickets;
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

  public function setLevel($level) {
    $level = (int) $level;

    if ($level > 0 && $level <= 99) {
      $this->_level = $level;
    }
  }

  public function setSalary($salary) {
    $salary = (int)$salary;

    if ($salary > 0) {
      $this->_salary = $salary;
    }
  }

  public function setSpeciality($speciality)
  {
    if (is_string($speciality)) {
      $this->_speciality = $speciality;
    }
  }
  public function setStatus($status) {
    if (is_string($status)) {
      $this->_status = $status;
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

  public function setHealth($health) {
    $health = (int) $health;

    if ($health > 0 && $health < 1000) {
      $this->_health = $health;
    }
  }

  public function setSpeed($speed) {
    if ($speed > 0 && $speed <= 2) {
      $this->_speed = $speed;
    }
  }

  public function setSkill($skill) {
    $skill = (int) $skill;

    if ($skill > 0 && $skill <= 99) {
      $this->_skill = $skill;
    }
  }

  public function setLuck($luck) {
    $luck = (int) $luck;

    if ($luck > 0 && $luck <= 99) {
      $this->_luck = $luck;
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


  public function addTickets(Ticket $ticket) {
	  $this->_tickets[$ticket->id()] = $ticket;
  }

  //ticket resolution
  public function resolveTickets(){
    if ($this->getStatus() == 'active'){
      $tickets = $this->getTickets();
      foreach ($tickets as $ticket){
        //Compare tickets stats to character stats to get a resolve time in turn
        $difficulty = $ticket->getDifficulty();
        $progression = $ticket->getProgression();
        $skill = $this->getSkill();
        $speed = $this->getSpeed();
        $turnToResolve = round(($difficulty-($skill/25))/$speed);
        $ticket->setProgression($progression+(100/$turnToResolve));
        $GLOBALS['container']->addTicket($ticket);
      }

    }
  }

  public function burnout(){
	  if ($this->getHealth() <= 0){
	    $this->setStatus('burnout');
    }
    elseif ($this->getHealth() > 0){
      $this->setStatus('burnout');
    }
  }

}