<?php

/**
 * @file
 * Contains \Drupal\aes\Plugin\AES\Reverse.
 *
 * Sample scrambler created as an example of AES plugin.
 */

namespace Drupal\aes\Plugin\AES;

use Drupal\aes\Plugin\AESPluginBase;

/**
 * Class Reverse - sample cryptor plugin.
 *
 * @Cryptor(
 *   id = "aes_encrypt_reverse",
 *   label = @Translation("AES reverse 'encryption'"),
 *   description = @Translation("Sample AES encryption plugin."),
 * )
 *
 * @package Drupal\aes\Plugin\AES
 */
class Reverse extends AESPluginBase {

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct([], 'aes-reverse', []);
  }

  /**
   * {@inheritdoc}
   */
  public function encrypt($data, $key) {
    return '**' . $data;
  }

  /**
   * {@inheritdoc}
   */
  public function decrypt($data, $key) {
    return substr($data, 2);
  }

}
