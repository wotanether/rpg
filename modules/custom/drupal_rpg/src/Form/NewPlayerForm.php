<?php
namespace Drupal\drupal_rpg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\drupal_rpg\Manager;
use Drupal\drupal_rpg\Container;
use Drupal\drupal_rpg\Client;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\node\Entity\Node;

class NewPlayerForm extends FormBase{

  /**
   * {@inheritdoc}.
   */
  public function getFormId(){
    return 'new_player';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['player_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('What\'s your name?'),
    ];

    $form['company_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('What\'s the name of your company?'),
    ];

    $form['confirm'] = [
      '#type' => 'submit',
      '#value' => $this->t('Ok'),
    ];

    return $form;

  }

  /**
   * {@inheritdoc}.
   */
  public function SubmitForm(array &$form, FormStateInterface $form_state){
    $user = \Drupal::currentUser();
    $database = \Drupal::database();

    $query = $database->insert('drupal_rpg_player')
                      ->fields([
                        'uid' => $user->id(),
                        'name' => $form_state->getValue('player_name'),
                        'company' => $form_state->getValue('company_name'),
                        'level' => 1,
                        'xp' => 0,
                        'xp_for_next_level' => 300,
                        'money' => '50000',
                      ])
                      ->execute();
  }

}

