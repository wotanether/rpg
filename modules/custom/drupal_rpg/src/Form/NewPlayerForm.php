<?php
namespace Drupal\drupal_rpg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\drupal_rpg\Manager;
use Drupal\drupal_rpg\Container;
use Drupal\drupal_rpg\Client;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\drupal_rpg\Player;
use Drupal\node\Entity\Node;

class NewPlayerForm extends FormBase{

  /**
   * {@inheritdoc}.
   */
  public function getFormId(){
    return 'new_player_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['player_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('What\'s your name?'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#ajax' => [
        'callback'=> [$this,'validateTextAjax'],
        'event' => 'change',
      ],
      '#suffix' =>'<span class="text-message-player"></span>',
    ];

    $form['company_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('What\'s the name of your company?'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#ajax' => [
        'callback'=> [$this,'validateTextAjax'],
        'event' => 'change',
      ],
      '#suffix' =>'<span class="text-message-company"></span>',
    ];

    $form['new_player_confirm'] = [
      '#type' => 'submit',
      '#value' => $this->t('Ok'),
      '#ajax' => [
        'callback' => [$this, 'submitAjax'],
      ],
    ];

    return $form;

  }
  function validateTextAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $css = ['color'=>'red'];
    $display = ['display'=>'none'];
    if(strlen($form_state->getValue('player_name')) < 3 && !empty($form_state->getValue('player_name'))) {
      $messagePlayer = $this->t('Your name must be between 3 and 50 characters');
    }
    else if(!empty($form_state->getValue('player_name'))) {
      $statusPlayer = "ok";
    }

    if(strlen($form_state->getValue('company_name')) < 3 && !empty($form_state->getValue('company_name'))) {
      $messageCompany = $this->t('Your company name must be between 3 and 50 characters');
    }
    else if(!empty($form_state->getValue('company_name'))) {
      $statusCompany = "ok";
    }

    if($statusPlayer == "ok" && $statusCompany == "ok"){
      $display = ['display'=>'inline-block'];
    }


    $response-> addCommand(new CssCommand('.text-message-player, .text-message-company',$css));
    $response-> addCommand(new HtmlCommand('.text-message-player',$messagePlayer));
    $response-> addCommand(new HtmlCommand('.text-message-company',$messageCompany));
    $response-> addCommand(new CssCommand('#edit-new-player-confirm',$display));

    return $response;
  }

  function submitAjax(array &$form, FormStateInterface $form_state){
    if(strlen($form_state->getValue('player_name')) >= 3 && strlen($form_state->getValue('company_name')) >= 3) {
      $response = new AjaxResponse();
      $response->addCommand(new InvokeCommand(NULL,'newPlayer'));
      $response->addCommand(new ReplaceCommand('#new-player',""));
    }
    return $response;
  }
  /**
   * {@inheritdoc}.
   */
  public function SubmitForm(array &$form, FormStateInterface $form_state){
    if(strlen($form_state->getValue('player_name')) >= 3 && strlen($form_state->getValue('company_name')) >= 3) {
      $user = \Drupal::currentUser();
      $manager = new Manager();
      $name = $form_state->getValue('player_name');
      $company = $form_state->getValue('company_name');
      $player = new Player($name,$company, 1, 1, 300, 50000);
      $manager->createPlayer($player);
      for($i=1; $i <= 20; $i++) {
        $manager->randomCharacter();
      }
      $manager->initUserData($user->id());
    }
  }

}

