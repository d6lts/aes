<?php
/**
 * @file
 * Contains \Drupal\aes\Plugin\AESPluginBase.
 */

namespace Drupal\aes\Plugin;

//use Drupal\Core\Entity\DependencyTrait;
//use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
//use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class for all cryptor plugins.
 */
abstract class AESPluginBase extends PluginBase {
  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  abstract public function encrypt($data, $key);
  abstract public function decrypt($data, $key);

}
