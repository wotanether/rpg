<?php

namespace Drupal\drupal_rpg\Controller;

use Drupal\Core\Controller\ControllerBase;

class TutorialController extends ControllerBase {

  public function content() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\drupal_rpg\Form\NewPlayerForm');

    return [
      '#theme' => 'tutorial',
      '#form' => $form,
    ];

  }
}