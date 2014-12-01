<?php

namespace Drupal\aes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AesController extends ControllerBase {

  public static function create(ContainerInterface $container) {
    return new static($container->get('module_handler'));
  }

  public function samplePage() {
    $output = "---<br>";

    $output .= var_export(\Drupal::hasService('aes.crypt'), 1);
    $output .= "<br>";
    /** @var \Drupal\aes\AesCryptManager $srv */
    $srv = \Drupal::service('aes.crypt');

    $test = 'test';
    $output .= $test;
    $output .= $srv->encrypt($test);

    //$output .= var_export($srv->getPlugins(), 1);
    //$imp = $srv->createInstance('MCrypt');
    //$output .= var_export($srv, 1);

    $build = array(
      '#type' => 'markup',
      '#markup' => var_export($output, 1),
    );
    return $build;
  }
}
