<?php

namespace Drupal\drupal_rpg;

class Container {
  private $_tickets = [], // list of all tickets
          $_clients = [], // list of all client
          $_characters = [], // list of all characters
          $_testers = []; // list of all testers

  //GETTER
  public function tickets() {
    return $this->_tickets;
  }

  public function clients() {
    return $this->_clients;
  }

  public function characters() {
    return $this->_characters;
  }

  public function testers() {
    return $this->_testers;
  }

  //ADD and REMOVE
  public function addTicket(Ticket $ticket) {
    if (is_object($ticket)) {
      $this->_tickets[$ticket->id()] = $ticket;
    }
  }
  public function removeTicket(Ticket $ticket) {
    if (is_object($ticket)) {
      unset($this->_tickets[$ticket->id()]);
    }
  }

  public function addClient(Client $client) {
    if (is_object($client)) {
      $this->_clients[$client->id()] = $client;
    }
  }

  public function removeClient(Client $client) {
    if (is_object($client)) {
      unset($this->_clients[$client->id()]);
    }
  }

  public function addCharacter(Character $character) {
    if (is_object($character)) {
      $this->_characters[$character->id()] = $character;
    }
  }

  public function removeCharacter(Character $character) {
    if (is_object($character)) {
      unset($this->_characters[$character->id()]);
    }
  }

  public function addTester(Tester $tester) {
    if (is_object($tester)) {
      $this->_testers[$tester->id()] = $tester;
    }
  }

  public function removeTester(Tester $tester) {
    if (is_object($tester)) {
      unset($this->_testers[$tester->id()]);
    }
  }





}