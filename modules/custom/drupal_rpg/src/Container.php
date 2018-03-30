<?php

namespace Drupal\drupal_rpg;

class Container {
  private $_player,
          $_characters = [], // list of all characters
          $_tickets = [], // list of all tickets
          $_testers = [], // list of all testers
          $_clients = []; // list of all client

  //GETTER

  public function player() {
    return $this->_player;
  }

  public function characters() {
    return $this->_characters;
  }

  public function tickets() {
    return $this->_tickets;
  }

  public function testers() {
    return $this->_testers;
  }

  public function clients() {
    return $this->_clients;
  }

  //ADD and REMOVE
  public function setPlayer(Player $player) {
    $this->_player = $player;
  }

  public function addCharacter(Character $character) {
    $this->_characters[$character->id()] = $character;
  }
  public function removeCharacter(Character $character) {
    unset($this->_characters[$character->id()]);
  }

  public function addTicket(Ticket $ticket) {
    $this->_tickets[$ticket->id()] = $ticket;
  }
  public function removeTicket(Ticket $ticket) {
    unset($this->_tickets[$ticket->id()]);
  }

  public function addTester(Tester $tester) {
    $this->_testers[$tester->id()] = $tester;
  }
  public function removeTester(Tester $tester) {
    unset($this->_testers[$tester->id()]);
  }

  public function addClient(Client $client) {
    $this->_clients[$client->id()] = $client;
  }
  public function removeClient(Client $client) {
    unset($this->_clients[$client->id()]);
  }

}