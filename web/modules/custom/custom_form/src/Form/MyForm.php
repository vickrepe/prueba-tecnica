<?php

namespace Drupal\custom_form\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Cache\CacheBackendInterface;

class MyForm extends FormBase {

  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  public function getFormId() {
    /**
     * {@inheritdoc}
     */
    return 'custom_form_myform';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user'] = [
      '#type' => 'textfield',
      '#attributes' => ['placeholder' => 'Example: admin, victor'],
      '#title' => $this->t('User'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
    ];

    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $value = $form_state->getValue('user');
    $query = $this->database->query("SELECT COUNT(*) FROM {users_field_data} WHERE name = :name", [':name' => $value]);
    $count = $query->fetchField();
    if ($count > 0) {
      $this->messenger()->addStatus($this->t('El usuario: @user existe', ['@user' => $value]));
    } else {
      $this->messenger()->addError($this->t('El usuario: @user no existe', ['@user' => $value]));
    }

    $message = $this->messenger()->all();
    $cache = \Drupal::cache('default');
    $cache->set($value . '_message', $message);
    //var_dump($cache->get($value . '_message'));

    $form_state->setRedirect('entity.node.canonical', ['node' => 1], ['query' => ['message' => $this->messenger()->all()]]);
  }


  

}
