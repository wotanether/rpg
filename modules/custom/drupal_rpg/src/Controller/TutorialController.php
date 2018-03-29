<?php

namespace Drupal\drupal_rpg\Controller;

use Drupal\Core\Controller\ControllerBase;

class TutorialController extends ControllerBase {

  public function content() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\drupal_rpg\Form\NewPlayerForm');

    $chart['chart'] = [
      '#markup' => '<div id="line_charts_user"></div>',
    ];

    return [$chart,$form];
  }
}