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

class FireForm extends FormBase{

  /**
   * {@inheritdoc}.
   */
  public function getFormId(){
    return 'fire_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $tempstore = \Drupal::service('user.private_tempstore')->get('drupal_rpg');
    $rpg_container = $tempstore->get('rpg_container');

    foreach ($rpg_container->getCharacters() as $character){
      $form['fire_'.$character->getId()] = [
        '#type' => 'submit',
        '#value' => $this->t('Fire'),
      ];
    }

    return $form;

  }

  /**
   * {@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
}

