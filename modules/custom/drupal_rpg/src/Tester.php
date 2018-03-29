<?php

namespace Drupal\drupal_rpg;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

class Tester{
  use StringTranslationTrait;

  Private $_id, //unique id of the tester
          $_name, // the name of the tester
          $_difficulty, // determine the chance to generate a ticket per turn and their minimum difficulty
          $_duration; // number of turn the tester remains

  public function __construct($id, $name, $difficulty, $duration) {

    $this->setId($id);
    $this->setName($name);
    $this->setDifficulty($difficulty);
    $this->setDuration($duration);

  }

  //GETTERS
  public function id() {
    return $this->_id;
  }

  public function name() {
    return $this->_name;
  }

	public function duration() {
    return $this->_duration;
  }

	public function difficulty() {
    return $this->_difficulty;
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

	public function setDuration($duration) {
    $duration = (int) $duration;

    if ($duration >= 0)  {
      $this->_duration = $duration;
    }
  }

  public function setDifficulty($difficulty) {
    $difficulty = (int) $difficulty;

    if ($difficulty > 0 && $difficulty <= 10)  {
      $this->_difficulty = $difficulty;
    }
  }

  public function createTicket(){

    $category_list = [$this->t('frontend'),
                     $this->t('backend'),
                     $this->t('projectManager'),
                     $this->t('graphist'),
                     $this->t('general'),
                     ];

    $prefix_list = [$this->t('awsome'),
                   $this->t('beautiful'),
                   $this->t('chaotic'),
                   $this->t('cool'),
                   $this->t('dangerous'),
                   $this->t('disgusting'),
                   $this->t('evil'),
                   $this->t('fabulous'),
                   $this->t('fucking'),
                   $this->t('hard'),
                   $this->t('hopeless'),
                   $this->t('impossible'),
                   $this->t('incredible'),
                   $this->t('ridiculous'),
                   $this->t('super'),
                   ];

    $suffix_list = [$this->t('chaos'),
                    $this->t('danger'),
                    $this->t('freedom'),
                    $this->t('hell'),
                    $this->t('hopelessness'),
                    $this->t('scepticism'),
                    $this->t('the light'),
                    $this->t('the truth'),
                    $this->t('the unknown'),
                    $this->t('trial'),
                    ];

    if ($this->difficulty() >=1 && $this->difficulty() < 4){
      $ticketDifficulty = mt_rand(1,4);
    }
    else if ($this->difficulty() >=4 && $this->difficulty() < 7){
      $ticketDifficulty = mt_rand(3,9);
    }

    else if ($this->difficulty() >=7 && $this->difficulty() <=10){
      $ticketDifficulty = mt_rand(5,10);
    }

    $id = $this->name() . '_' . mt_rand(0,99999);
    $category = $category_list[mt_rand(0, count($category_list) - 1)];
    $prefix = $prefix_list[mt_rand(0, count($prefix_list) - 1)];
    $suffix = $suffix_list[mt_rand(0, count($suffix_list) - 1)];
    $name =$this->t('@prefix @category ticket of @suffix', array('@prefix' => $prefix, '@category' => $category, '@suffix' => $suffix));


    return new Ticket($id, $name, $category, $ticketDifficulty);

  }

}