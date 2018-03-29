<?php

namespace Drupal\drupal_rpg;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\drupal_rpg\Container;
use Drupal\drupal_rpg\Character;
use Drupal\drupal_rpg\Ticket;
use Drupal\drupal_rpg\Tester;
use Drupal\drupal_rpg\Client;

class Manager{
  use StringTranslationTrait;

  public function initUserData($userId){
    $database = \Drupal::database();
    $rpg_container = new Container();
    $objects =['character', 'ticket', 'tester', 'client'];

    foreach ($objects as $object){
      $query = $database->select('drupal_rpg_' . $object, 'obj');
      $query->fields('obj');
      $query->condition('obj.uid', $userId, '=');

      $results = $query->execute()->fetchAll();
      foreach ($results as $result){
        switch ($object){
          case 'character':
            $character = new Character( $result->cid,
                                        $result->name,
                                        $result->speciality,
                                        $result->level,
                                        $result->status,
                                        $result->xp,
                                        $result->xp_for_next_level,
                                        $result->health,
                                        $result->speed,
                                        $result->skill,
                                        $result->luck);
            $rpg_container->addCharacter($character);
            break;
          case 'ticket':
            $ticket = new Ticket( $result->tid,
                                  $result->name,
                                  $result->category,
                                  $result->difficulty,
                                  $result->character,
                                  $result->progression);
            $rpg_container->addTicket($ticket);
            break;
          case 'tester':
            $tester = new Tester( $result->tid,
                                  $result->name,
                                  $result->difficulty,
                                  $result->duration);
            $rpg_container->addTester($tester);
            break;
          case 'client':
            $client = new Client( $result->cid,
                                  $result->name,
                                  $result->difficulty,
                                  $result->needs);
            $rpg_container->addClient($client);
            break;
        }
      }
    }

    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $tempstore->set('rpg_container', $rpg_container);
  }

  public function deleteUserData($userId){
    $database = \Drupal::database();

    $objects =['character', 'ticket', 'tester', 'client'];
    foreach ($objects as $object) {
      $query = $database->delete('drupal_rpg_' . $object)
                        ->condition('uid', $userId)
                        ->execute();
    }
  }


  public function count($object) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();

    $query = $database->select('drupal_rpg_' . $object, 'obj');
    $query->fields('obj');
    $query->condition('obj.uid', $user->id(), '=');

    return $query->countQuery()->execute()->fetchField();
  }

  //CREATORS

  public function createPlayer(Character $character) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->insert('drupal_rpg_character')
      ->fields([
        'uid' => $user->id(),
        'cid' => $character->id(),
        'name' => $character->name(),
        'speciality' => $character->speciality(),
        'level' => $character->level(),
        'status' => $character->status(),
        'xp' => $character->xp(),
        'xp_for_next_level' => $character->xpForNextLevel(),
        'health' => $character->health(),
        'speed' => $character->speed(),
        'skill' => $character->skill(),
        'luck' => $character->luck(),
      ])
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Character successfully created'));
    }
    else {
      $messenger->addError($this->t('Character not created'));
    }
  }



  /**
   * @param Character $character
   * @param $speciality
   * @throws \Exception
   */
  public function createCharacter(Character $character) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->insert('drupal_rpg_character')
                      ->fields([
                               'uid' => $user->id(),
                               'cid' => $character->id(),
                               'name' => $character->name(),
                               'speciality' => $character->speciality(),
                               'level' => $character->level(),
                               'status' => $character->status(),
                               'xp' => $character->xp(),
                               'xp_for_next_level' => $character->xpForNextLevel(),
                               'health' => $character->health(),
                               'speed' => $character->speed(),
                               'skill' => $character->skill(),
                               'luck' => $character->luck(),
                               ])
                      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Character successfully created'));
    }
    else {
      $messenger->addError($this->t('Character not created'));
    }
  }

  /**
   * @param Ticket $character
   * @throws \Exception
   */
  public function createTicket(Ticket $ticket) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->insert('drupal_rpg_ticket')
                      ->fields([
                        'uid' => $user->id(),
                        'tid' => $ticket->id(),
                        'name' => $ticket->name(),
                        'category' => $ticket->category(),
                        'difficulty' => $ticket->difficulty(),
                        'character' => $ticket->character(),
                        'progression' => $ticket->progression(),
                      ])
                      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Ticket successfully created'));
    }
    else {
      $messenger->addError($this->t('Ticket not created'));
    }
  }

  /**
   * @param Tester $tester
   * @throws \Exception
   */
  public function createTester(Tester $tester) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->insert('drupal_rpg_tester')
                      ->fields([
                        'uid' => $user->id(),
                        'tid' => $tester->id(),
                        'name' => $tester->name(),
                        'difficulty' => $tester->difficulty(),
                        'duration' => $tester->duration(),
                      ])
                      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Tester successfully created'));
    }
    else {
      $messenger->addError($this->t('Tester not created'));
    }
  }

  /**
   * @param Client $client
   * @throws \Exception
   */
  public function createClient(Client $client) {
    $user = \Drupal::currentUser();
    $messenger = \Drupal::messenger();
    $database = \Drupal::database();
    $query = $database->insert('drupal_rpg_client')
                      ->fields([
                        'uid' => $user->id(),
                        'cid' => $client->id(),
                        'name' => $client->name(),
                        'difficulty' => $client->difficulty(),
                        'needs' => $client->needs(),
                      ])
                      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Client successfully created'));
    }
    else {
      $messenger->addError($this->t('Client not created'));
    }
  }

  //SELECTORS

  public function selectCharacter($characterId){
    $database = \Drupal::database();
    $query = $database->select('drupal_rpg_character', 'char');
    $query->fields('char');
    $query->condition('char.cid', $characterId, '=');

    return $query->execute()->fetchAll();
  }

  public function selectTicket($ticketId){
    $database = \Drupal::database();
    $query = $database->select('drupal_rpg_ticket', 'tic');
    $query->fields('tic');
    $query->condition('tic.tid', $ticketId, '=');

    return $query->execute()->fetchAll();
  }

  public function selectTester($testerId){
    $database = \Drupal::database();
    $query = $database->select('drupal_rpg_tester', 'test');
    $query->fields('test');
    $query->condition('test.cid', $testerId, '=');

    return $query->execute()->fetchAll();
  }

  public function selectClient($clientId){
    $database = \Drupal::database();
    $query = $database->select('drupal_rpg_client', 'cli');
    $query->fields('cli');
    $query->condition('cli.cid', $clientId, '=');

    return $query->execute()->fetchAll();
  }

  //UPDATERS

  public function updateCharacter(Character $character) {
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->update('drupal_rpg_character')
                      ->fields([
                        'name' => $character->name(),
                        'level' => $character->level(),
                        'speciality' => $character->speciality(),
                        'status' => $character->status(),
                        'xp' => $character->xp(),
                        'xp_for_next_level' => $character->xpForNextLevel(),
                        'health' => $character->health(),
                        'speed' => $character->speed(),
                        'skill' => $character->skill(),
                        'luck' => $character->luck(),
                      ])
      ->condition('cid', $character->id(), '=')
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Character successfully updated'));
    }
    else {
      $messenger->addError($this->t('Character not updated'));
    }
  }

  public function updateTicket(Ticket $ticket) {
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->update('drupal_rpg_ticket')
                      ->fields([
                        'name' => $ticket->name(),
                        'category' => $ticket->category(),
                        'difficulty' => $ticket->difficulty(),
                        'character' => $ticket->character(),
                        'progression' => $ticket->progression(),
      ])
      ->condition('tid', $ticket->id(), '=')
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Ticket successfully updated'));
    }
    else {
      $messenger->addError($this->t('Ticket not updated'));
    }
  }

  public function updateTester(Tester $tester) {
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->update('drupal_rpg_tester')
                      ->fields([
                        'name' => $tester->name(),
                        'difficulty' => $tester->difficulty(),
                        'duration' => $tester->duration(),
                      ])
      ->condition('cid', $tester->id(), '=')
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Tester successfully updated'));
    }
    else {
      $messenger->addError($this->t('Tester not updated'));
    }
  }

  public function updateClient(Client $client) {
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();
    $query = $database->update('drupal_rpg_client')
                      ->fields([
                        'name' => $client->name(),
                        'difficulty' => $client->difficulty(),
                        'needs' => $client->needs(),
                      ])
      ->condition('cid', $client->id(), '=')
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Client successfully updated'));
    }
    else {
      $messenger->addError($this->t('Client not updated'));
    }
  }

  //DELETERS

  function deleteCharacter(Character $character){
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();

    $query = $database->delete('drupal_rpg_character')
      ->condition('cid', $character->id())
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Character successfully deleted'));
    }
    else {
      $messenger->addError($this->t('Character not deleted'));
    }
  }

  function deleteTicket(Ticket $ticket){
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();

    $query = $database->delete('drupal_rpg_ticket')
      ->condition('tid', $ticket->id())
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Ticket successfully deleted'));
    }
    else {
      $messenger->addError($this->t('Ticket not deleted'));
    }
  }

  function deleteTester(Tester $tester){
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();

    $query = $database->delete('drupal_rpg_tester')
      ->condition('tid', $tester->id())
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Tester successfully deleted'));
    }
    else {
      $messenger->addError($this->t('Tester not deleted'));
    }
  }

  function deleteClient(Client $client){
    $database = \Drupal::database();
    $messenger = \Drupal::messenger();

    $query = $database->delete('drupal_rpg_client')
      ->condition('cid', $client->id())
      ->execute();
    if($query != NULL) {
      $messenger->addStatus($this->t('Client successfully deleted'));
    }
    else {
      $messenger->addError($this->t('Client not deleted'));
    }
  }


}