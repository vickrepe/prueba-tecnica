<?php

namespace Drupal\form_user_search\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for form_user_search routes.
 */
class FormUserSearchController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
