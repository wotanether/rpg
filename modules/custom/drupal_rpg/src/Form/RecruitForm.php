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

class RecruitForm extends FormBase{

  /**
   * {@inheritdoc}.
   */
  public function getFormId(){
    return 'recruit_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $rpg_container = $tempstore->get('rpg_container');

    foreach ($rpg_container->getCharMarket() as $character){
      $form['char_id'] = [
        '#type' => 'hidden',
        '#value' => $character->getId(),
      ];
      $form['recruit_'.$character->getId()] = [
        '#type' => 'submit',
        '#value' => $this->t('Recruit'),
        '#ajax' => [
          'callback' => [$this, 'submitAjax'],
        ],
      ];
    }

    return $form;

  }

  public function submitAjax(array &$form, FormStateInterface $form_state) {


    $response = new AjaxResponse();
    $response-> addCommand(new HtmlCommand('.market'.$form_state->getValue('char_id'),''));
    return $response;
  }

  /**
   * {@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $manager = New Manager();
    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $rpg_container = $tempstore->get('rpg_container');
    $character = $rpg_container->selectCharMarket($form_state->getValue('char_id'));

    $character->setStatus('active');

    $rpg_container->removeFromCharMarket($character);
    $rpg_container->addCharacter($character);
    $tempstore->set('rpg_container', $rpg_container);
    $manager->updateCharacter($character);
  }
}

