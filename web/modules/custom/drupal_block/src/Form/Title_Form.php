<?php

namespace Drupal\drupal_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DrupalBlockSettingsForm.
 */
class Title_Form extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'drupal_block.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drupal_block_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('drupal_block.settings');

    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message'),
      '#description' => $this->t('Ingrese el título.'),
      '#default_value' => $config->get('message'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('drupal_block.settings')
      ->set('drupal_block.message', $form_state->getValue('message'))
      ->save();
      //dpm($form_state->getValue('message'));
  }

}
