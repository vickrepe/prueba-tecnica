<?php

namespace Drupal\drupal_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\seg_kit\SecurityKit;
use Drupal\Component\Utility\Xss;


class Formulario extends FormBase{

    protected $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container ->get('database')
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getFormId(){
        return 'drupal_block_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form ['name']=[
            '#type' => 'textfield',
            '#title' => $this->t('name'),
        ];
        $form ['lastname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('lastname'),
        ];
        $form ['sexo'] = [
            '#type' => 'select',
            '#title' => $this->t('Selecciona una opción'),
            '#options' => [
                'hombre' => $this->t('hombre'),
                'mujer' => $this->t('mujer')
            ]
        ];
        $form ['email'] = [
            '#type' => 'email',
            '#title' => $this ->t('Ingresa tu e-mail'),
            '#required' => TRUE,
        ];
        $form ['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Send'),
        ];

        //$form['#validate'][] = '::validateForm';


        $form['captcha'] = [  '#type' => 'captcha'];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $name = $form_state->getValue('name');
        if (strpos(strtolower($name), 'victor') !== false) {
            $form_state->setErrorByName('name', $this->t('No se permite la palabra "victor" en el campo "Name".'));
        }
        // // Validate the CAPTCHA.
        //  $captcha_response = \Drupal::service('captcha.validation')->validate($form_state->getValue('captcha'));
        //  if ($captcha_response !== TRUE) {
        //      // Handle invalid CAPTCHA.
        //      $form_state->setErrorByName('captcha', $this->t('Invalid CAPTCHA, please try again'));
        //      return;
        //  }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();
        $fields = [
            'name' => Xss::filter($values['name']),
            'lastname' => Xss::filter($values['lastname']),
            'sexo' => Xss::filter($values['sexo']),
            'email' => Xss::filter($values['email']),
        ];
        //$form_state->setRedirectUrl(Url::fromUserInput('/search/'.$form_state->getValue('keyword')));
        //$form_state->setRedirectUrl('user_list', ['form_name' => $form_state->getValue('name')]);
        $this->connection->insert('custom_table')
        ->fields($fields)
            ->execute();
        $form_state->setRedirect('drupal_block.content');
    }
    
    public function getTitle()
    {
        $title = \Drupal::config('drupal_block.settings')->get('drupal_block.message');
        return $title;
    }
}