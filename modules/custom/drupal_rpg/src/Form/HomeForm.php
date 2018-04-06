<?php
namespace Drupal\drupal_rpg\Form;

use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\drupal_rpg\Manager;
use Drupal\drupal_rpg\Container;
use Drupal\drupal_rpg\Client;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\node\Entity\Node;

class HomeForm extends FormBase{

  /**
   * {@inheritdoc}.
   */
  public function getFormId(){
    return 'home_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();
    $query = $database->select('drupal_rpg_player', 'play')
                       ->fields('play')
                       ->condition('play.uid', $user->id(), '=');

    $player = $query->execute()->fetchAll();

    //kint($player);
    if(!empty($player)){
      $form['new_game'] = [
        '#type' => 'submit',
        '#value' => $this->t('New game'),
        '#attributes' => array('data-reveal-id' => 'new-game-confirm'),
        '#submit' => array([$this,'NewGameSubmitForm']),
      ];

      $form['continue'] = [
        '#type' => 'submit',
        '#value' => $this->t('Continue'),
        '#submit' => array([$this,'ContinueSubmitForm']),
      ];
    }
    else{
      $form['new_game_first'] = [
        '#type' => 'submit',
        '#value' => $this->t('New game'),
        '#submit' => array([$this,'NewGameSubmitForm']),
      ];
    }

    return $form;

  }


  /**
   * {@inheritdoc}.
   */
  public function NewGameSubmitForm(array &$form, FormStateInterface $form_state){
    $user = \Drupal::currentUser();
    $manager = new Manager();
    $manager->deleteUserData($user->id());
    $form_state->setRedirect('drupal_rpg.tutorial');
  }

  /**
   * {@inheritdoc}.
   */
  public function ContinueSubmitForm(array &$form, FormStateInterface $form_state){
    //faire un select de tous les objets liés au jour et les mettre dans le container
    $user = \Drupal::currentUser();
    $manager = new Manager();
    $manager->initUserData($user->id());
    $form_state->setRedirect('drupal_rpg.game');
  }

  /**
   * {@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}

