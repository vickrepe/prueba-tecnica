<?php


namespace Drupal\drupal_block\Controller;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Query\PagerSelectExtender;
use Drupal\Core\Url;

class DrupalBlockController extends ControllerBase
{
  protected $connection;

  public function __construct(Connection $connection)
  {
    $this->connection = $connection;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database')
    );
  }

  public function mymodule()
  { 
    
    $header = [
    'Name' => [
      'data' => $this->t('name'),
      'field' => 't.name',
      //'sort' => 'desc',
    ],
    'lastname' => [
      'data' => $this->t('lastname'),
      'field' => 't.lastname',
      //'order' => 'asc',
    ],
    'sexo' => [
      'data' => $this->t('sexo'),
      'field' => 't.sexo',
      //'order' => 'asc',
    ],
    'email' => [
      'data' => $this->t('email'),
      'field' => 't.email',
      //'order' => 'asc',
    ],
  ];

    $database = \Drupal::database();
    /**
     * @var TableSortExtender $query
     */
    $query = $database->select('custom_table', 't')
      ->extend('Drupal\Core\Database\Query\TableSortExtender');
    $query->fields('t', ['name', 'lastname', 'email','sexo']);

    
    
    $query->orderByHeader($header);


    $data = $query->execute()->fetchAll();

    $datacount = $query->countQuery()->execute()->fetchField();

    $num_per_page = 5;
    $pager = \Drupal::service('pager.manager')
      ->createPager($datacount, $num_per_page);
    $page = $pager
      ->getCurrentPage();

    $offset = $num_per_page * $page;
    $query->range($offset, $num_per_page);
    $data = $query->execute()->fetchAll();

    foreach ($data as $row) {
      $rows[] = [
        'name' => $row->name,
        'lastname' => $row->lastname,
        'sexo' => $row->sexo,
        'email' => $row->email,
      ];
    }

    $render = [];
    $render['tablesort_table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
    $render[] = [
      '#type' => 'pager',
    ];
    return $render;
  }

}
