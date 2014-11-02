<?php
/**
 * Created by PhpStorm.
 * User: dennis
 * Date: 2/11/14
 * Time: 12:03 PM
 */

namespace Drupal\aes\Plugin;

use Drupal\Core\Config\Config;

class AesCryptManager {
  public function __construct(Config $config) {
    var_dump($config);
  }
} 