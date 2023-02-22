<?php

namespace Drupal\custom_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "my_custom_block",
 *   admin_label = @Translation("My custom block")
 * )
 */
class DataBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
        return [
            '#markup' => $this->t('¡Hola, mundo! Este es mi bloque personalizado.'),
          ];
    }
  }