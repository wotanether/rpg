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
    $objects =['player', 'character', 'ticket', 'tester', 'client'];

    foreach ($objects as $object){
      $query = $database->select('drupal_rpg_' . $object, 'obj');
      $query->fields('obj');
      $query->condition('obj.uid', $userId, '=');

      $results = $query->execute()->fetchAll();
      foreach ($results as $result){
        switch ($object){
          case 'player':
            $player = new Player( $result->name,
                                  $result->company,
                                  $result->level,
                                  $result->xp,
                                  $result->xp_for_next_level,
                                  $result->money);
            $rpg_container->setPlayer($player);
            break;
          case 'character':
            $character = new Character( $result->cid,
                                        $result->name,
                                        $result->speciality,
                                        $result->level,
                                        $result->salary,
                                        $result->status,
                                        $result->xp,
                                        $result->xp_for_next_level,
                                        $result->health,
                                        $result->speed,
                                        $result->skill,
                                        $result->luck);
            if($character->getStatus() == 'market'){
              $rpg_container->addToCharMarket($character);
            }
            else{
              $rpg_container->addCharacter($character);
            }

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

    //$rpg_container->shuffleCharMarket();

    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $tempstore->set('rpg_container', $rpg_container);
  }

  public function deleteUserData($userId){

    $database = \Drupal::database();
    $query = $database->select('drupal_rpg_player', 'pla')
                      ->fields('pla')
                      ->condition('pla.uid', $userId, '=');
    $player = $query->execute()->fetchAll();
    if(!empty($player)){
      $objects =['player', 'character', 'ticket', 'tester', 'client'];
      foreach ($objects as $object) {
        $query = $database->delete('drupal_rpg_' . $object)
          ->condition('uid', $userId)
          ->execute();
      }
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

  /**
   * @param Player $player
   * @throws \Exception
   */
  public function createPlayer(Player $player) {
    $rpg_container = new Container();
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->insert('drupal_rpg_player')
      ->fields([
        'uid' => $user->id(),
        'name' => $player->getName(),
        'company' => $player->getCompany(),
        'level' => $player->getLevel(),
        'xp' => $player->getXp(),
        'xp_for_next_level' => $player->getXpForNextLevel(),
        'money' => $player->getMoney(),
      ])
      ->execute();
    $rpg_container->setPlayer($player);
    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $tempstore->set('rpg_container', $rpg_container);
  }

  /**
   * @param Character $character
   * @param $speciality
   * @throws \Exception
   */
  public function createCharacter(Character $character) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->insert('drupal_rpg_character')
                      ->fields([
                               'uid' => $user->id(),
                               'cid' => $character->getId(),
                               'name' => $character->getName(),
                               'speciality' => $character->getSpeciality(),
                               'level' => $character->getLevel(),
                               'salary' => $character->getSalary(),
                               'status' => $character->getStatus(),
                               'xp' => $character->getXp(),
                               'xp_for_next_level' => $character->getXpForNextLevel(),
                               'health' => $character->getHealth(),
                               'speed' => $character->getSpeed(),
                               'skill' => $character->getSkill(),
                               'luck' => $character->getLuck(),
                               ]);
    $query->execute();
  }

  /**
   * @throws \Exception
   */
  public function randomCharacter(){
    $specialityList = ['back', 'front', 'rh', 'pm', 'graphist', 'sales'];

    $namesList = ['Allison','Arthur','Ana','Alex','Arlene','Alberto','Barry','Bertha','Bill','Bonnie','Bret','Beryl','Chantal','Cristobal','Claudette','Charley',
              'Cindy','Chris','Dean','Dolly','Danny','Danielle','Dennis','Debby','Erin','Edouard','Erika','Earl','Emily','Ernesto','Felix','Fay','Fabian','Frances',
              'Franklin','Florence','Gabielle','Gustav','Grace','Gaston','Gert','Gordon','Humberto','Hanna','Henri','Hermine','Harvey','Helene','Iris','Isidore',
              'Isabel','Ivan','Irene','Isaac','Jerry','Josephine','Juan','Jeanne','Jose','Joyce','Karen','Kyle','Kate','Karl','Katrina','Kirk','Lorenzo','Lili',
              'Larry','Lisa','Lee','Leslie','Michelle','Marco','Mindy','Maria','Michael','Noel','Nana','Nicholas','Nicole','Nate','Nadine','Olga','Omar','Odette',
              'Otto','Ophelia','Oscar','Pablo','Paloma','Peter','Paula','Philippe','Patty','Rebekah','Rene','Rose','Richard','Rita','Rafael','Sebastien','Sally',
              'Sam','Shary','Stan','Sandy','Tanya','Teddy','Teresa','Tomas','Tammy','Tony','Van','Vicky', 'Victor','Virginie','Vince','Valerie','Wendy','Wilfred',
              'Wanda','Walter','Wilma','William','Kumiko','Aki','Miharu','Chiaki','Michiyo','Itoe','Nanaho','Reina','Emi','Yumi','Ayumi','Kaori','Sayuri','Rie',
              'Miyuki','Hitomi','Naoko','Miwa','Etsuko','Akane','Kazuko','Miyako','Youko','Sachiko','Mieko','Toshie', 'Junko'];
    shuffle($specialityList);
    shuffle($namesList);
    $name = array_slice($namesList,0,1);
    $speciality = array_slice($specialityList,0,1);
    $player =$this->selectPlayer();
    if((is_object($player[0]))){
      $level = mt_rand(1,$player[0]->level);
    }
    else{
      $level = mt_rand(1,5);
    }
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->insert('drupal_rpg_character')
              ->fields([
              'uid' => $user->id(),
              'cid' => 'char_'.$speciality[0].'_'.mt_rand(1,999),
              'name' => $name[0],
              'speciality' => $speciality[0],
              'level' => $level,
              'salary' => 30000+$level*(mt_rand(1,6)*1000),
              'status' => 'market',
              'xp' => mt_rand(0,200)*$level,
              'xp_for_next_level' => $level*200,
              'health' => mt_rand(100,150)+$level*mt_rand(10,20),
              'speed' => mt_rand(1,20)/10,
              'skill' => mt_rand(5,20)+$level*mt_rand(1,5),
              'luck' => mt_rand(5,20)+$level*mt_rand(1,5),
              ]);
    $query->execute();
  }

  /**
   * @param Ticket $character
   * @throws \Exception
   */
  public function createTicket(Ticket $ticket) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->insert('drupal_rpg_ticket')
                      ->fields([
                        'uid' => $user->id(),
                        'tid' => $ticket->getId(),
                        'name' => $ticket->getName(),
                        'category' => $ticket->getCategory(),
                        'difficulty' => $ticket->getDifficulty(),
                        'character' => $ticket->getCharacter(),
                        'progression' => $ticket->getProgression(),
                      ]);
    $query->execute();
  }

  /**
   * @param Tester $tester
   * @throws \Exception
   */
  public function createTester(Tester $tester) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->insert('drupal_rpg_tester')
                      ->fields([
                        'uid' => $user->id(),
                        'tid' => $tester->getId(),
                        'name' => $tester->getName(),
                        'difficulty' => $tester->getDifficulty(),
                        'duration' => $tester->getDuration(),
                      ]);
    $query->execute();
  }

  /**
   * @param Client $client
   * @throws \Exception
   */
  public function createClient(Client $client) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->insert('drupal_rpg_client')
                      ->fields([
                        'uid' => $user->id(),
                        'cid' => $client->getId(),
                        'name' => $client->getName(),
                        'difficulty' => $client->getDifficulty(),
                        'needs' => $client->getNeeds(),
                      ]);
    $query->execute();
  }

  //SELECTORS

  public function selectPlayer(){
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->select('drupal_rpg_player', 'pla');
    $query->fields('pla');
    $query->condition('pla.uid', $user->id(), '=');

    return $query->execute()->fetchAll();
  }

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

  public function updatePlayer(Player $player) {
    $database = \Drupal::database();
    $query = $database->update('drupal_rpg_player')
      ->fields([
        'name' => $player->getName(),
        'company' => $player->getCompany(),
        'level' => $player->getLevel(),
        'xp' => $player->getXp(),
        'xp_for_next_level' => $player->getXpForNextLevel(),
        'money' => $player->getMoney(),
      ])
      ->condition('name', $player->getName(), '=');
    $query->execute();
  }



  public function updateCharacter(Character $character) {
    $database = \Drupal::database();
    $query = $database->update('drupal_rpg_character')
                      ->fields([
                        'name' => $character->getName(),
                        'speciality' => $character->getSpeciality(),
                        'level' => $character->getLevel(),
                        'salary' => $character->getSalary(),
                        'status' => $character->getStatus(),
                        'xp' => $character->getXp(),
                        'xp_for_next_level' => $character->getXpForNextLevel(),
                        'health' => $character->getHealth(),
                        'speed' => $character->getSpeed(),
                        'skill' => $character->getSkill(),
                        'luck' => $character->getLuck(),
                      ])
      ->condition('cid', $character->getId(), '=');
    $query->execute();
  }

  public function updateTicket(Ticket $ticket) {
    $database = \Drupal::database();
    $query = $database->update('drupal_rpg_ticket')
                      ->fields([
                        'name' => $ticket->getName(),
                        'category' => $ticket->getCategory(),
                        'difficulty' => $ticket->getDifficulty(),
                        'character' => $ticket->getCharacter(),
                        'progression' => $ticket->getProgression(),
      ])
      ->condition('tid', $ticket->getId(), '=');
    $query->execute();
  }

  public function updateTester(Tester $tester) {
    $database = \Drupal::database();
    $query = $database->update('drupal_rpg_tester')
                      ->fields([
                        'name' => $tester->getName(),
                        'difficulty' => $tester->getDifficulty(),
                        'duration' => $tester->getDuration(),
                      ])
      ->condition('cid', $tester->getId(), '=');
    $query->execute();
  }

  public function updateClient(Client $client) {
    $database = \Drupal::database();
    $query = $database->update('drupal_rpg_client')
                      ->fields([
                        'name' => $client->getName(),
                        'difficulty' => $client->getDifficulty(),
                        'needs' => $client->getNeeds(),
                      ])
      ->condition('cid', $client->getId(), '=');
    $query->execute();
  }

  //DELETERS

  function deleteCharacter(Character $character){
    $database = \Drupal::database();
    $query = $database->delete('drupal_rpg_character')
      ->condition('cid', $character->getId());
    $query->execute();
  }

  function deleteTicket(Ticket $ticket){
    $database = \Drupal::database();
    $query = $database->delete('drupal_rpg_ticket')
      ->condition('tid', $ticket->getId());
    $query->execute();
  }

  function deleteTester(Tester $tester){
    $database = \Drupal::database();
    $query = $database->delete('drupal_rpg_tester')
      ->condition('tid', $tester->getId());
    $query->execute();
  }

  function deleteClient(Client $client){
    $database = \Drupal::database();
    $query = $database->delete('drupal_rpg_client')
      ->condition('cid', $client->getId());
    $query->execute();
  }


}