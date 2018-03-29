<?php

namespace Drupal\drupal_rpg;


class Character{
  private $_id, // unique id of the character
          $_name, // name of the character
          $_speciality, //speciality of the character(front,back,rh...)
          $_level, // level of the character
          $_status, // active or burnout burnout characters can't do anything before their health
          $_xp, // the current xp of a character
          $_xpForNextLevel, // the xp need for the next level, higher levels need more xp

          $_health, // health of the character,it slowly drops if the character has too much tickets, if it drops to 0 the character is "burnout"
          $_speed, // character speed influence every action (time to resolve a ticket, develop a module...) basic-time x speed
          $_skill, // skill represent the strengh of a character the more skill he has, the more "need of the client" he fills with each action
          $_luck, // luck influence the outcome of events(client changing his mind, technical issue on the computer, electrical failure...) and the result of actions like "find a solution on stackoverflow" or "search for example"

          $_tickets = []; // an array of tickets assigned to a character

  public function __construct($id, $name, $speciality, $level, $status, $xp, $xpForNextLevel, $health, $speed, $skill, $luck) {

    $this->setId($id);
    $this->setName($name);
    $this->setSpeciality($speciality);
    $this->setLevel($level);
    $this->setStatus($status);
    $this->setXp($xp);
    $this->setXpForNextLevel($xpForNextLevel);
    $this->setHealth($health);
    $this->setSpeed($speed);
    $this->setSkill($skill);
    $this->setLuck($luck);

  }

	//GETTERS
	public function id() {
		return $this->_id;
	}
	
	public function name() {
		return $this->_name;
	}

  public function level() {
    return $this->_level;
  }

  public function speciality() {
    return $this->_speciality;
  }

  public function status() {
    return $this->_status;
  }

  public function xp() {
    return $this->_xp;
  }

  public function xpForNextLevel() {
    return $this->_xpForNextLevel;
  }

	public function health() {
		return $this->_health;
	}
	
	public function speed() {
		return $this->_speed;
	}
	
	public function skill() {
		return $this->_skill;
	}
	
	public function luck() {
		return $this->_luck;
	}

	public function tickets() {
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

    if ($health > 0 && $health < 200) {
      $this->_health = $health;
    }
  }

  public function setSpeed($speed) {
    $speed = (int) $speed;

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

  public function addTickets(Ticket $ticket) {
	  $this->_tickets[$ticket->id()] = $ticket;
  }

  //ticket resolution
  public function resolveTickets(){
    if ($this->status() == 'active'){
      $tickets = $this->tickets();
      foreach ($tickets as $ticket){
        //Compare tickets stats to character stats to get a resolve time in turn
        $difficulty = $ticket->difficulty();
        $progression = $ticket->progression();
        $skill = $this->skill();
        $speed = $this->speed();
        $turnToResolve = round(($difficulty-($skill/25))/$speed);
        $ticket->setProgression($progression+(100/$turnToResolve));
        $GLOBALS['container']->addTicket($ticket);
      }

    }
  }

  public function burnout(){
	  if ($this->_health <= 0){
	    $this->setStatus('burnout');
    }
    elseif ($this->_health > 0){
      $this->setStatus('burnout');
    }
  }

}