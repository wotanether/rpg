<?php

namespace Drupal\drupal_rpg\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\drupal_rpg\Manager;

class GameController extends ControllerBase {

  public function content() {
    $recruitForm = \Drupal::formBuilder()->getForm('\Drupal\drupal_rpg\Form\RecruitForm');
    $fireForm = \Drupal::formBuilder()->getForm('\Drupal\drupal_rpg\Form\FireForm');

    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $rpg_container = $tempstore->get('rpg_container');
    //kint($rpg_container);

    $player = $rpg_container->getPlayer();
    $characters = $rpg_container->getCharacters();
    $tickets = $rpg_container->getTickets();
    $testers = $rpg_container->getTesters();
    $clients = $rpg_container->getClients();
    $charMarket = $rpg_container->getCharMarket();



    return [
      '#theme' => 'game',
      '#player' => $player,
      '#characters' => $characters,
      '#charMarket' => $charMarket,
      '#recruitForm' => $recruitForm,
      '#fireForm' => $fireForm,
      '#tickets' => $tickets,
      '#testers' => $testers,
      '#clients' => $clients,
    ];
  }
}