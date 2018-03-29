<?php

namespace Drupal\drupal_rpg\Controller;

use Drupal\Core\Controller\ControllerBase;

class GameController extends ControllerBase {

  public function content() {
    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $rpg_container = $tempstore->get('rpg_container');
    $chart['chart'] = [
      '#markup' => '<div id="line_charts_user"></div>',
    ];

    return [$chart];
  }
}