<?php

namespace Drupal\drupal_rpg;

class Container {
  private $_player,
          $_characters = [], // list of all characters
          $_tickets = [], // list of all tickets
          $_testers = [], // list of all testers
          $_clients = [], // list of all client
          $_charMarket = []; //list of all character available on the market

  //GETTER

  public function getPlayer() {
    return $this->_player;
  }

  public function getCharacters() {
    return $this->_characters;
  }

  public function getTickets() {
    return $this->_tickets;
  }

  public function getTesters() {
    return $this->_testers;
  }

  public function getClients() {
    return $this->_clients;
  }

  public function getCharMarket() {
    return $this->_charMarket;
  }

  //ADD and REMOVE
  public function setPlayer(Player $player) {
    $this->_player = $player;
  }

  public function addCharacter(Character $character) {
    $this->_characters[$character->getId()] = $character;
  }
  public function removeCharacter(Character $character) {
    unset($this->_characters[$character->getId()]);
  }

  public function addTicket(Ticket $ticket) {
    $this->_tickets[$ticket->getId()] = $ticket;
  }
  public function removeTicket(Ticket $ticket) {
    unset($this->_tickets[$ticket->getId()]);
  }

  public function addTester(Tester $tester) {
    $this->_testers[$tester->getId()] = $tester;
  }
  public function removeTester(Tester $tester) {
    unset($this->_testers[$tester->getId()]);
  }

  public function addClient(Client $client) {
    $this->_clients[$client->getId()] = $client;
  }
  public function removeClient(Client $client) {
    unset($this->_clients[$client->getId()]);
  }

  public function addToCharMarket(Character $character) {
    $this->_charMarket[$character->getId()] = $character;
  }

  public function removeFromCharMarket(Character $character) {
    unset($this->_charMarket[$character->getId()]);
  }

  public function shuffleCharMarket() {
    $charMarket = $this->getCharMarket();
    shuffle($charMarket);
    $this->_charMarket = array_slice($charMarket,0,6);
    foreach ($charMarket as $character){
       $characterMarket[$character->getId()] = $character;
    }
  }

  //SELECT
  public function selectCharacter($characterId) {
    return $this->_characters[$characterId];
  }

  public function selectCharmarket($characterId) {
    return $this->_charMarket[$characterId];
  }


}