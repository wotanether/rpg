<?php

namespace Drupal\drupal_rpg\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with the New player form.
 *
 * @Block(
 *  id = "new_player_block",
 *  admin_label = @Translation("New player block")
 * )
 */
class NewPlayerBlock extends BlockBase {

  public function build() {
    return \Drupal::formBuilder()->getForm('\Drupal\drupal_rpg\Form\NewPlayerForm');
  }

}