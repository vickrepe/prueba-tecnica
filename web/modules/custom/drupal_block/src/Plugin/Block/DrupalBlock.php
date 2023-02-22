<?php

namespace Drupal\drupal_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a block to display Site selector.
 *
 * @Block(
 *   id = "drupal_block",
 *   admin_label = @Translation("Drupal Block"),
 *   category = @Translation("Drupal Block"),
 * )
 */


class DrupalBlock extends BlockBase implements ContainerFactoryPluginInterface  {

    /**
     * {@inheritdoc }
     */
    protected $nodo;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }
    /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }


     public function build(){

      //$nodoa = $this->entityTypeManager->getStorage('node')->load(8);
      $nodo = $this->entityTypeManager->getStorage('node')->load(8);
      //$node = node_load(8);
        //$view = node_view($node, 'teaser');
        //$rendered = drupal_render($view);
        $view_builder = $this->entityTypeManager->getViewBuilder('node')->view($nodo, 'teaser');
        //$output = \Drupal::service('renderer')->renderRoot($build);
        //$nodeHtml = $output->__toString();
        //dpm($nodoa);
        return $view_builder;
     }
    //  private function getFrases(){
    //     $frase = [
    //         'Hola, que tal?',
    //         'Otra vez por aquí?',
    //         'Nos vemos pronto!'
    //     ];
    //     return $frase[array_rand($frase)];
    //  }

}