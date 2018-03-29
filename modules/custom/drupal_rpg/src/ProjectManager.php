<?php

namespace Drupal\drupal_rpg;

use Drupal\drupal_rpg\Character;
use Drupal\drupal_rpg\Ticket;
use Drupal\drupal_rpg\Container;

class ProjectManager extends Character {

  public function attachTicket(Character $character, Ticket $ticket){
    if ($character->status() == 'active' && $ticket->progression() < 100){
      $character->addTickets($ticket);
      $ticket->setCharacter($character);
    }
  }

  public function detachTicket(Container $container){
      Foreach($container->tickets() as $ticket){
        if($ticket->progression() >= 100){
          $characterId = $ticket->character()->id();
          $ticket->setCharacter(null);
          unset($container->characters()[$characterId]->tickets()[$ticket->id()]);
          $container->removeTicket($ticket);

        }

      }
  }

}